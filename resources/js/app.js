// resources/js/app.js

// Import dependencies
import Echo from 'laravel-echo';
import 'sweetalert2/dist/sweetalert2.min.css'; // Import SweetAlert2 CSS
import 'toastr/build/toastr.min.css'; // Import Toastr CSS

// Assign Pusher to the window object

// Configure Toastr options globally
toastr.options = {
    "closeButton": true,
    "progressBar": true,
    "positionClass": "toast-top-right",
    "timeOut": "5000"
};

// Function to Perform AJAX Fetch Notifications

