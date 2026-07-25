<?php

namespace App\Http\Controllers;

use App\Models\Ingreso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IngresoController extends Controller
{
    public function index(Request $request)
    {
        $query = Ingreso::with(['ventaCarga.socio', 'user']);

        if ($request->filled('origen')) {
            $query->where('origen', $request->origen);
        }

        if ($request->filled('fecha_desde')) {
            $query->where('fecha', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->where('fecha', '<=', $request->fecha_hasta);
        }

        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where(function($q) use ($buscar) {
                $q->where('concepto', 'like', "%{$buscar}%")
                  ->orWhere('observaciones', 'like', "%{$buscar}%");
            });
        }

        $ingresos = $query->orderBy('fecha', 'desc')->orderBy('id', 'desc')->get();

        // Metrics summary
        $totalVentas = $ingresos->where('origen', 'venta_carga')->sum('monto');
        $totalCuotas = $ingresos->where('origen', 'cuota_socio')->sum('monto');
        $totalOtros = $ingresos->where('origen', 'otro')->sum('monto');
        $granTotal = $ingresos->sum('monto');

        return view('ingresos.index', compact('ingresos', 'totalVentas', 'totalCuotas', 'totalOtros', 'granTotal'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'fecha' => 'required|date',
            'concepto' => 'required|string|max:255',
            'monto' => 'required|numeric|min:0.01',
            'origen' => 'required|string|in:cuota_socio,otro',
            'observaciones' => 'nullable|string',
        ]);

        $data['user_id'] = Auth::id();

        Ingreso::create($data);

        return redirect()->route('ingresos.index')->with('success', 'Ingreso económico registrado exitosamente.');
    }

    public function destroy(Ingreso $ingreso)
    {
        if ($ingreso->origen === 'venta_carga' && $ingreso->venta_carga_id) {
            return back()->withErrors(['error' => 'Los ingresos generados por Venta de Cargas deben ser gestionados o eliminados desde el módulo de Ventas.']);
        }

        $ingreso->delete();

        return redirect()->route('ingresos.index')->with('success', 'Ingreso eliminado exitosamente.');
    }
}
