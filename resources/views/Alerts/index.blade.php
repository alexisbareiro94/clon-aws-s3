<div class="fixed top-5 right-5 z-50 flex flex-col gap-3 w-full max-w-sm pointer-events-none">
    {{-- Success Alert --}}
    @if (session('success'))
        <div class="alert shadow-xl border border-emerald-500/20 bg-emerald-50 text-emerald-800 p-4 rounded-xl flex items-start gap-4 pointer-events-auto transition-all duration-300"
            id="alert-success">
            <div class="p-1 rounded-full bg-emerald-100 text-emerald-600">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 6 9 17l-5-5" />
                </svg>
            </div>
            <div class="flex-1 pt-0.5">
                <p class="font-bold text-sm">Éxito</p>
                <p class="text-sm opacity-90 leading-relaxed">{{ session('success') }}</p>
            </div>
            <button onclick="closeAlert('alert-success')"
                class="text-emerald-500 hover:text-emerald-700 transition-colors cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </button>
        </div>
    @endif

    {{-- Warning Alert --}}
    @if (session('warning'))
        <div class="alert shadow-xl border border-amber-500/20 bg-amber-50 text-amber-800 p-4 rounded-xl flex items-start gap-4 pointer-events-auto transition-all duration-300"
            id="alert-warning">
            <div class="p-1 rounded-full bg-amber-100 text-amber-600">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m12 9 0 4" />
                    <path d="m12 17 0 .01" />
                    <path
                        d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" />
                </svg>
            </div>
            <div class="flex-1 pt-0.5">
                <p class="font-bold text-sm">Advertencia</p>
                <p class="text-sm opacity-90 leading-relaxed">{{ session('warning') }}</p>
            </div>
            <button onclick="closeAlert('alert-warning')"
                class="text-amber-500 hover:text-amber-700 transition-colors cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </button>
        </div>
    @endif

    {{-- Error Alert --}}
    @if (session('error'))
        <div class="alert shadow-xl border border-rose-500/20 bg-rose-50 text-rose-800 p-4 rounded-xl flex items-start gap-4 pointer-events-auto transition-all duration-300"
            id="alert-error">
            <div class="p-1 rounded-full bg-rose-100 text-rose-600">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10" />
                    <path d="m15 9-6 6" />
                    <path d="m9 9 6 6" />
                </svg>
            </div>
            <div class="flex-1 pt-0.5">
                <p class="font-bold text-sm">Error</p>
                <p class="text-sm opacity-90 leading-relaxed">{{ session('error') }}</p>
            </div>
            <button onclick="closeAlert('alert-error')"
                class="text-rose-500 hover:text-rose-700 transition-colors cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </button>
        </div>
    @endif
</div>

<style>
    .alert-exit {
        opacity: 0;
        transform: translateX(30px);
        margin-bottom: -64px;
        pointer-events: none;
    }
</style>

<script>
    function closeAlert(id) {
        const alert = document.getElementById(id);
        if (alert) {
            alert.classList.add('alert-exit');
            setTimeout(() => {
                alert.remove();
            }, 300);
        }
    }

    // Auto-close after 5 seconds
    document.querySelectorAll('.alert').forEach(alert => {
        setTimeout(() => {
            closeAlert(alert.id);
        }, 5000);
    });
</script>
