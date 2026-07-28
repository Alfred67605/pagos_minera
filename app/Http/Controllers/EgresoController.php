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
        $query = Egreso::with(['caja', 'categoria', 'bocamina', 'user']);

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
        $bocaminas = \App\Models\Bocamina::orderBy('nombre')->get();

        return view('egresos.index', compact('egresos', 'categorias', 'cajas', 'bocaminas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'categoria_id' => 'required|exists:categoria_egresos,id',
            'caja_id' => 'required|exists:cajas,id',
            'monto' => 'required|numeric|min:0.01',
            'concepto' => 'required|string|max:255',
            'fecha' => 'required|date',
            'presentacion' => 'nullable|string|in:volqueta,saco,concentrado,bruto',
            'bocamina_id' => 'nullable|exists:bocaminas,id',
            'peso_bruto' => 'nullable|numeric|min:0',
            'tara' => 'nullable|numeric|min:0',
            'peso_neto' => 'nullable|numeric|min:0',
            'ley_mineral' => 'nullable|string|max:255',
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

        $pesoBruto = $request->filled('peso_bruto') ? (float)$request->peso_bruto : null;
        $tara = $request->filled('tara') ? (float)$request->tara : null;
        $pesoNeto = $request->filled('peso_neto') ? (float)$request->peso_neto : (($pesoBruto !== null && $tara !== null) ? max(0, $pesoBruto - $tara) : $pesoBruto);

        DB::transaction(function() use ($request, $monto, $categoria, $caja, $pesoBruto, $tara, $pesoNeto) {
            $egreso = Egreso::create([
                'caja_id' => $caja->id,
                'categoria_id' => $request->categoria_id,
                'monto' => $monto,
                'concepto' => $request->concepto,
                'fecha' => $request->fecha,
                'presentacion' => $request->presentacion ?? 'saco',
                'bocamina_id' => $request->bocamina_id,
                'peso_bruto' => $pesoBruto,
                'tara' => $tara,
                'peso_neto' => $pesoNeto,
                'ley_mineral' => $request->ley_mineral,
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
                'concepto' => "Egreso ({$egreso->presentacion}): {$request->concepto}",
                'categoria' => $categoria->nombre,
                'referencia_tipo' => 'egreso',
                'referencia_id' => $egreso->id,
                'fecha' => $request->fecha,
                'user_id' => Auth::id(),
            ]);
        });

        return redirect()->route('egresos.index')->with('success', 'Egreso/Compra de mineral registrado exitosamente.');
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

