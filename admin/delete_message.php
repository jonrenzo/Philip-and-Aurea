<?php
session_start();
include("config.php");

if (!isset($_SESSION['uid'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized access.']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the message ID from the request
    $messageId = isset($_POST['message_id']) ? intval($_POST['message_id']) : 0;

    if ($messageId > 0) {
        // Prepare a statement to delete the message
        $stmt = $con->prepare("DELETE FROM messages WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ii", $messageId, $_SESSION['uid']); // Assuming 'user_id' is the foreign key

        if ($stmt->execute()) {
            // Check if a row was deleted
            if ($stmt->affected_rows > 0) {
                echo json_encode(['status' => 'success', 'message' => 'Message deleted successfully.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Message not found or already deleted.']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete the message.']);
        }

        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid message ID.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}

$con->close();
?>