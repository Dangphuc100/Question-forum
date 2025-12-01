<?php
session_start();
include __DIR__ . '/includes/DatabaseConnection.php';
include __DIR__ . '/includes/DatabaseFunctions.php';

$redirect = $_POST['redirect'] ?? '/COMP1841/week10/addquestion_public.php';
$name = isset($_POST['login_username']) ? trim($_POST['login_username']) : '';
$email = isset($_POST['login_email']) ? trim($_POST['login_email']) : '';
$password = isset($_POST['login_password']) ? $_POST['login_password'] : '';

if ($name === '' || $email === '' || $password === '' || strcasecmp($name, 'Admin') === 0) {
    $redirect .= (strpos($redirect, '?') === false ? '?' : '&') . 'msg=invalid';
    header('Location: ' . $redirect);
    exit;
}

$user = verifyUserCredentials($pdo, $name, $password);
$valid = $user && isset($user['email']) && strcasecmp($user['email'], $email) === 0;

if ($valid) {
    $_SESSION['current_user_name'] = $name;
    $_SESSION['current_user_email'] = $user['email'];
    $redirect .= (strpos($redirect, '?') === false ? '?' : '&') . 'msg=loggedin';
} else {
    $redirect .= (strpos($redirect, '?') === false ? '?' : '&') . 'msg=missing';
}

header('Location: ' . $redirect);
exit;
?>
