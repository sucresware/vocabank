<?php

namespace App\Http\Controllers;

use App\Jobs\ConvertToMP3Job;
use App\Jobs\GenerateWaveformJob;
use App\Models\Sample;
use App\Models\Tag;
use FFMpeg\FFMpeg;
use FFMpeg\FFProbe;
use FFMpeg\Format\Audio\Mp3;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Spatie\Regex\Regex;
use YoutubeDl\Exception\CopyrightException;
use YoutubeDl\Exception\NotFoundException;
use YoutubeDl\Exception\PrivateVideoException;
use YoutubeDl\YoutubeDl;

class SampleController extends Controller
{
    public function index()
    {
        return redirect()->route('samples.recent');
    }

    public function recent()
    {
        $samples = Sample::with('user')->public()->orderBy('created_at', 'DESC')->paginate(15);

        if (request()->ajax()) {
            return $samples;
        }

        return view('sample.index', compact('samples'))->with('filter', 'recent');
    }

    public function popular()
    {
        $samples = Sample::with('user')->public()->orderByViews()->paginate(15);

        if (request()->ajax()) {
            return $samples;
        }

        return view('sample.index', compact('samples'))->with('filter', 'popular');
    }

    public function search(Request $request)
    {
        if ($request->q) {
            $samples = Sample::with('user')
                ->public();

            $samples = $samples->whereHas('tags', function ($query) use ($request) {
                return $query->where('name', 'like', '%' . $request->q . '%');
            });

            if (!$request->tag) {
                $samples = $samples
                    ->orWhere('name', 'like', '%' . $request->q . '%')
                    ->orWhere('description', 'like', '%' . $request->q . '%');
            }

            $samples = $samples->paginate(15);

            return view('sample.index', compact('samples'))->with('q', $request->q);
        } else {
            return view('sample.index');
        }
    }

    public function random()
    {
        $sample = Sample::public()->limit(1)->inRandomOrder()->first();

        return redirect()->route('samples.show', $sample);
    }

    public function create()
    {
        return view('sample.create');
    }

    public function createURL()
    {
        return view('sample.create_url');
    }

    public function show(Sample $sample)
    {
        abort_if(
            ($sample->status != Sample::STATUS_PUBLIC) &&
            ($sample->user != auth()->user()), 403);

        return view('sample.show', compact('sample'));
    }

    public function next(Sample $sample)
    {
        $next_sample = $sample->next;

        if ($next_sample) {
            return redirect()->route('samples.show', $next_sample);
        }

        return redirect()->route('home');
    }

    public function prev(Sample $sample)
    {
        $prev_sample = $sample->prev;

        if ($prev_sample) {
            return redirect()->route('samples.show', $prev_sample);
        }

        return redirect()->route('home');
    }

    public function iframe(Sample $sample)
    {
        $sample->user; // Preload

        return view('sample.iframe', compact('sample'));
    }

    public function listen(Sample $sample)
    {
        views($sample)
            ->delayInSession(1)
            ->record();

        return response()->file(Storage::path($sample->audio));
    }

    public function download(Sample $sample)
    {
        return response()->download(Storage::path($sample->audio));
    }

    public function preflight()
    {
        request()->validate([
            'audio' => ['required', 'file', 'max:10240', 'mimetypes:audio/mpeg,audio/mp3,audio/wav,audio/ogg'],
        ]);

        $sample = auth()->user()->samples()->create(
            ['name' => request()->file('audio')->getClientOriginalName()]
        );

        $audio_name = $sample->id . '_audio_' . time() . '.' . request()->audio->getClientOriginalExtension();
        $storage_path = request()->file('audio')->storeAs('temp', $audio_name, 'local');
        $sample->audio = $storage_path;
        $sample->save();

        ConvertToMP3Job::withChain([
            new GenerateWaveformJob($sample->id),
        ])->dispatch($sample->id);

        return Sample::findOrFail($sample->id);
    }

    public function preflightURL()
    {
        request()->validate([
            'youtubeURL' => ['required'],
        ]);

        $pattern = '/http(?:s?):\/\/(?:www\.)?youtu(?:be\.com\/watch\?v=|\.be\/)([\w\-\_]*)(&(amp;)?‌​[\w\?‌​=]*)?/m';
        $match = Regex::match($pattern, request()->youtubeURL);

        if (!$match->hasMatch()) {
            return response()->json([
                'errors' => [
                    'youtubeURL' => [
                        'Le format du lien est invalide',
                    ],
                ],
            ], 422);
        }

        try {
            $client = new Client();
            $res = $client->get('https://www.googleapis.com/youtube/v3/videos?id=' . $match->group(1) . '&key=' . config('services.youtube.key') . '&part=snippet,contentDetails');

            $data = json_decode($res->getBody()->getContents());
            $duration = now()->add($data->items[0]->contentDetails->duration)->diffInSeconds();

            if ($duration > 300) {
                return response()->json([
                    'errors' => [
                        'youtubeURL' => [
                            'La durée de la vidéo doit être inférieure à 5 minutes',
                        ],
                    ],
                ], 422);
            }

            $sample = auth()->user()->samples()->create([
                'name'          => $data->items[0]->snippet->title,
                'youtube_video' => [
                    'id'            => $data->items[0]->id,
                    'title'         => $data->items[0]->snippet->title,
                    'author_name'   => $data->items[0]->snippet->channelTitle,
                    'thumbnail_url' => $data->items[0]->snippet->thumbnails->maxres->url ?? $data->items[0]->snippet->thumbnails->default->url,
                    'duration'      => $duration,
                ],
            ]);

            return $sample;
        } catch (\Exception $e) {
            return response()->json([
                'errors' => [
                    'youtubeURL' => [
                        // 'Impossible de récupérer les informations de la vidéo',
                        $e->getMessage(),
                    ],
                ],
            ], 422);
        }
    }

    public function processURL(Sample $sample)
    {
        $audio_name = $sample->id . '_url_' . time() . '.mp3';

        $dl = new YoutubeDl([
            'extract-audio' => true,
            'audio-format'  => 'mp3',
            'audio-quality' => 0, // best
            'output'        => $audio_name,
        ]);
        $dl->setDownloadPath(storage_path('app/temp'));

        try {
            $dl->download('https://www.youtube.com/watch?v=' . $sample->youtube_video['id']);
        } catch (NotFoundException $e) {
            return response(['Video not found']);
        } catch (PrivateVideoException $e) {
            return response(['Video is private']);
        } catch (CopyrightException $e) {
            return response(['The URL account associated with this video has been terminated due to multiple third-party notifications of copyright infringement']);
        } catch (\Exception $e) {
            return response([$e->getMessage()]);
        }

        $sample->audio = 'temp/' . $audio_name;

        $waveform_name = $sample->id . '_waveform_' . time() . '.png';

        $ffmpeg = FFMpeg::create();
        $audio = $ffmpeg->open(Storage::path($sample->audio));
        $audio->save((new Mp3()), storage_path('app/temp' . $audio_name));

        $ffprobe = FFProbe::create();
        $sample->duration = $ffprobe
            ->format(storage_path('app/temp/' . $audio_name)) // extracts file informations
            ->get('duration');             // returns the duration property

        $waveform = $audio->waveform(1920, 128, ['#a0aec0']);
        $waveform->save(Storage::path('public/samples/' . $waveform_name));
        $sample->waveform = 'samples/' . $waveform_name;

        $sample->save();

        return $sample;
    }

    public function edit(Sample $sample)
    {
        abort_if($sample->user != auth()->user(), 403);

        $tags = $sample->tags->pluck('name'); // Preload

        return view('sample.edit', compact('sample', 'tags'));
    }

    public function update(Sample $sample)
    {
        request()->validate([
            'name'      => ['required', 'min:3', 'max:60', 'unique:samples,name,' . $sample->id],
            'tags'      => ['nullable', 'array'],
            'thumbnail' => ['nullable', 'mimes:jpeg,bmp,png,jpg', 'max:2048'],
        ]);

        $sample->name = request()->name;
        $sample->description = request()->description;

        $thumbnail_name = $sample->id . '_thumbnail_' . time() . '.jpg';
        if (request()->hasFile('thumbnail')) {
            Image::make(request()->thumbnail)->fit(300, 300)->save(Storage::path('public/samples/' . $thumbnail_name));
            $sample->thumbnail = 'samples/' . $thumbnail_name;
        } elseif (isset($sample->youtube_video['thumbnail_url'])) {
            Image::make(file_get_contents($sample->youtube_video['thumbnail_url']))->fit(300, 300)->save(Storage::path('public/samples/' . $thumbnail_name));
            $sample->thumbnail = 'samples/' . $thumbnail_name;
        }

        $sample->tags()->detach();
        foreach (request()->tags ?? [] as $tag) {
            Tag::firstOrCreate(['name' => $tag])->samples()->attach($sample);
        }

        $sample->save();

        return $sample;
    }
}
