<?php
session_start();
include __DIR__ . '/includes/DatabaseConnection.php';
include __DIR__ . '/includes/DatabaseFunctions.php';

$redirect = $_POST['redirect'] ?? '/COMP1841/week10/user.php';
$newUser = isset($_POST['new_username']) ? trim($_POST['new_username']) : '';
$email = isset($_POST['new_email']) ? trim($_POST['new_email']) : '';
$password = isset($_POST['new_password']) ? $_POST['new_password'] : '';

// Auto-generate username/password if not provided
if ($newUser === '') {
    $newUser = 'user' . substr(uniqid('', true), -5);
}
if ($password === '') {
    $password = 'pw' . random_int(1000, 9999);
}

$canCreate = (
    $newUser !== '' &&
    strcasecmp($newUser, 'Admin') !== 0 &&
    $email !== '' &&
    filter_var($email, FILTER_VALIDATE_EMAIL)
);

if ($canCreate) {
    $before = findUserByEmail($pdo, $email);
    if ($before && strcasecmp($before['username'], $newUser) !== 0) {
        $redirect .= (strpos($redirect, '?') === false ? '?' : '&') . 'msg=emailtaken';
    } else {
        $created = addUserName($pdo, $newUser, $email, $password);
        $suffix = $created ? 'msg=created&created_user=' . urlencode($newUser) . '&created_pass=' . urlencode($password) : 'msg=invalid';
        $redirect .= (strpos($redirect, '?') === false ? '?' : '&') . $suffix;
    }
} else {
    $redirect .= (strpos($redirect, '?') === false ? '?' : '&') . 'msg=invalid';
}

header('Location: ' . $redirect);
exit;
