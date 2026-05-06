# Recomendaciones de Mejora

## Bugs Críticos

### 1. Error lógico en límites de descarga
**Archivo:** `app/Http/Controllers/DownloadEventController.php:17-19`

El código atual tiene un error de lógica:

```php
// ACTUAL (incorrecto)
if ($link->download_limit != null) {
    return redirect()->back()->with('error', ...);
} elseif ($link->download_limit <= $link->download_count) {
    // Nunca se ejecuta porque el primer if ya retornó
}
```

El problema es que si `download_limit` es `null`, retorna error inmediatamente. Debería permitir descargas ilimitadas cuando es `null`.

---

## Mejoras de Funcionalidad - ya esta

### 2. Búsqueda con más campos
**Archivo:** `app/Http/Controllers/BucketController.php:68`

```php
// ACTUAL
->where('original_name', 'ilike', "%{$q}%")

// MEJORA (buscar también por tipo MIME)
->where(function($query) use ($q) {
    $query->where('original_name', 'ilike', "%{$q}%")
          ->orWhere('mime_type', 'ilike', "%{$q}%");
})
```

### 3. Helpers de formato reutilizables
Crear un helper para formateo de bytes (重复 en varias vistas):

**Archivo nuevo:** `app/Helpers/format.php`

```php
<?php

function formatBytes(int $bytes, int $decimals = 2): string
{
    if ($bytes >= 1073741824) {
        return round($bytes / 1073741824, $decimals) . ' GB';
    } elseif ($bytes >= 1048576) {
        return round($bytes / 1048576, $decimals) . ' MB';
    } elseif ($bytes >= 1024) {
        return round($bytes / 1024, $decimals) . ' KB';
    }
    return $bytes . ' B';
}
```

然后在 `composer.json` 的 `autoload` 中添加:

```json
"autoload": {
    "files": ["app/Helpers/format.php"]
}
```

### 4. Caché para metadatos de objetos
**Archivo:** `app/Jobs/ExtractObjectMetadata.php`

El job procesa objetos sin caché. Agregar caché para no reprocesar:

```php
public function handle(): void
{
    foreach ($this->objectos as $objecto) {
        $cacheKey = "object_metadata_{$objecto->id}";
        
        if (Cache::has($cacheKey)) {
            continue; // Ya procesado
        }
        
        // ... proceso existente ...
        
        Cache::put($cacheKey, true, now()->addDay());
    }
}
```

### 5. Validación de tamaño de archivo antes de subir
**Archivo:** `app/Http/Controllers/ObjectController.php:60`

Agregar validación de tamaño máximo:

```php
public function store(ObjectStoreRequest $request, Bucket $bucket)
{
    $maxSize = $bucket->max_size_bytes ?? 524288000; // 500MB por defecto
    
    foreach ($files as $file) {
        if ($file->getSize() > $maxSize) {
            return back()->with('error', "El archivo {$file->getClientOriginalName()} excede el tamaño máximo.");
        }
    }
    // ... resto del código
}
```

### 6. Registro de eventos de subida
**Archivo:** `app/Http/Controllers/ObjectController.php:60`

Registrar cuando se sube un archivo:

```php
use App\Models\ObjectUploadEvent; // Crear modelo si no existe

// Después de $objecto->create([...])
// Agregar: registrar evento de upload si la tabla existe
if (class_exists('App\Models\ObjectUploadEvent')) {
    ObjectUploadEvent::create([
        'object_id' => $objecto->id,
        'user_id' => auth()->id(),
        'ip_address' => request()->ip(),
    ]);
}
```

### 7. Mejora en paginación AJAX
**Archivo:** `app/Http/Controllers/BucketController.php:74-81`

Retornar más información para mejor UX:

```php
return response()->json([
    'success' => true,
    'objects' => $objects,
    'html' => $html,
    'total' => $objects->total(),
    'currentPage' => $objects->currentPage(),
    'lastPage' => $objects->lastPage(),
    'hasMorePages' => $objects->hasMorePages(),
]);
```

### 8. Toast notifications mejoradas
**Archivo:** `resources/views/layouts/app.blade.php`

En lugar de `session()->with()`, implementar toast notifications:

```php
@if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
         class="fixed bottom-4 right-4 bg-green-500 text-white px-4 py-2 rounded shadow">
        {{ session('success') }}
    </div>
@endif
```

---

## Optimizaciones de Seguridad

### 9. Rate limiting para descarga
**Archivo:** `routes/web.php`

```php
Route::middleware('auth')->group(function () {
    // ... otras rutas
    
    Route::middleware('throttle:10,1')->group(function () {
        Route::get('/download/own/{objecto}', ...);
    });
});
```

### 10. Verificación de acceso al bucket en compartido
**Archivo:** `app/Http/Controllers/SharedLinkController.php:42`

```php
public function viewFile(string $token, Request $request)
{
    $link = ObjectShareLink::with('object', 'object.bucket')
        ->where('visibility', 'pu')
        ->where('token', $token)
        ->firstOrFail();
    
    // Verificar que el bucket también es público
    if ($link->object->bucket->visibility !== 'pu') {
        abort(403, 'Bucket no accesible');
    }
    // ...
}
```

---

## Mejoras UI/UX

### 11. Sorting en tabla de objetos
**Archivo:** `app/Http/Controllers/BucketController.php:66-70`

```php
$sort = $request->sort ?? 'created_at';
$direction = $request->direction ?? 'desc';

$query = $bucket->objectos()
    ->with(['shareLinks', 'bucket'])
    ->orderBy($sort, $direction);
```

### 12. Preview de imagen antes de subir
**Archivo:** `resources/views/object/upload.blade.php`

```javascript
<input type="file" id="fileInput" multiple accept="image/*">
<div id="preview" class="grid grid-cols-4 gap-2"></div>

<script>
document.getElementById('fileInput').addEventListener('change', function(e) {
    const preview = document.getElementById('preview');
    preview.innerHTML = '';
    
    Array.from(e.target.files).forEach(file => {
        const reader = new FileReader();
        reader.onload = e => {
            preview.innerHTML += `<img src="${e.target.result}" class="w-full h-24 object-cover rounded">`;
        };
        reader.readAsDataURL(file);
    });
});
</script>
```

---

## Propuestas Futuras (requieren migraciones)

- [ ] Tabla de versiones de objetos (`ObjectVersion`)
- [ ] Eventos de upload (`ObjectUploadEvent`)
- [ ] Cotas de almacenamiento por bucket
- [ ] Notificaciones en tiempo real
- [ ] Webhooks para eventos
