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
        $this->authApi  = env('AUTH_API_URL',  'http://localhost:5000/api/users'); // /api/users
    }

    /** Normaliza strings de rol a uno de: admin | seller | consultant */
    private function normalizeRole(?string $raw): ?string
    {
        if (!$raw) return null;
        $r = strtolower(trim($raw));

        // mapeos flexibles
        $map = [
            'admin'      => 'admin',
            'administrator' => 'admin',
            'superadmin' => 'admin',

            'seller'     => 'seller',
            'sales'      => 'seller',
            'vendedor'   => 'seller',
            'saller'     => 'seller',     // typo común
            'seller_user'=> 'seller',

            'consult'    => 'consultant',
            'consultant' => 'consultant',
            'consultor'  => 'consultant',
            'consultant_user' => 'consultant',
        ];

        return $map[$r] ?? $r; // si no está en mapa, regresa tal cual
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
            'name'     => 'required|string',
            'email'    => 'required|email',
            'password' => 'required|string|min:6|confirmed',
            'role'     => 'required|string'
        ]);

        // Alta desde panel (admin): requiere rol explícito
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
            'email'    => 'required|email',
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

        // Token si viene: api_token | token | access_token
        $token = $json['token'] ?? $json['access_token'] ?? $json['api_token'] ?? null;

        // Normalización de rol
        $roleValue = $user['role'] ?? null;
        $roleName  = is_array($roleValue) ? ($roleValue['name'] ?? null) : $roleValue;
        $roleName  = $this->normalizeRole($roleName);

        // Validar rol elegido anteriormente (flow de "Start")
        $intended = session('intended_role');
        if ($intended) {
            $allowed = false;
            if ($intended === 'otros') {
                // "otros" = NO admin/seller/consultant
                $allowed = !in_array($roleName, ['admin','seller','consultant'], true);
            } else {
                $allowed = ($roleName === $this->normalizeRole($intended));
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
            'api_token' => $token, // opcional si lo usas en peticiones
            'user_name' => $user['name'] ?? '',
            'user_role' => $roleName,
            'user_id'   => $user['_id'] ?? ($user['id'] ?? null),
        ]);

        session()->forget('intended_role');

        // Redirección central
        return redirect()->route('dashboard.redirect');
    }

    public function edit($id)
    {
        $response = Http::get("{$this->usersApi}/{$id}");
        if (!$response->successful()) abort(404, 'Usuario no encontrado');
        $user  = $response->json();

        $rolesResponse = Http::get(env('ROLES_API_URL', 'http://localhost:5000/api/roles'));
        $roles = $rolesResponse->successful() ? $rolesResponse->json() : [];

        return view('users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, $id)
{
    // Reglas base
    $rules = [
        'name' => 'required|string',
        'role' => 'required|string',
    ];

    // Si viene password, valida formato y que coincida con password_confirmation
    if ($request->filled('password')) {
        $rules['password'] = 'string|min:6';
        $rules['password_confirmation'] = 'same:password'; // <- exige coincidencia solo si vino password
    }

    $validated = $request->validate($rules);

    $data = $request->only(['name', 'role']);

    if ($request->filled('password')) {
        $data['password'] = $request->input('password'); // la API de Node hashea
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
        return view('users.register');
    }

    public function submitRegister(Request $request)
{
    $data = $request->validate([
        'name'     => 'required|string',
        'email'    => 'required|email',
        'password' => 'required|string|min:6|confirmed',
    ]);

    // 1) Obtener el ID del rol consultant
    $rolesUrl = env('ROLES_API_URL', 'http://localhost:5000/api/roles');
    $rolesRes = Http::get($rolesUrl);
    if (!$rolesRes->successful()) {
        return back()->withErrors(['error' => 'No se pudieron cargar roles']);
    }
    $roles = $rolesRes->json();
    $consultant = collect($roles)->first(fn ($r) => strcasecmp($r['name'] ?? '', 'consultant') === 0);
    if (!$consultant) {
        return back()->withErrors(['error' => 'No existe el rol consultant en la API']);
    }

    // 2) Registrar enviando el _id del rol
    $payload = [
        'name'     => $data['name'],
        'email'    => $data['email'],
        'password' => $data['password'],
        'role'     => $consultant['_id'], // ← ObjectId
    ];

    $response = Http::post(env('AUTH_API_URL', 'http://localhost:5000/api/users').'/register', $payload);
    if ($response->successful()) {
        return redirect()->route('users.login')->with('success', 'Registro exitoso');
    }
    return back()->withErrors(['error' => $response->json('message') ?? 'Error al registrarse']);
}


    public function redireccionarPorRol()
    {
        $rol    = session('user_role');
        $nombre = session('user_name');

        if (!$rol) {
            return redirect()->route('users.login')
                             ->withErrors(['error' => 'Debes iniciar sesión']);
        }

        return match ($rol) {
            'admin'      => view('dashboard.admin',      compact('nombre')),
            'seller'     => view('dashboard.seller',     compact('nombre')),
            'consultant' => view('dashboard.consultant', compact('nombre')),
            'Otros', 'otros' => redirect()->route('sales.consultants'),
            default      => abort(403, 'Rol no reconocido'),
        };
    }

    public function logout(Request $request)
{
    // Limpia variables que usabas
    $request->session()->forget(['api_token', 'user_name', 'user_role', 'user_id', 'intended_role']);

    // Invalida la sesión y regenera el token CSRF (práctica recomendada)
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    // Redirige a la pantalla de inicio (start)
    return redirect()->route('start.show')->with('success', 'Sesión cerrada');
}


    public function showStart()
    {
        if (session('api_token')) {
            return redirect()->route('dashboard.redirect');
        }
        $intended = session('intended_role');
        return view('users.start', compact('intended'));
    }

    public function selectStartRole(Request $request)
{
    $data = $request->validate([
        'role' => 'required|string|in:admin,seller,consultant,otros'
    ]);

    // Si elige "otros", lo mandamos directo a sales.consultants
    if ($data['role'] === 'otros') {
        // Por si existía algo previo:
        $request->session()->forget('intended_role');
        return redirect()->route('sales.consultants');
    }

    // Para los demás roles se mantiene el flujo normal (guardamos intención y vamos a login)
    session(['intended_role' => $data['role']]);
    return redirect()->route('users.login')->with('success', 'Seleccionaste: ' . $data['role']);
}

public function verifyPassword(Request $request)
{
    $data = $request->validate([
        'email'    => 'required|email',
        'password' => 'required|string',
    ]);

    // Reutilizamos el login de tu API externa
    $resp = Http::post(env('AUTH_API_URL', 'http://localhost:5000/api/users').'/login', $data);

    if ($resp->successful()) {
        return response()->json(['valid' => true]);
    }

    return response()->json([
        'valid' => false,
        'message' => $resp->json('message') ?? 'Credenciales inválidas'
    ], 422);
}


}
