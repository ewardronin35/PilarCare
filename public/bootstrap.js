import axios from 'axios';
import Echo from "laravel-echo";
import Pusher from 'pusher-js';
window.Pusher = Pusher;

window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.Pusher = require('pusher-js');
window.Pusher.logToConsole = true;  // Enable Pusher logs
w
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    encrypted: true
});
window.Echo.channel('inventory-channel')
    .listen('LowStockNotification', (e) => {
        console.log(e.message);
    });