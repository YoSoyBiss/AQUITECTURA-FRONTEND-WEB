<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SalesController extends Controller
{
    public function index()
    {
        $response = Http::get('http://localhost:5000/api/sales');
        $sales = $response->successful() ? $response->json() : [];

        return view('sales.indexsales', compact('sales'));
    }

    public function create()
    {
        return view('sales.createsales');
    }

    public function store(Request $request)
    {
        $response = Http::post('http://localhost:5000/api/sales', [
            'total' => $request->input('total'),
            'userId' => $request->input('userId'),
            //'details' => json_decode($request->input('details'), true), // aseguramos que sea un array
            'details' => json_decode($request->input('details'), true),

        ]);

        if ($response->successful()) {
            return redirect()->route('sales.index')->with('success', 'Venta registrada');
        }

        return back()->withErrors(['error' => 'Error al registrar venta']);
    }

    public function edit($id)
    {
        $response = Http::get("http://localhost:5000/api/sales/$id");
        $sale = $response->successful() ? $response->json() : null;

        return view('sales.editsales', compact('sale'));
    }

    public function update(Request $request, $id)
    {
        Http::put("http://localhost:5000/api/sales/$id", [
            'total' => $request->input('total'),
            'userId' => $request->input('userId'),
            'details' => json_decode($request->input('details'), true),
        ]);

        return redirect()->route('sales.index');
    }

    public function destroy($id)
    {
        Http::delete("http://localhost:5000/api/sales/$id");
        return redirect()->route('sales.index');
    }

    public function show($id)
    {
        $response = Http::get("http://localhost:5000/api/sales/$id");
        $sale = $response->successful() ? $response->json() : null;

        return view('sales.showsales', compact('sale'));
    }
}
