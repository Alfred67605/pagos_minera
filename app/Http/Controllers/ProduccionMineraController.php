<?php

namespace App\Http\Controllers;

use App\Models\ProduccionMinera;
use App\Models\Bocamina;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProduccionMineraController extends Controller
{
    public function index(Request $request)
    {
        $query = ProduccionMinera::with(['bocamina', 'user']);

        if ($request->filled('bocamina_id')) {
            $query->where('bocamina_id', $request->bocamina_id);
        }

        if ($request->filled('fecha_desde')) {
            $query->where('fecha', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->where('fecha', '<=', $request->fecha_hasta);
        }

        $producciones = $query->orderBy('fecha', 'desc')->orderBy('id', 'desc')->get();
        $bocaminas = Bocamina::orderBy('nombre')->get();

        $totalCargas = $producciones->sum('cargas_extraidas');
        $totalToneladas = $producciones->sum('toneladas_estimadas');

        return view('produccion.index', compact('producciones', 'bocaminas', 'totalCargas', 'totalToneladas'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'fecha' => 'required|date',
            'bocamina_id' => 'required|exists:bocaminas,id',
            'veta_sector' => 'nullable|string|max:255',
            'tipo_mineral' => 'required|string|max:100',
            'cargas_extraidas' => 'required|numeric|min:0.01',
            'toneladas_estimadas' => 'required|numeric|min:0.01',
            'observaciones' => 'nullable|string',
        ]);

        $data['user_id'] = Auth::id();

        ProduccionMinera::create($data);

        return redirect()->route('produccion.index')->with('success', 'Registro de producción minera diario guardado exitosamente.');
    }

    public function destroy(ProduccionMinera $produccion)
    {
        $produccion->delete();
        return redirect()->route('produccion.index')->with('success', 'Registro de producción minera eliminado.');
    }
}
