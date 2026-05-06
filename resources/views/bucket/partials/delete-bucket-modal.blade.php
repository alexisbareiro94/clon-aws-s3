<div id="delete-bucket-modal"
    class="hidden fixed inset-0 p-4 flex flex-wrap justify-center items-center w-full h-full z-[1000] before:fixed before:inset-0 before:w-full before:h-full before:bg-[rgba(0,0,0,0.5)] overflow-auto">
    <div class="w-full max-w-lg bg-white shadow-xl rounded-lg p-6 relative z-[1001]">
        {{-- Botón cerrar --}}
        <button type="button" onclick="toggleDeleteModal()"
            class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <div class="sm:flex sm:items-start">
            <div
                class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                <h3 class="text-xl font-bold text-gray-900">
                    Eliminar bucket
                </h3>
                <div class="mt-2">
                    <p class="text-sm text-gray-500">
                        Esta acción no se puede deshacer. Se eliminarán permanentemente el bucket <span
                            class="font-bold text-gray-900">{{ $bucket->name }}</span> y todos los objetos contenidos en
                        él.
                    </p>
                </div>

                <div class="mt-6 space-y-4">
                    <div>
                        <label for="confirm-bucket-name" class="block text-sm font-medium text-gray-700 mb-1">
                            Para confirmar, escriba <span class="font-mono font-bold">{{ $bucket->name }}</span> a
                            continuación:
                        </label>
                        <input type="text" id="confirm-bucket-name" data-bucket-name="{{ $bucket->name }}"
                            class="w-full px-3 py-2 border aws-border rounded text-sm focus:outline-none focus:ring-1 focus:ring-red-500 bg-white"
                            placeholder="Nombre del bucket">
                    </div>

                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-md border aws-border">
                        <span class="text-sm font-medium text-gray-700">Confirmo que deseo eliminar este bucket</span>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" id="confirm-switch" class="sr-only peer">
                            <div
                                class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-red-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-red-600">
                            </div>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8 flex flex-col sm:flex-row-reverse gap-3">
            <form action="{{ route('bucket.destroy', $bucket) }}" method="POST" class="w-full sm:w-auto">
                @csrf
                @method('DELETE')
                <input type="hidden" name="name" id="hidden-bucket-name">
                <input type="hidden" name="confirm" id="hidden-confirm-switch" value="0">
                <button type="submit" id="final-delete-btn" disabled
                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-bold text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:text-sm opacity-50 cursor-not-allowed transition-all">
                    Eliminar definitivamente
                </button>
            </form>
            <button type="button" onclick="toggleDeleteModal()"
                class="w-full sm:w-auto inline-flex justify-center rounded-md border aws-border shadow-sm px-4 py-2 bg-white text-base font-bold text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0073bb] sm:text-sm">
                Cancelar
            </button>
        </div>
    </div>
</div>
