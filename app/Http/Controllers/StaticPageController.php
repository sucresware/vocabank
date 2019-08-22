<?php

namespace App\Http\Controllers;

use App\Models\StaticPage;

class StaticPageController extends Controller
{
    public function show($slug)
    {
        $static_page = StaticPage::where('slug', $slug)->firstOrFail();
        return view('static_page', compact('static_page'));
    }
}
