<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

$is_admin = $_SESSION['is_admin'];
$user_id = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Live Chat UI</title>
  <link rel="stylesheet" href="styles.css" />
  <style>
    .user:hover {
      background-color: rgba(17, 97, 162, 0.8);
    }

    .chat-item {
      position: relative;
    }

    .flur {
      position: absolute;
      border-radius: 12px;
      background-color: rgba(236, 241, 245, 0.1);
      inset: 0;

    }

    /* .active::before {
      content: '';
      width: 10px;
      height: 10px;
      left: 13px;
      position: absolute;
      background-color: rgb(36, 234, 36);
      border-radius: 50%;
    } */
  </style>
</head>

<body>
  <div class="chat-container">
    <?php if ($is_admin): ?>
      <div class="sidebar">
        <div>My chat</div>
        <div class="chat-list">
          <?php
          $stmt = $conn->prepare("SELECT 
    users.id, 
    users.username, 
    messages.message 
FROM 
    users 
JOIN 
    messages 
ON 
    users.id = messages.sender_id 
WHERE 
    is_admin = 0 
AND 
    messages.timestamp = (
        SELECT MAX(m.timestamp) 
        FROM messages m 
        WHERE m.sender_id = users.id
    )
ORDER BY 
    messages.timestamp DESC;
");
          $stmt->execute();
          $users = $stmt->fetchAll();
          foreach ($users as $user) {
          ?>
            <div class="chat-item">
              <div class="flur user" data-id="<?= $user['id'] ?>"></div>
              <div class="active">
                <img src="image/avatar1.png" class="avatar" alt="">
              </div>
              <div class="chat-text">
                <div class="chat-name "><?= $user['username'] ?></div>
                <div class="chat-preview"><?= $user['message'] ?></div>
              </div>
            </div>
          <?php } ?>
        </div>
      </div>
    <?php endif; ?>

    <div class="chat-window">
      <div class="chat-header">
        <div class="chat-title" id="title">
          <?php
          if ($is_admin) {
            echo "Chọn người dùng để chat";
          } else {
            $admin_stmt = $conn->prepare("SELECT username FROM users WHERE is_admin = 1 LIMIT 1");
            $admin_stmt->execute();
            echo $admin_stmt->fetchColumn();
          }
          ?>
        </div>
        <div class="chat-time">Chat started: Today, 02:04 pm</div>
      </div>
      <div class="chat-body" id="chat-body">
      
      </div>
      <div class="chat-input">
        <input type="text" id="messageInput" placeholder="Nhập tin nhắn...">
        <button onclick="sendMessage()">Gửi</button>
      </div>
    </div>
  </div>

  <script>
    const userId = <?php echo json_encode($user_id); ?>;
    const isAdmin = <?php echo json_encode($is_admin); ?>;
    let selectedUserId = isAdmin ? null : <?php
                                          $stmt = $conn->prepare("SELECT id FROM users WHERE is_admin = 1 LIMIT 1");
                                          $stmt->execute();
                                          echo json_encode($stmt->fetchColumn());
                                          ?>;

    const conn = new WebSocket('ws://localhost:8080');
    let lastSentMessage = null; 

    conn.onopen = function() {
      console.log('Connected to WebSocket');
    };

    conn.onerror = function(error) {
      console.error('WebSocket error:', error);
      alert('Không thể kết nối đến server chat. Vui lòng thử lại sau.');
    };

    conn.onclose = function() {
      console.log('WebSocket connection closed');
      alert('Kết nối chat đã bị đóng. Vui lòng làm mới trang.');
    };

    conn.onmessage = function(e) {
      const data = JSON.parse(e.data);
      console.log('Received message:', data);

    
      if (data.senderId == userId && data.message === lastSentMessage) {
        lastSentMessage = null; // Reset sau khi bỏ qua
        return;
      }


      if ((data.senderId == userId && data.receiverId == selectedUserId) ||
        (data.senderId == selectedUserId && data.receiverId == userId)) {
        displayMessage(data.senderId, data.message, data.timestamp);
      }

      
    };

    function sendMessage() {
      const message = document.getElementById('messageInput').value.trim();

      if (message.length > 8) {
        alert('Lỗi: Tin nhắn không được vượt quá 8 ký tự!');
        messageInput.value = message.substring(0, 8); 
        return;
      } else {
        if (!message) return;

        if (isAdmin && !selectedUserId) {
          alert('Vui lòng chọn người dùng để chat');
          return;
        }

        const data = {
          userId: userId,
          senderId: userId,
          receiverId: selectedUserId,
          message: message
        };

     
        lastSentMessage = message;


        displayMessage(userId, message, new Date().toISOString().slice(0, 19).replace('T', ' '));

        conn.send(JSON.stringify(data));
        document.getElementById('messageInput').value = '';
      }



    }

    function displayMessage(senderId, message, timestamp) {
      const chatBody = document.getElementById('chat-body');
      const messageDiv = document.createElement('div');
      messageDiv.className = senderId == userId ? 'message agent' : 'message customer';
      messageDiv.innerHTML = `
      <div class="message-bubble">${message}</div>
      
    `;
      chatBody.appendChild(messageDiv);
      chatBody.scrollTop = chatBody.scrollHeight;
    }

    function loadMessages() {
      if (!selectedUserId) return;
      fetch(`get_messages.php?user_id=${selectedUserId}`)
        .then(response => {
          if (!response.ok) throw new Error('Network response was not ok');
          return response.json();
        })
        .then(messages => {
          console.log('Messages from server:', messages);
          const chatBody = document.getElementById('chat-body');
          chatBody.innerHTML = ''; 
          if (messages.status === 'empty') {
            console.log('No messages to display');
            return;
          }
          messages.forEach(msg => displayMessage(msg.sender_id, msg.message, msg.timestamp));
        })
        .catch(error => console.error('Fetch error:', error));
    }

   
    document.getElementById('messageInput').addEventListener('keypress', function(e) {
      if (e.key === 'Enter') {
        sendMessage();
      }
    });

    <?php if ($is_admin): ?>
      document.querySelectorAll('.user').forEach(user => {
        user.addEventListener('click', function() {
          selectedUserId = this.dataset.id;
          document.getElementById('title').textContent = `${this.textContent}`;
          loadMessages();
        });
      });
    <?php else: ?>
      loadMessages();
    <?php endif; ?>
  </script>
</body>

</html>