<?php

namespace App\Http\Controllers;

use App\Models\Trabajador;
use App\Models\Bocamina;
use Illuminate\Http\Request;

class TrabajadorController extends Controller
{
    public function index(Request $request)
    {
        $query = Trabajador::with('bocamina');

        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where(function($q) use ($buscar) {
                $q->where('nombre', 'like', "%{$buscar}%")
                  ->orWhere('ci', 'like', "%{$buscar}%");
            });
        }

        if ($request->filled('bocamina_id')) {
            $query->where('bocamina_id', $request->bocamina_id);
        }

        if ($request->filled('cargo')) {
            $query->where('cargo', $request->cargo);
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        $trabajadores = $query->orderBy('nombre', 'asc')->get();
        $bocaminas = Bocamina::orderBy('nombre', 'asc')->get();

        return view('trabajadores.index', compact('trabajadores', 'bocaminas'));
    }

    public function show(Trabajador $trabajador)
    {
        $trabajador->load([
            'bocamina',
            'contratos' => function($q) { $q->orderBy('created_at', 'desc'); },
            'trabajos' => function($q) { $q->orderBy('fecha', 'desc'); },
            'anticipos' => function($q) { $q->orderBy('fecha', 'desc'); },
            'pagos' => function($q) { $q->orderBy('fecha', 'desc'); }
        ]);

        $totalGanado = $trabajador->trabajos->sum('subtotal') + $trabajador->pagos->sum('bruto');
        $totalAnticipos = $trabajador->anticipos->sum('monto');
        $totalLiquidoCobrado = $trabajador->pagos->sum('neto');
        $anticiposPendientes = $trabajador->anticipos->sum('saldo');

        return view('trabajadores.show', compact(
            'trabajador',
            'totalGanado',
            'totalAnticipos',
            'totalLiquidoCobrado',
            'anticiposPendientes'
        ));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'ci' => 'required|string|max:255|unique:trabajadores,ci',
            'nombre' => [
                'required',
                'string',
                'max:255',
                'regex:/^[A-ZÁÉÍÓÚÑ][a-zzáéíóúñA-ZÁÉÍÓÚÑ\']*(?:\s+[A-ZÁÉÍÓÚÑ][a-zzáéíóúñA-ZÁÉÍÓÚÑ\']*)*$/u'
            ],
            'cargo' => 'required|string|in:trabajador_bocamina,sereno,chofer,personal_admin',
            'fecha_ingreso' => 'nullable|date',
            'modalidad_pago' => 'required|string|in:por_produccion,sueldo_fijo',
            'sueldo_base' => 'nullable|numeric|min:0',
            'telefono' => 'nullable|numeric|digits:8',
            'bocamina_id' => 'required|exists:bocaminas,id',
            'estado' => 'required|in:activo,inactivo',
            'observaciones' => 'nullable|string',
        ], [
            'nombre.regex' => 'Cada nombre y apellido debe comenzar con mayúscula (Ej. Juan Carlos Pérez).',
            'telefono.digits' => 'El teléfono debe tener exactamente 8 números.',
            'telefono.numeric' => 'El teléfono solo debe contener números.',
        ]);

        Trabajador::create($data);

        return redirect()->route('trabajadores.index')->with('success', 'Personal registrado exitosamente.');
    }

    public function update(Request $request, Trabajador $trabajador)
    {
        $data = $request->validate([
            'ci' => 'required|string|max:255|unique:trabajadores,ci,' . $trabajador->id,
            'nombre' => [
                'required',
                'string',
                'max:255',
                'regex:/^[A-ZÁÉÍÓÚÑ][a-zzáéíóúñA-ZÁÉÍÓÚÑ\']*(?:\s+[A-ZÁÉÍÓÚÑ][a-zzáéíóúñA-ZÁÉÍÓÚÑ\']*)*$/u'
            ],
            'cargo' => 'required|string|in:trabajador_bocamina,sereno,chofer,personal_admin',
            'fecha_ingreso' => 'nullable|date',
            'modalidad_pago' => 'required|string|in:por_produccion,sueldo_fijo',
            'sueldo_base' => 'nullable|numeric|min:0',
            'telefono' => 'nullable|numeric|digits:8',
            'bocamina_id' => 'required|exists:bocaminas,id',
            'estado' => 'required|in:activo,inactivo',
            'observaciones' => 'nullable|string',
        ], [
            'nombre.regex' => 'Cada nombre y apellido debe comenzar con mayúscula (Ej. Juan Carlos Pérez).',
            'telefono.digits' => 'El teléfono debe tener exactamente 8 números.',
            'telefono.numeric' => 'El teléfono solo debe contener números.',
        ]);

        $trabajador->update($data);

        return redirect()->route('trabajadores.index')->with('success', 'Personal actualizado exitosamente.');
    }

    public function destroy(Trabajador $trabajador)
    {
        // Block delete if they have payments, advances, or jobs linked
        if ($trabajador->trabajos()->exists() || $trabajador->anticipos()->exists() || $trabajador->pagos()->exists()) {
            return back()->withErrors(['error' => 'No se puede eliminar el trabajador porque tiene registros de trabajos, anticipos o pagos asociados.']);
        }

        $trabajador->delete();

        return redirect()->route('trabajadores.index')->with('success', 'Trabajador eliminado exitosamente.');
    }
}
