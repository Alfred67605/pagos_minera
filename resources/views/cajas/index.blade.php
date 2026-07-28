@extends('layouts.app')

@section('title', 'Fondo de Efectivo & Recarga Banco para Personal')

@section('content')
<div class="space-y-8" x-data="{ 
    modalOpen: false, 
    simuladorModalOpen: false,
    simulacion: {
        banco: 'Banco Unión S.A.',
        monto: 1000,
        nro_comprobante: 'CH-892401',
        caja_id: '{{ $cajas->where('tipo', 'caja_chica')->first()->id ?? '' }}',
        responsable: 'Administración de Mina',
        glosa: 'Retiro en efectivo para pago semanal de contratistas y anticipos'
    }
}">
    
    <!-- Header Banner Principal -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-5 bg-gradient-to-r from-slate-900 via-slate-900 to-slate-950 p-6 md:p-8 rounded-3xl border border-slate-800 shadow-2xl">
        <div class="space-y-2">
            <div class="flex flex-wrap items-center gap-2">
                <span class="px-3 py-1 rounded-full text-xs font-extrabold uppercase tracking-wider bg-rose-500/20 text-rose-300 border border-rose-500/40">
                    <i class="fa-solid fa-users-gear mr-1"></i> Módulo de Personal & Nóminas
                </span>
                <span class="px-3 py-1 rounded-full text-xs font-extrabold uppercase tracking-wider bg-emerald-500/20 text-emerald-300 border border-emerald-500/40">
                    <i class="fa-solid fa-shield-check mr-1"></i> Aislado de Ventas de Mineral
                </span>
            </div>
            
            <h1 class="text-2xl md:text-3xl font-extrabold text-white tracking-tight flex items-center gap-3">
                <div class="w-12 h-12 rounded-2xl bg-cyan-500/20 text-cyan-400 border border-cyan-500/40 flex items-center justify-center text-2xl shadow-lg shadow-cyan-500/10">
                    <i class="fa-solid fa-building-columns"></i>
                </div>
                Fondo y Recarga de Efectivo para Personal (Banco)
            </h1>
            <p class="text-sm md:text-base text-slate-300 max-w-3xl leading-relaxed">
                Simula y registra el dinero en efectivo que retira el jefe del banco, controla las salidas de sueldos y anticipos, y verifica de forma clara <strong>cuánto dinero te sobró en la semana o si faltó efectivo</strong>.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-3 flex-shrink-0">
            <button @click="simuladorModalOpen = true" 
                    class="btn-vibrant-success px-6 py-3.5 rounded-2xl text-xs md:text-sm font-extrabold uppercase tracking-wider flex items-center justify-center gap-2.5 shadow-xl shadow-emerald-500/20 hover:scale-[1.02] transition-transform">
                <i class="fa-solid fa-money-bill-transfer text-lg"></i> 💳 Recargar Dinero del Banco
            </button>

            <button @click="modalOpen = true" 
                    class="px-4 py-3 rounded-2xl bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-extrabold uppercase tracking-wider border border-slate-700 transition flex items-center gap-2">
                <i class="fa-solid fa-plus-circle text-base text-amber-400"></i> Crear Fondo
            </button>
        </div>
    </div>

    <!-- BANNER INFORMATIVO DE AISLAMIENTO FINANCIERO -->
    <div class="bg-slate-900/90 border-2 border-amber-500/40 rounded-2xl p-4 md:p-5 flex items-center gap-4 shadow-lg">
        <div class="w-10 h-10 rounded-xl bg-amber-500/20 text-amber-400 border border-amber-500/40 flex items-center justify-center text-xl flex-shrink-0">
            <i class="fa-solid fa-triangle-exclamation"></i>
        </div>
        <div>
            <h4 class="text-sm font-bold text-amber-300 uppercase tracking-wider">Aislamiento Financiero Exclusivo para Personal</h4>
            <p class="text-xs md:text-sm text-slate-200 mt-0.5">
                Este fondo controla únicamente el dinero destinado a <strong>anticipos y sueldos del personal (semanal y mensual)</strong>. <u>No afecta ni se mezcla</u> con las cajas de venta y compra de mineral.
            </p>
        </div>
    </div>

    <!-- SECCIÓN 1: TARJETAS DE FONDOS DE PERSONAL Y SALDOS -->
    <div class="space-y-5">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-extrabold text-white flex items-center gap-2.5">
                <i class="fa-solid fa-wallet text-rose-400"></i> Fondo Activo de Personal (Anticipos & Sueldos)
            </h2>
            <span class="text-xs text-slate-400 font-mono font-semibold">Cajas de Personal Registradas: {{ $cajas->where('tipo', 'caja_chica')->count() }}</span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            @forelse($cajas->where('tipo', 'caja_chica') as $caja)
                <div class="glass-card p-6 md:p-7 rounded-3xl flex flex-col justify-between space-y-6 border-2 border-slate-800 hover:border-emerald-500/50 transition-all duration-300 shadow-2xl relative overflow-hidden bg-slate-900/90">
                    <div class="absolute top-0 right-0 w-72 h-72 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>

                    <!-- Cabecera de la Caja -->
                    <div class="flex items-start justify-between relative z-10 border-b border-slate-800/80 pb-4">
                        <div>
                            <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-extrabold uppercase tracking-widest font-mono bg-rose-500/20 text-rose-300 border border-rose-500/40">
                                💳 CAJA DE PERSONAL & ANTICIPOS
                            </span>
                            <h3 class="text-2xl font-black text-white mt-2 tracking-tight">{{ $caja->nombre }}</h3>
                        </div>

                        <span class="px-3.5 py-1.5 rounded-full text-xs font-extrabold bg-emerald-500/20 text-emerald-300 border border-emerald-500/50 flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-ping"></span> ACTIVA
                        </span>
                    </div>

                    <!-- DESGLOSE DE MÉTRICAS (LETRA CLARA Y DE ALTO CONTRASTE) -->
                    <div class="grid grid-cols-3 gap-3 relative z-10 font-sans">
                        
                        <!-- Metric 1: Ingresado/Retirado del Banco -->
                        <div class="bg-slate-950 p-4 rounded-2xl border border-sky-500/40 space-y-1.5">
                            <span class="text-xs text-sky-300 font-extrabold uppercase tracking-wider block flex items-center gap-1">
                                <i class="fa-solid fa-building-columns text-sky-400"></i> Retirado Banco
                            </span>
                            <span class="text-lg md:text-xl font-black text-sky-300 font-mono block">
                                Bs. {{ number_format($caja->total_ingresos, 2) }}
                            </span>
                        </div>

                        <!-- Metric 2: Anticipos -->
                        <div class="bg-slate-950 p-4 rounded-2xl border border-rose-500/40 space-y-1.5">
                            <span class="text-xs text-rose-300 font-extrabold uppercase tracking-wider block flex items-center gap-1">
                                <i class="fa-solid fa-money-bill-transfer text-rose-400"></i> Anticipos
                            </span>
                            <span class="text-lg md:text-xl font-black text-rose-300 font-mono block">
                                - Bs. {{ number_format($caja->total_anticipos, 2) }}
                            </span>
                        </div>

                        <!-- Metric 3: Planillas -->
                        <div class="bg-slate-950 p-4 rounded-2xl border border-amber-500/40 space-y-1.5">
                            <span class="text-xs text-amber-300 font-extrabold uppercase tracking-wider block flex items-center gap-1">
                                <i class="fa-solid fa-receipt text-amber-400"></i> Planillas
                            </span>
                            <span class="text-lg md:text-xl font-black text-amber-300 font-mono block">
                                - Bs. {{ number_format($caja->total_planillas, 2) }}
                            </span>
                        </div>
                    </div>

                    <!-- PANEL PRINCIPAL DE SALDO EN MANO (SOBRANTE / FALTANTE) -->
                    <div class="p-5 rounded-2xl border-2 {{ $caja->saldo_actual > 0 ? 'bg-emerald-950/60 border-emerald-400' : ($caja->saldo_actual == 0 ? 'bg-slate-950 border-slate-700' : 'bg-rose-950/60 border-rose-400') }} flex flex-col md:flex-row md:items-center justify-between gap-4 relative z-10 shadow-xl">
                        <div class="space-y-1">
                            <span class="text-xs font-black uppercase tracking-widest block {{ $caja->saldo_actual > 0 ? 'text-emerald-300' : ($caja->saldo_actual == 0 ? 'text-slate-300' : 'text-rose-300') }}">
                                <i class="fa-solid {{ $caja->saldo_actual > 0 ? 'fa-circle-check' : ($caja->saldo_actual == 0 ? 'fa-circle-info' : 'fa-triangle-exclamation') }} text-sm mr-1"></i>
                                EFECTIVO DISPONIBLE EN MANO:
                            </span>
                            <p class="text-xs md:text-sm font-semibold text-slate-100 leading-snug">
                                @if($caja->saldo_actual > 0)
                                    🟢 <strong>TE SOBRAN Bs. {{ number_format($caja->saldo_actual, 2) }}</strong> en efectivo para seguir pagando esta semana.
                                @elseif($caja->saldo_actual == 0)
                                    ⚪ <strong>Caja sin saldo disponible (Bs. 0.00)</strong>. Haz clic en "Recargar Dinero del Banco" para ingresar efectivo.
                                @else
                                    🔴 <strong>FALTÓ / DEBES Bs. {{ number_format(abs($caja->saldo_actual), 2) }}</strong> (Se desembolsó más dinero del ingresado).
                                @endif
                            </p>
                        </div>

                        <div class="text-3xl md:text-4xl font-black font-mono tracking-tight text-right {{ $caja->saldo_actual > 0 ? 'text-emerald-300' : ($caja->saldo_actual == 0 ? 'text-slate-400' : 'text-rose-400') }}">
                            Bs. {{ number_format($caja->saldo_actual, 2) }}
                        </div>
                    </div>

                    <!-- BOTÓN PRINCIPAL DE RECARGA SIMULADA -->
                    <div class="pt-2 flex flex-col sm:flex-row items-center justify-between gap-3 relative z-10">
                        <button @click="simulacion.caja_id = '{{ $caja->id }}'; simuladorModalOpen = true" 
                                class="btn-vibrant-success w-full sm:flex-1 py-3.5 rounded-2xl text-xs md:text-sm font-black uppercase tracking-wider text-center flex items-center justify-center gap-2 shadow-lg shadow-emerald-500/20 hover:scale-[1.02] transition-transform">
                            <i class="fa-solid fa-plus-circle text-lg"></i> Recargar Dinero del Banco
                        </button>

                        <a href="{{ route('cajas.show', $caja->id) }}" class="w-full sm:w-auto px-5 py-3.5 rounded-2xl bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-extrabold uppercase tracking-wider text-center border border-slate-700 transition flex items-center justify-center gap-2">
                            <i class="fa-solid fa-list-check text-cyan-400"></i> Histórico ({{ $caja->movimientos_count }})
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full glass-card p-10 text-center rounded-3xl border-2 border-slate-800">
                    <i class="fa-solid fa-vault text-4xl text-slate-600 mb-3"></i>
                    <p class="text-base text-slate-300 font-bold">No hay cajas de personal registradas actualmente.</p>
                    <button @click="modalOpen = true" class="mt-4 btn-vibrant-amber px-6 py-3 rounded-2xl text-xs font-extrabold uppercase tracking-wider">
                        Crear Fondo de Personal
                    </button>
                </div>
            @endforelse
        </div>
    </div>

    <!-- SECCIÓN 2: OTRAS CAJAS Y FONDOS OPERATIVOS (MINERAL) -->
    <div class="space-y-4 pt-6 border-t border-slate-800">
        <div class="flex items-center justify-between">
            <h2 class="text-base font-extrabold text-slate-300 uppercase tracking-wider flex items-center gap-2">
                <i class="fa-solid fa-vault text-amber-400"></i> Cajas Operativas Comerciales (Mineral & Volquetas)
            </h2>
            <span class="text-xs text-slate-500 font-mono">Separadas de Personal</span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($cajas->where('tipo', 'caja_general') as $caja)
                <div class="glass-card p-6 rounded-2xl space-y-4 border border-slate-800 bg-slate-900/60">
                    <div class="flex items-start justify-between">
                        <div>
                            <span class="text-xs font-bold uppercase text-slate-400 font-mono">Fondo Operativo Comercial</span>
                            <h3 class="text-xl font-bold text-white mt-1">{{ $caja->nombre }}</h3>
                        </div>
                        <span class="text-base font-mono font-extrabold text-amber-400">Bs. {{ number_format($caja->saldo_actual, 2) }}</span>
                    </div>

                    <form action="{{ route('cajas.recargar', $caja->id) }}" method="POST" class="flex items-center gap-2 pt-2">
                        @csrf
                        <input type="number" step="0.01" min="0.01" name="monto" required placeholder="Monto en Bs." class="flex-1 py-2 px-3 bg-slate-950 border border-slate-700 rounded-xl text-sm font-mono text-amber-400 font-bold">
                        <input type="hidden" name="concepto" value="Aporte Fondo Operativo Comercial">
                        <button type="submit" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-extrabold rounded-xl uppercase tracking-wider border border-slate-700">
                            + Recargar
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
    </div>

    <!-- FORMULARIO COMPACTO TIPO PAPELETA BANCARIA (MAX-W-LG) -->
    <div x-show="simuladorModalOpen" 
         x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="fixed inset-0 z-[99999] flex items-center justify-center p-4 sm:p-6 bg-slate-950/90 backdrop-blur-xl overflow-y-auto"
         style="display: none !important;">
        
        <div class="glass-card w-full max-w-lg rounded-3xl shadow-2xl border-2 border-emerald-500/60 bg-slate-900 overflow-hidden my-auto relative z-[99999]" @click.away="simuladorModalOpen = false">
            
            <!-- Encabezado Estilo Papeleta Bancaria -->
            <div class="p-5 bg-gradient-to-r from-emerald-950 via-slate-900 to-slate-900 border-b border-emerald-500/40 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-500/20 text-emerald-300 border border-emerald-500/40 flex items-center justify-center text-lg shadow-lg">
                        <i class="fa-solid fa-building-columns"></i>
                    </div>
                    <div>
                        <span class="text-[10px] font-extrabold uppercase tracking-widest text-emerald-400 font-mono block">Papeleta de Retiro de Efectivo</span>
                        <h3 class="text-lg font-black text-white">Recargar Dinero del Banco</h3>
                    </div>
                </div>
                <button @click="simuladorModalOpen = false" type="button" class="w-8 h-8 rounded-xl bg-slate-800 text-slate-400 hover:text-white flex items-center justify-center transition border border-slate-700">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>

            <!-- Cuerpo del Formulario Compacto -->
            <form x-bind:action="'/cajas/' + simulacion.caja_id + '/recargar'" method="POST" class="p-5 sm:p-6 space-y-4">
                @csrf

                <!-- 1. Selección de Banco -->
                <div>
                    <label class="block text-xs font-black text-slate-200 uppercase tracking-wider mb-1">1. Banco de donde se retira el dinero *</label>
                    <select x-model="simulacion.banco" class="w-full py-2.5 px-3 bg-slate-950 border border-slate-700 rounded-xl text-slate-100 font-bold text-sm focus:border-emerald-500">
                        <option value="Banco Unión S.A.">🏦 Banco Unión S.A.</option>
                        <option value="Banco Nacional de Bolivia (BNB)">🏦 Banco Nacional de Bolivia (BNB)</option>
                        <option value="Banco Mercantil Santa Cruz">🏦 Banco Mercantil Santa Cruz</option>
                        <option value="Banco BISA S.A.">🏦 Banco BISA S.A.</option>
                        <option value="Banco FIE S.A.">🏦 Banco FIE S.A.</option>
                        <option value="Banco Económico S.A.">🏦 Banco Económico S.A.</option>
                    </select>
                </div>

                <!-- 2. Monto en Efectivo -->
                <div>
                    <label class="block text-xs font-black text-emerald-400 uppercase tracking-wider mb-1">2. Monto a Ingresar en Efectivo (Bs.) *</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 font-mono font-bold text-base">Bs.</span>
                        <input type="number" step="0.01" min="1" name="monto" x-model="simulacion.monto" required 
                               class="w-full pl-12 pr-4 py-3 bg-slate-950 border-2 border-emerald-500/80 rounded-xl text-emerald-400 font-mono font-black text-2xl focus:outline-none shadow-inner">
                    </div>
                </div>

                <!-- Montos Rápidos -->
                <div class="space-y-1">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Selección rápida de monto:</span>
                    <div class="grid grid-cols-4 gap-2">
                        <button type="button" @click="simulacion.monto = 1000" class="py-1.5 rounded-lg bg-slate-800 hover:bg-emerald-500/30 text-white text-xs font-mono font-bold border border-slate-700 transition">1.000</button>
                        <button type="button" @click="simulacion.monto = 5000" class="py-1.5 rounded-lg bg-slate-800 hover:bg-emerald-500/30 text-white text-xs font-mono font-bold border border-slate-700 transition">5.000</button>
                        <button type="button" @click="simulacion.monto = 10000" class="py-1.5 rounded-lg bg-slate-800 hover:bg-emerald-500/30 text-white text-xs font-mono font-bold border border-slate-700 transition">10.000</button>
                        <button type="button" @click="simulacion.monto = 25000" class="py-1.5 rounded-lg bg-slate-800 hover:bg-emerald-500/30 text-white text-xs font-mono font-bold border border-slate-700 transition">25.000</button>
                    </div>
                </div>

                <!-- 3. Comprobante de Banco / Cheque -->
                <div>
                    <label class="block text-xs font-black text-slate-200 uppercase tracking-wider mb-1">3. Nro. de Cheque o Comprobante (Opcional)</label>
                    <input type="text" x-model="simulacion.nro_comprobante" placeholder="Ej: CH-892401" class="w-full py-2 px-3 bg-slate-950 border border-slate-700 rounded-xl text-sky-300 font-mono font-bold text-xs">
                </div>

                <!-- 4. Caja Destino -->
                <div>
                    <label class="block text-xs font-black text-slate-200 uppercase tracking-wider mb-1">4. Depositar en Caja Chica Destino *</label>
                    <select x-model="simulacion.caja_id" required class="w-full py-2 px-3 bg-slate-950 border border-slate-700 rounded-xl text-slate-200 font-bold text-xs">
                        @foreach($cajas->where('tipo', 'caja_chica') as $c)
                            <option value="{{ $c->id }}">{{ $c->nombre }} (Caja Personal)</option>
                        @endforeach
                    </select>
                </div>

                <!-- Ticket resumen bancario comprimido -->
                <div class="p-3.5 rounded-xl bg-slate-950 border border-emerald-500/30 font-mono text-xs flex items-center justify-between">
                    <div>
                        <span class="text-[10px] text-slate-400 uppercase font-sans font-bold block">Resumen de Carga:</span>
                        <span class="text-white font-bold" x-text="simulacion.banco"></span>
                    </div>
                    <div class="text-right">
                        <span class="text-[10px] text-slate-400 uppercase font-sans font-bold block">Total Efectivo:</span>
                        <span class="text-lg font-black text-emerald-400" x-text="'Bs. ' + (parseFloat(simulacion.monto || 0).toLocaleString('es-BO', {minimumFractionDigits: 2}))"></span>
                    </div>
                </div>

                <input type="hidden" name="concepto" x-bind:value="'Retiro de Efectivo del ' + simulacion.banco + ' (Comp: ' + simulacion.nro_comprobante + ')'">
                <input type="hidden" name="origen" x-bind:value="simulacion.banco">

                <!-- Botones de Acción -->
                <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-slate-800">
                    <button type="button" @click="simuladorModalOpen = false" class="px-4 py-2.5 rounded-xl bg-slate-800 text-slate-300 hover:bg-slate-700 text-xs font-bold uppercase tracking-wider">
                        Cancelar
                    </button>
                    <button type="submit" class="btn-vibrant-success flex-1 py-2.5 rounded-xl text-xs font-black uppercase tracking-wider flex items-center justify-center gap-2 shadow-lg shadow-emerald-500/20">
                        <i class="fa-solid fa-check-circle text-base"></i> Confirmar y Cargar Efectivo
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL DE CREACIÓN DE NUEVA CAJA -->
    <div x-show="modalOpen" 
         x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="fixed inset-0 z-[99999] flex items-center justify-center p-4 bg-slate-950/85 backdrop-blur-md"
         style="display: none !important;">
        
        <div class="glass-card w-full max-w-md rounded-2xl overflow-hidden shadow-2xl border border-amber-500/30 bg-slate-900 my-auto relative z-[99999]" @click.away="modalOpen = false">
            <div class="p-6 border-b border-slate-800 flex items-center justify-between">
                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                    <i class="fa-solid fa-vault text-amber-500"></i> Creación de Nueva Caja / Fondo
                </h3>
                <button @click="modalOpen = false" type="button" class="text-slate-400 hover:text-white">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <form action="{{ route('cajas.store') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Nombre de la Caja / Fondo *</label>
                    <input type="text" name="nombre" required placeholder="Ej: Fondo Personal Mina San José" class="w-full py-2.5 text-sm">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Propósito / Destino *</label>
                    <select name="tipo" class="w-full py-2.5 text-sm">
                        <option value="caja_chica">Fondo de Personal (Anticipos y Sueldos)</option>
                        <option value="caja_general">Fondo Operativo Comercial (Compra/Venta Mineral y Volquetas)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Efectivo Inicial en Mano (Bs.) *</label>
                    <input type="number" step="0.01" min="0" name="saldo_inicial" value="0.00" required class="w-full py-2.5 text-sm font-mono text-amber-400 font-bold">
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-800">
                    <button type="button" @click="modalOpen = false" class="px-4 py-2.5 rounded-xl bg-slate-800 text-slate-300 hover:bg-slate-700 text-xs font-bold uppercase tracking-wider">
                        Cancelar
                    </button>
                    <button type="submit" class="btn-vibrant-amber px-5 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider">
                        Crear Fondo
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
