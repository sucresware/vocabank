<?php

namespace App\Http\Controllers;

use App\Models\Sample;
use App\Models\Tag;
use BoyHagemann\Waveform\Generator\Svg;
use BoyHagemann\Waveform\Waveform;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use maximal\audio\Waveform as SoX;
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
        $samples = Sample::public()->orderBy('created_at', 'DESC')->paginate(15);

        if (request()->ajax()) {
            return $samples;
        }

        return view('sample.index', compact('samples'))->with('filter', 'recent');
    }

    public function popular()
    {
        $samples = Sample::public()->orderByViews()->paginate(15);

        if (request()->ajax()) {
            return $samples;
        }

        return view('sample.index', compact('samples'))->with('filter', 'popular');
    }

    public function search(Request $request)
    {
        if ($request->q) {
            $q = explode(' ', $request->q);
            $samples = Sample::public();

            foreach ($q as $q_) {
                $samples = $samples->whereHas('tags', function ($query) use ($q_) {
                    return $query->where('name', 'like', $q_ . '%');
                });
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

    public function show(Sample $sample)
    {
        if ($sample->status != Sample::STATUS_PUBLIC) {
            abort(403);
        }

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

    public function store(Request $request)
    {
        request()->validate([
            'id'        => ['required'],
            'name'      => ['required', 'min:3', 'max:60', 'unique:samples,name'],
            'tags'      => ['required', 'array'],
            'thumbnail' => ['nullable', 'mimes:jpeg,bmp,png,jpg', 'max:2048'],
        ]);

        $sample = Sample::findOrFail(request()->id);

        if ($sample->status != Sample::STATUS_DRAFT);

        $sample->name = $request->name;
        $sample->description = $request->description;

        if ($request->hasFile('thumbnail')) {
            $thumbnail_name = $sample->id . '_thumbnail_' . time() . '.jpg';
            Image::make($request->thumbnail)->fit(300, 300)->save(Storage::path('public/samples/' . $thumbnail_name));
            $sample->thumbnail = 'samples/' . $thumbnail_name;
        }

        // TODO: Move audio from temp storage to remote
        // $audio_name = $sample->id . '_audio_' . time() . '.' . request()->audio->getClientOriginalExtension();
        // $sample->audio = request()->audio->storeAs('samples', $audio_name);

        foreach ($request->tags as $tag) {
            Tag::firstOrCreate(['name' => $tag])->samples()->attach($sample);
        }

        $sample->status = Sample::STATUS_PUBLIC;
        $sample->save();

        return $sample;
    }

    public function iframe(Sample $sample)
    {
        return view('sample.iframe', compact('sample'));
    }

    public function edit($id)
    {
    }

    public function update(Request $request, $id)
    {
    }

    public function destroy($id)
    {
    }

    public function listen(Sample $sample)
    {
        views($sample)
            ->delayInSession(1)
            ->record();

        return response()->file(Storage::path($sample->audio));

        // $headers = get_headers($url, 1);
        // $length = array_reverse(array_sort($headers['Content-Length']))[0];

        // return response()->stream(function () use ($url) {
        //     $stream = fopen($url, 'r');
        //     fpassthru($stream);
        //     if (is_resource($stream)) {
        //         fclose($stream);
        //     }
        // }, 200, [
        //     'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
        //     'Content-Type' => 'audio/mpeg3',
        //     'Content-Length' => $length,
        //     'Content-Disposition' => $headers['Content-Disposition'],
        //     'Pragma' => 'public',
        // ]);
    }

    public function download(Sample $sample)
    {
        $url = 'http://vocaroo.com/media_command.php?media=' . $sample->vocaroo_id . '&command=download_mp3';

        $media_name = $sample->id . '_vocaroo_' . $sample->vocaroo_id . '.mp3';
        $media_path = storage_path('app/temp/' . $media_name);
        if (!File::exists($media_path)) {
            File::put($media_path, file_get_contents($url));
        }

        return response()->download($media_path);

        // $url = 'http://vocaroo.com/media_command.php?media=' . $sample->vocaroo_id . '&command=download_mp3';
        // $headers = get_headers($url, 1);
        // $length = array_reverse(array_sort($headers['Content-Length']))[0];

        // return response()->stream(function () use ($url) {
        //     $stream = fopen($url, 'r');
        //     fpassthru($stream);
        //     if (is_resource($stream)) {
        //         fclose($stream);
        //     }
        // }, 200, [
        //     'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
        //     'Content-Type' => 'application/force-download',
        //     'Content-Length' => $length,
        //     'Content-Disposition' => trim($headers['Content-Disposition'], ':'),
        //     'Pragma' => 'public',
        // ]);
    }

    public function preflight()
    {
        request()->validate([
            'audio' => ['required', 'file', 'max:10240', 'mimetypes:audio/mpeg,audio/mp3'],
        ]);

        $sample = auth()->user()->samples()->create(
            ['name' => request()->file('audio')->getClientOriginalName()]
        );

        $audio_name = $sample->id . '_audio_' . time() . '.' . request()->audio->getClientOriginalExtension();
        $storage_path = request()->file('audio')->storeAs('temp', $audio_name, 'local');

        $sample->audio = $storage_path;

        try {
            $waveform_name = $sample->id . '_waveform_' . time() . '.png';
            $waveform_temp_path = storage_path('app/temp/' . $waveform_name);
            $waveform = new SoX(Storage::path($sample->audio));
            $height = 512;
            SoX::$backgroundColor = [255, 255, 255, 0];
            $waveform->getWaveform($waveform_temp_path, 2048, $height, true);
            if ($waveform->getChannels() > 1) {
                Image::make($waveform_temp_path)->crop(2048, $height / 2, 0, 0)->save(Storage::path('public/samples/' . $waveform_name));
            } else {
                Image::make($waveform_temp_path)->resize(2048, $height / 2, 0, 0)->save(Storage::path('public/samples/' . $waveform_name));
            }
            $sample->waveform = 'samples/' . $waveform_name;
            File::delete($waveform_temp_path);
            // TODO: Store waveform to remote storage
        } catch (\Exception $e) {
        }

        $sample->save();

        return $sample;
    }

    public function preflightYouTube()
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
                    'thumbnail_url' => $data->items[0]->snippet->thumbnails->maxres->url,
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

    public function processYouTube(Sample $sample)
    {
        $audio_name = $sample->id . '_youtube_' . time() . '.mp3';

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
            return response(['The YouTube account associated with this video has been terminated due to multiple third-party notifications of copyright infringement']);
        } catch (\Exception $e) {
            return response([$e->getMessage()]);
        }

        $sample->audio = 'temp/' . $audio_name;

        try {
            $waveform_name = $sample->id . '_waveform_' . time() . '.svg';
            $waveform_temp_path = storage_path('app/temp/' . $waveform_name);

            $waveform = Waveform::fromFilename(Storage::path($sample->audio));
            $waveform->setGenerator(new Svg())
                    ->setWidth(2048)
                    ->setHeight(512);

            $waveform_svg = $waveform->generate();
            file_put_contents($waveform_temp_path, $waveform_svg);
            $sample->waveform = 'samples/' . $waveform_name;
            // File::delete($waveform_temp_path);
            // TODO: Store waveform to remote storage
        } catch (\Exception $e) {
            dd($e->getMessage());

            return response([$e->getMessage()]);
        }

        $sample->save();

        return $sample;
    }
}
