<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class RegisterController extends Controller
{
    // Mostrar formulario de registro
    public function showRegistrationForm()
    {
        return view('register');
    }

    // Procesar registro
    public function register(Request $request)
    {
        // Validación de campos
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|string'
        ]);

        try {
            // Crear usuario
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'role'     => $request->role
            ]);

            Auth::login($user); // iniciar sesión automáticamente

            // Redirigir según el rol
            switch ($user->role) {
                case 'admin':
                    return redirect('/admin/dashboard')->with('success', '¡Registro exitoso como administrador!');
                case 'seller':
                    return redirect('/seller/home')->with('success', '¡Registro exitoso como vendedor!');
                default:
                    return redirect('/home')->with('success', '¡Cuenta registrada correctamente!');
            }

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al registrar. Inténtalo nuevamente.');
        }
    }
}
