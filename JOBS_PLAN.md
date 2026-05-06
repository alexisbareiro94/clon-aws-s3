# Plan de Implementación de Background Jobs

Este documento detalla los procesos dentro de **Clon-S3** que son candidatos para ser ejecutados en segundo plano (Background Jobs) para mejorar la experiencia del usuario y la estabilidad del sistema.

## 1. Procesamiento de Archivos (Carga)

Actualmente, el `ObjectController@store` realiza tareas pesadas de forma síncrona que bloquean la respuesta al usuario.

### Candidatos a Jobs:
- **Cálculo de Checksum (`md5_file`)**: Calcular el hash MD5 de archivos grandes puede tomar varios segundos.
- **Extracción de Metadatos**: Ya existe el job `ExtractObjectMetadata`, pero se dispara *después* de que el archivo ya fue procesado síncronamente en gran parte.

### Propuesta:
Mover el cálculo del checksum a un job o realizarlo de forma asíncrona si no es crítico para la creación inicial del registro. 
*Nota: Si el checksum es necesario para evitar duplicados inmediatos, debe seguir siendo síncrono o usar un estado "pendiente".*

---

## 2. Limpieza de Almacenamiento (Eliminación)

La eliminación de buckets y objetos grandes puede involucrar borrar miles de archivos físicamente.

### Candidatos a Jobs:
- **`Storage::deleteDirectory($bucket->slug)`**: En `BucketController@destroy`, borrar un directorio completo puede ser lento si tiene muchos archivos.
- **`Storage::delete($path)`**: Para objetos individuales muy grandes.

### Propuesta:
Usar la función `defer()` de Laravel 11 para la eliminación física de archivos, o un Job dedicado si la limpieza es masiva.
```php
// Ejemplo en BucketController
defer(fn () => Storage::deleteDirectory($bucket->slug));
```

---

## 3. Registro de Eventos y Métricas

El registro de descargas (`ObjectDownloadEvent`) es una tarea de "escritura y olvido" que no debería retrasar la descarga del archivo.

### Candidato:
- **`DownloadEventController`**: El registro de quién descargó qué y cuándo.

### Propuesta:
Usar `defer()` para insertar los registros de métricas después de que la respuesta de descarga haya sido enviada al navegador.

---

## 4. Generación de Enlaces Compartidos Masivos

Si en el futuro se permite generar enlaces para múltiples archivos a la vez.

### Propuesta:
Un Job que procese la colección de objetos y genere los tokens, notificando al usuario por websockets o email al terminar.

---

## 5. Notificaciones y Alertas

Cualquier envío de correo electrónico o notificación (ej. "Su bucket está llegando al límite de cuota").

### Propuesta:
Todas las notificaciones deben implementar `ShouldQueue` por defecto.

---

## Resumen de Prioridades

| Proceso | Método Sugerido | Prioridad |
|---------|-----------------|-----------|
| Eliminación de Buckets | `defer()` o Job | Alta |
| Registro de Descargas | `defer()` | Media |
| Cálculo de Checksum | Job (Queue) | Media |
| Extracción de Metadatos | Job (Ya existe) | - |

## Configuración Recomendada
Dado que el proyecto ya usa **Redis** como `QUEUE_CONNECTION` (visto en `.env`), el sistema está listo para escalar estos procesos sin configuración adicional de infraestructura.
