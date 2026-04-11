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

## 🌐 Guía de Despliegue en Hosting Compartido (FTP / Admin. de Archivos)

Si planeas desplegar en un hosting tradicional (cPanel, Plesk, etc.) donde no tienes acceso a terminal (SSH), sigue estos pasos detallados para asegurar que Laravel funcione correctamente:

### Fase 1: Preparación local
Como no se pueden ejecutar comandos en el servidor, debemos preparar el "corazón" de la app en tu PC:
1. **Limpieza de Caché:** Antes de subir, limpia las rutas y configuraciones locales para evitar errores de rutas absolutas:
   - Elimina manualmente los archivos dentro de `storage/framework/views/` (pero no la carpeta).
   - Elimina los archivos dentro de `storage/framework/sessions/`.
   - Elimina `bootstrap/cache/config.php` y `bootstrap/cache/routes.php` si existen.
2. **Dependencias:** Asegúrate de que la carpeta `vendor` esté completa (después de un `composer install`). No necesitas subir la carpeta `node_modules`.
3. **Recursos Estáticos:** El proyecto ya cuenta con sus estilos y JS listos en la carpeta `public/`, por lo que **no es necesario ejecutar npm install o npm build**.
4. **Comprimir:** Comprime todo el contenido en un archivo `.zip` (incluyendo archivos ocultos como `.env`).

### Fase 2: Subida y Estructura de Carpetas (Método Seguro)
Para evitar que el código fuente sea accesible desde la web, te recomiendo esta estructura:

1. **Subida:** Sube tu `.zip` a la raíz de tu hosting (un nivel ARRIBA de la carpeta `public_html` o `www`).
2. **Extracción:** Extrae el contenido en una carpeta dedicada (ejemplo: `/hosting/usuario/placapp_core/`).
3. **Punto de Entrada:** 
   - Entra a la carpeta `placapp_core/public/` y **mueve todos sus archivos** (incluyendo el `.htaccess` e `index.php`) a la carpeta pública del servidor (`public_html/`).
   - Ahora tu código fuente está seguro en `placapp_core/` y solo el punto de entrada es público en `public_html/`.

### Fase 3: Ajuste Crítico de Rutas (El paso más importante)
Como moviste el archivo `index.php`, debes decirle dónde encontrar el núcleo de Laravel. Edita `public_html/index.php` y ajusta las siguientes líneas:

```php
// Cambia estas líneas (aproximadamente líneas 34 y 47)
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';

// Por estas (ajustando al nombre de tu carpeta):
require __DIR__.'/../placapp_core/vendor/autoload.php';
$app = require_once __DIR__.'/../placapp_core/bootstrap/app.php';
```

### Fase 4: Configuración final y Base de Datos
1. **Archivo .env:** Edita el archivo `.env` dentro de `placapp_core/`:
   - `APP_ENV=production`
   - `APP_DEBUG=false` (¡Crítico para seguridad!)
   - `APP_URL=https://tudominio.com`
   - Ingresa las credenciales de la base de datos que creaste en tu panel (cPanel MySQL).
2. **Migración sin SSH:** Como no puedes ejecutar `artisan migrate`:
   - **Opción phpMyAdmin:** Exporta tu base de datos local como archivo `.sql` e impórtala usando phpMyAdmin en tu hosting.
   - **Opción de Emergencia:** Puedes crear temporalmente una ruta en `web.php` para ejecutar las migraciones:
     ```php
     Route::get('/run-migrations', function() {
         Artisan::call('migrate');
         return "Tablas creadas con éxito";
     });
     ```
3. **Permisos:** Asegúrate de que las carpetas `placapp_core/storage/` y `placapp_core/bootstrap/cache/` tengan permisos de escritura (normalmente 755 o 775).

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
