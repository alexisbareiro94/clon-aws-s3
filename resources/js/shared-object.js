import axios from 'axios';

document.addEventListener('DOMContentLoaded', async () => {
    const fileDataElement = document.getElementById('shared-file-data');
    if (!fileDataElement) return;

    const fetchUrl = fileDataElement.getAttribute('data-fetch-url');
    
    const loadingEl = document.getElementById('image-loading');
    const previewEl = document.getElementById('shared-image-preview');
    const errorEl = document.getElementById('image-error');

    try {
        const response = await axios.get(fetchUrl, {
            responseType: 'blob'
        });

        const blob = response.data;
        const mimeType = response.headers['content-type'] || blob.type;

        if (mimeType.startsWith('image/')) {
            const objectUrl = URL.createObjectURL(blob);
            previewEl.src = objectUrl;

            previewEl.onload = () => {
                loadingEl.classList.add('hidden');
                previewEl.classList.remove('hidden');
            };
        } else {
            loadingEl.classList.add('hidden');
            errorEl.querySelector('p').textContent = `El archivo no es una imagen (${mimeType})`;
            errorEl.classList.remove('hidden');
            errorEl.classList.add('flex');
        }
    } catch (error) {
        console.error('Error al descargar el archivo para previsualización:', error);
        loadingEl.classList.add('hidden');
        errorEl.classList.remove('hidden');
        errorEl.classList.add('flex');
    }
});
