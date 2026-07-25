<?php

namespace App\Http\Controllers;

use App\Models\DistribucionUtilidad;
use App\Models\DetalleUtilidadSocio;
use App\Models\Socio;
use App\Models\Ingreso;
use App\Models\Egreso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UtilidadController extends Controller
{
    public function index()
    {
        $distribuciones = DistribucionUtilidad::with(['detalles.socio', 'user'])
            ->orderBy('fecha', 'desc')
            ->get();

        $socios = Socio::where('estado', 'activo')->get();
        $totalParticipacion = $socios->sum('porcentaje_participacion');

        // Estimate current period gross profit
        $totalIngresos = Ingreso::sum('monto');
        $totalEgresos = Egreso::sum('monto');
        $utilidadBrutaEstimada = max(0, $totalIngresos - $totalEgresos);

        return view('utilidades.index', compact('distribuciones', 'socios', 'totalParticipacion', 'utilidadBrutaEstimada'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'periodo' => 'required|string|max:100',
            'fecha' => 'required|date',
            'utilidad_bruta_total' => 'required|numeric|min:0.01',
            'deducciones_reserva' => 'nullable|numeric|min:0',
            'observaciones' => 'nullable|string',
        ]);

        $socios = Socio::where('estado', 'activo')->get();

        if ($socios->isEmpty()) {
            return back()->withErrors(['error' => 'No existen socios activos registrados para realizar la distribución.'])->withInput();
        }

        $utilidadBruta = (float) $request->utilidad_bruta_total;
        $reserva = (float) ($request->deducciones_reserva ?? 0.00);

        if ($reserva >= $utilidadBruta) {
            return back()->withErrors(['deducciones_reserva' => 'Las reservas no pueden ser mayores o iguales a la utilidad bruta.'])->withInput();
        }

        $netoDistribuir = $utilidadBruta - $reserva;
        $numeroDist = 'DIST-UTIL-' . rand(1000, 9999);

        DB::transaction(function() use ($request, $socios, $numeroDist, $utilidadBruta, $reserva, $netoDistribuir) {
            $distribucion = DistribucionUtilidad::create([
                'numero_distribucion' => $numeroDist,
                'periodo' => $request->periodo,
                'fecha' => $request->fecha,
                'utilidad_bruta_total' => $utilidadBruta,
                'deducciones_reserva' => $reserva,
                'utilidad_neta_distribuir' => $netoDistribuir,
                'observaciones' => $request->observaciones,
                'user_id' => Auth::id(),
            ]);

            $totalParticipacion = $socios->sum('porcentaje_participacion');

            foreach ($socios as $socio) {
                // If participation percentage is 0, give equal share
                $pct = $totalParticipacion > 0 ? (float)$socio->porcentaje_participacion : (100 / count($socios));
                $montoSocio = round(($netoDistribuir * $pct) / 100, 2);

                DetalleUtilidadSocio::create([
                    'distribucion_utilidad_id' => $distribucion->id,
                    'socio_id' => $socio->id,
                    'porcentaje_participacion' => round($pct, 2),
                    'monto_utilidad' => $montoSocio,
                    'estado' => 'pagado',
                ]);
            }
        });

        return redirect()->route('utilidades.index')->with('success', 'Distribución de utilidades y dividendos generada exitosamente.');
    }

    public function destroy(DistribucionUtilidad $utilidad)
    {
        $utilidad->delete();
        return redirect()->route('utilidades.index')->with('success', 'Distribución de utilidades anulada.');
    }
}
