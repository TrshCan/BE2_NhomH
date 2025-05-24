@php
$role = session('user_role');
$user_id = session('user_id');


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
        .chat-widget {
            position: fixed;
            bottom: 30px;
            right: 35px;
            width: 80%;
            height: 80%;
            max-height: 450px;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 10px;
            display: flex;
            flex-direction: column;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
            z-index: 9999;
            overflow: hidden;
            border: 1px solid black;
        }

        .chat-header {
            background-color: #007bff;
            color: white;
            padding: 10px;
            font-weight: bold;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .chat-body {
            flex: 1;
            overflow-y: auto;
            padding: 10px;
            background-color: #f9f9f9;
        }

        .chat-input {
            display: flex;
            border-top: 1px solid #ddd;
            padding: 10px;
            background-color: #fff;
        }

        .chat-input input {
            flex: 1;
            padding: 6px;
            border: 1px solid #ccc;
            border-radius: 6px;
            margin-right: 6px;
        }

        .chat-input button {
            padding: 6px 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        .message {
            margin-bottom: 10px;
            display: flex;
        }

        .message.customer .message-bubble {
            background-color: #eef0f6;
            padding: 8px 12px;
            border-radius: 12px;
            max-width: 80%;
        }

        .message.agent {
            justify-content: flex-end;
        }

        .message.agent .message-bubble {
            background-color: #007bff;
            color: white;
            padding: 8px 12px;
            border-radius: 12px;
            max-width: 80%;
        }


        .close-chat {
            cursor: pointer;
            font-size: 14px;
        }

        .close-chat {
            cursor: pointer;
            font-size: 20px;
            color: white;
            margin-left: auto;
            padding: 5px;
        }
    </style>
</head>

<body>
    <div class="chat-widget" id="chat-widget">
        <div class="chat-header">
            <span class="chat-title" id="chat-title">Live Chat</span>
            <!-- <span class="close-chat" id="closeChat">✕</span> -->
          
        </div>
        <div class="chat-body" id="chat-body">

            <div class="message customer">
                <div class="message-bubble">Hello! How can I help you?</div>
            </div>
        </div>
        <div class="chat-input">
            <input id="messageInput" type="text" placeholder="Nhập tin nhắn..." autocomplete="off" maxlength="255">
            <button onclick="sendMessage()">Gửi</button>
        </div>
    </div>
    </div>

    <script>
        const userId = <?= json_encode($user_id) ?>;
        const selectedUserId = 0;
        let lastMessageId = 0;

        const showError = (message) => {
            const errorDiv = document.getElementById('error-message');
            errorDiv.textContent = message;
            errorDiv.style.display = 'block';
            setTimeout(() => {
                errorDiv.style.display = 'none';
            }, 3000);
        };


        const loadMessages = async () => {
            try {
                const response = await fetch(`{{ route("chat.messages") }}?receiver_id=${selectedUserId}`);
                if (!response.ok) {
                    throw new Error('Không thể tải tin nhắn');

                }

                const data = await response.json();
                if (data.messages.length === 0) {
                    return;
                }
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
        loadMessages();
        document.addEventListener('DOMContentLoaded', () => {
            setInterval(loadMessages, 5000);
        });
        document.getElementById('closeChat').addEventListener('click', function() {
            const chatWidget = document.getElementById('chat-widget');
            chatWidget.style.display = 'none';
        });
    </script>
</body>

</html>