<?php

namespace App\Http\Controllers;

use App\Models\VentaCarga;
use App\Models\Ingreso;
use App\Models\Socio;
use App\Models\Bocamina;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class VentaCargaController extends Controller
{
    public function index(Request $request)
    {
        $query = VentaCarga::with(['socio', 'bocamina', 'user']);

        if ($request->filled('socio_id')) {
            $query->where('socio_id', $request->socio_id);
        }

        if ($request->filled('bocamina_id')) {
            $query->where('bocamina_id', $request->bocamina_id);
        }

        if ($request->filled('tipo_mineral')) {
            $query->where('tipo_mineral', $request->tipo_mineral);
        }

        if ($request->filled('fecha_desde')) {
            $query->where('fecha', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->where('fecha', '<=', $request->fecha_hasta);
        }

        $ventas = $query->orderBy('fecha', 'desc')->orderBy('id', 'desc')->get();
        $socios = Socio::where('estado', 'activo')->orderBy('nombre')->get();
        $bocaminas = Bocamina::orderBy('nombre')->get();
        $compradores = \App\Models\Comprador::where('estado', 'activo')->orderBy('razon_social')->get();

        // Unique mineral types for filter
        $minerales = ['Complejo (Zn-Pb-Ag)', 'Zinc (Zn)', 'Plomo (Pb)', 'Plata (Ag)', 'Estaño (Sn)', 'Cobre (Cu)', 'Oro (Au)'];

        return view('ventas.index', compact('ventas', 'socios', 'bocaminas', 'compradores', 'minerales'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'numero_venta' => 'required|string|max:50|unique:venta_cargas,numero_venta',
            'fecha' => 'required|date',
            'socio_id' => 'required|exists:socios,id',
            'bocamina_id' => 'required|exists:bocaminas,id',
            'comprador_id' => 'nullable|exists:compradores,id',
            'tipo_mineral' => 'required|string|max:100',
            'cantidad' => 'nullable|integer|min:1',
            'peso_neto' => 'required|numeric|min:0.01',
            'precio_unitario' => 'required|numeric|min:0.01',
            'comprador' => 'required|string|max:255',
            'observaciones' => 'nullable|string',
        ]);

        $data['total_vendido'] = (float)$data['peso_neto'] * (float)$data['precio_unitario'];
        $data['user_id'] = Auth::id();

        DB::transaction(function() use ($data) {
            $venta = VentaCarga::create($data);

            // Automatically generate an Ingreso record for this sale!
            Ingreso::create([
                'fecha' => $venta->fecha,
                'concepto' => "Venta de Mineral N° {$venta->numero_venta} - {$venta->tipo_mineral} ({$venta->socio->nombre})",
                'monto' => $venta->total_vendido,
                'origen' => 'venta_carga',
                'venta_carga_id' => $venta->id,
                'observaciones' => "Venta realizada a {$venta->comprador}. Peso: {$venta->peso_neto} Tn @ Bs. {$venta->precio_unitario}/Tn.",
                'user_id' => Auth::id(),
            ]);
        });

        return redirect()->route('ventas-cargas.index')->with('success', 'Venta de carga registrada e ingreso económico generado automáticamente.');
    }

    public function update(Request $request, VentaCarga $ventasCarga)
    {
        $data = $request->validate([
            'numero_venta' => 'required|string|max:50|unique:venta_cargas,numero_venta,' . $ventasCarga->id,
            'fecha' => 'required|date',
            'socio_id' => 'required|exists:socios,id',
            'bocamina_id' => 'required|exists:bocaminas,id',
            'tipo_mineral' => 'required|string|max:100',
            'cantidad' => 'nullable|integer|min:1',
            'peso_neto' => 'required|numeric|min:0.01',
            'precio_unitario' => 'required|numeric|min:0.01',
            'comprador' => 'required|string|max:255',
            'observaciones' => 'nullable|string',
        ]);

        $data['total_vendido'] = (float)$data['peso_neto'] * (float)$data['precio_unitario'];

        DB::transaction(function() use ($ventasCarga, $data) {
            $ventasCarga->update($data);

            // Update auto-generated Ingreso
            if ($ventasCarga->ingreso) {
                $ventasCarga->ingreso->update([
                    'fecha' => $ventasCarga->fecha,
                    'concepto' => "Venta de Mineral N° {$ventasCarga->numero_venta} - {$ventasCarga->tipo_mineral} ({$ventasCarga->socio->nombre})",
                    'monto' => $ventasCarga->total_vendido,
                    'observaciones' => "Venta realizada a {$ventasCarga->comprador}. Peso: {$ventasCarga->peso_neto} Tn @ Bs. {$ventasCarga->precio_unitario}/Tn.",
                ]);
            }
        });

        return redirect()->route('ventas-cargas.index')->with('success', 'Venta de carga e ingreso actualizado exitosamente.');
    }

    public function destroy(VentaCarga $ventasCarga)
    {
        $ventasCarga->delete(); // Cascades auto-generated Ingreso
        return redirect()->route('ventas-cargas.index')->with('success', 'Venta de carga eliminada exitosamente.');
    }

    public function recibo(VentaCarga $ventasCarga)
    {
        $ventasCarga->load(['socio.bocamina', 'bocamina', 'user']);
        return view('ventas.recibo', compact('ventasCarga'));
    }
}
