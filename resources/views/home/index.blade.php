@extends('layouts.app')

@section('title', 'home')

@section('content')

    <main>
        <nav class="flex text-sm text-gray-600 mb-6 gap-2">
            <span class="hover:underline cursor-pointer text-[#0073bb]">Amazon S3</span>
            <span>&gt;</span>
            <a href="{{ route('bucket.index') }}" class="hover:underline cursor-pointer text-[#0073bb]">Buckets</a>
            <span>&gt;</span>
            <span class="font-semibold text-gray-800">mi-bucket-simulado</span>
        </nav>

        <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                    mi-bucket-simulado
                    <i data-lucide="copy" class="w-4 h-4 text-gray-400 cursor-pointer"></i>
                </h1>
                <p class="text-sm text-gray-500">Objetos en este bucket (Región: us-east-1)</p>
            </div>

            <div class="flex gap-4">
                <div class="bg-white border aws-border p-3 rounded shadow-sm text-center min-w-[120px]">
                    <p class="text-xs text-gray-500 uppercase font-bold">Objetos</p>
                    <p id="stat-count" class="text-xl font-bold">0</p>
                </div>
                <div class="bg-white border aws-border p-3 rounded shadow-sm text-center min-w-[120px]">
                    <p class="text-xs text-gray-500 uppercase font-bold">Tamaño Total</p>
                    <p id="stat-size" class="text-xl font-bold">0 KB</p>
                </div>
            </div>
        </div>

        <div class="bg-white border aws-border p-4 rounded-t-lg flex flex-col gap-4">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-2 flex-1 min-w-[280px]">
                    <div class="relative flex-1">
                        <i data-lucide="search" class="absolute left-3 top-2.5 w-4 h-4 text-gray-400"></i>
                        <input type="text" id="search-input" placeholder="Buscar objetos por nombre..."
                            class="w-full pl-10 pr-4 py-2 border aws-border rounded focus:outline-none focus:ring-1 focus:ring-[#0073bb] text-sm">
                    </div>
                    <select id="sort-select" class="border aws-border p-2 rounded text-sm bg-gray-50 focus:outline-none">
                        <option value="name-asc">Nombre (A-Z)</option>
                        <option value="name-desc">Nombre (Z-A)</option>
                        <option value="date-desc">Más reciente</option>
                        <option value="size-desc">Más grande</option>
                    </select>
                </div>

                <div class="flex items-center gap-2">
                    <button onclick="toggleView('list')" id="view-list-btn"
                        class="p-2 border aws-border rounded bg-gray-100"><i data-lucide="list"
                            class="w-4 h-4"></i></button>
                    <button onclick="toggleView('grid')" id="view-grid-btn"
                        class="p-2 border aws-border rounded bg-white"><i data-lucide="layout-grid"
                            class="w-4 h-4"></i></button>
                    <button onclick="document.getElementById('file-upload').click()"
                        class="btn-aws-primary text-white px-4 py-2 rounded font-bold text-sm flex items-center gap-2">
                        <i data-lucide="upload" class="w-4 h-4"></i> Subir
                    </button>
                    <input type="file" id="file-upload" class="hidden" multiple>
                </div>
            </div>
        </div>

        <div id="drop-zone" class="bg-blue-50 border-2 border-dashed border-blue-400 p-8 text-center hidden mb-4 rounded">
            <p class="text-blue-700 font-medium">Suelta los archivos para subirlos a S3</p>
        </div>

        <div class="bg-white border aws-border border-t-0 rounded-b-lg overflow-hidden min-h-[400px]">
            <div id="list-view" class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="aws-table-header border-b aws-border font-bold">
                        <tr>
                            <th class="p-3 w-10"><input type="checkbox"></th>
                            <th class="p-3">Nombre</th>
                            <th class="p-3">Tipo</th>
                            <th class="p-3">Última modificación</th>
                            <th class="p-3">Tamaño</th>
                            <th class="p-3 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="file-list-body">
                    </tbody>
                </table>
            </div>

            <div id="grid-view" class="hidden p-6 grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6">
            </div>

            <div id="empty-state" class="hidden flex flex-col items-center justify-center py-20 text-gray-500">
                <i data-lucide="folder-open" class="w-16 h-16 mb-4 opacity-20"></i>
                <p>No hay objetos en este bucket.</p>
                <button onclick="document.getElementById('file-upload').click()"
                    class="mt-2 text-[#0073bb] hover:underline">Empieza subiendo un archivo</button>
            </div>
        </div>
    </main>

    <div id="delete-modal" class="fixed inset-0 bg-black/50 hidden flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-lg max-w-md w-full shadow-xl">
            <div class="p-6">
                <h3 class="text-lg font-bold mb-2">Eliminar objetos</h3>
                <p class="text-sm text-gray-600 mb-4">¿Estás seguro de que deseas eliminar permanentemente el archivo
                    <span id="delete-filename" class="font-bold text-gray-900"></span>? Esta acción no se puede
                    deshacer.
                </p>
                <div class="flex justify-end gap-3">
                    <button onclick="closeDeleteModal()"
                        class="px-4 py-2 border aws-border rounded text-sm font-bold hover:bg-gray-50">Cancelar</button>
                    <button id="confirm-delete-btn"
                        class="px-4 py-2 bg-red-600 text-white rounded text-sm font-bold hover:bg-red-700">Eliminar</button>
                </div>
            </div>
        </div>
    </div>

    <div id="toast-container" class="fixed bottom-4 right-4 flex flex-col gap-2 z-50"></div>
@endsection
