<?php
session_start();
include 'config.php';

// Check session
if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(['error' => 'No user session']);
    header('Location: sai.php');
    exit();
}

$sender_id = $_SESSION['user_id'];
$receiver_id = $_GET['receiver_id'] ?? null;

// Check receiver_id
if (!$receiver_id) {
    echo json_encode(['error' => 'Invalid receiver_id']);
    exit();
}
error_log("User ID: " . $_SESSION['user_id']);
// Debug session and receiver_id
error_log("sender_id: $sender_id, receiver_id: $receiver_id");

try {
    // Ensure PDO throws errors
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt = $conn->prepare("
        SELECT sender_id, receiver_id, message, sent_at 
        FROM chats
        WHERE (sender_id = :sender_id AND receiver_id = :receiver_id)
           OR (sender_id = :receiver_id AND receiver_id = :sender_id)
        ORDER BY sent_at ASC
    ");
    $stmt->execute(['sender_id' => $sender_id, 'receiver_id' => $receiver_id]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode(['status' => 'success', 'messages' => $results]);

} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    echo json_encode(['error' => 'Database error']);
}
?>