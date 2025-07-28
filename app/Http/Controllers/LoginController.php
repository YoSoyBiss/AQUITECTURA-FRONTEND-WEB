<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // Mostrar formulario login
    public function showLoginForm()
    {
        return view('login');
    }

    // Procesar login
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            switch ($user->role) {
                case 'admin':
                    return redirect('/admin/dashboard')->with('success', 'Bienvenido Administrador');
                case 'seller':
                    return redirect('/seller/home')->with('success', 'Bienvenido Vendedor');
                default:
                    return redirect('/home')->with('success', 'Inicio de sesión exitoso');
            }
        }

        return back()->with('error', 'Credenciales incorrectas')->withInput();
    }

    // Cerrar sesión
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login')->with('success', 'Sesión cerrada correctamente');
    }
}
