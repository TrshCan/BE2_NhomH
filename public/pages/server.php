<?php
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
require 'vendor/autoload.php';
include 'config.php';

class Chat implements MessageComponentInterface {
    private $clients;
    private $connectedUsers;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
        $this->connectedUsers = [];
    }

    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);
        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        global $conn;
        $data = json_decode($msg, true);
        $this->connectedUsers[$from->resourceId] = $data['userId'];
      
        try {
          $stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)");
          $stmt->execute([$data['senderId'], $data['receiverId'], $data['message']]);
        } catch (Exception $e) {
          error_log("Database error: " . $e->getMessage());
        }

       
        foreach ($this->clients as $client) {
            if (isset($this->connectedUsers[$client->resourceId])) {
                if ($this->connectedUsers[$client->resourceId] == $data['receiverId'] || 
                    $this->connectedUsers[$client->resourceId] == $data['senderId']) {
                    $client->send(json_encode([
                        'senderId' => $data['senderId'],
                        'receiverId' => $data['receiverId'],
                        'message' => $data['message'],
                        'timestamp' => date('Y-m-d H:i:s')
                    ]));
                }
            }
        }
    }

    public function onClose(ConnectionInterface $conn) {
        unset($this->connectedUsers[$conn->resourceId]);
        $this->clients->detach($conn);
        echo "Connection closed! ({$conn->resourceId})\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error occurred: {$e->getMessage()}\n";
        $conn->close();
    }
}

$server = \Ratchet\Server\IoServer::factory(
    new \Ratchet\Http\HttpServer(
        new \Ratchet\WebSocket\WsServer(
            new Chat()
        )
    ),
    8080
);
$server->run();