<?php

namespace App\Providers;

use App\Auth\FourSucresProvider;
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

        View::composer('layouts.app', function ($view) {
            $view
                ->with('popular_tags', Cache::remember('popular_tags', now()->addMinute(), function () {
                    return Tag::join('sample_tag', 'tags.id', '=', 'sample_tag.tag_id')
                        ->join('samples', 'samples.id', '=', 'sample_tag.sample_id')
                        ->where('status', Sample::STATUS_PUBLIC)
                        ->groupBy('tags.id')
                        ->select(['tags.*', DB::raw('COUNT(*) as count')])
                        ->orderBy('count', 'desc')
                        ->limit(10)
                        ->get();
                }))
                ->with('static_pages', StaticPage::orderBy('name')->get())
                ->with('runtime', round((microtime(true) - LARAVEL_START), 3));

            return $view;
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        $socialite = $this->app->make('Laravel\Socialite\Contracts\Factory');
        $socialite->extend('foursucres', function ($app) use ($socialite) {
            $config = $app['config']['services.foursucres'];

            return $socialite->buildProvider(FourSucresProvider::class, $config);
        }
    );
    }
}
