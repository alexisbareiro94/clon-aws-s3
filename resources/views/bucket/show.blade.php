@extends('layouts.app')

@section('title', $bucket->name)

@section('breadcrumb')
    <a href="{{ route('bucket.index') }}" class="text-[#0073bb] hover:underline">Buckets</a>
    /
    <span class="font-semibold text-gray-800">{{ $bucket->name }}</span>
@endsection

@section('content')
    <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                <i data-lucide="hard-drive" class="w-6 h-6 text-orange-400"></i>
                {{ $bucket->name }}
                <td class="p-3 flex gap-2">
                    <a href="{{ route('bucket.settings', $bucket) }}" title="Configuración">
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                        </span>
                    </a>
                </td>
            </h1>
            <p class="text-sm text-gray-500 mt-1">
                Slug: <span class="font-mono font-medium">{{ $bucket->slug }}</span>
                &mdash; Acceso: <span class="font-medium">{{ $bucket->visibility_label }}</span>
            </p>
        </div>

        <div class="flex gap-4">
            <div class="bg-white border aws-border p-3 rounded shadow-sm text-center min-w-[120px]">
                <p class="text-xs text-gray-500 uppercase font-bold">Objetos</p>
                <p class="text-xl font-bold">{{ $objects->total() }}</p>
            </div>
            <div class="bg-white border aws-border p-3 rounded shadow-sm text-center min-w-[120px]">
                <p class="text-xs text-gray-500 uppercase font-bold">Tamaño Total</p>
                <p class="text-xl font-bold" id="total-size">
                    {{ formatBytes($totalBytes) }}
                </p>
            </div>
        </div>
    </div>

    {{-- Toolbar --}}
    <div class="bg-white border aws-border p-4 rounded-t-lg flex flex-col gap-4">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div class="relative flex-1 min-w-[280px] max-w-md">
                <i data-lucide="search" class="absolute left-3 top-2.5 w-4 h-4 text-gray-400"></i>
                <input type="text" id="search-objects" data-bucket-slug="{{ $bucket->slug }}"
                    placeholder="Buscar objetos..."
                    class="w-full pl-10 pr-4 py-2 border aws-border rounded focus:outline-none text-sm">
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('bucket.settings', $bucket) }}"
                    class="bg-white border aws-border hover:bg-gray-50 text-gray-700 px-4 py-2 rounded font-bold text-sm flex items-center gap-2">
                    <i data-lucide="settings" class="w-4 h-4"></i>
                    Configuración
                </a>
                <a href="{{ route('object.index', $bucket->id) }}"
                    class="btn-aws-primary text-white px-4 py-2 rounded font-bold text-sm flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 16.5V9.75m0 0 3 3m-3-3-3 3M6.75 19.5a4.5 4.5 0 0 1-1.41-8.775 5.25 5.25 0 0 1 10.233-2.33 3 3 0 0 1 3.758 3.848A3.752 3.752 0 0 1 18 19.5H6.75Z" />
                    </svg>
                    Subir
                </a>
            </div>
        </div>
    </div>

    {{-- Tabla de objetos --}}
    <div class="bg-white border aws-border border-t-0 rounded-b-lg overflow-hidden min-h-[300px]">
        <table class="w-full text-left text-sm">
            <thead class="aws-table-header border-b aws-border font-bold">
                <tr>
                    <th class="p-3 w-10"><input type="checkbox" id="check-all"></th>
                    <th class="p-3">Nombre</th>
                    <th class="p-3">Tipo</th>
                    <th class="p-3">Última modificación</th>
                    <th class="p-3">Tamaño</th>
                    <th class="p-3">Visibilidad</th>
                    <th class="p-3 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody id="file-list-body">
                @include('bucket.partials.objects-table')
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $objects->links() }}
    </div>
@endsection
