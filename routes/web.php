<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

// Home page
Route::get('/', function () {
    return view('welcome');
});

// Show all products
Route::get('/products', [ProductController::class, 'index'])->name('products.index');

// Show the form to create a new product
Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');

// Store a new product
Route::post('/products', [ProductController::class, 'store'])->name('products.store');

// Show the form to edit a product
Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');

// Update an existing product
Route::put('/products/{id}', [ProductController::class, 'update'])->name('products.update');

// Delete a product
Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');

