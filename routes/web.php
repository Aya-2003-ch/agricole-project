<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProduitAgriController;
use App\Http\Controllers\VeterinaireController;
use App\Http\Controllers\FermeController;
use App\Http\Controllers\DistributeurController;
Route::get('/produit-agri', [ProduitAgriController::class, 'index'])->name('produit_agri.index');
Route::post('/produit-agri', [ProduitAgriController::class, 'store'])->name('produit_agri.store');
Route::get('/produit-agri/{id}/edit', [ProduitAgriController::class, 'edit'])->name('produit_agri.edit');
Route::put('/produit-agri/{id}', [ProduitAgriController::class, 'update'])->name('produit_agri.update');
Route::delete('/produit-agri/{id}', [ProduitAgriController::class, 'destroy'])->name('produit_agri.destroy');
Route::get('/veterinaire/dashboard', [VeterinaireController::class, 'dashboard']);
Route::get('/veterinaire/consultations', [VeterinaireController::class, 'consultations']);
Route::get('/ferme/dashboard', [FermeController::class, 'dashboard']);
Route::get('/distributeur/dashboard', [DistributeurController::class, 'dashboard']);

// route
Route::resource('produit_agris', ProduitAgriController::class);

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/veterinaire', function () {
    return view('welcome');
});

Route::get('/home', function () {
    return view('home');
});
Route::get('/contact', function () {
    return view('contact');
})->name('contact');
// routes/web.php

// صفحات Dashboard حسب الـ role
Route::get('/dashboard/ferme', function () {
    return view('ferme.dashboard');
})->name('ferme.dashboard')->middleware('auth');
    

Route::get('/dashboard/veterinaire', function () {
    return view('veterinaire.dashboard');
})->name('veterinaire.dashboard')->middleware('auth');

Route::get('/dashboard/distributeur', function () {
    return view('distributeur.dashboard');
})->name('distributeur.dashboard')->middleware('auth');


// Produits
Route::resource('produit_agris', ProduitAgriController::class);

// Profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';