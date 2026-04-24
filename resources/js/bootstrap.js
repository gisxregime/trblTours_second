import axios from 'axios';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.axios = axios;
window.Pusher = Pusher;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

const pusherKey = import.meta.env.VITE_PUSHER_APP_KEY;
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

if (pusherKey) {
	const cluster = import.meta.env.VITE_PUSHER_APP_CLUSTER || 'mt1';
	const scheme = import.meta.env.VITE_PUSHER_SCHEME || 'https';
	const host = import.meta.env.VITE_PUSHER_HOST || `ws-${cluster}.pusher.com`;
	const port = Number(import.meta.env.VITE_PUSHER_PORT || (scheme === 'https' ? 443 : 80));
	const authHeaders = csrfToken ? { 'X-CSRF-TOKEN': csrfToken } : {};

	window.Echo = new Echo({
		broadcaster: 'pusher',
		key: pusherKey,
		cluster,
		wsHost: host,
		wsPort: port,
		wssPort: port,
		forceTLS: scheme === 'https',
		enabledTransports: ['ws', 'wss'],
		authEndpoint: '/broadcasting/auth',
		auth: {
			headers: authHeaders,
		},
	});
}
