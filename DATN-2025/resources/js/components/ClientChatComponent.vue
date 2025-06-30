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
                <div v-for="message in messages" :key="message.id" :class="{ 'my-message': message.sender_id === currentUserId, 'their-message': message.sender_id !== currentUserId }">
                    <strong>{{ message.sender_name }}:</strong> {{ message.content }}
                    <span class="timestamp">{{ message.created_at }}
                        <i v-if="message.status === 'sending'" class="fa-regular fa-clock-rotate-left"></i>
                        <i v-if="message.status === 'failed'" class="fa-solid fa-circle-exclamation" style="color: red;" title="Gửi thất bại"></i>
                    </span>
                </div>
            </div>
            <div class="message-input">
                <input type="text" v-model="newMessage" @keyup.enter="sendMessage" placeholder="Nhập tin nhắn...">
                <button @click="sendMessage">Gửi</button>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    props: ['currentUserId', 'adminId'], // `adminId` là ID của admin mà client sẽ chat
    data() {
        return {
            isChatOpen: false, // Trạng thái ẩn/hiện của khung chat
            messages: [],
            newMessage: '',
            hasNewMessage: false, // Để hiển thị chấm đỏ khi có tin nhắn mới
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
        sendMessage() {
            if (!this.newMessage.trim() || !this.adminId) return;

            const tempId = `temp_${Date.now()}`;
            const optimisticMessage = {
                id: tempId,
                content: this.newMessage,
                sender_id: this.currentUserId,
                sender_name: 'Bạn',
                created_at: new Date().toLocaleTimeString('vi-VN', { hour: '2-digit', minute: '2-digit' }),
                status: 'sending'
            };

            this.messages.push(optimisticMessage);
            const messageToSend = this.newMessage;
            this.newMessage = '';
            this.$nextTick(this.scrollToBottom);

            axios.post('/api/client/chat/send', {
                receiver_id: this.adminId,
                message_content: messageToSend
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
        }
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
}

.message-input button:hover {
    background-color: #852e2e;
}
</style>
