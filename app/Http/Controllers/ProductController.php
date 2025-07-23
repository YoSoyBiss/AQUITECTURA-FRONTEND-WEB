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

    // Display all products
    public function index()
    {
        $response = Http::get($this->apiUrl);

        if (!$response->successful()) {
            Log::error('Failed to fetch products', [
                'url' => $this->apiUrl,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
        }

        $products = $response->successful() ? $response->json()['products'] : [];

        return view('products.index', compact('products'));
    }

    // Show form to create a new product
    public function create()
    {
        return view('products.create');
    }

    // Store a new product via the API
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
            Log::info('Product successfully created', $request->only(['title', 'author']));
            return redirect('/products')->with('success', 'Product added successfully.');
        }

        Log::error('Failed to create product', [
            'data' => $request->all(),
            'status' => $response->status(),
            'body' => $response->body(),
        ]);

        return back()->withErrors('Failed to add the product');
    }

    // Show form to edit a product
    public function edit($id)
    {
        $response = Http::get("{$this->apiUrl}/{$id}");

        if (!$response->successful()) {
            Log::error("Failed to fetch product ID: $id", [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return redirect('/products')->withErrors('Failed to fetch the product.');
        }

        $product = $response->json()['product'] ?? $response->json();

        return view('products.edit', compact('product'));
    }

    // Update a product
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
            Log::info("Product updated ID: $id", $request->only(['title', 'author']));
            return redirect('/products')->with('success', 'Product updated successfully.');
        }

        Log::error("Failed to update product ID: $id", [
            'data' => $request->all(),
            'status' => $response->status(),
            'body' => $response->body(),
        ]);

        return back()->withErrors('Failed to update the product')->withInput();
    }

    // Delete a product
    public function destroy($id)
    {
        $response = Http::delete("{$this->apiUrl}/{$id}");

        if ($response->successful()) {
            Log::info("Product deleted ID: $id");
            return redirect('/products')->with('success', 'Product deleted successfully.');
        }

        Log::error("Failed to delete product ID: $id", [
            'status' => $response->status(),
            'body' => $response->body(),
        ]);

        return back()->withErrors('Failed to delete the product');
    }
}
