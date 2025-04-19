<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    exit();
}

$sender_id = $_SESSION['user_id'];
$receiver_id = $_POST['receiver_id'] ?? null;
$message = $_POST['message'] ?? '';

if (!$receiver_id || !$message) {
    http_response_code(400);
    exit();
}

$stmt = $conn->prepare("
    INSERT INTO chats (sender_id, receiver_id, message) 
    VALUES (:sender_id, :receiver_id, :message)
");
$stmt->execute(['sender_id' => $sender_id, 'receiver_id' => $receiver_id, 'message' => $message]);
?>
