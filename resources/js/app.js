import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,  // Make sure this key is set correctly
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,  // This should pull from your .env file
    forceTLS: true,  // Force TLS for secure connections
    encrypted: true,  // Make sure encryption is enabled
    wsHost: window.location.hostname,
    wsPort: 6001,  // Make sure this port is open if you're using WebSockets
    wssPort: 443,  // Ensure you're using the correct port for WSS connections
    disableStats: true,  // Disable stats collection for the free tier
    enabledTransports: ['ws', 'wss'],  // Enable both WebSocket and Secure WebSocket connections
});
