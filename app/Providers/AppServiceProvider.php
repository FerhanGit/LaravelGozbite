<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Inject variables into views
        return $this->injectViewParams();
    }


    private function injectViewParams()
    {
        // Inject variables into views
        view()->composer('*', function($view) {
            $language = 'FR';

            $addParams = [];
            $addParams['language'] = $language;

            if ('recipe.list' == $view->getName()) {
                $locale = 'fr_FR';
                $addParams['locale'] = $locale;
            }

            return $view->with($addParams);
        });
    }
}
