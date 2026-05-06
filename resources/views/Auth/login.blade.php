@extends('layouts.app')

@section('title', 'Iniciar Sesión')

@section('content')
<div class="min-h-screen bg-zinc-50 flex flex-col justify-center py-12 px-6 lg:px-8 font-sans">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <!-- Logo or Icon Placeholder -->
        <div class="flex justify-center">
            <div class="h-12 w-12 rounded-xl bg-zinc-900 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="white" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                </svg>
            </div>
        </div>
        
        <h2 class="mt-6 text-center text-3xl font-bold tracking-tight text-zinc-900">
            Bienvenido de nuevo
        </h2>
        <p class="mt-2 text-center text-sm text-zinc-600">
            Ingresa a tu cuenta para continuar
        </p>
    </div>

    <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-[440px]">
        <div class="bg-white py-10 px-8 shadow-sm border border-zinc-200 rounded-2xl">
            <form action="{{ route('login') }}" method="POST" class="space-y-6">
                @csrf
                
                <div>
                    <label for="email" class="block text-sm font-semibold text-zinc-900 mb-2">
                        Correo electrónico
                    </label>
                    <input id="email" name="email" type="email" autocomplete="email" required autofocus
                        class="block w-full rounded-lg border border-zinc-300 px-4 py-2.5 text-zinc-900 placeholder-zinc-400 focus:border-zinc-900 focus:ring-1 focus:ring-zinc-900 outline-none transition-all sm:text-sm"
                        placeholder="tu@ejemplo.com">
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold text-zinc-900 mb-2">
                        Contraseña
                    </label>
                    <div class="relative">
                        <input id="password" name="password" type="password" required
                            class="block w-full rounded-lg border border-zinc-300 px-4 py-2.5 text-zinc-900 placeholder-zinc-400 focus:border-zinc-900 focus:ring-1 focus:ring-zinc-900 outline-none transition-all sm:text-sm"
                            placeholder="••••••••">
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember-me" name="remember-me" type="checkbox"
                            class="h-4 w-4 rounded border-zinc-300 text-zinc-900 focus:ring-zinc-900">
                        <label for="remember-me" class="ml-2 block text-sm text-zinc-600">
                            Recordarme
                        </label>
                    </div>

                    <div class="text-sm">
                        <a href="#" class="font-semibold text-zinc-900 hover:underline decoration-zinc-900/30 underline-offset-4">
                            ¿Olvidaste tu contraseña?
                        </a>
                    </div>
                </div>

                <div>
                    <button type="submit"
                        class="flex w-full justify-center rounded-lg bg-zinc-900 px-4 py-3 text-sm font-semibold text-white shadow-sm hover:bg-zinc-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-zinc-900 transition-colors">
                        Iniciar sesión
                    </button>
                </div>
            </form>

            <div class="mt-8">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center" aria-hidden="true">
                        <div class="w-full border-t border-zinc-200"></div>
                    </div>
                    <div class="relative flex justify-center text-sm font-medium leading-6">
                        <span class="bg-white px-4 text-zinc-500">O continúa con</span>
                    </div>
                </div>

                <div class="mt-6">
                    <button type="button" class="flex w-full items-center justify-center gap-3 rounded-lg border border-zinc-300 bg-white px-4 py-2.5 text-sm font-semibold text-zinc-900 shadow-sm hover:bg-zinc-50 ring-1 ring-inset ring-zinc-300 focus:ring-zinc-900 transition-all">
                        <svg class="h-5 w-5" aria-hidden="true" viewBox="0 0 24 24">
                            <path d="M12.48 10.92v3.28h7.84c-.24 1.84-.909 3.157-1.901 4.148-1.223 1.223-3.11 2.56-6.419 2.56-5.174 0-9.282-4.182-9.282-9.358S6.826 3.012 12 3.012c2.73 0 4.816 1.083 6.362 2.53l2.313-2.313C18.298 1.05 15.53 0 12 0 5.4 0 0 5.4 0 12s5.4 12 12 12c3.58 0 6.26-1.18 8.35-3.35 2.14-2.14 2.82-5.16 2.82-7.53 0-.71-.06-1.39-.17-2.02h-10.52z" fill="currentColor" />
                        </svg>
                        <span>Google</span>
                    </button>
                </div>
            </div>
        </div>

        <p class="mt-8 text-center text-sm text-zinc-600">
            ¿No tienes una cuenta?
            <a href="{{ route('register') }}" class="font-semibold text-zinc-900 hover:underline decoration-zinc-900/30 underline-offset-4">
                Regístrate gratis
            </a>
        </p>
    </div>
</div>
@endsection
