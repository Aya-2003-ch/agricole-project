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
    Route::get('/eleveur/search', [EleveurController::class, 'search'])->name('eleveur.search');
    Route::post('/eleveur/update-location', [EleveurController::class, 'updateLocation'])->name('eleveur.updateLocation');
    Route::get('/veterinaire/search-medicines', [App\Http\Controllers\VeterinaireController::class, 'searchMedicines'])->name('veterinaire.searchMedicines');

    //  Redirector
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

    //Veterinaire 
    Route::prefix('veterinaire')->name('veterinaire.')->group(function () {
        Route::get('/dashboard', [VeterinaireController::class, 'dashboard'])->name('dashboard');
        Route::get('/consultations', [VeterinaireController::class, 'consultations'])->name('consultations');
        Route::get('/profile', [VeterinaireController::class, 'profile'])->name('profile');
        Route::get('/chats', [VeterinaireController::class, 'chats'])->name('chats');
        

        // البحث عن الأدوية عند الموزعين  
       
        Route::post('/order/place', [VeterinaireController::class, 'placeOrder'])->name('order.place');
        Route::get('/my-orders', [VeterinaireController::class, 'myOrders'])->name('commandes'); // عرض طلبات البيطري المرسلة للموزع

        //  التبليغ عن الأوبئة 
        Route::get('/report', [VeterinaireController::class, 'report'])->name('report');
        Route::post('/report/send', [VeterinaireController::class, 'sendReport'])->name('report.send');

        // تحديث حالة الاستشارة (عندما ينتهي الطبيب من فحص حالة الفلاح)
        Route::post('/consultations/{id}/status', [VeterinaireController::class, 'updateStatus'])->name('updateStatus');
        
        
    });

    //  Eleveur 
    Route::prefix('eleveur')->name('eleveur.')->group(function () {
        Route::get('/dashboard', [EleveurController::class, 'dashboard'])->name('dashboard');
        Route::post('/store', [EleveurController::class, 'store'])->name('store');
        
    });

    //(Distributeur) 
    Route::prefix('distributeur')->name('distributeur.')->group(function () {
        Route::get('/dashboard', [DistributeurController::class, 'dashboard'])->name('dashboard');
        Route::get('/profile', [DistributeurController::class, 'profile'])->name('profile');
        Route::post('/distributeur/profile/update', [DistributeurController::class, 'updateProfile'])->name('distributeur.profile.update');
        // الموزع هو من يملك صلاحية إضافة المنتجات (store)
        Route::post('/products/store', [DistributeurController::class, 'store'])->name('store');
    });

    //  الإدارة العامة للحساب 
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('consultation', ConsultationController::class);
    Route::resource('produits', ProduitController::class);
    Route::get('/produit/{id}', [ProduitController::class, 'show'])->name('produits.show');
});

require __DIR__.'/auth.php';