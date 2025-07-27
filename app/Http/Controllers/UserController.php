<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $response = Http::get('http://localhost:5000/api/users');
        $users = $response->successful() ? $response->json() : [];

        return view('users.indexusers', compact('users'));
    }

    public function create()
    {
        return view('users.createusers');
    }

    public function store(Request $request)
    {
        Http::post('http://localhost:5000/api/users/register', [
            'name' => $request->name,
            'password' => $request->password,
            'role' => $request->role
        ]);

        return redirect()->route('users.indexusers');
    }

    public function edit($id)
    {
        $response = Http::get("http://localhost:5000/api/users");
        $user = collect($response->json())->firstWhere('_id', $id);

        return view('users.editusers', compact('user'));
    }

    public function update(Request $request, $id)
    {
        Http::put("http://localhost:5000/api/users/$id", [
            'name' => $request->name,
            'password' => $request->password, // opcional
            'role' => $request->role
        ]);

        return redirect()->route('users.indexusers');
    }

    public function destroy($id)
    {
        Http::delete("http://localhost:5000/api/users/$id");
        return redirect()->route('users.indexusers');
    }
}
