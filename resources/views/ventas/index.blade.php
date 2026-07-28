@extends('layouts.app')

@section('title', 'Venta de Cargas (Comercialización)')

@section('content')
<div x-data="{
    openModal: false,
    editMode: false,
    ventaId: null,
    numero_venta: '',
    fecha: '{{ date('Y-m-d') }}',
    socio_id: '',
    bocamina_id: '',
    tipo_mineral: 'Complejo (Zn-Pb-Ag)',
    presentacion: 'saco',
    cantidad: '',
    peso_bruto: '',
    tara: '',
    peso_neto: '',
    ley_mineral: '',
    precio_unitario: '',
    comprador: '',
    caja_id: '',
    observaciones: '',
    editActionUrl: '',

    calcularTotal() {
        const p = parseFloat(this.peso_neto) || 0;
        const u = parseFloat(this.precio_unitario) || 0;
        return (p * u).toFixed(2);
    },

    openCreate() {
        this.editMode = false;
        this.ventaId = null;
        this.numero_venta = 'VTA-' + Math.floor(1000 + Math.random() * 9000);
        this.fecha = '{{ date('Y-m-d') }}';
        this.socio_id = '';
        this.bocamina_id = '';
        this.tipo_mineral = 'Complejo (Zn-Pb-Ag)';
        this.presentacion = 'saco';
        this.cantidad = '';
        this.peso_bruto = '';
        this.tara = '';
        this.peso_neto = '';
        this.ley_mineral = '';
        this.precio_unitario = '';
        this.comprador = '';
        this.caja_id = '';
        this.observaciones = '';
        this.openModal = true;
    },
    openEdit(venta) {
        this.editMode = true;
        this.ventaId = venta.id;
        this.numero_venta = venta.numero_venta;
        this.fecha = venta.fecha;
        this.socio_id = venta.socio_id;
        this.bocamina_id = venta.bocamina_id;
        this.tipo_mineral = venta.tipo_mineral;
        this.presentacion = venta.presentacion || 'saco';
        this.cantidad = venta.cantidad || '';
        this.peso_bruto = venta.peso_bruto || '';
        this.tara = venta.tara || '';
        this.peso_neto = venta.peso_neto;
        this.ley_mineral = venta.ley_mineral || '';
        this.precio_unitario = venta.precio_unitario;
        this.comprador = venta.comprador;
        this.caja_id = venta.caja_id || '';
        this.observaciones = venta.observaciones || '';
        this.editActionUrl = '/ventas-cargas/' + venta.id;
        this.openModal = true;
    }
}" class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-slate-100">Comercialización & Venta de Cargas</h1>
            <p class="text-sm text-slate-400 mt-1">Registra la venta de minerales por socio y bocamina. Genera automáticamente el ingreso a caja.</p>
        </div>
        <button @click="openCreate()" class="btn-vibrant-amber inline-flex items-center justify-center px-4 py-2.5 rounded-lg text-sm font-bold shadow-lg self-start">
            <i class="fa-solid fa-cart-plus mr-2"></i> Registrar Nueva Venta
        </button>
    </div>

    <!-- Filters Section -->
    <div class="glass-card rounded-xl p-6 no-print">
        <form action="{{ route('ventas-cargas.index') }}" method="GET" class="grid grid-cols-1 gap-4 sm:grid-cols-5 items-end">
            <div>
                <label for="socio_id_filter" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Socio</label>
                <select name="socio_id" id="socio_id_filter" 
                        class="mt-1 block w-full px-3 py-2 bg-slate-900 border border-slate-700/80 rounded-lg text-slate-100 focus:outline-none focus:ring-1 focus:ring-amber-500 focus:border-amber-500 text-sm">
                    <option value="">Todos los Socios</option>
                    @foreach($socios as $socio)
                        <option value="{{ $socio->id }}" {{ request('socio_id') == $socio->id ? 'selected' : '' }}>{{ $socio->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="bocamina_id_filter" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Bocamina</label>
                <select name="bocamina_id" id="bocamina_id_filter" 
                        class="mt-1 block w-full px-3 py-2 bg-slate-900 border border-slate-700/80 rounded-lg text-slate-100 focus:outline-none focus:ring-1 focus:ring-amber-500 focus:border-amber-500 text-sm">
                    <option value="">Todas las Bocaminas</option>
                    @foreach($bocaminas as $bocamina)
                        <option value="{{ $bocamina->id }}" {{ request('bocamina_id') == $bocamina->id ? 'selected' : '' }}>{{ $bocamina->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="tipo_mineral_filter" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Tipo de Mineral</label>
                <select name="tipo_mineral" id="tipo_mineral_filter" 
                        class="mt-1 block w-full px-3 py-2 bg-slate-900 border border-slate-700/80 rounded-lg text-slate-100 focus:outline-none focus:ring-1 focus:ring-amber-500 focus:border-amber-500 text-sm">
                    <option value="">Todos los Minerales</option>
                    @foreach($minerales as $m)
                        <option value="{{ $m }}" {{ request('tipo_mineral') === $m ? 'selected' : '' }}>{{ $m }}</option>
                    @endforeach
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
                <a href="{{ route('ventas-cargas.index') }}" class="inline-flex items-center justify-center px-3 py-2 bg-slate-900 border border-slate-800 hover:bg-slate-800 text-sm font-medium text-slate-400 rounded-lg transition duration-150" title="Limpiar Filtros">
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
                        <th class="px-6 py-4 font-semibold">Nº Venta / Fecha</th>
                        <th class="px-6 py-4 font-semibold">Socio</th>
                        <th class="px-6 py-4 font-semibold">Bocamina</th>
                        <th class="px-6 py-4 font-semibold">Mineral</th>
                        <th class="px-6 py-4 font-semibold">Peso (Tn) / Precio</th>
                        <th class="px-6 py-4 font-semibold">Total Vendido</th>
                        <th class="px-6 py-4 font-semibold">Comprador</th>
                        <th class="px-6 py-4 font-semibold no-print">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/40 text-sm text-slate-300">
                    @forelse($ventas as $venta)
                        <tr class="hover:bg-slate-900/10 transition duration-150">
                            <td class="px-6 py-4">
                                <span class="font-mono text-xs text-amber-500 font-bold">{{ $venta->numero_venta }}</span>
                                <span class="block font-mono text-xs text-slate-450">{{ $venta->fecha->format('d/m/Y') }}</span>
                            </td>
                            <td class="px-6 py-4 font-medium text-slate-100">
                                {{ $venta->socio->nombre }}
                                <span class="block text-xs font-mono text-slate-450">{{ $venta->socio->codigo }}</span>
                            </td>
                            <td class="px-6 py-4 text-xs">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-800 text-slate-300 border border-slate-700">
                                    <i class="fa-solid fa-mountain mr-1.5 text-amber-500"></i> {{ $venta->bocamina->nombre }}
                                </span>
                            </td>
                            <td class="px-6 py-4 font-semibold text-emerald-400 text-xs">
                                {{ $venta->tipo_mineral }}
                                @if($venta->cantidad)
                                    <span class="block text-slate-450 font-normal">({{ $venta->cantidad }} sacos/bazas)</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-mono text-xs">
                                <span class="text-slate-200 font-bold">{{ number_format($venta->peso_neto, 2) }} Tn</span>
                                <span class="block text-slate-450">Bs. {{ number_format($venta->precio_unitario, 2) }}/Tn</span>
                            </td>
                            <td class="px-6 py-4 font-mono font-bold text-amber-500 text-base">
                                Bs. {{ number_format($venta->total_vendido, 2) }}
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-300">
                                {{ $venta->comprador }}
                            </td>
                            <td class="px-6 py-4 no-print">
                                <div class="flex space-x-2">
                                    <button @click="openEdit({{ $venta }})" class="p-2 rounded-lg bg-slate-800/80 hover:bg-amber-500/20 text-slate-300 hover:text-amber-400 border border-slate-700/60 hover:border-amber-500/40 transition-all duration-300 hover:scale-105 active:scale-95 shadow-sm" title="Editar">
                                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                                    </button>
                                    <form action="{{ route('ventas-cargas.destroy', $venta->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro de eliminar esta venta?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 rounded-lg bg-slate-800/80 hover:bg-red-500/20 text-slate-300 hover:text-red-400 border border-slate-700/60 hover:border-red-500/40 transition-all duration-300 hover:scale-105 active:scale-95 shadow-sm" title="Eliminar">
                                            <i class="fa-solid fa-trash text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-slate-500">
                                <i class="fa-solid fa-truck-ramp-box text-4xl mb-3 block text-slate-600"></i>
                                No se encontraron ventas de cargas registradas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Form (Create/Edit) -->
    <div x-show="openModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm" x-cloak>
        <div @click.away="openModal = false" class="glass-card w-full max-w-xl rounded-2xl overflow-hidden shadow-2xl border border-slate-800/80 relative">
            <div class="px-6 py-4 border-b border-slate-800/80 flex items-center justify-between bg-slate-900/60">
                <h3 class="text-lg font-bold text-slate-100" x-text="editMode ? 'Editar Venta de Carga' : 'Registrar Venta de Carga de Mineral'"></h3>
                <button @click="openModal = false" class="text-slate-400 hover:text-slate-200">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <form :action="editMode ? editActionUrl : '{{ route('ventas-cargas.store') }}'" method="POST">
                @csrf
                <template x-if="editMode">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="modal_numero_venta" class="block text-sm font-medium text-slate-300">Nº de Venta / Lote</label>
                            <input id="modal_numero_venta" name="numero_venta" type="text" required x-model="numero_venta"
                                   class="mt-1 block w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-lg text-slate-100 focus:outline-none focus:ring-1 focus:ring-amber-500 focus:border-amber-500 text-sm font-mono"
                                   placeholder="Ej. VTA-1001">
                        </div>
                        <div>
                            <label for="modal_fecha" class="block text-sm font-medium text-slate-300">Fecha de Venta</label>
                            <input id="modal_fecha" name="fecha" type="date" required x-model="fecha"
                                   class="mt-1 block w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-lg text-slate-100 focus:outline-none focus:ring-1 focus:ring-amber-500 focus:border-amber-500 text-sm">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="modal_socio" class="block text-sm font-medium text-slate-300">Socio Vendedor</label>
                            <select id="modal_socio" name="socio_id" required x-model="socio_id"
                                    class="mt-1 block w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-lg text-slate-100 focus:outline-none focus:ring-1 focus:ring-amber-500 focus:border-amber-500 text-sm">
                                <option value="">Seleccione al socio...</option>
                                @foreach($socios as $socio)
                                    <option value="{{ $socio->id }}">{{ $socio->codigo }} - {{ $socio->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="modal_bocamina" class="block text-sm font-medium text-slate-300">Bocamina de Origen</label>
                            <select id="modal_bocamina" name="bocamina_id" required x-model="bocamina_id"
                                    class="mt-1 block w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-lg text-slate-100 focus:outline-none focus:ring-1 focus:ring-amber-500 focus:border-amber-500 text-sm">
                                <option value="">Seleccione la bocamina...</option>
                                @foreach($bocaminas as $bocamina)
                                    <option value="{{ $bocamina->id }}">{{ $bocamina->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="modal_presentacion" class="block text-sm font-medium text-slate-300">Presentación / Formato *</label>
                            <select id="modal_presentacion" name="presentacion" required x-model="presentacion"
                                    class="mt-1 block w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-lg text-slate-100 focus:outline-none focus:ring-1 focus:ring-amber-500 focus:border-amber-500 text-sm font-bold text-amber-400">
                                <option value="saco">📦 Sacos / Cargas</option>
                                <option value="volqueta">🚛 Volqueta de Mineral</option>
                                <option value="concentrado">🏭 Concentrado Procesado</option>
                                <option value="bruto">🪨 Mineral Bruto</option>
                            </select>
                        </div>
                        <div>
                            <label for="modal_tipo_mineral" class="block text-sm font-medium text-slate-300">Tipo de Mineral *</label>
                            <select id="modal_tipo_mineral" name="tipo_mineral" required x-model="tipo_mineral"
                                    class="mt-1 block w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-lg text-slate-100 focus:outline-none focus:ring-1 focus:ring-amber-500 focus:border-amber-500 text-sm">
                                @foreach($minerales as $m)
                                    <option value="{{ $m }}">{{ $m }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Ficha de Pesajes y Ley -->
                    <div class="bg-slate-900/90 p-3.5 rounded-xl border border-slate-800 space-y-3">
                        <span class="text-xs font-bold uppercase tracking-wider text-amber-500 block flex items-center gap-1.5">
                            <i class="fa-solid fa-scale-balanced"></i> Pesaje y Calidad de Carga
                        </span>

                        <div class="grid grid-cols-3 gap-3">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Peso Bruto (Tn)</label>
                                <input type="number" step="0.01" min="0" name="peso_bruto" x-model="peso_bruto" @input="if(peso_bruto && tara) peso_neto = (parseFloat(peso_bruto) - parseFloat(tara)).toFixed(2)" placeholder="0.00" class="w-full py-1.5 px-2 bg-slate-950 border border-slate-700 rounded text-xs font-mono">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Tara (Tn)</label>
                                <input type="number" step="0.01" min="0" name="tara" x-model="tara" @input="if(peso_bruto && tara) peso_neto = (parseFloat(peso_bruto) - parseFloat(tara)).toFixed(2)" placeholder="0.00" class="w-full py-1.5 px-2 bg-slate-950 border border-slate-700 rounded text-xs font-mono">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Peso Neto (Tn) *</label>
                                <input id="modal_peso_neto" name="peso_neto" type="number" step="0.01" min="0.01" required x-model="peso_neto"
                                       class="w-full py-1.5 px-2 bg-slate-950 border border-slate-700 rounded text-xs font-mono font-bold text-amber-400"
                                       placeholder="Ej. 15.50">
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Ley del Mineral (% / DM Ag / g/t Au)</label>
                            <input type="text" name="ley_mineral" x-model="ley_mineral" placeholder="Ej: 45% Pb, 120 DM Ag" class="w-full py-1.5 px-2 bg-slate-950 border border-slate-700 rounded text-xs">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="modal_precio_unitario" class="block text-sm font-medium text-slate-300">Precio Unitario por Tn (Bs.)</label>
                            <input id="modal_precio_unitario" name="precio_unitario" type="number" step="0.01" min="0.01" required x-model="precio_unitario"
                                   class="mt-1 block w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-lg text-slate-100 focus:outline-none focus:ring-1 focus:ring-amber-500 focus:border-amber-500 text-sm font-mono"
                                   placeholder="Ej. 3200.00">
                        </div>
                        <div>
                            <label for="modal_caja_id" class="block text-sm font-medium text-slate-300">Acreditar a Caja *</label>
                            <select id="modal_caja_id" name="caja_id" x-model="caja_id"
                                    class="mt-1 block w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-lg text-slate-100 focus:outline-none focus:ring-1 focus:ring-amber-500 focus:border-amber-500 text-sm font-bold text-emerald-400">
                                @foreach($cajas as $caja)
                                    <option value="{{ $caja->id }}">{{ $caja->nombre }} (Saldo: Bs. {{ number_format($caja->saldo_actual, 2) }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Auto Calculation Display -->
                    <div class="p-3 bg-amber-500/10 border border-amber-500/30 rounded-xl flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-amber-400">Total Vendido Calculado (Bs.):</span>
                        <span class="text-xl font-bold font-mono text-amber-500" x-text="calcularTotal()">0.00</span>
                    </div>

                    <div>
                        <label for="modal_comprador" class="block text-sm font-medium text-slate-300">Empresa Compradora / Comercializadora</label>
                        <input id="modal_comprador" name="comprador" type="text" required x-model="comprador"
                               class="mt-1 block w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-lg text-slate-100 focus:outline-none focus:ring-1 focus:ring-amber-500 focus:border-amber-500 text-sm"
                               placeholder="Ej. Empresa Minera Manquiri S.A.">
                    </div>

                    <div>
                        <label for="modal_observaciones" class="block text-sm font-medium text-slate-300">Observaciones (Opcional)</label>
                        <textarea id="modal_observaciones" name="observaciones" rows="2" x-model="observaciones"
                                  class="mt-1 block w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-lg text-slate-100 focus:outline-none focus:ring-1 focus:ring-amber-500 focus:border-amber-500 text-sm"
                                  placeholder="Detalles sobre deducciones de humedad, ley de mineral..."></textarea>
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-slate-800/80 bg-slate-900/40 flex justify-end space-x-3">
                    <button type="button" @click="openModal = false" class="px-4 py-2 text-sm font-medium rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-350 border border-slate-700/60 hover:border-slate-600 transition-all duration-200">
                        Cancelar
                    </button>
                    <button type="submit" 
                            :disabled="!numero_venta || !socio_id || !bocamina_id || !peso_neto || !precio_unitario || !comprador"
                            :class="(!numero_venta || !socio_id || !bocamina_id || !peso_neto || !precio_unitario || !comprador) ? 'opacity-50 cursor-not-allowed' : ''"
                            class="btn-vibrant-amber px-4 py-2 text-sm font-bold rounded-lg shadow-lg transition-all duration-150">
                        Guardar Venta & Generar Ingreso
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
