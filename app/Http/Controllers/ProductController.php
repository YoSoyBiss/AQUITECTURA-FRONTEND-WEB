<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    protected $apiUrl;

    public function __construct()
    {
        $this->apiUrl = env('API_URL', 'http://127.0.0.1:8000') . '/api/products';
    }

    public function index()
    {
        $response = Http::get($this->apiUrl);

        if (!$response->successful()) {
            Log::error('Error al obtener productos', [
                'url' => $this->apiUrl,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
        }

        $productos = $response->successful() ? $response->json()['products'] : [];

        return view('productos.index', compact('productos'));
    }

    public function create()
    {
        return view('productos.crear');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'author' => 'required',
            'publisher' => 'required',
            'stock' => 'required|integer|min:0',
        ]);

        $response = Http::post($this->apiUrl, $request->all());

        if ($response->successful()) {
            Log::info('Producto creado con éxito', $request->only(['title', 'author']));
            return redirect('/productos')->with('success', 'Producto agregado correctamente.');
        }

        Log::error('Error al crear producto', [
            'data' => $request->all(),
            'status' => $response->status(),
            'body' => $response->body(),
        ]);

        return back()->withErrors('Error al agregar el producto');
    }

    public function edit($id)
    {
        $response = Http::get("{$this->apiUrl}/{$id}");

        if (!$response->successful()) {
            Log::error("Error al obtener producto ID: $id", [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return redirect('/productos')->withErrors('No se pudo obtener el producto.');
        }

        $producto = $response->json()['product'] ?? $response->json();

        return view('productos.modificar', compact('producto'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'author' => 'required',
            'publisher' => 'required',
            'stock' => 'required|integer|min:0',
        ]);

        $response = Http::put("{$this->apiUrl}/{$id}", $request->all());

        if ($response->successful()) {
            Log::info("Producto actualizado ID: $id", $request->only(['title', 'author']));
            return redirect('/productos')->with('success', 'Producto actualizado correctamente.');
        }

        Log::error("Error al actualizar producto ID: $id", [
            'data' => $request->all(),
            'status' => $response->status(),
            'body' => $response->body(),
        ]);

        return back()->withErrors('Error al actualizar el producto')->withInput();
    }

    public function destroy($id)
    {
        $response = Http::delete("{$this->apiUrl}/{$id}");

        if ($response->successful()) {
            Log::info("Producto eliminado ID: $id");
            return redirect('/productos')->with('success', 'Producto eliminado correctamente.');
        }

        Log::error("Error al eliminar producto ID: $id", [
            'status' => $response->status(),
            'body' => $response->body(),
        ]);

        return back()->withErrors('Error al eliminar el producto');
    }
}
