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

## 🌐 Guía de Despliegue en Hosting Compartido (cPanel sin acceso a SSH)

Si no dispones de acceso a la terminal (SSH) en tu servidor cPanel, sigue estas instrucciones paso a paso:

### Fase 1: Preparación en Local
Dado que no puedes ejecutar comandos en el servidor, debes preparar todo tu proyecto en local antes de subirlo:

1. En tu máquina local, asegúrate de que el archivo `.env` está configurado para producción:
   - `APP_ENV=production`
   - `APP_DEBUG=false`
   - Configura las credenciales correctas de base de datos que crearás en cPanel.
2. Ejecuta la compilación de recursos estáticos en tu máquina local:
   ```bash
   npm run build
   ```
3. Comprime todo el contenido de la carpeta de tu proyecto (incluyendo todos los archivos ocultos como `.env`) en un archivo **`.zip`**.

### Fase 2: Subida y Configuración en cPanel
1. Accede a **cPanel > Administrador de Archivos (File Manager)**.
2. Sube el archivo `.zip` al directorio de tu servidor, un nivel por encima de `public_html` (ej. `/home/tu_usuario/placapp_web/`).
3. Extrae el archivo `.zip` en ese directorio.
4. Entra a la carpeta `/home/tu_usuario/placapp_web/public/`, **selecciona todos los archivos** y muévelos adentro de la carpeta `/home/tu_usuario/public_html/` (la carpeta pública del servidor).
5. Como moviste el archivo `index.php`, debes actualizar sus rutas correspondientes. Edita `/home/tu_usuario/public_html/index.php`:
   - Cambia `require __DIR__.'/../storage/framework/maintenance.php';`
     a `require __DIR__.'/../placapp_web/storage/framework/maintenance.php';`
   - Cambia `require __DIR__.'/../vendor/autoload.php';`
     a `require __DIR__.'/../placapp_web/vendor/autoload.php';`
   - Cambia `$app = require_once __DIR__.'/../bootstrap/app.php';`
     a `$app = require_once __DIR__.'/../placapp_web/bootstrap/app.php';`

### Fase 3: Creación de la Base de Datos y Optimización
1. En tu panel de control, dirígete a **cPanel > Bases de Datos MySQL**.
   - Haz clic en **Crear nueva base de datos**.
   - Haz clic en **Crear usuario** (y guarda la contraseña de forma segura).
   - Añade el usuario a la base de datos para darle **Todos los privilegios**.
   - Anota el nombre de la base de datos y usuario (ej. `tu_usuario_placapp_db`) y actualízalos en tu archivo `.env`.
2. Como no puedes ejecutar `php artisan migrate`, entra en tu base de datos local usando un programa como phpMyAdmin (o DBeaver), exporta las tablas (archivo `.sql`) e **infíltralas/impórtalas** directamente en la base de datos recién creada usando phpMyAdmin de cPanel.
3. (Opcional) Crea un script cron en cPanel si la aplicación maneja tareas en segundo plano.

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
