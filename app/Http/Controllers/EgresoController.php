<?php

namespace App\Http\Controllers;

use App\Models\Egreso;
use App\Models\CategoriaEgreso;
use App\Models\Caja;
use App\Models\CajaMovimiento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EgresoController extends Controller
{
    public function index(Request $request)
    {
        $query = Egreso::with(['caja', 'categoria', 'user']);

        if ($request->filled('categoria_id')) {
            $query->where('categoria_id', $request->categoria_id);
        }

        if ($request->filled('caja_id')) {
            $query->where('caja_id', $request->caja_id);
        }

        if ($request->filled('fecha_desde')) {
            $query->where('fecha', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->where('fecha', '<=', $request->fecha_hasta);
        }

        $egresos = $query->orderBy('fecha', 'desc')->orderBy('id', 'desc')->get();
        $categorias = CategoriaEgreso::orderBy('nombre')->get();
        $cajas = Caja::where('estado', 'abierta')->get();

        return view('egresos.index', compact('egresos', 'categorias', 'cajas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'categoria_id' => 'required|exists:categoria_egresos,id',
            'caja_id' => 'required|exists:cajas,id',
            'monto' => 'required|numeric|min:0.01',
            'concepto' => 'required|string|max:255',
            'fecha' => 'required|date',
            'comprobante_numero' => 'nullable|string|max:100',
            'proveedor' => 'nullable|string|max:255',
            'observaciones' => 'nullable|string',
        ]);

        $monto = (float) $request->monto;
        $categoria = CategoriaEgreso::find($request->categoria_id);
        $caja = Caja::findOrFail($request->caja_id);

        if ($caja->saldo_actual < $monto) {
            return back()->withErrors(['monto' => "Saldo insuficiente en la caja '{$caja->nombre}' (Saldo actual: Bs. {$caja->saldo_actual})."])->withInput();
        }

        DB::transaction(function() use ($request, $monto, $categoria, $caja) {
            $egreso = Egreso::create([
                'caja_id' => $caja->id,
                'categoria_id' => $request->categoria_id,
                'monto' => $monto,
                'concepto' => $request->concepto,
                'fecha' => $request->fecha,
                'comprobante_numero' => $request->comprobante_numero,
                'proveedor' => $request->proveedor,
                'observaciones' => $request->observaciones,
                'user_id' => Auth::id(),
            ]);

            $caja->saldo_actual -= $monto;
            $caja->save();

            CajaMovimiento::create([
                'caja_id' => $caja->id,
                'tipo' => 'egreso',
                'monto' => $monto,
                'concepto' => "Egreso: {$request->concepto}",
                'categoria' => $categoria->nombre,
                'referencia_tipo' => 'egreso',
                'referencia_id' => $egreso->id,
                'fecha' => $request->fecha,
                'user_id' => Auth::id(),
            ]);
        });

        return redirect()->route('egresos.index')->with('success', 'Egreso/Gasto operativo registrado exitosamente.');
    }

    public function storeCategoria(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255|unique:categoria_egresos,nombre',
            'descripcion' => 'nullable|string',
        ]);

        CategoriaEgreso::create($data);

        return redirect()->route('egresos.index')->with('success', 'Categoría de egreso creada exitosamente.');
    }

    public function destroy(Egreso $egreso)
    {
        DB::transaction(function() use ($egreso) {
            // Reverse balance deduction
            if ($egreso->caja_id && $egreso->caja) {
                $egreso->caja->saldo_actual += $egreso->monto;
                $egreso->caja->save();
                CajaMovimiento::where('referencia_tipo', 'egreso')->where('referencia_id', $egreso->id)->delete();
            }

            $egreso->delete();
        });

        return redirect()->route('egresos.index')->with('success', 'Egreso eliminado y saldo devuelto a la caja.');
    }
}

