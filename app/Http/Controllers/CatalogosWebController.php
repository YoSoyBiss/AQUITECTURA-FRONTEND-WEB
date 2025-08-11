<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CatalogosWebController extends Controller
{
    private string $apiBase = 'http://127.0.0.1:8000/api';

    public function index(Request $request)
    {
        $authors    = Http::get("{$this->apiBase}/authors")->json()     ?? [];
        $genres     = Http::get("{$this->apiBase}/genres")->json()      ?? [];
        $publishers = Http::get("{$this->apiBase}/publishers")->json()  ?? [];

        // normaliza {data:[...]} si aplicara
        $authors    = $authors['data']    ?? $authors;
        $genres     = $genres['data']     ?? $genres;
        $publishers = $publishers['data'] ?? $publishers;

        $canManage = in_array(session('user_role'), ['admin','editor']); // ajusta a tus roles

        return view('catalogs.index', compact('authors','genres','publishers','canManage'));
    }

    // ---------- PUBLISHERS ----------
    public function storePublisher(Request $request)
    {
        $payload = $request->validate(['name' => 'required|string|max:255']);
        $resp = Http::post("{$this->apiBase}/publishers", $payload);
        return $this->backWithApi($resp, 'Editorial creada');
    }

    public function updatePublisher(Request $request, $id)
    {
        $payload = $request->validate(['name' => 'required|string|max:255']);
        $resp = Http::put("{$this->apiBase}/publishers/{$id}", $payload);
        return $this->backWithApi($resp, 'Editorial actualizada');
    }

    public function destroyPublisher($id)
    {
        $resp = Http::delete("{$this->apiBase}/publishers/{$id}");
        return $this->backWithApi($resp, 'Editorial eliminada');
    }

    // ---------- AUTHORS ----------
    public function storeAuthor(Request $request)
    {
        $payload = $request->validate(['name' => 'required|string|max:255']);
        $resp = Http::post("{$this->apiBase}/authors", $payload);
        return $this->backWithApi($resp, 'Autor creado');
    }
    public function updateAuthor(Request $request, $id)
    {
        $payload = $request->validate(['name' => 'required|string|max:255']);
        $resp = Http::put("{$this->apiBase}/authors/{$id}", $payload);
        return $this->backWithApi($resp, 'Autor actualizado');
    }
    public function destroyAuthor($id)
    {
        $resp = Http::delete("{$this->apiBase}/authors/{$id}");
        return $this->backWithApi($resp, 'Autor eliminado');
    }

    // ---------- GENRES ----------
    public function storeGenre(Request $request)
    {
        $payload = $request->validate(['name' => 'required|string|max:255']);
        $resp = Http::post("{$this->apiBase}/genres", $payload);
        return $this->backWithApi($resp, 'Género creado');
    }
    public function updateGenre(Request $request, $id)
    {
        $payload = $request->validate(['name' => 'required|string|max:255']);
        $resp = Http::put("{$this->apiBase}/genres/{$id}", $payload);
        return $this->backWithApi($resp, 'Género actualizado');
    }
    public function destroyGenre($id)
    {
        $resp = Http::delete("{$this->apiBase}/genres/{$id}");
        return $this->backWithApi($resp, 'Género eliminado');
    }

    // Utilidad
    private function backWithApi($resp, $okMsg)
    {
        if ($resp->failed()) {
            $errors = $resp->json('errors') ?? ['api' => [$resp->json('message') ?? 'Error de API']];
            return back()->withErrors($errors)->withInput();
        }
        return back()->with('ok', $okMsg);
    }
}
