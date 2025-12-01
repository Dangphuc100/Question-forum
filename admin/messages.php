<?php
include __DIR__ . '/../includes/DatabaseConnection.php';
include __DIR__ . '/../includes/DatabaseFunctions.php';

$title = 'Messages';
$messages = allMessages($pdo);

ob_start();
include __DIR__ . '/../templates/admin_messages.html.php';
$output = ob_get_clean();

include __DIR__ . '/../templates/adminlayout.html.php';
