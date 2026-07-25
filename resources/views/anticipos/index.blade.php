@extends('layouts.app')

@section('title', 'Anticipos')

@section('content')
<div x-data="{
    openModal: false,
    tipo_receptor: 'trabajador',
    trabajador_id: '',
    socio_id: '',
    fecha: '{{ date('Y-m-d') }}',
    monto: '',
    motivo: '',

    openCreate() {
        this.tipo_receptor = 'trabajador';
        this.trabajador_id = '';
        this.socio_id = '';
        this.fecha = '{{ date('Y-m-d') }}';
        this.monto = '';
        this.motivo = '';
        this.openModal = true;
    }
}" class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-slate-100">Gestión de Anticipos</h1>
            <p class="text-sm text-slate-400 mt-1">Registra adelantos en efectivo entregados a socios y personal. Se descuentan automáticamente en las liquidaciones de pago.</p>
        </div>
        <button @click="openCreate()" class="btn-vibrant-amber inline-flex items-center justify-center px-4 py-2.5 rounded-lg text-sm font-bold shadow-lg self-start">
            <i class="fa-solid fa-hand-holding-dollar mr-2"></i> Registrar Nuevo Anticipo
        </button>
    </div>

    <!-- Filters Section -->
    <div class="glass-card rounded-xl p-6 no-print">
        <form action="{{ route('anticipos.index') }}" method="GET" class="grid grid-cols-1 gap-4 sm:grid-cols-4 items-end">
            <div>
                <label for="tipo_receptor_filter" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Tipo Receptor</label>
                <select name="tipo_receptor" id="tipo_receptor_filter" 
                        class="mt-1 block w-full px-3 py-2 bg-slate-900 border border-slate-700/80 rounded-lg text-slate-100 focus:outline-none focus:ring-1 focus:ring-amber-500 focus:border-amber-500 text-sm">
                    <option value="">Todos (Socios y Personal)</option>
                    <option value="trabajador" {{ request('tipo_receptor') === 'trabajador' ? 'selected' : '' }}>Personal / Trabajadores</option>
                    <option value="socio" {{ request('tipo_receptor') === 'socio' ? 'selected' : '' }}>Socios Cooperativistas</option>
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
                <label for="estado_filter" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Estado de Saldo</label>
                <select name="estado" id="estado_filter" 
                        class="mt-1 block w-full px-3 py-2 bg-slate-900 border border-slate-700/80 rounded-lg text-slate-100 focus:outline-none focus:ring-1 focus:ring-amber-500 focus:border-amber-500 text-sm">
                    <option value="">Todos los Anticipos</option>
                    <option value="pendiente" {{ request('estado') === 'pendiente' ? 'selected' : '' }}>Con Saldo Pendiente</option>
                    <option value="pagado" {{ request('estado') === 'pagado' ? 'selected' : '' }}>Totalmente Descontados</option>
                </select>
            </div>

            <div class="flex space-x-2">
                <button type="submit" class="btn-vibrant-warm flex-1 inline-flex items-center justify-center px-4 py-2 text-sm font-bold rounded-lg shadow-lg">
                    <i class="fa-solid fa-magnifying-glass mr-2"></i> Filtrar
                </button>
                <a href="{{ route('anticipos.index') }}" class="inline-flex items-center justify-center px-3 py-2 bg-slate-900 border border-slate-800 hover:bg-slate-800 text-sm font-medium text-slate-400 rounded-lg transition duration-150" title="Limpiar Filtros">
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
                        <th class="px-6 py-4 font-semibold">Nº / Fecha</th>
                        <th class="px-6 py-4 font-semibold">Beneficiario / Receptor</th>
                        <th class="px-6 py-4 font-semibold">Bocamina</th>
                        <th class="px-6 py-4 font-semibold">Motivo / Concepto</th>
                        <th class="px-6 py-4 font-semibold">Monto Original</th>
                        <th class="px-6 py-4 font-semibold">Saldo Restante</th>
                        <th class="px-6 py-4 font-semibold">Estado</th>
                        <th class="px-6 py-4 font-semibold no-print">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/40 text-sm text-slate-300">
                    @forelse($anticipos as $anticipo)
                        <tr class="hover:bg-slate-900/10 transition duration-150">
                            <td class="px-6 py-4">
                                <span class="font-mono text-xs text-amber-500 font-bold">#{{ $anticipo->id }}</span>
                                <span class="block font-mono text-xs text-slate-450">{{ $anticipo->fecha->format('d/m/Y') }}</span>
                            </td>
                            <td class="px-6 py-4 font-medium text-slate-100">
                                @if($anticipo->tipo_receptor === 'socio' || $anticipo->socio_id)
                                    <div class="flex items-center space-x-2">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-amber-500/10 text-amber-400 border border-amber-500/30">SOCIO</span>
                                        <span>{{ $anticipo->socio->nombre ?? 'Socio no encontrado' }}</span>
                                    </div>
                                @else
                                    <div class="flex items-center space-x-2">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-blue-500/10 text-blue-400 border border-blue-500/30">PERSONAL</span>
                                        <span>{{ $anticipo->trabajador->nombre ?? 'Trabajador no encontrado' }}</span>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-xs">
                                @php
                                    $bocamina = $anticipo->tipo_receptor === 'socio' ? $anticipo->socio?->bocamina : $anticipo->trabajador?->bocamina;
                                @endphp
                                @if($bocamina)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-800 text-slate-300 border border-slate-700">
                                        <i class="fa-solid fa-mountain mr-1.5 text-amber-500"></i> {{ $bocamina->nombre }}
                                    </span>
                                @else
                                    <span class="text-slate-500 italic">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-300">
                                {{ $anticipo->motivo ?: 'Sin motivo registrado' }}
                            </td>
                            <td class="px-6 py-4 font-mono font-medium text-slate-200">Bs. {{ number_format($anticipo->monto, 2) }}</td>
                            <td class="px-6 py-4 font-mono font-bold text-amber-500">Bs. {{ number_format($anticipo->saldo, 2) }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $anticipo->saldo == 0 ? 'bg-slate-800 text-slate-400 border border-slate-700' : 'bg-red-500/10 text-red-400 border border-red-500/25' }}">
                                    {{ $anticipo->saldo == 0 ? 'Descontado' : 'Pendiente' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 no-print">
                                <div class="flex space-x-2">
                                    <!-- Botón de impresión -->
                                    <a href="{{ route('anticipos.recibo', $anticipo->id) }}" target="_blank"
                                       class="p-2 rounded-lg bg-slate-800/80 hover:bg-emerald-500/20 text-slate-300 hover:text-emerald-400 border border-slate-700/60 hover:border-emerald-500/40 transition-all duration-300 hover:scale-105 active:scale-95 shadow-sm" title="Imprimir Recibo">
                                        <i class="fa-solid fa-print text-xs"></i>
                                    </a>

                                    @if($anticipo->saldo == $anticipo->monto)
                                        <form action="{{ route('anticipos.destroy', $anticipo->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro de eliminar este anticipo?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 rounded-lg bg-slate-800/80 hover:bg-red-500/20 text-slate-300 hover:text-red-400 border border-slate-700/60 hover:border-red-500/40 transition-all duration-300 hover:scale-105 active:scale-95 shadow-sm" title="Eliminar">
                                                <i class="fa-solid fa-trash text-xs"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-slate-500">
                                <i class="fa-solid fa-money-bill-transfer text-4xl mb-3 block text-slate-600"></i>
                                No se encontraron anticipos registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal para Nuevo Anticipo -->
    <div x-show="openModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm" x-cloak>
        <div @click.away="openModal = false" class="glass-card w-full max-w-lg rounded-2xl overflow-hidden shadow-2xl border border-slate-800/80 relative">
            <div class="px-6 py-4 border-b border-slate-800/80 flex items-center justify-between bg-slate-900/60">
                <h3 class="text-lg font-bold text-slate-100">Registrar Adelanto / Anticipo</h3>
                <button @click="openModal = false" class="text-slate-400 hover:text-slate-200">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <form action="{{ route('anticipos.store') }}" method="POST">
                @csrf
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-1">Tipo de Beneficiario</label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="flex items-center justify-center p-3 rounded-lg border cursor-pointer transition"
                                   :class="tipo_receptor === 'trabajador' ? 'bg-amber-500/10 border-amber-500 text-amber-400 font-bold' : 'bg-slate-900 border-slate-700 text-slate-400'">
                                <input type="radio" name="tipo_receptor" value="trabajador" x-model="tipo_receptor" class="sr-only">
                                <i class="fa-solid fa-user-group mr-2"></i> Personal / Trabajador
                            </label>
                            <label class="flex items-center justify-center p-3 rounded-lg border cursor-pointer transition"
                                   :class="tipo_receptor === 'socio' ? 'bg-amber-500/10 border-amber-500 text-amber-400 font-bold' : 'bg-slate-900 border-slate-700 text-slate-400'">
                                <input type="radio" name="tipo_receptor" value="socio" x-model="tipo_receptor" class="sr-only">
                                <i class="fa-solid fa-id-card mr-2"></i> Socio Cooperativista
                            </label>
                        </div>
                    </div>

                    <div x-show="tipo_receptor === 'trabajador'">
                        <label for="modal_trabajador_id" class="block text-sm font-medium text-slate-300">Seleccionar Trabajador / Personal</label>
                        <select id="modal_trabajador_id" name="trabajador_id" x-model="trabajador_id"
                                class="mt-1 block w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-lg text-slate-100 focus:outline-none focus:ring-1 focus:ring-amber-500 focus:border-amber-500 text-sm">
                            <option value="">Seleccione a la persona...</option>
                            @foreach($trabajadores as $tr)
                                <option value="{{ $tr->id }}">{{ $tr->nombre }} ({{ ucfirst($tr->cargo) }} - {{ $tr->bocamina->nombre }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div x-show="tipo_receptor === 'socio'">
                        <label for="modal_socio_id" class="block text-sm font-medium text-slate-300">Seleccionar Socio Cooperativista</label>
                        <select id="modal_socio_id" name="socio_id" x-model="socio_id"
                                class="mt-1 block w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-lg text-slate-100 focus:outline-none focus:ring-1 focus:ring-amber-500 focus:border-amber-500 text-sm">
                            <option value="">Seleccione al socio...</option>
                            @foreach($socios as $so)
                                <option value="{{ $so->id }}">{{ $so->codigo }} - {{ $so->nombre }} ({{ $so->bocamina?->nombre ?: 'Sin bocamina' }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="modal_fecha" class="block text-sm font-medium text-slate-300">Fecha del Anticipo</label>
                            <input id="modal_fecha" name="fecha" type="date" required x-model="fecha"
                                   class="mt-1 block w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-lg text-slate-100 focus:outline-none focus:ring-1 focus:ring-amber-500 focus:border-amber-500 text-sm">
                        </div>
                        <div>
                            <label for="modal_monto" class="block text-sm font-medium text-slate-300">Monto Entregado (Bs.)</label>
                            <input id="modal_monto" name="monto" type="number" step="0.01" min="0.01" required x-model="monto"
                                   class="mt-1 block w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-lg text-slate-100 focus:outline-none focus:ring-1 focus:ring-amber-500 focus:border-amber-500 text-sm font-mono"
                                   placeholder="500.00">
                        </div>
                    </div>

                    <div>
                        <label for="modal_motivo" class="block text-sm font-medium text-slate-300">Motivo / Observación</label>
                        <input id="modal_motivo" name="motivo" type="text" x-model="motivo"
                               class="mt-1 block w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-lg text-slate-100 focus:outline-none focus:ring-1 focus:ring-amber-500 focus:border-amber-500 text-sm"
                               placeholder="Ej. Adelanto para gastos personales / combustible">
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-slate-800/80 bg-slate-900/40 flex justify-end space-x-3">
                    <button type="button" @click="openModal = false" class="px-4 py-2 text-sm font-medium rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-350 border border-slate-700/60 hover:border-slate-600 transition-all duration-200">
                        Cancelar
                    </button>
                    <button type="submit" 
                            :disabled="!monto || (tipo_receptor === 'trabajador' && !trabajador_id) || (tipo_receptor === 'socio' && !socio_id)"
                            :class="(!monto || (tipo_receptor === 'trabajador' && !trabajador_id) || (tipo_receptor === 'socio' && !socio_id)) ? 'opacity-50 cursor-not-allowed' : ''"
                            class="btn-vibrant-amber px-4 py-2 text-sm font-bold rounded-lg shadow-lg transition-all duration-150">
                        Guardar Anticipo
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
