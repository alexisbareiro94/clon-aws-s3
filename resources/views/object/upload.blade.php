@extends('layouts.app')

@section('title', 'Cargar')

@section('breadcrumb')
    <a href="{{ route('bucket.index') }}" class="text-[#0073bb] hover:underline">Buckets</a>
    /
    <a href="{{ route('bucket.show', $bucket) }}" class="text-[#0073bb] hover:underline">{{ $bucket->name }}</a>
    /
    <span class="font-semibold text-gray-800">Cargar</span>
@endsection

@section('content')
    <div class="max-w-6xl mx-auto space-y-6 pb-20">
        <!-- Header Section -->
        <div class="bg-white border border-gray-200 rounded-sm p-6 shadow-sm">
            <h1 class="text-3xl font-semibold text-gray-900 border-b border-gray-100 pb-4">Cargar</h1>
            <p class="mt-4 text-sm text-gray-600">
                Cargue archivos y carpetas a su bucket de S3. Puede arrastrar y soltar archivos en esta página para comenzar
                la carga.
            </p>
        </div>

        <form action="{{ route('object.store', $bucket->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="space-y-6">
                <!-- Files Section -->
                <div class="bg-white border border-gray-200 rounded-sm shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex items-center justify-between">
                        <h2 class="text-lg font-bold text-gray-800">Archivos y carpetas</h2>
                        <div class="flex gap-3">
                            <label
                                class="cursor-pointer bg-white border border-gray-300 text-gray-700 px-4 py-1.5 rounded-sm text-sm font-medium hover:bg-gray-50 transition-colors">
                                Agregar archivos
                                <input type="file" name="files[]" multiple class="hidden" id="fileInput">
                            </label>
                            <label
                                class="cursor-pointer bg-white border border-gray-300 text-gray-700 px-4 py-1.5 rounded-sm text-sm font-medium hover:bg-gray-50 transition-colors">
                                Agregar carpeta
                                <input type="file" name="folders[]" webkitdirectory directory multiple class="hidden">
                            </label>
                        </div>
                    </div>

                    <!-- Drag & Drop / Empty State -->
                    <div id="dropZone"
                        class="p-12 border-2 border-dashed border-gray-200 m-6 rounded-sm flex flex-col items-center justify-center text-center group hover:border-[#0073bb] transition-all cursor-pointer">
                        <div
                            class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4 group-hover:bg-blue-50">
                            <i data-lucide="cloud-upload" class="w-8 h-8 text-gray-400 group-hover:text-[#0073bb]"></i>
                        </div>
                        <p class="text-gray-700 font-medium">Arrastre y suelte archivos y carpetas para cargar</p>
                        <p class="text-sm text-gray-500 mt-1">O haga clic en los botones de arriba para seleccionar sus
                            archivos.</p>
                    </div>

                    <!-- File List Preview (Hidden by default until files selected) -->
                    <div id="filePreview" class="hidden px-6 pb-6">
                        <div class="overflow-x-auto border border-gray-200 rounded-sm">
                            <table class="w-full text-sm text-left text-gray-500">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-200">
                                    <tr>
                                        <th class="px-4 py-3 font-semibold">Nombre</th>
                                        <th class="px-4 py-3 font-semibold text-right">Tamaño</th>
                                        <th class="px-4 py-3 w-10"></th>
                                    </tr>
                                </thead>
                                <tbody id="fileListBody">
                                    <!-- JS items here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Destination Section -->
                <div class="bg-white border border-gray-200 rounded-sm shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h2 class="text-lg font-bold text-gray-800">Destino</h2>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center gap-4 p-4 bg-blue-50 border-l-4 border-[#0073bb] rounded-r-sm">
                            <i data-lucide="info" class="w-5 h-5 text-[#0073bb]"></i>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Bucket de destino: <span
                                        class="font-bold text-[#0073bb]">s3://{{ $bucket->name }}/</span></p>
                                <p class="text-xs text-gray-600 mt-0.5">Los objetos cargados estarán disponibles
                                    inmediatamente en esta ubicación.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Permissions / settings section (simplified) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Permissions -->
                    <div class="bg-white border border-gray-200 rounded-sm shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wider">Permisos</h2>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="flex items-start gap-3">
                                <input type="radio" name="visibility" value="pr" id="priv" checked
                                    class="mt-1">
                                <label for="priv">
                                    <span class="block text-sm font-bold text-gray-900">Privado</span>
                                    <span class="block text-xs text-gray-600 leading-relaxed">Solo el propietario tiene
                                        acceso para leer y escribir.</span>
                                </label>
                            </div>
                            @if ($bucket->visibility == 'pr')
                            <div class="flex items-start gap-3 opacity-50">
                                <input type="radio" name="visibility" value="pu" id="pub" class="mt-1"
                                    disabled title="Consulte su configuración de bucket">
                                <label for="pub">
                                    <span class="block text-sm font-bold text-gray-900">Público (Desactivado)</span>
                                    <span class="block text-xs text-gray-600 leading-relaxed">El acceso público está
                                        bloqueado para este bucket.</span>
                                </label>
                            </div>
                            @else
                            <div class="flex items-start gap-3">
                                <input type="radio" name="visibility" value="pu" id="pub" class="mt-1"
                                    title="Consulte su configuración de bucket">
                                <label for="pub">
                                    <span class="block text-sm font-bold text-gray-900">Público</span>
                                    <span class="block text-xs text-gray-600 leading-relaxed">El acceso público está
                                        bloqueado para este bucket.</span>
                                </label>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Properties -->
                    <div class="bg-white border border-gray-200 rounded-sm shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wider">Clase de almacenamiento
                            </h2>
                        </div>
                        <div class="p-6">
                            <select name="storage_class"
                                class="w-full text-sm border-gray-300 rounded-sm shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="standard">S3 Standard (Frecuente)</option>
                                <option value="infrequent">S3 Infrequent Access</option>
                                <option value="glacier">S3 Glacier</option>
                            </select>
                            <p class="mt-2 text-xs text-gray-500 italic flex items-center gap-1">
                                <i data-lucide="sparkles" class="w-3 h-3"></i>
                                Seleccione la clase que mejor se adapte a su caso de uso.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Actions Sticky/Fixed -->
            <div
                class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 py-3 px-6 shadow-[0_-4px_10px_-4px_rgba(0,0,0,0.1)] z-10">
                <div class="max-w-6xl mx-auto flex justify-end gap-3">
                    <a href="{{ route('bucket.show', $bucket) }}"
                        class="px-6 py-2 border border-gray-300 text-sm font-bold text-gray-600 hover:bg-gray-50 rounded-sm transition-colors">
                        Cancelar
                    </a>
                    <button type="submit"
                        class="px-8 py-2 bg-[#ff9900] hover:bg-[#ec8b00] text-sm font-bold text-[#232f3e] rounded-sm transition-colors shadow-sm">
                        Cargar
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const fileInput = document.getElementById('fileInput');
            const dropZone = document.getElementById('dropZone');
            const filePreview = document.getElementById('filePreview');
            const fileListBody = document.getElementById('fileListBody');

            const updatePreview = (files) => {
                if (files.length > 0) {
                    dropZone.classList.add('hidden');
                    filePreview.classList.remove('hidden');
                    fileListBody.innerHTML = '';

                    Array.from(files).forEach((file, index) => {
                        const row = document.createElement('tr');
                        row.className = 'border-b border-gray-100 hover:bg-gray-50';
                        row.innerHTML = `
                        <td class="px-4 py-3 text-gray-900 flex items-center gap-2">
                             <i data-lucide="file" class="w-4 h-4 text-gray-400"></i>
                             ${file.name}
                        </td>
                        <td class="px-4 py-3 text-right text-gray-500">
                            ${(file.size / 1024 / 1024).toFixed(2)} MB
                        </td>
                        <td class="px-4 py-3">
                             <button type="button" class="text-gray-400 hover:text-red-600">
                                <i data-lucide="x" class="w-4 h-4"></i>
                             </button>
                        </td>
                    `;
                        fileListBody.appendChild(row);
                    });

                    // Re-initialize icons for new elements
                    if (window.lucide) {
                        lucide.createIcons();
                    }
                }
            };

            fileInput.addEventListener('change', (e) => {
                updatePreview(e.target.files);
            });

            // Basic Drag & Drop
            dropZone.addEventListener('dragover', (e) => {
                e.preventDefault();
                dropZone.classList.add('border-[#0073bb]', 'bg-blue-50');
            });

            dropZone.addEventListener('dragleave', () => {
                dropZone.classList.remove('border-[#0073bb]', 'bg-blue-50');
            });

            dropZone.addEventListener('drop', (e) => {
                e.preventDefault();
                dropZone.classList.remove('border-[#0073bb]', 'bg-blue-50');
                const files = e.dataTransfer.files;
                fileInput.files = files;
                updatePreview(files);
            });

            dropZone.addEventListener('click', () => {
                fileInput.click();
            });
        });
    </script>
@endsection
