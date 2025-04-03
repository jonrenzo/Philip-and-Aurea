<?php
session_start();
require_once("config.php");
require_once 'include/message_system.php';

// Check if user is logged in
if (!isset($_SESSION['uid'])) {
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'Please login to send messages']);
    exit(); // Ensure this exits to prevent further execution
}

// Validate request method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    exit(); // Ensure this exits to prevent further execution
}

try {
    // Get and sanitize POST data
    $subject = trim(filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_STRING));
    $message = trim(filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING));
    $user_id = $_SESSION['uid'];

    // Validate inputs
    if (empty($subject) || empty($message)) {
        echo json_encode(['status' => 'error', 'message' => 'Subject and message are required']);
        exit(); // Ensure this exits to prevent further execution
    }

    // Validate lengths
    if (strlen($subject) > 255) {
        echo json_encode(['status' => 'error', 'message' => 'Subject must be less than 255 characters']);
        exit(); // Ensure this exits to prevent further execution
    }

    if (strlen($message) > 10000) { // Adjust max length as needed
        echo json_encode(['status' => 'error', 'message' => 'Message is too long']);
        exit(); // Ensure this exits to prevent further execution
    }

    // Initialize message system and send message
    $messageSystem = new MessageSystem($con);
    $result = $messageSystem->sendMessage($user_id, $subject, $message);

    if ($result) {
        header('Location: messages.php');

        echo json_encode(['status' => 'success']);
        exit(); // Ensure this exits to prevent further execution
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to send message. Please try again.']);
        exit(); // Ensure this exits to prevent further execution
    }

} catch (Exception $e) {
    error_log("Message System Error: " . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => 'An error occurred while sending your message. Please try again later.']);
    exit(); // Ensure this exits to prevent further execution
}
