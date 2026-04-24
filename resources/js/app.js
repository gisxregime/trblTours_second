import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// Password toggle functionality for all password fields
document.addEventListener('DOMContentLoaded', () => {
    const toggleButtons = document.querySelectorAll('[data-password-toggle]');

    toggleButtons.forEach((button) => {
        button.addEventListener('click', (e) => {
            e.preventDefault();

            const fieldId = button.getAttribute('data-password-toggle');
            const input = document.querySelector(`[data-password-toggle-input="${fieldId}"]`);

            if (!input) return;

            const isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';
            button.textContent = isPassword ? 'Hide' : 'Show';
        });
    });
});
