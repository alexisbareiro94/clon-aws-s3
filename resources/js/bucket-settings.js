const nameInput = document.getElementById('name-input');
const nameButton = document.getElementById('save-name-btn');

if (nameInput && nameButton) {
    const initialName = nameInput.value;

    nameInput.addEventListener('input', () => {
        if (nameInput.value !== initialName && nameInput.value.trim().length >= 3) {
            nameInput.classList.add('border-blue-500');
            nameButton.disabled = false;
            nameButton.classList.remove('opacity-50', 'cursor-not-allowed');
            nameButton.classList.add('opacity-100');
        } else {
            nameInput.classList.remove('border-blue-500');
            nameButton.disabled = true;
            nameButton.classList.remove('opacity-100');
            nameButton.classList.add('opacity-50', 'cursor-not-allowed');
        }
    });
}

const visibilityOptions = document.getElementById('visibility-options');
const visibilityButton = document.getElementById('save-visibility-btn');

if (visibilityOptions && visibilityButton) {
    const initialVisibility = visibilityOptions.dataset.initialVisibility;
    const radios = visibilityOptions.querySelectorAll('.visibility-radio');

    radios.forEach(radio => {
        radio.addEventListener('change', () => {
            const selectedVisibility = visibilityOptions.querySelector('.visibility-radio:checked').value;

            if (selectedVisibility !== initialVisibility) {
                visibilityButton.disabled = false;
                visibilityButton.classList.remove('opacity-50', 'cursor-not-allowed');
                visibilityButton.classList.add('opacity-100');
            } else {
                visibilityButton.disabled = true;
                visibilityButton.classList.remove('opacity-100');
                visibilityButton.classList.add('opacity-50', 'cursor-not-allowed');
            }
        });
    });
}


// Delete Modal Logic
const deleteModal = document.getElementById('delete-bucket-modal');
const confirmInput = document.getElementById('confirm-bucket-name');
const confirmSwitch = document.getElementById('confirm-switch');
const finalDeleteBtn = document.getElementById('final-delete-btn');
const hiddenNameInput = document.getElementById('hidden-bucket-name');
const hiddenConfirmInput = document.getElementById('hidden-confirm-switch');

window.toggleDeleteModal = function () {
    if (deleteModal) {
        deleteModal.classList.toggle('hidden');
        if (!deleteModal.classList.contains('hidden')) {
            confirmInput.focus();
        }
    }
}

if (confirmInput && confirmSwitch && finalDeleteBtn) {
    const bucketName = confirmInput.dataset.bucketName;

    const validateDelete = () => {
        const nameMatches = confirmInput.value === bucketName;
        const switchOn = confirmSwitch.checked;

        if (hiddenNameInput) hiddenNameInput.value = confirmInput.value;
        if (hiddenConfirmInput) hiddenConfirmInput.value = switchOn ? "1" : "0";

        if (nameMatches && switchOn) {
            finalDeleteBtn.disabled = false;
            finalDeleteBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            finalDeleteBtn.classList.add('opacity-100');
        } else {
            finalDeleteBtn.disabled = true;
            finalDeleteBtn.classList.remove('opacity-100');
            finalDeleteBtn.classList.add('opacity-50', 'cursor-not-allowed');
        }
    };

    confirmInput.addEventListener('input', validateDelete);
    confirmSwitch.addEventListener('change', validateDelete);
}
