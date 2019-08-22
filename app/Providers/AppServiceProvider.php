<?php

namespace App\Providers;

use App\Models\Tag;
use App\Models\Sample;
use App\Models\StaticPage;
use Illuminate\Support\Carbon;
use App\Auth\FourSucresProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
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
        }

        Carbon::setLocale(config('app.locale'));
        setlocale(LC_TIME, config('app.locale'));

        View::composer('layouts.app', function ($view) {
            $view->with('popular_tags', Cache::remember('popular_tags', now()->addMinute(), function () {
                return Tag::join('sample_tag', 'tags.id', '=', 'sample_tag.tag_id')
                    ->join('samples', 'samples.id', '=', 'sample_tag.sample_id')
                    ->where('status', Sample::STATUS_PUBLIC)
                    ->groupBy('tags.id')
                    ->select(['tags.*', DB::raw('COUNT(*) as count')])
                    ->orderBy('count', 'desc')
                    ->limit(10)
                    ->get();
            }));

            $view->with('static_pages', Cache::remember('static_pages', now()->addMinute(), function () {
                return StaticPage::orderBy('name')->get();
            }));

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
