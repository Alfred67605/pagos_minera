@extends('layouts.app')

@section('title', 'Kardex Digital - ' . $trabajador->nombre)

@section('content')
<div class="space-y-8" x-data="{ tab: 'pagos' }">

    <!-- Header / Acciones (no-print) -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-slate-900/80 p-6 rounded-3xl border border-slate-800 backdrop-blur-xl no-print">
        <div>
            <span class="text-xs font-black text-cyan-400 uppercase tracking-widest block font-mono">FICHA TÉCNICA Y KARDEX LABORAL</span>
            <h1 class="text-2xl md:text-3xl font-black text-white flex items-center gap-3">
                <i class="fa-solid fa-address-card text-rose-400"></i> {{ $trabajador->nombre }}
            </h1>
            <p class="text-xs text-slate-400 mt-1">Consulta integral de planillas cobradas, anticipos recibidos e información contractual.</p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('trabajadores.index') }}" class="px-4 py-2.5 rounded-xl bg-slate-800 text-slate-300 hover:bg-slate-700 text-xs font-bold uppercase tracking-wider transition">
                <i class="fa-solid fa-arrow-left mr-1.5"></i> Volver al Personal
            </a>
            <button onclick="window.print()" class="btn-vibrant-warm px-5 py-2.5 rounded-xl text-xs font-black uppercase tracking-wider flex items-center gap-2 shadow-lg shadow-amber-500/20">
                <i class="fa-solid fa-print text-sm"></i> Imprimir Kardex
            </button>
        </div>
    </div>

    <!-- Perfil Principal & Código QR de Pago -->
    <div class="glass-card rounded-3xl p-6 md:p-8 relative overflow-hidden">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
            
            <!-- Columna Izquierda: Avatar e Info Básica (Col-8) -->
            <div class="lg:col-span-8 flex flex-col sm:flex-row items-center sm:items-start gap-6">
                <!-- Avatar Dinámico por Rol -->
                <div class="w-24 h-24 rounded-3xl flex items-center justify-center text-3xl font-black text-white shadow-2xl flex-shrink-0 border-2 border-white/10
                    @if($trabajador->cargo === 'trabajador_bocamina') bg-gradient-to-br from-rose-500 to-pink-600 shadow-rose-500/30
                    @elseif($trabajador->cargo === 'chofer') bg-gradient-to-br from-emerald-500 to-teal-600 shadow-emerald-500/30
                    @elseif($trabajador->cargo === 'sereno') bg-gradient-to-br from-amber-500 to-orange-600 shadow-amber-500/30
                    @else bg-gradient-to-br from-sky-500 to-indigo-600 shadow-sky-500/30
                    @endif">
                    {{ strtoupper(substr($trabajador->nombre, 0, 2)) }}
                </div>

                <div class="space-y-3 text-center sm:text-left flex-1">
                    <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2">
                        <span class="px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider font-mono
                            @if($trabajador->cargo === 'trabajador_bocamina') bg-rose-500/20 text-rose-300 border border-rose-500/40
                            @elseif($trabajador->cargo === 'chofer') bg-emerald-500/20 text-emerald-300 border border-emerald-500/40
                            @elseif($trabajador->cargo === 'sereno') bg-amber-500/20 text-amber-300 border border-amber-500/40
                            @else bg-sky-500/20 text-sky-300 border border-sky-500/40
                            @endif">
                            <i class="fa-solid fa-hard-hat mr-1"></i> {{ strtoupper(str_replace('_', ' ', $trabajador->cargo)) }}
                        </span>

                        <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-slate-800 text-slate-300 border border-slate-700">
                            {{ $trabajador->modalidad_pago === 'por_produccion' ? '⚡ Pago Semanal (Contratista/Trato)' : '📅 Pago Mensual (Fijo/Sereno)' }}
                        </span>

                        @if($trabajador->estado === 'activo')
                            <span class="px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-emerald-500/20 text-emerald-400 border border-emerald-500/40 flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span> Activo
                            </span>
                        @else
                            <span class="px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-rose-500/20 text-rose-400 border border-rose-500/40">Inactivo</span>
                        @endif
                    </div>

                    <h2 class="text-2xl md:text-3xl font-black text-white">{{ $trabajador->nombre }}</h2>

                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 pt-2 text-xs font-semibold text-slate-300 border-t border-slate-800/80">
                        <div>
                            <span class="text-slate-400 block font-mono">C.I. / Documento:</span>
                            <span class="text-white font-bold font-mono text-sm">{{ $trabajador->ci }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block font-mono">Bocamina Asignada:</span>
                            <span class="text-cyan-300 font-bold">{{ $trabajador->bocamina ? $trabajador->bocamina->nombre : 'Sin Asignar' }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block font-mono">Teléfono / WhatsApp:</span>
                            <span class="text-emerald-300 font-bold font-mono">{{ $trabajador->telefono ?: 'No registrado' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Columna Derecha: Tarjeta QR de Pago Rápido (Col-4) -->
            <div class="lg:col-span-4 flex flex-col items-center justify-center p-5 rounded-2xl bg-slate-950/80 border border-slate-800 text-center space-y-3">
                <span class="text-[10px] font-black uppercase tracking-widest text-slate-400 font-mono">Código QR de Identificación / Pago</span>
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=130x130&data={{ urlencode('TRABAJADOR:' . $trabajador->id . '|CI:' . $trabajador->ci . '|NOMBRE:' . $trabajador->nombre) }}" 
                     alt="QR Trabajador" class="w-28 h-28 rounded-xl border-2 border-cyan-500/40 bg-white p-1 shadow-lg">
                <span class="text-[11px] font-bold text-slate-300 font-mono">CI: {{ $trabajador->ci }}</span>
            </div>

        </div>
    </div>

    <!-- Tarjetas KPI Acumuladas -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Total Ganado -->
        <div class="glass-card rounded-2xl p-5">
            <span class="text-[10px] font-black uppercase tracking-widest text-emerald-400 font-mono block">Ganado Histórico Acumulado</span>
            <p class="text-2xl font-black text-white font-mono mt-1">Bs. {{ number_format($totalGanado, 2) }}</p>
            <span class="text-[11px] font-medium text-slate-400 mt-1 block">Producción + Jornales</span>
        </div>

        <!-- Total Anticipos Recibidos -->
        <div class="glass-card rounded-2xl p-5">
            <span class="text-[10px] font-black uppercase tracking-widest text-purple-400 font-mono block">Anticipos Recibidos</span>
            <p class="text-2xl font-black text-purple-300 font-mono mt-1">Bs. {{ number_format($totalAnticipos, 2) }}</p>
            <span class="text-[11px] font-medium text-slate-400 mt-1 block">Total adelantos históricos</span>
        </div>

        <!-- Total Cobrado en Efectivo -->
        <div class="glass-card rounded-2xl p-5">
            <span class="text-[10px] font-black uppercase tracking-widest text-rose-400 font-mono block">Cobrado Neto en Efectivo</span>
            <p class="text-2xl font-black text-rose-300 font-mono mt-1">Bs. {{ number_format($totalLiquidoCobrado, 2) }}</p>
            <span class="text-[11px] font-medium text-slate-400 mt-1 block">Entregado en mano</span>
        </div>

        <!-- Anticipos Pendientes por Descontar -->
        <div class="glass-card rounded-2xl p-5 border border-amber-500/30">
            <span class="text-[10px] font-black uppercase tracking-widest text-amber-400 font-mono block">Anticipos Pendientes</span>
            <p class="text-2xl font-black text-amber-300 font-mono mt-1">Bs. {{ number_format($anticiposPendientes, 2) }}</p>
            <span class="text-[11px] font-medium text-amber-400/80 mt-1 block">A descontar próxima planilla</span>
        </div>
    </div>

    <!-- Pestañas de Histórico -->
    <div class="glass-card rounded-3xl p-6 space-y-6">
        
        <!-- Tab Navigation (no-print) -->
        <div class="border-b border-slate-800 flex flex-wrap gap-4 no-print">
            <button @click="tab = 'pagos'" :class="tab === 'pagos' ? 'border-emerald-500 text-emerald-400 font-black' : 'border-transparent text-slate-400 hover:text-slate-200'" class="py-3 px-4 border-b-2 text-xs uppercase tracking-wider flex items-center gap-2 transition">
                <i class="fa-solid fa-receipt"></i> 1. Planillas & Liquidaciones ({{ $trabajador->pagos->count() }})
            </button>
            <button @click="tab = 'anticipos'" :class="tab === 'anticipos' ? 'border-purple-500 text-purple-400 font-black' : 'border-transparent text-slate-400 hover:text-slate-200'" class="py-3 px-4 border-b-2 text-xs uppercase tracking-wider flex items-center gap-2 transition">
                <i class="fa-solid fa-hand-holding-dollar"></i> 2. Anticipos Otorgados ({{ $trabajador->anticipos->count() }})
            </button>
            <button @click="tab = 'trabajos'" :class="tab === 'trabajos' ? 'border-cyan-500 text-cyan-400 font-black' : 'border-transparent text-slate-400 hover:text-slate-200'" class="py-3 px-4 border-b-2 text-xs uppercase tracking-wider flex items-center gap-2 transition">
                <i class="fa-solid fa-mountain-sun"></i> 3. Trabajos & Producción ({{ $trabajador->trabajos->count() }})
            </button>
            <button @click="tab = 'contratos'" :class="tab === 'contratos' ? 'border-sky-500 text-sky-400 font-black' : 'border-transparent text-slate-400 hover:text-slate-200'" class="py-3 px-4 border-b-2 text-xs uppercase tracking-wider flex items-center gap-2 transition">
                <i class="fa-solid fa-file-contract"></i> 4. Contratos ({{ $trabajador->contratos->count() }})
            </button>
        </div>

        <!-- 1. Pestaña Liquidaciones -->
        <div x-show="tab === 'pagos'" class="space-y-4">
            <h3 class="text-sm font-black text-white uppercase tracking-wider font-mono">Historial de Liquidaciones y Sueldos Entregados:</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-800 text-xs">
                    <thead>
                        <tr class="text-left font-extrabold text-slate-400 uppercase tracking-wider">
                            <th class="py-3 px-3">Nº Recibo / Fecha</th>
                            <th class="py-3 px-3">Periodo Liquidado</th>
                            <th class="py-3 px-3 text-right">Total Ganado</th>
                            <th class="py-3 px-3 text-right">Anticipos Descontados</th>
                            <th class="py-3 px-3 text-right">Líquido Pagado</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/50 text-slate-200">
                        @forelse($trabajador->pagos as $pago)
                            <tr class="hover:bg-slate-900/50 transition">
                                <td class="py-3 px-3 font-mono font-bold text-emerald-400">
                                    {{ $pago->numero_recibo }}
                                    <span class="block text-[10px] text-slate-400 font-normal">{{ $pago->fecha->format('d/m/Y') }}</span>
                                </td>
                                <td class="py-3 px-3 text-slate-300 font-medium">
                                    {{ $pago->periodo_inicio ? $pago->periodo_inicio->format('d/m/Y') : '' }} - {{ $pago->periodo_fin ? $pago->periodo_fin->format('d/m/Y') : '' }}
                                </td>
                                <td class="py-3 px-3 text-right font-mono text-slate-300">Bs. {{ number_format($pago->bruto, 2) }}</td>
                                <td class="py-3 px-3 text-right font-mono text-purple-400">-Bs. {{ number_format($pago->descuentos_anticipos, 2) }}</td>
                                <td class="py-3 px-3 text-right font-mono font-black text-emerald-400 text-sm">Bs. {{ number_format($pago->neto, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-slate-500 font-medium">No se registran planillas o pagos procesados para este trabajador.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 2. Pestaña Anticipos -->
        <div x-show="tab === 'anticipos'" class="space-y-4">
            <h3 class="text-sm font-black text-white uppercase tracking-wider font-mono">Historial de Anticipos Entregados:</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-800 text-xs">
                    <thead>
                        <tr class="text-left font-extrabold text-slate-400 uppercase tracking-wider">
                            <th class="py-3 px-3">Fecha</th>
                            <th class="py-3 px-3">Concepto / Glosa</th>
                            <th class="py-3 px-3 text-right">Monto Otorgado</th>
                            <th class="py-3 px-3 text-right">Saldo Restante</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/50 text-slate-200">
                        @forelse($trabajador->anticipos as $ant)
                            <tr class="hover:bg-slate-900/50 transition">
                                <td class="py-3 px-3 font-mono text-slate-300">{{ $ant->fecha ? $ant->fecha->format('d/m/Y H:i') : '' }}</td>
                                <td class="py-3 px-3 font-medium text-slate-200">{{ $ant->concepto }}</td>
                                <td class="py-3 px-3 text-right font-mono font-bold text-purple-300">Bs. {{ number_format($ant->monto, 2) }}</td>
                                <td class="py-3 px-3 text-right font-mono font-black text-amber-400">Bs. {{ number_format($ant->saldo, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-8 text-center text-slate-500 font-medium">No se registran anticipos entregados a este trabajador.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 3. Pestaña Trabajos -->
        <div x-show="tab === 'trabajos'" class="space-y-4">
            <h3 class="text-sm font-black text-white uppercase tracking-wider font-mono">Registro de Trabajos Diarios y Avances:</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-800 text-xs">
                    <thead>
                        <tr class="text-left font-extrabold text-slate-400 uppercase tracking-wider">
                            <th class="py-3 px-3">Fecha</th>
                            <th class="py-3 px-3">Descripción Trabajo</th>
                            <th class="py-3 px-3 text-right">Cantidad / Cant.</th>
                            <th class="py-3 px-3 text-right">Precio Unit.</th>
                            <th class="py-3 px-3 text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/50 text-slate-200">
                        @forelse($trabajador->trabajos as $trab)
                            <tr class="hover:bg-slate-900/50 transition">
                                <td class="py-3 px-3 font-mono text-slate-300">{{ $trab->fecha ? $trab->fecha->format('d/m/Y') : '' }}</td>
                                <td class="py-3 px-3 font-medium text-slate-200">{{ $trab->descripcion }}</td>
                                <td class="py-3 px-3 text-right font-mono text-slate-300">{{ number_format($trab->cantidad, 2) }}</td>
                                <td class="py-3 px-3 text-right font-mono text-slate-300">Bs. {{ number_format($trab->precio_unitario, 2) }}</td>
                                <td class="py-3 px-3 text-right font-mono font-bold text-cyan-300">Bs. {{ number_format($trab->subtotal, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-slate-500 font-medium">No hay registros de avance o producción para este trabajador.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 4. Pestaña Contratos -->
        <div x-show="tab === 'contratos'" class="space-y-4">
            <h3 class="text-sm font-black text-white uppercase tracking-wider font-mono">Contratos Asociados:</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @forelse($trabajador->contratos as $contrato)
                    <div class="p-4 rounded-2xl bg-slate-950 border border-slate-800 space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-cyan-400 font-mono">{{ $contrato->codigo_contrato }}</span>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $contrato->estado === 'activo' ? 'bg-emerald-500/20 text-emerald-400' : 'bg-slate-800 text-slate-400' }}">
                                {{ $contrato->estado }}
                            </span>
                        </div>
                        <p class="text-sm font-bold text-white">{{ $contrato->tipo_contrato }}</p>
                        <p class="text-xs text-slate-400">Inicio: {{ $contrato->fecha_inicio ? $contrato->fecha_inicio->format('d/m/Y') : '-' }}</p>
                    </div>
                @empty
                    <div class="col-span-2 py-8 text-center text-slate-500 font-medium">No hay contratos registrados.</div>
                @endforelse
            </div>
        </div>

    </div>
</div>
@endsection
