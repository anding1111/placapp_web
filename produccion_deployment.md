# 🚀 Guía Maestra de Despliegue - PlacApp (raptor.mipos.pro)

Esta guía detalla el proceso para desplegar la nueva versión de **PlacApp Web** en el subdominio `raptor.mipos.pro`, utilizando el **Método del Núcleo Protegido** para máxima seguridad.

---

## 📋 Fase 1: Preparación Local (Puesta a Punto)

Antes de subir nada, debemos optimizar el proyecto en tu PC local.

1. **Limpieza y Optimización:**
   ```bash
   php artisan optimize:clear
   composer install --no-dev --optimize-autoloader
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```
2. **Creación del ZIP:** Crea un archivo `placapp_web.zip` incluyendo los siguientes elementos:
   - **✅ INCLUIR:** `app/`, `bootstrap/`, `config/`, `database/`, `lang/`, `public/`, `resources/`, `routes/`, `storage/`, `vendor/`, `.env`, `artisan`, `composer.json`, `composer.lock`.
   - **❌ OMITIR:** `node_modules/`, `.git/`, `.github/`, `tests/`, `stubs/`, `phpunit.xml`, `vite.config.js`, `package.json`, `.DS_Store`.

---

## 🏢 Fase 2: Configuración en el Servidor (raptor.mipos.pro)

Seguiremos el método de **"Archivos fuera del Directorio Público"**.

### 1. Limpieza del Subdominio
Accede a tu Administrador de Archivos y **borra todo** lo que hay dentro de la carpeta:
`/home/mipospro/public_html/raptor.mipos.pro/`
*(Esto incluye borrar las carpetas antiguas de app, vendor, etc. que tenías allí expuestas).*

### 2. Estructura de Carpetas Final
Subiremos los archivos de forma que queden organizados así:

```text
/home/mipospro/
├── placapp_core/        <-- (Aquí extraes TODO el ZIP, excepto la carpeta public)
└── public_html/
    └── raptor.mipos.pro/ <-- (Aquí mueves solo el CONTENIDO de la carpeta public)
        ├── css/
        ├── js/
        ├── img/
        ├── .htaccess
        └── index.php
```

### 3. Ajuste de Rutas en index.php
Edita el archivo `/home/mipospro/public_html/raptor.mipos.pro/index.php` y ajusta estas 3 líneas para que encuentren el núcleo en la carpeta superior:

```php
// 1. Ruta de mantenimiento
if (file_exists($maintenance = __DIR__.'/../../../placapp_core/storage/framework/maintenance.php')) {
    require $maintenance;
}

// 2. Autoload de Composer
require __DIR__.'/../../../placapp_core/vendor/autoload.php';

// 3. Inicio de la App
$app = require_once __DIR__.'/../../../placapp_core/bootstrap/app.php';
```

---

## 🛡️ Fase 3: Configuración de Seguridad (.htaccess)

Copia este contenido exacto en tu archivo `/home/mipospro/public_html/raptor.mipos.pro/.htaccess` para asegurar el flujo de Laravel:

```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Forzar HTTPS
    RewriteCond %{HTTPS} off
    RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

    # Redirigir barras finales si no es una carpeta
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Enviar peticiones al Front Controller
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

---

## ⚙️ Fase 4: Base de Datos y .env

1. **Variables de Entorno:** Edita `/home/mipospro/placapp_core/.env` con los datos de producción:
   - `APP_ENV=production`
   - `APP_DEBUG=false`
   - `APP_URL=https://raptor.mipos.pro`
   - Credenciales de la base de datos MySQL.
2. **Base de Datos:** Importa tu archivo `.sql` mediante el **phpMyAdmin** de cPanel.

---

## 🔧 Solución de Problemas

*   **Error 500:** Verifica los permisos de las carpetas `placapp_core/storage` y `placapp_core/bootstrap/cache` (deben ser 755 o 775).
*   **Imágenes no cargan:** Asegúrate de que el logo esté en `/raptor.mipos.pro/img/Logo_Placapp.png`.
*   **Sin cambios visuales:** Limpia el caché de tu navegador (Ctrl+Shift+R).

---
**Última actualización:** Abril 2026 - Versión Nativa Apple.
