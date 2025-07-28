<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// 🔓 Ruta pública para iniciar sesión (recibe email y password)
Route::post('/login', [AuthController::class, 'login']);

// 🔒 Rutas protegidas (requieren token con Sanctum)
Route::middleware('auth:sanctum')->group(function () {
    // Cerrar sesión y obtener datos del usuario autenticado
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // 🛡 Ruta exclusiva para usuarios con rol admin
    Route::middleware('role:admin')->group(function () {
        Route::get('/admin-only', function () {
            return response()->json(['message' => 'Welcome admin!']);
        });
    });
});
