@extends('layouts.app')

@section('title', 'Préstamos y Créditos')

@section('content')
<div class="space-y-6" x-data="{ modalOpen: false, cuotasModal: false, selectedPrestamo: {} }">
    
    <!-- Header Banner -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold tracking-tight text-white flex items-center gap-3">
                <div class="p-2.5 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-500">
                    <i class="fa-solid fa-hand-holding-dollar text-2xl"></i>
                </div>
                Préstamos & Créditos a Socios y Cuadrillas
            </h1>
            <p class="text-sm text-slate-400 mt-1">Otorgamiento de préstamos corporativos, planes de amortización en cuotas y control de saldos pendientes.</p>
        </div>

        <button @click="modalOpen = true" 
                class="btn-vibrant-success px-5 py-2.5 rounded-xl text-sm font-bold uppercase tracking-wider flex items-center justify-center gap-2">
            <i class="fa-solid fa-plus text-base"></i> Otorgar Préstamo
        </button>
    </div>

    <!-- Prestamos Table Card -->
    <div class="glass-card rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-900/80 border-b border-slate-800 text-xs font-semibold uppercase tracking-wider text-slate-400">
                        <th class="px-6 py-4">N° Préstamo / Fecha</th>
                        <th class="px-6 py-4">Beneficiario (Socio / Personal)</th>
                        <th class="px-6 py-4 text-right">Monto Total (Bs.)</th>
                        <th class="px-6 py-4 text-center">Cuotas (Pagadas / Total)</th>
                        <th class="px-6 py-4 text-right">Valor Cuota (Bs.)</th>
                        <th class="px-6 py-4 text-right">Saldo Pendiente (Bs.)</th>
                        <th class="px-6 py-4">Estado</th>
                        <th class="px-6 py-4 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60 text-sm text-slate-200">
                    @forelse($prestamos as $p)
                        <tr class="hover:bg-slate-900/40 transition">
                            <td class="px-6 py-4">
                                <div class="font-mono font-bold text-emerald-400">{{ $p->numero_prestamo }}</div>
                                <div class="text-xs text-slate-400 font-mono">{{ \Carbon\Carbon::parse($p->fecha_otorgamiento)->format('d/m/Y') }}</div>
                            </td>
                            <td class="px-6 py-4 font-semibold text-white">
                                @if($p->socio)
                                    <div><i class="fa-solid fa-user-tie text-amber-500 mr-1.5"></i>{{ $p->socio->nombre }}</div>
                                    <div class="text-xs text-slate-400 font-normal">Socio Cooperativista</div>
                                @else
                                    <div><i class="fa-solid fa-user-gear text-sky-400 mr-1.5"></i>{{ $p->trabajador->nombre ?? 'Personal' }}</div>
                                    <div class="text-xs text-slate-400 font-normal">Trabajador</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right font-mono font-bold text-slate-100">
                                Bs. {{ number_format($p->monto_total, 2) }}
                            </td>
                            <td class="px-6 py-4 text-center font-mono text-xs">
                                <span class="font-bold text-emerald-400">{{ $p->cuotas_pagadas }}</span> / {{ $p->total_cuotas }}
                            </td>
                            <td class="px-6 py-4 text-right font-mono text-xs text-slate-300">
                                Bs. {{ number_format($p->monto_cuota, 2) }}
                            </td>
                            <td class="px-6 py-4 text-right font-mono font-bold text-base {{ $p->saldo_pendiente > 0 ? 'text-rose-400' : 'text-emerald-400' }}">
                                Bs. {{ number_format($p->saldo_pendiente, 2) }}
                            </td>
                            <td class="px-6 py-4">
                                @if($p->estado === 'activo')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-amber-500/10 text-amber-400 border border-amber-500/30">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-400 mr-1.5 animate-pulse"></span> Activo
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30">
                                        Completado
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <button @click="selectedPrestamo = {{ json_encode($p->load('cuotas')) }}; cuotasModal = true" 
                                        class="p-2 rounded-lg bg-emerald-500/10 text-emerald-400 hover:bg-emerald-500/20 border border-emerald-500/30 transition"
                                        title="Ver Plan de Amortización / Pagar Cuotas">
                                    <i class="fa-solid fa-list-ol"></i>
                                </button>

                                <form action="{{ route('prestamos.destroy', $p->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Anular este préstamo?')">
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
                            <td colspan="8" class="px-6 py-12 text-center text-slate-500">
                                <i class="fa-solid fa-hand-holding-dollar text-4xl mb-3 block text-slate-600"></i>
                                No se han registrado préstamos ni créditos.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Create Loan Modal -->
    <div x-show="modalOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md"
         style="display: none;">
        
        <div class="glass-card w-full max-w-lg rounded-2xl overflow-hidden shadow-2xl border border-emerald-500/30" @click.away="modalOpen = false" x-data="{ tipoBen: 'socio' }">
            <div class="p-6 border-b border-slate-800 flex items-center justify-between">
                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                    <i class="fa-solid fa-hand-holding-dollar text-emerald-500"></i> Otorgar Préstamo / Crédito
                </h3>
                <button @click="modalOpen = false" class="text-slate-400 hover:text-white">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <form action="{{ route('prestamos.store') }}" method="POST" class="p-6 space-y-4">
                @csrf
                
                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Tipo de Beneficiario *</label>
                    <select name="tipo_beneficiario" x-model="tipoBen" class="w-full py-2.5 text-sm">
                        <option value="socio">Socio Cooperativista</option>
                        <option value="trabajador">Trabajador / Personal Mina</option>
                    </select>
                </div>

                <div x-show="tipoBen === 'socio'">
                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Seleccionar Socio *</label>
                    <select name="socio_id" class="w-full py-2.5 text-sm">
                        <option value="">Seleccione Socio</option>
                        @foreach($socios as $s)
                            <option value="{{ $s->id }}">{{ $s->nombre }} ({{ $s->codigo }})</option>
                        @endforeach
                    </select>
                </div>

                <div x-show="tipoBen === 'trabajador'" style="display: none;">
                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Seleccionar Trabajador *</label>
                    <select name="trabajador_id" class="w-full py-2.5 text-sm">
                        <option value="">Seleccione Trabajador</option>
                        @foreach($trabajadores as $t)
                            <option value="{{ $t->id }}">{{ $t->nombre }} ({{ $t->cargo }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4 font-mono">
                    <div>
                        <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Monto Préstamo (Bs.) *</label>
                        <input type="number" step="0.01" min="1" name="monto_total" required placeholder="5000.00" class="w-full py-2.5 text-sm font-bold text-emerald-400">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">N° de Cuotas *</label>
                        <input type="number" step="1" min="1" max="60" name="total_cuotas" value="6" required class="w-full py-2.5 text-sm font-bold text-amber-400">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Fecha Otorgamiento *</label>
                    <input type="date" name="fecha_otorgamiento" value="{{ date('Y-m-d') }}" required class="w-full py-2.5 text-sm font-mono">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Observaciones</label>
                    <textarea name="observaciones" rows="2" placeholder="Motivo o respaldo del crédito..." class="w-full py-2.5 text-sm"></textarea>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-800">
                    <button type="button" @click="modalOpen = false" class="px-4 py-2.5 rounded-xl bg-slate-800 text-slate-300 hover:bg-slate-700 text-xs font-bold uppercase tracking-wider">
                        Cancelar
                    </button>
                    <button type="submit" class="btn-vibrant-success px-5 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider">
                        Otorgar Préstamo
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Amortization Modal -->
    <div x-show="cuotasModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md"
         style="display: none;">
        
        <div class="glass-card w-full max-w-xl rounded-2xl overflow-hidden shadow-2xl border border-emerald-500/30" @click.away="cuotasModal = false">
            <div class="p-6 border-b border-slate-800 flex items-center justify-between">
                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                    <i class="fa-solid fa-list-ol text-emerald-500"></i> Plan de Cuotas: <span x-text="selectedPrestamo.numero_prestamo" class="font-mono text-amber-400"></span>
                </h3>
                <button @click="cuotasModal = false" class="text-slate-400 hover:text-white">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <div class="p-6 space-y-4">
                <div class="overflow-x-auto max-h-80">
                    <table class="w-full text-left text-xs font-mono">
                        <thead class="bg-slate-900 text-slate-400">
                            <tr>
                                <th class="p-2">Cuota #</th>
                                <th class="p-2">Vencimiento</th>
                                <th class="p-2 text-right">Monto (Bs.)</th>
                                <th class="p-2">Estado</th>
                                <th class="p-2 text-right">Acción</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800">
                            <template x-for="c in selectedPrestamo.cuotas" :key="c.id">
                                <tr class="hover:bg-slate-900/50">
                                    <td class="p-2 font-bold text-amber-400" x-text="'Cuota ' + c.numero_cuota"></td>
                                    <td class="p-2 text-slate-300" x-text="c.fecha_vencimiento"></td>
                                    <td class="p-2 text-right font-bold text-white" x-text="'Bs. ' + parseFloat(c.monto_cuota).toFixed(2)"></td>
                                    <td class="p-2">
                                        <span x-show="c.estado === 'pagado'" class="text-emerald-400 font-bold">✓ Pagado</span>
                                        <span x-show="c.estado === 'pendiente'" class="text-amber-400 font-bold">⏳ Pendiente</span>
                                    </td>
                                    <td class="p-2 text-right">
                                        <template x-if="c.estado === 'pendiente'">
                                            <form :action="'/prestamos/cuotas/' + c.id + '/pagar'" method="POST">
                                                @csrf
                                                <button type="submit" class="px-2 py-1 bg-emerald-500/20 text-emerald-400 hover:bg-emerald-500/30 rounded border border-emerald-500/30 text-[10px] font-bold uppercase">
                                                    Cobrar
                                                </button>
                                            </form>
                                        </template>
                                    </td>
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
