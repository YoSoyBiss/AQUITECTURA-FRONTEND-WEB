<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductWebController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\LoginController;

// Rutas para productos
Route::get('/productos', [ProductController::class, 'index']);
Route::get('/productos/create', [ProductController::class, 'create']);
Route::post('/productos', [ProductController::class, 'store']);
Route::get('/productos/{id}/edit', [ProductController::class, 'edit']);
Route::put('/productos/{id}', [ProductController::class, 'update']);
Route::delete('/productos/{id}', [ProductController::class, 'destroy'])->name('productos.destroy');

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::view('/login', 'login')->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', function () {
    return view('register');
});
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register.form');
Route::post('/register', [RegisterController::class, 'register'])->name('register');


Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


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

Route::get('/ventas', [App\Http\Controllers\SalesController::class, 'index'])->name('sales.indexsales');
Route::get('/sales/create', [SalesController::class, 'create'])->name('sales.createsales');

Route::post('/sales', [SalesController::class, 'store'])->name('sales.store');



Route::get('/users', [UserController::class, 'index'])->name('users.indexusers');
Route::get('/users/create', [UserController::class, 'create'])->name('users.createusers');
Route::post('/users', [UserController::class, 'store'])->name('users.store');
Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.editusers');
Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
