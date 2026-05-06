@extends('layouts.app')

@section('title', 'Detalles del Objeto')

@section('breadcrumb')
    <a href="{{ route('bucket.index') }}" class="text-[#0073bb] hover:underline">Buckets</a>
    /
    <a href="{{ route('bucket.show', $object->bucket) }}"
        class="text-[#0073bb] hover:underline">{{ $object->bucket->name }}</a>
    /
    <span class="font-semibold text-gray-800">{{ $object->original_name }}</span>
@endsection
@section('content')
    <div class="container mx-auto px-4 py-8">
        <!-- Encabezado -->
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $object->original_name }}</h1>
                <p class="text-sm text-gray-500 mt-1">Detalles y configuración del objeto</p>
            </div>
        </div>

        @empty($object->mime_type)
            <!-- Si no hay mime_type, no asumimos nada -->
        @else
            @if (str_starts_with($object->mime_type, 'image/'))
                <img id="preview-image" src="" alt="{{ $object->original_name }}"
                    data-preview-url="{{ route('object.view', [$object->bucket, $object]) }}"
                    class="w-full max-w-2xl rounded-lg shadow-md mb-6 hidden">
            @endif
        @endempty

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Información principal (columna izquierda) -->
            <div class="lg:col-span-2">
                <!-- Información básica del objeto -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <div class="flex justify-between">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Información del Objeto</h2>
                        <button id="edit-object" onclick="toggleEditObjectModal()"
                            class="cursor-pointer transition hover:bg-gray-100 rounded-full p-2" title="Cambiar nombre">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                            </svg>
                        </button>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm text-gray-600">Nombre Original</label>
                            <p class="text-gray-900 font-medium">{{ $object->original_name }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600">Clave del Objeto</label>
                            <p class="text-gray-900 font-medium break-all">{{ $object->object_key }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600">Tamaño</label>
                            <p class="text-gray-900 font-medium">{{ number_format($object->size_bytes / 1024 / 1024, 2) }}
                                MB</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600">Tipo MIME</label>
                            <p class="text-gray-900 font-medium">{{ $object->mime_type ?? 'No especificado' }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600">Creado</label>
                            <p class="text-gray-900 font-medium">{{ $object->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600">Última actualización</label>
                            <p class="text-gray-900 font-medium">{{ $object->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600">Disco de almacenamiento</label>
                            <p class="text-gray-900 font-medium">{{ $object->storage_disk }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600">Checksum</label>
                            <p class="text-gray-900 font-mono text-sm break-all">{{ $object->checksum ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Configuración de visibilidad -->
                @if ($object->bucket->visibility == 'pu')
                    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Configuración de Visibilidad</h2>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="font-medium text-gray-900">Estado Actual</p>
                                    <p class="text-sm text-gray-600">Visibilidad: <span
                                            class="font-semibold">{{ $object->visibility == 'pr' ? 'Privado' : 'Público' }}</span>
                                    </p>
                                </div>
                                <button type="button"
                                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm"
                                    onclick="toggleVisibilityModal()">
                                    Cambiar
                                </button>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Metadatos -->
                @if ($object->metadata && count($object->metadata) > 0)
                    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Metadatos</h2>
                        <div class="space-y-2">
                            @foreach ($object->metadata as $key => $value)
                                <div class="flex justify-between items-start p-2 bg-gray-50 rounded">
                                    <span class="font-medium text-gray-700">{{ $key }}</span>
                                    <span
                                        class="text-gray-600 text-sm break-all">{{ is_array($value) ? json_encode($value) : $value }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Versiones del objeto -->
                @if ($object->versions && count($object->versions) > 0)
                    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Historial de Versiones
                            ({{ count($object->versions) }})</h2>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="px-4 py-2 text-left">Versión</th>
                                        <th class="px-4 py-2 text-left">Tamaño</th>
                                        <th class="px-4 py-2 text-left">MIME</th>
                                        <th class="px-4 py-2 text-left">Estado</th>
                                        <th class="px-4 py-2 text-left">Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($object->versions as $version)
                                        <tr class="border-t hover:bg-gray-50">
                                            <td class="px-4 py-2">v{{ $version->version_number }}</td>
                                            <td class="px-4 py-2">{{ number_format($version->size_bytes / 1024, 2) }} KB
                                            </td>
                                            <td class="px-4 py-2">{{ $version->mime_type }}</td>
                                            <td class="px-4 py-2">
                                                @if ($version->is_current)
                                                    <span
                                                        class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">Actual</span>
                                                @else
                                                    <span
                                                        class="bg-gray-100 text-gray-800 px-2 py-1 rounded text-xs">Anterior</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-2">
                                                <button class="text-blue-500 hover:text-blue-700 text-sm">Descargar</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                <!-- Enlaces de compartición -->
                @if ($object->shareLinks && count($object->shareLinks) > 0)
                    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Enlaces Compartidos
                            ({{ count($object->shareLinks) }})</h2>
                        <div class="space-y-3">
                            @foreach ($object->shareLinks as $link)
                                <div class="p-3 bg-gray-50 rounded-lg border border-gray-200">
                                    <div class="flex justify-between items-start mb-2">
                                        <div>
                                            <p class="font-medium text-gray-900">Permiso:
                                                {{ $link->permission == 'r' ? 'Lectura' : ($link->permission == 'd' ? 'Descarga' : 'Lectura y Descarga') }}
                                            </p>
                                            <p class="text-xs text-gray-600">Token:
                                                <span class="font-mono">{{ substr($link->token, 0, 20) }}...</span>
                                            </p>
                                            <div class="flex items-center gap-2 bg-gray-50 py-2 rounded">
                                                <p id="url-{{ $link->id }}" class="font-medium text-xs text-gray-600">
                                                    URL:
                                                    <span class="">{{ $link->url }}</span>
                                                </p>

                                                @if ($link->revoked_at == null && $link->expires_at == null)
                                                    <button class="cursor-pointer hover:text-blue-500 " title="Copiar"
                                                        onclick="copyUrl('{{ $link->url }}')">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                            class="size-4">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 0 1-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 0 1 1.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 0 0-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 0 1-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 0 0-3.375-3.375h-1.5a1.125 1.125 0 0 1-1.125-1.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H9.75" />
                                                        </svg>

                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                        @if ($link->expires_at != null)
                                            <button class="text-red-500 hover:text-red-700 text-sm">
                                                Expirado
                                            </button>
                                        @elseif($link->revoked_at != null)
                                            <button class="text-red-500 hover:text-red-700 text-sm">
                                                Revocado
                                            </button>
                                        @else
                                            <form action="{{ route('shared.link.revoke', $link) }}" method="POST">
                                                @csrf
                                                <button type="submit"
                                                    onclick="confirm('¿esta seguro de revocar el enlace?')"
                                                    class="cursor-pointer bg-red-500 text-white px-4 py-2 rounded-lg text-sm">
                                                    Revocar
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                    <div class="grid grid-cols-2 text-xs text-gray-600">
                                        <span>Creado: {{ $link->created_at->format('d/m/Y') }}</span>
                                        <span>Descargas:
                                            {{ $link->download_count ?? 0 }}/{{ $link->download_limit ?? 'Ilimitado' }}</span>
                                    </div>
                                    @if ($link->expires_at)
                                        <p class="text-xs text-gray-600 mt-1" title="{{ $link->expires_at }}">
                                            @if ($link->expires_at <= now())
                                                <span class="text-red-500">Expiro:
                                                    {{ \Carbon\Carbon::parse($link->expires_at)->diffForHumans() }}</span>
                                            @else
                                                <span class="text-green-500">Expira:
                                                    {{ \Carbon\Carbon::parse($link->expires_at)->diffForHumans() }}</span>
                                            @endif
                                        </p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        <button class="mt-3 bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-sm w-full"
                            onclick="createShareLink()">
                            + Crear nuevo enlace
                        </button>
                    </div>
                @endif
            </div>

            <!-- Panel lateral derecho -->
            <div>
                <!-- Información del usuario y bucket -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Información General</h2>
                    <div class="space-y-3">
                        <div>
                            <label class="text-xs text-gray-600 uppercase">Bucket</label>
                            <p class="text-gray-900 font-medium">{{ $object->bucket->name }}</p>
                        </div>
                        <div>
                            <label class="text-xs text-gray-600 uppercase">Propietario</label>
                            <p class="text-gray-900 font-medium">{{ $object->user->name }}</p>
                        </div>
                        <div>
                            <label class="text-xs text-gray-600 uppercase">ID del Objeto</label>
                            <p class="text-gray-900 font-mono text-sm">{{ $object->id }}</p>
                        </div>
                    </div>
                </div>

                <!-- Acciones rápidas -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Acciones</h2>
                    <div class="space-y-2">
                        <a href="{{ route('download.own', ['objecto' => $object]) }}"
                            class="block w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-center text-sm">
                            📥 Descargar
                        </a>
                        <button type="button"
                            class="cursor-pointer w-full bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-sm"
                            onclick="createShareLink()">
                            🔗 Compartir
                        </button>
                        <button type="button"
                            class="w-full bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm"
                            onclick="deleteObject()">
                            🗑️ Eliminar
                        </button>
                    </div>
                </div>

                <!-- Estadísticas -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Estadísticas</h2>
                    <div class="space-y-3">
                        <div class="p-3 bg-blue-50 rounded">
                            <p class="text-xs text-gray-600">Total de Descargas</p>
                            <p class="text-2xl font-bold text-blue-600">{{ $object->downloadEvents()->count() ?? 0 }}</p>
                        </div>
                        <div class="p-3 bg-green-50 rounded">
                            <p class="text-xs text-gray-600">Enlaces Activos</p>
                            <p class="text-2xl font-bold text-green-600">
                                {{ $object->shareLinks()->whereNull('revoked_at')->count() ?? 0 }}</p>
                        </div>
                        <div class="p-3 bg-purple-50 rounded">
                            <p class="text-xs text-gray-600">Versiones</p>
                            <p class="text-2xl font-bold text-purple-600">{{ $object->versions()->count() ?? 0 }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para crear enlace temporal -->
    <div onclick="document.getElementById('createShareLinkModal').classList.add('hidden')" id="createShareLinkModal"
        class="hidden fixed inset-0 bg-black/10 flex items-center justify-center z-10">

        <div onclick="event.stopPropagation()" class="bg-white rounded-lg p-6 max-w-sm w-full mx-4 z-20">
            <form action="{{ route('shared.link.store', $object) }}" method="post">
                @csrf
                <input type="hidden" id="share_object_id" name="object_id" value="{{ $object->id }}">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Crear Enlace Compartido</h3>

                <div class="space-y-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Permiso</label>
                        <select required name="permission" class="w-full border border-gray-300 rounded-lg p-2 bg-white">
                            <option value="r">Ver</option>
                            <option value="d">Descargar</option>
                            <option value="vd">Ver y Descargar</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Expira en (Opcional)</label>

                        <div class="flex flex-wrap gap-2 mb-3">
                            <button type="button" onclick="setExpirationMode('hours')" id="btn-mode-hours"
                                class="px-3 py-1.5 text-xs font-semibold rounded-md bg-blue-100 text-blue-700 hover:bg-blue-200 border border-blue-200 transition-colors">
                                Elegir por hora
                            </button>
                            <button type="button" onclick="setExpirationMode('weeks')" id="btn-mode-weeks"
                                class="px-3 py-1.5 text-xs font-medium rounded-md bg-gray-100 text-gray-700 hover:bg-gray-200 border border-gray-200 transition-colors">
                                Personalizar semana
                            </button>
                            <button type="button" onclick="setExpirationMode('custom')" id="btn-mode-custom"
                                class="px-3 py-1.5 text-xs font-medium rounded-md bg-gray-100 text-gray-700 hover:bg-gray-200 border border-gray-200 transition-colors">
                                Personalizado fecha y hora
                            </button>
                            <button type="button" onclick="setExpirationMode('unlimited')" id="btn-mode-unlimited"
                                class="px-3 py-1.5 text-xs font-medium rounded-md bg-gray-100 text-gray-700 hover:bg-gray-200 border border-gray-200 transition-colors">
                                Sin límite
                            </button>
                        </div>

                        <div id="panel-hours" class="block">
                            <select id="select-hours" onchange="updateExpiresAtValue()"
                                class="w-full border border-gray-300 rounded-lg p-2 bg-white text-sm">
                                <option value="1">1 hora</option>
                                <option value="5">5 horas</option>
                                <option value="12">12 horas</option>
                                <option value="24">24 horas</option>
                            </select>
                        </div>

                        <div id="panel-weeks" class="hidden">
                            <select id="select-weeks" onchange="updateExpiresAtValue()"
                                class="w-full border border-gray-300 rounded-lg p-2 bg-white text-sm">
                                <option value="1">1 semana</option>
                                <option value="2">2 semanas</option>
                                <option value="4">4 semanas</option>
                            </select>
                        </div>

                        <div id="panel-custom" class="hidden">
                            <input type="datetime-local" id="input-custom-date" onchange="updateExpiresAtValue()"
                                class="w-full border border-gray-300 rounded-lg p-2 text-sm">
                        </div>

                        <input type="hidden" name="expires_at" id="hidden-expires-at">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Límite de Descargas (Opcional)</label>
                        <input type="number" name="download_limit" min="1" placeholder="Ej: 10"
                            class="w-full border border-gray-300 rounded-lg p-2">
                    </div>
                </div>

                <div class="flex gap-3">
                    <button type="button"
                        onclick="document.getElementById('createShareLinkModal').classList.add('hidden')"
                        class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg">
                        Cancelar
                    </button>
                    <button type="submit" id="btnSubmitShareLink"
                        class="flex-1 bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg">
                        Crear
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal para cambiar visibilidad -->
    <div id="visibilityModal" class="hidden fixed inset-0 bg-black/10 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 max-w-sm w-full mx-4">
            <form action="{{ route('object.update', $object) }}" method="post">
                @csrf
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Cambiar Visibilidad</h3>
                <div class="space-y-2 mb-4">
                    <label class="flex items-center p-3 border rounded cursor-pointer hover:bg-gray-50">
                        <input type="radio" name="visibility" value="pr"
                            {{ $object->visibility === 'pr' ? 'checked' : '' }} class="mr-3">
                        <span class="text-gray-900">Privado</span>
                    </label>
                    <label class="flex items-center p-3 border rounded cursor-pointer hover:bg-gray-50">
                        <input type="radio" name="visibility" value="pu"
                            {{ $object->visibility === 'pu' ? 'checked' : '' }} class="mr-3">
                        <span class="text-gray-900">Público</span>
                    </label>
                </div>
                <div class="flex gap-3">
                    <button type="button" onclick="toggleVisibilityModal()"
                        class="cursor-pointer flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="cursor-pointer flex-1 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                        Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal para editar nombre del objeto -->
    <div id="editObjectModal" class="hidden fixed inset-0 bg-black/10 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 max-w-sm w-full mx-4">
            <form action="{{ route('object.update', $object) }}" method="post">
                @csrf
                @method('PUT')
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Cambiar Nombre</h3>
                <div class="mb-4">
                    <label for="original_name" class="block text-sm font-medium text-gray-700 mb-1">Nuevo nombre</label>
                    <input type="text" name="original_name" id="original_name" value="{{ $object->original_name }}"
                        class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                        required>
                </div>
                <div class="flex gap-3">
                    <button type="button" onclick="toggleEditObjectModal()"
                        class="cursor-pointer flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="cursor-pointer flex-1 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                        Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Las funciones como toggleVisibilityModal, toggleEditObjectModal, createShareLink y la lógica de vista previa se encuentran en resources/js/show-object.js

        function saveVisibility() {
            const visibility = document.querySelector('input[name="visibility"]:checked').value;
            // Aquí iría la lógica para guardar la visibilidad
            console.log('Cambiar visibilidad a:', visibility);
            toggleVisibilityModal();
        }

        // La funcion createShareLink se encuentra en resources/js/show-object.js

        function editObject() {
            console.log('Editar objeto');
            // Aquí iría la lógica para editar el objeto
        }

        function deleteObject() {
            if (confirm('¿Estás seguro de que deseas eliminar este objeto?')) {
                console.log('Eliminar objeto');
                // Aquí iría la lógica para eliminar el objeto
            }
        }

        function revokeShare(linkId) {
            if (confirm('¿Estás seguro de que deseas revocar este enlace?')) {
                console.log('Revocar enlace:', linkId);
                // Aquí iría la lógica para revocar el enlace
            }
        }
    </script>
@endsection
