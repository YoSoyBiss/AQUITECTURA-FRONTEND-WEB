<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Http;

class SalesController extends Controller
{
    private $apiBase = 'http://localhost:5000/api/sales';

    public function index()
    {
        $response = Http::get($this->apiBase);
        $sales = $response->successful() ? $response->json() : [];

        return view('sales.index', compact('sales'));
    }

    public function create()
{
    $response = Http::get('http://localhost:8000/api/products');

    $products = $response->successful() ? $response->json() : [];

    return view('sales.create', compact('products'));
}


    public function store(Request $request)
{
    // ✅ Validar que 'details' sea un array, no JSON string
    $request->validate([
        'total' => 'required|numeric|min:0',
        'userId' => 'required|string',
        'details' => 'required|array',
        'details.*.productId' => 'required|integer',
        'details.*.quantity' => 'required|integer|min:1',
        'details.*.unitPrice' => 'required|numeric|min:0',
    ]);

    // ✅ Ya no uses json_decode aquí
    $response = Http::post('http://localhost:5000/api/sales', [
        'total' => $request->input('total'),
        'userId' => $request->input('userId'),
        'details' => $request->input('details'), // array directamente
    ]);

    if ($response->successful()) {
        return redirect()->route('sales.index')->with('success', 'Venta registrada con éxito');
    }

    return back()->withErrors(['error' => 'Error al registrar la venta'])->withInput();
}


    public function edit($id)
    {
        $response = Http::get("{$this->apiBase}/{$id}");
        $sale = $response->successful() ? $response->json() : null;

        if (!$sale) {
            return redirect()->route('sales.index')->withErrors('Venta no encontrada');
        }

        return view('sales.edit', compact('sale'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'total' => 'required|numeric|min:0',
            'userId' => 'required|string',
            'details' => 'required|json'
        ]);

        $response = Http::put("{$this->apiBase}/{$id}", [
            'total' => $request->input('total'),
            'userId' => $request->input('userId'),
            'details' => json_decode($request->input('details'), true),
        ]);

        if ($response->successful()) {
            return redirect()->route('sales.index')->with('success', 'Venta actualizada correctamente');
        }

        return back()->withErrors(['error' => 'Error al actualizar la venta'])->withInput();
    }

    public function destroy($id)
    {
        $response = Http::delete("{$this->apiBase}/{$id}");

        if ($response->successful()) {
            return redirect()->route('sales.index')->with('success', 'Venta eliminada correctamente');
        }

        return redirect()->route('sales.index')->withErrors('Error al eliminar la venta');
    }

    public function show($id)
    {
        $response = Http::get("{$this->apiBase}/{$id}");
        $sale = $response->successful() ? $response->json() : null;

        if (!$sale) {
            return redirect()->route('sales.index')->withErrors('Venta no encontrada');
        }

        return view('sales.showsales', compact('sale'));
    }


    public function reporte()
{
    $response = Http::get('http://localhost:5000/api/sales');

    if (!$response->successful()) {
        return back()->withErrors(['error' => 'Error al obtener las ventas']);
    }

    $ventas = $response->json();

    return view('sales.reporte', compact('ventas')); // O usa 'sales.report'
}


public function descargarPDF()
{
    $response = Http::get('http://localhost:5000/api/sales');

    if (!$response->successful()) {
        return back()->withErrors(['error' => 'Error al obtener las ventas']);
    }

    $ventas = $response->json();

    // Generar la vista PDF
    $pdf = Pdf::loadView('sales.report-pdf', compact('ventas'));

    return $pdf->download('reporte_ventas.pdf');
}

}
