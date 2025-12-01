<?php
session_start();
include __DIR__ . '/includes/DatabaseConnection.php';
include __DIR__ . '/includes/DatabaseFunctions.php';

$redirect = $_POST['redirect'] ?? '/COMP1841/week10/module.php';
$moduleId = isset($_POST['module_id']) ? (int) $_POST['module_id'] : 0;

if ($moduleId <= 0) {
    header('Location: ' . $redirect . '?msg=invalid');
    exit;
}

try {
    deleteModule($pdo, $moduleId);
    header('Location: ' . $redirect . '?msg=deleted');
    exit;
} catch (Exception $e) {
    header('Location: ' . $redirect . '?msg=dberror');
    exit;
}
