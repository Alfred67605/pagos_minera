@extends('layouts.app')

@section('title', 'Dashboard General')

@section('content')
<div class="space-y-8">
    
    <!-- Welcome & Executive Action Header -->
    <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-6 bg-slate-900/90 p-6 md:p-8 rounded-3xl border-2 border-cyan-500/40 backdrop-blur-xl relative overflow-hidden shadow-2xl">
        <div class="absolute top-0 right-0 w-96 h-96 bg-gradient-to-br from-cyan-500/20 via-sky-500/10 to-transparent rounded-full blur-3xl pointer-events-none -mr-20 -mt-20"></div>
        
        <div class="relative z-10 space-y-2">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-cyan-500/20 border border-cyan-400/50 text-cyan-300 text-xs font-black uppercase tracking-widest shadow-md">
                <span class="w-2.5 h-2.5 rounded-full bg-cyan-400 animate-ping"></span>
                SISTEMA ERP MINERO & CONTROL DE PAGOS
            </div>
            <h1 class="text-3xl md:text-4xl font-black tracking-tight text-white">
                Dashboard General <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 via-sky-300 to-indigo-300">Ejecutivo</span>
            </h1>
            <p class="text-xs md:text-sm text-slate-200 font-medium max-w-2xl leading-relaxed">
                Supervisión centralizada en tiempo real del dinero en efectivo para personal, liquidación de nóminas semanales y comercialización de mineral.
            </p>
        </div>

        <!-- Insignia de Estado y Supervisión General (Sin botones) -->
        <div class="flex items-center gap-3 bg-slate-950/80 px-5 py-3 rounded-2xl border border-cyan-500/40 text-xs font-mono text-cyan-300 shadow-inner relative z-10">
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-ping"></span>
                <span class="font-black text-white font-sans uppercase tracking-wider">Monitor de Estado General</span>
            </div>
            <span class="text-slate-600">|</span>
            <span class="text-slate-200 font-bold"><i class="fa-solid fa-clock text-cyan-400 mr-1.5"></i>{{ date('d/m/Y H:i') }}</span>
        </div>
    </div>

    <!-- Executive Stats Grid (6 Tarjetas de Control de Alto Contraste) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4">
        
        <!-- 1. Fondo de Personal (Caja Chica) -->
        <div class="glass-card rounded-2xl p-5 relative overflow-hidden group hover:border-emerald-400/60 transition-all duration-300 border-2 border-emerald-500/30">
            <div class="flex items-center justify-between mb-1.5">
                <span class="text-xs font-black text-emerald-400 uppercase tracking-widest">Fondo de Personal</span>
                <div class="w-8 h-8 rounded-xl bg-emerald-500/20 text-emerald-300 flex items-center justify-center text-sm border border-emerald-500/40">
                    <i class="fa-solid fa-wallet"></i>
                </div>
            </div>
            <p class="text-3xl font-black text-white font-mono tracking-tight my-1">Bs. {{ number_format($saldoFondoPersonalBs, 2) }}</p>
            <div class="mt-2 text-xs font-bold flex items-center gap-1.5">
                @if($saldoFondoPersonalBs > 0)
                    <span class="inline-block w-2.5 h-2.5 rounded-full bg-emerald-400"></span> <span class="text-emerald-300 font-extrabold">Efectivo Disponible</span>
                @else
                    <span class="inline-block w-2.5 h-2.5 rounded-full bg-rose-500 animate-ping"></span> <span class="text-rose-300 font-black">Requiere Recarga</span>
                @endif
            </div>
        </div>

        <!-- 2. Fondo Operativo Comercial -->
        <div class="glass-card rounded-2xl p-5 relative overflow-hidden group hover:border-cyan-400/60 transition-all duration-300 border-2 border-cyan-500/30">
            <div class="flex items-center justify-between mb-1.5">
                <span class="text-xs font-black text-cyan-400 uppercase tracking-widest">Fondo Comercial</span>
                <div class="w-8 h-8 rounded-xl bg-cyan-500/20 text-cyan-300 flex items-center justify-center text-sm border border-cyan-500/40">
                    <i class="fa-solid fa-vault"></i>
                </div>
            </div>
            <p class="text-3xl font-black text-white font-mono tracking-tight my-1">Bs. {{ number_format($saldoFondoOperativoBs, 2) }}</p>
            <p class="mt-2 text-xs font-extrabold text-cyan-200">Compra / Venta Mineral</p>
        </div>

        <!-- 3. Personal Activo -->
        <div class="glass-card rounded-2xl p-5 relative overflow-hidden group hover:border-sky-400/60 transition-all duration-300 border-2 border-sky-500/30">
            <div class="flex items-center justify-between mb-1.5">
                <span class="text-xs font-black text-sky-400 uppercase tracking-widest">Personal Activo</span>
                <div class="w-8 h-8 rounded-xl bg-sky-500/20 text-sky-300 flex items-center justify-center text-sm border border-sky-500/40">
                    <i class="fa-solid fa-user-group"></i>
                </div>
            </div>
            <p class="text-3xl font-black text-white font-mono tracking-tight my-1">{{ $totalTrabajadores }} <span class="text-xs font-semibold text-slate-300 font-sans">Personas</span></p>
            <p class="mt-2 text-xs font-extrabold text-sky-200">Contratistas & Serenos</p>
        </div>

        <!-- 4. Anticipos Activos sin Saldar -->
        <div class="glass-card rounded-2xl p-5 relative overflow-hidden group hover:border-purple-400/60 transition-all duration-300 border-2 border-purple-500/30">
            <div class="flex items-center justify-between mb-1.5">
                <span class="text-xs font-black text-purple-400 uppercase tracking-widest">Anticipos Pendientes</span>
                <div class="w-8 h-8 rounded-xl bg-purple-500/20 text-purple-300 flex items-center justify-center text-sm border border-purple-500/40">
                    <i class="fa-solid fa-money-bill-transfer"></i>
                </div>
            </div>
            <p class="text-3xl font-black text-purple-300 font-mono tracking-tight my-1">Bs. {{ number_format($totalAnticiposPendientes, 2) }}</p>
            <p class="mt-2 text-xs font-extrabold text-purple-200">A descontar en planilla</p>
        </div>

        <!-- 5. Ventas Mineral Acumuladas -->
        <div class="glass-card rounded-2xl p-5 relative overflow-hidden group hover:border-amber-400/60 transition-all duration-300 border-2 border-amber-500/30">
            <div class="flex items-center justify-between mb-1.5">
                <span class="text-xs font-black text-amber-400 uppercase tracking-widest">Ventas Mineral</span>
                <div class="w-8 h-8 rounded-xl bg-amber-500/20 text-amber-300 flex items-center justify-center text-sm border border-amber-500/40">
                    <i class="fa-solid fa-truck-ramp-box"></i>
                </div>
            </div>
            <p class="text-3xl font-black text-amber-300 font-mono tracking-tight my-1">Bs. {{ number_format($totalVentasIngresos, 2) }}</p>
            <p class="mt-2 text-xs font-extrabold text-amber-200">Comercialización Acumulada</p>
        </div>

        <!-- 6. Utilidad Neta Estimada -->
        <div class="glass-card rounded-2xl p-5 relative overflow-hidden group hover:border-indigo-400/60 transition-all duration-300 border-2 border-indigo-500/30">
            <div class="flex items-center justify-between mb-1.5">
                <span class="text-xs font-black text-indigo-400 uppercase tracking-widest">Utilidad Neta</span>
                <div class="w-8 h-8 rounded-xl bg-indigo-500/20 text-indigo-300 flex items-center justify-center text-sm border border-indigo-500/40">
                    <i class="fa-solid fa-chart-line"></i>
                </div>
            </div>
            <p class="text-3xl font-black text-indigo-300 font-mono tracking-tight my-1">Bs. {{ number_format($utilidadNetaEstimada, 2) }}</p>
            <p class="mt-2 text-xs font-extrabold text-indigo-200">Balance Financiero Neto</p>
        </div>
    </div>

    <!-- Charts Grid -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <!-- Chart 1: Producción por Bocamina -->
        <div class="glass-card rounded-3xl p-6">
            <h3 class="text-base font-black text-white mb-4 flex items-center gap-2">
                <i class="fa-solid fa-mountain text-cyan-400"></i> Producción por Bocamina (Bs. Acumulado)
            </h3>
            <div class="relative h-72">
                <canvas id="bocaminasChart"></canvas>
            </div>
        </div>

        <!-- Chart 2: Desembolsos de Pagos Históricos -->
        <div class="glass-card rounded-3xl p-6">
            <h3 class="text-base font-black text-white mb-4 flex items-center gap-2">
                <i class="fa-solid fa-chart-area text-rose-400"></i> Desembolsos de Sueldos y Nóminas (Últimos Meses)
            </h3>
            <div class="relative h-72">
                <canvas id="pagosChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Activity Tables Grid (3 Tablas Vivas) -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        
        <!-- 1. Anticipos Recientes de Personal -->
        <div class="glass-card rounded-3xl p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-black text-white flex items-center gap-2">
                    <i class="fa-solid fa-hand-holding-dollar text-purple-400"></i> Anticipos Recientes
                </h3>
                <a href="{{ route('anticipos.index') }}" class="text-xs font-bold text-purple-400 hover:text-purple-300 hover:underline">Ver todos</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-800 text-xs">
                    <thead>
                        <tr class="text-left font-bold text-slate-400 uppercase tracking-wider">
                            <th class="py-2">Trabajador</th>
                            <th class="py-2 text-right">Monto</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/50 text-slate-200">
                        @forelse($recientesAnticipos as $ant)
                            <tr class="hover:bg-slate-900/40 transition">
                                <td class="py-2.5">
                                    <span class="font-bold text-white block">{{ $ant->trabajador ? $ant->trabajador->nombre : ($ant->socio ? $ant->socio->nombre : 'N/A') }}</span>
                                    <span class="text-slate-400 text-[10px]">{{ $ant->fecha ? $ant->fecha->format('d/m/Y') : '' }}</span>
                                </td>
                                <td class="py-2.5 text-right font-mono font-black text-purple-300">Bs. {{ number_format($ant->monto, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="py-4 text-center text-slate-500 font-medium">No hay anticipos recientes.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 2. Pagos de Personal Recientes -->
        <div class="glass-card rounded-3xl p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-black text-white flex items-center gap-2">
                    <i class="fa-solid fa-receipt text-emerald-400"></i> Liquidaciones Recientes
                </h3>
                <a href="{{ route('pagos.index') }}" class="text-xs font-bold text-emerald-400 hover:text-emerald-300 hover:underline">Ver todas</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-800 text-xs">
                    <thead>
                        <tr class="text-left font-bold text-slate-400 uppercase tracking-wider">
                            <th class="py-2">Personal</th>
                            <th class="py-2 text-right">Líquido Pagado</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/50 text-slate-200">
                        @forelse($recientesPagos as $pago)
                            <tr class="hover:bg-slate-900/40 transition">
                                <td class="py-2.5">
                                    <span class="font-bold text-white block">{{ $pago->trabajador ? $pago->trabajador->nombre : ($pago->socio ? $pago->socio->nombre : 'N/A') }}</span>
                                    <span class="text-slate-400 text-[10px]">{{ $pago->fecha ? $pago->fecha->format('d/m/Y') : '' }}</span>
                                </td>
                                <td class="py-2.5 text-right font-mono font-black text-emerald-400">Bs. {{ number_format($pago->neto, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="py-4 text-center text-slate-500 font-medium">No hay pagos procesados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 3. Ventas de Mineral Recientes -->
        <div class="glass-card rounded-3xl p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-black text-white flex items-center gap-2">
                    <i class="fa-solid fa-truck-ramp-box text-amber-400"></i> Cargas / Ventas Recientes
                </h3>
                <a href="{{ route('ventas-cargas.index') }}" class="text-xs font-bold text-amber-400 hover:text-amber-300 hover:underline">Ver todas</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-800 text-xs">
                    <thead>
                        <tr class="text-left font-bold text-slate-400 uppercase tracking-wider">
                            <th class="py-2">Venta / Socio</th>
                            <th class="py-2 text-right">Total (Bs.)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/50 text-slate-200">
                        @forelse($recientesVentas as $venta)
                            <tr class="hover:bg-slate-900/40 transition">
                                <td class="py-2.5">
                                    <span class="font-bold text-white block">{{ $venta->numero_venta }}</span>
                                    <span class="text-slate-400 text-[10px]">{{ $venta->socio->nombre }}</span>
                                </td>
                                <td class="py-2.5 text-right font-mono font-black text-amber-300">Bs. {{ number_format($venta->total_vendido, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="py-4 text-center text-slate-500 font-medium">No hay ventas registradas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Grafico Bocaminas
        const bocaminasData = @json($produccionBocaminas);
        const ctxBocaminas = document.getElementById('bocaminasChart').getContext('2d');
        new Chart(ctxBocaminas, {
            type: 'bar',
            data: {
                labels: bocaminasData.map(d => d.nombre),
                datasets: [{
                    label: 'Producción (Bs.)',
                    data: bocaminasData.map(d => d.total),
                    backgroundColor: 'rgba(6, 182, 212, 0.6)',
                    borderColor: 'rgba(6, 182, 212, 1)',
                    borderWidth: 2,
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { ticks: { color: '#94a3b8' }, grid: { display: false } },
                    y: { ticks: { color: '#94a3b8' }, grid: { color: 'rgba(51, 65, 85, 0.4)' } }
                }
            }
        });

        // Grafico Pagos Mensuales
        const pagosData = @json($pagosMensuales);
        const ctxPagos = document.getElementById('pagosChart').getContext('2d');
        new Chart(ctxPagos, {
            type: 'line',
            data: {
                labels: pagosData.map(d => d.etiqueta),
                datasets: [{
                    label: 'Pagos Desembolsados (Bs.)',
                    data: pagosData.map(d => d.total),
                    borderColor: 'rgba(244, 63, 94, 1)',
                    backgroundColor: 'rgba(244, 63, 94, 0.15)',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { ticks: { color: '#94a3b8' }, grid: { display: false } },
                    y: { ticks: { color: '#94a3b8' }, grid: { color: 'rgba(51, 65, 85, 0.4)' } }
                }
            }
        });
    });
</script>
@endpush
@endsection
