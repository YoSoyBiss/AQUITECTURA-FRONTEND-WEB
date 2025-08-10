<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ProductWebController extends Controller
{
    private string $apiBase = 'http://127.0.0.1:8000/api';

    // GET /products (vista)
    public function index(Request $request)
    {
        // Catálogos para filtros
        $authors    = Http::get("{$this->apiBase}/authors")->json()     ?? [];
        $genres     = Http::get("{$this->apiBase}/genres")->json()      ?? [];
        $publishers = Http::get("{$this->apiBase}/publishers")->json()  ?? [];

        // Normalizar query params
        $q           = $request->query('q');
        $authorIds   = $request->query('author_ids', []);
        $genreIds    = $request->query('genre_ids',  []);
        $publisherId = $request->query('publisher_id');

        $authorIds = is_array($authorIds) ? $authorIds : [$authorIds];
        $genreIds  = is_array($genreIds)  ? $genreIds  : [$genreIds];

        // Limpiar vacíos
        $authorIds = array_values(array_filter($authorIds, fn($v)=>is_numeric($v)));
        $genreIds  = array_values(array_filter($genreIds,  fn($v)=>is_numeric($v)));

        // Reenviar a la API
        $query = array_filter([
            'q'            => $q,
            'author_ids'   => $authorIds ?: null,
            'genre_ids'    => $genreIds  ?: null,
            'publisher_id' => $publisherId ?: null,
        ], fn($v) => !is_null($v));

        $response = Http::get("{$this->apiBase}/products", $query);
        $products = ($response->successful() && is_array($response->json())) ? $response->json() : [];

        $view = match (session('user_role')) {
            'consultant' => 'products.indexv',
            default      => 'products.index',
        };

        return view($view, [
            'products'   => $products,
            'authors'    => ($authors['data'] ?? $authors),
            'genres'     => ($genres['data'] ?? $genres),
            'publishers' => ($publishers['data'] ?? $publishers),
            'filters'    => [
                'q'            => $q,
                'author_ids'   => $authorIds,
                'genre_ids'    => $genreIds,
                'publisher_id' => $publisherId,
            ],
        ])->withErrors($response->successful() ? [] : [
            'api' => 'No se pudieron obtener los productos (estado: '.$response->status().')'
        ]);
    }

    public function create()
    {
        $authors    = Http::get("{$this->apiBase}/authors")->json()     ?? [];
        $genres     = Http::get("{$this->apiBase}/genres")->json()      ?? [];
        $publishers = Http::get("{$this->apiBase}/publishers")->json()  ?? [];

        // normalizar posibles formatos {data:[...]}
        $authors    = ($authors['data'] ?? $authors);
        $genres     = ($genres['data'] ?? $genres);
        $publishers = ($publishers['data'] ?? $publishers);

        return view('products.create', compact('authors','genres','publishers'));
    }

    public function store(Request $request)
    {
        // Validación del producto (incluye preciodeproveedor)
        $validated = $request->validate([
            'title'             => 'required|string|max:255',
            'publisher_id'      => 'required|integer',
            'stock'             => 'required|integer|min:0',
            'price'             => 'required|numeric|min:0',
            'preciodeproveedor' => 'nullable|numeric|min:0',
        ]);

        // Normalizar relaciones
        $authorIds = $request->input('author_ids', []);
        $genreIds  = $request->input('genre_ids',  []);
        $authorIds = is_array($authorIds) ? $authorIds : [$authorIds];
        $genreIds  = is_array($genreIds)  ? $genreIds  : [$genreIds];

        // Normalizar imágenes
        $images = $request->input('images', null);
        if (is_array($images)) {
            $images = array_values(array_filter($images, fn($img) =>
                is_array($img) && !empty($img['url'] ?? null)
            ));
        }

        // payload hacia el API de productos
        $payload = array_merge($validated, [
            'author_ids' => $authorIds ?: null,
            'genre_ids'  => $genreIds  ?: null,
            'images'     => $images    ?: null,
        ]);

        // Crear producto (+ preciodeproveedor si viene)
        $resp = Http::post("{$this->apiBase}/products", $payload);
        if ($resp->failed()) {
            $apiErrors = $resp->json('errors') ?? ['api' => ['No se pudo guardar el producto.']];
            return back()->withErrors($apiErrors)->withInput();
        }

        return redirect()->route('products.index')->with('ok','Producto creado');
    }

    public function edit($id)
    {
        $productResp = Http::get("{$this->apiBase}/products/{$id}");
        abort_if($productResp->failed(), 404);

        $product    = $productResp->json();
        $authors    = Http::get("{$this->apiBase}/authors")->json()     ?? [];
        $genres     = Http::get("{$this->apiBase}/genres")->json()      ?? [];
        $publishers = Http::get("{$this->apiBase}/publishers")->json()  ?? [];

        return view('products.edit', [
            'product'    => $product,
            'authors'    => ($authors['data'] ?? $authors),
            'genres'     => ($genres['data'] ?? $genres),
            'publishers' => ($publishers['data'] ?? $publishers),
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title'             => 'required|string|max:255',
            'publisher_id'      => 'required|integer',
            'stock'             => 'required|integer|min:0',
            'price'             => 'required|numeric|min:0',
            'preciodeproveedor' => 'nullable|numeric|min:0',
        ]);

        $authorIds = $request->input('author_ids', []);
        $genreIds  = $request->input('genre_ids',  []);
        $authorIds = is_array($authorIds) ? $authorIds : [$authorIds];
        $genreIds  = is_array($genreIds)  ? $genreIds  : [$genreIds];

        $images = $request->input('images', null);
        if (is_array($images)) {
            $images = array_values(array_filter($images, fn($img) =>
                is_array($img) && !empty($img['url'] ?? null)
            ));
        }

        $payload = array_merge($validated, [
            'author_ids' => $authorIds,
            'genre_ids'  => $genreIds,
            'images'     => $images,
        ]);

        $resp = Http::put("{$this->apiBase}/products/{$id}", $payload);

        if ($resp->failed()) {
            $apiErrors = $resp->json('errors') ?? ['api' => ['No se pudo actualizar el producto.']];
            return back()->withErrors($apiErrors)->withInput();
        }

        return redirect()->route('products.index')->with('ok','Producto actualizado');
    }

    public function destroy($id)
    {
        Http::delete("{$this->apiBase}/products/{$id}");
        return redirect()->route('products.index')->with('ok','Producto eliminado');
    }
}
