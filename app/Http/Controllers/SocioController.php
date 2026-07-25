<?php

namespace App\Http\Controllers;

use App\Models\Socio;
use App\Models\Bocamina;
use Illuminate\Http\Request;

class SocioController extends Controller
{
    public function index(Request $request)
    {
        $query = Socio::with('bocamina');

        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where(function($q) use ($buscar) {
                $q->where('nombre', 'like', "%{$buscar}%")
                  ->orWhere('ci', 'like', "%{$buscar}%")
                  ->orWhere('codigo', 'like', "%{$buscar}%");
            });
        }

        if ($request->filled('bocamina_id')) {
            $query->where('bocamina_id', $request->bocamina_id);
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        $socios = $query->orderBy('nombre', 'asc')->get();
        $bocaminas = Bocamina::orderBy('nombre', 'asc')->get();

        return view('socios.index', compact('socios', 'bocaminas'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'codigo' => 'required|string|max:50|unique:socios,codigo',
            'ci' => 'required|string|max:20|unique:socios,ci',
            'nombre' => [
                'required',
                'string',
                'max:255',
                'regex:/^[A-ZÁÉÍÓÚÑ][a-zzáéíóúñA-ZÁÉÍÓÚÑ\']*(?:\s+[A-ZÁÉÍÓÚÑ][a-zzáéíóúñA-ZÁÉÍÓÚÑ\']*)*$/u'
            ],
            'telefono' => 'nullable|numeric|digits:8',
            'bocamina_id' => 'nullable|exists:bocaminas,id',
            'estado' => 'required|in:activo,inactivo',
            'observaciones' => 'nullable|string',
        ], [
            'nombre.regex' => 'Cada nombre y apellido debe comenzar con mayúscula (Ej. Juan Carlos Pérez).',
            'telefono.digits' => 'El teléfono debe tener exactamente 8 números.',
            'telefono.numeric' => 'El teléfono solo debe contener números.',
        ]);

        Socio::create($data);

        return redirect()->route('socios.index')->with('success', 'Socio registrado exitosamente.');
    }

    public function update(Request $request, Socio $socio)
    {
        $data = $request->validate([
            'codigo' => 'required|string|max:50|unique:socios,codigo,' . $socio->id,
            'ci' => 'required|string|max:20|unique:socios,ci,' . $socio->id,
            'nombre' => [
                'required',
                'string',
                'max:255',
                'regex:/^[A-ZÁÉÍÓÚÑ][a-zzáéíóúñA-ZÁÉÍÓÚÑ\']*(?:\s+[A-ZÁÉÍÓÚÑ][a-zzáéíóúñA-ZÁÉÍÓÚÑ\']*)*$/u'
            ],
            'telefono' => 'nullable|numeric|digits:8',
            'bocamina_id' => 'nullable|exists:bocaminas,id',
            'estado' => 'required|in:activo,inactivo',
            'observaciones' => 'nullable|string',
        ], [
            'nombre.regex' => 'Cada nombre y apellido debe comenzar con mayúscula (Ej. Juan Carlos Pérez).',
            'telefono.digits' => 'El teléfono debe tener exactamente 8 números.',
            'telefono.numeric' => 'El teléfono solo debe contener números.',
        ]);

        $socio->update($data);

        return redirect()->route('socios.index')->with('success', 'Socio actualizado exitosamente.');
    }

    public function destroy(Socio $socio)
    {
        if ($socio->anticipos()->exists() || $socio->pagos()->exists() || $socio->ventas()->exists()) {
            return back()->withErrors(['error' => 'No se puede eliminar el socio porque tiene registros de anticipos, pagos o ventas asociados.']);
        }

        $socio->delete();

        return redirect()->route('socios.index')->with('success', 'Socio eliminado exitosamente.');
    }
}
