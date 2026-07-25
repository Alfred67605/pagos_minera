# ⛏️ SCPM - Sistema de Control de Pagos Mineros

Sistema web desarrollado con **Laravel 11**, **TailwindCSS**, **Vite** y **Alpine.js** para la gestión y control de pagos a personal minero, bocaminas, contratos, saldos pendientes y anticipos.

---

## 📋 Requisitos Previos

Antes de comenzar, asegúrate de tener instalado en tu computadora:

* **PHP** >= 8.2 (con extensiones `pdo_pgsql`, `mbstring`, `openssl`, `curl`)
* **Composer** (gestor de dependencias de PHP)
* **Node.js** >= 18.x y **NPM**
* **Git**

---

## 🚀 Pasos para Clonar e Instalar el Proyecto

Sigue estos pasos en orden para levantar el sistema localmente desde cero:

### 1. Clonar el Repositorio
Abre tu terminal y ejecuta:
```bash
git clone https://github.com/lucybeltran/sistema-pagos.git
cd sistema-pagos
```

### 2. Instalar Dependencias de PHP (Composer)
```bash
composer install
```

### 3. Instalar Dependencias de JavaScript (NPM)
```bash
npm install
```

### 4. Configurar el Archivo de Entorno (`.env`)
Copia el archivo de ejemplo para crear tu archivo `.env`:
* **Windows (PowerShell):**
  ```powershell
  copy .env.example .env
  ```
* **Linux / macOS / Git Bash:**
  ```bash
  cp .env.example .env
  ```

### 5. Generar la Clave de la Aplicación (App Key)
```bash
php artisan key:generate
```

### 6. Configurar y Preparar la Base de Datos (PostgreSQL)

Configura las variables de base de datos en tu archivo `.env` (asegúrate de que la base de datos `pagos` esté creada en PostgreSQL):

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=pagos
DB_USERNAME=postgres
DB_PASSWORD=admin
```

Ejecuta las migraciones y puebla la base de datos con los datos iniciales y el usuario administrador:
```bash
php artisan migrate:fresh --seed
```

### 7. Compilar los Assets (CSS / JS con Vite)

Para compilar los estilos y scripts en modo desarrollo:
```bash
npm run dev
```
*(Opcional: Si quieres compilar para producción, ejecuta `npm run build`)*

### 8. Iniciar el Servidor de Desarrollo (Laravel)

En otra ventana de la terminal, ejecuta:
```bash
php artisan serve
```

El sistema estará disponible en: **`http://127.0.0.1:8000`**

---

## 🔑 Credenciales de Acceso por Defecto

Al ejecutar el seeder (`php artisan db:seed`), se creará automáticamente la cuenta del administrador:

* **Correo Electrónico:** `admin@mina.com`
* **Contraseña:** `admin123`

---

## ✨ Características Principales

* **Gestión de Bocaminas**: Registro y administración de áreas de trabajo y sectores mineros.
* **Control de Trabajadores / Contratistas**: Registro con validaciones avanzadas de CI, nombres con mayúsculas iniciales y teléfono.
* **Gestión de Contratos**: Definición de contratos por metros, volquetas, sacos o cajas con avance automático.
* **Liquidación de Pagos & Saldos**:
  * Pagos totales y pagos parciales (con saldos pendientes para la siguiente planilla).
  * Generación automática de anticipos por sobrantes de efectivo entregados.
  * Justificación / observación obligatoria en caso de registrar descuentos.
* **Historial de Anticipos (Solo Lectura)**: Log histórico de adelantos con filtros por Bocamina y por Trabajador.
* **Impresión de Recibos**: Emisión e impresión de recibos formales de pagos y anticipos.
* **Dashboard y Reportes**: Reportes detallados por fecha, saldos pendientes e historial financiero.

---

## 🛠️ Tecnologías Utilizadas

* **Backend**: Laravel 11.x (PHP 8.2+)
* **Frontend**: Blade, Tailwind CSS 4.0, Alpine.js
* **Build Tool**: Vite 8.x
* **Base de Datos**: PostgreSQL (compatible con SQLite / MySQL)

