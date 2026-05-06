@extends('layouts.app')

@section('title', 'Configuración de ' . $bucket->name)

@section('content')
    <div class="mb-6">
        <nav class="flex mb-4 text-sm text-gray-500" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('bucket.index') }}" class="hover:text-gray-700">Buckets</a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <a href="{{ route('bucket.show', $bucket) }}"
                            class="ml-1 md:ml-2 hover:text-gray-700">{{ $bucket->name }}</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-1 md:ml-2 font-medium text-gray-700">Configuración</span>
                    </div>
                </li>
            </ol>
        </nav>

        <h1 class="text-2xl font-bold text-gray-900">Configuración del bucket: {{ $bucket->name }}</h1>
        <p class="text-sm text-gray-500">Administra las propiedades, permisos y otras opciones de este bucket.</p>
    </div>

    <div class="space-y-8">
        <!-- Propiedades del bucket -->
        <div class="bg-white border aws-border rounded-lg overflow-hidden shadow-sm">
            <div class="p-4 border-b aws-border bg-gray-50">
                <h2 class="text-lg font-bold text-gray-900">Propiedades del bucket</h2>
            </div>
            <div class="p-6 space-y-6">
                <!-- Cambiar Nombre -->
                <div>
                    <h3 class="text-md font-bold text-gray-900 mb-2">Nombre del bucket</h3>
                    <p class="text-sm text-gray-500 mb-4">El nombre del bucket debe ser único y cumplir con las reglas de
                        nomenclatura de S3.</p>
                    <form action="{{ route('bucket.update', $bucket) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="max-w-md">
                            <div class="flex gap-2">
                                <input id="name-input" type="text" name="name" value="{{ $bucket->name }}"
                                    class="flex-1 px-3 py-2 border aws-border rounded text-sm focus:outline-none focus:ring-1 focus:ring-[#0073bb]">
                                <button id="save-name-btn" type="submit"
                                    class="btn-aws-primary text-white px-4 py-2 rounded text-sm font-bold opacity-50 cursor-not-allowed"
                                    disabled>
                                    Guardar cambios
                                </button>
                            </div>
                        </div>
                    </form>
                    </li>
                </div>

                <hr class="aws-border">

                <!-- Visibilidad -->
                <div>
                    <h3 class="text-md font-bold text-gray-900 mb-2">Visibilidad y acceso</h3>
                    <p class="text-sm text-gray-500 mb-4">Configura si los objetos de este bucket pueden ser públicos o si
                        requieren autenticación.</p>
                    <form action="{{ route('bucket.update', $bucket) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="space-y-3" id="visibility-options" data-initial-visibility="{{ $bucket->visibility }}">
                            <div class="flex items-center gap-3">
                                <input type="radio" id="visibility-private" name="visibility" value="pr"
                                    {{ $bucket->visibility === 'pr' ? 'checked' : '' }}
                                    class="visibility-radio text-[#0073bb] focus:ring-[#0073bb]">
                                <label for="visibility-private" class="text-sm">
                                    <span class="font-bold text-gray-900">Privado</span>
                                    <p class="text-xs text-gray-500">Acceso limitado al propietario.
                                        Terceros solo mediante API con token proporcionado por el dueño.</p>
                                </label>
                            </div>
                            <div class="flex items-center gap-3">
                                <input type="radio" id="visibility-public" name="visibility" value="pu"
                                    {{ $bucket->visibility === 'pu' ? 'checked' : '' }}
                                    class="visibility-radio text-[#0073bb] focus:ring-[#0073bb]">
                                <label for="visibility-public" class="text-sm">
                                    <span class="font-bold text-gray-900">Público</span>
                                    <p class="text-xs text-gray-500">Cualquier persona con el enlace puede ver los objetos
                                        de
                                        este bucket.</p>
                                </label>
                            </div>
                        </div>
                        <div class="mt-6">
                            <button type="submit" id="save-visibility-btn"
                                class="btn-aws-primary text-white px-4 py-2 rounded text-sm font-bold opacity-50 cursor-not-allowed"
                                disabled>
                                Actualizar visibilidad
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Zona de Peligro -->
        <div class="bg-white border-2 border-red-200 rounded-lg overflow-hidden shadow-sm">
            <div class="p-4 border-b border-red-200 bg-red-50">
                <h2 class="text-lg font-bold text-red-700">Zona de peligro</h2>
            </div>
            <div class="p-6">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h3 class="text-md font-bold text-gray-900">Eliminar este bucket</h3>
                        <p class="text-sm text-gray-500">Una vez que elimines un bucket, no hay vuelta atrás. Por favor,
                            asegúrate.</p>
                        <p class="text-xs text-red-600 mt-1 font-bold">Esta acción también eliminará todos los archivos
                            contenidos en el bucket.</p>
                    </div>
                    <button type="button" onclick="toggleDeleteModal()"
                        class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded text-sm font-bold transition-colors">
                        Eliminar bucket
                    </button>
                </div>
            </div>
        </div>
    </div>

    @include('bucket.partials.delete-bucket-modal')
@endsection
