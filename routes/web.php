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

Route::get('/veterinaire', function () {
    return view('welcome');
});

Route::get('/homev', function () {
    return view('dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
