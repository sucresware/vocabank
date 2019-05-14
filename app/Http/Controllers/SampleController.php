<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Sample;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class SampleController extends Controller
{
    public function index()
    {
        $recent_samples = Sample::orderBy('created_at', 'DESC')->limit(5)->get();
        $popular_samples = Sample::orderByViews()->limit(5)->get();

        return view('sample.index', compact('recent_samples', 'popular_samples'));
    }

    public function recent()
    {
        $samples = Sample::orderBy('created_at', 'DESC')->paginate(10);

        return view('sample.index', compact('samples'));
    }

    public function popular()
    {
        $samples = Sample::orderByViews()->paginate(10);

        return view('sample.index', compact('samples'));
    }

    public function search(Request $request)
    {
        if ($request->q) {
            $q = explode(' ', $request->q);
            $samples = Sample::query();

            foreach ($q as $q_) {
                $samples = $samples->whereHas('tags', function ($query) use ($q_) {
                    return $query->where('name', 'like', $q_ . '%');
                });
            }

            $samples = $samples->paginate(10);

            return view('sample.index', compact('samples'))->with('q', $request->q);
        } else {
            return view('sample.index');
        }
    }

    public function random()
    {
        $sample = Sample::limit(1)->inRandomOrder()->first();

        return redirect()->route('samples.show', $sample);
    }

    public function create()
    {
        return view('sample.create');
    }

    public function store(Request $request)
    {
        // $request->merge(['tags', Helper::stripAccents($request->tags)]);

        $validator = \Validator::make($request->all(), [
            'name' => ['required', 'min:3', 'max:255'],
            'vocaroo_link' => ['required', 'regex:' . '/http(?:s|):\/\/vocaroo.com\/i\/((?:\w|-)*)/m'],
            'tags' => [
                'required', 'regex:' . '/^(?:[a-zA-Z0-9]| )+$/m',
                function ($attribute, $value, $fail) {
                    $tags = explode(' ', $value);
                    $tags = array_unique($tags);
                    if (count($tags) < 3) {
                        $fail('Tu dois spécifier au moins 3 tags!');
                    }
                    if (count($tags) > 10) {
                        $fail('Maximum 10 tags !');
                    }
                },
            ],
            'thumbnail' => ['image'],
        ]);
        $validator->validate();

        $sample = new Sample([
            'name' => $request->name,
            'vocaroo_link' => $request->vocaroo_link,
        ]);
        $sample->user()->associate(auth()->user());
        $sample->save();

        if ($request->hasFile('thumbnail')) {
            $thumbnail_name = $sample->id . '_thumbnail' . time() . '.' . request()->thumbnail->getClientOriginalExtension();
            $thumbnail_path = storage_path('app/temp/' . $thumbnail_name);

            Image::make(request()->thumbnail)->fit(300)->save($thumbnail_path);
            $imgur = \Imgur::upload(File::get($thumbnail_path));
            $sample->thumbnail_link = $imgur->link();

            File::delete($thumbnail_path);
        } else {
            $sample->thumbnail_link = '/img/default.png';
        }

        $sample->save();

        foreach (explode(' ', $request->tags) as $tag) {
            Tag::firstOrCreate([
                'name' => $tag,
            ])->samples()->attach($sample);
        }

        return redirect()->route('samples.show', $sample);
    }

    public function show(Sample $sample)
    {
        return view('sample.show', compact('sample'));
    }

    public function iframe(Sample $sample)
    {
        return view('sample.iframe', compact('sample'));
    }

    public function edit($id)
    { }

    public function update(Request $request, $id)
    { }

    public function destroy($id)
    { }

    public function listen(Sample $sample)
    {
        $url = 'http://vocaroo.com/media_command.php?media=' . $sample->vocaroo_id . '&command=download_mp3';

        $media_name = $sample->id . '_vocaroo_' . $sample->vocaroo_id . '.mp3';
        $media_path = storage_path('app/temp/' . $media_name);
        if (!File::exists($media_path)) {
            File::put($media_path, file_get_contents($url));
        }

        return response()->file($media_path);

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
}
