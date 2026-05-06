@extends('layouts.app')

@section('title', 'Archivo Compartido - ' . $link->object->original_name)

@section('content')
    <div class="py-12 px-4 sm:px-6 lg:px-8 flex items-center justify-center">
        <div
            class="max-w-5xl w-full bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden flex flex-col md:flex-row">

            <!-- Contenedor de la Imagen (Izquierda en desktop) -->
            <div
                class="md:w-3/5 bg-gray-50 flex flex-col items-center justify-center p-8 relative min-h-[400px] border-b md:border-b-0 md:border-r border-gray-200">
                <!-- Skeleton Loading (Se oculta desde JS cuando carga) -->
                <div id="image-loading" class="flex flex-col items-center justify-center space-y-4">
                    <div class="w-10 h-10 border-4 border-blue-500 border-t-transparent rounded-full animate-spin"></div>
                    <p class="text-sm text-gray-500 font-medium tracking-wide">Cargando vista previa...</p>
                </div>

                <!-- Contenedor real de la imagen -->
                <img id="shared-image-preview" src="" alt="Vista previa" oncontextmenu="return false;"
                    class="max-w-full max-h-full object-contain hidden rounded-lg shadow-sm" />

                <!-- Mensaje de error por si falla -->
                <div id="image-error" class="hidden flex-col items-center justify-center text-red-500">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-12 mb-2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <p class="text-sm font-medium">No se pudo cargar la vista previa</p>
                </div>

                <!-- Almacenamos datos vitales (token y ruta de la API) para que JS los consuma -->
                <input type="hidden" id="shared-file-data" data-token="{{ $link->token }}"
                    data-fetch-url="{{ route('shared.link.viewFile', ['token' => $link->token]) }}" />
            </div>

            <!-- Panel de Detalles del Archivo (Derecha en desktop) -->
            <div class="md:w-2/5 p-8 flex flex-col justify-between">
                <div>
                    <div
                        class="flex items-center space-x-2 mb-6 text-sm text-blue-600 font-semibold tracking-wide uppercase">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M7.217 10.907a2.25 2.25 0 1 0 0 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186 9.566-5.314m-9.566 7.5 9.566 5.314m0 0a2.25 2.25 0 1 0 3.935 2.186 2.25 2.25 0 0 0-3.935-2.186Zm0-12.814a2.25 2.25 0 1 0 3.933-2.185 2.25 2.25 0 0 0-3.933 2.185Z" />
                        </svg>
                        <span>Enlace Compartido</span>
                    </div>

                    <h2 class="text-2xl font-bold text-gray-900 mb-2 break-words"
                        title="{{ $link->object->original_name }}">
                        {{ $link->object->original_name }}
                    </h2>

                    <div class="space-y-4 mt-8">
                        <div class="flex flex-col border-b border-gray-100 pb-3">
                            <span class="text-xs text-gray-400 uppercase font-semibold tracking-wider">Tamaño</span>
                            <span
                                class="text-sm font-medium text-gray-800">{{ number_format($link->object->size_bytes / 1024 / 1024, 2) }}
                                MB</span>
                        </div>

                        <div class="flex flex-col border-b border-gray-100 pb-3">
                            <span class="text-xs text-gray-400 uppercase font-semibold tracking-wider">Tipo de
                                archivo</span>
                            <span class="text-sm font-medium text-gray-800 mt-1"><span
                                    class="bg-gray-100 px-2 py-1 rounded-md">{{ $link->object->mime_type ?? 'Desconocido' }}</span></span>
                        </div>

                        <div class="flex flex-col border-b border-gray-100 pb-3">
                            <span class="text-xs text-gray-400 uppercase font-semibold tracking-wider">Creado el</span>
                            <span
                                class="text-sm font-medium text-gray-800">{{ $link->created_at->format('d M, Y - H:i') }}</span>
                        </div>

                        @if ($link->expires_at)
                            <div class="flex flex-col border-b border-gray-100 pb-3">
                                <span class="text-xs text-gray-400 uppercase font-semibold tracking-wider">Expira en</span>
                                <span
                                    class="text-sm font-semibold text-red-500">{{ \Carbon\Carbon::parse($link->expires_at)->diffForHumans() }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Botones de Acción -->
                <div class="mt-8">
                    @if (in_array($link->permission, ['r', 'rd']))
                        <a href="{{ route('download.object', ['link' => $link]) }}"
                            class="group relative flex items-center justify-center w-full px-8 py-4 font-bold text-white transition-all duration-300 bg-linear-to-br from-blue-600 to-indigo-700 rounded-xl hover:from-blue-500 hover:to-indigo-600 hover:scale-[1.02] active:scale-95 shadow-lg shadow-blue-500/25 hover:shadow-blue-500/40">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                                stroke="currentColor"
                                class="w-6 h-6 mr-3 transition-transform duration-300 group-hover:translate-y-1">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                            </svg>
                            <span>Descargar Original</span>
                            <div class="absolute inset-0 rounded-xl ring-1 ring-white/20 ring-inset pointer-events-none">
                            </div>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
