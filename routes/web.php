<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\VeterinaireController;
use App\Http\Controllers\EleveurController;
use App\Http\Controllers\DistributeurController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RechercheController;
use App\Http\Controllers\DashboardController;


// 1. الصفحات العامة
Route::get('/', function () { return view('welcome'); });
Route::get('/home', function () { return view('home'); })->name('home');
Route::get('/contact', function () { return view('contact'); })->name('contact');
Route::resource('produits', ProduitController::class);
Route::get('/search', [RechercheController::class, 'Search'])->name('search');
Route::get('/live-search', [DashboardController::class, 'search']);
Route::get('/nearby-distributeurs', [DashboardController::class, 'nearby']);
Route::get('/notifications', [DashboardController::class, 'notifications']);
// 2. الروابط المحمية (لازم تسجيل دخول)
Route::middleware(['auth'])->group(function () {
    Route::get('/distributeur/dashboard', [DistributeurController::class, 'dashboard']);
    Route::resource('produits', ProduitController::class);
    
    Route::get('/check-role', function () {
        return "User Role is: " . auth()->user()->role;
    });
    Route::middleware(['auth'])->group(function () {

    Route::get('/veterinaire/consultations', [ConsultationController::class, 'indexVet']);

    Route::post('/consultation', [ConsultationController::class, 'store']);

    Route::post('/consultation/{id}', [ConsultationController::class, 'update']);
});

    // التوجيه الذكي (العقل تاع السيستيم اللي يفرق بين المستخدمين)
    Route::get('/dashboard', function () {
        $user = auth()->user();

        if ($user->role == 'eleveur' || $user->role == 'فلاح') {
            return redirect()->route('eleveur.dashboard');
        } 

        if ($user->role == 'veterinaire' || $user->role == 'بيطرى' || $user->role == 'بيطري') {
            return redirect()->route('veterinaire.dashboard');
        }

        if ($user->role == 'distributeur' || $user->role == 'موزع') {
            return redirect()->route('distributeur.dashboard');
        }

        return view('dashboard'); 
    })->name('dashboard');

    // داشبورد الطبيب البيطري
    Route::get('/veterinaire/dashboard', [VeterinaireController::class, 'dashboard'])->name('veterinaire.dashboard');
    Route::get('/veterinaire/consultations', [VeterinaireController::class, 'consultations'])->name('veterinaire.consultations');
    Route::get('/veterinaire/profile', [VeterinaireController::class, 'profile'])->name('veterinaire.profile');
     Route::post('/veterinaire/store', [VeterinaireController::class, 'store'])
    ->name('veterinaire.store');
    Route::get('/produit/{id}', [ProduitController::class, 'show'])->name('produits.show');

    // داشبورد الفلاح
    Route::get('/eleveur/dashboard', [EleveurController::class, 'dashboard'])->name('eleveur.dashboard');
// الطريق لحفظ معلومات المزرعة مع الخريطة
Route::post('/eleveur/store', [EleveurController::class, 'store'])->name('eleveur.store');
    // داشبورد الموزع
    Route::get('/distributeur/dashboard', [DistributeurController::class, 'dashboard'])->name('distributeur.dashboard');
    Route::get('distributeur/profile', [DistributeurController::class, 'profile'])->name('distributeur.profile');
    Route::post('/distributeur/store', [DistributeurController::class, 'store'])
    ->name('distributeur.store');

    // البروفايل (خدمة آية)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // إدارة المنتجات والاستشارات (Resources)
    Route::resource('produit', ProduitController::class);
    Route::resource('consultation', ConsultationController::class);
    Route::get('/produit', [ProduitController::class, 'index'])->name('produits.index');
});

require __DIR__.'/auth.php';