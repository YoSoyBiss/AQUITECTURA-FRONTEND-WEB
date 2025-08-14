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
    $rolesResponse = Http::get(env('ROLES_API_URL', 'http://localhost:5000/api/roles'));

    $roles = $rolesResponse->successful() ? $rolesResponse->json() : [];



    return view('users.create', compact('roles'));
}



   public function store(Request $request)
{
    $data = $request->validate([
        'name' => 'required|string',
        'email' => 'required|email',
        'password' => 'required|string|min:6|confirmed',
        'role' => 'required|string'  // Agregado
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

    if (!$response->successful()) {
        return back()->withErrors(['error' => $response->json('message') ?? 'Credenciales incorrectas']);
    }

    $json = $response->json();
    $user = $json['user'] ?? null;

    if (!$user) {
        return back()->withErrors(['error' => 'No se recibió información de usuario']);
    }

    // Normalizar rol
    $roleValue = $user['role'] ?? null;
    $roleName = is_array($roleValue) ? ($roleValue['name'] ?? null) : $roleValue;
    $roleName = $roleName ? strtolower(trim($roleName)) : null;

    // Validar rol elegido en Start
    $intended = session('intended_role');
    if ($intended) {
        $allowed = false;

        if ($intended === 'otros') {
            $allowed = !in_array($roleName, ['admin', 'seller', 'consultant'], true);
        } else {
            $allowed = ($roleName === $intended);
        }

        if (!$allowed) {
            return redirect()
                ->route('users.login')
                ->withErrors(['error' => "No puedes entrar como '{$intended}' con una cuenta '{$roleName}'."])
                ->withInput();
        }
    }

    // Guardar en sesión
    session([
        'user_name' => $user['name'] ?? '',
        'user_role' => $roleName,
        'user_id'   => $user['_id'] ?? ($user['id'] ?? null),
    ]);

    session()->forget('intended_role');

    return redirect()->route('dashboard.redirect');
}


    public function edit($id)
{
    // Obtener usuario desde API o base de datos
    $response = Http::get("{$this->usersApi}/{$id}");
    if (!$response->successful()) {
        abort(404, 'Usuario no encontrado');
    }
    $user = $response->json();

    // Obtener roles para el dropdown
    $rolesResponse = Http::get(env('ROLES_API_URL', 'http://localhost:5000/api/roles'));
    $roles = $rolesResponse->successful() ? $rolesResponse->json() : [];

    return view('users.edit', compact('user', 'roles'));
}



  public function update(Request $request, $id)
{
    // Validación básica
    $request->validate([
        'name' => 'required|string',
        'role' => 'required|string',
        'password' => 'nullable|string|min:6|confirmed', // password puede ser opcional, y si se envía debe confirmarse
    ]);

    // Tomar solo lo que viene, excepto password si está vacío
    $data = $request->only(['name', 'role']);

    if ($request->filled('password')) {
        $data['password'] = $request->input('password');
        // Para confirmar la password, necesitas agregar un campo 'password_confirmation' en el form si no lo tienes.
    }

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

public function showStart()
{
    // Opcional: si ya está logueado, puedes mandarlo directo al dashboard
    if (session('api_token')) {
        return redirect()->route('dashboard.redirect');
    }

    // Si ya había un intended_role, muéstralo (por UX)
    $intended = session('intended_role');
    return view('users.start', compact('intended'));
}

public function selectStartRole(Request $request)
{
    $data = $request->validate([
        'role' => 'required|string|in:admin,seller,consultant,otros'
    ]);

    session(['intended_role' => $data['role']]);

    // Luego de elegir rol, vamos a login
    return redirect()->route('users.login')->with('success', 'Seleccionaste: ' . $data['role']);
}


}