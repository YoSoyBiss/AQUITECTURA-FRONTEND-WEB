<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoController;

Route::get('/productos', [ProductoController::class, 'index']);
Route::get('/productos/crear', [ProductoController::class, 'crear']);
Route::post('/productos', [ProductoController::class, 'guardar']);
Route::delete('/productos/{id}', [ProductoController::class, 'destroy'])->name('productos.destroy');



Route::get('/productos/{id}/editar', [ProductoController::class, 'editar']);
Route::put('/productos/{id}', [ProductoController::class, 'actualizar']);
Route::get('/', function () {
    return view('welcome');
});
