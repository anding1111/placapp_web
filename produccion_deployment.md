# 🚀 Guía Completa de Despliegue a Producción - Laravel

Esta guía cubre el despliegue de aplicaciones Laravel tanto en **Hosting Compartido** como en **VPS**, incluyendo la compilación de assets con Vite.

---

## 📋 Tabla de Contenidos

1. [Hosting Compartido (Compilación Local)](#hosting-compartido)
2. [VPS (Compilación en Servidor)](#vps)
3. [Configuración Post-Despliegue](#configuración-post-despliegue)
4. [Solución de Problemas](#solución-de-problemas)

---

## 🏢 Hosting Compartido

En hosting compartido, **debes compilar los assets localmente** antes de subir, ya que normalmente no tienes acceso SSH ni Node.js instalado.

### Paso 1: Compilar Assets Localmente

```bash
# En tu máquina local
cd /ruta/a/tu/proyecto
npm run build
```

Esto genera:
```
public/build/
├── assets/
│   ├── app-[hash].css
│   └── app-[hash].js
└── manifest.json
```

### Paso 2: Preparar Estructura de Archivos

En hosting compartido, la estructura típica es:

```
public_html/          ← Raíz del dominio (accesible públicamente)
├── index.php         ← Contenido de public/index.php (MODIFICADO)
├── .htaccess         ← Contenido de public/.htaccess
├── build/            ← Contenido de public/build/
├── css/              ← Contenido de public/css/
├── js/               ← Contenido de public/js/
├── img/              ← Contenido de public/img/
└── favicon.ico

app_raptor/           ← Carpeta privada (NO accesible públicamente)
├── app/
├── bootstrap/
├── config/
├── database/
├── resources/
├── routes/
├── storage/
├── vendor/
├── .env              ← Configuración de producción
├── artisan
└── composer.json
```

### Paso 3: Modificar index.php

**Archivo original** (`public/index.php`):
```php
<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());
```

**Archivo modificado** (para hosting compartido - `public_html/index.php`):
```php
<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../app_raptor/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../app_raptor/vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../app_raptor/bootstrap/app.php';

$app->handleRequest(Request::capture());
```

**Cambios realizados:**
- `__DIR__.'/../storage'` → `__DIR__.'/../app_raptor/storage'`
- `__DIR__.'/../vendor'` → `__DIR__.'/../app_raptor/vendor'`
- `__DIR__.'/../bootstrap'` → `__DIR__.'/../app_raptor/bootstrap'`

> **Nota:** Reemplaza `app_raptor` con el nombre de tu carpeta privada.

### Paso 4: Configurar .env en Producción

Crea/edita el archivo `.env` en `app_raptor/.env`:

```env
APP_NAME="Tu Aplicación"
APP_ENV=production
APP_KEY=base64:TU_APP_KEY_AQUI
APP_DEBUG=false
APP_URL=https://tu-dominio.com

LOG_CHANNEL=stack
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=tu_base_datos
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120
```

### Paso 5: Subir Archivos al Servidor

**Archivos a subir a `public_html/`:**
- ✅ `index.php` (modificado)
- ✅ `.htaccess`
- ✅ `build/` (carpeta completa)
- ✅ `css/`
- ✅ `js/`
- ✅ `img/`
- ✅ `favicon.ico`
- ✅ `robots.txt`

**Archivos a subir a `app_raptor/`:**
- ✅ `app/`
- ✅ `bootstrap/`
- ✅ `config/`
- ✅ `database/`
- ✅ `resources/`
- ✅ `routes/`
- ✅ `storage/`
- ✅ `vendor/`
- ✅ `.env` (configurado para producción)
- ✅ `artisan`
- ✅ `composer.json`

**NO subir:**
- ❌ `node_modules/`
- ❌ `.git/`
- ❌ `tests/`
- ❌ `.env.example`

### Paso 6: Configurar Permisos

Vía FTP o panel de control, establece permisos:

```bash
# Carpetas de almacenamiento
chmod 775 app_raptor/storage
chmod 775 app_raptor/storage/framework
chmod 775 app_raptor/storage/framework/cache
chmod 775 app_raptor/storage/framework/sessions
chmod 775 app_raptor/storage/framework/views
chmod 775 app_raptor/storage/logs

# Carpeta bootstrap/cache
chmod 775 app_raptor/bootstrap/cache
```

### Paso 7: Optimizar para Producción

Si tienes acceso SSH (algunos hosting compartidos lo permiten):

```bash
cd app_raptor
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Si **NO** tienes SSH, asegúrate de que tu `.env` tenga `APP_DEBUG=false`.

---

## 🖥️ VPS

En VPS tienes control total y puedes compilar directamente en el servidor.

### Opción A: Compilar en el Servidor (Recomendado)

#### Paso 1: Conectar al VPS

```bash
ssh usuario@tu-servidor.com
```

#### Paso 2: Instalar Dependencias del Sistema

**Ubuntu/Debian:**
```bash
sudo apt update
sudo apt install -y nginx mysql-server php8.2-fpm php8.2-mysql php8.2-mbstring \
    php8.2-xml php8.2-bcmath php8.2-curl php8.2-zip git curl

# Instalar Node.js y npm
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install -y nodejs
```

**CentOS/RHEL:**
```bash
sudo yum install -y epel-release
sudo yum install -y nginx mysql-server php php-fpm php-mysql php-mbstring \
    php-xml php-bcmath php-curl git curl

# Instalar Node.js
curl -fsSL https://rpm.nodesource.com/setup_20.x | sudo bash -
sudo yum install -y nodejs
```

#### Paso 3: Clonar el Repositorio

```bash
cd /var/www
sudo git clone https://github.com/tu-usuario/tu-repo.git tu-app
cd tu-app
```

#### Paso 4: Instalar Dependencias de PHP

```bash
# Instalar Composer si no está instalado
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Instalar dependencias
composer install --optimize-autoloader --no-dev
```

#### Paso 5: Instalar Dependencias de Node.js y Compilar

```bash
npm install
npm run build
```

#### Paso 6: Configurar .env

```bash
cp .env.example .env
nano .env
```

Configura:
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://tu-dominio.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_DATABASE=tu_base_datos
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña
```

Genera la clave de aplicación:
```bash
php artisan key:generate
```

#### Paso 7: Configurar Permisos

```bash
sudo chown -R www-data:www-data /var/www/tu-app
sudo chmod -R 775 /var/www/tu-app/storage
sudo chmod -R 775 /var/www/tu-app/bootstrap/cache
```

#### Paso 8: Configurar Nginx

Crea `/etc/nginx/sites-available/tu-app`:

```nginx
server {
    listen 80;
    server_name tu-dominio.com www.tu-dominio.com;
    root /var/www/tu-app/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

Habilita el sitio:
```bash
sudo ln -s /etc/nginx/sites-available/tu-app /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

#### Paso 9: Optimizar Laravel

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

#### Paso 10: Configurar SSL (Opcional pero Recomendado)

```bash
sudo apt install certbot python3-certbot-nginx
sudo certbot --nginx -d tu-dominio.com -d www.tu-dominio.com
```

### Opción B: Compilar Localmente y Subir (Similar a Hosting Compartido)

Si prefieres compilar localmente:

1. Ejecuta `npm run build` en tu máquina
2. Sube todos los archivos vía SFTP/SCP
3. Sigue los pasos 4-10 de la Opción A

---

## ⚙️ Configuración Post-Despliegue

### Migraciones de Base de Datos

**Hosting Compartido (si tienes SSH):**
```bash
cd app_raptor
php artisan migrate --force
```

**VPS:**
```bash
cd /var/www/tu-app
php artisan migrate --force
```

### Seeders (Datos de Prueba)

```bash
php artisan db:seed --class=PlatesDemoSeeder
```

### Limpiar Caché

Si haces cambios en configuración:
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

Luego vuelve a cachear:
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 🔧 Solución de Problemas

### Problema: Estilos no se cargan

**Causa:** Falta compilar assets o no se subió la carpeta `build/`.

**Solución:**
```bash
# Local
npm run build

# Verifica que existe
ls -la public/build/

# Sube la carpeta completa al servidor
```

### Problema: Error 500

**Causa:** Permisos incorrectos o `.env` mal configurado.

**Solución:**
```bash
# Verifica permisos
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Verifica .env
cat .env | grep APP_KEY
# Si está vacío:
php artisan key:generate
```

### Problema: "Class not found"

**Causa:** Autoload no está actualizado.

**Solución:**
```bash
composer dump-autoload
php artisan config:clear
```

### Problema: Assets con rutas incorrectas

**Causa:** `APP_URL` en `.env` no coincide con el dominio.

**Solución:**
```env
# En .env
APP_URL=https://tu-dominio.com
```

Luego:
```bash
php artisan config:cache
```

### Problema: Modal o componentes sin estilos

**Causa:** Conflicto entre `style.css` y `app.css` compilado.

**Solución:**

Verifica en tus vistas Blade:
```blade
{{-- Opción 1: Usar solo Vite --}}
@vite(['resources/css/app.css', 'resources/js/app.js'])

{{-- Opción 2: Usar ambos (orden importa) --}}
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
@vite(['resources/css/app.css', 'resources/js/app.js'])
```

---

## 📝 Checklist de Despliegue

### Hosting Compartido

- [ ] Ejecutar `npm run build` localmente
- [ ] Copiar contenido de `public/` a `public_html/`
- [ ] Copiar resto del proyecto a carpeta privada (ej: `app_raptor/`)
- [ ] Modificar `index.php` con rutas correctas
- [ ] Configurar `.env` para producción
- [ ] Establecer permisos en `storage/` y `bootstrap/cache/`
- [ ] Verificar que `build/` se subió correctamente
- [ ] Probar el sitio en navegador
- [ ] Limpiar caché del navegador (Ctrl+Shift+R)

### VPS

- [ ] Conectar vía SSH
- [ ] Instalar dependencias del sistema (Nginx, PHP, MySQL, Node.js)
- [ ] Clonar repositorio o subir archivos
- [ ] Ejecutar `composer install --no-dev`
- [ ] Ejecutar `npm install && npm run build`
- [ ] Configurar `.env`
- [ ] Ejecutar `php artisan key:generate`
- [ ] Configurar permisos
- [ ] Configurar Nginx/Apache
- [ ] Ejecutar migraciones
- [ ] Cachear configuración
- [ ] Configurar SSL con Certbot
- [ ] Probar el sitio

---

## 🎯 Resumen de Comandos Útiles

```bash
# Compilar assets
npm run build

# Optimizar Laravel (producción)
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

# Limpiar caché
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Permisos (Linux)
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data .

# Ver logs
tail -f storage/logs/laravel.log
```

---

## 📚 Referencias

- [Documentación oficial de Laravel - Deployment](https://laravel.com/docs/10.x/deployment)
- [Documentación de Vite](https://vitejs.dev/guide/build.html)
- [Guía de Nginx para Laravel](https://laravel.com/docs/10.x/deployment#nginx)

---

**Última actualización:** 2025-12-11
