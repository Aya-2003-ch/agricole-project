<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\CommandePolicy;
use Illuminate\Support\StorePolicy;

class AppServiceProvider extends ServiceProvider
{
    protected $policies=[
        Store::class=>StorePolicy::class,
        Commande::class=>Commande::class,
    ];
     
        
    

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
    public function boot()
{
    view()->composer('*', function ($view) {
        // نحسب التقارير المرسلة في آخر 24 ساعة مثلاً أو غير المقروءة
        $unreadReportsCount = \App\Models\RapportEpidemie::where('created_at', '>=', now()->subDays(2))->count();
        $view->with('unreadReportsCount', $unreadReportsCount);
    });
}
   
}
