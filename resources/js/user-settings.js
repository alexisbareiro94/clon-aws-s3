document.addEventListener('DOMContentLoaded', () => {
    // --- Modal Logic ---
    const modal = document.getElementById('apiKeyModal');
    const modalContent = document.getElementById('apiKeyModalContent');
    const openBtn = document.getElementById('openApiKeyModal');
    const closeBtn = document.getElementById('closeApiKeyModalBtn');
    const closeBg = document.getElementById('closeApiKeyModalBg');
    const cancelBtn = document.getElementById('cancelApiKeyBtn');

    const adminCheckbox = document.getElementById('admin_permission');
    const individualCheckboxes = document.querySelectorAll('.individual-permission');

    const expirationSection = document.getElementById('expiration-section');
    const customExpiryContainer = document.getElementById('custom-expiry-container');
    const expiresOptionRadios = document.querySelectorAll('.expires-option');

    const openModal = () => {
        if (!modal) return;
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            modalContent?.classList.remove('scale-95');
            modalContent?.classList.add('scale-100');
        }, 10);
    };

    const closeModal = () => {
        if (!modal) return;
        modal.classList.add('opacity-0');
        modalContent?.classList.remove('scale-100');
        modalContent?.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    };

    openBtn?.addEventListener('click', openModal);
    closeBtn?.addEventListener('click', closeModal);
    closeBg?.addEventListener('click', closeModal);
    cancelBtn?.addEventListener('click', closeModal);

    // Logic for checkboxes
    adminCheckbox?.addEventListener('change', (e) => {
        const isChecked = e.target.checked;
        individualCheckboxes.forEach(cb => {
            cb.checked = isChecked;
            if (isChecked) {
                cb.parentElement.classList.add('opacity-50');
                cb.disabled = true;
            } else {
                cb.parentElement.classList.remove('opacity-50');
                cb.disabled = false;
            }
        });
    });

    individualCheckboxes.forEach(cb => {
        cb.addEventListener('change', () => {
            if (!cb.checked && adminCheckbox.checked) {
                adminCheckbox.checked = false;
            }
            const allChecked = Array.from(individualCheckboxes).every(c => c.checked);
            if (allChecked) {
                adminCheckbox.checked = true;
                adminCheckbox.dispatchEvent(new Event('change'));
            }
        });
    });

    // Expiration Options Logic
    expiresOptionRadios?.forEach(radio => {
        radio.addEventListener('change', () => {
            if (radio.value === 'custom') {
                customExpiryContainer?.classList.remove('hidden');
            } else {
                customExpiryContainer?.classList.add('hidden');
            }
        });
    });

    // --- Toggle Tokens Visibility ---
    const toggleTokensBtn = document.getElementById('toggleTokensVisibility');
    const tokensContainer = document.getElementById('tokensContainer');
    const showText = document.getElementById('showTokensText');
    const hideText = document.getElementById('hideTokensText');

    toggleTokensBtn?.addEventListener('click', () => {
        const isHidden = tokensContainer.classList.contains('hidden');
        if (isHidden) {
            tokensContainer.classList.remove('hidden');
            showText.classList.add('hidden');
            hideText.classList.remove('hidden');
        } else {
            tokensContainer.classList.add('hidden');
            showText.classList.remove('hidden');
            hideText.classList.add('hidden');
        }
    });

    // --- Copy Token Logic ---
    window.copyToClipboard = function(text, btnId) {
        navigator.clipboard.writeText(text).then(() => {
            if (window.showToast) {
                window.showToast('success', 'Copiado', 'El token ha sido copiado al portapapeles');
            }
            
            // Visual feedback on button
            const btn = document.getElementById(btnId);
            if (btn) {
                const originalHTML = btn.innerHTML;
                btn.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                    </svg>
                `;
                setTimeout(() => {
                    btn.innerHTML = originalHTML;
                }, 2000);
            }
        }).catch(err => {
            if (window.showToast) {
                window.showToast('error', 'Error', 'No se pudo copiar el texto');
            }
        });
    };

    // --- Revoke Token Modal Logic ---
    const revokeModal = document.getElementById('revokeTokenModal');
    const revokeModalContent = document.getElementById('revokeTokenModalContent');
    const revokeTokenBtns = document.querySelectorAll('.revoke-token-btn');
    const closeRevokeModalBtn = document.getElementById('closeRevokeModalBtn');
    const closeRevokeModalBg = document.getElementById('closeRevokeModalBg');
    const cancelRevokeBtn = document.getElementById('cancelRevokeBtn');
    const revokeTokenForm = document.getElementById('revokeTokenForm');
    const revokeTokenName = document.getElementById('revokeTokenName');

    const openRevokeModal = (tokenId, tokenName) => {
        if (!revokeModal || !revokeTokenForm) return;
        
        revokeTokenForm.action = `/revokeToken/${tokenId}`;
        revokeTokenName.textContent = tokenName;
        
        revokeModal.classList.remove('hidden');
        setTimeout(() => {
            revokeModal.classList.remove('opacity-0');
            revokeModalContent?.classList.remove('scale-95');
            revokeModalContent?.classList.add('scale-100');
        }, 10);
    };

    const closeRevokeModal = () => {
        if (!revokeModal) return;
        revokeModal.classList.add('opacity-0');
        revokeModalContent?.classList.remove('scale-100');
        revokeModalContent?.classList.add('scale-95');
        setTimeout(() => {
            revokeModal.classList.add('hidden');
        }, 300);
    };

    revokeTokenBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const tokenId = btn.dataset.tokenId;
            const tokenName = btn.dataset.tokenName;
            openRevokeModal(tokenId, tokenName);
        });
    });

    closeRevokeModalBtn?.addEventListener('click', closeRevokeModal);
    closeRevokeModalBg?.addEventListener('click', closeRevokeModal);
    cancelRevokeBtn?.addEventListener('click', closeRevokeModal);
});
