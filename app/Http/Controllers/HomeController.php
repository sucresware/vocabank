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
        $user = auth()->user();
        $user->disableLogging();

        switch ($user->getSetting('layout.theme', 'theme-vocabank')) {
            case 'theme-vocabank':
                $user->setSetting('layout.theme', 'theme-legacy');

            break;
            case 'theme-legacy':
                $user->setSetting('layout.theme', 'theme-vocabank');

            break;
        }

        return ['success' => true];
    }
}
