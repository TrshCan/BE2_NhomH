<?php
session_start();
include 'config.php';

// Ensure the content type is JSON
header('Content-Type: application/json');

// Check admin access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Access denied.']);
    exit();
}

try {
    // Query to fetch the user list and their latest message
    $stmt = $conn->prepare("
       SELECT 
    users.user_id, 
    users.full_name, 
    latest_message.message, 
    latest_message.sent_at AS last_time
FROM 
    users
JOIN (
    SELECT 
        sender_id, 
        message, 
        sent_at
    FROM 
        chats c1
    WHERE 
        sent_at = (
            SELECT MAX(sent_at)
            FROM chats c2
            WHERE c1.sender_id = c2.sender_id
        )
) AS latest_message
ON 
    users.user_id = latest_message.sender_id
WHERE 
    users.role = 'user'
ORDER BY 
    latest_message.sent_at DESC;
    ");
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($users) {
        http_response_code(200);
        echo json_encode(['status' => 'success', 'data' => $users]);
    } else {
        http_response_code(404);
        echo json_encode(['status' => 'empty', 'message' => 'No users found']);
    }
} catch (PDOException $e) {
    error_log("Database query failed: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'An internal server error occurred.']);
}


