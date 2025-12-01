<?php
try {
    include '../includes/DatabaseConnection.php';
    include '../includes/DatabaseFunctions.php';
    ensureUsernameColumn($pdo);
    // Admin entries are always marked as Admin (isolated from public users)
    $currentUserName = 'Admin';

    if (isset($_POST['questiontext'])) {
        $selectedImage = !empty($_POST['image']) ? $_POST['image'] : null;
        $authorId = findOrCreateAuthorByName($pdo, $currentUserName);
        $moduleId = $_POST['moduleid'] ?? $_POST['categoryid'] ?? null;
        if ($moduleId === null || $moduleId === '') {
            throw new Exception('Please select a module.');
        }
        if ($selectedImage) {
            $imagesDir = realpath(__DIR__ . '/../images');
            $imagePath = $imagesDir . '/' . $selectedImage;
            
            if (!file_exists($imagePath)) {
                throw new Exception('Selected image not found: ' . $imagePath);
            }
        }

        insertQuestion($pdo, $_POST['questiontext'], $authorId, $moduleId, $selectedImage, $currentUserName);

        // After adding, stay in admin list
        header('Location: questions.php');
        exit;
    }

    $title = 'Add a new question';
    $authors = []; // admin uses fixed identity
    $modules = allModules($pdo);

    ob_start();
    include '../templates/addquestion.html.php';
    $output = ob_get_clean();

} catch (PDOException $e) {
    $title = 'Error adding question';
    $output = 'Database error: ' . $e->getMessage();
} catch (Exception $e) {
    $title = 'Error adding question';
    $output = 'File error: ' . $e->getMessage();
}

include '../templates/adminlayout.html.php';
?>
