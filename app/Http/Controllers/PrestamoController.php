<?php

namespace App\Http\Controllers;

use App\Models\Prestamo;
use App\Models\CuotaPrestamo;
use App\Models\Socio;
use App\Models\Trabajador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PrestamoController extends Controller
{
    public function index(Request $request)
    {
        $query = Prestamo::with(['socio', 'trabajador', 'cuotas', 'user']);

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        $prestamos = $query->orderBy('fecha_otorgamiento', 'desc')->get();
        $socios = Socio::where('estado', 'activo')->orderBy('nombre')->get();
        $trabajadores = Trabajador::where('estado', 'activo')->orderBy('nombre')->get();

        return view('prestamos.index', compact('prestamos', 'socios', 'trabajadores'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'tipo_beneficiario' => 'required|in:socio,trabajador',
            'socio_id' => 'nullable|required_if:tipo_beneficiario,socio|exists:socios,id',
            'trabajador_id' => 'nullable|required_if:tipo_beneficiario,trabajador|exists:trabajadores,id',
            'monto_total' => 'required|numeric|min:1',
            'total_cuotas' => 'required|integer|min:1|max:60',
            'fecha_otorgamiento' => 'required|date',
            'observaciones' => 'nullable|string',
        ]);

        $montoTotal = (float) $data['monto_total'];
        $totalCuotas = (int) $data['total_cuotas'];
        $montoCuota = round($montoTotal / $totalCuotas, 2);

        $numeroPrestamo = 'PRST-' . rand(10000, 99999);

        DB::transaction(function() use ($request, $data, $numeroPrestamo, $montoTotal, $totalCuotas, $montoCuota) {
            $prestamo = Prestamo::create([
                'numero_prestamo' => $numeroPrestamo,
                'socio_id' => $request->tipo_beneficiario === 'socio' ? $request->socio_id : null,
                'trabajador_id' => $request->tipo_beneficiario === 'trabajador' ? $request->trabajador_id : null,
                'monto_total' => $montoTotal,
                'monto_cuota' => $montoCuota,
                'total_cuotas' => $totalCuotas,
                'cuotas_pagadas' => 0,
                'saldo_pendiente' => $montoTotal,
                'fecha_otorgamiento' => $request->fecha_otorgamiento,
                'estado' => 'activo',
                'observaciones' => $request->observaciones,
                'user_id' => Auth::id(),
            ]);

            // Create Amortization Installments Plan
            $fechaBase = \Carbon\Carbon::parse($request->fecha_otorgamiento);
            for ($i = 1; $i <= $totalCuotas; $i++) {
                CuotaPrestamo::create([
                    'prestamo_id' => $prestamo->id,
                    'numero_cuota' => $i,
                    'monto_cuota' => $montoCuota,
                    'fecha_vencimiento' => $fechaBase->copy()->addMonths($i)->toDateString(),
                    'estado' => 'pendiente',
                ]);
            }
        });

        return redirect()->route('prestamos.index')->with('success', 'Préstamo otorgado y plan de cuotas generado exitosamente.');
    }

    public function pagarCuota(Request $request, CuotaPrestamo $cuota)
    {
        if ($cuota->estado === 'pagado') {
            return back()->withErrors(['error' => 'Esta cuota ya ha sido pagada previamente.']);
        }

        DB::transaction(function() use ($cuota) {
            $cuota->estado = 'pagado';
            $cuota->fecha_pago = now()->toDateString();
            $cuota->save();

            $prestamo = $cuota->prestamo;
            $prestamo->cuotas_pagadas += 1;
            $prestamo->saldo_pendiente = max(0, $prestamo->saldo_pendiente - $cuota->monto_cuota);

            if ($prestamo->cuotas_pagadas >= $prestamo->total_cuotas || $prestamo->saldo_pendiente <= 0) {
                $prestamo->estado = 'completado';
            }
            $prestamo->save();
        });

        return redirect()->route('prestamos.index')->with('success', 'Cuota de préstamo cobrada exitosamente.');
    }

    public function destroy(Prestamo $prestamo)
    {
        if ($prestamo->cuotas_pagadas > 0) {
            return back()->withErrors(['error' => 'No se puede eliminar un préstamo con cuotas cobradas.']);
        }

        $prestamo->delete();
        return redirect()->route('prestamos.index')->with('success', 'Préstamo cancelado exitosamente.');
    }
}
