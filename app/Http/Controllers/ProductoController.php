<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ProductoController extends Controller
{
    protected $apiUrl;

    public function __construct()
    {
        $this->apiUrl = env('API_URL', 'http://127.0.0.1:8000') . '/api/products';
    }

    public function index()
    {
        $response = Http::get($this->apiUrl);

        $productos = $response->successful() ? $response->json()['products'] : [];

        return view('productos.index', compact('productos'));
    }

    public function crear()
    {
        return view('productos.crear');
    }

    public function guardar(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'author' => 'required',
            'publisher' => 'required',
            'stock' => 'required|integer|min:0',
        ]);

        $response = Http::post($this->apiUrl, $request->all());

        if ($response->successful()) {
            return redirect('/productos')->with('success', 'Producto agregado correctamente.');
        }

        return back()->withErrors('Error al agregar el producto');
    }

    public function editar($id)
    {
        $response = Http::get("{$this->apiUrl}/{$id}");

        if (!$response->successful()) {
            return redirect('/productos')->withErrors('No se pudo obtener el producto.');
        }

        $producto = $response->json()['product'] ?? $response->json();

        return view('productos.modificar', compact('producto'));
    }

    public function actualizar(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'author' => 'required',
            'publisher' => 'required',
            'stock' => 'required|integer|min:0',
        ]);

        $response = Http::put("{$this->apiUrl}/{$id}", $request->all());

        if ($response->successful()) {
            return redirect('/productos')->with('success', 'Producto actualizado correctamente.');
        }

        return back()->withErrors('Error al actualizar el producto')->withInput();
    }

    public function destroy($id)
    {
        $response = Http::delete("{$this->apiUrl}/{$id}");

        if ($response->successful()) {
            return redirect('/productos')->with('success', 'Producto eliminado correctamente.');
        }

        return back()->withErrors('Error al eliminar el producto');
    }
}
