<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductWebController;

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
