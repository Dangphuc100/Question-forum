<?php
session_start();
try {
    include __DIR__ . '/includes/DatabaseConnection.php';
    include __DIR__ . '/includes/DatabaseFunctions.php';
    ensureUsernameColumn($pdo);

    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $redirect = $_POST['redirect'] ?? '/COMP1841/week10/user.php';

    if ($username === '' || strcasecmp($username, 'Admin') === 0) {
        $redirect .= (strpos($redirect, '?') === false ? '?' : '&') . (strcasecmp($username, 'Admin') === 0 ? 'msg=forbidden' : 'msg=invalid');
        header('Location: ' . $redirect);
        exit;
    }

    deleteUserAndQuestions($pdo, $username);

    if (isset($_SESSION['current_user_name']) && $_SESSION['current_user_name'] === $username) {
        unset($_SESSION['current_user_name']);
    }
    $redirect .= (strpos($redirect, '?') === false ? '?' : '&') . 'msg=deleted';

    header('Location: ' . $redirect);
    exit;
} catch (PDOException $e) {
    header('Location: /COMP1841/week10/user.php?msg=dberror');
    exit;
}
