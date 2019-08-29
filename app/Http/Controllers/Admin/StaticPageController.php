<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StaticPage;
use Illuminate\Http\Request;

class StaticPageController extends Controller
{
    public function index()
    {
        $static_pages = StaticPage::get();

        return view('admin.static_page.index', compact('static_pages'));
    }

    public function create()
    {
        return view('admin.static_page.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'    => 'required|max:255',
            'slug'    => 'required|max:255',
            'content' => 'required',
        ]);

        StaticPage::create($request->input());

        return redirect()->route('admin.static-pages.index');
    }

    public function edit(StaticPage $static_page)
    {
        return view('admin.static_page.edit', compact('static_page'));
    }

    public function update(Request $request, StaticPage $static_page)
    {
        $request->validate([
            'name'    => 'required|max:255',
            'slug'    => 'required|max:255',
            'content' => 'required',
        ]);

        $static_page->update($request->input());

        return redirect()->route('admin.static-pages.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int  StaticPage $static_page
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(StaticPage $static_page)
    {
        $static_page->delete();

        return redirect()->route('admin.static-pages.index');
    }
}
