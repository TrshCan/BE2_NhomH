@php
    $role = session('user_role');
    $user_id = session('user_id');

    if ($role === 'admin') {
        $user_id = 0; // Admin user ID
        session(['user_id' => $user_id]);
    }
@endphp

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Trò Chuyện Trực Tuyến</title>
    <link rel="stylesheet" href="{{ asset('assets/styles/livechat.css') }}">
    <style>
       .windown {
        position: relative;
    }

    .chat-input {
        position: absolute;
        bottom: 0;
        width: 76%;
        background-color: #fff;
        padding: 10px;
        box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.1);
    }
    </style>
</head>
<body>
    <div id="chat-container" class="chat-container">
        @if ($role === 'admin')
            <div class="sidebar">
                <div>Cuộc Trò Chuyện Của Tôi</div>
                <div class="chat-list" id="chat-list"></div>
            </div>
        @endif
        <div id="chat-window" class="chat-window window">
            <div class="chat-header">
                <div class="chat-title" id="chat-title">Chọn người dùng để trò chuyện</div>
            </div>
            <div id="error-message" class="error-message"></div>
            <div id="chat-body" class="chat-body"></div>
            <div class="chat-input">
                <input id="messageInput" type="text" placeholder="Nhập tin nhắn..." autocomplete="off" maxlength="255">
                <button onclick="sendMessage()">Gửi</button>
            </div>
        </div>
    </div>

    <script>
 const userId = <?= json_encode($user_id) ?>;
        let selectedUserId = null;
        let lastMessageId = 0;

        const showError = (message) => {
            const errorDiv = document.getElementById('error-message');
            errorDiv.textContent = message;
            errorDiv.style.display = 'block';
            setTimeout(() => {
                errorDiv.style.display = 'none';
            }, 3000);
        };

        const loadUserList = async () => {
            try {
                const response = await fetch('{{ route("chat.users") }}');
                if (!response.ok) throw new Error('Không thể tải danh sách người dùng');
                const users = await response.json();

                const chatList = document.getElementById('chat-list');
                chatList.innerHTML = '';

                users.data.forEach(user => {
                    const chatItem = document.createElement('div');
                    chatItem.classList.add('chat-item');

                    // Flur user (hidden as per original)
                    const flurUser = document.createElement('div');
                    flurUser.classList.add('flur', 'user');
                    flurUser.setAttribute('data-id', user.user_id);
                    chatItem.appendChild(flurUser);

                    // Chat text section
                    const chatText = document.createElement('div');
                    chatText.classList.add('chat-text');

                    const chatName = document.createElement('div');
                    chatName.classList.add('chat-name');
                    chatName.textContent = user.full_name;

                    const chatPreview = document.createElement('div');
                    chatPreview.classList.add('chat-preview');
                    chatPreview.textContent = user.message || 'Chưa có tin nhắn';

                    chatText.appendChild(chatName);
                    chatText.appendChild(chatPreview);
                    chatItem.appendChild(chatText);

                    flurUser.onclick = () => {
                        selectedUserId = user.user_id;
                        lastMessageId = 0;
                        document.getElementById('chat-title').textContent = `Trò chuyện với ${user.full_name}`;
                        loadMessages();
                    };

                    chatList.appendChild(chatItem);
                });
            } catch (error) {
                showError('không có người dùng nào chat cho bạn');
            }
        };

        const loadMessages = async () => {
            if (!selectedUserId) return;

            try {
                const response = await fetch(`{{ route("chat.messages") }}?receiver_id=${selectedUserId}`);
                if (!response.ok) throw new Error('Không thể tải tin nhắn');
                
                const data = await response.json();
                const chatBody = document.getElementById('chat-body');
                chatBody.innerHTML = '';
                data.messages.forEach(msg => {
                        const messageDiv = document.createElement('div');
                        messageDiv.className = msg.sender_id == userId ? 'message agent' : 'message customer';
                        messageDiv.innerHTML = `<div class="message-bubble">${msg.message}</div>`;
                        chatBody.appendChild(messageDiv);
                });

                chatBody.scrollTop = chatBody.scrollHeight;
            } catch (error) {
                showError('Lỗi khi tải tin nhắn');
            }
        };

        const sendMessage = async () => {
            const messageInput = document.getElementById('messageInput');
            const message = messageInput.value.trim();
            console.log(message);
            if (!message || !selectedUserId) {
                showError('Vui lòng chọn người dùng và nhập tin nhắn');
                return;
            }

            try {
                const response = await fetch('{{ route("chat.messages") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        sender_id: userId,
                        receiver_id: selectedUserId,
                        message
                    })
                });

                if (!response.ok) throw new Error('Không thể gửi tin nhắn');

                messageInput.value = '';
                await loadMessages();
            } catch (error) {
                showError('Lỗi khi gửi tin nhắn');
            }
        };

        document.getElementById('messageInput').addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                sendMessage();
            }
        });

        document.addEventListener('DOMContentLoaded', () => {
            loadUserList();
            setInterval(loadUserList, 5000);
            setInterval(loadMessages, 5000); 
        });
    </script>
</body>
</html>