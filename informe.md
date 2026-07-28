# 📊 Informe Técnico del Sistema de Control de Pagos Mineros (SCPM)

## 📌 1. Resumen Ejecutivo

El **Sistema de Control de Pagos Mineros (SCPM)** es una plataforma web integral desarrollada con **Laravel 11, PostgreSQL, TailwindCSS y Alpine.js**, diseñada específicamente para la gestión operativa, financiera, de comercialización y tributaria de empresas y cooperativas mineras en Bolivia.

El sistema digitaliza y automatiza el ciclo completo de la cadena de valor minera: desde la producción en bocamina, pesajes en balanza, ensayos químicos de laboratorio, comerciales de mineral con Formulario SENARECOM M-02, emisión e impresión de recibos y comprobantes, tesorería de cajas y bancos, hasta la contabilidad general en Libro Diario y reparto de utilidades a socios.

---

## 🛠️ 2. Módulos del Sistema e Implementación Completa por Fases

### 🔹 Fase 1: Caja General, Egresos, Tesorería y Compradores (COMPLETADA)
1. **Compradores de Mineral (`compradores`)**: Padrón de ingenios, comercializadoras y refinerías (NIT, RUIM, REX, representante, dirección, teléfono).
2. **Caja General & Arqueos (`cajas`, `caja_movimientos`)**: Apertura y cierre diario de cajas con arqueo físico (billetes/monedas), ingresos, egresos y control de descuadres.
3. **Egresos & Gastos Operativos (`categoria_egresos`, `egresos`)**: Clasificación de gastos por categorías (combustible, repuestos, viáticos, herramientas), proveedores y comprobantes.
4. **Tesorería & Cuentas Bancarias (`cuentas_bancarias`, `movimientos_bancarios`)**: Registro de cuentas corrientes/ahorros, conciliación bancaria y transferencias entre cajas y bancos.

### 🔹 Fase 2: Pesaje, Laboratorio y Liquidación Avanzada SENARECOM M-02 (COMPLETADA)
1. **Pesaje en Balanza (`pesajes`)**: Tickets de entrada con Peso Bruto, Tara del Camión, Humedad ($H_2O\%$) y cálculo automático de **Peso Seco (Tn)**.
2. **Ensayos de Laboratorio Químico (`ensayos_laboratorio`)**: Registro de boletines de laboratorio con leyes físicas ($Ag, Au, Zn, Pb, Sn, Cu$) e impurezas penalizables ($As, Sb, Bi$).
3. **Liquidación Mineral SENARECOM M-02 (`liquidaciones_minerales`)**:
   * Algoritmo de **Valor Bruto de Venta (VBV)** según cotizaciones internacionales en USD y tipo de cambio.
   * Deducciones comerciales de Maquila y Penalizaciones por impurezas.
   * **Cálculo de Regalía Minera (Formulario M-02)** según alícuotas oficiales de Ley Minera.
   * Retenciones de aportes a COMIBOL y Caja Nacional de Salud (CNS).
   * **Generación de Formulario M-02 Imprimible** oficial con firmas de conformidad.
   * Integración directa con las Ventas de Cargas y los Ingresos Económicos.

### 🔹 Fase 3: Producción Minera, Recibos y Descuentos por Avance (COMPLETADA)
1. **Producción Minera Diaria (`producciones_mineras`)**: Control diario del volumen de cargas extraídas, humedad, ley estimada y tonelaje neto por bocamina.
2. **Generación e Impresión de Recibos (`anticipos`, `ventas_cargas`)**: Emisión de recibos de venta de cargas, vales de anticipos y comprobantes oficiales de pago con firma de conformidad.
3. **Control de Frentes de Explotación & Bocaminas (`bocaminas`)**: Registro de sectores de trabajo, bocaminas asignadas y trazabilidad por cuadrilla o trabajador.
4. **Registro y Asignación de Contratos (`contratos`)**: Configuración de modalidades de trabajo (avance por metro, tonelada o sueldo fijo) con cálculo automático de saldos líquidamente pagables.

### 🔹 Fase 4: Contabilidad, Utilidades, Préstamos y Dashboard Ejecutivo (COMPLETADA)
1. **Préstamos & Créditos (`prestamos`, `cuotas_prestamo`)**: Otorgamiento de préstamos corporativos a socios o cuadrillas de trabajadores, tabla de amortización en cuotas y cobranza programada.
2. **Distribución de Utilidades & Dividendos (`distribucion_utilidades`, `detalle_utilidad_socios`)**: Cálculo de utilidades netas del periodo, reservas legales y reparto automático de dividendos según el porcentaje de participación de cada socio.
3. **Contabilidad General & Libro Diario (`cuentas_contables`, `asientos_contables`, `detalle_asientos`)**: Plan de cuentas estructurado por niveles, registro de asientos contables balanceados (Debe vs. Haber) y balance de comprobación de sumas y saldos.
4. **Dashboard Ejecutivo Global (`dashboard`)**: Consolidador estratégico con KPIs macro en tiempo real (Ventas acumuladas, Regalía Minera SENARECOM M-02 pagada, Tonelaje total producido, Utilidad Neta corporativa, saldos en Tesorería y gráficos comparativos).

---

## 🏛️ 3. Arquitectura Técnica del Proyecto

* **Framework**: Laravel 11.x (PHP 8.2+)
* **Base de Datos**: PostgreSQL / SQLite (Migraciones y Seeders 100% compatibles)
* **Frontend Design**: TailwindCSS v3 con estética *Modern Glassmorphism* (Modo Oscuro, partículas en canvas, colores HSL armonizados y tipografía Inter).
* **Componentes Reactivos**: Alpine.js para modales, calculadoras en tiempo real y componentes interactivos sin recargas.
* **Seguridad & Confidencialidad**: Encriptación de contraseñas con hashing `Bcrypt`, middleware `auth`, control de acceso por roles, auditoría de transacciones y copias de seguridad automáticas para máxima protección de la información financiera.

---

## 📄 4. Documentos de Referencia Generados
* **[README.md](file:///c:/Users/User2/Downloads/sistema-pagos/README.md)**: Guía general y manual de usuario del sistema.
* **[walkthrough.md](file:///C:/Users/User2/.gemini/antigravity-ide/brain/7f42872c-3245-4575-b465-9f34c04b44ca/walkthrough.md)**: Historial completo de cambios y archivos construidos en las 4 Fases.
