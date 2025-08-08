<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RoleApiController extends Controller
{
    private $rolesApi;

    public function __construct()
    {
        $this->rolesApi = env('ROLES_API_URL', 'http://localhost:5000/api/roles');
    }

    public function index()
    {
        $response = Http::get($this->rolesApi);
        $roles = $response->successful() ? $response->json() : [];

        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        return view('roles.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $response = Http::post($this->rolesApi, $data);

        if ($response->successful()) {
            return redirect()->route('roles.index')->with('success', 'Rol creado');
        }

        return back()->withErrors(['error' => 'Error al crear rol']);
    }

    public function edit($id)
    {
        $response = Http::get("{$this->rolesApi}/{$id}");

        if (!$response->successful()) {
            return redirect()->route('roles.index')->withErrors(['error' => 'Rol no encontrado']);
        }

        $role = $response->json();
        return view('roles.edit', compact('role'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $response = Http::put("{$this->rolesApi}/{$id}", $data);

        if ($response->successful()) {
            return redirect()->route('roles.index')->with('success', 'Rol actualizado');
        }

        return back()->withErrors(['error' => 'Error al actualizar rol']);
    }

    public function destroy($id)
    {
        $response = Http::delete("{$this->rolesApi}/{$id}");

        if ($response->successful()) {
            return redirect()->route('roles.index')->with('success', 'Rol eliminado');
        }

        return back()->withErrors(['error' => 'Error al eliminar rol']);
    }
}
