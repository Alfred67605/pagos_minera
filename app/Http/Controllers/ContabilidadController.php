<?php

namespace App\Http\Controllers;

use App\Models\CuentaContable;
use App\Models\AsientoContable;
use App\Models\DetalleAsientoContable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ContabilidadController extends Controller
{
    public function index()
    {
        $cuentas = CuentaContable::orderBy('codigo')->get();
        $asientos = AsientoContable::with(['detalles.cuenta', 'user'])
            ->orderBy('fecha', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        $totalDebe = $asientos->sum('debe_total');
        $totalHaber = $asientos->sum('haber_total');

        return view('contabilidad.index', compact('cuentas', 'asientos', 'totalDebe', 'totalHaber'));
    }

    public function storeCuenta(Request $request)
    {
        $data = $request->validate([
            'codigo' => 'required|string|max:50|unique:cuentas_contables,codigo',
            'nombre' => 'required|string|max:255',
            'tipo' => 'required|in:activo,pasivo,patrimonio,ingreso,gasto',
            'nivel' => 'required|integer|min:1|max:5',
        ]);

        CuentaContable::create($data);

        return redirect()->route('contabilidad.index')->with('success', 'Cuenta contable agregada al plan de cuentas.');
    }

    public function storeAsiento(Request $request)
    {
        $request->validate([
            'fecha' => 'required|date',
            'glosa' => 'required|string',
            'cuentas' => 'required|array|min:2',
            'cuentas.*' => 'exists:cuentas_contables,id',
            'debes' => 'required|array|min:2',
            'haberes' => 'required|array|min:2',
        ]);

        $cuentas = $request->cuentas;
        $debes = $request->debes;
        $haberes = $request->haberes;

        $sumaDebe = 0;
        $sumaHaber = 0;

        foreach ($debes as $d) {
            $sumaDebe += (float) $d;
        }
        foreach ($haberes as $h) {
            $sumaHaber += (float) $h;
        }

        if (abs($sumaDebe - $sumaHaber) > 0.01) {
            return back()->withErrors(['error' => "El asiento no cuadra (Suma Debe: Bs. {$sumaDebe} vs Suma Haber: Bs. {$sumaHaber}). Debe haber balance exacto."])->withInput();
        }

        $numAsiento = 'AST-' . date('Ym') . '-' . rand(1000, 9999);

        DB::transaction(function() use ($request, $numAsiento, $sumaDebe, $sumaHaber, $cuentas, $debes, $haberes) {
            $asiento = AsientoContable::create([
                'numero_asiento' => $numAsiento,
                'fecha' => $request->fecha,
                'glosa' => $request->glosa,
                'debe_total' => $sumaDebe,
                'haber_total' => $sumaHaber,
                'user_id' => Auth::id(),
            ]);

            for ($i = 0; $i < count($cuentas); $i++) {
                $montoDebe = (float) ($debes[$i] ?? 0);
                $montoHaber = (float) ($haberes[$i] ?? 0);

                if ($montoDebe > 0 || $montoHaber > 0) {
                    DetalleAsientoContable::create([
                        'asiento_contable_id' => $asiento->id,
                        'cuenta_contable_id' => $cuentas[$i],
                        'debe' => $montoDebe,
                        'haber' => $montoHaber,
                    ]);
                }
            }
        });

        return redirect()->route('contabilidad.index')->with('success', 'Asiento contable registrado en el Libro Diario exitosamente.');
    }

    public function destroyAsiento(AsientoContable $asiento)
    {
        $asiento->delete();
        return redirect()->route('contabilidad.index')->with('success', 'Asiento contable anulado.');
    }
}
