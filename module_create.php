<?php
session_start();
include __DIR__ . '/includes/DatabaseConnection.php';
include __DIR__ . '/includes/DatabaseFunctions.php';

$redirect = $_POST['redirect'] ?? '/COMP1841/week10/module.php';

try {
    $name = $_POST['new_module'] ?? '';
    addModuleName($pdo, $name);
    header('Location: ' . $redirect . '?msg=created');
    exit;
} catch (Exception $e) {
    header('Location: ' . $redirect . '?msg=dberror');
    exit;
}
