@extends('layouts.app')

@section('title', 'Movimientos de Caja - ' . $caja->nombre)

@section('content')
<div class="space-y-6" x-data="{ movModal: false }">
    
    <!-- Top Bar -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <a href="{{ route('cajas.index') }}" class="text-xs text-amber-500 hover:text-amber-400 font-bold uppercase tracking-wider flex items-center gap-1.5 mb-2">
                <i class="fa-solid fa-arrow-left"></i> Volver a Cajas
            </a>
            <h1 class="text-2xl md:text-3xl font-bold tracking-tight text-white flex items-center gap-3">
                <div class="p-2.5 rounded-xl bg-amber-500/10 border border-amber-500/30 text-amber-500">
                    <i class="fa-solid fa-vault text-2xl"></i>
                </div>
                {{ $caja->nombre }}
            </h1>
            <p class="text-sm text-slate-400 mt-1">Estado de Caja: <span class="font-bold text-white uppercase">{{ $caja->estado }}</span></p>
        </div>

        <div class="flex items-center gap-3">
            @if($caja->estado === 'abierta')
                <button @click="movModal = true" 
                        class="btn-vibrant-amber px-5 py-2.5 rounded-xl text-sm font-bold uppercase tracking-wider flex items-center gap-2">
                    <i class="fa-solid fa-plus-minus text-base"></i> Registrar Movimiento
                </button>
            @endif
            <button onclick="window.print()" class="px-4 py-2.5 rounded-xl bg-slate-800 text-slate-200 hover:text-white border border-slate-700 text-sm font-bold flex items-center gap-2">
                <i class="fa-solid fa-print"></i> Imprimir Arqueo
            </button>
        </div>
    </div>

    <!-- Summary KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="glass-card p-5 rounded-2xl border-l-4 border-l-amber-500">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block font-mono">Saldo Actual Disponible</span>
            <div class="text-2xl font-bold text-amber-400 font-mono mt-1">Bs. {{ number_format($caja->saldo_actual, 2) }}</div>
        </div>
        <div class="glass-card p-5 rounded-2xl border-l-4 border-l-emerald-500">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block font-mono">Total Ingresos Acumulados</span>
            <div class="text-2xl font-bold text-emerald-400 font-mono mt-1">Bs. {{ number_format($totalIngresos, 2) }}</div>
        </div>
        <div class="glass-card p-5 rounded-2xl border-l-4 border-l-rose-500">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block font-mono">Total Egresos Acumulados</span>
            <div class="text-2xl font-bold text-rose-400 font-mono mt-1">Bs. {{ number_format($totalEgresos, 2) }}</div>
        </div>
    </div>

    <!-- Filter & Movement History Table -->
    <div class="glass-card rounded-2xl overflow-hidden">
        <div class="p-4 border-b border-slate-800 flex flex-col md:flex-row items-center justify-between gap-4">
            <h3 class="text-lg font-bold text-white flex items-center gap-2">
                <i class="fa-solid fa-list-ul text-amber-500"></i> Histórico de Movimientos de Caja
            </h3>
            
            <form method="GET" action="{{ route('cajas.show', $caja->id) }}" class="flex flex-wrap items-center gap-3">
                <select name="tipo" class="py-1.5 px-3 text-xs">
                    <option value="">Todos los tipos</option>
                    <option value="ingreso" {{ request('tipo') === 'ingreso' ? 'selected' : '' }}>Ingresos</option>
                    <option value="egreso" {{ request('tipo') === 'egreso' ? 'selected' : '' }}>Egresos</option>
                </select>
                <input type="date" name="fecha_desde" value="{{ request('fecha_desde') }}" class="py-1.5 px-3 text-xs font-mono">
                <input type="date" name="fecha_hasta" value="{{ request('fecha_hasta') }}" class="py-1.5 px-3 text-xs font-mono">
                <button type="submit" class="btn-vibrant-indigo py-1.5 px-3 rounded-lg text-xs font-bold uppercase tracking-wider">
                    Filtrar
                </button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-900/80 border-b border-slate-800 text-xs font-semibold uppercase tracking-wider text-slate-400">
                        <th class="px-6 py-4">Fecha</th>
                        <th class="px-6 py-4">Tipo</th>
                        <th class="px-6 py-4">Concepto / Referencia</th>
                        <th class="px-6 py-4">Categoría</th>
                        <th class="px-6 py-4">Usuario</th>
                        <th class="px-6 py-4 text-right">Monto (Bs.)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60 text-sm text-slate-200">
                    @forelse($movimientos as $mov)
                        <tr class="hover:bg-slate-900/40 transition">
                            <td class="px-6 py-4 font-mono text-xs text-slate-300">
                                {{ \Carbon\Carbon::parse($mov->fecha)->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4">
                                @if($mov->tipo === 'ingreso')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30">
                                        <i class="fa-solid fa-arrow-down-left mr-1"></i> Ingreso
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-rose-500/10 text-rose-400 border border-rose-500/30">
                                        <i class="fa-solid fa-arrow-up-right mr-1"></i> Egreso
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-semibold text-white">
                                {{ $mov->concepto }}
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-400">
                                {{ $mov->categoria ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-400">
                                {{ $mov->user->name ?? 'Sistema' }}
                            </td>
                            <td class="px-6 py-4 text-right font-mono font-bold text-base {{ $mov->tipo === 'ingreso' ? 'text-emerald-400' : 'text-rose-400' }}">
                                {{ $mov->tipo === 'ingreso' ? '+' : '-' }} {{ number_format($mov->monto, 2) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                                <i class="fa-solid fa-receipt text-4xl mb-3 block text-slate-600"></i>
                                No hay movimientos registrados en esta caja.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Movement Modal -->
    <div x-show="movModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md"
         style="display: none;">
        
        <div class="glass-card w-full max-w-md rounded-2xl overflow-hidden shadow-2xl border border-amber-500/30" @click.away="movModal = false">
            <div class="p-6 border-b border-slate-800 flex items-center justify-between">
                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                    <i class="fa-solid fa-plus-minus text-amber-500"></i> Registrar Movimiento de Caja
                </h3>
                <button @click="movModal = false" class="text-slate-400 hover:text-white">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <form action="{{ route('cajas.movimientos.store', $caja->id) }}" method="POST" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Tipo de Movimiento *</label>
                    <select name="tipo" class="w-full py-2.5 text-sm">
                        <option value="ingreso">Ingreso de Efectivo (+)</option>
                        <option value="egreso">Egreso / Retiro de Efectivo (-)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Monto (Bs.) *</label>
                    <input type="number" step="0.01" min="0.01" name="monto" required placeholder="0.00" class="w-full py-2.5 text-sm font-mono text-amber-400 font-bold">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Concepto / Detalle *</label>
                    <input type="text" name="concepto" required placeholder="Ej: Reposición de caja chica para repuestos" class="w-full py-2.5 text-sm">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Categoría</label>
                    <input type="text" name="categoria" placeholder="Ej: Operativo, Insumos, etc." class="w-full py-2.5 text-sm">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Fecha *</label>
                    <input type="date" name="fecha" value="{{ date('Y-m-d') }}" required class="w-full py-2.5 text-sm font-mono">
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-800">
                    <button type="button" @click="movModal = false" class="px-4 py-2.5 rounded-xl bg-slate-800 text-slate-300 hover:bg-slate-700 text-xs font-bold uppercase tracking-wider">
                        Cancelar
                    </button>
                    <button type="submit" class="btn-vibrant-amber px-5 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider">
                        Guardar Movimiento
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
