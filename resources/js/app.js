import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

const initPasswordToggles = () => {
	if (document.getElementById('password-toggle-style')) {
		return;
	}

	const style = document.createElement('style');
	style.id = 'password-toggle-style';
	style.textContent = `
		.password-toggle-wrapper {
			position: relative;
			width: 100%;
		}

		.password-toggle-button {
			position: absolute;
			right: 10px;
			top: 50%;
			transform: translateY(-50%);
			border: none;
			background: transparent;
			color: #6b7280;
			font-size: 12px;
			font-weight: 600;
			cursor: pointer;
			padding: 4px 6px;
			line-height: 1;
		}

		.password-toggle-button:hover {
			color: #374151;
		}

		.password-toggle-button:focus-visible {
			outline: 2px solid #9ca3af;
			outline-offset: 1px;
			border-radius: 4px;
		}
	`;
	document.head.appendChild(style);

	const passwordInputs = document.querySelectorAll('input[type="password"]:not([data-password-toggle])');

	passwordInputs.forEach((input) => {
		if (!(input instanceof HTMLInputElement) || input.disabled) {
			return;
		}

		const wrapper = document.createElement('div');
		wrapper.className = 'password-toggle-wrapper';

		const parent = input.parentNode;
		if (!parent) {
			return;
		}

		parent.insertBefore(wrapper, input);
		wrapper.appendChild(input);

		const button = document.createElement('button');
		button.type = 'button';
		button.className = 'password-toggle-button';
		button.textContent = 'Show';
		button.setAttribute('aria-label', 'Show password');

		const originalPaddingRight = window.getComputedStyle(input).paddingRight;
		input.style.paddingRight = `max(${originalPaddingRight}, 72px)`;
		input.dataset.passwordToggle = 'true';

		button.addEventListener('click', () => {
			const shouldShowPassword = input.type === 'password';
			input.type = shouldShowPassword ? 'text' : 'password';
			button.textContent = shouldShowPassword ? 'Hide' : 'Show';
			button.setAttribute('aria-label', shouldShowPassword ? 'Hide password' : 'Show password');
		});

		wrapper.appendChild(button);
	});
};

if (document.readyState === 'loading') {
	document.addEventListener('DOMContentLoaded', initPasswordToggles);
} else {
	initPasswordToggles();
}
