<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProduitAgriController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\VeterinaireController;
use App\Http\Controllers\FermeController;
use App\Http\Controllers\DistributeurController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\ProfileController;

// 1. الصفحات العامة
Route::get('/', function () { return view('welcome'); });
Route::get('/home', function () { return view('home'); });
Route::get('/contact', function () { return view('contact'); })->name('contact');

// 2. الروابط المحمية (لازم تسجيل دخول)
Route::middleware(['auth'])->group(function () {
    
    // صفحة الداشبورد العامة (اختيارية)
    Route::get('/dashboard', function () { return view('dashboard'); })->name('dashboard');

    // داشبورد الطبيب البيطري
    Route::get('/veterinaire/dashboard', [VeterinaireController::class, 'dashboard'])->name('vet.dashboard');
    Route::get('/veterinaire/consultations', [VeterinaireController::class, 'consultations'])->name('veterinaire.consultations');

    // داشبورد الفلاح
    Route::get('/ferme/dashboard', [FermeController::class, 'dashboard'])->name('farmer.dashboard');

    // داشبورد الموزع
    Route::get('/distributeur/dashboard', [DistributeurController::class, 'dashboard'])->name('distrib.dashboard');

    // البروفايل (خدمة آية)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // إدارة المنتجات والاستشارات (Resources)
    Route::resource('produit_agris', ProduitAgriController::class);
    Route::resource('consultation', ConsultationController::class);
    Route::get('/produit', [ProduitController::class, 'index'])->name('produits.index');
});

require __DIR__.'/auth.php';