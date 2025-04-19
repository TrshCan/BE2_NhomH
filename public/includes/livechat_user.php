<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Live Chat Widget</title>
    <link rel="stylesheet" href="../assets/styles/livechat.css">
    <style>
    .chat-widget {
        position: fixed;
        bottom: 20px;
        right: 20px;
        width: 300px;
        max-height: 450px;
        background-color: #fff;
        border: 1px solid #ccc;
        border-radius: 10px;
        display: flex;
        flex-direction: column;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        z-index: 9999;
        overflow: hidden;
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

    /* Optional close/minimize */
    .close-chat {
        cursor: pointer;
        font-size: 14px;
    }
    </style>
</head>

<body>
    <div class="chat-widget" id="chat-widget">
        <div class="chat-header">
            <span class="chat-title" id="chat-title">Live Chat</span>
            <span class="close-chat" onclick="document.getElementById('chat-widget').style.display='none'">✕</span>
        </div>
        <div class="chat-body" id="chat-body">
            <div class="message customer">
                <div class="message-bubble">Hi there!</div>
            </div>
            <div class="message agent">
                <div class="message-bubble">Hello! How can I help you?</div>
            </div>
        </div>
        <div class="chat-input">
            <input id="messageInput" type="text" placeholder="Type a message...">
            <button onclick="sendMessage()">Send</button>
        </div>
    </div>

    <script>
    const userId = <?= json_encode($user_id) ?>;
    let selectedUserId = <?= $role !== 'admin' ? '5' : 'null' ?>;
    const title = document.getElementById('chat-title');

    if (selectedUserId === 4) {
        title.textContent = 'Chat with Admin';
    }

    function loadMessages() {
        if (!selectedUserId) return;
        fetch(`get_messages.php?receiver_id=${selectedUserId}`)
            .then(response => response.json())
            .then(data => {
                const chatBody = document.getElementById('chat-body');
                chatBody.innerHTML = '';
                data.messages.forEach(msg => {
                    const div = document.createElement('div');
                    div.className = msg.sender_id == userId ? 'message agent' : 'message customer';
                    div.innerHTML = `<div class="message-bubble">${msg.message}</div>`;
                    chatBody.appendChild(div);
                });
                chatBody.scrollTop = chatBody.scrollHeight;
            });
    }

    function sendMessage() {
        const input = document.getElementById('messageInput');
        const message = input.value.trim();
        if (!message) return;

        fetch('send_message.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({
                sender_id: userId,
                receiver_id: selectedUserId,
                message
            })
        }).then(() => {
            input.value = '';
            loadMessages();
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        loadMessages();
        setInterval(loadMessages, 1000);
    });
    </script>
</body>

</html>