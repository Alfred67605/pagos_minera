<?php

namespace App\Http\Controllers;

use App\Models\Caja;
use App\Models\CajaMovimiento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CajaController extends Controller
{
    public function index()
    {
        $cajas = Caja::withCount('movimientos')->get();
        foreach ($cajas as $caja) {
            $caja->total_ingresos = $caja->movimientos()->where('tipo', 'ingreso')->sum('monto');
            $caja->total_egresos = $caja->movimientos()->where('tipo', 'egreso')->sum('monto');
            $caja->total_anticipos = $caja->movimientos()->where('tipo', 'egreso')->where(function($q){
                $q->where('categoria', 'LIKE', '%Anticipo%')->orWhere('referencia_tipo', 'anticipo');
            })->sum('monto');
            $caja->total_planillas = $caja->movimientos()->where('tipo', 'egreso')->where(function($q){
                $q->where('categoria', 'LIKE', '%Pago%')->orWhere('referencia_tipo', 'pago_planilla');
            })->sum('monto');
        }
        return view('cajas.index', compact('cajas'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'tipo' => 'required|in:caja_general,caja_chica',
            'saldo_inicial' => 'required|numeric|min:0',
        ]);

        $data['saldo_actual'] = $data['saldo_inicial'];
        $data['estado'] = 'abierta';

        DB::transaction(function() use ($data) {
            $caja = Caja::create($data);

            if ($data['saldo_inicial'] > 0) {
                CajaMovimiento::create([
                    'caja_id' => $caja->id,
                    'tipo' => 'ingreso',
                    'monto' => $data['saldo_inicial'],
                    'concepto' => 'Apertura de Caja - Saldo Inicial',
                    'categoria' => 'Saldo Inicial',
                    'fecha' => now()->toDateString(),
                    'user_id' => Auth::id(),
                ]);
            }
        });

        return redirect()->route('cajas.index')->with('success', 'Caja registrada y abierta exitosamente.');
    }

    public function show(Caja $caja, Request $request)
    {
        $query = $caja->movimientos()->with('user');

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->filled('fecha_desde')) {
            $query->where('fecha', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->where('fecha', '<=', $request->fecha_hasta);
        }

        $movimientos = $query->orderBy('fecha', 'desc')->orderBy('id', 'desc')->get();

        $totalIngresos = $caja->movimientos()->where('tipo', 'ingreso')->sum('monto');
        $totalEgresos = $caja->movimientos()->where('tipo', 'egreso')->sum('monto');

        return view('cajas.show', compact('caja', 'movimientos', 'totalIngresos', 'totalEgresos'));
    }

    public function registrarMovimiento(Request $request, Caja $caja)
    {
        if ($caja->estado !== 'abierta') {
            return back()->withErrors(['error' => 'No se pueden registrar movimientos en una caja cerrada.']);
        }

        $request->validate([
            'tipo' => 'required|in:ingreso,egreso',
            'monto' => 'required|numeric|min:0.01',
            'concepto' => 'required|string|max:255',
            'categoria' => 'nullable|string|max:255',
            'fecha' => 'required|date',
        ]);

        $monto = (float) $request->monto;

        if ($request->tipo === 'egreso' && $caja->saldo_actual < $monto) {
            return back()->withErrors(['monto' => 'El monto del egreso supera el saldo actual disponible en la caja.'])->withInput();
        }

        DB::transaction(function() use ($caja, $request, $monto) {
            CajaMovimiento::create([
                'caja_id' => $caja->id,
                'tipo' => $request->tipo,
                'monto' => $monto,
                'concepto' => $request->concepto,
                'categoria' => $request->categoria ?? 'Movimiento Manual',
                'fecha' => $request->fecha,
                'user_id' => Auth::id(),
            ]);

            if ($request->tipo === 'ingreso') {
                $caja->saldo_actual += $monto;
            } else {
                $caja->saldo_actual -= $monto;
            }
            $caja->save();
        });

        return redirect()->route('cajas.show', $caja->id)->with('success', 'Movimiento de caja registrado exitosamente.');
    }

    public function toggleEstado(Caja $caja)
    {
        $caja->estado = $caja->estado === 'abierta' ? 'cerrada' : 'abierta';
        $caja->save();

        $estadoTxt = $caja->estado === 'abierta' ? 'reabierta' : 'cerrada (arqueo completado)';
        return redirect()->route('cajas.index')->with('success', "La caja '{$caja->nombre}' ha sido {$estadoTxt}.");
    }

    public function destroy(Caja $caja)
    {
        if ($caja->movimientos()->count() > 1) {
            return back()->withErrors(['error' => 'No se puede eliminar una caja con movimientos contables registrados.']);
        }

        $caja->delete();
        return redirect()->route('cajas.index')->with('success', 'Caja eliminada exitosamente.');
    }

    public function recargar(Request $request, Caja $caja)
    {
        if ($caja->estado !== 'abierta') {
            return back()->withErrors(['error' => 'No se puede recargar una caja que se encuentra cerrada.']);
        }

        $request->validate([
            'monto' => 'required|numeric|min:0.01',
            'concepto' => 'required|string|max:255',
            'origen' => 'nullable|string|max:255',
        ]);

        $monto = (float) $request->monto;

        DB::transaction(function() use ($caja, $request, $monto) {
            CajaMovimiento::create([
                'caja_id' => $caja->id,
                'tipo' => 'ingreso',
                'monto' => $monto,
                'concepto' => 'Recarga de Fondos: ' . $request->concepto . ($request->origen ? ' (Origen: ' . $request->origen . ')' : ''),
                'categoria' => 'Recarga de Fondos',
                'fecha' => now()->toDateString(),
                'user_id' => Auth::id(),
            ]);

            $caja->saldo_actual += $monto;
            $caja->save();
        });

        return back()->with('success', "Se han recargado Bs. " . number_format($monto, 2) . " exitosamente en la caja {$caja->nombre}.");
    }
}
