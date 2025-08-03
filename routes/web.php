<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductWebController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserApiController;


// Ruta raíz
Route::get('/', function () {
    return view('welcome');
});

// Listar productos (llama a la API para obtener productos)
Route::get('/products', [ProductWebController::class, 'index'])->name('products.index');

// Mostrar formulario para crear un producto
Route::get('/products/create', [ProductWebController::class, 'create'])->name('products.create');

// Guardar nuevo producto (envía datos a la API)
Route::post('/products', [ProductWebController::class, 'store'])->name('products.store');

// Mostrar formulario para editar producto específico
Route::get('/products/{id}/edit', [ProductWebController::class, 'edit'])->name('products.edit');

// Actualizar producto específico (envía datos a la API)
Route::put('/products/{id}', [ProductWebController::class, 'update'])->name('products.update');

// Eliminar producto específico (envía petición a la API)
Route::delete('/products/{id}', [ProductWebController::class, 'destroy'])->name('products.destroy');

Route::prefix('sales')->group(function () {
    Route::get('/', [SalesController::class, 'index'])->name('sales.index');               // Ver lista de ventas
    Route::get('/create', [SalesController::class, 'create'])->name('sales.createsales');  // Mostrar formulario
    Route::post('/', [SalesController::class, 'store'])->name('sales.store');              // Guardar venta
    Route::get('/{id}', [SalesController::class, 'show'])->name('sales.show');             // Ver una venta (opcional)
    Route::get('/{id}/edit', [SalesController::class, 'edit'])->name('sales.edit');        // Editar venta (opcional)
    Route::put('/{id}', [SalesController::class, 'update'])->name('sales.update');         // Actualizar venta
    Route::delete('/{id}', [SalesController::class, 'destroy'])->name('sales.destroy');    // Eliminar venta
});

Route::get('/users', [UserApiController::class, 'index'])->name('users.index');
Route::get('/users/create', [UserApiController::class, 'create'])->name('users.create');
Route::post('/users', [UserApiController::class, 'store'])->name('users.store');
Route::get('/users/edit/{id}', [UserApiController::class, 'edit'])->name('users.edit');
Route::put('/users/{id}', [UserApiController::class, 'update'])->name('users.update');
Route::delete('/users/{id}', [UserApiController::class, 'destroy'])->name('users.destroy');

Route::get('/login', [UserApiController::class, 'showLogin'])->name('users.login');
Route::post('/login', [UserApiController::class, 'login'])->name('users.login.submit');

Route::get('/register', [UserApiController::class, 'create'])->name('users.register');
Route::post('/register', [UserApiController::class, 'store'])->name('users.register.submit');
