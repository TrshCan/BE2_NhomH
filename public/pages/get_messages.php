<?php
session_start();
include 'config.php';

$current_user_id = $_SESSION['user_id'];
$is_admin = $_SESSION['is_admin'];
$other_user_id = $_GET['user_id'];

$stmt = $conn->prepare("SELECT sender_id, message, timestamp FROM messages 
    WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?)
    ORDER BY timestamp");
$stmt->execute([$current_user_id, $other_user_id, $other_user_id, $current_user_id]);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
if (empty($messages)) {
    echo json_encode(['status' => 'empty', 'message' => 'No messages found']);
} else {
    echo json_encode($messages);
}