# PlacApp Web

PlacApp Web es una plataforma desarrollada en Laravel diseñada para la gestión y monitoreo de placas (`plates`), vinculación segura de usuarios mediante `uuids` y manejo de acceso por tokens (`personal_access_tokens`).

## 📋 Auditoría de Código y Migraciones

El proyecto es una aplicación web basada en el framework Laravel. Durante la auditoría se revisaron las migraciones personalizadas que estructuran la base de datos de la plataforma:

1. **`plates`**: Almacena el registro principal de placas.
   - Campos destacados: `plate_name` (placa), `plate_desc` (descripción), `plate_entry_date` (fecha de entrada), `plate_exit_date` (fecha de salida), `plate_level` (nivel de alerta o acceso, por defecto 3), `plate_location` y `plate_detail`.
2. **`plates_demo`**: Tabla idéntica a `plates` pero destinada a entornos de prueba, demostraciones o registros no oficiales. El `plate_level` por defecto es 4.
3. **`uuids`**: Tabla de vinculación que asocia identificadores únicos universales (UUID) reales o generados, directamente a un usuario (`user_id` en cascada). Incluye un estado (`status`) para habilitar o deshabilitar la vinculación.
4. **`personal_access_tokens`**: Utilizada por Laravel Sanctum para la autenticación basada en tokens (API), permitiendo accesos seguros desde aplicaciones móviles o clie## 🚀 Despliegue en Producción

Para desplegar esta aplicación en un entorno de producción (Hosting Compartido o VPS), por favor sigue la:

### 📖 [Guía Maestra de Despliegue (raptor.mipos.pro)](./produccion_deployment.md)

Esta guía contiene las instrucciones detalladas para realizar un despliegue seguro, optimizado y compatible con las exigencias de la App Store.

---

## 💻 Desarrollo Local

Si deseas ejecutar el proyecto localmente para realizar cambios:

1. **Instalar dependencias:** `composer install` y `npm install`.
2. **Configurar el entorno:** Copiar `.env.example` a `.env` y configurar la base de datos.
3. **Generar llave:** `php artisan key:generate`.
4. **Migraciones:** `php artisan migrate`.
5. **Servidor:** `php artisan serve`.

---

## 🛠️ Tecnologías

- **Framework:** Laravel 11
- **Interfaz:** Vanilla CSS (A estética Nativa iOS 17)
- **Base de Datos:** MySQL / MariaDB
- **Utilidades:** Laravel Excel, Sanctum

---
**Última actualización:** Abril 2026.
```

¡Con estos pasos tu aplicación PlacApp Web debería estar corriendo y migrando óptimamente en el escenario que elijas!
