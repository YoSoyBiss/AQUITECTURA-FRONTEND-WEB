<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ProductWebController extends Controller
{
    private $apiBase = 'http://127.0.0.1:8000/api/products'; // API base URL

   public function index()
{
    $response = Http::get($this->apiBase);

    if (! $response->successful()) {
        // You can log it, show a friendly error, etc.
        // For quick debugging:
        // dd($response->status(), $response->body());
        return view('products.index', ['products' => []])
            ->withErrors(['api' => 'Could not fetch products from API (status: '.$response->status().')']);
    }

    $products = $response->json();

    // Ensure it's an array
    if (!is_array($products)) {
        $products = [];
    }

    return view('products.index', compact('products'));
}


    public function create()
    {
        return view('products.create');
    }

  public function store(Request $request)
{
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'author' => 'required|string|max:255',
        'publisher' => 'required|string|max:255',
        'stock' => 'required|integer|min:0'
    ]);

    $response = Http::post($this->apiBase, $validated);

    if ($response->failed()) {
        // Si la API devuelve errores de validación (422), puedes obtenerlos así:
       $apiErrors = $response->json('errors') ?? ['api' => ['Unknown error while saving the product.']];

        // Redirige con errores de vuelta al formulario
        return back()
            ->withErrors($apiErrors)
            ->withInput(); // mantiene los valores ingresados
    }

    return redirect()->route('products.index');
}


    public function edit($id)
    {
        $product = Http::get("{$this->apiBase}/{$id}")->json();
        return view('products.edit', compact('product'));
    }

   public function update(Request $request, $id)
{
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'author' => 'required|string|max:255',
        'publisher' => 'required|string|max:255',
        'stock' => 'required|integer|min:0'
    ]);

    Http::put("{$this->apiBase}/{$id}", $validated);
    return redirect()->route('products.index');
}

    public function destroy($id)
    {
        Http::delete("{$this->apiBase}/{$id}");
        return redirect()->route('products.index');
    }
}
