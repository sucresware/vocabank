<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sample;
use Illuminate\Http\Request;

class SampleController extends Controller
{
    public function index()
    {
        $samples = Sample::with('user')->public();

        switch (request()->input('order', 'recent')) {
            case 'recent':
                $samples = $samples->orderBy('created_at', 'DESC');

                break;
        }

        return $samples->paginate(30);
    }

    public function search(Request $request)
    {
        $request->validate([
            'q' => 'required',
        ]);

        $samples = Sample::with('user')->public();

        if (! $request->tag) {
            $samples = $samples
                ->whereHas('tags', function ($query) use ($request) {
                    return $query->where('name', 'like', '%' . $request->q . '%');
                })
                ->orWhere('name', 'like', '%' . $request->q . '%')
                ->orWhere('description', 'like', '%' . $request->q . '%');
        } else {
            $samples = $samples->whereHas('tags', function ($query) use ($request) {
                return $query->where('name', $request->q);
            });
        }

        return $samples->paginate(30);
    }

    public function show(Sample $sample)
    {
        return $sample;
    }
}
