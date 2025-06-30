// resources/js/admin-app.js

import { createApp } from 'vue';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
import AdminChatComponent from './components/AdminChatComponent.vue';
import axios from 'axios';

// Cấu hình Axios để luôn gửi token
const token = localStorage.getItem('authToken');
if (token) {
    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
}

window.Pusher = Pusher;

// Cấu hình Echo cho Admin
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true,
    auth: {
        headers: {
            Authorization: 'Bearer ' + localStorage.getItem('authToken'), // Giả sử bạn dùng token
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
    },
});

const adminAppElement = document.getElementById('admin-app');

if (adminAppElement) {
    const props = {
        currentUserId: JSON.parse(adminAppElement.dataset.currentUserId || 'null'),
    };
    const app = createApp(AdminChatComponent, props);
    app.mount(adminAppElement);
}
