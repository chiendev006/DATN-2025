    <template>
        <div class="chat-floating-container">
            <button class="chat-toggle-button" @click="toggleChat">
                <i class="fa-solid fa-comments"></i>
                <span v-if="hasNewMessage" class="new-message-indicator"></span>
            </button>

            <div class="chat-box" :class="{ 'open': isChatOpen }">
                <div class="chat-header">
<h3>Admin Chat</h3>
<button class="close-button" @click="closeChat">&times;</button>
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
            <div v-for="message in messages" :key="message.id" :class="{ 'my-message': message.sender_id === currentUserId, 'their-message': message.sender_id !== currentUserId }"
                @mouseenter="hoveredMessageId = message.id" @mouseleave="hoveredMessageId = null">
                <div class="message-bubble">
                    <strong>{{ message.sender_name === 'admin' ? 'Bạn' : message.sender_name }} <br></strong>
                 <template v-if="editingMessageId === message.id">
                        <input v-model="editingContent" @keyup.enter="confirmEditMessage(message)" @blur="cancelEditMessage" class="edit-input" />
                      <div style="display: block;">  <button @mousedown.prevent="confirmEditMessage(message)" class="edit-btn">Lưu</button>
                        <button @click="cancelEditMessage" class="cancel-btn">Hủy</button></div>
                    </template>
                    <template v-else>
                        <template v-if="message.type === 'image'">
                            <img :src="message.content" style="max-width: 200px; max-height: 200px; border-radius: 8px; margin: 5px 0;">
                        </template>
                        <template v-else>
                            <span class="message-content" v-html="formatMessageContent(message.content)"></span>
                        <small><span style="margin-left: 0px; color: red;" v-if="message.is_edited && message.content !== '*đã thu hồi*'" class="edited-flag">*đã chỉnh sửa</span></small>
                        </template>
                        <div class="timestamp">{{ message.created_at }}</div>
                        <div v-if="message.sender_id === currentUserId && hoveredMessageId === message.id && message.content !== '*đã thu hồi*'" class="message-actions">
                            <button v-if="message.type === 'text'" @click="startEditMessage(message)" class="edit-btn">Sửa</button>
                            <button @click="deleteMessage(message)" class="delete-btn">Thu hồi</button>
                        </div>
                    </template>
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
                editingMessageId: null,
                editingContent: '',
                hoveredMessageId: null,
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
})
.catch(error => console.error('Lỗi khi lấy danh sách người dùng:', error));
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
    if (this.isChatOpen && this.selectedUser && this.selectedUser.id === e.sender_id) {
        this.messages.push({ ...e, sender_name: this.selectedUser.name });
        this.$nextTick(this.scrollToBottom);
    } else {
        const senderId = e.sender_id;
        this.unreadMessages[senderId] = (this.unreadMessages[senderId] || 0) + 1;
        this.recalculateTotalUnread();
        console.log(`[Chat] Unread message from user ${senderId}. Total unread:`, this.unreadMessages);
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
            startEditMessage(message) {
                this.editingMessageId = message.id;
                this.editingContent = message.content;
            },
            cancelEditMessage() {
                this.editingMessageId = null;
                this.editingContent = '';
            },
            confirmEditMessage(message) {
                if (!this.editingContent.trim()) return;
                const idx = this.messages.findIndex(m => m.id === message.id);
                const newContent = this.editingContent;
                let oldMsg;
                if (idx !== -1) {
                    oldMsg = { ...this.messages[idx] };
                    this.messages[idx] = { ...this.messages[idx], content: newContent, is_edited: true };
                }
                this.editingMessageId = null;
                this.editingContent = '';
                axios.patch(`/api/chat/message/${message.id}`, { content: newContent })
                    .then(res => {
                        if (idx !== -1) {
                            this.messages[idx] = { ...this.messages[idx], ...res.data.message };
                        }
                    })
                    .catch(err => {
                        if (idx !== -1) {
                            this.messages[idx] = oldMsg;
                        }
                        alert('Lỗi khi sửa tin nhắn!');
                    });
            },
            deleteMessage(message) {
                if (!confirm('Bạn có chắc chắn muốn thu hồi tin nhắn này?')) return;
                axios.patch(`/api/chat/message/${message.id}`, { content: '*đã thu hồi*' })
                    .then(res => {
                        const idx = this.messages.findIndex(m => m.id === message.id);
                        if (idx !== -1) {
                            this.messages[idx] = {
                                ...this.messages[idx],
                                ...res.data.message,
                                type: 'text',
                            };
                        }
                    })
                    .catch(err => {
                        alert('Lỗi khi thu hồi tin nhắn!');
                    });
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

    .edit-btn {
        background: #428cd6;
        color: #fff;
        border: none;
        border-radius: 4px;
        margin: 0 4px;
        padding: 2px 8px;
        cursor: pointer;
        font-size: 12px;
    }
    .delete-btn {
        background: #e74c3c;
        color: #fff;
        border: none;
        border-radius: 4px;
        margin: 0 4px;
        padding: 2px 8px;
        cursor: pointer;
        font-size: 12px;
    }
    .cancel-btn {
        background: #aaa;
        color: #fff;
        border: none;
        border-radius: 4px;
        margin: 0 4px;
        padding: 2px 8px;
        cursor: pointer;
        font-size: 12px;
    }
    .edit-input {
        width: 100%;
        margin: 4px 0;
        padding: 2px 6px;
        border-radius: 4px;
        border: 1px solid #ccc;
    }
    .message-actions {
        margin-top: 4px;
        text-align: right;
        display: none;
    }
    .my-message:hover .message-actions {
        display: block;
    }
    .edited-flag {
        color: #888;
        font-size: 0.85em;
        margin-left: 6px;
        font-style: italic;
    }
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
