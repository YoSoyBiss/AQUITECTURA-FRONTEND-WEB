<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::get('/productos', [ProductController::class, 'index']);
Route::get('/productos/create', [ProductController::class, 'create']);
Route::post('/productos', [ProductController::class, 'store']);
Route::get('/productos/{id}/edit', [ProductController::class, 'edit']);
Route::put('/productos/{id}', [ProductController::class, 'update']);
Route::delete('/productos/{id}', [ProductController::class, 'destroy'])->name('productos.destroy');

Route::get('/', function () {
    return view('welcome');
});
