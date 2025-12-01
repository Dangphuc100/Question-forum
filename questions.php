<?php
try {
    if (session_status() === PHP_SESSION_NONE) { session_start(); }
    include __DIR__ . '/includes/DatabaseConnection.php';
    include __DIR__ . '/includes/DatabaseFunctions.php';
    ensureUsernameColumn($pdo);
    ensureModuleTable($pdo);

    $currentUserName = $_SESSION['current_user_name'] ?? null;
    $isAdmin = isset($_SESSION['Authorised']) && $_SESSION['Authorised'] === 'Y';

    $includeImage = columnExists($pdo, 'question', 'image');
    $search = isset($_GET['q']) ? trim($_GET['q']) : '';

    // If not admin, restrict to current user; admin sees all
    $questions = allQuestions($pdo, $isAdmin ? null : $currentUserName, $search);
    
    $title = 'Question list';
    $totalQuestions = count($questions);
    
    ob_start();
    include __DIR__ . '/templates/public_questions.html.php';
    $output = ob_get_clean();
} catch (PDOException $e) {
    $title = 'An error has occurred';
    $output = 'Database error: ' . $e->getMessage();
}

include __DIR__ . '/templates/layout.html.php';
?>
