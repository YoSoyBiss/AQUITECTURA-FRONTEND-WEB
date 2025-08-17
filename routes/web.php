<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductWebController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\UserApiController;
use App\Http\Controllers\RoleApiController;
use App\Http\Controllers\CatalogosWebController;

// IMPORTA LOS MIDDLEWARES POR CLASE:
use App\Http\Middleware\RequireAuthSession;
use App\Http\Middleware\RoleMiddleware;

// Públicas
Route::get('/', fn() => redirect()->route('start.show'));
Route::get('/start', [UserApiController::class, 'showStart'])->name('start.show');
Route::post('/start/select', [UserApiController::class, 'selectStartRole'])->name('start.select');

Route::middleware('guest')->group(function () {
    Route::get('/login', [UserApiController::class, 'showLogin'])->name('users.login');
    Route::post('/login', [UserApiController::class, 'login'])->name('users.login.submit');
    Route::get('/register', [UserApiController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [UserApiController::class, 'submitRegister'])->name('register.submit');
});

Route::get('/sales/consultants', [SalesController::class, 'consultants'])->name('sales.consultants');
Route::get('/products/consult', [ProductWebController::class, 'indexConsult'])->name('products.indexcon');

// Requiere sesión (usa FQCN en vez de 'sess.auth')
Route::middleware(RequireAuthSession::class)->group(function () {

    Route::post('/logout', [UserApiController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [UserApiController::class, 'redireccionarPorRol'])->name('dashboard.redirect');

    // Dashboards directos protegidos por rol
    Route::view('/dashboard/admin', 'dashboard.admin')->name('dashboard.admin')
        ->middleware(RoleMiddleware::class.':admin');
    Route::view('/dashboard/seller', 'dashboard.seller')->name('dashboard.seller')
        ->middleware(RoleMiddleware::class.':seller');
    Route::view('/dashboard/consultant', 'dashboard.consultant')->name('dashboard.consultant')
        ->middleware(RoleMiddleware::class.':consultant');

    // Ventas (admin + seller)
    Route::middleware(RoleMiddleware::class.':admin,seller')->group(function () {
        Route::prefix('sales')->group(function () {
            Route::get('/', [SalesController::class, 'index'])->name('sales.index');
            Route::get('/create', [SalesController::class, 'create'])->name('sales.createsales');
            Route::post('/', [SalesController::class, 'store'])->name('sales.store');
            Route::get('/{id}', [SalesController::class, 'show'])->name('sales.show');
            Route::get('/{id}/edit', [SalesController::class, 'edit'])->name('sales.edit');
            Route::put('/{id}', [SalesController::class, 'update'])->name('sales.update');
            Route::delete('/{id}', [SalesController::class, 'destroy'])->name('sales.destroy');
        });

        Route::get('/reporte-ventas', [SalesController::class, 'reporte'])->name('sales.reporte');
        Route::get('/reporte-ventas/pdf', [SalesController::class, 'descargarPDF'])->name('sales.report.pdf');
    });

    // Catálogos (admin)
    Route::middleware(RoleMiddleware::class.':admin')->prefix('catalogos')->group(function () {
        Route::get('/', [CatalogosWebController::class, 'index'])->name('catalogs.index');
        Route::post('/publishers', [CatalogosWebController::class, 'storePublisher'])->name('catalogs.publishers.store');
        Route::put('/publishers/{id}', [CatalogosWebController::class, 'updatePublisher'])->name('catalogs.publishers.update');
        Route::delete('/publishers/{id}', [CatalogosWebController::class, 'destroyPublisher'])->name('catalogs.publishers.destroy');
        Route::post('/authors', [CatalogosWebController::class, 'storeAuthor'])->name('catalogs.authors.store');
        Route::put('/authors/{id}', [CatalogosWebController::class, 'updateAuthor'])->name('catalogs.authors.update');
        Route::delete('/authors/{id}', [CatalogosWebController::class, 'destroyAuthor'])->name('catalogs.authors.destroy');
        Route::post('/genres', [CatalogosWebController::class, 'storeGenre'])->name('catalogs.genres.store');
        Route::put('/genres/{id}', [CatalogosWebController::class, 'updateGenre'])->name('catalogs.genres.update');
        Route::delete('/genres/{id}', [CatalogosWebController::class, 'destroyGenre'])->name('catalogs.genres.destroy');
    });

    // Productos (admin) — cámbialo a admin,seller si quieres
    Route::middleware(RoleMiddleware::class.':admin')->group(function () {
        Route::get('/products', [ProductWebController::class, 'index'])->name('products.index');
        Route::get('/products/create', [ProductWebController::class, 'create'])->name('products.create');
        Route::post('/products', [ProductWebController::class, 'store'])->name('products.store');
        Route::get('/products/{id}/edit', [ProductWebController::class, 'edit'])->name('products.edit');
        Route::put('/products/{id}', [ProductWebController::class, 'update'])->name('products.update');
        Route::delete('/products/{id}', [ProductWebController::class, 'destroy'])->name('products.destroy');
    });

    // Usuarios y Roles (admin)
    Route::middleware(RoleMiddleware::class.':admin')->group(function () {
        Route::get('/users', [UserApiController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserApiController::class, 'create'])->name('users.create');
        Route::post('/users', [UserApiController::class, 'store'])->name('users.store');
        Route::get('/users/edit/{id}', [UserApiController::class, 'edit'])->name('users.edit');
        Route::put('/users/{id}', [UserApiController::class, 'update'])->name('users.update');
        Route::delete('/users/{id}', [UserApiController::class, 'destroy'])->name('users.destroy');

        Route::get('/roles', [RoleApiController::class, 'index'])->name('roles.index');
        Route::get('/roles/create', [RoleApiController::class, 'create'])->name('roles.create');
        Route::post('/roles', [RoleApiController::class, 'store'])->name('roles.store');
        Route::get('/roles/{id}/edit', [RoleApiController::class, 'edit'])->name('roles.edit');
        Route::put('/roles/{id}', [RoleApiController::class, 'update'])->name('roles.update');
        Route::delete('/roles/{id}', [RoleApiController::class, 'destroy'])->name('roles.destroy');
    });
});

// Verificación de password (pública si la usas vía AJAX en login)
Route::post('/users/verify-password', [UserApiController::class, 'verifyPassword'])
    ->name('users.verifyPassword');

// Fallback
Route::fallback(fn() => redirect()->route('start.show'));
