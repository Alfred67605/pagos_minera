<?php

namespace App\Http\Controllers;

use App\Models\Anticipo;
use App\Models\Trabajador;
use App\Models\Socio;
use App\Models\Bocamina;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnticipoController extends Controller
{
    public function index(Request $request)
    {
        $query = Anticipo::with(['trabajador.bocamina', 'socio.bocamina', 'user']);

        if ($request->filled('tipo_receptor')) {
            $query->where('tipo_receptor', $request->tipo_receptor);
        }

        if ($request->filled('trabajador_id')) {
            $query->where('trabajador_id', $request->trabajador_id);
        }

        if ($request->filled('socio_id')) {
            $query->where('socio_id', $request->socio_id);
        }

        if ($request->filled('bocamina_id')) {
            $bocaminaId = $request->bocamina_id;
            $query->where(function($q) use ($bocaminaId) {
                $q->whereHas('trabajador', function($tr) use ($bocaminaId) {
                    $tr->where('bocamina_id', $bocaminaId);
                })->orWhereHas('socio', function($so) use ($bocaminaId) {
                    $so->where('bocamina_id', $bocaminaId);
                });
            });
        }

        if ($request->filled('estado')) {
            if ($request->estado === 'pendiente') {
                $query->where('saldo', '>', 0);
            } elseif ($request->estado === 'pagado') {
                $query->where('saldo', '=', 0);
            }
        }

        $anticipos = $query->orderBy('fecha', 'desc')->orderBy('id', 'desc')->get();
        $trabajadores = Trabajador::where('estado', 'activo')->orderBy('nombre')->get();
        $socios = Socio::where('estado', 'activo')->orderBy('nombre')->get();
        $bocaminas = Bocamina::orderBy('nombre')->get();

        return view('anticipos.index', compact('anticipos', 'trabajadores', 'socios', 'bocaminas'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'tipo_receptor' => 'required|in:trabajador,socio',
            'trabajador_id' => 'required_if:tipo_receptor,trabajador|nullable|exists:trabajadores,id',
            'socio_id' => 'required_if:tipo_receptor,socio|nullable|exists:socios,id',
            'fecha' => 'required|date',
            'monto' => 'required|numeric|min:0.01',
            'motivo' => 'nullable|string|max:255',
        ], [
            'trabajador_id.required_if' => 'Debe seleccionar un trabajador.',
            'socio_id.required_if' => 'Debe seleccionar un socio.',
            'monto.min' => 'El monto del anticipo debe ser mayor a 0.',
        ]);

        $data['saldo'] = $data['monto'];
        $data['pagado'] = false;
        $data['user_id'] = Auth::id();

        if ($data['tipo_receptor'] === 'trabajador') {
            $data['socio_id'] = null;
        } else {
            $data['trabajador_id'] = null;
        }

        Anticipo::create($data);

        return redirect()->route('anticipos.index')->with('success', 'Anticipo registrado exitosamente.');
    }

    public function update(Request $request, Anticipo $anticipo)
    {
        // Allow modifying motif if pending
        $data = $request->validate([
            'motivo' => 'nullable|string|max:255',
        ]);

        $anticipo->update($data);

        return redirect()->route('anticipos.index')->with('success', 'Anticipo actualizado.');
    }

    public function destroy(Anticipo $anticipo)
    {
        if ($anticipo->pagos()->exists() || $anticipo->saldo < $anticipo->monto) {
            return back()->withErrors(['error' => 'No se puede eliminar un anticipo que ya ha sido parcialmente o totalmente descontado.']);
        }

        $anticipo->delete();

        return redirect()->route('anticipos.index')->with('success', 'Anticipo eliminado exitosamente.');
    }

    public function recibo(Anticipo $anticipo)
    {
        $anticipo->load(['trabajador.bocamina', 'socio.bocamina', 'user']);
        return view('anticipos.recibo', compact('anticipo'));
    }
}
