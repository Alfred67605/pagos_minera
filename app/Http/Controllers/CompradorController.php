<?php

namespace App\Http\Controllers;

use App\Models\Comprador;
use Illuminate\Http\Request;

class CompradorController extends Controller
{
    public function index(Request $request)
    {
        $query = Comprador::query();

        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where('razon_social', 'like', "%{$buscar}%")
                  ->orWhere('nit_ci', 'like', "%{$buscar}%")
                  ->orWhere('contacto_nombre', 'like', "%{$buscar}%");
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        $compradores = $query->orderBy('razon_social')->get();

        return view('compradores.index', compact('compradores'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'razon_social' => 'required|string|max:255',
            'nit_ci' => 'nullable|string|max:50',
            'contacto_nombre' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'direccion' => 'nullable|string',
            'notas' => 'nullable|string',
            'estado' => 'required|in:activo,inactivo',
        ]);

        Comprador::create($data);

        return redirect()->route('compradores.index')->with('success', 'Comprador de mineral registrado exitosamente.');
    }

    public function update(Request $request, Comprador $comprador)
    {
        $data = $request->validate([
            'razon_social' => 'required|string|max:255',
            'nit_ci' => 'nullable|string|max:50',
            'contacto_nombre' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'direccion' => 'nullable|string',
            'notas' => 'nullable|string',
            'estado' => 'required|in:activo,inactivo',
        ]);

        $comprador->update($data);

        return redirect()->route('compradores.index')->with('success', 'Comprador actualizado exitosamente.');
    }

    public function destroy(Comprador $comprador)
    {
        if ($comprador->ventas()->exists()) {
            return back()->withErrors(['error' => 'No se puede eliminar este comprador porque tiene ventas asociadas.']);
        }

        $comprador->delete();

        return redirect()->route('compradores.index')->with('success', 'Comprador eliminado exitosamente.');
    }
}
