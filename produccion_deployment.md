# 🚀 Guía Maestra de Despliegue - PlacApp (raptor.mipos.pro)

Esta guía detalla el despliegue de **PlacApp Web** en su propia carpeta de subdominio independiente, integrando el núcleo y los recursos públicos en una misma estructura raíz para mayor organización.

---

## 📋 Fase 1: Preparación Local

Antes de subir el proyecto, optimízalo en tu computadora local:

1. **Optimización total:**
   ```bash
   php artisan optimize:clear
   composer install --no-dev --optimize-autoloader
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```
2. **ZIP de Producción:** Crea un archivo `placapp_web.zip` incluyendo:
   - **✅ INCLUIR:** `app/`, `bootstrap/`, `config/`, `database/`, `lang/`, `public/`, `resources/`, `routes/`, `storage/`, `vendor/`, `.env`, `artisan`, `composer.json`, `composer.lock`.
   - **❌ OMITIR:** `node_modules/`, `.git/`, `tests/`, `stubs/`, `phpunit.xml`, `vite.config.js`.

---

## 🏢 Fase 2: Configuración en el Servidor (raptor.mipos.pro)

Esta estructura asume que tu subdominio tiene su propia raíz en `/home/mipospro/raptor.mipos.pro/`.

### 1. Limpieza y Extracción
- **Borra todo** el contenido actual de la carpeta `/home/mipospro/raptor.mipos.pro/`.
- Sube y **extrae el ZIP** directamente en esa carpeta raíz.

### 2. Método de Fusión en Raíz
Debes mover el contenido de la carpeta `public/` para que la aplicación sea accesible directamente desde el subdominio:

1. Entra a la carpeta `public/` recién extraída.
2. **Mueve todos los archivos** (incluyendo `.htaccess` e `index.php`) a la carpeta principal `raptor.mipos.pro/`.
3. (Opcional) Borra la carpeta `public/` ya vacía para mayor limpieza.

### 3. Ajuste de Rutas en index.php
Edita e archivo `/home/mipospro/raptor.mipos.pro/index.php` y ajusta estas 3 líneas (ya que ahora todo está al mismo nivel):

```php
// 1. Ruta de mantenimiento (Quitar el ../)
if (file_exists($maintenance = __DIR__.'/storage/framework/maintenance.php')) {
    require $maintenance;
}

// 2. Autoload de Composer (Quitar el ../)
require __DIR__.'/vendor/autoload.php';

// 3. Inicio de la App (Quitar el ../)
$app = require_once __DIR__.'/bootstrap/app.php';
```

---

## 🛡️ Fase 3: Seguridad y Blindaje (.htaccess)

Para proteger tus archivos sensibles (`.env`, `app/`, `vendor/`) de accesos externos, edita el archivo `/home/mipospro/raptor.mipos.pro/.htaccess` y añade estas reglas al principio:

```apache
# ----------------------------------------------------------------------
# BLINDAJE DE SEGURIDAD (Subdominio Independiente)
# ----------------------------------------------------------------------

# Bloquear acceso a archivos sensibles (.env, logs, etc)
<FilesMatch "^\.env|.*\.log|composer\.(json|lock)$">
    Order allow,deny
    Deny from all
</FilesMatch>

# Bloquear acceso a carpetas del sistema
RedirectMatch 404 ^/(app|bootstrap|config|database|lang|resources|routes|storage|tests|vendor)/

# ----------------------------------------------------------------------
# REGLAS ESTÁNDAR DE LARAVEL
# ----------------------------------------------------------------------
<IfModule mod_rewrite.c>
    RewriteEngine On
    # Forzar HTTPS
    RewriteCond %{HTTPS} off
    RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

---

## ⚙️ Fase 4: Finalización

1. **Variables de Entorno:** Edita `.env` con las credenciales de base de datos de producción y asegúrate de que `APP_DEBUG=false`.
2. **Base de Datos:** Importa tu base de datos mediante **phpMyAdmin** en cPanel.

---
**Última actualización:** Abril 2026 - Versión Nativa Apple.
