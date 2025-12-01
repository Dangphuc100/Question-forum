<?php
require "login/Check.php";
try {
    include '../includes/DatabaseConnection.php';
    include '../includes/DatabaseFunctions.php';

    // Admin area: always show all questions and allow edits
    $isAdmin = true;
    $currentUserName = $_SESSION['current_user_name'] ?? null;
    $search = isset($_GET['q']) ? trim($_GET['q']) : '';
    $questions = allQuestions($pdo, null, $search);
    $totalQuestions = totalQuestions($pdo);
    $title = 'Question list';

    ob_start();
    include '../templates/questions.html.php';
    $output = ob_get_clean();
} catch (PDOException $e) {
    $title = 'An error has occured';
    $output = 'Database error: ' . $e->getMessage();
}

include '../templates/adminlayout.html.php';
?>
