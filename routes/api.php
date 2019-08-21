<?php

use App\Models\Sample;
use App\Models\User;
use Illuminate\Http\Request;

Route::get('/samples', function () {
    $samples = Sample::with('user')->public();

    switch (request()->input('order', 'recent')) {
        case 'recent':
            $samples = $samples->orderBy('created_at', 'DESC');

            break;
        case 'popular':
            $samples = $samples->orderByViews();

        break;
    }

    return $samples->paginate(30);
});

Route::get('/samples/search', function (Request $request) {
    $request->validate([
        'q' => 'required',
    ]);

    $samples = Sample::with('user')->public();

    if (!$request->tag) {
        $samples = $samples
            ->whereHas('tags', function ($query) use ($request) { return $query->where('name', 'like', '%' . $request->q . '%'); })
            ->orWhere('name', 'like', '%' . $request->q . '%')
            ->orWhere('description', 'like', '%' . $request->q . '%');
    } else {
        $samples = $samples->whereHas('tags', function ($query) use ($request) { return $query->where('name', $request->q); });
    }

    return $samples->paginate(30);
});

Route::get('/samples/{sample}', function (Sample $sample) {
    return Sample::findOrFail($sample);
});

Route::get('/users', function () {
    return User::orderBy('created_at', 'ASC')->paginate(30);
});

Route::get('/users/{user}', function (User $user) {
    return User::findOrFail($user);
});
