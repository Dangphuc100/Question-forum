<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include __DIR__ . '/includes/DatabaseConnection.php';
include __DIR__ . '/includes/DatabaseFunctions.php';

$isAdmin = isset($_SESSION['Authorised']) && $_SESSION['Authorised'] === 'Y';
$currentUser = $_SESSION['current_user_name'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];
    $question = getQuestion($pdo, $id);

    $owner = $question['username'] ?? null;
    $userMatch = $currentUser && $owner && strcasecmp($currentUser, $owner) === 0;

    if ($isAdmin || $userMatch) {
        deleteQuestion($pdo, $id);
    }
}

header('Location: /COMP1841/week10/questions.php');
exit;
?>
