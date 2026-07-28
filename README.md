# 💎 SCP MINERO - Sistema Enterprise de Control de Pagos y Gestión Minera

[![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![TailwindCSS](https://img.shields.io/badge/Tailwind_CSS-38BDF8?style=for-the-badge&logo=tailwind-css&logoColor=white)](https://tailwindcss.com)
[![Alpine.js](https://img.shields.io/badge/Alpine.js-8BC0D0?style=for-the-badge&logo=alpine.js&logoColor=black)](https://alpinejs.dev)
[![Chart.js](https://img.shields.io/badge/Chart.js-FF6384?style=for-the-badge&logo=chartdotjs&logoColor=white)](https://www.chartjs.org/)

**SCP MINERO** es un sistema ERP de grado empresarial de estándar internacional (*Stripe / Linear / Vercel Aesthetic*) diseñado para la administración integral, liquidación de pagos, control financiero, comercialización de minerales y contabilidad general de empresas y cooperativas mineras.

---

## 🌟 Diseño & Arquitectura UI/UX Premium
- **Tema Visual**: Inspirado en degradados elegantes Celeste/Cyan (`#38BDF8`, `#0EA5E9`), Glassmorphism con bordes translúcidos neón y tarjetas elevadas (`backdrop-filter: blur(20px)`).
- **Tipografía**: Fuentes modernas Google Fonts **Plus Jakarta Sans** e **Inter**.
- **Navegación Organizativa**: Dividida en **4 Módulos Principales** desplegables con indicador de sección activa.
- **Navbar Superior SaaS**: Atajo de búsqueda global (`Ctrl + K`), Breadcrumbs dinámicos, notificaciones y reloj en vivo.
- **Layout Inmunizado**: Sistema de Flexbox de dos columnas independientes que previene cualquier solapamiento.

---

## 🗂️ Estructura de Módulos del Sistema

### 📊 1. Módulo de Reportes & Tablero
- **Tablero Ejecutivo Principal**: Monitoreo en tiempo real de ventas acumuladas, saldo en caja general, volumen de producción extraída y utilidad neta estimada.
- **Gráficos Estadísticos Interactivos**: Gráficos de producción acumulada por bocamina y curva histórica de desembolsos en pagos.
- **Reportes Generales**: Reportes consolidados exportables e imprimibles.

### 🟢 2. Módulo de Ingresos Económicos
- **Ventas de Cargas de Mineral**: Registro de liquidaciones de comercialización de lotes de mineral (Complejo Zn-Pb-Ag, Zinc, Plata) con peso neto, precio unitario y comprador.
- **Ingresos Operativos**: Registro de cobros y abonos a caja general.
- **Compradores de Mineral**: Padrón y catálogo de empresas metalúrgicas y comercializadoras clientes.

### 🔴 3. Módulo de Egresos y Gastos
- **Pagos y Recibos a Personal**: Asistente de liquidación salarial por avance de metro, volquetas o sueldo fijo con bonos, descuentos y amortización automática de anticipos.
- **Anticipos & Adelantos**: Otorgamiento y seguimiento de saldos pendientes de adelantos a trabajadores y socios.
- **Egresos y Gastos Operativos**: Control de compras de insumos (herramientas, repuestos, alimentos, mantenimiento y servicios) clasificados por categoría.
- **Caja General y Cajas Chicas**: Control de saldos en efectivo, arqueos y libro de movimientos de caja.

### ⚙️ 4. Módulo de Administración
- **Personal y Trabajadores**: Padrón de perforistas, cargadores, capataces, choferes y serenos con su asignación a bocaminas.
- **Contratos Mineros**: Gestión de contratos por metraje de avance, cargado de volquetas o por producción.
- **Socios Cooperativistas**: Registro de socios con porcentaje de participación sobre utilidades y asignación de bocamina.
- **Bocaminas**: Administración de frentes de explotación, vetas y niveles socavón.
- **Producción Minera Diaria**: Registro de volumen de cargas extraídas y tonelaje estimado diario por sector.
- **Préstamos & Créditos a Socios**: Asignación de préstamos financieros con generación automática de planes de cuotas.
- **Distribución de Utilidades**: Liquidación periódica de utilidades netas distribuidas según el porcentaje de participación de cada socio.
- **Contabilidad General**: Plan de Cuentas estándar (Activos, Pasivos, Patrimonio, Ingresos, Gastos) y Libro Diario de Asientos Contables balanceados.

---

## 🗄️ Sembrado de Datos Demostrativos (`Seeders`)

El proyecto cuenta con un **DatabaseSeeder completo y autosuficiente** (`DatabaseSeeder.php`) que puebla toda la base de datos con información altamente realista de la operación minera:

### Datos sembrados automáticamente:
- **Usuario Administrador**: `admin@mina.com` / `admin123`
- **5 Bocaminas**: *San José (Nivel 3), Rosario (Nivel 5), Santa María, La Esperanza, San Antonio*.
- **4 Socios Cooperativistas**: *Donato Quispe (35%), Germán Morales (30%), Benjamín Gutiérrez (20%), Teófilo Alarcón (15%)*.
- **7 Trabajadores**: *Perforistas, cargadores, enmaderadores, choferes, serenos y administradores*.
- **4 Contratos Mineros**: *Metraje de avance en veta, volquetas cargadas y disparos*.
- **4 Compradores de Mineral**: *Vinto S.A., Baremsa Ltda., San Cristóbal S.A., Minerales del Sur*.
- **4 Ventas de Mineral**: *Transacciones de cargas por un total superior a Bs. 350,000*.
- **2 Cajas Operativas**: *Caja General Central (Bs. 150,000) y Caja Chica Mina (Bs. 25,000)*.
- **3 Egresos Operativos**: *Facturas de mantenimiento, repuestos e insumos industriales del minero*.
- **5 Registros Diarios de Producción Minera**.
- **2 Préstamos a Socios** con tablas de amortización de cuotas.
- **1 Distribución de Utilidades** repartida entre los socios.
- **Plan de Cuentas y Asientos Contables Balanceados**.

---

## 🛠️ Instalación y Configuración Local

1. **Clonar el repositorio**:
   ```bash
   git clone https://github.com/Alfred67605/pagos_minera.git
   cd pagos_minera
   ```

2. **Instalar dependencias de PHP**:
   ```bash
   composer install
   ```

3. **Configurar el archivo `.env`**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configurar la Base de Datos** (PostgreSQL o MySQL en `.env`):
   ```env
   DB_CONNECTION=pgsql
   DB_HOST=127.0.0.1
   DB_PORT=5432
   DB_DATABASE=pagos
   DB_USERNAME=postgres
   DB_PASSWORD=tu_contraseña
   ```

5. **Ejecutar Migraciones y Poblado de Datos (Seeders)**:
   ```bash
   php artisan migrate:fresh --seed
   ```

6. **Iniciar el Servidor de Desarrollo**:
   ```bash
   php artisan serve
   ```

7. **Acceso al Sistema**:
   - **URL**: `http://127.0.0.1:8000`
   - **Usuario**: `admin@mina.com`
   - **Contraseña**: `admin123`

---

## 📄 Licencia
Este proyecto es software privado de gestión minera empresarial.
