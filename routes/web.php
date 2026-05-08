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
    MessageController,
    CommandeController,
    AdminController
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

    // الـ Redirector المطور: يوجه المستخدم للوحة التحكم الخاصة به حسب دوره
    Route::get('/dashboard', function () {
        $user = auth()->user();
        
        // توجيه الأدمن
        if ($user->role == 'admin') {
            return redirect()->route('admin.panel');
        }
        
        // توجيه الفلاح
        if (in_array($user->role, ['eleveur', 'فلاح'])) {
            return redirect()->route('eleveur.dashboard');
        } 
        
        // توجيه البيطري
        if (in_array($user->role, ['veterinaire', 'بيطرى', 'بيطري'])) {
            return redirect()->route('veterinaire.dashboard');
        }
        
        // توجيه الموزع
        if (in_array($user->role, ['distributeur', 'موزع'])) {
            return redirect()->route('distributeur.dashboard');
        }
        
        return view('dashboard'); 
    })->name('dashboard');

    // --- قسم المدير (Admin Panel) ---
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/panel', [AdminController::class, 'index'])->name('panel');
        Route::get('/delete/{id}', [AdminController::class, 'delete'])->name('user.delete');
    });

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
        
        Route::get('/consultations', [ConsultationController::class, 'indexVet'])->name('consultations');
        Route::post('/consultations/{id}/status', [ConsultationController::class, 'updateStatus'])->name('consultations.status');
        Route::put('/consultations/{id}', [ConsultationController::class, 'update'])->name('consultations.update');

        Route::get('/api/medicines/suggestions', [VeterinaireController::class, 'getSuggestions'])->name('api.suggestions');
        Route::get('/market', [VeterinaireController::class, 'market'])->name('market');
        Route::post('/order/store', [VeterinaireController::class, 'storeOrder'])->name('order.store');
        Route::get('/mes-commandes', [VeterinaireController::class, 'myOrders'])->name('my_orders');

        Route::get('/report', [VeterinaireController::class, 'report'])->name('report');
        Route::post('/report/send', [VeterinaireController::class, 'sendReport'])->name('report.send');
        Route::get('/chats', [MessageController::class, 'index'])->name('chats');
    });

    // --- قسم الموزع (Distributeur) ---
    Route::prefix('distributeur')->name('distributeur.')->group(function () {
        Route::get('/dashboard', [DistributeurController::class, 'dashboard'])->name('dashboard');
        Route::get('/profile', [DistributeurController::class, 'profile'])->name('profile');
        Route::post('/profile/update', [DistributeurController::class, 'updateProfile'])->name('profile.update');
        
        Route::post('/products/store', [DistributeurController::class, 'store'])->name('store');
        Route::get('/marche', [DistributeurController::class, 'market'])->name('market');
        Route::post('/order', [DistributeurController::class, 'storeOrder'])->name('market.store');
        Route::get('/commandes-recues', [DistributeurController::class, 'incomingOrders'])->name('incoming.orders');
        Route::get('/mes-commandes', [DistributeurController::class, 'myOrders'])->name('my.orders');
        
        Route::patch('/order/{order}/accept', [DistributeurController::class, 'acceptOrder'])->name('order.accept');
        Route::patch('/order/{order}/reject', [DistributeurController::class, 'rejectOrder'])->name('order.reject');
        
        Route::get('/suggestions', [DistributeurController::class, 'getProductSuggestions'])->name('suggestions');
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