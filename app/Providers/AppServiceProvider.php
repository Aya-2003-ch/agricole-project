<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Store;
use App\Models\Commande;
use App\Policies\StorePolicy;
use App\Policies\CommandePolicy;

class AppServiceProvider extends ServiceProvider
{
    /**
     * سياسات الصلاحيات (Policies) للمشروع.
     * تم تصحيح المسارات (Namespaces) لتشير إلى مجلد Models و Policies الصحيح.
     */
    protected $policies = [
        Store::class => StorePolicy::class,
        Commande::class => CommandePolicy::class, // تم تصحيح ربط السياسة هنا
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
    public function boot(): void
    {
    
    }
}
