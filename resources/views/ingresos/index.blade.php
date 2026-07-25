@extends('layouts.app')

@section('title', 'Ingresos Económicos')

@section('content')
<div x-data="{
    openModal: false,
    fecha: '{{ date('Y-m-d') }}',
    concepto: '',
    monto: '',
    origen: 'cuota_socio',
    observaciones: '',

    openCreate() {
        this.fecha = '{{ date('Y-m-d') }}';
        this.concepto = '';
        this.monto = '';
        this.origen = 'cuota_socio';
        this.observaciones = '';
        this.openModal = true;
    }
}" class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-slate-100">Flujo de Caja - Ingresos Económicos</h1>
            <p class="text-sm text-slate-400 mt-1">Control centralizado de ingresos por venta de cargas de mineral y aportes/cuotas de socios.</p>
        </div>
        <button @click="openCreate()" class="btn-vibrant-amber inline-flex items-center justify-center px-4 py-2.5 rounded-lg text-sm font-bold shadow-lg self-start">
            <i class="fa-solid fa-plus-circle mr-2"></i> Registrar Otro Ingreso
        </button>
    </div>

    <!-- Summary Metrics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Gran Total -->
        <div class="glass-card rounded-xl p-5 border border-amber-500/30">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Ingresos Caja</span>
                <div class="w-9 h-9 rounded-lg bg-amber-500/10 flex items-center justify-center text-amber-500 border border-amber-500/30">
                    <i class="fa-solid fa-vault text-base"></i>
                </div>
            </div>
            <p class="text-2xl font-bold font-mono text-amber-500 mt-2">Bs. {{ number_format($granTotal, 2) }}</p>
            <span class="text-[11px] text-slate-450 mt-1 block">Flujo bruto acumulado</span>
        </div>

        <!-- Ventas Cargas -->
        <div class="glass-card rounded-xl p-5 border border-emerald-500/30">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Por Ventas de Mineral</span>
                <div class="w-9 h-9 rounded-lg bg-emerald-500/10 flex items-center justify-center text-emerald-400 border border-emerald-500/30">
                    <i class="fa-solid fa-truck-ramp-box text-base"></i>
                </div>
            </div>
            <p class="text-2xl font-bold font-mono text-emerald-400 mt-2">Bs. {{ number_format($totalVentas, 2) }}</p>
            <span class="text-[11px] text-slate-450 mt-1 block">Generado por comercialización</span>
        </div>

        <!-- Cuotas / Aportes -->
        <div class="glass-card rounded-xl p-5 border border-indigo-500/30">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Aportes / Cuotas Socios</span>
                <div class="w-9 h-9 rounded-lg bg-indigo-500/10 flex items-center justify-center text-indigo-400 border border-indigo-500/30">
                    <i class="fa-solid fa-hand-holding-hand text-base"></i>
                </div>
            </div>
            <p class="text-2xl font-bold font-mono text-indigo-400 mt-2">Bs. {{ number_format($totalCuotas, 2) }}</p>
            <span class="text-[11px] text-slate-450 mt-1 block">Cuotas de mantenimiento</span>
        </div>

        <!-- Otros Ingresos -->
        <div class="glass-card rounded-xl p-5 border border-cyan-500/30">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Otros Ingresos</span>
                <div class="w-9 h-9 rounded-lg bg-cyan-500/10 flex items-center justify-center text-cyan-400 border border-cyan-500/30">
                    <i class="fa-solid fa-coins text-base"></i>
                </div>
            </div>
            <p class="text-2xl font-bold font-mono text-cyan-400 mt-2">Bs. {{ number_format($totalOtros, 2) }}</p>
            <span class="text-[11px] text-slate-450 mt-1 block">Ingresos extraordinarios</span>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="glass-card rounded-xl p-6 no-print">
        <form action="{{ route('ingresos.index') }}" method="GET" class="grid grid-cols-1 gap-4 sm:grid-cols-4 items-end">
            <div>
                <label for="buscar" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Buscar por Concepto</label>
                <input type="text" name="buscar" id="buscar" value="{{ request('buscar') }}" 
                       class="mt-1 block w-full px-3 py-2 bg-slate-900 border border-slate-700/80 rounded-lg text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-1 focus:ring-amber-500 focus:border-amber-500 text-sm"
                       placeholder="Ej. Cuota mensual / Venta zinc">
            </div>

            <div>
                <label for="origen_filter" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Origen del Ingreso</label>
                <select name="origen" id="origen_filter" 
                        class="mt-1 block w-full px-3 py-2 bg-slate-900 border border-slate-700/80 rounded-lg text-slate-100 focus:outline-none focus:ring-1 focus:ring-amber-500 focus:border-amber-500 text-sm">
                    <option value="">Todos los Orígenes</option>
                    <option value="venta_carga" {{ request('origen') === 'venta_carga' ? 'selected' : '' }}>Ventas de Cargas (Mineral)</option>
                    <option value="cuota_socio" {{ request('origen') === 'cuota_socio' ? 'selected' : '' }}>Aportes / Cuotas de Socios</option>
                    <option value="otro" {{ request('origen') === 'otro' ? 'selected' : '' }}>Otros Ingresos</option>
                </select>
            </div>

            <div>
                <label for="fecha_desde_filter" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Fecha Desde</label>
                <input type="date" name="fecha_desde" id="fecha_desde_filter" value="{{ request('fecha_desde') }}"
                       class="mt-1 block w-full px-3 py-2 bg-slate-900 border border-slate-700/80 rounded-lg text-slate-100 text-sm">
            </div>

            <div class="flex space-x-2">
                <button type="submit" class="btn-vibrant-warm flex-1 inline-flex items-center justify-center px-4 py-2 text-sm font-bold rounded-lg shadow-lg">
                    <i class="fa-solid fa-magnifying-glass mr-2"></i> Filtrar
                </button>
                <a href="{{ route('ingresos.index') }}" class="inline-flex items-center justify-center px-3 py-2 bg-slate-900 border border-slate-800 hover:bg-slate-800 text-sm font-medium text-slate-400 rounded-lg transition duration-150" title="Limpiar Filtros">
                    <i class="fa-solid fa-rotate-left"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Table Section -->
    <div class="glass-card rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-800">
                <thead>
                    <tr class="text-left text-xs font-semibold text-slate-400 uppercase tracking-wider bg-slate-900/40">
                        <th class="px-6 py-4 font-semibold">ID / Fecha</th>
                        <th class="px-6 py-4 font-semibold">Origen</th>
                        <th class="px-6 py-4 font-semibold">Concepto / Descripción</th>
                        <th class="px-6 py-4 font-semibold">Monto Ingresado</th>
                        <th class="px-6 py-4 font-semibold">Registrado Por</th>
                        <th class="px-6 py-4 font-semibold no-print">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/40 text-sm text-slate-300">
                    @forelse($ingresos as $ingreso)
                        <tr class="hover:bg-slate-900/10 transition duration-150">
                            <td class="px-6 py-4">
                                <span class="font-mono text-xs text-amber-500 font-bold">#ING-{{ $ingreso->id }}</span>
                                <span class="block font-mono text-xs text-slate-450">{{ $ingreso->fecha->format('d/m/Y') }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @if($ingreso->origen === 'venta_carga')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30">
                                        <i class="fa-solid fa-truck-ramp-box mr-1.5"></i> Venta Mineral
                                    </span>
                                @elseif($ingreso->origen === 'cuota_socio')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-500/10 text-indigo-400 border border-indigo-500/30">
                                        <i class="fa-solid fa-hand-holding-hand mr-1.5"></i> Cuota / Aporte
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-cyan-500/10 text-cyan-400 border border-cyan-500/30">
                                        <i class="fa-solid fa-coins mr-1.5"></i> Otro Ingreso
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-medium text-slate-100">
                                {{ $ingreso->concepto }}
                                @if($ingreso->observaciones)
                                    <span class="block text-xs text-slate-450">{{ $ingreso->observaciones }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-mono font-bold text-emerald-400 text-base">
                                Bs. {{ number_format($ingreso->monto, 2) }}
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-400 font-mono">
                                {{ $ingreso->user->name ?? 'Sistema' }}
                            </td>
                            <td class="px-6 py-4 no-print">
                                @if($ingreso->origen !== 'venta_carga')
                                    <form action="{{ route('ingresos.destroy', $ingreso->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro de eliminar este ingreso?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 rounded-lg bg-slate-800/80 hover:bg-red-500/20 text-slate-300 hover:text-red-400 border border-slate-700/60 hover:border-red-500/40 transition-all duration-300 hover:scale-105 active:scale-95 shadow-sm" title="Eliminar">
                                            <i class="fa-solid fa-trash text-xs"></i>
                                        </button>
                                    </form>
                                @else
                                    <span class="text-[11px] text-slate-500 italic">Vía Venta Carga</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                                <i class="fa-solid fa-vault text-4xl mb-3 block text-slate-600"></i>
                                No se encontraron ingresos económicos registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Form para Registrar Ingreso Manual -->
    <div x-show="openModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm" x-cloak>
        <div @click.away="openModal = false" class="glass-card w-full max-w-lg rounded-2xl overflow-hidden shadow-2xl border border-slate-800/80 relative">
            <div class="px-6 py-4 border-b border-slate-800/80 flex items-center justify-between bg-slate-900/60">
                <h3 class="text-lg font-bold text-slate-100">Registrar Ingreso a Caja</h3>
                <button @click="openModal = false" class="text-slate-400 hover:text-slate-200">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <form action="{{ route('ingresos.store') }}" method="POST">
                @csrf
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="modal_origen" class="block text-sm font-medium text-slate-300">Origen / Categoría</label>
                            <select id="modal_origen" name="origen" required x-model="origen"
                                    class="mt-1 block w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-lg text-slate-100 focus:outline-none focus:ring-1 focus:ring-amber-500 focus:border-amber-500 text-sm">
                                <option value="cuota_socio">Aporte / Cuota de Socio</option>
                                <option value="otro">Otro Ingreso Extraordinario</option>
                            </select>
                        </div>
                        <div>
                            <label for="modal_fecha" class="block text-sm font-medium text-slate-300">Fecha de Ingreso</label>
                            <input id="modal_fecha" name="fecha" type="date" required x-model="fecha"
                                   class="mt-1 block w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-lg text-slate-100 focus:outline-none focus:ring-1 focus:ring-amber-500 focus:border-amber-500 text-sm">
                        </div>
                    </div>

                    <div>
                        <label for="modal_concepto" class="block text-sm font-medium text-slate-300">Concepto del Ingreso</label>
                        <input id="modal_concepto" name="concepto" type="text" required x-model="concepto"
                               class="mt-1 block w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-lg text-slate-100 focus:outline-none focus:ring-1 focus:ring-amber-500 focus:border-amber-500 text-sm"
                               placeholder="Ej. Cuota mensual de mantenimiento de socio - Juan Perez">
                    </div>

                    <div>
                        <label for="modal_monto" class="block text-sm font-medium text-slate-300">Monto Ingresado (Bs.)</label>
                        <input id="modal_monto" name="monto" type="number" step="0.01" min="0.01" required x-model="monto"
                               class="mt-1 block w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-lg text-slate-100 focus:outline-none focus:ring-1 focus:ring-amber-500 focus:border-amber-500 text-sm font-mono"
                               placeholder="150.00">
                    </div>

                    <div>
                        <label for="modal_observaciones" class="block text-sm font-medium text-slate-300">Observaciones (Opcional)</label>
                        <textarea id="modal_observaciones" name="observaciones" rows="2" x-model="observaciones"
                                  class="mt-1 block w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-lg text-slate-100 focus:outline-none focus:ring-1 focus:ring-amber-500 focus:border-amber-500 text-sm"
                                  placeholder="Detalles sobre el recibo o depósito..."></textarea>
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-slate-800/80 bg-slate-900/40 flex justify-end space-x-3">
                    <button type="button" @click="openModal = false" class="px-4 py-2 text-sm font-medium rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-350 border border-slate-700/60 hover:border-slate-600 transition-all duration-200">
                        Cancelar
                    </button>
                    <button type="submit" 
                            :disabled="!concepto || !monto"
                            :class="(!concepto || !monto) ? 'opacity-50 cursor-not-allowed' : ''"
                            class="btn-vibrant-amber px-4 py-2 text-sm font-bold rounded-lg shadow-lg transition-all duration-150">
                        Guardar Ingreso
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
