@extends('layouts.app')

@section('title', 'Contabilidad General y Libro Diario')

@section('content')
<div class="space-y-6" x-data="{ cuentaModal: false, asientoModal: false }">
    
    <!-- Header Banner -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold tracking-tight text-white flex items-center gap-3">
                <div class="p-2.5 rounded-xl bg-amber-500/10 border border-amber-500/30 text-amber-500">
                    <i class="fa-solid fa-book-bookmark text-2xl"></i>
                </div>
                Libro Diario & Contabilidad General
            </h1>
            <p class="text-sm text-slate-400 mt-1">Plan de cuentas estructurado, asientos contables balanceados (Debe / Haber) y balance de comprobación.</p>
        </div>

        <div class="flex items-center gap-3">
            <button @click="cuentaModal = true" class="px-4 py-2.5 rounded-xl bg-slate-800 text-slate-200 hover:text-white border border-slate-700 text-sm font-bold flex items-center gap-2">
                <i class="fa-solid fa-folder-tree"></i> Plan Cuentas
            </button>
            <button @click="asientoModal = true" class="btn-vibrant-amber px-5 py-2.5 rounded-xl text-sm font-bold uppercase tracking-wider flex items-center justify-center gap-2">
                <i class="fa-solid fa-plus text-base"></i> Registrar Asiento Diario
            </button>
        </div>
    </div>

    <!-- Trial Balance KPI Summary -->
    <div class="glass-card p-6 rounded-2xl grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="flex items-center justify-between border-r border-slate-800 pr-6">
            <div>
                <span class="text-xs font-bold uppercase tracking-widest text-slate-400 font-mono">Suma Total DEBE</span>
                <div class="text-3xl font-bold text-sky-400 mt-1 font-mono">Bs. {{ number_format($totalDebe, 2) }}</div>
            </div>
            <div class="p-3 rounded-xl bg-sky-500/10 text-sky-400">
                <i class="fa-solid fa-scale-unbalanced-flip text-2xl"></i>
            </div>
        </div>

        <div class="flex items-center justify-between pl-6">
            <div>
                <span class="text-xs font-bold uppercase tracking-widest text-slate-400 font-mono">Suma Total HABER</span>
                <div class="text-3xl font-bold text-emerald-400 mt-1 font-mono">Bs. {{ number_format($totalHaber, 2) }}</div>
            </div>
            <div class="p-3 rounded-xl bg-emerald-500/10 text-emerald-400">
                <i class="fa-solid fa-scale-balanced text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- Journal Entries Table Card -->
    <div class="glass-card rounded-2xl overflow-hidden">
        <div class="p-4 border-b border-slate-800">
            <h3 class="text-lg font-bold text-white flex items-center gap-2">
                <i class="fa-solid fa-book-journal-whills text-amber-500"></i> Asientos Contables Registrados (Libro Diario)
            </h3>
        </div>

        <div class="divide-y divide-slate-800">
            @forelse($asientos as $ast)
                <div class="p-6 hover:bg-slate-900/40 transition space-y-4">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2 border-b border-slate-800/80 pb-3">
                        <div class="flex items-center gap-3">
                            <span class="px-3 py-1 rounded-lg bg-amber-500/10 text-amber-400 border border-amber-500/30 font-mono font-bold text-xs">
                                {{ $ast->numero_asiento }}
                            </span>
                            <span class="text-xs font-mono text-slate-400">
                                {{ \Carbon\Carbon::parse($ast->fecha)->format('d/m/Y') }}
                            </span>
                        </div>
                        <div class="text-sm font-semibold text-white">
                            Glosa: <span class="font-normal text-slate-300">{{ $ast->glosa }}</span>
                        </div>
                        <form action="{{ route('contabilidad.asientos.destroy', $ast->id) }}" method="POST" onsubmit="return confirm('¿Anular asiento contable?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs text-rose-400 hover:text-rose-300 font-bold uppercase">
                                <i class="fa-solid fa-trash-can mr-1"></i> Anular
                            </button>
                        </form>
                    </div>

                    <!-- Asiento Detail Rows -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs font-mono">
                            <thead class="bg-slate-900/60 text-slate-400">
                                <tr>
                                    <th class="p-2">Código Cuenta</th>
                                    <th class="p-2">Nombre Cuenta Contable</th>
                                    <th class="p-2 text-right">DEBE (Bs.)</th>
                                    <th class="p-2 text-right">HABER (Bs.)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-800/40">
                                @foreach($ast->detalles as $det)
                                    <tr>
                                        <td class="p-2 font-bold text-amber-400">{{ $det->cuenta->codigo ?? '-' }}</td>
                                        <td class="p-2 text-slate-200">{{ $det->cuenta->nombre ?? '-' }}</td>
                                        <td class="p-2 text-right font-bold text-sky-400">
                                            {{ $det->debe > 0 ? 'Bs. ' . number_format($det->debe, 2) : '-' }}
                                        </td>
                                        <td class="p-2 text-right font-bold text-emerald-400">
                                            {{ $det->haber > 0 ? 'Bs. ' . number_format($det->haber, 2) : '-' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @empty
                <div class="p-12 text-center text-slate-500">
                    <i class="fa-solid fa-book-open text-4xl mb-3 block text-slate-600"></i>
                    No hay asientos contables en el Libro Diario.
                </div>
            @endforelse
        </div>
    </div>

    <!-- Create Account Modal -->
    <div x-show="cuentaModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md"
         style="display: none;">
        
        <div class="glass-card w-full max-w-md rounded-2xl overflow-hidden shadow-2xl border border-amber-500/30" @click.away="cuentaModal = false">
            <div class="p-6 border-b border-slate-800 flex items-center justify-between">
                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                    <i class="fa-solid fa-folder-tree text-amber-500"></i> Nueva Cuenta en Plan de Cuentas
                </h3>
                <button @click="cuentaModal = false" class="text-slate-400 hover:text-white">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <form action="{{ route('contabilidad.cuentas.store') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Código Cuenta *</label>
                    <input type="text" name="codigo" required placeholder="Ej: 1.1.01.01" class="w-full py-2.5 text-sm font-mono text-amber-400 font-bold">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Nombre de Cuenta *</label>
                    <input type="text" name="nombre" required placeholder="Ej: Caja General Moneda Nacional" class="w-full py-2.5 text-sm">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Tipo de Cuenta *</label>
                        <select name="tipo" class="w-full py-2.5 text-sm">
                            <option value="activo">Activo</option>
                            <option value="pasivo">Pasivo</option>
                            <option value="patrimonio">Patrimonio</option>
                            <option value="ingreso">Ingreso</option>
                            <option value="gasto">Gasto / Costo</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Nivel *</label>
                        <input type="number" step="1" min="1" max="5" name="nivel" value="4" required class="w-full py-2.5 text-sm font-mono">
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-800">
                    <button type="button" @click="cuentaModal = false" class="px-4 py-2.5 rounded-xl bg-slate-800 text-slate-300 hover:bg-slate-700 text-xs font-bold uppercase tracking-wider">
                        Cancelar
                    </button>
                    <button type="submit" class="btn-vibrant-amber px-5 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider">
                        Guardar Cuenta
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Create Journal Entry Modal -->
    <div x-show="asientoModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md"
         style="display: none;">
        
        <div class="glass-card w-full max-w-2xl rounded-2xl overflow-hidden shadow-2xl border border-amber-500/30" @click.away="asientoModal = false">
            <div class="p-6 border-b border-slate-800 flex items-center justify-between">
                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                    <i class="fa-solid fa-pen-to-square text-amber-500"></i> Registrar Asiento Diario
                </h3>
                <button @click="asientoModal = false" class="text-slate-400 hover:text-white">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <form action="{{ route('contabilidad.asientos.store') }}" method="POST" class="p-6 space-y-4">
                @csrf
                
                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Fecha *</label>
                    <input type="date" name="fecha" value="{{ date('Y-m-d') }}" required class="w-full py-2.5 text-sm font-mono">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Glosa / Concepto del Asiento *</label>
                    <textarea name="glosa" rows="2" required placeholder="Glosa explicativa de la transacción contable..." class="w-full py-2.5 text-sm"></textarea>
                </div>

                <!-- 2-Row Entry Form -->
                <div class="bg-slate-900/80 p-4 rounded-xl border border-slate-800 space-y-3 font-mono">
                    <span class="text-xs font-bold uppercase tracking-wider text-amber-400 block">Cuentas Contables (Debe vs Haber)</span>
                    
                    <!-- Row 1 (Debe) -->
                    <div class="grid grid-cols-3 gap-3">
                        <select name="cuentas[]" required class="w-full py-2 text-xs">
                            <option value="">Cuenta DEBE</option>
                            @foreach($cuentas as $c)
                                <option value="{{ $c->id }}">{{ $c->codigo }} - {{ $c->nombre }}</option>
                            @endforeach
                        </select>
                        <input type="number" step="0.01" min="0" name="debes[]" placeholder="DEBE (Bs.)" class="w-full py-2 text-xs font-bold text-sky-400">
                        <input type="number" step="0.01" min="0" name="haberes[]" value="0.00" class="w-full py-2 text-xs text-slate-400">
                    </div>

                    <!-- Row 2 (Haber) -->
                    <div class="grid grid-cols-3 gap-3">
                        <select name="cuentas[]" required class="w-full py-2 text-xs">
                            <option value="">Cuenta HABER</option>
                            @foreach($cuentas as $c)
                                <option value="{{ $c->id }}">{{ $c->codigo }} - {{ $c->nombre }}</option>
                            @endforeach
                        </select>
                        <input type="number" step="0.01" min="0" name="debes[]" value="0.00" class="w-full py-2 text-xs text-slate-400">
                        <input type="number" step="0.01" min="0" name="haberes[]" placeholder="HABER (Bs.)" class="w-full py-2 text-xs font-bold text-emerald-400">
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-800">
                    <button type="button" @click="asientoModal = false" class="px-4 py-2.5 rounded-xl bg-slate-800 text-slate-300 hover:bg-slate-700 text-xs font-bold uppercase tracking-wider">
                        Cancelar
                    </button>
                    <button type="submit" class="btn-vibrant-amber px-5 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider">
                        Guardar Asiento Contable
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
