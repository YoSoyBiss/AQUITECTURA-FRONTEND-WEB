<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class UserApiController extends Controller
{
    private $usersApi;
    private $authApi;

public function __construct()
{
    $this->usersApi = env('USERS_API_URL', 'http://localhost:5000/api/users');
    $this->authApi = env('AUTH_API_URL', 'http://localhost:5000/api/users');  // Cambiado a /api/users
}

    public function index()
    {
        $response = Http::get($this->usersApi);
        $users = $response->successful() ? $response->json() : [];
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $response = Http::post("{$this->usersApi}/register", $data);

        if ($response->successful()) {
            return redirect()->route('users.index')->with('success', 'Usuario creado');
        }

        return back()->withErrors(['error' => $response->json('message') ?? 'Error en la API']);
    }

    public function showLogin()
    {
        return view('users.login');
    }

   public function login(Request $request)
{
    $data = $request->validate([
        'email' => 'required|email',
        'password' => 'required|string',
    ]);

    $response = Http::post("{$this->authApi}/login", $data);

    if ($response->successful()) {
    $user = $response->json('user');

    session([
        'api_token'  => $response->json('token'),  // si tienes token
        'user_name'  => $user['name'],
        'user_role'  => $user['role'],
    ]);

    return redirect()->route('dashboard.redirect');
}


    return back()->withErrors(['error' => $response->json('message') ?? 'Credenciales incorrectas']);
}

    public function edit($id)
{
    $response = Http::get("{$this->usersApi}/{$id}");

    if (!$response->successful()) {
        return redirect()->route('users.index')->withErrors(['error' => 'Usuario no encontrado']);
    }

    $user = $response->json();
    return view('users.edit', compact('user'));
}


  public function update(Request $request, $id)
{


    $data = $request->only(['name', 'password', 'role']);


    $response = Http::put("{$this->usersApi}/{$id}", $data);

    if ($response->successful()) {
        return redirect()->route('users.index')->with('success', 'Usuario actualizado');
    }

    return back()->withErrors(['error' => 'Error al actualizar usuario']);
}



    public function destroy($id)
    {
        $response = Http::delete("{$this->usersApi}/{$id}");

        if ($response->successful()) {
            return redirect()->route('users.index')->with('success', 'Usuario eliminado');
        }

        return back()->withErrors(['error' => 'Error al eliminar usuario']);
    }

    public function showRegisterForm()
{
    return view('users.register'); // <- Aquí va la vista bonita que hicimos
}

public function submitRegister(Request $request)
{
    $data = $request->validate([
        'name' => 'required|string',
        'email' => 'required|email',
        'password' => 'required|string|min:6|confirmed',
    ]);

    // Enviar al backend Node.js (no agregar role)
    $response = Http::post("{$this->authApi}/register", $data);

    if ($response->successful()) {
        return redirect()->route('users.login')->with('success', 'Registro exitoso');
    }

    \Log::error('Error al registrarse en API:', [
        'status' => $response->status(),
        'body' => $response->body(),
        'json' => $response->json()
    ]);

    return back()->withErrors(['error' => $response->json('message') ?? 'Error al registrarse']);
}

public function redireccionarPorRol()
{
    $rol = session('user_role');
    $nombre = session('user_name');

    if (!$rol) {
        return redirect()->route('users.login')->withErrors(['error' => 'Debes iniciar sesión']);
    }

    return match ($rol) {
        'admin'     => view('dashboard.admin', compact('nombre')),
        'seller'    => view('dashboard.seller', compact('nombre')),
        'consultant'=> view('dashboard.consultant', compact('nombre')),
        default     => abort(403, 'Rol no reconocido'),
    };
}

public function logout(Request $request)
{
    session()->forget('api_token');
    session()->flush(); // Limpia todo

    return redirect('/login')->with('success', 'Sesión cerrada');
}



}

