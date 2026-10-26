<?php

namespace App\Providers;

use App\Models\Service;
use App\Support\GymContact;
use App\Support\SiteSettings;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        SiteSettings::apply();

        View::share('gymContact', new GymContact());

        View::composer('layouts.app', function ($view) {
            $view->with('navServices', rescue(fn () => Service::visible()->get(['id', 'title', 'slug', 'icon']), collect(), false));
        });

        Blade::directive('icon', fn ($expression) => "<?php echo \\App\\Support\\SiteHtml::icon({$expression}); ?>");
        Blade::directive('img', fn ($expression) => "<?php echo \\App\\Support\\SiteHtml::img({$expression}); ?>");
    }
}
