@extends('layouts.app')

@section('title', 'Tablero Principal')

@section('content')
<div class="space-y-8">
    
    <!-- Welcome Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0 bg-slate-900/40 p-6 rounded-2xl border border-slate-800/80 backdrop-blur-lg relative overflow-hidden">
        <div class="absolute top-0 right-0 w-96 h-96 bg-cyan-500/10 rounded-full blur-3xl pointer-events-none -mr-20 -mt-20"></div>
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-cyan-500/10 border border-cyan-500/30 text-cyan-400 text-xs font-bold uppercase tracking-wider mb-2">
                <span class="w-2 h-2 rounded-full bg-cyan-400 animate-ping"></span>
                Sistema de Control de Pagos Mineros
            </div>
            <h1 class="text-3xl font-extrabold tracking-tight text-slate-100">
                Tablero de Control <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 via-sky-300 to-indigo-400">Ejecutivo</span>
            </h1>
            <p class="text-sm text-slate-300 mt-1">Monitoreo en tiempo real de bocaminas, liquidaciones de cargas, ingresos y egresos de caja.</p>
        </div>
        <div class="flex space-x-3 no-print relative z-10">
            <a href="{{ route('ventas-cargas.index') }}" class="btn-vibrant-cyan inline-flex items-center justify-center px-4 py-2.5 rounded-xl text-sm font-bold shadow-lg shadow-cyan-500/20">
                <i class="fa-solid fa-cart-plus mr-2"></i> Nueva Venta
            </a>
            <a href="{{ route('pagos.create') }}" class="btn-vibrant-success inline-flex items-center justify-center px-4 py-2.5 rounded-xl text-sm font-bold shadow-lg shadow-emerald-500/20">
                <i class="fa-solid fa-receipt mr-2"></i> Procesar Pago
            </a>
        </div>
    </div>

    <!-- Executive Stats Grid -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Ventas Mineral -->
        <div class="glass-card rounded-2xl p-6 relative overflow-hidden group hover:border-cyan-500/40 transition duration-300">
            <div class="absolute top-0 right-0 p-3 opacity-10 text-6xl text-cyan-400 group-hover:opacity-25 group-hover:scale-110 transition duration-300">
                <i class="fa-solid fa-truck-ramp-box"></i>
            </div>
            <p class="text-xs font-bold text-cyan-400 uppercase tracking-wider">Ventas Acumuladas Mineral</p>
            <p class="mt-2 text-3xl font-extrabold text-cyan-300 font-mono">Bs. {{ number_format($totalVentasIngresos, 2) }}</p>
            <div class="mt-2 text-xs text-slate-400 font-mono flex items-center gap-1">
                <i class="fa-solid fa-arrow-trend-up text-emerald-400"></i> Comercialización acumulada
            </div>
        </div>

        <!-- Saldo Caja General -->
        <div class="glass-card rounded-2xl p-6 relative overflow-hidden group hover:border-sky-500/40 transition duration-300">
            <div class="absolute top-0 right-0 p-3 opacity-10 text-6xl text-sky-400 group-hover:opacity-25 group-hover:scale-110 transition duration-300">
                <i class="fa-solid fa-vault"></i>
            </div>
            <p class="text-xs font-bold text-sky-400 uppercase tracking-wider">Saldo Caja General</p>
            <p class="mt-2 text-3xl font-extrabold text-sky-300 font-mono">Bs. {{ number_format($saldoCajasBs, 2) }}</p>
            <div class="mt-2 text-xs text-slate-400 font-mono flex items-center gap-1">
                <i class="fa-solid fa-wallet text-sky-400"></i> Efectivo disponible en caja
            </div>
        </div>

        <!-- Producción Extraída -->
        <div class="glass-card rounded-2xl p-6 relative overflow-hidden group hover:border-indigo-500/40 transition duration-300">
            <div class="absolute top-0 right-0 p-3 opacity-10 text-6xl text-indigo-400 group-hover:opacity-25 group-hover:scale-110 transition duration-300">
                <i class="fa-solid fa-cubes"></i>
            </div>
            <p class="text-xs font-bold text-indigo-400 uppercase tracking-wider">Producción Total Extraída</p>
            <p class="mt-2 text-3xl font-extrabold text-indigo-300 font-mono">{{ number_format($totalToneladasExtraidas, 2) }} <span class="text-sm font-normal text-slate-400">Tn</span></p>
            <div class="mt-2 text-xs text-slate-400 font-mono flex items-center gap-1">
                <i class="fa-solid fa-mountain text-indigo-400"></i> Volumen físico de bocaminas
            </div>
        </div>

        <!-- Utilidad Neta Estimada -->
        <div class="glass-card rounded-2xl p-6 relative overflow-hidden group hover:border-emerald-500/40 transition duration-300">
            <div class="absolute top-0 right-0 p-3 opacity-10 text-6xl text-emerald-400 group-hover:opacity-25 group-hover:scale-110 transition duration-300">
                <i class="fa-solid fa-chart-line"></i>
            </div>
            <p class="text-xs font-bold text-emerald-400 uppercase tracking-wider">Utilidad Neta Empresa / Coop.</p>
            <p class="mt-2 text-3xl font-extrabold text-emerald-300 font-mono">Bs. {{ number_format($utilidadNetaEstimada, 2) }}</p>
            <div class="mt-2 text-xs text-slate-400 font-mono flex items-center gap-1">
                <i class="fa-solid fa-piggy-bank text-emerald-400"></i> Balance financiero positivo
            </div>
        </div>
    </div>

    <!-- Charts Grid -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <!-- Chart 1: Producción por Bocamina -->
        <div class="glass-card rounded-2xl p-6">
            <h3 class="text-lg font-bold text-slate-100 mb-4 flex items-center">
                <i class="fa-solid fa-mountain mr-2 text-cyan-400"></i> Producción Total por Bocamina (Bs.)
            </h3>
            <div class="relative h-72">
                <canvas id="bocaminasChart"></canvas>
            </div>
        </div>

        <!-- Chart 2: Pagos Históricos -->
        <div class="glass-card rounded-2xl p-6">
            <h3 class="text-lg font-bold text-slate-100 mb-4 flex items-center">
                <i class="fa-solid fa-chart-area mr-2 text-sky-400"></i> Desembolsos de Pagos Netos (Últimos Meses)
            </h3>
            <div class="relative h-72">
                <canvas id="pagosChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Logs Grid -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <!-- Ventas Recientes -->
        <div class="glass-card rounded-2xl p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-slate-100 flex items-center">
                    <i class="fa-solid fa-truck-ramp-box mr-2 text-cyan-400"></i> Ventas de Cargas Recientes
                </h3>
                <a href="{{ route('ventas-cargas.index') }}" class="text-xs font-bold text-cyan-400 hover:text-cyan-300 hover:underline">Ver todas</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-800 text-sm">
                    <thead>
                        <tr class="text-left font-bold text-slate-400 uppercase tracking-wider text-xs">
                            <th class="py-3">Nº / Fecha</th>
                            <th class="py-3">Socio</th>
                            <th class="py-3">Mineral</th>
                            <th class="py-3 text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/50 text-slate-200">
                        @forelse($recientesVentas as $venta)
                            <tr class="hover:bg-slate-900/40 transition">
                                <td class="py-3 font-mono">
                                    <span class="text-cyan-400 font-bold">{{ $venta->numero_venta }}</span>
                                    <span class="block text-slate-400 text-xs">{{ $venta->fecha->format('d/m/Y') }}</span>
                                </td>
                                <td class="py-3 font-medium text-slate-100">{{ $venta->socio->nombre }}</td>
                                <td class="py-3 text-cyan-300 font-semibold">{{ $venta->tipo_mineral }}</td>
                                <td class="py-3 font-mono text-right font-bold text-sky-400">Bs. {{ number_format($venta->total_vendido, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-6 text-center text-slate-500 font-medium">No hay ventas registradas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Advances -->
        <div class="glass-card rounded-2xl p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-slate-100 flex items-center">
                    <i class="fa-solid fa-money-bill-transfer mr-2 text-sky-400"></i> Anticipos Recientes
                </h3>
                <a href="{{ route('anticipos.index') }}" class="text-xs font-bold text-sky-400 hover:text-sky-300 hover:underline">Ver todos</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-800 text-sm">
                    <thead>
                        <tr class="text-left font-bold text-slate-400 uppercase tracking-wider text-xs">
                            <th class="py-3">Beneficiario</th>
                            <th class="py-3">Fecha</th>
                            <th class="py-3 text-right">Monto</th>
                            <th class="py-3 text-right">Saldo</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/50 text-slate-200">
                        @forelse($recientesAnticipos as $anticipo)
                            <tr class="hover:bg-slate-900/40 transition">
                                <td class="py-3 font-medium text-slate-100">
                                    {{ $anticipo->tipo_receptor === 'socio' ? ($anticipo->socio->nombre ?? '-') : ($anticipo->trabajador->nombre ?? '-') }}
                                </td>
                                <td class="py-3 font-mono text-slate-400 text-xs">{{ $anticipo->fecha->format('d/m/Y') }}</td>
                                <td class="py-3 font-mono text-right text-slate-300">Bs. {{ number_format($anticipo->monto, 2) }}</td>
                                <td class="py-3 font-mono text-right font-bold text-cyan-400">Bs. {{ number_format($anticipo->saldo, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-6 text-center text-slate-500 font-medium">No hay anticipos registrados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Render Producción Chart with Cyan Gradient
        const bocaminasData = @json($produccionBocaminas);
        const bocaminasLabels = bocaminasData.map(item => item.nombre);
        const bocaminasTotals = bocaminasData.map(item => item.total);

        const ctx1 = document.getElementById('bocaminasChart').getContext('2d');
        const grad1 = ctx1.createLinearGradient(0, 0, 0, 300);
        grad1.addColorStop(0, 'rgba(6, 182, 212, 0.85)');
        grad1.addColorStop(1, 'rgba(56, 189, 248, 0.25)');

        new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: bocaminasLabels,
                datasets: [{
                    label: 'Producción (Bs.)',
                    data: bocaminasTotals,
                    backgroundColor: grad1,
                    borderColor: '#06b6d4',
                    borderWidth: 2,
                    borderRadius: 8,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: { ticks: { color: '#94a3b8', font: { family: 'Outfit', size: 12 } }, grid: { display: false } },
                    y: { ticks: { color: '#94a3b8', font: { family: 'Outfit', size: 12 } }, grid: { color: 'rgba(255, 255, 255, 0.05)' } }
                }
            }
        });

        // Render Pagos Chart with Sky Blue Line
        const pagosData = @json($pagosMensuales);
        const pagosLabels = pagosData.map(item => item.etiqueta);
        const pagosTotals = pagosData.map(item => item.total);

        const ctx2 = document.getElementById('pagosChart').getContext('2d');
        const grad2 = ctx2.createLinearGradient(0, 0, 0, 300);
        grad2.addColorStop(0, 'rgba(56, 189, 248, 0.35)');
        grad2.addColorStop(1, 'rgba(6, 182, 212, 0.02)');

        new Chart(ctx2, {
            type: 'line',
            data: {
                labels: pagosLabels,
                datasets: [{
                    label: 'Pagos Netos (Bs.)',
                    data: pagosTotals,
                    borderColor: '#38bdf8',
                    backgroundColor: grad2,
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3,
                    pointBackgroundColor: '#06b6d4',
                    pointBorderColor: '#ffffff',
                    pointRadius: 4,
                    pointHoverRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: { ticks: { color: '#94a3b8', font: { family: 'Outfit', size: 12 } }, grid: { display: false } },
                    y: { ticks: { color: '#94a3b8', font: { family: 'Outfit', size: 12 } }, grid: { color: 'rgba(255, 255, 255, 0.05)' } }
                }
            }
        });
    });
</script>
@endsection
