<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoController;

Route::get('/productos', [ProductoController::class, 'index']);
Route::get('/productos/crear', [ProductoController::class, 'crear']);
Route::post('/productos', [ProductoController::class, 'guardar']);


Route::get('/', function () {
    return view('welcome');
});
