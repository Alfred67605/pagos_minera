<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Bocamina;
use App\Models\Socio;
use App\Models\Trabajador;
use App\Models\Comprador;
use App\Models\Contrato;
use App\Models\VentaCarga;
use App\Models\Ingreso;
use App\Models\Trabajo;
use App\Models\Anticipo;
use App\Models\Pago;
use App\Models\Caja;
use App\Models\CajaMovimiento;
use App\Models\CategoriaEgreso;
use App\Models\Egreso;
use App\Models\ProduccionMinera;
use App\Models\Prestamo;
use App\Models\CuotaPrestamo;
use App\Models\DistribucionUtilidad;
use App\Models\DetalleUtilidadSocio;
use App\Models\CuentaContable;
use App\Models\AsientoContable;
use App\Models\DetalleAsientoContable;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with complete, rich, realistic mining enterprise data.
     */
    public function run(): void
    {
        // 1. Usuarios del Sistema
        $admin = User::create([
            'name' => 'Administrador Minero',
            'email' => 'admin@mina.com',
            'password' => Hash::make('admin123'),
        ]);

        $contador = User::create([
            'name' => 'Lic. Marco Antonio Soliz',
            'email' => 'contador@mina.com',
            'password' => Hash::make('admin123'),
        ]);

        // 2. Bocaminas Operativas
        $sanjose = Bocamina::create([
            'nombre' => 'Bocamina San José',
            'descripcion' => 'Sector norte, veta rica de plata, plomo y zinc. Nivel 3.',
        ]);

        $rosario = Bocamina::create([
            'nombre' => 'Bocamina Rosario',
            'descripcion' => 'Galería central profunda, alta concentración de zinc y plata.',
        ]);

        $santamaria = Bocamina::create([
            'nombre' => 'Bocamina Santa María',
            'descripcion' => 'Frente de exploración y explotación sección sur.',
        ]);

        $esperanza = Bocamina::create([
            'nombre' => 'Bocamina La Esperanza',
            'descripcion' => 'Socavón principal nivel 1. Mineral complejo.',
        ]);

        $sanantonio = Bocamina::create([
            'nombre' => 'Bocamina San Antonio',
            'descripcion' => 'Veta secundaria de pirita y sulfuros.',
        ]);

        // 3. Socios Cooperativistas
        $socioDonato = Socio::create([
            'codigo' => 'SOC-001',
            'nombre' => 'Donato Quispe Fernández',
            'ci' => '4829103-PO',
            'telefono' => '71928374',
            'bocamina_id' => $sanjose->id,
            'porcentaje_participacion' => 35.00,
            'estado' => 'activo',
            'observaciones' => 'Socio fundador y delegado de seguridad.',
        ]);

        $socioGerman = Socio::create([
            'codigo' => 'SOC-002',
            'nombre' => 'Germán Morales Mamani',
            'ci' => '3920194-OR',
            'telefono' => '72839401',
            'bocamina_id' => $rosario->id,
            'porcentaje_participacion' => 30.00,
            'estado' => 'activo',
            'observaciones' => 'Encargado de operaciones de la Bocamina Rosario.',
        ]);

        $socioBenjamin = Socio::create([
            'codigo' => 'SOC-003',
            'nombre' => 'Benjamín Gutiérrez Choque',
            'ci' => '5019283-LP',
            'telefono' => '73940192',
            'bocamina_id' => $santamaria->id,
            'porcentaje_participacion' => 20.00,
            'estado' => 'activo',
            'observaciones' => 'Supervisión de maquinaria y compresoras.',
        ]);

        $socioTeofilo = Socio::create([
            'codigo' => 'SOC-004',
            'nombre' => 'Teófilo Alarcón Condori',
            'ci' => '4102938-PT',
            'telefono' => '74019283',
            'bocamina_id' => $esperanza->id,
            'porcentaje_participacion' => 15.00,
            'estado' => 'activo',
            'observaciones' => 'Representante comercial de la cooperativa.',
        ]);

        // 4. Trabajadores de Mina
        $juan = Trabajador::create([
            'ci' => '5938201-LP',
            'nombre' => 'Juan Pérez Mamani',
            'telefono' => '71234567',
            'cargo' => 'trabajador_bocamina',
            'fecha_ingreso' => Carbon::today()->subMonths(12)->toDateString(),
            'modalidad_pago' => 'por_produccion',
            'sueldo_base' => 0.00,
            'bocamina_id' => $sanjose->id,
            'estado' => 'activo',
            'observaciones' => 'Perforista experimentado de veta principal.',
        ]);

        $pedro = Trabajador::create([
            'ci' => '4829301-OR',
            'nombre' => 'Pedro Quispe Mamani',
            'telefono' => '72198765',
            'cargo' => 'trabajador_bocamina',
            'fecha_ingreso' => Carbon::today()->subMonths(8)->toDateString(),
            'modalidad_pago' => 'por_produccion',
            'sueldo_base' => 0.00,
            'bocamina_id' => $rosario->id,
            'estado' => 'activo',
            'observaciones' => 'Cargador y enganchador de carros mineros.',
        ]);

        $luis = Trabajador::create([
            'ci' => '6910293-PT',
            'nombre' => 'Luis Alberto Flores',
            'telefono' => '73204918',
            'cargo' => 'trabajador_bocamina',
            'fecha_ingreso' => Carbon::today()->subMonths(15)->toDateString(),
            'modalidad_pago' => 'por_produccion',
            'sueldo_base' => 0.00,
            'bocamina_id' => $sanjose->id,
            'estado' => 'activo',
            'observaciones' => 'Capataz de cuadrilla y especialista en enmaderado.',
        ]);

        $mario = Trabajador::create([
            'ci' => '3928103-LP',
            'nombre' => 'Mario Choque Condori',
            'telefono' => '70129384',
            'cargo' => 'sereno',
            'fecha_ingreso' => Carbon::today()->subMonths(6)->toDateString(),
            'modalidad_pago' => 'sueldo_fijo',
            'sueldo_base' => 2800.00,
            'bocamina_id' => $santamaria->id,
            'estado' => 'activo',
            'observaciones' => 'Sereno turno nocturno en planta de bocamina.',
        ]);

        $edwin = Trabajador::create([
            'ci' => '5102938-OR',
            'nombre' => 'Edwin Tarqui Choque',
            'telefono' => '71902834',
            'cargo' => 'chofer',
            'fecha_ingreso' => Carbon::today()->subMonths(10)->toDateString(),
            'modalidad_pago' => 'sueldo_fijo',
            'sueldo_base' => 3500.00,
            'bocamina_id' => $sanjose->id,
            'estado' => 'activo',
            'observaciones' => 'Chofer titular de volqueta Volvo FMX.',
        ]);

        $ramiro = Trabajador::create([
            'ci' => '6019283-PO',
            'nombre' => 'Ramiro Mendoza Viza',
            'telefono' => '72019283',
            'cargo' => 'trabajador_bocamina',
            'fecha_ingreso' => Carbon::today()->subMonths(4)->toDateString(),
            'modalidad_pago' => 'por_produccion',
            'sueldo_base' => 0.00,
            'bocamina_id' => $esperanza->id,
            'estado' => 'activo',
            'observaciones' => 'Mecánico de mantenimiento de perforadoras.',
        ]);

        $carmen = Trabajador::create([
            'ci' => '4920192-CB',
            'nombre' => 'Carmen Rosa Colque',
            'telefono' => '73102938',
            'cargo' => 'personal_admin',
            'fecha_ingreso' => Carbon::today()->subMonths(18)->toDateString(),
            'modalidad_pago' => 'sueldo_fijo',
            'sueldo_base' => 3200.00,
            'bocamina_id' => $sanjose->id,
            'estado' => 'activo',
            'observaciones' => 'Encargada de kardex de herramientas y despacho.',
        ]);

        // 5. Compradores de Mineral
        $compVinto = Comprador::create([
            'razon_social' => 'Empresa Metalúrgica Vinto S.A.',
            'nit_ci' => '1029384029',
            'contacto_nombre' => 'Ing. Roberto Mendoza',
            'telefono' => '71239876',
            'email' => 'ventas@vinto.com.bo',
            'direccion' => 'Carretera Oruro - La Paz Km 7',
            'estado' => 'activo',
        ]);

        $compBaremsa = Comprador::create([
            'razon_social' => 'Comercializadora de Minerales Baremsa Ltda.',
            'nit_ci' => '2039481023',
            'contacto_nombre' => 'Lic. Carlos Villagómez',
            'telefono' => '72839401',
            'email' => 'compras@baremsa.com.bo',
            'direccion' => 'Av. 6 de Agosto Nro 1420, Oruro',
            'estado' => 'activo',
        ]);

        $compSanCristobal = Comprador::create([
            'razon_social' => 'Compañía Minera San Cristóbal S.A.',
            'nit_ci' => '3948201092',
            'contacto_nombre' => 'Ing. Fernando Roca',
            'telefono' => '73940192',
            'email' => 'logistica@sancristobal.com.bo',
            'direccion' => 'Edificio Los Cerros Piso 4, Potosí',
            'estado' => 'activo',
        ]);

        $compMinsur = Comprador::create([
            'razon_social' => 'Minerales del Sur & Asoc.',
            'nit_ci' => '1928374012',
            'contacto_nombre' => 'Sr. Gonzalo Terán',
            'telefono' => '74019283',
            'email' => 'gteran@minsur.com.bo',
            'direccion' => 'Zona Industrial Huajara Lote 12',
            'estado' => 'activo',
        ]);

        // 6. Contratos Mineros
        $contratoJuan = Contrato::create([
            'codigo' => 'CON-SJOSE-01',
            'trabajador_id' => $juan->id,
            'bocamina_id' => $sanjose->id,
            'descripcion' => 'Avance de 50 metros en Veta Principal Nivel 3',
            'tipo_pago' => 'metro',
            'precio_unitario' => 500.00,
            'monto_total' => 25000.00,
            'fecha_inicio' => Carbon::today()->subDays(45)->toDateString(),
            'estado' => 'activo',
        ]);

        $contratoPedro = Contrato::create([
            'codigo' => 'CON-ROS-02',
            'trabajador_id' => $pedro->id,
            'bocamina_id' => $rosario->id,
            'descripcion' => 'Carga y transporte de 100 volquetas de mineral bruto',
            'tipo_pago' => 'volqueta',
            'precio_unitario' => 150.00,
            'monto_total' => 15000.00,
            'fecha_inicio' => Carbon::today()->subDays(30)->toDateString(),
            'estado' => 'activo',
        ]);

        $contratoLuis = Contrato::create([
            'codigo' => 'CON-SMARIA-03',
            'trabajador_id' => $luis->id,
            'bocamina_id' => $santamaria->id,
            'descripcion' => 'Enmaderado y sostenimiento de 30 metros de galería',
            'tipo_pago' => 'metro',
            'precio_unitario' => 300.00,
            'monto_total' => 9000.00,
            'fecha_inicio' => Carbon::today()->subDays(25)->toDateString(),
            'estado' => 'activo',
        ]);

        $contratoRamiro = Contrato::create([
            'codigo' => 'CON-ESP-04',
            'trabajador_id' => $ramiro->id,
            'bocamina_id' => $esperanza->id,
            'descripcion' => 'Perforación y disparo de 80 tiros de voladura',
            'tipo_pago' => 'tonelada',
            'precio_unitario' => 80.00,
            'monto_total' => 6400.00,
            'fecha_inicio' => Carbon::today()->subDays(15)->toDateString(),
            'estado' => 'activo',
        ]);

        // 7. Ventas de Cargas de Mineral
        $vnt1 = VentaCarga::create([
            'numero_venta' => 'VNT-2026-001',
            'fecha' => Carbon::today()->subDays(20)->toDateString(),
            'socio_id' => $socioDonato->id,
            'bocamina_id' => $sanjose->id,
            'tipo_mineral' => 'Complejo (Zn-Pb-Ag)',
            'cantidad' => 1,
            'peso_neto' => 25.50,
            'precio_unitario' => 2686.27,
            'total_vendido' => 68500.00,
            'comprador' => $compVinto->razon_social,
            'comprador_id' => $compVinto->id,
            'observaciones' => 'Liquidación de lote Nro. 402 en planta Vinto con ley de plata alta.',
            'user_id' => $admin->id,
        ]);

        $vnt2 = VentaCarga::create([
            'numero_venta' => 'VNT-2026-002',
            'fecha' => Carbon::today()->subDays(12)->toDateString(),
            'socio_id' => $socioGerman->id,
            'bocamina_id' => $rosario->id,
            'tipo_mineral' => 'Zinc Concentrado',
            'cantidad' => 2,
            'peso_neto' => 40.00,
            'precio_unitario' => 2300.00,
            'total_vendido' => 92000.00,
            'comprador' => $compBaremsa->razon_social,
            'comprador_id' => $compBaremsa->id,
            'observaciones' => 'Venta directa de mineral procesado en huajra.',
            'user_id' => $admin->id,
        ]);

        VentaCarga::create([
            'numero_venta' => 'VNT-2026-003',
            'fecha' => Carbon::today()->subDays(5)->toDateString(),
            'socio_id' => $socioBenjamin->id,
            'bocamina_id' => $santamaria->id,
            'tipo_mineral' => 'Plata / Plomo',
            'cantidad' => 1,
            'peso_neto' => 18.20,
            'precio_unitario' => 6318.68,
            'total_vendido' => 115000.00,
            'comprador' => $compSanCristobal->razon_social,
            'comprador_id' => $compSanCristobal->id,
            'observaciones' => 'Concentrado de alta ley entregado en Potosí.',
            'user_id' => $admin->id,
        ]);

        VentaCarga::create([
            'numero_venta' => 'VNT-2026-004',
            'fecha' => Carbon::today()->subDays(2)->toDateString(),
            'socio_id' => $socioTeofilo->id,
            'bocamina_id' => $esperanza->id,
            'tipo_mineral' => 'Zinc Bruto',
            'cantidad' => 1,
            'peso_neto' => 30.00,
            'precio_unitario' => 2600.00,
            'total_vendido' => 78000.00,
            'comprador' => $compMinsur->razon_social,
            'comprador_id' => $compMinsur->id,
            'observaciones' => 'Despacho de volqueta completa.',
            'user_id' => $admin->id,
        ]);

        // 8. Cajas y Movimientos
        $cajaGen = Caja::create([
            'nombre' => 'Caja General Central',
            'tipo' => 'caja_general',
            'saldo_inicial' => 150000.00,
            'saldo_actual' => 150000.00,
            'estado' => 'abierta',
        ]);

        $cajaChica = Caja::create([
            'nombre' => 'Caja Chica Operativa Mina',
            'tipo' => 'caja_chica',
            'saldo_inicial' => 25000.00,
            'saldo_actual' => 25000.00,
            'estado' => 'abierta',
        ]);

        CajaMovimiento::create([
            'caja_id' => $cajaGen->id,
            'tipo' => 'ingreso',
            'monto' => 150000.00,
            'concepto' => 'Apertura de Caja General de la Cooperativa',
            'categoria' => 'Saldo Inicial',
            'fecha' => Carbon::today()->subDays(60)->toDateString(),
            'user_id' => $admin->id,
        ]);

        CajaMovimiento::create([
            'caja_id' => $cajaChica->id,
            'tipo' => 'ingreso',
            'monto' => 25000.00,
            'concepto' => 'Asignación de Fondos a Caja Chica',
            'categoria' => 'Saldo Inicial',
            'fecha' => Carbon::today()->subDays(60)->toDateString(),
            'user_id' => $admin->id,
        ]);

        // Ingresos Operativos
        Ingreso::create([
            'fecha' => Carbon::today()->subDays(18)->toDateString(),
            'concepto' => 'Cobro de Venta Nro VNT-2026-001 Empresa Metalúrgica Vinto',
            'monto' => 68500.00,
            'origen' => 'venta_carga',
            'venta_carga_id' => $vnt1->id,
            'observaciones' => 'Liquidación bancaria recibida.',
            'user_id' => $admin->id,
        ]);
        $cajaGen->saldo_actual += 68500.00;
        $cajaGen->save();

        CajaMovimiento::create([
            'caja_id' => $cajaGen->id,
            'tipo' => 'ingreso',
            'monto' => 68500.00,
            'concepto' => 'Ingreso: Cobro de Venta Nro VNT-2026-001 Empresa Vinto',
            'categoria' => 'Venta Mineral',
            'referencia_tipo' => 'ingreso',
            'fecha' => Carbon::today()->subDays(18)->toDateString(),
            'user_id' => $admin->id,
        ]);

        Ingreso::create([
            'fecha' => Carbon::today()->subDays(10)->toDateString(),
            'concepto' => 'Cobro parcial de Venta Nro VNT-2026-002 Baremsa',
            'monto' => 50000.00,
            'origen' => 'venta_carga',
            'venta_carga_id' => $vnt2->id,
            'observaciones' => 'Transferencia recibida.',
            'user_id' => $admin->id,
        ]);
        $cajaGen->saldo_actual += 50000.00;
        $cajaGen->save();

        CajaMovimiento::create([
            'caja_id' => $cajaGen->id,
            'tipo' => 'ingreso',
            'monto' => 50000.00,
            'concepto' => 'Ingreso: Cobro parcial de Venta Nro VNT-2026-002 Baremsa',
            'categoria' => 'Venta Mineral',
            'referencia_tipo' => 'ingreso',
            'fecha' => Carbon::today()->subDays(10)->toDateString(),
            'user_id' => $admin->id,
        ]);

        // 9. Categorías de Egreso & Gastos Operativos
        $catMant = CategoriaEgreso::create(['nombre' => 'Mantenimiento y Repuestos', 'descripcion' => 'Repuestos de maquinaria, lubricantes y reparaciones.']);
        $catComb = CategoriaEgreso::create(['nombre' => 'Combustible y Carburantes', 'descripcion' => 'Diésel y gasolina para volquetes y compresoras.']);
        $catAlim = CategoriaEgreso::create(['nombre' => 'Alimentación y Viáticos', 'descripcion' => 'Rancho, insumos alimenticios y viáticos de personal.']);
        $catExpl = CategoriaEgreso::create(['nombre' => 'Herramientas e Explosivos', 'descripcion' => 'Dinamita, anfo, detonadores y herramientas de mina.']);
        $catServ = CategoriaEgreso::create(['nombre' => 'Servicios e Impuestos', 'descripcion' => 'Energía eléctrica, agua, telefonía y patentes.']);

        // Egreso 1: Explosivos
        $e1 = Egreso::create([
            'caja_id' => $cajaGen->id,
            'categoria_id' => $catExpl->id,
            'monto' => 12500.00,
            'concepto' => 'Adquisición de dinamita, anfo y guía fusible para voladuras',
            'fecha' => Carbon::today()->subDays(25)->toDateString(),
            'comprobante_numero' => 'FAC-7819',
            'proveedor' => 'Explosivos de Bolivia S.A.',
            'user_id' => $admin->id,
        ]);
        $cajaGen->saldo_actual -= 12500.00;
        $cajaGen->save();

        CajaMovimiento::create([
            'caja_id' => $cajaGen->id,
            'tipo' => 'egreso',
            'monto' => 12500.00,
            'concepto' => 'Egreso: Adquisición de dinamita, anfo y guía fusible',
            'categoria' => $catExpl->nombre,
            'referencia_tipo' => 'egreso',
            'fecha' => Carbon::today()->subDays(25)->toDateString(),
            'user_id' => $admin->id,
        ]);

        // Egreso 2: Combustible
        $e2 = Egreso::create([
            'caja_id' => $cajaGen->id,
            'categoria_id' => $catComb->id,
            'monto' => 8400.00,
            'concepto' => 'Compra de 2,000 Litros de Diésel Oil para maquinaria y volquetes',
            'fecha' => Carbon::today()->subDays(15)->toDateString(),
            'comprobante_numero' => 'FAC-9401',
            'proveedor' => 'Estación de Servicio Oruro SRL',
            'user_id' => $admin->id,
        ]);
        $cajaGen->saldo_actual -= 8400.00;
        $cajaGen->save();

        CajaMovimiento::create([
            'caja_id' => $cajaGen->id,
            'tipo' => 'egreso',
            'monto' => 8400.00,
            'concepto' => 'Egreso: Compra de 2,000 Litros de Diésel Oil',
            'categoria' => $catComb->nombre,
            'referencia_tipo' => 'egreso',
            'fecha' => Carbon::today()->subDays(15)->toDateString(),
            'user_id' => $admin->id,
        ]);

        // Egreso 3: Repuestos
        $e3 = Egreso::create([
            'caja_id' => $cajaChica->id,
            'categoria_id' => $catMant->id,
            'monto' => 2300.00,
            'concepto' => 'Compra de brocas barrenos y mangueras de alta presión',
            'fecha' => Carbon::today()->subDays(8)->toDateString(),
            'comprobante_numero' => 'FAC-3102',
            'proveedor' => 'Ferretería Industrial El Minero',
            'user_id' => $admin->id,
        ]);
        $cajaChica->saldo_actual -= 2300.00;
        $cajaChica->save();

        CajaMovimiento::create([
            'caja_id' => $cajaChica->id,
            'tipo' => 'egreso',
            'monto' => 2300.00,
            'concepto' => 'Egreso: Compra de brocas barrenos y mangueras',
            'categoria' => $catMant->nombre,
            'referencia_tipo' => 'egreso',
            'fecha' => Carbon::today()->subDays(8)->toDateString(),
            'user_id' => $admin->id,
        ]);

        // 10. Anticipos a Trabajadores
        $antJuan1 = Anticipo::create([
            'trabajador_id' => $juan->id,
            'fecha' => Carbon::today()->subDays(30)->toDateString(),
            'monto' => 1500.00,
            'saldo' => 0.00,
            'pagado' => true,
        ]);

        $antJuan2 = Anticipo::create([
            'trabajador_id' => $juan->id,
            'fecha' => Carbon::today()->subDays(8)->toDateString(),
            'monto' => 1200.00,
            'saldo' => 1200.00,
            'pagado' => false,
        ]);

        $antPedro = Anticipo::create([
            'trabajador_id' => $pedro->id,
            'fecha' => Carbon::today()->subDays(14)->toDateString(),
            'monto' => 800.00,
            'saldo' => 0.00,
            'pagado' => true,
        ]);

        $antLuis = Anticipo::create([
            'trabajador_id' => $luis->id,
            'fecha' => Carbon::today()->subDays(5)->toDateString(),
            'monto' => 1000.00,
            'saldo' => 1000.00,
            'pagado' => false,
        ]);

        // 11. Trabajos Registrados
        $trabJuan1 = Trabajo::create([
            'trabajador_id' => $juan->id,
            'contrato_id' => $contratoJuan->id,
            'fecha' => Carbon::today()->subDays(28)->toDateString(),
            'tipo' => 'metro',
            'cantidad' => 12.00,
            'precio_unitario' => 500.00,
            'subtotal' => 6000.00,
            'observacion' => 'Avance quincena 1 veta norte',
            'pagado' => true,
        ]);

        $trabJuan2 = Trabajo::create([
            'trabajador_id' => $juan->id,
            'contrato_id' => $contratoJuan->id,
            'fecha' => Carbon::today()->subDays(3)->toDateString(),
            'tipo' => 'metro',
            'cantidad' => 10.00,
            'precio_unitario' => 500.00,
            'subtotal' => 5000.00,
            'observacion' => 'Avance quincena 2 nivel 3',
            'pagado' => false,
        ]);

        $trabPedro1 = Trabajo::create([
            'trabajador_id' => $pedro->id,
            'contrato_id' => $contratoPedro->id,
            'fecha' => Carbon::today()->subDays(15)->toDateString(),
            'tipo' => 'volqueta',
            'cantidad' => 20.00,
            'precio_unitario' => 150.00,
            'subtotal' => 3000.00,
            'observacion' => 'Cargado volquetes lote 1',
            'pagado' => true,
        ]);

        $trabPedro2 = Trabajo::create([
            'trabajador_id' => $pedro->id,
            'contrato_id' => $contratoPedro->id,
            'fecha' => Carbon::today()->subDays(2)->toDateString(),
            'tipo' => 'volqueta',
            'cantidad' => 15.00,
            'precio_unitario' => 150.00,
            'subtotal' => 2250.00,
            'observacion' => 'Cargado volquetes lote 2',
            'pagado' => false,
        ]);

        $trabLuis1 = Trabajo::create([
            'trabajador_id' => $luis->id,
            'contrato_id' => $contratoLuis->id,
            'fecha' => Carbon::today()->subDays(4)->toDateString(),
            'tipo' => 'metro',
            'cantidad' => 15.00,
            'precio_unitario' => 300.00,
            'subtotal' => 4500.00,
            'observacion' => 'Enmaderado galería principal',
            'pagado' => false,
        ]);

        // 12. Pagos y Recibos a Personal
        $pagoJuan = Pago::create([
            'trabajador_id' => $juan->id,
            'fecha' => Carbon::today()->subDays(25)->toDateString(),
            'subtotal' => 6000.00,
            'bonos' => 300.00,
            'descuentos' => 100.00,
            'anticipos_descontados' => 1500.00,
            'neto' => 4700.00,
            'observacion' => 'Pago de liquidación quincenal mes pasado.',
        ]);
        $trabJuan1->pago_id = $pagoJuan->id;
        $trabJuan1->save();
        $pagoJuan->anticipos()->attach($antJuan1->id, ['monto_descontado' => 1500.00]);

        $pagoPedro = Pago::create([
            'trabajador_id' => $pedro->id,
            'fecha' => Carbon::today()->subDays(12)->toDateString(),
            'subtotal' => 3000.00,
            'bonos' => 200.00,
            'descuentos' => 50.00,
            'anticipos_descontados' => 800.00,
            'neto' => 2350.00,
            'observacion' => 'Pago de cargado quincena anterior.',
        ]);
        $trabPedro1->pago_id = $pagoPedro->id;
        $trabPedro1->save();
        $pagoPedro->anticipos()->attach($antPedro->id, ['monto_descontado' => 800.00]);

        // 14. Producción Minera Diaria
        ProduccionMinera::create([
            'fecha' => Carbon::today()->subDays(5)->toDateString(),
            'bocamina_id' => $sanjose->id,
            'veta_sector' => 'Veta Principal Nivel 3',
            'tipo_mineral' => 'Complejo (Zn-Pb-Ag)',
            'cargas_extraidas' => 22,
            'toneladas_estimadas' => 55.00,
            'observaciones' => 'Turno día y noche sin interrupciones.',
            'user_id' => $admin->id,
        ]);

        ProduccionMinera::create([
            'fecha' => Carbon::today()->subDays(4)->toDateString(),
            'bocamina_id' => $rosario->id,
            'veta_sector' => 'Galería Central Nivel 5',
            'tipo_mineral' => 'Zinc Concentrado',
            'cargas_extraidas' => 18,
            'toneladas_estimadas' => 45.00,
            'observaciones' => 'Avance óptimo con cargado mecánico.',
            'user_id' => $admin->id,
        ]);

        ProduccionMinera::create([
            'fecha' => Carbon::today()->subDays(3)->toDateString(),
            'bocamina_id' => $santamaria->id,
            'veta_sector' => 'Sección Sur Frente 2',
            'tipo_mineral' => 'Plata / Plomo',
            'cargas_extraidas' => 15,
            'toneladas_estimadas' => 37.50,
            'observaciones' => 'Buen ley de plata observada.',
            'user_id' => $admin->id,
        ]);

        ProduccionMinera::create([
            'fecha' => Carbon::today()->subDays(2)->toDateString(),
            'bocamina_id' => $esperanza->id,
            'veta_sector' => 'Socavón Nivel 1',
            'tipo_mineral' => 'Zinc Bruto',
            'cargas_extraidas' => 20,
            'toneladas_estimadas' => 50.00,
            'observaciones' => 'Despacho directo a chancadora.',
            'user_id' => $admin->id,
        ]);

        ProduccionMinera::create([
            'fecha' => Carbon::today()->subDays(1)->toDateString(),
            'bocamina_id' => $sanjose->id,
            'veta_sector' => 'Veta Principal Nivel 3',
            'tipo_mineral' => 'Complejo (Zn-Pb-Ag)',
            'cargas_extraidas' => 25,
            'toneladas_estimadas' => 62.50,
            'observaciones' => 'RRecord de extracción del mes.',
            'user_id' => $admin->id,
        ]);

        // 15. Préstamos a Socios y Cuotas
        $prestamoDonato = Prestamo::create([
            'numero_prestamo' => 'PRST-2026-001',
            'socio_id' => $socioDonato->id,
            'monto_total' => 12000.00,
            'monto_cuota' => 2000.00,
            'total_cuotas' => 6,
            'cuotas_pagadas' => 2,
            'saldo_pendiente' => 8000.00,
            'fecha_otorgamiento' => Carbon::today()->subDays(60)->toDateString(),
            'estado' => 'activo',
            'observaciones' => 'Préstamo acordado para compra de martillo neumático.',
            'user_id' => $admin->id,
        ]);

        CuotaPrestamo::create([
            'prestamo_id' => $prestamoDonato->id,
            'numero_cuota' => 1,
            'monto_cuota' => 2000.00,
            'fecha_vencimiento' => Carbon::today()->subDays(30)->toDateString(),
            'fecha_pago' => Carbon::today()->subDays(30)->toDateString(),
            'estado' => 'pagado',
        ]);

        CuotaPrestamo::create([
            'prestamo_id' => $prestamoDonato->id,
            'numero_cuota' => 2,
            'monto_cuota' => 2000.00,
            'fecha_vencimiento' => Carbon::today()->subDays(1)->toDateString(),
            'fecha_pago' => Carbon::today()->subDays(1)->toDateString(),
            'estado' => 'pagado',
        ]);

        CuotaPrestamo::create([
            'prestamo_id' => $prestamoDonato->id,
            'numero_cuota' => 3,
            'monto_cuota' => 2000.00,
            'fecha_vencimiento' => Carbon::today()->addDays(29)->toDateString(),
            'estado' => 'pendiente',
        ]);

        $prestamoGerman = Prestamo::create([
            'numero_prestamo' => 'PRST-2026-002',
            'socio_id' => $socioGerman->id,
            'monto_total' => 8000.00,
            'monto_cuota' => 2000.00,
            'total_cuotas' => 4,
            'cuotas_pagadas' => 0,
            'saldo_pendiente' => 8000.00,
            'fecha_otorgamiento' => Carbon::today()->subDays(15)->toDateString(),
            'estado' => 'activo',
            'observaciones' => 'Financiamiento de insumos de explosivos.',
            'user_id' => $admin->id,
        ]);

        CuotaPrestamo::create([
            'prestamo_id' => $prestamoGerman->id,
            'numero_cuota' => 1,
            'monto_cuota' => 2000.00,
            'fecha_vencimiento' => Carbon::today()->addDays(15)->toDateString(),
            'estado' => 'pendiente',
        ]);

        // 16. Distribución de Utilidades
        $distrib = DistribucionUtilidad::create([
            'numero_distribucion' => 'UTIL-2026-Q1',
            'periodo' => 'Primer Trimestre 2026',
            'fecha' => Carbon::today()->subDays(10)->toDateString(),
            'utilidad_bruta_total' => 200000.00,
            'deducciones_reserva' => 20000.00,
            'utilidad_neta_distribuir' => 180000.00,
            'observaciones' => 'Distribución aprobada en Asamblea General ordinaria.',
            'user_id' => $admin->id,
        ]);

        DetalleUtilidadSocio::create([
            'distribucion_utilidad_id' => $distrib->id,
            'socio_id' => $socioDonato->id,
            'porcentaje_participacion' => 35.00,
            'monto_utilidad' => 63000.00,
            'estado' => 'pagado',
        ]);

        DetalleUtilidadSocio::create([
            'distribucion_utilidad_id' => $distrib->id,
            'socio_id' => $socioGerman->id,
            'porcentaje_participacion' => 30.00,
            'monto_utilidad' => 54000.00,
            'estado' => 'pagado',
        ]);

        DetalleUtilidadSocio::create([
            'distribucion_utilidad_id' => $distrib->id,
            'socio_id' => $socioBenjamin->id,
            'porcentaje_participacion' => 20.00,
            'monto_utilidad' => 36000.00,
            'estado' => 'pendiente',
        ]);

        DetalleUtilidadSocio::create([
            'distribucion_utilidad_id' => $distrib->id,
            'socio_id' => $socioTeofilo->id,
            'porcentaje_participacion' => 15.00,
            'monto_utilidad' => 27000.00,
            'estado' => 'pendiente',
        ]);

        // 17. Contabilidad General (Plan de Cuentas, Asientos y Detalle de Asientos)
        $cuentaCaja = CuentaContable::create(['codigo' => '1.1.01', 'nombre' => 'Caja General Moneda Nacional', 'tipo' => 'activo', 'nivel' => 3]);
        $cuentaBanco = CuentaContable::create(['codigo' => '1.1.02', 'nombre' => 'Bancos M/N BNB', 'tipo' => 'activo', 'nivel' => 3]);
        $cuentaVenta = CuentaContable::create(['codigo' => '4.1.01', 'nombre' => 'Venta de Mineral Concentrado', 'tipo' => 'ingreso', 'nivel' => 3]);
        $cuentaRegalia = CuentaContable::create(['codigo' => '5.1.01', 'nombre' => 'Costo de Regalía Minera SENARECOM', 'tipo' => 'gasto', 'nivel' => 3]);
        $cuentaPlanilla = CuentaContable::create(['codigo' => '5.1.02', 'nombre' => 'Sueldos y Salarios Personal Minero', 'tipo' => 'gasto', 'nivel' => 3]);

        // Asiento 1: Registro Venta de Mineral
        $ast1 = AsientoContable::create([
            'numero_asiento' => 'AST-202607-001',
            'fecha' => Carbon::today()->subDays(20)->toDateString(),
            'glosa' => 'Registro de venta de lote de mineral concentrado a Empresa Metalúrgica Vinto',
            'debe_total' => 68500.00,
            'haber_total' => 68500.00,
            'user_id' => $admin->id,
        ]);

        DetalleAsientoContable::create([
            'asiento_contable_id' => $ast1->id,
            'cuenta_contable_id' => $cuentaBanco->id,
            'debe' => 68500.00,
            'haber' => 0.00,
        ]);
        DetalleAsientoContable::create([
            'asiento_contable_id' => $ast1->id,
            'cuenta_contable_id' => $cuentaVenta->id,
            'debe' => 0.00,
            'haber' => 68500.00,
        ]);

        // Asiento 2: Pago de Planilla
        $ast2 = AsientoContable::create([
            'numero_asiento' => 'AST-202607-002',
            'fecha' => Carbon::today()->subDays(12)->toDateString(),
            'glosa' => 'Liquidación y pago de planillas de sueldos a personal de mina',
            'debe_total' => 15000.00,
            'haber_total' => 15000.00,
            'user_id' => $admin->id,
        ]);

        DetalleAsientoContable::create([
            'asiento_contable_id' => $ast2->id,
            'cuenta_contable_id' => $cuentaPlanilla->id,
            'debe' => 15000.00,
            'haber' => 0.00,
        ]);
        DetalleAsientoContable::create([
            'asiento_contable_id' => $ast2->id,
            'cuenta_contable_id' => $cuentaCaja->id,
            'debe' => 0.00,
            'haber' => 15000.00,
        ]);
    }
}
