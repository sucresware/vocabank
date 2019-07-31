<?php

namespace App\Providers;

use App\Auth\FourSucresProvider;
use Illuminate\Support\Carbon;
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
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        $socialite = $this->app->make('Laravel\Socialite\Contracts\Factory');
        $socialite->extend(
        'foursucres',
        function ($app) use ($socialite) {
            $config = $app['config']['services.foursucres'];

            return $socialite->buildProvider(FourSucresProvider::class, $config);
        }
    );
    }
}
