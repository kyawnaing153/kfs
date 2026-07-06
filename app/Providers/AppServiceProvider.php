<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Http\ViewComposers\GeneralSettingComposer;
use App\Models\Frontend\Quotation;

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
        View::composer('*', GeneralSettingComposer::class);

        View::composer('layouts.sidebar', function ($view) {

            $menuCounts = [
                '/admin/quotation/customer' => Quotation::where('status', 'submitted')->count(),
            ];

            $view->with('menuCounts', $menuCounts);
        });
    }
}
