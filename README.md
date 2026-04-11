# PlacApp Web

PlacApp Web es una plataforma desarrollada en Laravel diseñada para la gestión y monitoreo de placas (`plates`), vinculación segura de usuarios mediante `uuids` y manejo de acceso por tokens (`personal_access_tokens`).

## 📋 Auditoría de Código y Migraciones

El proyecto es una aplicación web basada en el framework Laravel. Durante la auditoría se revisaron las migraciones personalizadas que estructuran la base de datos de la plataforma:

1. **`plates`**: Almacena el registro principal de placas.
   - Campos destacados: `plate_name` (placa), `plate_desc` (descripción), `plate_entry_date` (fecha de entrada), `plate_exit_date` (fecha de salida), `plate_level` (nivel de alerta o acceso, por defecto 3), `plate_location` y `plate_detail`.
2. **`plates_demo`**: Tabla idéntica a `plates` pero destinada a entornos de prueba, demostraciones o registros no oficiales. El `plate_level` por defecto es 4.
3. **`uuids`**: Tabla de vinculación que asocia identificadores únicos universales (UUID) reales o generados, directamente a un usuario (`user_id` en cascada). Incluye un estado (`status`) para habilitar o deshabilitar la vinculación.
4. **`personal_access_tokens`**: Utilizada por Laravel Sanctum para la autenticación basada en tokens (API), permitiendo accesos seguros desde aplicaciones móviles o clientes externos.

## 🚀 Guía de Despliegue Local (Localhost)

Para ejecutar este proyecto en tu entorno de desarrollo local, sigue estos pasos:

1. **Clonar el Repositorio** (o descargar el código fuente):
   ```bash
   git clone <url-del-repositorio>
   cd placapp_web
   ```

2. **Instalar Dependencias de PHP y Node**:
   ```bash
   composer install
   npm install && npm run build
   ```

3. **Configurar las Variables de Entorno y la Base de Datos**:
   - Asegúrate de tener instalado tu motor de base de datos local (MySQL, MariaDB o PostgreSQL).
   - Crea una nueva base de datos vacía. Puedes usar un cliente de interfaz gráfica (como phpMyAdmin o DBeaver) o mediante terminal con: `CREATE DATABASE placapp_db;`.
   - Copia el archivo `.env.example` y renómbralo a `.env`.
   - Modifica las credenciales de base de datos (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`) en el `.env` con los datos correspondientes.

4. **Generar la Clave de la Aplicación**:
   ```bash
   php artisan key:generate
   ```

5. **Ejecutar Migraciones y Poblado (Seeders)**:
   ```bash
   php artisan migrate
   ```

6. **Iniciar el Servidor de Desarrollo**:
   ```bash
   php artisan serve
   ```
   La aplicación estará disponible en `http://localhost:8000`.

---

## 🌐 Guía de Despliegue Maestro (Hosting Compartido sin SSH)

Esta guía está diseñada para un despliegue manual mediante **FTP o Administrador de Archivos Web**, optimizando el rendimiento en local antes de la subida.

### Fase 1: Preparación "Clean Slate" (En tu PC Local)
Antes de comprimir el proyecto, debemos limpiar el entorno y optimizarlo para producción. Abre una terminal en tu proyecto local y ejecuta:

1. **Limpieza total de Caches:**
   ```bash
   php artisan optimize:clear
   ```
2. **Dependencias de Producción:** (Elimina herramientas de testeo y debug para mayor ligereza):
   ```bash
   composer install --no-dev --optimize-autoloader
   ```
3. **Optimización de Carga:** (Genera archivos de caché que el servidor usará directamente):
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

### Fase 2: El Paquete ZIP Perfecto
No todo lo que tienes en tu PC debe ir al servidor. Aquí tienes el checklist de lo que debes incluir en tu archivo `placapp_web.zip`:

**✅ LO QUE SÍ DEBES INCLUIR:**
- `app/`, `bootstrap/`, `config/`, `database/`
- `lang/`, `resources/`, `routes/`, `vendor/` (¡Indispensable!)
- `storage/` (Asegúrate de que `storage/framework/views` esté vacío pero que la carpeta exista).
- `public/` (Contiene tus estilos CSS y JS nativos).
- `.env` y `.env.example`
- `artisan`, `composer.json`, `index.php`

**❌ LO QUE DEBES OMITIR (Excluir del ZIP):**
- `node_modules/` (Es pesado y no se usa en producción).
- `.git/` y archivos `.gitignore` / `.gitattributes`.
- `tests/` (No ejecutamos pruebas en hosting compartido).
- Archivos `.log` viejos en `storage/logs/`.

---

### Fase 3: Estructura de Carpetas en el Servidor (Método Seguro)
Para proteger tu código, utilizaremos el método de **Archivos sobre Raíz**. Tu Administrador de Archivos debería verse así:

```text
/home/tu_usuario/
├── placapp_core/        <-- (Aquí extraes TODO el contenido del ZIP, excepto la carpeta public)
└── public_html/         <-- (Aquí mueves solo el CONTENIDO de la carpeta public)
    ├── css/
    ├── js/
    ├── img/
    ├── .htaccess        <-- (El guardián del sitio)
    └── index.php        <-- (El punto de entrada)
```

### Fase 4: Configuración de Rutas (index.php)
Edita el archivo `public_html/index.php` y ajusta las rutas para que apunten a tu carpeta core. Busca y modifica estas **3 líneas críticas**:

```php
// 1. Modificar la ruta del mantenimiento (Línea ~34)
if (file_exists($maintenance = __DIR__.'/../placapp_core/storage/framework/maintenance.php')) {
    require $maintenance;
}

// 2. Modificar el Autoload de Composer (Línea ~47)
require __DIR__.'/../placapp_core/vendor/autoload.php';

// 3. Modificar el punto de inicio de la App (Línea ~61)
$app = require_once __DIR__.'/../placapp_core/bootstrap/app.php';
```

### Fase 5: El Archivo .htaccess y Seguridad
Asegúrate de que tu archivo `public_html/.htaccess` tenga estas reglas para el manejo correcto de rutas de Laravel y seguridad:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    # Forzar HTTPS si tu hosting tiene SSL
    RewriteCond %{HTTPS} off
    RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

    # Regla base de Laravel
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

### Fase 6: Variables de Entorno (.env)
Edita el archivo `/placapp_core/.env` directamente en el Administrador de Archivos:
- `APP_ENV=production`
- `APP_DEBUG=false`
- `APP_URL=https://tudominio.com`
- `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`: Datos que creaste en tu panel de MySQL.

---

### Fase 7: Base de Datos (phpMyAdmin)
1. **Local:** Ve a tu phpMyAdmin local, selecciona la DB y haz clic en **Exportar** (Método rápido, formato SQL).
2. **Servidor:** En cPanel, crea la DB y el usuario, entra a phpMyAdmin, selecciona la nueva DB y haz clic en **Importar**. Sube tu archivo `.sql`.

¡Listo! Con este flujo manual, tu PlacApp Web estará optimizada, segura y funcionando al 100% sin necesidad de tocar una consola en el servidor.

¡Con esto, tu PlacApp Web estará activa y segura en tu Hosting compartido!

---

## 💻 Guía de Despliegue en VPS (Ubuntu / Debian con SSH)

Si tienes un servidor privado o cloud propio con acceso raíz (SSH), sigue estos pasos para desplegar a nivel profesional:

1. **Acceder a tu VPS por SSH**: `ssh usuario@tu_ip_del_vps`
2. **Crear la Base de Datos (ej. MySQL/MariaDB)**:
   ```bash
   mysql -u root -p
   ```
   ```sql
   CREATE DATABASE placapp_db;
   CREATE USER 'placapp_user'@'localhost' IDENTIFIED BY 'tu_contraseña_segura';
   GRANT ALL PRIVILEGES ON placapp_db.* TO 'placapp_user'@'localhost';
   FLUSH PRIVILEGES;
   EXIT;
   ```
3. **Clonar e Instalar el Proyecto**:
   - Clona el repositorio en `/var/www/placapp_web`.
   - Copia `.env.example` a `.env` e ingresa la base de datos creada en el paso 2.
   - Ejecuta `composer install`, luego `php artisan key:generate` y **`php artisan migrate`**.
   - Ejecuta `npm install` y `npm run build`.
4. **Permisos y Servidor Web**:
   - Configura Nginx o Apache apuntando el VirtualHost directamente a la carpeta `public/` del proyecto.
   - Asigna permisos en Laravel para que el servidor web pueda operar:
   ```bash
   sudo chown -R www-data:www-data /var/www/placapp_web/storage /var/www/placapp_web/bootstrap/cache
   ```

¡Con estos pasos tu aplicación PlacApp Web debería estar corriendo y migrando óptimamente en el escenario que elijas!
