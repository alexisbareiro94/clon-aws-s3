@extends('layouts.app')

@section('title', 'Crear bucket')

@section('breadcrumb')
    <a href="{{ route('bucket.index') }}" class="text-[#0073bb] hover:underline">Buckets</a>
    <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-gray-400"></i>
    <span class="font-semibold text-gray-800">Crear bucket</span>
@endsection

@section('content')
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Crear bucket</h1>

    <form method="POST" action="{{ route('bucket.store') }}">
        @csrf

        <div class="bg-white border aws-border rounded-lg p-6 max-w-3xl mb-6">
            <h2 class="text-lg font-bold mb-4">Configuración general</h2>

            <div class="mb-6">
                <label for="bucket-name" class="block text-sm font-bold mb-1">Nombre del bucket</label>
                <p class="text-xs text-gray-500 mb-2">El nombre debe tener entre 3 y 120 caracteres.</p>
                <input type="text" id="bucket-name" name="name" value="{{ old('name') }}"
                    placeholder="mi-nuevo-bucket-ejemplo"
                    class="w-full p-2 border aws-border rounded text-sm focus:ring-1 focus:ring-[#0073bb] outline-none @error('name') border-red-400 @enderror">
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-2">
                <label for="bucket-visibility" class="block text-sm font-bold mb-1">Visibilidad</label>
                <select id="bucket-visibility" name="visibility"
                    class="w-full p-2 border aws-border rounded text-sm bg-gray-50 outline-none">
                    <option value="pr" {{ old('visibility', 'pr') === 'pr' ? 'selected' : '' }}>Privado</option>
                    <option value="pu" {{ old('visibility') === 'pu' ? 'selected' : '' }}>Público</option>
                </select>
                @error('visibility')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="flex justify-end gap-3 max-w-3xl">
            <a href="{{ route('bucket.index') }}"
                class="px-4 py-2 border aws-border rounded text-sm font-bold hover:bg-gray-50">
                Cancelar
            </a>
            <button type="submit" class="btn-aws-primary text-white px-6 py-2 rounded font-bold text-sm">
                Crear bucket
            </button>
        </div>
    </form>

    <script>
        lucide.createIcons();
    </script>
@endsection
