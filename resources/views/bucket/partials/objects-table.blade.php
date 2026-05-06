@forelse ($objects as $object)
    <tr class="border-b aws-border hover:bg-gray-50 group">
        <td class="p-3"><input type="checkbox"></td>
        <td
            class="p-3 font-medium {{ $object->shareLinks->count() == 0 ? 'text-[#0073bb]' : 'text-green-500' }} cursor-pointer hover:underline">
            <a href="{{ route('object.show', [$bucket, $object]) }}">
                {{ $object->original_name }}
            </a>
        </td>
        <td class="p-3 text-gray-500">
            {{ $object->mime_type ? explode('/', $object->mime_type)[1] : 'file' }}
        </td>
        <td class="p-3 text-gray-500">{{ $object->updated_at->format('d/m/Y H:i') }}</td>
        <td class="p-3 text-gray-500">
            @php
                $b = $object->size_bytes;
                if ($b >= 1073741824) {
                    echo round($b / 1073741824, 2) . ' GB';
                } elseif ($b >= 1048576) {
                    echo round($b / 1048576, 2) . ' MB';
                } elseif ($b >= 1024) {
                    echo round($b / 1024, 2) . ' KB';
                } else {
                    echo $b . ' B';
                }
            @endphp
        </td>
        <td class="p-3 text-gray-500">
            {{ $object->visibility == 'pr' ? 'Privado' : 'Público' }}
        </td>
        <td class="p-3 text-right">
            <button></button>
            <button class="cursor-pointer hover:text-blue-500" title="Generar Link">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M13.19 8.688a4.5 4.5 0 0 1 1.242 7.244l-4.5 4.5a4.5 4.5 0 0 1-6.364-6.364l1.757-1.757m13.35-.622 1.757-1.757a4.5 4.5 0 0 0-6.364-6.364l-4.5 4.5a4.5 4.5 0 0 0 1.242 7.244" />
                </svg>
            </button>
            <button type="button"
                onclick="document.getElementById('delete-modal-{{ $object->id }}').classList.remove('hidden')"
                class="cursor-pointer p-1 hover:text-red-600" title="Eliminar">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                </svg>
            </button>

            <div id="delete-modal-{{ $object->id }}" class="hidden delete-modal-container">
                @include('partials.delete-modal', ['objecto' => $object])
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="6">
            <div class="flex flex-col items-center justify-center py-20 text-gray-500">
                <i data-lucide="folder-open" class="w-16 h-16 mb-4 opacity-20"></i>
                <p>No hay objetos en este bucket.</p>
            </div>
        </td>
    </tr>
@endforelse
