<?php
session_start();
try {
    include __DIR__ . '/includes/DatabaseConnection.php';
    include __DIR__ . '/includes/DatabaseFunctions.php';

    $id = isset($_POST['module_id']) ? (int) $_POST['module_id'] : 0;
    $newName = isset($_POST['new_module']) ? trim($_POST['new_module']) : '';
    $redirect = $_POST['redirect'] ?? '/COMP1841/week10/module.php';

    if ($id <= 0 || $newName === '') {
        $redirect .= (strpos($redirect, '?') === false ? '?' : '&') . 'msg=invalid';
        header('Location: ' . $redirect);
        exit;
    }

    renameModule($pdo, $id, $newName);

    $redirect .= (strpos($redirect, '?') === false ? '?' : '&') . 'msg=edited';
    header('Location: ' . $redirect);
    exit;
} catch (Exception $e) {
    $fallback = '/COMP1841/week10/module.php';
    $target = $redirect ?? $fallback;
    $target .= (strpos($target, '?') === false ? '?' : '&') . 'msg=dberror';
    header('Location: ' . $target);
    exit;
}
