@extends('layouts.app')

@section('title', 'Buckets')

@section('content')
    <div class="mb-6 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Buckets</h1>
            <p class="text-sm text-gray-500">Los buckets son contenedores para objetos almacenados en S3.</p>
        </div>
        <a href="{{ route('bucket.create') }}"
            class="cursor-pointer btn-aws-primary text-white px-4 py-2 rounded font-bold text-sm flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
            </svg>
            Crear bucket
        </a>
    </div>

    <div class="bg-white border aws-border rounded-lg overflow-hidden">
        <div class="p-4 border-b aws-border bg-gray-50">
            <div class="relative max-w-md">
                <i  class="absolute left-3 top-2.5 w-4 h-4 text-gray-400"></i>
                <input type="text" id="search-buckets" placeholder="Buscar buckets..."
                    class="w-full pl-10 pr-4 py-2 border aws-border rounded text-sm focus:outline-none focus:ring-1 focus:ring-[#0073bb]">
            </div>
        </div>

        <table class="w-full text-left text-sm">
            <thead class="aws-table-header border-b aws-border font-bold">
                <tr>
                    <th class="p-3">Nombre</th>
                    <th class="p-3">Slug</th>
                    <th class="p-3">Acceso</th>
                    <th class="p-3">Fecha de creación</th>
                    <th class="p-3">Acciones</th>
                </tr>
            </thead>
            <tbody id="buckets-table-body">
                @include('bucket.partials.buckets-table')
            </tbody>
        </table>
    </div>
@endsection
