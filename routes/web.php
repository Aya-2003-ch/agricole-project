<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\VeterinaireController;
use App\Http\Controllers\FermeController;
use App\Http\Controllers\DistributeurController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\ProfileController;


// 1. الصفحات العامة
Route::get('/', function () { return view('welcome'); });
Route::get('/home', function () { return view('home'); })->name('home');
Route::get('/contact', function () { return view('contact'); })->name('contact');
Route::resource('produits', ProduitController::class);
// 2. الروابط المحمية (لازم تسجيل دخول)
Route::middleware(['auth'])->group(function () {
    Route::get('/distributeur/dashboard', [DistributeurController::class, 'dashboard']);
    Route::resource('produits', ProduitController::class);
    
    Route::get('/check-role', function () {
        return "User Role is: " . auth()->user()->role;
    });

    // التوجيه الذكي (العقل تاع السيستيم اللي يفرق بين المستخدمين)
    Route::get('/dashboard', function () {
        $user = auth()->user();

        if ($user->role == 'farmer' || $user->role == 'فلاح') {
            return redirect()->route('ferme.dashboard');
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

    // داشبورد الفلاح
    Route::get('/ferme/dashboard', [FermeController::class, 'dashboard'])->name('ferme.dashboard');

    // داشبورد الموزع
    Route::get('/distributeur/dashboard', [DistributeurController::class, 'dashboard'])->name('distributeur.dashboard');
    Route::get('distributeur/profile', [DistributeurController::class, 'profile'])->name('distributeur.profile');

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