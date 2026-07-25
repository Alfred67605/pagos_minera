@extends('layouts.app')

@section('title', 'Registro de Producción Minera')

@section('content')
<div class="space-y-6" x-data="{ modalOpen: false }">
    
    <!-- Header Banner -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold tracking-tight text-white flex items-center gap-3">
                <div class="p-2.5 rounded-xl bg-purple-500/10 border border-purple-500/30 text-purple-500">
                    <i class="fa-solid fa-cubes text-2xl"></i>
                </div>
                Registro Diario de Producción Minera
            </h1>
            <p class="text-sm text-slate-400 mt-1">Control de extracción de volumen de mineral, cargas diarias y tonelaje por bocamina y sector veta.</p>
        </div>

        <button @click="modalOpen = true" 
                class="btn-vibrant-indigo px-5 py-2.5 rounded-xl text-sm font-bold uppercase tracking-wider flex items-center justify-center gap-2">
            <i class="fa-solid fa-plus text-base"></i> Registrar Producción
        </button>
    </div>

    <!-- Summary KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-6">
        <div class="glass-card p-6 rounded-2xl flex items-center justify-between border border-purple-500/30">
            <div>
                <span class="text-xs font-bold uppercase tracking-widest text-slate-400 font-mono">Total Cargas Extraídas</span>
                <div class="text-3xl font-bold text-white mt-1 font-mono">{{ number_format($totalCargas, 0) }} <span class="text-sm font-normal text-purple-400">cargas</span></div>
            </div>
            <div class="p-4 rounded-xl bg-purple-500/10 text-purple-400">
                <i class="fa-solid fa-truck-moving text-3xl"></i>
            </div>
        </div>

        <div class="glass-card p-6 rounded-2xl flex items-center justify-between border border-emerald-500/30">
            <div>
                <span class="text-xs font-bold uppercase tracking-widest text-slate-400 font-mono">Tonelaje Total Estimado</span>
                <div class="text-3xl font-bold text-white mt-1 font-mono">{{ number_format($totalToneladas, 2) }} <span class="text-sm font-normal text-emerald-400">Tn</span></div>
            </div>
            <div class="p-4 rounded-xl bg-emerald-500/10 text-emerald-400">
                <i class="fa-solid fa-weight-hanging text-3xl"></i>
            </div>
        </div>
    </div>

    <!-- Search & Filter Bar -->
    <div class="glass-card p-4 rounded-2xl">
        <form method="GET" action="{{ route('produccion.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <select name="bocamina_id" class="w-full py-2.5 text-sm">
                    <option value="">Todas las Bocaminas</option>
                    @foreach($bocaminas as $b)
                        <option value="{{ $b->id }}" {{ request('bocamina_id') == $b->id ? 'selected' : '' }}>{{ $b->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <input type="date" name="fecha_desde" value="{{ request('fecha_desde') }}" class="w-full py-2.5 text-sm font-mono" placeholder="Fecha Desde">
            </div>

            <div>
                <input type="date" name="fecha_hasta" value="{{ request('fecha_hasta') }}" class="w-full py-2.5 text-sm font-mono" placeholder="Fecha Hasta">
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="btn-vibrant-indigo flex-1 py-2.5 rounded-xl text-sm font-bold uppercase tracking-wider">
                    <i class="fa-solid fa-filter mr-1.5"></i> Filtrar
                </button>
                @if(request()->hasAny(['bocamina_id', 'fecha_desde', 'fecha_hasta']))
                    <a href="{{ route('produccion.index') }}" class="px-4 py-2.5 rounded-xl bg-slate-800 text-slate-300 hover:text-white text-sm font-semibold transition">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Produccion Table Card -->
    <div class="glass-card rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-900/80 border-b border-slate-800 text-xs font-semibold uppercase tracking-wider text-slate-400">
                        <th class="px-6 py-4">Fecha</th>
                        <th class="px-6 py-4">Bocamina & Sector Veta</th>
                        <th class="px-6 py-4">Tipo Mineral</th>
                        <th class="px-6 py-4 text-right">Cargas Extraídas</th>
                        <th class="px-6 py-4 text-right">Tonelaje Estimado (Tn)</th>
                        <th class="px-6 py-4">Observaciones</th>
                        <th class="px-6 py-4 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60 text-sm text-slate-200">
                    @forelse($producciones as $p)
                        <tr class="hover:bg-slate-900/40 transition">
                            <td class="px-6 py-4 font-mono text-xs text-slate-300">
                                {{ \Carbon\Carbon::parse($p->fecha)->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-semibold text-white">{{ $p->bocamina->nombre }}</div>
                                <div class="text-xs text-slate-400">Sector: {{ $p->veta_sector ?? 'Nivel Principal' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-purple-500/10 text-purple-400 border border-purple-500/30">
                                    {{ $p->tipo_mineral }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right font-mono font-bold text-amber-400 text-base">
                                {{ number_format($p->cargas_extraidas, 0) }}
                            </td>
                            <td class="px-6 py-4 text-right font-mono font-bold text-emerald-400 text-base">
                                {{ number_format($p->toneladas_estimadas, 2) }}
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-400">
                                {{ $p->observaciones ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <form action="{{ route('produccion.destroy', $p->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar registro de producción?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 rounded-lg bg-rose-500/10 text-rose-400 hover:bg-rose-500/20 border border-rose-500/30 transition" title="Eliminar">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-500">
                                <i class="fa-solid fa-cubes text-4xl mb-3 block text-slate-600"></i>
                                No hay registros de producción minera.
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
                    <i class="fa-solid fa-cubes text-purple-500"></i> Registrar Producción Minera Diaria
                </h3>
                <button @click="modalOpen = false" class="text-slate-400 hover:text-white">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <form action="{{ route('produccion.store') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Bocamina Origen *</label>
                        <select name="bocamina_id" required class="w-full py-2.5 text-sm">
                            <option value="">Seleccione Bocamina</option>
                            @foreach($bocaminas as $b)
                                <option value="{{ $b->id }}">{{ $b->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Fecha Registro *</label>
                        <input type="date" name="fecha" value="{{ date('Y-m-d') }}" required class="w-full py-2.5 text-sm font-mono">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Sector / Veta Mina</label>
                        <input type="text" name="veta_sector" placeholder="Ej: Veta Pailaviri Nivel 3" class="w-full py-2.5 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Tipo de Mineral *</label>
                        <input type="text" name="tipo_mineral" value="Complejo (Zn-Pb-Ag)" required class="w-full py-2.5 text-sm">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 font-mono">
                    <div>
                        <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Cargas Extraídas *</label>
                        <input type="number" step="1" min="1" name="cargas_extraidas" required placeholder="0" class="w-full py-2.5 text-sm font-bold text-amber-400">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Toneladas Estimadas *</label>
                        <input type="number" step="0.01" min="0.01" name="toneladas_estimadas" required placeholder="0.00" class="w-full py-2.5 text-sm font-bold text-emerald-400">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Observaciones</label>
                    <textarea name="observaciones" rows="2" placeholder="Notas de turno de trabajo..." class="w-full py-2.5 text-sm"></textarea>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-800">
                    <button type="button" @click="modalOpen = false" class="px-4 py-2.5 rounded-xl bg-slate-800 text-slate-300 hover:bg-slate-700 text-xs font-bold uppercase tracking-wider">
                        Cancelar
                    </button>
                    <button type="submit" class="btn-vibrant-indigo px-5 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider">
                        Guardar Producción
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
