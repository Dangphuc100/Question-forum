<?php
session_start();
include __DIR__ . '/includes/DatabaseConnection.php';
include __DIR__ . '/includes/DatabaseFunctions.php';

$currentUserName = $_SESSION['current_user_name'] ?? '';
$feedback = '';
$userChoices = allUsernames($pdo, $currentUserName);
$selectedUser = $currentUserName;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selectedUser = isset($_POST['username_choice']) ? trim($_POST['username_choice']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : null;
    $content = isset($_POST['content']) ? trim($_POST['content']) : '';

    if ($content === '') {
        $feedback = 'Please enter a contact message.';
    } else {
        addMessage($pdo, $selectedUser ?: null, $email, $content);
        $feedback = 'Contact sent!';
    }
}

$title = 'Contact';
ob_start();
include __DIR__ . '/templates/message_form.html.php';
$output = ob_get_clean();
include __DIR__ . '/templates/layout.html.php';
