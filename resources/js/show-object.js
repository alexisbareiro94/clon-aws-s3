
window.createShareLink = function () {
    const modal = document.getElementById('createShareLinkModal');
    if (modal) {
        modal.classList.remove('hidden');
    }
}

window.toggleEditObjectModal = function () {
    const modal = document.getElementById('editObjectModal');
    if (modal) {
        modal.classList.toggle('hidden');
    }
}

window.toggleVisibilityModal = function () {
    const modal = document.getElementById('visibilityModal');
    if (modal) {
        modal.classList.toggle('hidden');
    }
}


window.copyUrl = function (url) {
    navigator.clipboard.writeText(url).then(() => {
        if (window.showToast) {
            window.showToast('success', 'Éxito', 'URL copiada al portapapeles');
        } else {
            alert('URL copiada al portapapeles');
        }
    }).catch(err => {
        if (window.showToast) {
            window.showToast('error', 'Error', 'No se pudo copiar la URL');
        } else {
            alert('Error al copiar la URL');
        }
    });
};

document.addEventListener('DOMContentLoaded', () => {
    // Si los elementos no existen (puede que no estemos en show.blade.php), salimos
    if (!document.getElementById('hidden-expires-at')) return;

    window.currentExpirationMode = 'hours';

    window.setExpirationMode = function (mode) {
        window.currentExpirationMode = mode;

        const modes = ['hours', 'weeks', 'custom', 'unlimited'];
        modes.forEach(m => {
            const btn = document.getElementById(`btn-mode-${m}`);
            const panel = document.getElementById(`panel-${m}`);

            if (!btn) return;

            if (m === mode) {
                btn.classList.replace('bg-gray-100', 'bg-blue-100');
                btn.classList.replace('text-gray-700', 'text-blue-700');
                btn.classList.replace('border-gray-200', 'border-blue-200');
                btn.classList.replace('font-medium', 'font-semibold');
                if (panel) panel.classList.remove('hidden');
            } else {
                btn.classList.replace('bg-blue-100', 'bg-gray-100');
                btn.classList.replace('text-blue-700', 'text-gray-700');
                btn.classList.replace('border-blue-200', 'border-gray-200');
                btn.classList.replace('font-semibold', 'font-medium');
                if (panel) panel.classList.add('hidden');
            }
        });

        window.updateExpiresAtValue();
    };

    window.updateExpiresAtValue = function () {
        const hiddenInput = document.getElementById('hidden-expires-at');
        if (!hiddenInput) return;

        let date = new Date();

        if (window.currentExpirationMode === 'hours') {
            const selectHours = document.getElementById('select-hours');
            const hours = selectHours ? parseInt(selectHours.value) || 1 : 1;
            date.setHours(date.getHours() + hours);
            hiddenInput.value = formatDateTimeLocal(date);
        } else if (window.currentExpirationMode === 'weeks') {
            const selectWeeks = document.getElementById('select-weeks');
            const weeks = selectWeeks ? parseInt(selectWeeks.value) || 1 : 1;
            date.setDate(date.getDate() + (weeks * 7));
            hiddenInput.value = formatDateTimeLocal(date);
        } else if (window.currentExpirationMode === 'custom') {
            const customInput = document.getElementById('input-custom-date');
            hiddenInput.value = customInput && customInput.value ? customInput.value : '';
        } else if (window.currentExpirationMode === 'unlimited') {
            hiddenInput.value = '';
        }
    };

    function formatDateTimeLocal(date) {
        const tzoffset = (new Date()).getTimezoneOffset() * 60000;
        const localISOTime = (new Date(date - tzoffset)).toISOString().slice(0, 16);
        return localISOTime;
    }

    // Initialize
    window.updateExpiresAtValue();

    // Image Preview Logic
    const imgElement = document.getElementById('preview-image');
    if (imgElement && imgElement.dataset.previewUrl) {
        const imageUrl = imgElement.dataset.previewUrl;

        fetch(imageUrl)
            .then(response => {
                if (!response.ok) throw new Error('No se pudo cargar la imagen');
                return response.blob();
            })
            .then(blob => {
                const objectUrl = URL.createObjectURL(blob);
                imgElement.src = objectUrl;
                imgElement.classList.remove('hidden');
            })
            .catch(error => {
                console.error('Error cargando la vista previa:', error);
            });
    }
});