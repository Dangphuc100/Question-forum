<?php
try {
    if (session_status() === PHP_SESSION_NONE) { session_start(); }
    include __DIR__ . '/includes/DatabaseConnection.php';
    include __DIR__ . '/includes/DatabaseFunctions.php';
    ensureUsernameColumn($pdo);

    $currentUserName = $_SESSION['current_user_name'] ?? null;
    $currentUserName = $currentUserName ?: 'Guest';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (empty($currentUserName)) {
            throw new Exception('Please set a user name first (User link in the menu).');
        }

        $selectedImage = !empty($_POST['image']) ? $_POST['image'] : null;
        $authorId = findOrCreateAuthorByName($pdo, $currentUserName);
        $moduleId = $_POST['moduleid'] ?? $_POST['categoryid'] ?? null;
        if ($moduleId === null || $moduleId === '') {
            throw new Exception('Please select a module.');
        }
        if ($selectedImage) {
            $imagesDir = realpath(__DIR__ . '/images');
            $imagePath = $imagesDir . '/' . $selectedImage;
            
            if (!file_exists($imagePath)) {
                throw new Exception('Selected image not found: ' . $imagePath);
            }
        }

        insertQuestion($pdo, $_POST['questiontext'], $authorId, $moduleId, $selectedImage, $currentUserName);

        header('Location: questions.php');
        exit;
    }

    $title = 'Add a new question';
    $authors = []; // user is fixed from session
    $modules = allModules($pdo);

    ob_start();
    include __DIR__ . '/templates/addquestion.html.php';
    $output = ob_get_clean();
} catch (PDOException $e) {
    $title = 'Error adding question';
    $output = 'Database error: ' . $e->getMessage();
} catch (Exception $e) {
    $title = 'Error adding question';
    $output = 'Error: ' . $e->getMessage();
}

include __DIR__ . '/templates/layout.html.php';
