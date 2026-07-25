@extends('layouts.app')

@section('title', 'Egresos y Gastos Operativos')

@section('content')
<div class="space-y-6" x-data="{ egresoModal: false, catModal: false, origenPago: 'caja' }">
    
    <!-- Header Banner -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold tracking-tight text-white flex items-center gap-3">
                <div class="p-2.5 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-500">
                    <i class="fa-solid fa-file-invoice-dollar text-2xl"></i>
                </div>
                Egresos y Gastos Operativos
            </h1>
            <p class="text-sm text-slate-400 mt-1">Registro de salidas de dinero, compras de insumos, mantenimiento y pagos a proveedores.</p>
        </div>

        <div class="flex items-center gap-3">
            <button @click="catModal = true" class="px-4 py-2.5 rounded-xl bg-slate-800 text-slate-200 hover:text-white border border-slate-700 text-sm font-bold flex items-center gap-2">
                <i class="fa-solid fa-tags"></i> Nueva Categoría
            </button>
            <button @click="egresoModal = true" 
                    class="btn-vibrant-danger px-5 py-2.5 rounded-xl text-sm font-bold uppercase tracking-wider flex items-center justify-center gap-2">
                <i class="fa-solid fa-plus text-base"></i> Registrar Egreso
            </button>
        </div>
    </div>

    <!-- Filters Bar -->
    <div class="glass-card p-4 rounded-2xl">
        <form method="GET" action="{{ route('egresos.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <select name="categoria_id" class="w-full py-2.5 text-sm">
                    <option value="">Todas las categorías</option>
                    @foreach($categorias as $cat)
                        <option value="{{ $cat->id }}" {{ request('categoria_id') == $cat->id ? 'selected' : '' }}>{{ $cat->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <input type="date" name="fecha_desde" value="{{ request('fecha_desde') }}" class="w-full py-2.5 text-sm font-mono" placeholder="Fecha desde">
            </div>

            <div>
                <input type="date" name="fecha_hasta" value="{{ request('fecha_hasta') }}" class="w-full py-2.5 text-sm font-mono" placeholder="Fecha hasta">
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="btn-vibrant-indigo flex-1 py-2.5 rounded-xl text-sm font-bold uppercase tracking-wider">
                    <i class="fa-solid fa-filter mr-1.5"></i> Filtrar
                </button>
                @if(request()->hasAny(['categoria_id', 'fecha_desde', 'fecha_hasta']))
                    <a href="{{ route('egresos.index') }}" class="px-4 py-2.5 rounded-xl bg-slate-800 text-slate-300 hover:text-white text-sm font-semibold transition">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Egresos Table Card -->
    <div class="glass-card rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-900/80 border-b border-slate-800 text-xs font-semibold uppercase tracking-wider text-slate-400">
                        <th class="px-6 py-4">Fecha</th>
                        <th class="px-6 py-4">Categoría</th>
                        <th class="px-6 py-4">Concepto / Proveedor</th>
                        <th class="px-6 py-4">Origen de Pago</th>
                        <th class="px-6 py-4">N° Comprobante</th>
                        <th class="px-6 py-4 text-right">Monto (Bs.)</th>
                        <th class="px-6 py-4 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60 text-sm text-slate-200">
                    @forelse($egresos as $egreso)
                        <tr class="hover:bg-slate-900/40 transition">
                            <td class="px-6 py-4 font-mono text-xs text-slate-300">
                                {{ \Carbon\Carbon::parse($egreso->fecha)->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-amber-500/10 text-amber-400 border border-amber-500/30">
                                    {{ $egreso->categoria->nombre ?? 'General' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 font-semibold text-white">
                                {{ $egreso->concepto }}
                                @if($egreso->proveedor)
                                    <div class="text-xs text-slate-400 font-normal mt-0.5"><i class="fa-solid fa-store mr-1 text-slate-500"></i>{{ $egreso->proveedor }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-xs font-medium">
                                @if($egreso->caja)
                                    <span class="text-amber-400 flex items-center gap-1.5"><i class="fa-solid fa-vault"></i> {{ $egreso->caja->nombre }}</span>
                                @else
                                    <span class="text-slate-500">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-mono text-xs text-slate-400">
                                {{ $egreso->comprobante_numero ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-right font-mono font-bold text-rose-400 text-base">
                                - Bs. {{ number_format($egreso->monto, 2) }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <form action="{{ route('egresos.destroy', $egreso->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Desea anular este egreso y devolver el saldo al origen?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 rounded-lg bg-rose-500/10 text-rose-400 hover:bg-rose-500/20 border border-rose-500/30 transition" title="Anular Egreso">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-500">
                                <i class="fa-solid fa-receipt text-4xl mb-3 block text-slate-600"></i>
                                No hay egresos u otros gastos registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Create Egreso Modal -->
    <div x-show="egresoModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md"
         style="display: none;">
        
        <div class="glass-card w-full max-w-lg rounded-2xl overflow-hidden shadow-2xl border border-rose-500/30" @click.away="egresoModal = false">
            <div class="p-6 border-b border-slate-800 flex items-center justify-between">
                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                    <i class="fa-solid fa-file-invoice-dollar text-rose-500"></i> Registrar Nuevo Egreso / Gasto
                </h3>
                <button @click="egresoModal = false" class="text-slate-400 hover:text-white">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <form action="{{ route('egresos.store') }}" method="POST" class="p-6 space-y-4">
                @csrf
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Categoría de Gasto *</label>
                        <select name="categoria_id" required class="w-full py-2.5 text-sm">
                            <option value="">Seleccione Categoría</option>
                            @foreach($categorias as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Fecha *</label>
                        <input type="date" name="fecha" value="{{ date('Y-m-d') }}" required class="w-full py-2.5 text-sm font-mono">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Caja de Origen *</label>
                    <select name="caja_id" required class="w-full py-2.5 text-sm">
                        @foreach($cajas as $caja)
                            <option value="{{ $caja->id }}">{{ $caja->nombre }} (Saldo: Bs. {{ number_format($caja->saldo_actual, 2) }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Monto (Bs.) *</label>
                        <input type="number" step="0.01" min="0.01" name="monto" required placeholder="0.00" class="w-full py-2.5 text-sm font-mono text-rose-400 font-bold">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">N° Comprobante / Factura</label>
                        <input type="text" name="comprobante_numero" placeholder="Ej: FAC-9821" class="w-full py-2.5 text-sm font-mono">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Concepto / Motivo *</label>
                    <input type="text" name="concepto" required placeholder="Ej: Compra de repuestos de perforadora y brocas" class="w-full py-2.5 text-sm">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Proveedor / Beneficiario</label>
                    <input type="text" name="proveedor" placeholder="Ej: Ferretería Industrial SRL" class="w-full py-2.5 text-sm">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Observaciones</label>
                    <textarea name="observaciones" rows="2" placeholder="Detalles adicionales..." class="w-full py-2.5 text-sm"></textarea>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-800">
                    <button type="button" @click="egresoModal = false" class="px-4 py-2.5 rounded-xl bg-slate-800 text-slate-300 hover:bg-slate-700 text-xs font-bold uppercase tracking-wider">
                        Cancelar
                    </button>
                    <button type="submit" class="btn-vibrant-danger px-5 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider">
                        Guardar Egreso
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Create Category Modal -->
    <div x-show="catModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md"
         style="display: none;">
        
        <div class="glass-card w-full max-w-md rounded-2xl overflow-hidden shadow-2xl border border-amber-500/30" @click.away="catModal = false">
            <div class="p-6 border-b border-slate-800 flex items-center justify-between">
                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                    <i class="fa-solid fa-tags text-amber-500"></i> Nueva Categoría de Gasto
                </h3>
                <button @click="catModal = false" class="text-slate-400 hover:text-white">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <form action="{{ route('egresos.categorias.store') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Nombre de Categoría *</label>
                    <input type="text" name="nombre" required placeholder="Ej: Mantenimiento de Maquinaria" class="w-full py-2.5 text-sm">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Descripción</label>
                    <textarea name="descripcion" rows="2" placeholder="Ej: Repuestos, aceite y lubricantes" class="w-full py-2.5 text-sm"></textarea>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-800">
                    <button type="button" @click="catModal = false" class="px-4 py-2.5 rounded-xl bg-slate-800 text-slate-300 hover:bg-slate-700 text-xs font-bold uppercase tracking-wider">
                        Cancelar
                    </button>
                    <button type="submit" class="btn-vibrant-amber px-5 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider">
                        Crear Categoría
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
