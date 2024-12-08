<?php

use App\Http\Controllers\ProduitController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('produits/create', [ProduitController::class, 'create'])->name('produits.create');
    Route::post('produits', [ProduitController::class, 'store'])->name('produits.store');
    Route::get('produits', [ProduitController::class, 'index'])->name('produits.index');
    Route::get('produits/{produit}', [ProduitController::class, 'show'])->name('produits.show');
    Route::get('produits/{produit}/edit', [ProduitController::class, 'edit'])->name('produits.edit');
    Route::put('produits/{produit}', [ProduitController::class, 'update'])->name('produits.update');
    Route::delete('produits/{produit}', [ProduitController::class, 'destroy'])->name('produits.destroy');
});
