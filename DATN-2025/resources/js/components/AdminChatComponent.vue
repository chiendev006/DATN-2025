    <template>
        <div class="chat-floating-container">
            <button class="chat-toggle-button" @click="toggleChat">
                <i class="fa-solid fa-comments"></i>
                <span v-if="hasNewMessage" class="new-message-indicator"></span>
            </button>

            <div class="chat-box" :class="{ 'open': isChatOpen }">
                <div class="chat-header">
<h3>Admin Chat</h3>

                </div>
                <div class="admin-chat-container">
<div class="user-list">
    <ul>
        <li v-for="user in users" :key="user.id" @click="selectUser(user)" :class="{ 'active': selectedUser && selectedUser.id === user.id }">
            {{ user.name }}
            <span v-if="unreadMessages[user.id]" class="unread-count">{{ unreadMessages[user.id] }}</span>
        </li>
    </ul>
</div>
<div class="chat-window">
    <div v-if="selectedUser" class="chat-content">
        <div class="messages-container" ref="messagesContainer">
            <div v-for="message in messages" :key="message.id" :class="{ 'my-message': message.sender_id === currentUserId, 'their-message': message.sender_id !== currentUserId }">
                <div class="message-bubble">
                    <strong>{{ message.sender_name === 'admin' ? 'Bạn' : message.sender_name }} <br></strong>
                    <template v-if="message.type === 'image'">
                        <img :src="message.content" style="max-width: 200px; max-height: 200px; border-radius: 8px; margin: 5px 0;">
                    </template>
                    <template v-else>
                        <span class="message-content" v-html="formatMessageContent(message.content)"></span>
                    </template>
                    <div class="timestamp">{{ message.created_at }}</div>
                </div>
            </div>
        </div>
        <div class="message-input">
            <input type="file" ref="fileInput" style="display:none" @change="handleFileChange">
            <button @click="$refs.fileInput.click()" class="attach-btn" title="Đính kèm ảnh"><i class="fa fa-paperclip"></i></button>
            <input type="text" v-model="newMessage" @keyup.enter="sendMessage()" placeholder="Nhập tin nhắn...">
            <button @click="sendMessage()">Gửi</button>
        </div>
    </div>
    <div v-else class="no-chat-selected">
        <p>Chọn một cuộc trò chuyện.</p>
    </div>
</div>
                </div>
            </div>
        </div>
    </template>

    <script>
    import axios from 'axios';

    export default {
        props: ['currentUserId'],
        data() {
            return {
                isChatOpen: false,
                hasNewMessage: false,
                users: [],
                selectedUser: null,
                messages: [],
                newMessage: '',
                unreadMessages: {},
                notificationSound: null,
            };
        },
        watch: {
            isChatOpen(newValue) {
                if (newValue) {
this.hasNewMessage = false;
if (this.users.length === 0) {
    this.fetchUsers();
}
                }
            }
        },
        mounted() {
            if (this.currentUserId) {
                this.initNotificationSound();
                this.listenForIncomingMessages();
            }
        },
        methods: {
            toggleChat() {
                this.isChatOpen = !this.isChatOpen;
            },
            closeChat() {
                this.isChatOpen = false;
            },
            fetchUsers() {
                axios.get('/api/admin/chat/users')
.then(response => {
    this.users = response.data.users;
    this.sortUsersByLastMessage();
})
.catch(error => console.error('Lỗi khi lấy danh sách người dùng:', error));
            },
            fetchUserInfo(userId) {
                return axios.get(`/api/admin/chat/user/${userId}`)
                    .then(response => response.data.user)
                    .catch(error => {
                        console.error('Lỗi khi lấy thông tin user:', error);
                        return null;
                    });
            },
            addNewUserToUsersList(user) {
                const existingUser = this.users.find(u => u.id === user.id);
                if (!existingUser) {
                    user.last_message_time = new Date().toISOString();
                    this.users.push(user);
                    this.sortUsersByLastMessage();
                    console.log('Đã thêm user mới vào danh sách:', user);
                }
            },
            sortUsersByLastMessage() {
                this.users.sort((a, b) => {
                    const timeA = new Date(a.last_message_time || 0);
                    const timeB = new Date(b.last_message_time || 0);
                    return timeB - timeA;
                });
            },
            updateUserLastMessageTime(userId) {
                const user = this.users.find(u => u.id === userId);
                if (user) {
                    user.last_message_time = new Date().toISOString();
                    this.sortUsersByLastMessage();
                }
            },
            selectUser(user) {
                this.selectedUser = user;
                this.messages = [];
                this.fetchChatHistory();
                if (this.unreadMessages[user.id]) {
this.unreadMessages[user.id] = 0;
this.recalculateTotalUnread();
                }
            },
            fetchChatHistory() {
                if (!this.selectedUser) return;
                axios.get(`/api/admin/chat/history/${this.selectedUser.id}`)
.then(response => {
    this.messages = response.data.messages.map(msg => ({
        ...msg,
        sender_name: msg.sender_id === this.currentUserId ? 'Bạn' : this.selectedUser.name
    }));
    this.$nextTick(this.scrollToBottom);
})
.catch(error => console.error('Lỗi khi lấy lịch sử chat admin:', error));
            },
            listenForIncomingMessages() {
                if (!window.Echo || !this.currentUserId) {
console.error("Echo or User ID not available.");
return;
                }

                console.log(`[Chat] Admin is attempting to listen on channel: chat.user.${this.currentUserId}`);

                window.Echo.private(`chat.user.${this.currentUserId}`)
.listen('.message.sent', (e) => {
    console.log('[Chat] Message received by admin:', e);

    // Kiểm tra xem sender có trong danh sách users hiện tại không
    const existingUser = this.users.find(u => u.id === e.sender_id);

    if (!existingUser) {
        // Nếu user chưa có trong danh sách, fetch thông tin user mới
        this.fetchUserInfo(e.sender_id).then(newUser => {
            if (newUser) {
                this.addNewUserToUsersList(newUser);
            }
        });
    } else {
        // Nếu user đã có trong danh sách, cập nhật thời gian tin nhắn cuối cùng
        this.updateUserLastMessageTime(e.sender_id);
    }

    if (this.isChatOpen && this.selectedUser && this.selectedUser.id === e.sender_id) {
        this.messages.push({ ...e, sender_name: this.selectedUser.name });
        this.$nextTick(this.scrollToBottom);
    } else {
        const senderId = e.sender_id;
        this.unreadMessages[senderId] = (this.unreadMessages[senderId] || 0) + 1;
        this.recalculateTotalUnread();
        console.log(`[Chat] Unread message from user ${senderId}. Total unread:`, this.unreadMessages);

        // Phát âm thanh thông báo khi có tin nhắn mới và chat đang đóng
        if (!this.isChatOpen) {
            this.playNotificationSound();
        }
    }
})
.error((error) => {
    console.error(`[Chat] Error listening to channel chat.user.${this.currentUserId}:`, error);
});
            },
            handleFileChange(e) {
                const file = e.target.files[0];
                if (!file) return;
                const formData = new FormData();
                formData.append('image', file);
                axios.post('/api/chat/upload', formData, {
                    headers: { 'Content-Type': 'multipart/form-data' }
                }).then(res => {
                    this.sendMessage(res.data.url, 'image');
                });
            },
            sendMessage(content = null, type = 'text') {
                const messageContent = content || this.newMessage || '';
                if (!messageContent.toString().trim() || !this.selectedUser) return;
                const tempId = `temp_${Date.now()}`;
                const optimisticMessage = {
                    id: tempId,
                    content: messageContent,
                    type,
                    sender_id: this.currentUserId,
                    sender_name: 'Bạn',
                    receiver_id: this.selectedUser.id,
                    created_at: new Date().toLocaleTimeString('vi-VN', { hour: '2-digit', minute: '2-digit' }),
                    status: 'sending'
                };
                this.messages.push(optimisticMessage);
                if (!content) this.newMessage = '';
                this.$nextTick(this.scrollToBottom);

                // Cập nhật thời gian tin nhắn cuối cùng khi admin gửi tin nhắn
                this.updateUserLastMessageTime(this.selectedUser.id);

                axios.post('/api/admin/chat/send', {
                    receiver_id: this.selectedUser.id,
                    message_content: messageContent,
                    type
                }).then(response => {
                    const sentMessage = this.messages.find(m => m.id === tempId);
                    if (sentMessage) {
                        Object.assign(sentMessage, response.data.data);
                        sentMessage.status = 'sent';
                    }
                }).catch(error => {
                    const failedMessage = this.messages.find(m => m.id === tempId);
                    if (failedMessage) {
                        failedMessage.status = 'failed';
                    }
                    console.error('Lỗi khi gửi tin nhắn từ admin:', error);
                });
            },
            scrollToBottom() {
                const container = this.$refs.messagesContainer;
                if (container) container.scrollTop = container.scrollHeight;
            },
            recalculateTotalUnread() {
                this.hasNewMessage = Object.values(this.unreadMessages).some(count => count > 0);
            },
            formatMessageContent(content) {
                if (!content) return '';
                const urlRegex = /(https?:\/\/[\w\-._~:/?#[\]@!$&'()*+,;=%]+)(?=\s|$)/g;
                const escapeHtml = (str) => str.replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m]));
                return escapeHtml(content).replace(urlRegex, url => `<a href="${url}" target="_blank">${url}</a>`);
            },
            initNotificationSound() {
                try {
                    this.notificationSound = new Audio('/sounds/notification.mp3');
                    this.notificationSound.volume = 0.5;

                    // Thêm event listeners để debug
                    this.notificationSound.addEventListener('loadstart', () => console.log('Bắt đầu tải âm thanh'));
                    this.notificationSound.addEventListener('canplay', () => console.log('Âm thanh đã sẵn sàng phát'));
                    this.notificationSound.addEventListener('error', (e) => console.error('Lỗi tải âm thanh:', e));
                    this.notificationSound.addEventListener('ended', () => console.log('Âm thanh đã kết thúc'));

                    console.log('Âm thanh thông báo đã được khởi tạo thành công');
                } catch (error) {
                    console.error('Không thể khởi tạo âm thanh thông báo:', error);
                }
            },
            playNotificationSound() {
                if (this.notificationSound) {
                    try {
                        console.log('Đang cố gắng phát âm thanh thông báo...');

                        // Kiểm tra trạng thái của audio
                        if (this.notificationSound.readyState >= 2) { // HAVE_CURRENT_DATA
                            // Reset audio để có thể phát lại
                            this.notificationSound.currentTime = 0;

                            // Thử phát âm thanh
                            const playPromise = this.notificationSound.play();

                            if (playPromise !== undefined) {
                                playPromise.then(() => {
                                    console.log('Đã phát âm thanh thông báo thành công');
                                }).catch(error => {
                                    console.log('Không thể phát âm thanh thông báo:', error);
                                    // Thử tạo âm thanh đơn giản bằng Web Audio API như fallback
                                    this.playFallbackSound();
                                });
                            }
                        } else {
                            console.log('Âm thanh chưa sẵn sàng, thử fallback...');
                            this.playFallbackSound();
                        }
                    } catch (error) {
                        console.log('Lỗi khi phát âm thanh thông báo:', error);
                        this.playFallbackSound();
                    }
                } else {
                    console.log('Không có âm thanh thông báo, thử fallback...');
                    this.playFallbackSound();
                }
            },
            playFallbackSound() {
                try {
                    // Tạo âm thanh đơn giản bằng Web Audio API
                    const audioContext = new (window.AudioContext || window.webkitAudioContext)();
                    const oscillator = audioContext.createOscillator();
                    const gainNode = audioContext.createGain();

                    oscillator.connect(gainNode);
                    gainNode.connect(audioContext.destination);

                    oscillator.frequency.setValueAtTime(800, audioContext.currentTime);
                    oscillator.frequency.setValueAtTime(600, audioContext.currentTime + 0.1);
                    oscillator.frequency.setValueAtTime(800, audioContext.currentTime + 0.2);

                    gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
                    gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.3);

                    oscillator.start(audioContext.currentTime);
                    oscillator.stop(audioContext.currentTime + 0.3);

                    console.log('Đã phát âm thanh fallback');
                } catch (error) {
                    console.log('Không thể phát âm thanh fallback:', error);
                }
            },

        }
    }
    </script>

    <style scoped>
    /* Floating container and button */
    .chat-floating-container {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 1000;
    }
    .chat-toggle-button {
        background-color: #428cd6; /* Red for admin */
        color: white;
        border: none;
        border-radius: 50%;
        width: 60px;
        height: 60px;
        font-size: 24px;
        display: flex;
        justify-content: center;
        align-items: center;
        cursor: pointer;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        position: relative;
    }
    .new-message-indicator {
        position: absolute;
        top: 5px;
        right: 5px;
        width: 15px;
        height: 15px;
        background-color: #007bff;
        border-radius: 50%;
        border: 2px solid white;
    }

    /* Chat box */
    .chat-box {
        position: absolute;
        bottom: 70px;
        right: 0;
        width: 500px; /* Wider for admin view */
        height: 600px; /* Taller for admin view */
        background-color: white;
        border: 1px solid #ddd;
        border-radius: 8px;
        box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.3);
        display: flex;
        flex-direction: column;
        overflow: hidden;
        opacity: 0;
        visibility: hidden;
        transform: translateY(20px);
        transition: all 0.3s ease-in-out;
    }
    .chat-box.open {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }
    .chat-header {
        background-color: #428cd6;
        color: white;
        padding: 10px 15px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .close-button {
        background: none; border: none; color: white; font-size: 1.5em; cursor: pointer;
    }


    /* Admin layout inside chatbox */
    .admin-chat-container {
        display: flex;
        flex-grow: 1;
        height: calc(100% - 48px); /* Adjust based on header height */
    }
    .user-list {
        width: 35%;
        border-right: 1px solid #ccc;
        overflow-y: auto;
    }
    .user-list ul { list-style: none; padding: 0; margin: 0; }
    .user-list li { padding: 10px; cursor: pointer; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center; font-size: 0.9em; }
    .user-list li:hover, .user-list li.active { background-color: #f0f0f0; }
    .unread-count { background-color: #007bff; color: white; border-radius: 10px; padding: 2px 6px; font-size: 11px; }

    .chat-window { width: 65%; display: flex; flex-direction: column; }
    .chat-content { display: flex; flex-direction: column; height: 100%; }

    .messages-container { flex-grow: 1; overflow-y: auto; padding: 10px; background-color: #f9f9f9; }
    .my-message {
        background-color: #dcf8c6;
        margin-left: auto;
        text-align: right;
        align-items: flex-end;
        display: flex;
        flex-direction: column;
    }
    .message-bubble{
        width: 100%;
    }
    .my-message .message-bubble {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
    }
    .my-message strong {
        text-align: right;
        width: 100%;
    }
    .their-message { background-color: #e0e0e0; margin-right: auto; }
    .my-message, .their-message { padding: 8px 12px; border-radius: 15px; margin-bottom: 8px; max-width: 80%; word-wrap: break-word; }
    .timestamp { display: block; font-size: 0.7em; color: #888; margin-top: 2px; }

    .message-input { display: flex; padding: 10px; border-top: 1px solid #eee; }
    .message-input input { flex-grow: 1; padding: 8px; border: 1px solid #ccc; border-radius: 20px; margin-right: 10px; }
    .message-input button {  max-height: 50px;  background-color: #428cd6; color: white; border: none; border-radius: 20px; padding: 8px 15px; cursor: pointer; }

    .attach-btn {
        background-color: #428cd6;
        color: white;
        border: none;
        border-radius: 50%;
        width: 35px;
        height: 35px;
        display: flex;
        justify-content: center;
        align-items: center;
        cursor: pointer;
        margin-right: 8px;
        transition: background-color 0.3s ease;
    }

    .attach-btn:hover {
        background-color: #2c5aa0;
    }

    .no-chat-selected { display: flex;justify-content: center; align-items: center; height: 100%; color: #888; }

    .message-content a {
        color: #1976d2;
        text-decoration: none;
        word-break: break-all;
        transition: text-decoration 0.2s;
    }
    .message-content a:hover {
        text-decoration: underline;
    }
    </style>
