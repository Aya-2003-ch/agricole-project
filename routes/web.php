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

Route::get('/live-search', [DashboardController::class, 'search']);
Route::get('/nearby-distributeurs', [DashboardController::class, 'nearby']);
Route::get('/notifications', [DashboardController::class, 'notifications']);
Route::get('/search', [RechercheController::class, 'Search'])->name('search');

// 2. الروابط المحمية
Route::middleware(['auth'])->group(function () {

    // التوجيه الذكي
    Route::get('/dashboard', function () {
        $user = auth()->user();
        if (in_array($user->role, ['eleveur', 'فلاح'])) {
            return redirect()->route('eleveur.dashboard');
        } 
        if (in_array($user->role, ['veterinaire', 'بيطرى', 'بيطري'])) {
            return redirect()->route('veterinaire.dashboard');
        }
        if (in_array($user->role, ['distributeur', 'موزع'])) {
            return redirect()->route('distributeur.dashboard');
        }
        return view('dashboard'); 
    })->name('dashboard');

    // --- قسم الطبيب البيطري (Veterinaire) ---
    Route::prefix('veterinaire')->name('veterinaire.')->group(function () {
        Route::get('/dashboard', [VeterinaireController::class, 'dashboard'])->name('dashboard');
        Route::get('/consultations', [VeterinaireController::class, 'consultations'])->name('consultations');
        Route::get('/profile', [VeterinaireController::class, 'profile'])->name('profile');
        
        // إدارة الأدوية والطلبات
        Route::get('/commandes', [VeterinaireController::class, 'orders'])->name('commandes');
        Route::get('/medicines', [VeterinaireController::class, 'medicines'])->name('medicines');
        Route::get('/chats', [VeterinaireController::class, 'chats'])->name('chats');

        // أفعال الأدوية (CRUD)
        Route::get('/produits/create', [VeterinaireController::class, 'createProduit'])->name('produit.create');
        Route::post('/produits/store', [VeterinaireController::class, 'storeProduit'])->name('produit.store');
        Route::get('/produits/edit/{id}', [VeterinaireController::class, 'editProduit'])->name('produit.edit');
        Route::put('/produits/update/{id}', [VeterinaireController::class, 'updateProduit'])->name('produit.update');
        Route::delete('/produits/destroy/{id}', [VeterinaireController::class, 'destroyProduit'])->name('produit.destroy');

        // تحديث حالة الطلب
        Route::post('/orders/{id}/status', [VeterinaireController::class, 'updateStatus'])->name('updateStatus');
    });

    // --- قسم الفلاح (Eleveur) ---
    Route::prefix('eleveur')->name('eleveur.')->group(function () {
        Route::get('/dashboard', [EleveurController::class, 'dashboard'])->name('dashboard');
        Route::post('/store', [EleveurController::class, 'store'])->name('store');
    });

    // --- قسم الموزع (Distributeur) ---
    Route::prefix('distributeur')->name('distributeur.')->group(function () {
        Route::get('/dashboard', [DistributeurController::class, 'dashboard'])->name('dashboard');
        Route::get('/profile', [DistributeurController::class, 'profile'])->name('profile');
        Route::post('/store', [DistributeurController::class, 'store'])->name('store');
    });

    // --- الإدارة العامة ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('consultation', ConsultationController::class);
    Route::resource('produit', ProduitController::class);
    Route::get('/produit/{id}', [ProduitController::class, 'show'])->name('produits.show');
});

require __DIR__.'/auth.php';