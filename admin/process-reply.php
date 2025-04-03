<?php


declare(strict_types=1);
session_start();
require_once("../admin/config.php");
require_once "../include/message_system.php";

if (!isset($_SESSION['auser']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
   exit(json_encode(['status' => 'error', 'message' => 'Unauthorized access']));
}


$message_id = filter_input(INPUT_POST, 'message_id', FILTER_VALIDATE_INT);
$reply = trim(filter_input(INPUT_POST, 'reply', FILTER_SANITIZE_STRING));

if (!$message_id || !$reply) {
    error_log("Invalid input: message_id=$message_id, reply=$reply");
    exit(json_encode(['status' => 'error', 'message' => 'Invalid message ID or reply content']));
}


try {
    $con->begin_transaction();

    $stmt = $con->prepare("UPDATE messages SET admin_reply = ?, status = 'replied', replied_at = CURRENT_TIMESTAMP WHERE id = ?");
    $stmt->bind_param("si", $reply, $message_id);
    $stmt->execute();

    $stmt = $con->prepare("SELECT u.uemail, m.subject FROM messages m JOIN user u ON m.user_id = u.uid WHERE m.id = ?");
    $stmt->bind_param("i", $message_id);
    $stmt->execute();
    $userDetails = $stmt->get_result()->fetch_assoc();


    $con->commit();
    header('Location: messages.php');
    echo json_encode(['status' => 'success', 'message' => 'Reply sent successfully']);
} catch (Exception $e) {
    $con->rollback();
    echo json_encode(['status' => 'error', 'message' => 'Failed to send reply']);
}
?>
