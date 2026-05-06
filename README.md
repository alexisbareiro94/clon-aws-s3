# Clon-S3

Clon de Amazon S3 (Simple Storage Service) construido con Laravel 13. Sistema de almacenamiento de objetos con API REST compatible con los conceptos fundamentales de S3.

## Características

- **Buckets**: Contenedores para organizar objetos
- **Objetos**: Archivos almacenados con metadatos, versiones y checksums
- **Control de Acceso**: Sistema de claves de acceso estilo AWS (access key/secret key)
- **Enlaces Compartidos**: Generación de URLs temporales para compartir archivos
- **Visibilidad**: Soporte para buckets y objetos públicos o privados
- **Historial de Descargas**: Registro de eventos de descarga
- **Versionamiento**: Control de versiones de objetos

## Requisitos

- PHP 8.3+
- Laravel 13
- Composer
- Node.js + npm
- Redis (para cola de trabajos)

## Instalación

```bash
# Instalar dependencias
composer install

# Copiar archivo de entorno
cp .env.example .env

# Generar clave de aplicación
php artisan key:generate

# Ejecutar migraciones
php artisan migrate

# Instalar dependencias frontend
npm install
npm run build
```

## Configuración

Configura las siguientes variables en tu archivo `.env`:

```env
QUEUE_CONNECTION=redis
FILESYSTEM_DISK=local
```

## Ejecutar en Desarrollo

```bash
# Modo desarrollo completo (servidor + queue + logs + vite)
composer run dev

# O manualmente
php artisan serve
npm run dev
php artisan queue:listen
```

## API Endpoints

### Buckets

| Método | Endpoint | Descripción |
|--------|----------|-------------|
| GET | `/api/oss/buckets` | Listar buckets |
| POST | `/api/oss/buckets` | Crear bucket |
| GET | `/api/oss/{slug}` | Ver bucket |
| PUT | `/api/oss/{slug}` | Actualizar bucket |
| DELETE | `/api/oss/{slug}` | Eliminar bucket |

### Objetos

| Método | Endpoint | Descripción |
|--------|----------|-------------|
| GET | `/api/oss/{bucket}/{objeto}` | Ver objeto |
| GET | `/api/oss/{bucket}/{objeto}/view` | Descargar/ver archivo |
| POST | `/api/oss/{bucket}/object` | Subir objeto |
| PUT | `/api/oss/{bucket}/{objeto}` | Actualizar objeto |
| DELETE | `/api/oss/{bucket}/{objeto}` | Eliminar objeto |

### Autenticación

El proyecto utiliza Laravel Sanctum para autenticación API.

## Estructura de Base de Datos

- **users**: Usuarios del sistema
- **buckets**: Contenedores de objetos
- **objects**: Archivos almacenados
- **object_versions**: Versiones de objetos
- **bucket_access_keys**: Claves de acceso API
- **object_share_links**: Enlaces compartidos
- **object_download_events**: Registro de descargas

## Licencia

MIT License
