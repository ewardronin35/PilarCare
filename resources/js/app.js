// resources/js/app.js

import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

// Assign Pusher to the window object
window.Pusher = Pusher;

// Initialize Laravel Echo with Pusher
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: window.PUSHER_APP_KEY, // Use global variable
    cluster: window.PUSHER_APP_CLUSTER, // Use global variable
    forceTLS: true, // Use TLS for secure connections
    encrypted: true,
});

// Listen for specific events
// Listen for specific events
window.Echo.channel('inventory-channel')
    .listen('.inventory-expiring', (data) => {
        console.log('Notification Received:', data);
        
        // Update notification count badge
        let badge = document.querySelector('#notification-icon .badge'); // Corrected selector
        if (badge) {
            let currentCount = parseInt(badge.innerText) || 0;
            badge.innerText = currentCount + 1;
            badge.style.display = 'inline'; // Ensure it's visible
        }

        // Prepend new notification to the dropdown
        let dropdownMenu = document.querySelector('#notification-dropdown');
        if (dropdownMenu) {
            // Remove the "No new notifications" message if it exists
            let noNotifications = dropdownMenu.querySelector('.dropdown-item');
            if (noNotifications && noNotifications.textContent === 'No new notifications') {
                noNotifications.remove();
            }

            let newNotification = document.createElement('div');
            newNotification.classList.add('dropdown-item');
            newNotification.innerHTML = `
                <a href="#" class="dropdown-item">
                    ${data.title}: ${data.message} on ${new Date(data.expiry_date).toLocaleDateString()}.
                </a>
            `;
            dropdownMenu.prepend(newNotification);
        }

        // Display SweetAlert notification
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'info',
                title: 'Inventory Alert',
                text: `${data.title}: ${data.message}`,
                timer: 5000,
                showConfirmButton: false
            });
        }
    });
