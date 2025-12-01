<?php
// Set or clear current user then redirect to requested page
session_start();
include __DIR__ . '/includes/DatabaseConnection.php';
include __DIR__ . '/includes/DatabaseFunctions.php';

// default: go to public add-question page so users stay out of admin
$redirect = $_POST['redirect'] ?? '/COMP1841/week10/addquestion_public.php';

if (isset($_POST['username'])) {
    $name = trim($_POST['username']);
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    if (strcasecmp($name, 'Admin') === 0) {
        // prevent taking the Admin identity from public area
        $redirect .= (strpos($redirect, '?') === false ? '?' : '&') . 'msg=forbidden';
        header('Location: ' . $redirect);
        exit;
    }
    if ($name === '' || $email === '' || $password === '') {
        unset($_SESSION['current_user_name'], $_SESSION['current_user_email']);
        $redirect .= (strpos($redirect, '?') === false ? '?' : '&') . 'msg=invalid';
        header('Location: ' . $redirect);
        exit;
    }

    $user = verifyUserCredentials($pdo, $name, $password);
    $matchEmail = $user && isset($user['email']) && strcasecmp($email, $user['email']) === 0;
    if (!$matchEmail) {
        unset($_SESSION['current_user_name'], $_SESSION['current_user_email']);
        $failRedirect = '/COMP1841/week10/user.php';
        $failRedirect .= (strpos($failRedirect, '?') === false ? '?' : '&') . 'msg=badpass';
        header('Location: ' . $failRedirect);
        exit;
    }

    $_SESSION['current_user_name'] = $name;
    $_SESSION['current_user_email'] = $user['email'] ?? null;
}

header('Location: ' . $redirect);
exit;
?>
