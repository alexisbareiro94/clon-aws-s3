@extends('layouts.app')

@section('title', 'Ajustes de Usuario')

@section('breadcrumb')
    <a href="{{ route('home') }}" class="hover:text-gray-900 transition-colors">Inicio</a>
    <span class="text-gray-400">/</span>
    <span class="text-gray-900 font-medium">Ajustes</span>
@endsection

@section('content')
    <div class="max-w-4xl mx-auto py-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Ajustes de Usuario</h1>
            <p class="text-gray-500 mt-2 text-sm">Administra tu información personal y genera claves de API para acceso
                programático.</p>
        </div>

        <div class="space-y-8 mt-6">
            <!-- Personal Information Section -->
            <div
                class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden transition-all hover:shadow-md duration-300">
                <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-800">Información Personal</h2>
                        <p class="text-gray-500 text-xs mt-1">Actualiza tu nombre y correo electrónico.</p>
                    </div>
                    <div class="p-2 bg-blue-50 text-blue-600 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                        </svg>
                    </div>
                </div>
                <div class="p-6">
                    <form class="space-y-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="space-y-2">
                                <label for="name" class="block text-sm font-medium text-gray-700">Nombre
                                    Completo</label>
                                <input type="text" id="name" name="name" value="{{ auth()->user()->name ?? '' }}"
                                    class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors text-sm text-gray-800 placeholder-gray-400">
                            </div>
                            <div class="space-y-2">
                                <label for="email" class="block text-sm font-medium text-gray-700">Correo
                                    Electrónico</label>
                                <input type="email" id="email" name="email"
                                    value="{{ auth()->user()->email ?? '' }}"
                                    class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors text-sm text-gray-800 placeholder-gray-400">
                            </div>
                        </div>

                        <div class="pt-4 flex justify-end">
                            <button type="button"
                                class="px-5 py-2.5 bg-[#232f3e] hover:bg-[#1a232e] text-white text-sm font-medium rounded-lg transition-colors shadow-sm active:scale-95 duration-200">
                                Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- API Keys Section -->
            <div
                class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden transition-all hover:shadow-md duration-300">
                <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-800">Claves de API (API Keys)</h2>
                        <p class="text-gray-500 text-xs mt-1">Genera claves para autenticar aplicaciones de terceros o
                            requests programáticos.</p>
                    </div>
                    <div class="p-2 bg-orange-50 text-orange-500 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1 1 21.75 8.25Z" />
                        </svg>
                    </div>
                </div>

                <div class="p-6">
                    <!-- Info Alert -->
                    <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 mb-6 flex items-start gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-5 text-amber-500 mt-0.5 shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                        </svg>
                        <div>
                            <h4 class="text-sm font-semibold text-amber-800">Mantén tus claves seguras</h4>
                            <p class="text-xs text-amber-700 mt-1">Nunca compartas tus claves de API públicamente ni las
                                incluyas en repositorios de código abiertos.</p>
                        </div>
                    </div>

                    @if ($tokens->count() > 0)
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-sm font-medium text-gray-800">Tus API Keys ({{ $tokens->count() }})</h3>
                            <div class="flex items-center gap-3">
                                <button type="button" id="toggleTokensVisibility"
                                    class="text-xs text-blue-600 hover:text-blue-800 font-medium transition-colors">
                                    <span id="showTokensText">Mostrar Claves</span>
                                    <span id="hideTokensText" class="hidden">Ocultar Claves</span>
                                </button>
                                <button type="button" id="openApiKeyModal"
                                    class="inline-flex items-center gap-1 text-xs bg-orange-500 hover:bg-orange-600 text-white px-3 py-1.5 rounded-md transition-colors shadow-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                        class="size-3.5">
                                        <path
                                            d="M10.75 4.75a.75.75 0 0 0-1.5 0v4.5h-4.5a.75.75 0 0 0 0 1.5h4.5v4.5a.75.75 0 0 0 1.5 0v-4.5h4.5a.75.75 0 0 0 0-1.5h-4.5v-4.5Z" />
                                    </svg>
                                    Nueva
                                </button>
                            </div>
                        </div>

                        <div id="tokensContainer" class="hidden overflow-x-auto border border-gray-100 rounded-xl">
                            <table class="w-full text-left text-sm">
                                <thead class="bg-gray-50 text-gray-600 font-medium">
                                    <tr>
                                        <th class="px-4 py-3 border-b border-gray-100">Nombre</th>
                                        <th class="px-4 py-3 border-b border-gray-100">Accesos</th>
                                        <th class="px-4 py-3 border-b border-gray-100">Creación</th>
                                        <th class="px-4 py-3 border-b border-gray-100">Último uso</th>
                                        <th class="px-4 py-3 border-b border-gray-100 text-right">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    @foreach ($tokens as $token)
                                        <tr class="hover:bg-gray-50/50 transition-colors">
                                            <td class="px-4 py-4 font-medium text-gray-900">{{ $token->name }}</td>
                                            <td class="px-4 py-4">
                                                <div class="flex flex-wrap gap-1">
                                                    @foreach ($token->abilities as $ability)
                                                        <span
                                                            class="px-2 py-0.5 bg-blue-50 text-blue-600 text-[10px] font-bold uppercase rounded border border-blue-100">
                                                            {{ $ability == '*' ? 'ADMIN' : $ability }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            </td>
                                            <td class="px-4 py-4 text-gray-500 text-xs">
                                                {{ $token->created_at->format('d M, Y H:i') }}</td>
                                            <td class="px-4 py-4 text-gray-500 text-xs">
                                                {{ $token->last_used_at ? $token->last_used_at->diffForHumans() : 'Nunca usado' }}
                                            </td>
                                            <td class="px-4 py-4 text-right">
                                                <div class="flex items-center justify-end gap-2">
                                                    <!-- Delete button -->
                                                    <button type="button"
                                                        class="p-1.5 text-gray-400 hover:text-rose-500 transition-colors revoke-token-btn"
                                                        title="Eliminar token"
                                                        data-token-id="{{ $token->id }}"
                                                        data-token-name="{{ $token->name }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                            class="size-4">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <!-- Empty State -->
                        <div class="border-2 border-dashed border-gray-200 rounded-xl p-8 text-center bg-gray-50/50">
                            <div
                                class="inline-flex items-center justify-center w-12 h-12 bg-white rounded-full shadow-sm border border-gray-100 mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-6 text-gray-400">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                                </svg>
                            </div>
                            <h3 class="text-sm font-medium text-gray-800">No hay claves activas</h3>
                            <p class="text-xs text-gray-500 mt-1 mb-5">Aún no has generado ninguna API Key.</p>
                            <button type="button" id="openApiKeyModal"
                                class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 hover:text-gray-900 text-sm font-medium rounded-lg transition-colors shadow-sm active:scale-95 duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                    class="size-4 text-gray-400">
                                    <path
                                        d="M10.75 4.75a.75.75 0 0 0-1.5 0v4.5h-4.5a.75.75 0 0 0 0 1.5h4.5v4.5a.75.75 0 0 0 1.5 0v-4.5h4.5a.75.75 0 0 0 0-1.5h-4.5v-4.5Z" />
                                </svg>
                                Crear nueva API Key
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        @if (session('plainTextToken'))
            <!-- Modal para Mostrar el Token recién creado -->
            <div id="newTokenModal"
                class="fixed inset-0 z-50 flex items-center justify-center transition-opacity duration-300">
                <div class="fixed inset-0 bg-black/60 backdrop-blur-md" onclick="this.parentElement.remove()"></div>

                <div
                    class="bg-white rounded-2xl shadow-2xl z-10 w-full max-w-lg mx-4 overflow-hidden transform transition-all duration-300 border border-emerald-100">
                    <div class="bg-emerald-500 px-6 py-8 text-white text-center">
                        <div
                            class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4 border border-white/30">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="2.5" stroke="currentColor" class="size-8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold">¡Token Generado con Éxito!</h3>
                        <p class="text-emerald-50 text-sm mt-1 opacity-90 text-center px-4">Por seguridad, solo podrás ver
                            esta clave una vez. Asegúrate de copiarla ahora.</p>
                    </div>

                    <div class="p-8">
                        <div class="space-y-4">
                            <div class="bg-gray-50 border-2 border-dashed border-gray-200 rounded-xl p-6 relative group">
                                <p
                                    class="text-center font-mono text-lg text-gray-800 break-all select-all tracking-wider font-bold">
                                    {{ session('plainTextToken') }}
                                </p>

                                <button type="button" id="copyTokenBtnMain"
                                    onclick="window.copyToClipboard('{{ session('plainTextToken') }}', 'copyTokenBtnMain')"
                                    class="absolute -bottom-5 left-1/2 -translate-x-1/2 bg-white border border-gray-200 shadow-lg px-6 py-2.5 rounded-full text-xs font-bold text-gray-700 hover:bg-gray-50 hover:text-emerald-600 transition-all active:scale-95 flex items-center gap-2 group-hover:border-emerald-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="2" stroke="currentColor" class="size-4 text-emerald-500">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 0 1-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 0 1 1.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 0 0-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 0 1-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 0 0-3.375-3.375h-1.5a1.125 1.125 0 0 1-1.125-1.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H9.75" />
                                    </svg>
                                    COPIAR TOKEN
                                </button>
                            </div>

                            <div class="pt-10 flex flex-col items-center">
                                <button type="button" onclick="this.closest('#newTokenModal').remove()"
                                    class="w-full py-3 bg-gray-900 hover:bg-black text-white rounded-xl font-bold text-sm transition-all shadow-md active:scale-[0.98]">
                                    He guardado mi token, cerrar
                                </button>
                                <p class="text-[10px] text-gray-400 mt-4 uppercase tracking-widest font-black">Amazon S3
                                    Security Layer</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Modal de Crear API Key -->
        <div id="apiKeyModal"
            class="fixed inset-0 z-50 flex items-center justify-center hidden opacity-0 transition-opacity duration-300">
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" id="closeApiKeyModalBg"></div>

            <div class="bg-white rounded-xl shadow-2xl z-10 w-full max-w-md mx-4 overflow-hidden transform scale-95 transition-transform duration-300"
                id="apiKeyModalContent">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                    <h3 class="text-lg font-semibold text-gray-800">Crear nueva API Key</h3>
                    <button type="button" id="closeApiKeyModalBtn"
                        class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="p-6">
                    <form id="createApiKeyForm" method="POST" action="{{ route('createToken') }}">
                        @csrf
                        <div class="space-y-5">
                            <div>
                                <label for="token_name" class="block text-sm font-medium text-gray-700">Nombre del
                                    Token</label>
                                <input type="text" id="token_name" name="token_name" placeholder="Ej. Mi App Backend"
                                    required
                                    class="mt-1 w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-colors text-sm text-gray-800 placeholder-gray-400">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Permisos</label>
                                <div class="space-y-3">
                                    <label
                                        class="flex items-start gap-3 p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                        <div class="flex items-center h-5">
                                            <input type="checkbox" id="admin_permission" name="permissions[]"
                                                value="*"
                                                class="w-4 h-4 text-orange-500 rounded border-gray-300 focus:ring-orange-500 cursor-pointer">
                                        </div>
                                        <div>
                                            <span class="block text-sm font-medium text-gray-800">Admin (Acceso
                                                Total)</span>
                                            <span class="block text-xs text-gray-500 mt-0.5">Permite realizar todas las
                                                acciones sin restricciones.</span>
                                        </div>
                                    </label>

                                    <div class="grid grid-cols-2 gap-2 mt-2">
                                        <label
                                            class="flex items-center gap-2 p-2 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                            <input type="checkbox" name="permissions[]" value="create"
                                                class="individual-permission w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500 cursor-pointer">
                                            <span class="text-sm text-gray-700">Crear (Create)</span>
                                        </label>
                                        <label
                                            class="flex items-center gap-2 p-2 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                            <input type="checkbox" name="permissions[]" value="view"
                                                class="individual-permission w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500 cursor-pointer">
                                            <span class="text-sm text-gray-700">Ver (View)</span>
                                        </label>
                                        <label
                                            class="flex items-center gap-2 p-2 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                            <input type="checkbox" name="permissions[]" value="update"
                                                class="individual-permission w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500 cursor-pointer">
                                            <span class="text-sm text-gray-700">Actualizar (Update)</span>
                                        </label>
                                        <label
                                            class="flex items-center gap-2 p-2 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                            <input type="checkbox" name="permissions[]" value="delete"
                                                class="individual-permission w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500 cursor-pointer">
                                            <span class="text-sm text-gray-700">Eliminar (Delete)</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div id="expiration-section">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Expira</label>
                                <div class="space-y-3">
                                    <label class="flex items-center gap-2 p-2 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                        <input type="radio" name="expires_option" value="unlimited" checked
                                            class="expires-option w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500 cursor-pointer">
                                        <span class="text-sm text-gray-700">Sin límite</span>
                                    </label>
                                    <div class="flex items-center gap-2 p-2 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                        <input type="radio" name="expires_option" value="hours"
                                            class="expires-option w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500 cursor-pointer">
                                        <span class="text-sm text-gray-700 w-20">En</span>
                                        <input type="number" id="expires_hours" name="expires_hours" min="1" placeholder="1"
                                            class="w-20 px-2 py-1 bg-gray-50 border border-gray-200 rounded text-sm text-gray-800 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none">
                                        <span class="text-sm text-gray-700">hora(s)</span>
                                    </div>
                                    <div class="flex items-center gap-2 p-2 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                        <input type="radio" name="expires_option" value="weeks"
                                            class="expires-option w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500 cursor-pointer">
                                        <span class="text-sm text-gray-700 w-20">En</span>
                                        <input type="number" id="expires_weeks" name="expires_weeks" min="1" placeholder="1"
                                            class="w-20 px-2 py-1 bg-gray-50 border border-gray-200 rounded text-sm text-gray-800 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none">
                                        <span class="text-sm text-gray-700">semana(s)</span>
                                    </div>
                                    <label class="flex items-center gap-2 p-2 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                        <input type="radio" name="expires_option" value="custom"
                                            class="expires-option w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500 cursor-pointer">
                                        <span class="text-sm text-gray-700">Personalizado</span>
                                    </label>
                                </div>
                                <div id="custom-expiry-container" class="hidden mt-2">
                                    <input type="datetime-local" id="expires_at" name="expires_at"
                                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-colors text-sm text-gray-800">
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end gap-3">
                            <button type="button" id="cancelApiKeyBtn"
                                class="px-4 py-2 bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 text-sm font-medium rounded-lg transition-colors shadow-sm active:scale-95 duration-200">
                                Cancelar
                            </button>
                            <button type="submit"
                                class="px-5 py-2 bg-[#232f3e] hover:bg-[#1a232e] text-white text-sm font-medium rounded-lg transition-colors shadow-sm active:scale-95 duration-200">
                                Generar Token
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal de Confirmación para Revocar Token -->
        <div id="revokeTokenModal"
            class="fixed inset-0 z-50 flex items-center justify-center hidden opacity-0 transition-opacity duration-300">
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" id="closeRevokeModalBg"></div>

            <div class="bg-white rounded-xl shadow-2xl z-10 w-full max-w-md mx-4 overflow-hidden transform scale-95 transition-transform duration-300"
                id="revokeTokenModalContent">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-red-50/50">
                    <h3 class="text-lg font-semibold text-gray-800">Revocar Token</h3>
                    <button type="button" id="closeRevokeModalBtn"
                        class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="p-6">
                    <div class="text-center mb-6">
                        <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-6 text-red-600">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                            </svg>
                        </div>
                        <p class="text-gray-600">¿Estás seguro de que deseas revocar el token?</p>
                        <p class="text-sm text-gray-500 mt-2">Token: <span id="revokeTokenName"
                                class="font-semibold text-gray-800"></span></p>
                        <p class="text-xs text-red-500 mt-2">Esta acción no se puede deshacer.</p>
                    </div>

                    <form id="revokeTokenForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <div class="flex justify-end gap-3">
                            <button type="button" id="cancelRevokeBtn"
                                class="px-4 py-2 bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 text-sm font-medium rounded-lg transition-colors shadow-sm active:scale-95 duration-200">
                                Cancelar
                            </button>
                            <button type="submit"
                                class="px-5 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm active:scale-95 duration-200">
                                Revocar Token
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
