<?php
session_start();
try {
    include __DIR__ . '/includes/DatabaseConnection.php';
    include __DIR__ . '/includes/DatabaseFunctions.php';
    ensureUsernameColumn($pdo);

    $old = isset($_POST['old_username']) ? trim($_POST['old_username']) : '';
    $new = isset($_POST['new_username']) ? trim($_POST['new_username']) : '';
    $email = isset($_POST['new_email']) ? trim($_POST['new_email']) : '';
    $password = isset($_POST['new_password']) ? $_POST['new_password'] : '';
    $oldPassword = isset($_POST['old_password']) ? $_POST['old_password'] : '';
    $redirect = $_POST['redirect'] ?? '/COMP1841/week10/user.php';

    // Guard against Admin edits and invalid names
    if ($old === '' || $new === '' || $email === '' || $password === '' || $oldPassword === '' || strcasecmp($old, 'Admin') === 0 || strcasecmp($new, 'Admin') === 0) {
        $redirect .= (strpos($redirect, '?') === false ? '?' : '&') . 'msg=forbidden';
        header('Location: ' . $redirect);
        exit;
    }

    // verify old password before allowing changes
    $userCheck = verifyUserCredentials($pdo, $old, $oldPassword);
    if (!$userCheck) {
        $redirect .= (strpos($redirect, '?') === false ? '?' : '&') . 'msg=badpass';
        header('Location: ' . $redirect);
        exit;
    }

    // update username in questions
    renameUser($pdo, $old, $new);

    // update email/password in users (password required and already validated)
    $params = [':new' => $new, ':old' => $old, ':email' => $email, ':hash' => password_hash($password, PASSWORD_DEFAULT)];
    $sql = 'UPDATE users SET username = :new, email = :email, password_hash = :hash WHERE username = :old';
    query($pdo, $sql, $params);

    if (isset($_SESSION['current_user_name']) && $_SESSION['current_user_name'] === $old) {
        $_SESSION['current_user_name'] = $new;
    }

    $redirect .= (strpos($redirect, '?') === false ? '?' : '&') . 'msg=edited';
    header('Location: ' . $redirect);
    exit;
} catch (PDOException $e) {
    header('Location: /COMP1841/week10/user.php?msg=dberror');
    exit;
}
