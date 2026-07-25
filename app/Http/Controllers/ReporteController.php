<?php

namespace App\Http\Controllers;

use App\Models\Bocamina;
use App\Models\Trabajador;
use App\Models\Contrato;
use App\Models\Trabajo;
use App\Models\Anticipo;
use App\Models\Pago;
use App\Models\Socio;
use App\Models\VentaCarga;
use App\Models\Ingreso;
use App\Models\Egreso;
use App\Models\ProduccionMinera;
use App\Models\Caja;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ReporteController extends Controller
{
    public function dashboard()
    {
        $totalTrabajadores = Trabajador::count();
        $totalSocios = Socio::count();
        $totalBocaminas = Bocamina::count();
        $totalContratosActivos = Contrato::where('estado', 'activo')->count();
        $totalAnticiposPendientes = Anticipo::where('saldo', '>', 0)->sum('saldo');
        $totalVentasIngresos = VentaCarga::sum('total_vendido');
        $totalIngresos = Ingreso::sum('monto');
        $totalEgresos = Egreso::sum('monto');
        
        // Executive Metrics
        $totalToneladasExtraidas = ProduccionMinera::sum('toneladas_estimadas');
        $saldoCajasBs = Caja::sum('saldo_actual');
        $utilidadNetaEstimada = max(0, $totalIngresos - $totalEgresos);

        $recientesAnticipos = Anticipo::with(['trabajador', 'socio'])->orderBy('fecha', 'desc')->take(5)->get();
        $recientesPagos = Pago::with(['trabajador', 'socio'])->orderBy('fecha', 'desc')->take(5)->get();
        $recientesVentas = VentaCarga::with(['socio', 'bocamina'])->orderBy('fecha', 'desc')->take(5)->get();

        // Chart data: Production (subtotal of jobs) by Bocamina
        $produccionBocaminas = Bocamina::with(['trabajadores.trabajos'])
            ->get()
            ->map(function($bocamina) {
                $total = 0;
                foreach ($bocamina->trabajadores as $trabajador) {
                    $total += $trabajador->trabajos->sum('subtotal');
                }
                return [
                    'nombre' => $bocamina->nombre,
                    'total' => $total
                ];
            });

        // Chart data: Payments by month (last 6 months)
        $driver = DB::connection()->getDriverName();
        if ($driver === 'pgsql') {
            $mesRaw = DB::raw("TO_CHAR(fecha, 'MM') as mes");
            $anioRaw = DB::raw("TO_CHAR(fecha, 'YYYY') as anio");
            $groupBy = [DB::raw("TO_CHAR(fecha, 'YYYY')"), DB::raw("TO_CHAR(fecha, 'MM')")];
        } elseif ($driver === 'sqlite') {
            $mesRaw = DB::raw("strftime('%m', fecha) as mes");
            $anioRaw = DB::raw("strftime('%Y', fecha) as anio");
            $groupBy = ['anio', 'mes'];
        } else {
            $mesRaw = DB::raw("MONTH(fecha) as mes");
            $anioRaw = DB::raw("YEAR(fecha) as anio");
            $groupBy = ['anio', 'mes'];
        }

        $pagosMensuales = Pago::select(
            $mesRaw,
            $anioRaw,
            DB::raw("SUM(neto) as total")
        )
        ->groupBy($groupBy)
        ->orderBy('anio', 'desc')
        ->orderBy('mes', 'desc')
        ->take(6)
        ->get()
        ->reverse()
        ->map(function($item) {
            $date = Carbon::createFromDate((int)$item->anio, (int)$item->mes, 1);
            return [
                'etiqueta' => $date->translatedFormat('F Y'),
                'total' => $item->total
            ];
        });

        return view('dashboard', compact(
            'totalTrabajadores',
            'totalSocios',
            'totalBocaminas',
            'totalContratosActivos',
            'totalAnticiposPendientes',
            'totalVentasIngresos',
            'totalIngresos',
            'totalEgresos',
            'totalToneladasExtraidas',
            'saldoCajasBs',
            'utilidadNetaEstimada',
            'recientesAnticipos',
            'recientesPagos',
            'recientesVentas',
            'produccionBocaminas',
            'pagosMensuales'
        ));
    }

    public function index(Request $request)
    {
        $trabajadores = Trabajador::orderBy('nombre', 'asc')->get();
        $socios = Socio::orderBy('nombre', 'asc')->get();
        $bocaminas = Bocamina::orderBy('nombre', 'asc')->get();
        $tab = $request->input('tab', 'trabajador');

        $filtroFecha = $request->input('filtro_fecha', 'personalizado');
        $fechaDesde = $request->input('fecha_desde');
        $fechaHasta = $request->input('fecha_hasta');

        if ($request->filled('filtro_fecha') && $filtroFecha !== 'personalizado') {
            $hoy = Carbon::today();
            if ($filtroFecha === 'esta_semana') {
                $fechaDesde = $hoy->copy()->startOfWeek()->toDateString();
                $fechaHasta = $hoy->copy()->endOfWeek()->toDateString();
            } elseif ($filtroFecha === 'semana_pasada') {
                $fechaDesde = $hoy->copy()->subWeek()->startOfWeek()->toDateString();
                $fechaHasta = $hoy->copy()->subWeek()->endOfWeek()->toDateString();
            } elseif ($filtroFecha === 'este_mes') {
                $fechaDesde = $hoy->copy()->startOfMonth()->toDateString();
                $fechaHasta = $hoy->copy()->endOfMonth()->toDateString();
            } elseif ($filtroFecha === 'mes_pasado') {
                $fechaDesde = $hoy->copy()->subMonth()->startOfMonth()->toDateString();
                $fechaHasta = $hoy->copy()->subMonth()->endOfMonth()->toDateString();
            }
        }

        // 1. Reporte por Trabajador
        $reporteTrabajador = null;
        if ($request->filled('trabajador_id')) {
            $t = Trabajador::findOrFail($request->trabajador_id);
            
            $trabajosQuery = $t->trabajos();
            $anticiposQuery = $t->anticipos();
            $pagosQuery = $t->pagos();
            
            if ($fechaDesde) {
                $trabajosQuery->where('fecha', '>=', $fechaDesde);
                $anticiposQuery->where('fecha', '>=', $fechaDesde);
                $pagosQuery->where('fecha', '>=', $fechaDesde);
            }
            if ($fechaHasta) {
                $trabajosQuery->where('fecha', '<=', $fechaHasta);
                $anticiposQuery->where('fecha', '<=', $fechaHasta);
                $pagosQuery->where('fecha', '<=', $fechaHasta);
            }
            
            $trabajos = $trabajosQuery->get();
            $anticipos = $anticiposQuery->get();
            $pagos = $pagosQuery->get();

            $reporteTrabajador = [
                'trabajador' => $t,
                'trabajos' => $trabajos->sortByDesc('fecha'),
                'anticipos' => $anticipos->sortByDesc('fecha'),
                'pagos' => $pagos->sortByDesc('fecha'),
                'subtotal_trabajos' => $trabajos->sum('subtotal'),
                'trabajos_pendientes' => $trabajos->where('pagado', false)->sum('subtotal'),
                'anticipos_pendientes' => $anticipos->sum('saldo'),
                'pagos_recibidos' => $pagos->sum('neto'),
                'desde' => $fechaDesde,
                'hasta' => $fechaHasta,
            ];
        }

        // 2. Reporte por Socio
        $reporteSocio = null;
        if ($request->filled('socio_id')) {
            $s = Socio::findOrFail($request->socio_id);
            $ventasQuery = $s->ventas();
            $anticiposQuery = $s->anticipos();
            $pagosQuery = $s->pagos();

            if ($fechaDesde) {
                $ventasQuery->where('fecha', '>=', $fechaDesde);
                $anticiposQuery->where('fecha', '>=', $fechaDesde);
                $pagosQuery->where('fecha', '>=', $fechaDesde);
            }
            if ($fechaHasta) {
                $ventasQuery->where('fecha', '<=', $fechaHasta);
                $anticiposQuery->where('fecha', '<=', $fechaHasta);
                $pagosQuery->where('fecha', '<=', $fechaHasta);
            }

            $ventas = $ventasQuery->get();
            $anticipos = $anticiposQuery->get();
            $pagos = $pagosQuery->get();

            $reporteSocio = [
                'socio' => $s,
                'ventas' => $ventas->sortByDesc('fecha'),
                'anticipos' => $anticipos->sortByDesc('fecha'),
                'pagos' => $pagos->sortByDesc('fecha'),
                'total_ventas' => $ventas->sum('total_vendido'),
                'total_anticipos' => $anticipos->sum('monto'),
                'saldo_anticipos' => $anticipos->sum('saldo'),
                'total_pagos' => $pagos->sum('neto'),
                'desde' => $fechaDesde,
                'hasta' => $fechaHasta,
            ];
        }

        // 3. Reporte por Bocamina
        $reporteBocamina = [];
        foreach ($bocaminas as $bocamina) {
            $trabajadoresIds = Trabajador::where('bocamina_id', $bocamina->id)->pluck('id');
            $cantTrabajadores = count($trabajadoresIds);
            
            $pagosQuery = Pago::whereIn('trabajador_id', $trabajadoresIds);
            $trabajosQuery = Trabajo::whereIn('trabajador_id', $trabajadoresIds);
            $ventasQuery = VentaCarga::where('bocamina_id', $bocamina->id);

            if ($fechaDesde) {
                $pagosQuery->where('fecha', '>=', $fechaDesde);
                $trabajosQuery->where('fecha', '>=', $fechaDesde);
                $ventasQuery->where('fecha', '>=', $fechaDesde);
            }
            if ($fechaHasta) {
                $pagosQuery->where('fecha', '<=', $fechaHasta);
                $trabajosQuery->where('fecha', '<=', $fechaHasta);
                $ventasQuery->where('fecha', '<=', $fechaHasta);
            }
            
            $reporteBocamina[] = [
                'bocamina' => $bocamina,
                'cantidad_trabajadores' => $cantTrabajadores,
                'total_pagado' => $pagosQuery->sum('neto'),
                'total_produccion' => $trabajosQuery->sum('subtotal'),
                'total_ventas' => $ventasQuery->sum('total_vendido'),
                'metros' => (clone $trabajosQuery)->where('tipo', 'metro')->sum('cantidad'),
                'volquetas' => (clone $trabajosQuery)->where('tipo', 'volqueta')->sum('cantidad'),
            ];
        }

        $reporteBocaminaDetalle = null;
        if ($request->filled('bocamina_id')) {
            $bocaminaSeleccionada = Bocamina::find($request->bocamina_id);
            if ($bocaminaSeleccionada) {
                $trabajadoresBocamina = Trabajador::where('bocamina_id', $bocaminaSeleccionada->id)->get();
                $trabajadoresIds = $trabajadoresBocamina->pluck('id');
                
                $pagosQuery = Pago::whereIn('trabajador_id', $trabajadoresIds);
                $trabajosQuery = Trabajo::whereIn('trabajador_id', $trabajadoresIds);
                $ventasQuery = VentaCarga::where('bocamina_id', $bocaminaSeleccionada->id);
                $anticiposQuery = Anticipo::whereIn('trabajador_id', $trabajadoresIds);

                if ($fechaDesde) {
                    $pagosQuery->where('fecha', '>=', $fechaDesde);
                    $trabajosQuery->where('fecha', '>=', $fechaDesde);
                    $ventasQuery->where('fecha', '>=', $fechaDesde);
                    $anticiposQuery->where('fecha', '>=', $fechaDesde);
                }
                if ($fechaHasta) {
                    $pagosQuery->where('fecha', '<=', $fechaHasta);
                    $trabajosQuery->where('fecha', '<=', $fechaHasta);
                    $ventasQuery->where('fecha', '<=', $fechaHasta);
                    $anticiposQuery->where('fecha', '<=', $fechaHasta);
                }

                $trabajadoresData = [];
                foreach ($trabajadoresBocamina as $tb) {
                    $tTrabajos = Trabajo::where('trabajador_id', $tb->id);
                    $tPagos = Pago::where('trabajador_id', $tb->id);
                    $tAnticipos = Anticipo::where('trabajador_id', $tb->id);
                    if ($fechaDesde) {
                        $tTrabajos->where('fecha', '>=', $fechaDesde);
                        $tPagos->where('fecha', '>=', $fechaDesde);
                        $tAnticipos->where('fecha', '>=', $fechaDesde);
                    }
                    if ($fechaHasta) {
                        $tTrabajos->where('fecha', '<=', $fechaHasta);
                        $tPagos->where('fecha', '<=', $fechaHasta);
                        $tAnticipos->where('fecha', '<=', $fechaHasta);
                    }
                    $trabajadoresData[] = [
                        'trabajador' => $tb,
                        'total_produccion' => $tTrabajos->sum('subtotal'),
                        'total_pagado' => $tPagos->sum('neto'),
                        'saldo_anticipos' => $tAnticipos->sum('saldo'),
                    ];
                }

                $reporteBocaminaDetalle = [
                    'bocamina' => $bocaminaSeleccionada,
                    'trabajadores_data' => $trabajadoresData,
                    'total_produccion' => $trabajosQuery->sum('subtotal'),
                    'total_pagado' => $pagosQuery->sum('neto'),
                    'total_ventas' => $ventasQuery->sum('total_vendido'),
                    'total_anticipos' => $anticiposQuery->sum('monto'),
                    'saldo_anticipos' => $anticiposQuery->sum('saldo'),
                    'metros' => (clone $trabajosQuery)->where('tipo', 'metro')->sum('cantidad'),
                    'volquetas' => (clone $trabajosQuery)->where('tipo', 'volqueta')->sum('cantidad'),
                    'desde' => $fechaDesde,
                    'hasta' => $fechaHasta,
                ];
            }
        }

        // 4. Reporte de Ventas e Ingresos
        $ventasReporteQuery = VentaCarga::with(['socio', 'bocamina']);
        $ingresosReporteQuery = Ingreso::query();

        if ($fechaDesde) {
            $ventasReporteQuery->where('fecha', '>=', $fechaDesde);
            $ingresosReporteQuery->where('fecha', '>=', $fechaDesde);
        }
        if ($fechaHasta) {
            $ventasReporteQuery->where('fecha', '<=', $fechaHasta);
            $ingresosReporteQuery->where('fecha', '<=', $fechaHasta);
        }

        $reporteVentas = $ventasReporteQuery->orderBy('fecha', 'desc')->get();
        $reporteIngresos = $ingresosReporteQuery->orderBy('fecha', 'desc')->get();

        // 5. Reporte de Anticipos
        $antEstado = $request->input('ant_estado', 'todos');
        $anticiposQuery = Anticipo::with(['trabajador.bocamina', 'socio.bocamina'])->orderBy('fecha', 'desc');

        if ($fechaDesde) {
            $anticiposQuery->where('fecha', '>=', $fechaDesde);
        }
        if ($fechaHasta) {
            $anticiposQuery->where('fecha', '<=', $fechaHasta);
        }
        
        if ($antEstado === 'pendiente') {
            $anticiposQuery->where('saldo', '>', 0);
        } elseif ($antEstado === 'pagado') {
            $anticiposQuery->where('saldo', '=', 0);
        }

        $reporteAnticipos = $anticiposQuery->get();

        // 6. Reporte General (Semanal / Auditoría por Fechas)
        $genFiltro = $request->input('gen_filtro_fecha', 'este_mes');
        $genFechaDesde = $request->input('gen_fecha_desde');
        $genFechaHasta = $request->input('gen_fecha_hasta');

        if ($request->filled('gen_filtro_fecha') && $genFiltro !== 'personalizado') {
            $hoy = Carbon::today();
            if ($genFiltro === 'esta_semana') {
                $genFechaDesde = $hoy->copy()->startOfWeek()->toDateString();
                $genFechaHasta = $hoy->copy()->endOfWeek()->toDateString();
            } elseif ($genFiltro === 'semana_pasada') {
                $genFechaDesde = $hoy->copy()->subWeek()->startOfWeek()->toDateString();
                $genFechaHasta = $hoy->copy()->subWeek()->endOfWeek()->toDateString();
            } elseif ($genFiltro === 'este_mes') {
                $genFechaDesde = $hoy->copy()->startOfMonth()->toDateString();
                $genFechaHasta = $hoy->copy()->endOfMonth()->toDateString();
            } elseif ($genFiltro === 'mes_pasado') {
                $genFechaDesde = $hoy->copy()->subMonth()->startOfMonth()->toDateString();
                $genFechaHasta = $hoy->copy()->subMonth()->endOfMonth()->toDateString();
            }
        }

        if (!$genFechaDesde) {
            $genFechaDesde = Carbon::today()->startOfMonth()->toDateString();
        }
        if (!$genFechaHasta) {
            $genFechaHasta = Carbon::today()->endOfMonth()->toDateString();
        }

        $trabajosGen = Trabajo::with(['trabajador', 'contrato'])->where('fecha', '>=', $genFechaDesde)->where('fecha', '<=', $genFechaHasta)->get();
        $pagosGen = Pago::with('trabajador')->where('fecha', '>=', $genFechaDesde)->where('fecha', '<=', $genFechaHasta)->get();
        $anticiposGen = Anticipo::with('trabajador')->where('fecha', '>=', $genFechaDesde)->where('fecha', '<=', $genFechaHasta)->get();

        $semanas = [];
        $periodoObj = Carbon::parse($genFechaDesde);
        $finObj = Carbon::parse($genFechaHasta);

        while ($periodoObj->lte($finObj)) {
            $inicioSemana = $periodoObj->copy()->startOfWeek();
            $finSemana = $periodoObj->copy()->endOfWeek();
            $semanaKey = $inicioSemana->format('Y-W');
            
            if (!isset($semanas[$semanaKey])) {
                $trabajosSem = $trabajosGen->filter(fn($t) => Carbon::parse($t->fecha)->between($inicioSemana, $finSemana));
                $pagosSem = $pagosGen->filter(fn($p) => Carbon::parse($p->fecha)->between($inicioSemana, $finSemana));
                $anticiposSem = $anticiposGen->filter(fn($a) => Carbon::parse($a->fecha)->between($inicioSemana, $finSemana));
                
                $semanas[$semanaKey] = [
                    'semana_nombre' => 'Semana ' . $inicioSemana->format('d/m') . ' - ' . $finSemana->format('d/m/Y'),
                    'cantidad_trabajadores' => $trabajosSem->pluck('trabajador_id')->unique()->count(),
                    'cantidad_trabajos' => $trabajosSem->count(),
                    'total_produccion' => $trabajosSem->sum('subtotal'),
                    'cantidad_pagos' => $pagosSem->count(),
                    'total_pagado' => $pagosSem->sum('neto'),
                    'total_anticipos' => $anticiposSem->sum('monto'),
                ];
            }
            $periodoObj->addWeek();
        }

        $reporteGeneral = [
            'desde' => $genFechaDesde,
            'hasta' => $genFechaHasta,
            'total_trabajos' => $trabajosGen->sum('subtotal'),
            'total_pagos' => $pagosGen->sum('neto'),
            'total_anticipos' => $anticiposGen->sum('monto'),
            'semanas' => array_values($semanas),
            'trabajos' => $trabajosGen,
            'pagos' => $pagosGen,
            'anticipos' => $anticiposGen,
        ];

        return view('reportes.index', compact(
            'trabajadores',
            'socios',
            'bocaminas',
            'tab',
            'reporteTrabajador',
            'reporteSocio',
            'reporteBocamina',
            'reporteBocaminaDetalle',
            'reporteVentas',
            'reporteIngresos',
            'reporteAnticipos',
            'filtroFecha',
            'fechaDesde',
            'fechaHasta',
            'antEstado',
            'genFiltro',
            'genFechaDesde',
            'genFechaHasta',
            'reporteGeneral'
        ));
    }
}
