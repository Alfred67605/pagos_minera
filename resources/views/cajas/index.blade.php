@extends('layouts.app')

@section('title', 'Gestión de Caja General y Arqueos')

@section('content')
<div class="space-y-6" x-data="{ modalOpen: false }">
    
    <!-- Header Banner -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold tracking-tight text-white flex items-center gap-3">
                <div class="p-2.5 rounded-xl bg-amber-500/10 border border-amber-500/30 text-amber-500">
                    <i class="fa-solid fa-vault text-2xl"></i>
                </div>
                Caja General y Arqueos
            </h1>
            <p class="text-sm text-slate-400 mt-1">Administración de flujos de efectivo, cajas chicas, apertura/cierre y control de saldos.</p>
        </div>

        <button @click="modalOpen = true" 
                class="btn-vibrant-amber px-5 py-2.5 rounded-xl text-sm font-bold uppercase tracking-wider flex items-center justify-center gap-2">
            <i class="fa-solid fa-plus text-base"></i> Abrir Nueva Caja
        </button>
    </div>

    <!-- Cards summary -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($cajas as $caja)
            <div class="glass-card p-6 rounded-2xl flex flex-col justify-between space-y-4 hover:border-amber-500/40 transition duration-300">
                <div class="flex items-start justify-between">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400 font-mono block">
                            {{ $caja->tipo === 'caja_general' ? 'Caja General' : 'Caja Chica' }}
                        </span>
                        <h3 class="text-xl font-bold text-white mt-1">{{ $caja->nombre }}</h3>
                    </div>
                    @if($caja->estado === 'abierta')
                        <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30 flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span> Abierta
                        </span>
                    @else
                        <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-rose-500/10 text-rose-400 border border-rose-500/30">
                            Cerrada
                        </span>
                    @endif
                </div>

                <div class="space-y-1 bg-slate-900/60 p-4 rounded-xl border border-slate-800 font-mono">
                    <div class="text-xs text-slate-400 flex justify-between">
                        <span>Saldo Inicial:</span>
                        <span>Bs. {{ number_format($caja->saldo_inicial, 2) }}</span>
                    </div>
                    <div class="text-lg font-bold text-amber-400 flex justify-between pt-1 border-t border-slate-800">
                        <span>Saldo Actual:</span>
                        <span>Bs. {{ number_format($caja->saldo_actual, 2) }}</span>
                    </div>
                </div>

                <div class="flex items-center justify-between gap-3 pt-2">
                    <a href="{{ route('cajas.show', $caja->id) }}" class="btn-vibrant-indigo flex-1 py-2 rounded-lg text-xs font-bold uppercase tracking-wider text-center">
                        <i class="fa-solid fa-list-check mr-1"></i> Movimientos ({{ $caja->movimientos_count }})
                    </a>

                    <form action="{{ route('cajas.toggle-estado', $caja->id) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" 
                                class="p-2 rounded-lg {{ $caja->estado === 'abierta' ? 'bg-amber-500/10 text-amber-400 border border-amber-500/30 hover:bg-amber-500/20' : 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/30 hover:bg-emerald-500/20' }} transition"
                                title="{{ $caja->estado === 'abierta' ? 'Cerrar Caja (Arqueo)' : 'Reabrir Caja' }}">
                            <i class="fa-solid {{ $caja->estado === 'abierta' ? 'fa-lock' : 'fa-lock-open' }}"></i>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-full glass-card p-12 text-center text-slate-500 rounded-2xl">
                <i class="fa-solid fa-vault text-5xl mb-3 text-slate-600 block"></i>
                No existen cajas registradas en el sistema.
            </div>
        @endforelse
    </div>

    <!-- Create Modal -->
    <div x-show="modalOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md"
         style="display: none;">
        
        <div class="glass-card w-full max-w-md rounded-2xl overflow-hidden shadow-2xl border border-amber-500/30" @click.away="modalOpen = false">
            <div class="p-6 border-b border-slate-800 flex items-center justify-between">
                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                    <i class="fa-solid fa-vault text-amber-500"></i> Apertura de Nueva Caja
                </h3>
                <button @click="modalOpen = false" class="text-slate-400 hover:text-white">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <form action="{{ route('cajas.store') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Nombre de la Caja *</label>
                    <input type="text" name="nombre" required placeholder="Ej: Caja Chica Mina 1" class="w-full py-2.5 text-sm">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Tipo de Caja *</label>
                    <select name="tipo" class="w-full py-2.5 text-sm">
                        <option value="caja_general">Caja General</option>
                        <option value="caja_chica">Caja Chica Operativa</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Saldo Inicial (Bs.) *</label>
                    <input type="number" step="0.01" min="0" name="saldo_inicial" value="0.00" required class="w-full py-2.5 text-sm font-mono text-amber-400 font-bold">
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-800">
                    <button type="button" @click="modalOpen = false" class="px-4 py-2.5 rounded-xl bg-slate-800 text-slate-300 hover:bg-slate-700 text-xs font-bold uppercase tracking-wider">
                        Cancelar
                    </button>
                    <button type="submit" class="btn-vibrant-amber px-5 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider">
                        Crear y Abrir Caja
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
