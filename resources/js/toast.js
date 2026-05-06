window.showToast = function(type, title, message) {
    // Buscar el contenedor existente (de Alerts/index.blade.php) o crear uno nuevo
    let container = document.querySelector('.fixed.top-5.right-5.z-50.flex.flex-col');
    if (!container) {
        container = document.createElement('div');
        container.className = 'fixed top-5 right-5 z-50 flex flex-col gap-3 w-full max-w-sm pointer-events-none';
        document.body.appendChild(container);
    }

    // Inyectar clase de animación si no existe
    if (!document.getElementById('toast-styles')) {
        const style = document.createElement('style');
        style.id = 'toast-styles';
        style.innerHTML = `
            .alert-exit {
                opacity: 0;
                transform: translateX(30px);
                margin-bottom: -64px;
                pointer-events: none;
            }
        `;
        document.head.appendChild(style);
    }

    const id = 'toast-' + Math.random().toString(36).substr(2, 9);
    let colors = {};
    let icon = '';

    if (type === 'success') {
        colors = {
            border: 'border-emerald-500/20',
            bg: 'bg-emerald-50',
            text: 'text-emerald-800',
            iconBg: 'bg-emerald-100',
            iconText: 'text-emerald-600',
            closeText: 'text-emerald-500',
            closeHover: 'hover:text-emerald-700'
        };
        icon = `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5" /></svg>`;
    } else if (type === 'warning') {
        colors = {
            border: 'border-amber-500/20',
            bg: 'bg-amber-50',
            text: 'text-amber-800',
            iconBg: 'bg-amber-100',
            iconText: 'text-amber-600',
            closeText: 'text-amber-500',
            closeHover: 'hover:text-amber-700'
        };
        icon = `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m12 9 0 4" /><path d="m12 17 0 .01" /><path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" /></svg>`;
    } else { // error
        colors = {
            border: 'border-rose-500/20',
            bg: 'bg-rose-50',
            text: 'text-rose-800',
            iconBg: 'bg-rose-100',
            iconText: 'text-rose-600',
            closeText: 'text-rose-500',
            closeHover: 'hover:text-rose-700'
        };
        icon = `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10" /><path d="m15 9-6 6" /><path d="m9 9 6 6" /></svg>`;
    }

    const toastHTML = `
        <div class="alert shadow-xl border ${colors.border} ${colors.bg} ${colors.text} p-4 rounded-xl flex items-start gap-4 pointer-events-auto transition-all duration-300" id="${id}">
            <div class="p-1 rounded-full ${colors.iconBg} ${colors.iconText}">
                ${icon}
            </div>
            <div class="flex-1 pt-0.5">
                <p class="font-bold text-sm">${title}</p>
                <p class="text-sm opacity-90 leading-relaxed">${message}</p>
            </div>
            <button onclick="window.closeAlertJS('${id}')" class="${colors.closeText} ${colors.closeHover} transition-colors cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </button>
        </div>
    `;

    container.insertAdjacentHTML('beforeend', toastHTML);

    setTimeout(() => {
        window.closeAlertJS(id);
    }, 5000);
};

window.closeAlertJS = function(id) {
    const alert = document.getElementById(id);
    if (alert) {
        alert.classList.add('alert-exit');
        setTimeout(() => {
            alert.remove();
        }, 300);
    }
};
