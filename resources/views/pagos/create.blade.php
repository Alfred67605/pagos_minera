@extends('layouts.app')

@section('title', 'Planillas de Pago y Liquidación de Personal')

@section('content')
<div x-data="pagoPageManager()" class="space-y-6">

    <!-- Header Banner & Primary Actions -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-slate-900/90 p-6 rounded-3xl border-2 border-rose-500/30 backdrop-blur-xl shadow-2xl">
        <div>
            <h1 class="text-2xl md:text-3xl font-black tracking-tight text-white flex items-center gap-3">
                <div class="p-2.5 rounded-2xl bg-rose-500/20 border border-rose-500/40 text-rose-400">
                    <i class="fa-solid fa-calculator text-2xl"></i>
                </div>
                Liquidación & Planillas de Pago
            </h1>
            <p class="text-xs md:text-sm text-slate-200 font-medium mt-1">
                Histórico completo de planillas semanales y registro de nuevos pagos a contratistas, choferes y serenos.
            </p>
        </div>

        <!-- Header Actions: Registrar Nuevo Pago & Otorgar Anticipo -->
        <div class="flex items-center gap-2 self-start md:self-auto flex-wrap">
            <button type="button" @click="modalPagoOpen = true"
                    class="bg-rose-500 hover:bg-rose-400 text-white font-black px-5 py-3 rounded-2xl text-xs uppercase tracking-wider transition-all duration-200 flex items-center gap-2 shadow-lg shadow-rose-500/30 hover:scale-105">
                <i class="fa-solid fa-plus-circle text-base"></i> Registrar Nuevo Pago
            </button>
            <a href="{{ route('anticipos.index') }}" 
               class="px-4 py-3 rounded-2xl text-xs font-black uppercase tracking-wider text-purple-300 hover:text-white bg-purple-900/40 hover:bg-purple-800/60 border border-purple-500/40 transition-all flex items-center gap-2 shadow-lg">
                <i class="fa-solid fa-hand-holding-dollar text-purple-400 text-base"></i> Otorgar Anticipo
            </a>
        </div>
    </div>

    <!-- MAIN PAGE: FULL WIDTH HISTORIAL DE PLANILLAS Y RECIBOS -->
    <div class="space-y-6">

        <!-- PERIOD & TYPE FILTER BAR -->
        <div class="glass-card rounded-2xl p-5 border border-slate-700 space-y-4 shadow-xl">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 border-b border-slate-800 pb-4">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-amber-500/10 border border-amber-500/30 text-amber-400 flex items-center justify-center font-bold">
                        <i class="fa-solid fa-filter text-base"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-extrabold text-white">Filtrar Historial de Planillas</h3>
                        <p class="text-xs text-slate-300">Filtra por periodo de tiempo, tipo de liquidación o busca por contratista.</p>
                    </div>
                </div>

                <!-- Live Search Bar -->
                <div class="relative w-full lg:w-72">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-3 text-slate-400 text-xs"></i>
                    <input type="text" x-model="searchHistory" placeholder="Buscar contratista o C.I..."
                           class="w-full py-2 pl-9 pr-3 bg-slate-950 border border-slate-700 rounded-xl text-xs text-white font-bold placeholder-slate-400 focus:border-cyan-500 focus:outline-none">
                </div>
            </div>

            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <!-- Period Filter Buttons (Hoy, Semana, Mes, Personalizado) -->
                <div class="flex flex-wrap items-center gap-1.5 bg-slate-950 p-1.5 rounded-xl border border-slate-800">
                    <span class="text-[10px] font-black uppercase text-slate-400 px-2">Periodo:</span>
                    <button type="button" @click="filtroPeriodo = 'todos'"
                            :class="filtroPeriodo === 'todos' ? 'bg-cyan-500 text-slate-950 font-black' : 'text-slate-300 hover:text-white font-bold'"
                            class="px-3 py-1.5 rounded-lg text-xs transition">
                        🌐 Todos
                    </button>
                    <button type="button" @click="filtroPeriodo = 'hoy'"
                            :class="filtroPeriodo === 'hoy' ? 'bg-emerald-500 text-slate-950 font-black' : 'text-slate-300 hover:text-white font-bold'"
                            class="px-3 py-1.5 rounded-lg text-xs transition">
                        ⚡ Hoy
                    </button>
                    <button type="button" @click="filtroPeriodo = 'semana'"
                            :class="filtroPeriodo === 'semana' ? 'bg-indigo-500 text-white font-black' : 'text-slate-300 hover:text-white font-bold'"
                            class="px-3 py-1.5 rounded-lg text-xs transition">
                        🗓️ Esta Semana
                    </button>
                    <button type="button" @click="filtroPeriodo = 'mes'"
                            :class="filtroPeriodo === 'mes' ? 'bg-purple-500 text-white font-black' : 'text-slate-300 hover:text-white font-bold'"
                            class="px-3 py-1.5 rounded-lg text-xs transition">
                        📆 Este Mes
                    </button>
                    <button type="button" @click="filtroPeriodo = 'custom'"
                            :class="filtroPeriodo === 'custom' ? 'bg-amber-500 text-slate-950 font-black' : 'text-slate-300 hover:text-white font-bold'"
                            class="px-3 py-1.5 rounded-lg text-xs transition">
                        🛠️ Personalizado
                    </button>
                </div>

                <!-- Type Filter Buttons (Todos, Pago Completo, Anticipo/Parcial) -->
                <div class="flex flex-wrap items-center gap-1.5 bg-slate-950 p-1.5 rounded-xl border border-slate-800">
                    <span class="text-[10px] font-black uppercase text-slate-400 px-2">Tipo:</span>
                    <button type="button" @click="filtroTipoPago = 'todos'"
                            :class="filtroTipoPago === 'todos' ? 'bg-cyan-500 text-slate-950 font-black' : 'text-slate-300 hover:text-white font-bold'"
                            class="px-3 py-1.5 rounded-lg text-xs transition">
                        Todos
                    </button>
                    <button type="button" @click="filtroTipoPago = 'completo'"
                            :class="filtroTipoPago === 'completo' ? 'bg-emerald-500 text-slate-950 font-black' : 'text-slate-300 hover:text-white font-bold'"
                            class="px-3 py-1.5 rounded-lg text-xs transition">
                        💵 Pago Completo
                    </button>
                    <button type="button" @click="filtroTipoPago = 'anticipo'"
                            :class="filtroTipoPago === 'anticipo' ? 'bg-amber-500 text-slate-950 font-black' : 'text-slate-300 hover:text-white font-bold'"
                            class="px-3 py-1.5 rounded-lg text-xs transition">
                        💸 Anticipos / Parcial
                    </button>
                </div>
            </div>

            <!-- Custom Date Inputs (Only when filtroPeriodo === 'custom') -->
            <div x-show="filtroPeriodo === 'custom'" class="p-3 bg-slate-950 rounded-xl border border-amber-500/30 flex flex-wrap items-center gap-3" x-cloak>
                <span class="text-xs font-bold text-amber-400 uppercase"><i class="fa-solid fa-calendar-range mr-1"></i> Rango Personalizado:</span>
                <div class="flex items-center gap-2">
                    <label class="text-xs text-slate-300 font-bold">Desde:</label>
                    <input type="date" x-model="fechaInicio" class="py-1 px-2.5 bg-slate-900 border border-slate-700 rounded-lg text-xs text-white">
                </div>
                <div class="flex items-center gap-2">
                    <label class="text-xs text-slate-300 font-bold">Hasta:</label>
                    <input type="date" x-model="fechaFin" class="py-1 px-2.5 bg-slate-900 border border-slate-700 rounded-xl text-xs text-white">
                </div>
            </div>
        </div>

        <!-- FULL WIDTH TABLE OF HISTORIAL DE PAGOS -->
        <div class="glass-card rounded-2xl overflow-hidden border border-slate-700 shadow-2xl">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-800">
                    <thead>
                        <tr class="text-left text-xs font-black text-slate-200 uppercase tracking-wider bg-slate-950">
                            <th class="px-6 py-4">ID</th>
                            <th class="px-6 py-4">Fecha</th>
                            <th class="px-6 py-4">Trabajador / Contratista</th>
                            <th class="px-6 py-4">Bocamina</th>
                            <th class="px-6 py-4">Ganado (Subtotal)</th>
                            <th class="px-6 py-4">Bonos (+)</th>
                            <th class="px-6 py-4">Anticipos (-)</th>
                            <th class="px-6 py-4">Efectivo Entregado</th>
                            <th class="px-6 py-4">Estado</th>
                            <th class="px-6 py-4 no-print text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60 text-sm text-slate-200">
                        @forelse($pagos as $pago)
                            @php
                                $fechaStr = $pago->fecha->format('Y-m-d');
                            @endphp
                            <tr x-show="matchesFilter('{{ $fechaStr }}', '{{ strtolower($pago->trabajador->nombre ?? '') }}', '{{ strtolower($pago->trabajador->ci ?? '') }}', '{{ strtolower($pago->trabajador->bocamina->nombre ?? '') }}', {{ $pago->saldo_pendiente > 0 ? 'true' : 'false' }})"
                                class="hover:bg-slate-900/60 transition duration-150">
                                <td class="px-6 py-4 font-mono text-xs font-bold text-slate-300">#{{ $pago->id }}</td>
                                <td class="px-6 py-4 font-mono text-xs text-white font-bold">{{ $pago->fecha->format('d/m/Y') }}</td>
                                <td class="px-6 py-4">
                                    <span class="font-black text-white block">{{ $pago->trabajador->nombre }}</span>
                                    <span class="text-[11px] text-slate-300 font-mono">C.I.: {{ $pago->trabajador->ci }}</span>
                                </td>
                                <td class="px-6 py-4 text-xs font-extrabold text-amber-400">
                                    {{ $pago->trabajador->bocamina->nombre ?? 'Sin asignar' }}
                                </td>
                                <td class="px-6 py-4 font-mono text-xs text-slate-100 font-bold">Bs. {{ number_format($pago->subtotal, 2) }}</td>
                                <td class="px-6 py-4 font-mono text-xs text-emerald-400 font-bold">+Bs. {{ number_format($pago->bonos, 2) }}</td>
                                <td class="px-6 py-4 font-mono text-xs text-rose-400 font-bold">-Bs. {{ number_format($pago->anticipos_descontados, 2) }}</td>
                                <td class="px-6 py-4 font-mono font-black text-emerald-400 text-base">
                                    Bs. {{ number_format($pago->monto_pagado, 2) }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($pago->saldo_pendiente > 0)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-black bg-amber-500/20 text-amber-400 border border-amber-500/40">
                                            ⚠️ Saldo Pendiente: Bs. {{ number_format($pago->saldo_pendiente, 2) }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-black bg-emerald-500/20 text-emerald-400 border border-emerald-500/40">
                                            ✓ Pagado Completo
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 no-print text-center">
                                    <a href="{{ route('pagos.show', $pago->id) }}" 
                                       class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-cyan-500/20 hover:bg-cyan-500/30 text-cyan-300 hover:text-white border border-cyan-500/40 text-xs font-black transition shadow">
                                        <i class="fa-solid fa-print"></i> Imprimir Recibo
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="px-6 py-12 text-center text-slate-400">
                                    <i class="fa-solid fa-receipt text-4xl mb-3 block text-slate-500"></i>
                                    No se encontraron planillas de pago registradas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- MODAL / SLIDE-OVER DRAWER: REGISTRAR NUEVO PAGO -->
    <div x-show="modalPagoOpen" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
        <!-- Backdrop dark overlay -->
        <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-md transition-opacity" @click="modalPagoOpen = false"></div>

        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="relative glass-card rounded-3xl p-6 border-2 border-rose-500/40 shadow-2xl max-w-5xl w-full space-y-6 z-10 max-h-[90vh] overflow-y-auto">
                
                <!-- Modal Header -->
                <div class="flex items-center justify-between border-b border-slate-800 pb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-rose-500/20 border border-rose-500/40 text-rose-400 flex items-center justify-center font-bold text-lg">
                            <i class="fa-solid fa-calculator"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-black text-white">Registrar Nueva Liquidación de Pago</h2>
                            <p class="text-xs text-slate-200 font-medium">Calculador semanal de sueldos por sacos, toneladas, metros o tarifa.</p>
                        </div>
                    </div>
                    <button type="button" @click="modalPagoOpen = false" class="w-9 h-9 rounded-xl bg-slate-900 hover:bg-slate-800 text-slate-400 hover:text-white border border-slate-700 flex items-center justify-center text-sm transition">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <!-- Form Wizard Content inside Modal -->
                <div x-data="paymentWizard()">
                    <!-- SELECCIÓN RÁPIDA POR GRUPOS Y ROLES -->
                    <div class="glass-card rounded-2xl p-4 border border-slate-800 space-y-3 mb-6">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-800/80 pb-3">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-xl bg-cyan-500/10 border border-cyan-500/30 text-cyan-400 flex items-center justify-center font-bold">
                                    <i class="fa-solid fa-users-gear text-sm"></i>
                                </div>
                                <div>
                                    <h3 class="text-sm font-extrabold text-white uppercase tracking-wider">Filtrar Grupo de Personal a Pagar</h3>
                                    <p class="text-xs text-slate-300 font-medium">Selecciona el grupo para filtrar la lista automáticamente:</p>
                                </div>
                            </div>
                        </div>

                        <!-- Botonera de Grupos por Rol -->
                        <div class="flex flex-wrap items-center gap-2">
                            <button type="button" @click="grupoSeleccionado = ''; trabajadorId = ''; clear()"
                                    :class="grupoSeleccionado === '' ? 'bg-cyan-500 text-slate-950 font-black shadow-lg shadow-cyan-500/20' : 'bg-slate-900 text-slate-100 hover:text-white border border-slate-700 hover:bg-slate-800'"
                                    class="px-4 py-2 rounded-xl text-xs uppercase tracking-wider transition-all duration-200 flex items-center gap-2">
                                <i class="fa-solid fa-layer-group text-cyan-400"></i> Todos ({{ count($trabajadores) }})
                            </button>
                            <button type="button" @click="grupoSeleccionado = 'trabajador_bocamina'; trabajadorId = ''; clear()"
                                    :class="grupoSeleccionado === 'trabajador_bocamina' ? 'bg-rose-500 text-white font-black shadow-lg shadow-rose-500/20' : 'bg-slate-900 text-slate-100 hover:text-white border border-slate-700 hover:bg-slate-800'"
                                    class="px-4 py-2 rounded-xl text-xs uppercase tracking-wider transition-all duration-200 flex items-center gap-2">
                                <i class="fa-solid fa-hammer text-rose-400"></i> 👷 Contratistas Bocamina
                            </button>
                            <button type="button" @click="grupoSeleccionado = 'chofer'; trabajadorId = ''; clear()"
                                    :class="grupoSeleccionado === 'chofer' ? 'bg-emerald-500 text-slate-950 font-black shadow-lg shadow-emerald-500/20' : 'bg-slate-900 text-slate-100 hover:text-white border border-slate-700 hover:bg-slate-800'"
                                    class="px-4 py-2 rounded-xl text-xs uppercase tracking-wider transition-all duration-200 flex items-center gap-2">
                                <i class="fa-solid fa-truck-front text-emerald-400"></i> 🚛 Choferes (Viajes)
                            </button>
                            <button type="button" @click="grupoSeleccionado = 'sereno'; trabajadorId = ''; clear()"
                                    :class="grupoSeleccionado === 'sereno' ? 'bg-amber-500 text-slate-950 font-black shadow-lg shadow-amber-500/20' : 'bg-slate-900 text-slate-100 hover:text-white border border-slate-700 hover:bg-slate-800'"
                                    class="px-4 py-2 rounded-xl text-xs uppercase tracking-wider transition-all duration-200 flex items-center gap-2">
                                <i class="fa-solid fa-shield-halved text-amber-400"></i> 🛡️ Serenos de Mina
                            </button>
                            <button type="button" @click="grupoSeleccionado = 'personal_admin'; trabajadorId = ''; clear()"
                                    :class="grupoSeleccionado === 'personal_admin' ? 'bg-indigo-500 text-white font-black shadow-lg shadow-indigo-500/20' : 'bg-slate-900 text-slate-100 hover:text-white border border-slate-700 hover:bg-slate-800'"
                                    class="px-4 py-2 rounded-xl text-xs uppercase tracking-wider transition-all duration-200 flex items-center gap-2">
                                <i class="fa-solid fa-user-gear text-indigo-400"></i> 💼 Admin / Fijos
                            </button>
                        </div>
                    </div>

                    <!-- Main Payment Form Grid -->
                    <form action="{{ route('pagos.store') }}" method="POST" class="grid grid-cols-1 gap-6 lg:grid-cols-12">
                        @csrf

                        <!-- LEFT COLUMN: PASO 1 Y PASO 2 (COL-SPAN 7) -->
                        <div class="lg:col-span-7 space-y-6">
                            
                            <!-- PASO 1: SELECCIONAR TRABAJADOR -->
                            <div class="glass-card rounded-2xl p-6 space-y-4 border border-slate-800">
                                <div class="flex items-center gap-3 border-b border-slate-800 pb-3">
                                    <span class="w-8 h-8 rounded-full bg-amber-500/20 text-amber-300 font-extrabold flex items-center justify-center text-sm border border-amber-500/40">1</span>
                                    <div>
                                        <h3 class="text-base font-extrabold text-white">¿A quién le vas a pagar esta semana?</h3>
                                        <p class="text-xs text-slate-200 font-medium">Selecciona el contratista o trabajador de bocamina.</p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                    <div>
                                        <label class="block text-xs font-bold uppercase tracking-wider text-cyan-400 mb-1">
                                            <i class="fa-solid fa-magnifying-glass mr-1"></i> Buscar Nombre o C.I.
                                        </label>
                                        <input type="text" x-model="searchQuery" placeholder="Escribe nombre o C.I..."
                                               class="w-full py-2.5 px-3 bg-slate-900 border border-cyan-500/40 rounded-xl text-cyan-300 font-bold text-sm focus:outline-none focus:ring-1 focus:ring-cyan-500 placeholder-slate-500">
                                    </div>

                                    <div>
                                        <label for="bocamina_filtro_id" class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1">Filtrar por Bocamina</label>
                                        <select id="bocamina_filtro_id" x-model="bocaminaFiltroId" @change="trabajadorId = ''; clear()"
                                                class="w-full py-2.5 px-3 bg-slate-900 border border-slate-700 rounded-xl text-slate-100 text-sm font-semibold">
                                            <option value="">-- Todas las Bocaminas --</option>
                                            @foreach($bocaminas as $bocamina)
                                                <option value="{{ $bocamina->id }}">{{ $bocamina->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <label for="trabajador_id" class="block text-xs font-bold uppercase tracking-wider text-amber-400 mb-1">Seleccionar Contratista *</label>
                                        <select id="trabajador_id" name="trabajador_id" required x-model="trabajadorId" @change="onTrabajadorChange()"
                                                class="w-full py-2.5 px-3 bg-slate-900 border border-amber-500/50 rounded-xl text-amber-400 font-bold text-sm">
                                            <option value="">-- Selecciona el Contratista --</option>
                                            <template x-for="t in filteredTrabajadores" :key="t.id">
                                                <option :value="t.id" x-text="t.nombre + ' (CI: ' + t.ci + ') - ' + (t.bocamina ? t.bocamina.nombre : '')"></option>
                                            </template>
                                        </select>
                                    </div>
                                </div>

                                <!-- Real-Time Interactive Worker Badges Grid -->
                                <div x-show="filteredTrabajadores.length > 0 && !trabajadorId" class="pt-2 space-y-2" x-cloak>
                                    <div class="flex items-center justify-between text-xs font-black uppercase tracking-widest text-cyan-300">
                                        <span><i class="fa-solid fa-users mr-1"></i> Trabajadores encontrados en tiempo real:</span>
                                        <span class="font-mono text-white bg-cyan-950 px-2 py-0.5 rounded border border-cyan-800" x-text="filteredTrabajadores.length + ' personas'"></span>
                                    </div>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 max-h-60 overflow-y-auto p-2 bg-slate-950 rounded-xl border border-slate-700">
                                        <template x-for="t in filteredTrabajadores" :key="t.id">
                                            <button type="button" @click="trabajadorId = t.id; onTrabajadorChange()"
                                                    class="p-3 rounded-xl bg-slate-900 hover:bg-slate-800 border border-slate-700 hover:border-cyan-400 text-left transition-all duration-150 flex items-center justify-between group shadow-md">
                                                <div>
                                                    <span class="text-xs font-black text-white group-hover:text-cyan-300 block tracking-wide" x-text="t.nombre"></span>
                                                    <span class="text-[11px] text-slate-200 font-mono font-semibold block mt-0.5" x-text="'C.I.: ' + t.ci + ' | ' + (t.bocamina ? t.bocamina.nombre : 'Sin mina')"></span>
                                                </div>
                                                <span class="text-xs font-black text-cyan-400 bg-cyan-500/10 px-2.5 py-1 rounded-lg border border-cyan-500/30 group-hover:bg-cyan-500 group-hover:text-slate-950 transition-all">
                                                    Pagar <i class="fa-solid fa-chevron-right text-[10px]"></i>
                                                </span>
                                            </button>
                                        </template>
                                    </div>
                                </div>

                                <!-- Contratista Selected Banner & Details -->
                                <div x-show="trabajador" class="p-4 rounded-xl bg-slate-900/90 border border-slate-800 space-y-3" x-cloak>
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-full bg-amber-500/10 text-amber-400 border border-amber-500/30 flex items-center justify-center font-bold text-base">
                                                <i class="fa-solid fa-user"></i>
                                            </div>
                                            <div>
                                                <h4 class="text-base font-extrabold text-white" x-text="trabajador ? trabajador.nombre : ''"></h4>
                                                <p class="text-xs text-slate-300 font-mono">
                                                    C.I.: <span class="text-white font-bold" x-text="trabajador ? trabajador.ci : ''"></span> | 
                                                    Cargo: <span class="text-rose-300 font-semibold" x-text="trabajador ? (trabajador.cargo || 'Trabajador') : ''"></span>
                                                </p>
                                            </div>
                                        </div>
                                        <span class="px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-emerald-500/10 text-emerald-400 border border-emerald-500/30">
                                            Personal Activo
                                        </span>
                                    </div>

                                    <!-- Informative badges of Bocamina & Contract Type -->
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2 border-t border-slate-800 text-xs">
                                        <div class="bg-slate-950 p-3 rounded-xl border border-slate-800 flex items-center justify-between">
                                            <span class="text-slate-400 font-bold uppercase text-[10px]">🏔️ Bocamina Asignada:</span>
                                            <span class="text-amber-400 font-black text-xs" x-text="trabajador && trabajador.bocamina ? trabajador.bocamina.nombre : 'Sin asignar'"></span>
                                        </div>
                                        <div class="bg-slate-950 p-3 rounded-xl border border-slate-800 flex items-center justify-between">
                                            <span class="text-slate-400 font-bold uppercase text-[10px]">📝 Tipo de Contrato:</span>
                                            <span class="text-cyan-400 font-black text-xs" x-text="getModalidadLabel(trabajador ? trabajador.modalidad_pago : '')"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- PASO 2: GANADO ESTA SEMANA Y TRABAJOS ACUMULADOS -->
                            <div class="glass-card rounded-2xl p-6 space-y-4 border border-slate-800" x-show="trabajadorId" x-cloak>
                                <div class="flex items-center gap-3 border-b border-slate-800 pb-3">
                                    <span class="w-8 h-8 rounded-full bg-amber-500/20 text-amber-400 font-extrabold flex items-center justify-center text-sm border border-amber-500/30">2</span>
                                    <div>
                                        <h3 class="text-base font-bold text-white">Trabajo de la Semana (Monto Ganado)</h3>
                                        <p class="text-xs text-slate-400">Total ganado por sacos, volquetas, metros o tarifa semanal.</p>
                                    </div>
                                </div>

                                <!-- Trabajos acumulados list -->
                                <template x-if="trabajos.length > 0">
                                    <div class="space-y-3">
                                        <span class="text-xs font-bold text-slate-300 uppercase tracking-wider block">Avances Diarios Registrados esta Semana:</span>
                                        <div class="overflow-x-auto rounded-xl border border-slate-800 bg-slate-900/60">
                                            <table class="w-full text-left text-xs text-slate-300 font-mono">
                                                <thead>
                                                    <tr class="bg-slate-950 text-slate-400 uppercase text-[10px]">
                                                        <th class="p-3">Fecha</th>
                                                        <th class="p-3">Detalle / Modalidad</th>
                                                        <th class="p-3 text-right">Cantidad</th>
                                                        <th class="p-3 text-right">Precio</th>
                                                        <th class="p-3 text-right">Subtotal</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-slate-800">
                                                    <template x-for="job in trabajos" :key="job.id">
                                                        <tr>
                                                            <td class="p-3 text-slate-400" x-text="job.fecha"></td>
                                                            <td class="p-3 text-white font-sans" x-text="job.observacion || 'Avance de trabajo'"></td>
                                                            <td class="p-3 text-right" x-text="parseFloat(job.cantidad).toFixed(2)"></td>
                                                            <td class="p-3 text-right" x-text="'Bs. ' + parseFloat(job.precio_unitario).toFixed(2)"></td>
                                                            <td class="p-3 text-right font-bold text-amber-400" x-text="'Bs. ' + parseFloat(job.subtotal).toFixed(2)"></td>
                                                        </tr>
                                                    </template>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </template>

                                <div>
                                    <label for="subtotal" class="block text-xs font-bold uppercase tracking-wider text-amber-400 mb-1">
                                        Monto Ganado esta Semana (Bs.) *
                                    </label>
                                    <input id="subtotal" name="subtotal" type="number" step="0.01" min="0" required x-model="subtotal" @input="recalculate()"
                                           class="w-full py-3 px-4 bg-slate-900 border border-amber-500/50 rounded-xl text-amber-400 text-xl font-mono font-extrabold focus:outline-none focus:ring-1 focus:ring-amber-500">
                                    <p class="text-[11px] text-slate-400 mt-1">Puedes modificar manualmente si deseas ajustar el monto ganado esta semana.</p>
                                </div>

                                <!-- Saldos pendientes de semanas pasadas -->
                                <div x-show="saldosPendientes.length > 0" class="p-4 rounded-xl bg-amber-500/10 border border-amber-500/30 space-y-2" x-cloak>
                                    <div class="flex items-center gap-2 text-xs font-bold text-amber-400 uppercase">
                                        <i class="fa-solid fa-clock-rotate-left"></i> Saldo Pendiente de Semanas Anteriores
                                    </div>
                                    <p class="text-xs text-slate-300">
                                        Se le debe a este contratista un saldo pendiente de <strong>Bs. <span x-text="totalSaldosPendientes.toFixed(2)"></span></strong> de semanas previas que se agregará a esta liquidación.
                                    </p>
                                </div>

                                <!-- Bonos y Descuentos extras -->
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2 border-t border-slate-800">
                                    <div>
                                        <label for="bonos" class="block text-xs font-bold text-slate-300 uppercase mb-1">Bonos Extras (+ Bs.)</label>
                                        <input id="bonos" name="bonos" type="number" step="0.01" min="0" required x-model="bonos" @input="recalculate()"
                                               class="w-full py-2 px-3 bg-slate-900 border border-slate-700 rounded-xl text-emerald-400 text-sm font-mono font-bold">
                                    </div>
                                    <div>
                                        <label for="descuentos" class="block text-xs font-bold text-slate-300 uppercase mb-1">Descuentos Varios (- Bs.)</label>
                                        <input id="descuentos" name="descuentos" type="number" step="0.01" min="0" required x-model="descuentos" @input="recalculate()"
                                               class="w-full py-2 px-3 bg-slate-900 border border-slate-700 rounded-xl text-rose-400 text-sm font-mono font-bold">
                                    </div>
                                </div>
                            </div>

                            <!-- PASO 3: DESCONTAR ANTICIPOS -->
                            <div class="glass-card rounded-2xl p-6 space-y-4 border border-slate-800" x-show="trabajadorId" x-cloak>
                                <div class="flex items-center gap-3 border-b border-slate-800 pb-3">
                                    <span class="w-8 h-8 rounded-full bg-amber-500/20 text-amber-400 font-extrabold flex items-center justify-center text-sm border border-amber-500/30">3</span>
                                    <div>
                                        <h3 class="text-base font-bold text-white">Descontar Anticipos Entregados</h3>
                                        <p class="text-xs text-slate-400">Marca los adelantos de dinero en efectivo que le diste durante la semana.</p>
                                    </div>
                                </div>

                                <template x-if="anticipos.length === 0">
                                    <div class="p-4 rounded-xl bg-slate-900/60 border border-slate-800 text-xs text-slate-400 flex items-center gap-2">
                                        <i class="fa-solid fa-circle-check text-emerald-400 text-base"></i>
                                        Este contratista no tiene ningún anticipo pendiente de cobro esta semana.
                                    </div>
                                </template>

                                <template x-if="anticipos.length > 0">
                                    <div class="space-y-3">
                                        <template x-for="ant in anticipos" :key="ant.id">
                                            <div class="p-3.5 rounded-xl border transition flex items-center justify-between gap-3"
                                                 :class="ant.aplicado ? 'bg-rose-950/20 border-rose-500/40' : 'bg-slate-900/60 border-slate-800'">
                                                <div class="flex items-center gap-3">
                                                    <input type="checkbox" :id="'ant-' + ant.id" x-model="ant.aplicado" @change="recalculate()"
                                                           class="w-5 h-5 rounded border-slate-700 text-amber-500 focus:ring-amber-500 bg-slate-950">
                                                    <label :for="'ant-' + ant.id" class="cursor-pointer">
                                                        <span class="text-xs font-bold text-white block" x-text="'Anticipo en efectivo del ' + ant.fecha"></span>
                                                        <span class="text-[11px] text-slate-400 font-mono block mt-0.5" x-text="'Saldo adelantado: Bs. ' + parseFloat(ant.saldo).toFixed(2)"></span>
                                                    </label>
                                                </div>

                                                <div x-show="ant.aplicado" class="flex items-center gap-2">
                                                    <span class="text-[10px] text-rose-400 uppercase font-mono font-bold">Descontar:</span>
                                                    <input type="number" step="0.01" min="0" :max="ant.saldo"
                                                           :name="'deducciones_anticipos['+ant.id+']'"
                                                           x-model="ant.liveDeduccion"
                                                           @input="recalculate()"
                                                           class="w-28 py-1 px-2.5 bg-slate-950 border border-rose-500/50 rounded-lg text-right text-xs font-mono font-extrabold text-rose-400">
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- RIGHT COLUMN: PASO 4 RESUMEN DE PAGO (COL-SPAN 5) -->
                        <div class="lg:col-span-5 space-y-6">
                            
                            <!-- Empty state when no worker selected -->
                            <div x-show="!trabajadorId" class="glass-card rounded-2xl p-8 text-center space-y-4 border border-slate-700">
                                <div class="w-16 h-16 rounded-2xl bg-cyan-500/10 border border-cyan-500/30 flex items-center justify-center mx-auto text-cyan-400 text-2xl shadow-lg">
                                    <i class="fa-solid fa-calculator"></i>
                                </div>
                                <h4 class="text-base font-extrabold text-white">Selecciona un Contratista</h4>
                                <p class="text-xs text-slate-200 font-medium max-w-xs mx-auto">Selecciona o haz clic en cualquier minero de la izquierda para desplegar la liquidación automática.</p>
                            </div>

                            <!-- PASO 4: LIQUIDACIÓN FINAL EN VIVO -->
                            <div x-show="trabajadorId" class="glass-card rounded-2xl p-6 space-y-5 border border-amber-500/40 gold-glow sticky top-6" x-cloak>
                                <div class="flex items-center gap-3 border-b border-slate-800 pb-3">
                                    <span class="w-8 h-8 rounded-full bg-emerald-500/20 text-emerald-400 font-extrabold flex items-center justify-center text-sm border border-emerald-500/30">4</span>
                                    <div>
                                        <h3 class="text-base font-bold text-white">Sobre de Pago Semanal</h3>
                                        <p class="text-xs text-slate-400">Resumen claro del efectivo a entregar en la mano.</p>
                                    </div>
                                </div>

                                <!-- Breakdown Numbers -->
                                <div class="space-y-2.5 font-mono text-xs border-b border-slate-800 pb-4">
                                    <div class="flex justify-between items-center">
                                        <span class="text-slate-400 font-sans">Monto Ganado esta Semana:</span>
                                        <span class="text-white font-bold text-sm" x-text="'Bs. ' + subtotal.toFixed(2)"></span>
                                    </div>

                                    <div class="flex justify-between items-center" x-show="totalSaldosPendientes > 0" x-cloak>
                                        <span class="text-slate-400 font-sans">Deuda Semanas Anteriores (+):</span>
                                        <span class="text-emerald-400 font-bold" x-text="'+ Bs. ' + totalSaldosPendientes.toFixed(2)"></span>
                                    </div>

                                    <div class="flex justify-between items-center" x-show="parseFloat(bonos) > 0">
                                        <span class="text-slate-400 font-sans">Bonos Extra (+):</span>
                                        <span class="text-emerald-400 font-bold" x-text="'+ Bs. ' + (parseFloat(bonos) || 0).toFixed(2)"></span>
                                    </div>

                                    <div class="flex justify-between items-center text-rose-400" x-show="anticiposDescontados > 0">
                                        <span class="text-slate-400 font-sans">(-) Anticipos Descontados:</span>
                                        <span class="font-bold text-sm" x-text="'- Bs. ' + anticiposDescontados.toFixed(2)"></span>
                                    </div>

                                    <div class="flex justify-between items-center text-rose-400" x-show="parseFloat(descuentos) > 0">
                                        <span class="text-slate-400 font-sans">(-) Descuentos Varios:</span>
                                        <span class="font-bold" x-text="'- Bs. ' + (parseFloat(descuentos) || 0).toFixed(2)"></span>
                                    </div>
                                </div>

                                <!-- TOTAL NETO A ENTREGAR EN EFECTIVO -->
                                <div class="p-4 rounded-xl bg-emerald-950/40 border border-emerald-500/40 space-y-1">
                                    <span class="text-[11px] font-extrabold uppercase tracking-wider text-emerald-400 font-sans block">
                                        💰 Efectivo Neto Líquido a Pagar:
                                    </span>
                                    <div class="text-3xl font-extrabold font-mono text-emerald-400" x-text="'Bs. ' + neto.toFixed(2)"></div>
                                    <p class="text-[11px] text-slate-400 font-sans">Este es el monto total que le corresponde recibir libre de anticipos.</p>
                                </div>

                                <!-- OPCIÓN DE PAGO PARCIAL O COMPLETO -->
                                <div class="space-y-3 pt-2">
                                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300">Forma de Cancelación esta Semana</label>
                                    
                                    <div class="grid grid-cols-2 gap-2">
                                        <button type="button" @click="tipoPagoPlanilla = 'completo'; userEditedMontoPagado = false; recalculate()"
                                                :class="tipoPagoPlanilla === 'completo' ? 'bg-gradient-to-r from-emerald-500 to-teal-600 text-slate-950 font-extrabold shadow-lg shadow-emerald-500/20' : 'bg-slate-900 text-slate-400 border border-slate-800'"
                                                class="py-2.5 px-3 rounded-xl text-xs font-bold text-center transition">
                                            <i class="fa-solid fa-circle-check mr-1"></i> Pagar Completo
                                        </button>
                                        <button type="button" @click="tipoPagoPlanilla = 'adelanto'; userEditedMontoPagado = true; montoPagado = (neto * 0.5).toFixed(2); recalculate()"
                                                :class="tipoPagoPlanilla === 'adelanto' ? 'bg-gradient-to-r from-amber-500 to-orange-600 text-slate-950 font-extrabold shadow-lg shadow-amber-500/20' : 'bg-slate-900 text-slate-400 border border-slate-800'"
                                                class="py-2.5 px-3 rounded-xl text-xs font-bold text-center transition">
                                            <i class="fa-solid fa-hourglass-half mr-1"></i> Pago Parcial
                                        </button>
                                    </div>

                                    <!-- Input Monto Real Entregado en Mano -->
                                    <div>
                                        <label for="monto_pagado" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">
                                            <span x-show="tipoPagoPlanilla === 'completo'">Efectivo Entregado (Total Completo)</span>
                                            <span x-show="tipoPagoPlanilla === 'adelanto'">Efectivo Entregado Hoy en Mano (Parcial)</span>
                                        </label>
                                        <input id="monto_pagado" name="monto_pagado" type="number" step="0.01" min="0" required 
                                               x-model="montoPagado" 
                                               :disabled="tipoPagoPlanilla === 'completo'"
                                               @input="userEditedMontoPagado = true; recalculate()"
                                               :class="tipoPagoPlanilla === 'completo' ? 'bg-slate-900/80 text-slate-400 border-slate-800 cursor-not-allowed' : 'bg-slate-900 border-amber-500 text-amber-400'"
                                               class="w-full py-2.5 px-3 rounded-xl focus:outline-none text-base font-mono font-extrabold">

                                        <!-- Alertas visuales sobre el saldo resultante -->
                                        <div class="pt-2">
                                            <div x-show="tipoPagoPlanilla === 'adelanto' && parseFloat(montoPagado) < parseFloat(neto)" class="p-2.5 rounded-lg bg-amber-500/10 border border-amber-500/30 text-xs font-bold text-amber-400" x-cloak>
                                                <i class="fa-solid fa-triangle-exclamation mr-1"></i> Quedará debiéndose <strong>Bs. <span x-text="(neto - parseFloat(montoPagado)).toFixed(2)"></span></strong> para la siguiente semana.
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- CAJA DE DONDE SALE EL DINERO -->
                                <div class="space-y-3 pt-2 border-t border-slate-800">
                                    <div>
                                        <label for="caja_id" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Caja / Fondo de Personal de donde se saca el efectivo *</label>
                                        <select id="caja_id" name="caja_id" required
                                                class="w-full py-2.5 px-3 bg-slate-900 border border-slate-700 rounded-xl text-slate-100 font-bold text-xs text-amber-400">
                                            @foreach($cajas as $caja)
                                                <option value="{{ $caja->id }}" {{ $caja->tipo === 'caja_chica' ? 'selected' : '' }}>
                                                    {{ $caja->nombre }} (Efectivo disponible: Bs. {{ number_format($caja->saldo_actual, 2) }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <label for="fecha" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Fecha de Pago</label>
                                        <input id="fecha" name="fecha" type="date" required x-model="fecha"
                                               class="w-full py-2 px-3 bg-slate-900 border border-slate-700 rounded-xl text-slate-100 font-mono text-xs">
                                    </div>

                                    <div>
                                        <label for="observacion" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Observación / Nota (Opcional)</label>
                                        <textarea id="observacion" name="observacion" rows="2" x-model="observacion"
                                                  class="w-full py-2 px-3 bg-slate-900 border border-slate-700 rounded-xl text-slate-100 text-xs"
                                                  placeholder="Ej. Pago correspondiente a la Semana 31"></textarea>
                                    </div>

                                    <input type="hidden" name="metodo_pago" value="efectivo">
                                    <input type="hidden" name="tipo_cambio" value="6.96">
                                    <input type="hidden" name="entregado_por" value="{{ Auth::user()->name ?? 'Administración' }}">
                                </div>

                                <!-- SUBMIT BUTTON -->
                                <div class="pt-3">
                                    <button type="submit" :disabled="!trabajadorId"
                                            :class="(!trabajadorId) ? 'opacity-50 cursor-not-allowed' : ''"
                                            class="w-full py-3.5 px-4 rounded-xl text-sm font-extrabold text-slate-950 bg-gradient-to-r from-emerald-400 to-teal-500 hover:from-emerald-500 hover:to-teal-600 transition shadow-lg shadow-emerald-500/20 uppercase tracking-wider flex items-center justify-center gap-2">
                                        <i class="fa-solid fa-check-circle text-base"></i> Confirmar Pago y Generar Recibo
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    function pagoPageManager() {
        return {
            modalPagoOpen: {{ request('cargo') ? 'true' : 'false' }},
            filtroPeriodo: 'todos',
            filtroTipoPago: 'todos',
            fechaInicio: '',
            fechaFin: '',
            searchHistory: '',

            matchesFilter(dateStr, nombreStr, ciStr, bocaminaStr, tieneSaldoPendiente) {
                // Type filter
                if (this.filtroTipoPago === 'completo' && tieneSaldoPendiente) return false;
                if (this.filtroTipoPago === 'anticipo' && !tieneSaldoPendiente) return false;

                // Search query filter
                const q = (this.searchHistory || '').toLowerCase().trim();
                if (q) {
                    const matchQ = (nombreStr || '').includes(q) || 
                                   (ciStr || '').includes(q) || 
                                   (bocaminaStr || '').includes(q);
                    if (!matchQ) return false;
                }

                // Period filter
                if (this.filtroPeriodo === 'todos') return true;

                const rowDate = new Date(dateStr + 'T00:00:00');
                const now = new Date();

                if (this.filtroPeriodo === 'hoy') {
                    const todayStr = now.toISOString().slice(0, 10);
                    return dateStr === todayStr;
                }

                if (this.filtroPeriodo === 'semana') {
                    const curr = new Date();
                    const first = curr.getDate() - curr.getDay() + 1; // Monday
                    const monday = new Date(curr.setDate(first));
                    monday.setHours(0,0,0,0);
                    return rowDate >= monday;
                }

                if (this.filtroPeriodo === 'mes') {
                    return rowDate.getMonth() === now.getMonth() && rowDate.getFullYear() === now.getFullYear();
                }

                if (this.filtroPeriodo === 'custom') {
                    if (this.fechaInicio && dateStr < this.fechaInicio) return false;
                    if (this.fechaFin && dateStr > this.fechaFin) return false;
                    return true;
                }

                return true;
            }
        };
    }

    function paymentWizard() {
        return {
            trabajadorId: '',
            bocaminaFiltroId: '',
            trabajadoresList: @json($trabajadores),
            fecha: '{{ now()->toDateString() }}',
            bonos: 0,
            descuentos: 0,
            tipoCambio: 6.96,
            observacion: '',
            loading: false,
            
            // Data loaded dynamically
            trabajador: null,
            trabajos: [],
            anticipos: [],
            saldosPendientes: [],
            totalSaldosPendientes: 0,
            montoPagado: 0,
            userEditedMontoPagado: false,
            tipoPagoPlanilla: 'completo',
            
            // Live Totals
            subtotal: 0,
            anticiposDescontados: 0,
            neto: 0,

            searchQuery: '',
            grupoSeleccionado: '{{ request('cargo', request('grupo', '')) }}',
            
            getModalidadLabel(modalidad) {
                const labels = {
                    'por_produccion': '📦 Por Sacos Extraídos (Semanal)',
                    'por_toneladas': '⚖️ Por Toneladas / Cargas (Semanal)',
                    'por_metros': '📏 Por Avance de Metros (Semanal)',
                    'por_viaje': '🚛 Por Viajes / Fletes Diarios (Chofer)',
                    'sueldo_fijo': '🗓️ Sueldo Fijo Mensual (Sereno/Admin)'
                };
                return labels[modalidad] || 'Contrato Estándar';
            },

            get filteredTrabajadores() {
                return this.trabajadoresList.filter(t => {
                    const matchesGroup = !this.grupoSeleccionado || t.cargo === this.grupoSeleccionado;
                    const matchesBocamina = !this.bocaminaFiltroId || t.bocamina_id == this.bocaminaFiltroId;
                    const q = (this.searchQuery || '').toLowerCase().trim();
                    const matchesQuery = !q || 
                        (t.nombre || '').toLowerCase().includes(q) || 
                        (t.ci || '').toLowerCase().includes(q);
                    return matchesGroup && matchesBocamina && matchesQuery;
                });
            },

            onTrabajadorChange() {
                if (!this.trabajadorId) {
                    this.clear();
                    return;
                }

                this.loading = true;
                fetch(`/pagos/trabajador-data/${this.trabajadorId}`)
                    .then(res => res.json())
                    .then(data => {
                        this.trabajador = data.trabajador;
                        this.trabajos = data.trabajos;
                        this.anticipos = data.anticipos.map(a => ({
                            ...a,
                            aplicado: true,
                            liveDeduccion: parseFloat(a.saldo)
                        }));
                        this.saldosPendientes = data.saldos_pendientes || [];
                        this.totalSaldosPendientes = parseFloat(data.total_saldos_pendientes) || 0;
                        
                        this.subtotal = parseFloat(data.subtotal);
                        this.recalculate();
                        this.loading = false;
                    })
                    .catch(err => {
                        console.error(err);
                        this.loading = false;
                    });
            },

            recalculate() {
                let antDeductions = 0;
                this.anticipos.forEach(a => {
                    if (a.aplicado) {
                        antDeductions += parseFloat(a.liveDeduccion || 0);
                    }
                });
                this.anticiposDescontados = antDeductions;

                const base = parseFloat(this.subtotal || 0) + parseFloat(this.totalSaldosPendientes || 0) + parseFloat(this.bonos || 0);
                const desc = parseFloat(this.descuentos || 0) + parseFloat(this.anticiposDescontados || 0);
                
                this.neto = Math.max(0, base - desc);

                if (this.tipoPagoPlanilla === 'completo' && !this.userEditedMontoPagado) {
                    this.montoPagado = this.neto.toFixed(2);
                }
            },

            clear() {
                this.trabajador = null;
                this.trabajos = [];
                this.anticipos = [];
                this.saldosPendientes = [];
                this.totalSaldosPendientes = 0;
                this.subtotal = 0;
                this.bonos = 0;
                this.descuentos = 0;
                this.anticiposDescontados = 0;
                this.neto = 0;
                this.montoPagado = 0;
                this.userEditedMontoPagado = false;
                this.tipoPagoPlanilla = 'completo';
            }
        };
    }
</script>
@endpush
