@forelse ($buckets as $bucket)
    <tr class="border-b aws-border hover:bg-gray-50">
        <td class="p-3">
            <a href="{{ route('bucket.show', $bucket) }}" class="font-bold text-[#0073bb] hover:underline">
                {{ $bucket->name }}
            </a>
        </td>
        <td class="p-3 text-gray-600 font-mono text-xs">{{ $bucket->slug }}</td>
        <td class="p-3">
            <span class="px-2 py-0.5 bg-gray-100 border aws-border rounded text-[10px] font-bold">
                {{ $bucket->visibility_label }}
            </span>
        </td>
        <td class="p-3 text-gray-500">{{ $bucket->created_at->format('Y-m-d') }}</td>
        <td class="p-3 flex gap-2">
            <a href="{{ route('bucket.settings', $bucket) }}" title="Configuración">
                <span>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>
                </span>
            </a>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="4" class="p-12 text-center text-gray-400">
            <div class="flex flex-col items-center gap-3">
                <i data-lucide="inbox" class="w-12 h-12 opacity-30"></i>
                <p>No hay buckets todavía.
                    <a href="{{ route('bucket.create') }}" class="text-[#0073bb] hover:underline">Crea el
                        primero</a>.
                </p>
            </div>
        </td>
    </tr>
@endforelse
