<?php
include __DIR__ . '/../includes/DatabaseConnection.php';
include __DIR__ . '/../includes/DatabaseFunctions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $questiontext = $_POST['questiontext'] ?? null;
    // keep existing author if none provided
    $current = getQuestion($pdo, $id);
    $authorid = $current['userid'] ?? null;
    $moduleid = $_POST['moduleid'] ?? $_POST['categoryid'] ?? ($current['moduleid'] ?? $current['categoryid'] ?? null);
    $image = isset($_POST['image']) && $_POST['image'] !== '' ? $_POST['image'] : null;

    updateQuestion($pdo, $id, $questiontext, $authorid, $moduleid, $image);

    header('Location: questions.php');
    exit;
} else {
    $question = getQuestion($pdo, $_GET['id']);
    $title = 'Edit Question';
    // load modules for selection
    $authors = []; // user is fixed via existing question author
    $modules = allModules($pdo);

    ob_start();
    include __DIR__ . '/../templates/editquestion.html.php';
    $output = ob_get_clean();
    include __DIR__ . '/../templates/adminlayout.html.php';
}
?>
