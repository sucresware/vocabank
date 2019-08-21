<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    public function index()
    {
        return redirect()->route('samples.index');
    }

    public function lightToggler()
    {
        switch (auth()->user()->getSetting('layout.theme', 'theme-vocabank')) {
            case 'theme-vocabank':
                auth()->user()->setSetting('layout.theme', 'theme-legacy');

            break;
            case 'theme-legacy':
                auth()->user()->setSetting('layout.theme', 'theme-vocabank');

            break;
        }

        return ['success' => true];
    }
}
