<?php
include __DIR__ . '/../includes/DatabaseConnection.php';
include __DIR__ . '/../includes/DatabaseFunctions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    deleteMessage($pdo, $_POST['id']);
}

header('Location: /COMP1841/week10/admin/messages.php');
exit;
