<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Laravel\Horizon\Horizon;
use Laravel\Horizon\HorizonApplicationServiceProvider;

class HorizonServiceProvider extends HorizonApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        parent::boot();

        if (app()->isProduction()) {
            Horizon::routeMailNotificationsTo('gmauriciocd@gmail.com');
            // Horizon::routeSmsNotificationsTo('15556667777');
            // Horizon::routeSlackNotificationsTo('slack-webhook-url', '#channel');
        }

    }

    /**
     * Register the Horizon gate.
     *
     * This gate determines who can access Horizon in non-local environments.
     */
    protected function gate(): void
    {
        Gate::define('viewHorizon', function ($user = null) {
            return in_array(optional($user)->email, [
                'gmauriciocd@gmail.com',
                'admav@alquilavende.com'
            ]);
        });
    }
}
