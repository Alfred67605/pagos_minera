<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BocaminaController;
use App\Http\Controllers\TrabajadorController;
use App\Http\Controllers\ContratoController;
use App\Http\Controllers\TrabajoController;
use App\Http\Controllers\AnticipoController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\SocioController;
use App\Http\Controllers\VentaCargaController;
use App\Http\Controllers\IngresoController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\CompradorController;
use App\Http\Controllers\CajaController;
use App\Http\Controllers\EgresoController;
use App\Http\Controllers\ProduccionMineraController;
use App\Http\Controllers\PrestamoController;
use App\Http\Controllers\UtilidadController;
use App\Http\Controllers\ContabilidadController;

// Public routes / Authentication
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Protected routes (Only logged-in admin users)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Dashboard
    Route::get('/', [ReporteController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard', function() {
        return redirect()->route('dashboard');
    });

    // Socios
    Route::resource('socios', SocioController::class)->only(['index', 'store', 'update', 'destroy']);

    // Bocaminas
    Route::resource('bocaminas', BocaminaController::class)->only(['index', 'store', 'update', 'destroy']);

    // Trabajadores / Personal
    Route::resource('trabajadores', TrabajadorController::class)->only(['index', 'store', 'update', 'destroy']);

    // Contratos
    Route::resource('contratos', ContratoController::class);

    // Préstamos & Créditos (Fase 4)
    Route::resource('prestamos', PrestamoController::class)->only(['index', 'store', 'destroy']);
    Route::post('/prestamos/cuotas/{cuota}/pagar', [PrestamoController::class, 'pagarCuota'])->name('prestamos.cuotas.pagar');

    // Distribución de Utilidades & Dividendos (Fase 4)
    Route::resource('utilidades', UtilidadController::class)->only(['index', 'store', 'destroy']);

    // Contabilidad & Libro Diario (Fase 4)
    Route::get('/contabilidad', [ContabilidadController::class, 'index'])->name('contabilidad.index');
    Route::post('/contabilidad/cuentas', [ContabilidadController::class, 'storeCuenta'])->name('contabilidad.cuentas.store');
    Route::post('/contabilidad/asientos', [ContabilidadController::class, 'storeAsiento'])->name('contabilidad.asientos.store');
    Route::delete('/contabilidad/asientos/{asiento}', [ContabilidadController::class, 'destroyAsiento'])->name('contabilidad.asientos.destroy');

    // Producción Minera (Fase 3)
    Route::resource('produccion', ProduccionMineraController::class)->only(['index', 'store', 'destroy']);

    // Venta de Cargas (Comercialización de Mineral)
    Route::get('/ventas-cargas/{ventas_carga}/recibo', [VentaCargaController::class, 'recibo'])->name('ventas-cargas.recibo');
    Route::resource('ventas-cargas', VentaCargaController::class)->only(['index', 'store', 'update', 'destroy']);

    // Compradores de Mineral
    Route::resource('compradores', CompradorController::class)->only(['index', 'store', 'update', 'destroy']);

    // Caja General & Arqueos
    Route::resource('cajas', CajaController::class)->only(['index', 'store', 'show', 'destroy']);
    Route::post('/cajas/{caja}/movimientos', [CajaController::class, 'registrarMovimiento'])->name('cajas.movimientos.store');
    Route::post('/cajas/{caja}/toggle-estado', [CajaController::class, 'toggleEstado'])->name('cajas.toggle-estado');

    // Egresos & Gastos Operativos
    Route::resource('egresos', EgresoController::class)->only(['index', 'store', 'destroy']);
    Route::post('/egresos/categorias', [EgresoController::class, 'storeCategoria'])->name('egresos.categorias.store');

    // Ingresos Económicos
    Route::resource('ingresos', IngresoController::class)->only(['index', 'store', 'destroy']);

    // Anticipos
    Route::get('/anticipos/{anticipo}/recibo', [App\Http\Controllers\AnticipoController::class, 'recibo'])->name('anticipos.recibo');
    Route::resource('anticipos', AnticipoController::class)->only(['index', 'store', 'update', 'destroy']);

    // Pagos
    Route::get('/pagos', [PagoController::class, 'index'])->name('pagos.index');
    Route::get('/pagos/crear', [PagoController::class, 'create'])->name('pagos.create');
    Route::post('/pagos', [PagoController::class, 'store'])->name('pagos.store');
    Route::get('/pagos/trabajador-data/{id}', [PagoController::class, 'getTrabajadorData'])->name('pagos.trabajador-data');
    Route::get('/pagos/{pago}', [PagoController::class, 'show'])->name('pagos.show');

    // Reportes
    Route::get('/reportes', [ReporteController::class, 'index'])->name('reportes.index');
});

