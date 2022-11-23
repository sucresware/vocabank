<?php

namespace App\Providers;

use App\Models\Sample;
use App\Models\StaticPage;
use App\Models\Tag;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        if (config('app.env') === 'production') {
            \URL::forceScheme('https');
            $this->app['request']->server->set('HTTPS', 'on');
        }

        Carbon::setLocale(config('app.locale'));
        setlocale(LC_TIME, config('app.locale'));

        $runtime = round((microtime(true) - LARAVEL_START), 3);

        $version = 'WIP';

        try {
            $version = 'v' . file_get_contents(config_path('.version'));
        } catch (\Exception $e) {
        }

        View::composer('layouts.app', function ($view) use ($runtime, $version) {
            $view
                ->with('static_pages', StaticPage::orderBy('name')->get())
                ->with('runtime', $runtime)
                ->with('version', $version);

            return $view;
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        //
    }
}
