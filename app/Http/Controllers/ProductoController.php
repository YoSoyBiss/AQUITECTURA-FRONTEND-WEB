<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ProductoController extends Controller
{
    public function index()
    {
        $response = Http::get('http://127.0.0.1:8000/api/productos');

        $productos = $response->successful() ? $response->json()['productos'] : [];

        return view('productos.index', compact('productos'));
    }

    public function crear()
    {
        return view('productos.crear');
    }

    public function guardar(Request $request)
    {
        $request->validate([
            'titulo' => 'required',
            'autor' => 'required',
            'editorial' => 'required',
            'stock' => 'required|integer|min:0',
        ]);

        $response = Http::post('http://127.0.0.1:8000/api/productos', $request->all());

        if ($response->successful()) {
            return redirect('/productos')->with('success', 'Producto agregado correctamente.');
        }

        return back()->withErrors('Error al agregar el producto');
    }

    public function editar($id)
    {
        $response = Http::get("http://127.0.0.1:8000/api/productos/{$id}");

        if (!$response->successful()) {
            return redirect('/productos')->withErrors('No se pudo obtener el producto.');
        }

        $producto = $response->json()['producto'] ?? $response->json();

        return view('productos.modificar', compact('producto'));
    }

    public function actualizar(Request $request, $id)
    {
        $request->validate([
            'titulo' => 'required',
            'autor' => 'required',
            'editorial' => 'required',
            'stock' => 'required|integer|min:0',
        ]);

        $response = Http::put("http://127.0.0.1:8000/api/productos/{$id}", $request->all());

        if ($response->successful()) {
            return redirect('/productos')->with('success', 'Producto actualizado correctamente.');
        }

        return back()->withErrors('Error al actualizar el producto')->withInput();
    }


    public function destroy($id)
    {
        $response = Http::delete("http://127.0.0.1:8000/api/productos/{$id}");

        if ($response->successful()) {
            return redirect('/productos')->with('success', 'Producto eliminado correctamente.');
        }

        return back()->withErrors('Error al eliminar el producto');
    }
}
