// resources/js/client-app.js

import { createApp } from 'vue';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js'; // THAY ĐỔI DÒNG NÀY: Dùng import thay vì require
import ClientChatComponent from './components/ClientChatComponent.vue';
import axios from 'axios';

// Cấu hình Axios để luôn gửi token
const token = localStorage.getItem('authToken');
if (token) {
    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
}

window.Pusher = Pusher; // Gán Pusher đã import vào window.Pusher

// Cấu hình Echo cho Client
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true,
    auth: {
        headers: {
            Authorization: 'Bearer ' + localStorage.getItem('authToken'),
        },
    },
});

const clientAppElement = document.getElementById('client-app');

if (clientAppElement) {
    const props = {
        currentUserId: JSON.parse(clientAppElement.dataset.currentUserId || 'null'),
        adminId: JSON.parse(clientAppElement.dataset.adminId || 'null'),
    };
    const app = createApp(ClientChatComponent, props);
    app.mount(clientAppElement);
}
