<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    ProduitController,
    VeterinaireController,
    EleveurController,
    DistributeurController,
    ConsultationController,
    ProfileController,
    RechercheController,
    DashboardController,
    MessageController
};

// --- 1. الصفحات العامة (الجميع يمكنه الوصول إليها) ---
Route::get('/', function () { return view('welcome'); });
Route::get('/home', function () { return view('home'); })->name('home');
Route::get('/contact', function () { return view('contact'); })->name('contact');

Route::get('/search', [RechercheController::class, 'Search'])->name('search');
Route::get('/live-search', [DashboardController::class, 'search']);
Route::get('/nearby-distributeurs', [DashboardController::class, 'nearby']);

// --- 2. الروابط المحمية (تحتاج تسجيل دخول) ---
Route::middleware(['auth'])->group(function () {

    // الـ Redirector: يوجه المستخدم للوحة التحكم الخاصة به حسب دوره (Role)
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

    // --- قسم الفلاح (Eleveur) ---
    Route::prefix('eleveur')->name('eleveur.')->group(function () {
        Route::get('/dashboard', [EleveurController::class, 'dashboard'])->name('dashboard');
        Route::post('/update-location', [EleveurController::class, 'updateLocation'])->name('updateLocation');
        Route::get('/search-medicine', [EleveurController::class, 'search'])->name('search');

        // الاستشارات الخاصة بالفلاح
        Route::get('/consultations', [ConsultationController::class, 'index'])->name('consultations');
        Route::post('/consultations/store', [ConsultationController::class, 'store'])->name('consultations.store');
        Route::get('/nearby-vets', [ConsultationController::class, 'getNearbyVets'])->name('nearby.vets');

        // المحادثات (الفلاح مع البيطري)
        Route::get('/chats/{receiver_id?}', [MessageController::class, 'index'])->name('chats');
        Route::post('/messages/send', [MessageController::class, 'send'])->name('messages.send');
    });

    // --- قسم البيطري (Veterinaire) ---
    Route::prefix('veterinaire')->name('veterinaire.')->group(function () {
        Route::get('/dashboard', [VeterinaireController::class, 'dashboard'])->name('dashboard');
        Route::get('/profile', [VeterinaireController::class, 'profile'])->name('profile');
        
        // إدارة الاستشارات الواصلة للبيطري
        Route::get('/consultations', [ConsultationController::class, 'indexVet'])->name('consultations');
        Route::post('/consultations/{id}/status', [VeterinaireController::class, 'updateStatus'])->name('updateStatus');

        // طلب الأدوية من الموزعين
        Route::get('/search-medicines', [VeterinaireController::class, 'searchMedicines'])->name('searchMedicines');
        Route::post('/order/place', [VeterinaireController::class, 'placeOrder'])->name('order.place');
        Route::get('/my-orders', [VeterinaireController::class, 'myOrders'])->name('commandes');

        // التبليغ عن الأوبئة
        Route::get('/report', [VeterinaireController::class, 'report'])->name('report');
        Route::post('/report/send', [VeterinaireController::class, 'sendReport'])->name('report.send');

        // المحادثات (البيطري مع الفلاح)
        Route::get('/chats', [MessageController::class, 'index'])->name('chats');
    });

    // --- قسم الموزع (Distributeur) ---
    Route::prefix('distributeur')->name('distributeur.')->group(function () {
        Route::get('/dashboard', [DistributeurController::class, 'dashboard'])->name('dashboard');
        Route::get('/profile', [DistributeurController::class, 'profile'])->name('profile');
        Route::post('/profile/update', [DistributeurController::class, 'updateProfile'])->name('profile.update');
        
        // إدارة المخزن والمنتجات
        Route::post('/products/store', [DistributeurController::class, 'store'])->name('store');
    });

    // --- إدارة الملف الشخصي العامة ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- الموارد العامة ---
    Route::resource('produits', ProduitController::class);
    Route::get('/produit/{id}', [ProduitController::class, 'show'])->name('produits.show');
});

require __DIR__.'/auth.php';