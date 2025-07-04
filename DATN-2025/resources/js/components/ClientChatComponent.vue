<template>
    <div class="chat-floating-container">
        <button class="chat-toggle-button" @click="toggleChat">
            <i class="fa-solid fa-comments"></i>
            <span v-if="hasNewMessage" class="new-message-indicator"></span>
        </button>

        <div class="chat-box" :class="{ 'open': isChatOpen }">
            <div class="chat-header">
                <h3>Chat với Mira Café</h3>
                <button class="close-button" @click="closeChat">&times;</button>
            </div>
            <div class="messages-container" ref="messagesContainer">
                <div v-for="message in messages" :key="message.id" :class="{ 'my-message': message.sender_id === currentUserId, 'their-message': message.sender_id !== currentUserId }"
                    @mouseenter="hoveredMessageId = message.id" @mouseleave="hoveredMessageId = null">
                    <strong>
                        {{ message.sender_name === 'admin' ? 'Admin' : 'Bạn' }}
                    </strong>
                    <template v-if="editingMessageId === message.id">
                        <div class="edit-row">
                            <input v-model="editingContent" @keyup.enter="confirmEditMessage(message)" class="edit-input" />
                           <br> <button @mousedown.prevent="confirmEditMessage(message)" class="edit-btn">Lưu</button>
                            <button @mousedown.prevent="cancelEditMessage" class="cancel-btn">Hủy</button>
                        </div>
                    </template>
                    <template v-else>
                        <template v-if="message.type === 'image'">
                            <img :src="message.content" style="max-width: 200px; max-height: 200px; border-radius: 8px; margin: 5px 0;">
                        </template>
                        <template v-else>
                            <span class="message-content" v-html="formatMessageContent(message.content)"></span>
                            <br><small><span style="color: red;" v-if="message.is_edited && message.content !== '*đã thu hồi*'" class="edited-flag">*đã chỉnh sửa</span></small>
                        </template>
                        <span class="timestamp">{{ message.created_at }}
                            <i v-if="message.status === 'sending'" class="fa-regular fa-clock-rotate-left"></i>
                            <i v-if="message.status === 'failed'" class="fa-solid fa-circle-exclamation" style="color: red;" title="Gửi thất bại"></i>
                        </span>
                        <div v-if="message.sender_id === currentUserId && hoveredMessageId === message.id && message.content !== '*đã thu hồi*'" class="message-actions">
                            <button v-if="message.type === 'text'" @click="startEditMessage(message)" class="edit-btn">Sửa</button>
                            <button @click="deleteMessage(message)" class="delete-btn">Thu hồi</button>
                        </div>
                    </template>
                </div>
            </div>
            <div class="message-input">
                <input type="file" ref="fileInput" style="display:none" @change="handleFileChange">
                <button @click="$refs.fileInput.click()" class="attach-btn" title="Đính kèm ảnh"><i class="fa fa-paperclip"></i></button>
                <input type="text" v-model="newMessage" @keyup.enter="sendMessage()" placeholder="Nhập tin nhắn...">
                <button @click="sendMessage()">Gửi</button>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
axios.defaults.withCredentials = true;
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

export default {
    props: ['currentUserId', 'adminId'], // `adminId` là ID của admin mà client sẽ chat
    data() {
        return {
            isChatOpen: false, // Trạng thái ẩn/hiện của khung chat
            messages: [],
            newMessage: '',
            hasNewMessage: false, // Để hiển thị chấm đỏ khi có tin nhắn mới
            editingMessageId: null,
            editingContent: '',
            hoveredMessageId: null,
        };
    },
    watch: {
        isChatOpen(newValue) {
            if (newValue) {
                // Khi mở chat, reset indicator và cuộn xuống dưới
                this.hasNewMessage = false;
                this.$nextTick(this.scrollToBottom);
            }
        }
    },
    mounted() {
        if (this.currentUserId && this.adminId) {
            // Chỉ fetch lịch sử khi chat được mở lần đầu hoặc khi component được mount
            // hoặc khi có tin nhắn mới đến
            this.listenForNewMessages();
            // Nếu muốn chat mở sẵn khi trang load, bỏ comment dòng dưới
            // this.isChatOpen = true;
        } else {
            console.warn('currentUserId hoặc adminId không được cung cấp cho ClientChatComponent.');
        }
    },
    methods: {
        toggleChat() {
            this.isChatOpen = !this.isChatOpen;
            if (this.isChatOpen && this.messages.length === 0) { // Chỉ tải lịch sử nếu chưa có
                this.fetchChatHistory();
            }
        },
        closeChat() {
            this.isChatOpen = false;
        },
        fetchChatHistory() {
            axios.get(`/api/client/chat/history/${this.adminId}`)
                .then(response => {
                    this.messages = response.data.messages;
                    this.$nextTick(this.scrollToBottom);
                })
                .catch(error => {
                    console.error('Lỗi khi lấy lịch sử chat client:', error);
                });
        },
        listenForNewMessages() {
            if (!window.Echo || !this.currentUserId) {
                console.error('Laravel Echo chưa được khởi tạo hoặc currentUserId không có.');
                return;
            }

            window.Echo.private(`chat.user.${this.currentUserId}`)
                .listen('.message.sent', (e) => {
                    // Kiểm tra xem tin nhắn có phải từ người admin mà client đang chat không
                    // và có phải đến kênh của client không
                    if (e.sender_id === this.adminId || e.receiver_id === this.currentUserId) {
                        console.log('Client nhận tin nhắn mới:', e);
                        this.messages.push({
                            id: e.id,
                            content: e.content,
                            type: e.type,
                            sender_id: e.sender_id,
                            sender_name: e.sender_name,
                            created_at: e.created_at,
                        });
                        if (!this.isChatOpen) { // Nếu chat đang đóng, báo có tin nhắn mới
                            this.hasNewMessage = true;
                        }
                        this.$nextTick(this.scrollToBottom);
                    }
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
            if (!messageContent.toString().trim() || !this.adminId) return;
            const tempId = `temp_${Date.now()}`;
            const optimisticMessage = {
                id: tempId,
                content: messageContent,
                type,
                sender_id: this.currentUserId,
                sender_name: 'Bạn',
                created_at: new Date().toLocaleTimeString('vi-VN', { hour: '2-digit', minute: '2-digit' }),
                status: 'sending'
            };
            this.messages.push(optimisticMessage);
            if (!content) this.newMessage = '';
            this.$nextTick(this.scrollToBottom);
            axios.post('/api/client/chat/send', {
                receiver_id: this.adminId,
                message_content: messageContent,
                type
            })
            .then(response => {
                const sentMessage = this.messages.find(m => m.id === tempId);
                if (sentMessage) {
                    Object.assign(sentMessage, response.data.data);
                    sentMessage.status = 'sent';
                }
            })
            .catch(error => {
                console.error('Lỗi khi gửi tin nhắn từ client:', error);
                if (error.response && error.response.data) {
                    console.error('Chi tiết lỗi:', error.response.data);
                    alert('Lỗi: ' + JSON.stringify(error.response.data.errors || error.response.data));
                }
                const failedMessage = this.messages.find(m => m.id === tempId);
                if (failedMessage) {
                    failedMessage.status = 'failed';
                }
            });
        },
        scrollToBottom() {
            const container = this.$refs.messagesContainer; // Sử dụng ref để truy cập DOM element
            if (container) {
                container.scrollTop = container.scrollHeight;
            }
        },
        isUrl(text) {
            return /^(https?:\/\/[^\s]+)$/.test(text);
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
                    console.error('Lỗi khi sửa tin nhắn:', err, err.response, err.request, err.message);
                    if (err.response && (err.response.status === 401 || err.response.status === 419)) {
                        alert('Phiên đăng nhập đã hết hạn, vui lòng tải lại trang!');
                    } else {
                        alert('Lỗi khi sửa tin nhắn!');
                    }
                });
        },
        deleteMessage(message) {
            if (!confirm('Bạn có chắc chắn muốn thu hồi tin nhắn này?')) return;
            axios.patch(`/api/chat/message/${message.id}`, { content: '*đã thu hồi*' })
                .then(res => {
                    const idx = this.messages.findIndex(m => m.id === message.id);
                    if (idx !== -1) {
                        const oldMsg = this.messages[idx];
                        const newMsg = { ...oldMsg, ...res.data.message, type: 'text' };
                        if (!newMsg.sender_name && oldMsg.sender_name) newMsg.sender_name = oldMsg.sender_name;
                        this.messages[idx] = newMsg;
                    }
                })
                .catch(err => {
                    console.error('Lỗi khi thu hồi tin nhắn:', err, err.response, err.request, err.message);
                    if (err.response && (err.response.status === 401 || err.response.status === 419)) {
                        alert('Phiên đăng nhập đã hết hạn, vui lòng tải lại trang!');
                    } else {
                        alert('Lỗi khi thu hồi tin nhắn!');
                    }
                });
        },
        formatMessageContent(content) {
            if (!content) return '';
            const urlRegex = /(https?:\/\/[\w\-._~:/?#[\]@!$&'()*+,;=%]+)(?=\s|$)/g;
            const escapeHtml = (str) => str.replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m]));
            return escapeHtml(content).replace(urlRegex, url => `<a href="${url}" target="_blank">${url}</a>`);
        },
    }
}
</script>

<style scoped>
/* Container cho cả nút và khung chat, dùng để định vị nổi */
.chat-floating-container {
    position: fixed;
    bottom: 20px; /* Cách dưới 20px */
    right: 20px;  /* Cách phải 20px */
    z-index: 1000; /* Đảm bảo nó nằm trên các nội dung khác */
}

/* Nút mở chat */
.chat-toggle-button {
    background-color: #813f3f;
    color: white;
    border: none;
    border-radius: 50%; /* Hình tròn */
    width: 60px;
    height: 60px;
    font-size: 24px;
    display: flex;
    justify-content: center;
    align-items: center;
    cursor: pointer;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
    transition: background-color 0.3s ease;
    position: relative; /* Để đặt chấm đỏ */
}

.chat-toggle-button:hover {
    background-color: #852e2e; /* Màu tối hơn khi hover */
}

/* Chấm đỏ báo tin nhắn mới */
.new-message-indicator {
    position: absolute;
    top: 5px;
    right: 5px;
    width: 15px;
    height: 15px;
    background-color: red;
    border-radius: 50%;
    border: 2px solid white;
}


/* Khung chat */
.chat-box {
    position: absolute;
    bottom: 70px; /* Đặt trên nút toggle */
    right: 0;
    width: 350px; /* Chiều rộng khung chat */
    height: 450px; /* Chiều cao khung chat */
    background-color: white;
    border: 1px solid #ddd;
    border-radius: 8px;
    box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.3);
    display: flex;
    flex-direction: column;
    overflow: hidden;
    opacity: 0; /* Mặc định ẩn */
    visibility: hidden; /* Mặc định ẩn */
    transform: translateY(20px); /* Bắt đầu hơi dịch xuống */
    transition: all 0.3s ease-in-out; /* Hiệu ứng chuyển động */
}

.chat-box.open {
    opacity: 1; /* Hiện ra */
    visibility: visible; /* Hiện ra */
    transform: translateY(0); /* Về vị trí ban đầu */
}

.chat-header {
    background-color: #813f3f;
    color: white;
    padding: 10px 15px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-top-left-radius: 8px;
    border-top-right-radius: 8px;
}

.chat-header h3 {
    margin: 0;
    font-size: 1.1em;
}

.chat-header .close-button {
    background: none;
    border: none;
    color: white;
    font-size: 1.5em;
    cursor: pointer;
}

.messages-container {
    flex-grow: 1;
    overflow-y: auto;
    padding: 10px;
    background-color: #f9f9f9;
}

/* CSS cho tin nhắn */
.my-message, .their-message {
    padding: 8px 12px;
    border-radius: 15px;
    margin-bottom: 8px;
    max-width: 70%;
    word-wrap: break-word; /* Đảm bảo văn bản xuống dòng */
}
.my-message {
    background-color: #dcf8c6;
    margin-left: auto; /* Căn phải */
    text-align: right;
}
.their-message {
    background-color: #e0e0e0;
    margin-right: auto; /* Căn trái */
    text-align: left;
}
.my-message strong, .their-message strong {
    display: block;
    font-size: 0.8em;
    color: #555;
    margin-bottom: 2px;
}
.timestamp {
    display: block;
    font-size: 0.7em;
    color: #888;
    margin-top: 2px;
}


.message-input {
    display: flex;
    padding: 10px;
    border-top: 1px solid #eee;
    background-color: white;
}

.message-input input {
    flex-grow: 1;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 20px;
    margin-right: 10px;
}

.message-input button {
    background-color: #813f3f;
    color: white;
    border: none;
    border-radius: 20px;
    padding: 8px 15px;
    cursor: pointer;
    transition: background-color 0.3s ease;
    max-height: 50px;
}

.message-input button:hover {
    background-color: #852e2e;
}

.attach-btn {
    background-color: #813f3f;
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
    background-color: #852e2e;
}

.edit-row {
    display: block;
    gap: 6px;
    align-items: center;
}
.edit-btn {
    background: #813f3f;
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
