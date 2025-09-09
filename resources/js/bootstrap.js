// -----------------------------
// 1. Axios
// -----------------------------
import axios from 'axios';
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// -----------------------------
// 2. jQuery + Bootstrap
// -----------------------------
import jQuery from 'jquery';
window.$ = window.jQuery = jQuery;
import 'bootstrap/dist/js/bootstrap.bundle.min.js';

// -----------------------------
// 3. Toastr
// -----------------------------
import toastr from 'toastr';
import 'toastr/build/toastr.min.css';
window.toastr = toastr;

// -----------------------------
// 4. Pusher + Echo
// -----------------------------
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true,
    auth: {
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    }
});
