@extends('layouts.app')

@section('title', 'Distribución de Utilidades y Dividendos')

@section('content')
<div class="space-y-6" x-data="{ modalOpen: false, detallesModal: false, selectedDist: {} }">
    
    <!-- Header Banner -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold tracking-tight text-white flex items-center gap-3">
                <div class="p-2.5 rounded-xl bg-chart-pie/10 border border-purple-500/30 text-purple-400">
                    <i class="fa-solid fa-chart-pie text-2xl"></i>
                </div>
                Distribución de Utilidades & Dividendos
            </h1>
            <p class="text-sm text-slate-400 mt-1">Cálculo de utilidades del periodo, reservas de capital y reparto según el porcentaje de participación de cada socio.</p>
        </div>

        <button @click="modalOpen = true" 
                class="btn-vibrant-indigo px-5 py-2.5 rounded-xl text-sm font-bold uppercase tracking-wider flex items-center justify-center gap-2">
            <i class="fa-solid fa-calculator text-base"></i> Distribuir Utilidades
        </button>
    </div>

    <!-- Equity Summary Card -->
    <div class="glass-card p-6 rounded-2xl grid grid-cols-1 md:grid-cols-3 gap-6">
        <div>
            <span class="text-xs font-bold uppercase tracking-widest text-slate-400 font-mono">Socios Activos</span>
            <div class="text-2xl font-bold text-white mt-1">{{ count($socios) }} <span class="text-xs font-normal text-slate-400">socios</span></div>
        </div>

        <div>
            <span class="text-xs font-bold uppercase tracking-widest text-slate-400 font-mono">Participación Total Registrada</span>
            <div class="text-2xl font-bold text-amber-400 mt-1 font-mono">{{ number_format($totalParticipacion, 2) }}%</div>
        </div>

        <div>
            <span class="text-xs font-bold uppercase tracking-widest text-slate-400 font-mono">Utilidad Bruta Estimada Actual</span>
            <div class="text-2xl font-bold text-emerald-400 mt-1 font-mono">Bs. {{ number_format($utilidadBrutaEstimada, 2) }}</div>
        </div>
    </div>

    <!-- Distributions Table Card -->
    <div class="glass-card rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-900/80 border-b border-slate-800 text-xs font-semibold uppercase tracking-wider text-slate-400">
                        <th class="px-6 py-4">N° Distribución / Fecha</th>
                        <th class="px-6 py-4">Periodo Contable</th>
                        <th class="px-6 py-4 text-right">Utilidad Bruta (Bs.)</th>
                        <th class="px-6 py-4 text-right">Reservas / Deducciones</th>
                        <th class="px-6 py-4 text-right">Monto Neto Repartido</th>
                        <th class="px-6 py-4 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60 text-sm text-slate-200">
                    @forelse($distribuciones as $d)
                        <tr class="hover:bg-slate-900/40 transition">
                            <td class="px-6 py-4">
                                <div class="font-mono font-bold text-purple-400">{{ $d->numero_distribucion }}</div>
                                <div class="text-xs text-slate-400 font-mono">{{ \Carbon\Carbon::parse($d->fecha)->format('d/m/Y') }}</div>
                            </td>
                            <td class="px-6 py-4 font-semibold text-white">
                                {{ $d->periodo }}
                            </td>
                            <td class="px-6 py-4 text-right font-mono text-slate-300">
                                Bs. {{ number_format($d->utilidad_bruta_total, 2) }}
                            </td>
                            <td class="px-6 py-4 text-right font-mono text-xs text-rose-400">
                                - Bs. {{ number_format($d->deducciones_reserva, 2) }}
                            </td>
                            <td class="px-6 py-4 text-right font-mono font-bold text-emerald-400 text-base">
                                Bs. {{ number_format($d->utilidad_neta_distribuir, 2) }}
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <button @click="selectedDist = {{ json_encode($d->load('detalles.socio')) }}; detallesModal = true"
                                        class="p-2 rounded-lg bg-indigo-500/10 text-indigo-400 hover:bg-indigo-500/20 border border-indigo-500/30 transition"
                                        title="Ver Planilla de Reparto a Socios">
                                    <i class="fa-solid fa-users-rectangle"></i>
                                </button>

                                <form action="{{ route('utilidades.destroy', $d->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Anular esta distribución?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 rounded-lg bg-rose-500/10 text-rose-400 hover:bg-rose-500/20 border border-rose-500/30 transition" title="Anular">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                                <i class="fa-solid fa-chart-pie text-4xl mb-3 block text-slate-600"></i>
                                No se han procesado distribuciones de utilidades.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Create Form Modal -->
    <div x-show="modalOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md"
         style="display: none;">
        
        <div class="glass-card w-full max-w-lg rounded-2xl overflow-hidden shadow-2xl border border-purple-500/30" @click.away="modalOpen = false">
            <div class="p-6 border-b border-slate-800 flex items-center justify-between">
                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                    <i class="fa-solid fa-chart-pie text-purple-500"></i> Distribuir Utilidades del Periodo
                </h3>
                <button @click="modalOpen = false" class="text-slate-400 hover:text-white">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <form action="{{ route('utilidades.store') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Periodo Contable *</label>
                        <input type="text" name="periodo" placeholder="Ej: Trimestre 1 - 2026" required class="w-full py-2.5 text-sm font-bold text-purple-400">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Fecha Emisión *</label>
                        <input type="date" name="fecha" value="{{ date('Y-m-d') }}" required class="w-full py-2.5 text-sm font-mono">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 font-mono">
                    <div>
                        <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Utilidad Bruta (Bs.) *</label>
                        <input type="number" step="0.01" min="0.01" name="utilidad_bruta_total" value="{{ $utilidadBrutaEstimada }}" required class="w-full py-2.5 text-sm font-bold text-emerald-400">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Reserva Legal / Contingencia</label>
                        <input type="number" step="0.01" min="0" name="deducciones_reserva" value="0.00" class="w-full py-2.5 text-sm text-rose-400">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Observaciones</label>
                    <textarea name="observaciones" rows="2" placeholder="Notas de distribución..." class="w-full py-2.5 text-sm"></textarea>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-800">
                    <button type="button" @click="modalOpen = false" class="px-4 py-2.5 rounded-xl bg-slate-800 text-slate-300 hover:bg-slate-700 text-xs font-bold uppercase tracking-wider">
                        Cancelar
                    </button>
                    <button type="submit" class="btn-vibrant-indigo px-5 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider">
                        Calcular & Repartir Dividendos
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Shareholders Breakdown Modal -->
    <div x-show="detallesModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md"
         style="display: none;">
        
        <div class="glass-card w-full max-w-xl rounded-2xl overflow-hidden shadow-2xl border border-purple-500/30" @click.away="detallesModal = false">
            <div class="p-6 border-b border-slate-800 flex items-center justify-between">
                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                    <i class="fa-solid fa-users-rectangle text-purple-500"></i> Planilla Dividendos: <span x-text="selectedDist.numero_distribucion" class="font-mono text-amber-400"></span>
                </h3>
                <button @click="detallesModal = false" class="text-slate-400 hover:text-white">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <div class="p-6 space-y-4">
                <div class="overflow-x-auto max-h-80">
                    <table class="w-full text-left text-xs font-mono">
                        <thead class="bg-slate-900 text-slate-400">
                            <tr>
                                <th class="p-2">Socio</th>
                                <th class="p-2 text-center">% Acción</th>
                                <th class="p-2 text-right">Dividendos (Bs.)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800">
                            <template x-for="d in selectedDist.detalles" :key="d.id">
                                <tr class="hover:bg-slate-900/50">
                                    <td class="p-2 font-bold text-white" x-text="d.socio.nombre"></td>
                                    <td class="p-2 text-center font-bold text-amber-400" x-text="parseFloat(d.porcentaje_participacion).toFixed(2) + '%'"></td>
                                    <td class="p-2 text-right font-bold text-emerald-400 text-sm" x-text="'Bs. ' + parseFloat(d.monto_utilidad).toFixed(2)"></td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
