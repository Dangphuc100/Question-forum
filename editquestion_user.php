<?php
session_start();
try {
    include __DIR__ . '/includes/DatabaseConnection.php';
    include __DIR__ . '/includes/DatabaseFunctions.php';

    $currentUserName = $_SESSION['current_user_name'] ?? '';
    $isAdmin = isset($_SESSION['Authorised']) && $_SESSION['Authorised'] === 'Y';
    if ($currentUserName === '') {
        header('Location: /COMP1841/week10/user.php?msg=missing');
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = $_POST['id'] ?? null;
        $questiontext = $_POST['questiontext'] ?? null;
        $moduleid = $_POST['moduleid'] ?? $_POST['categoryid'] ?? null;
        $image = isset($_POST['image']) && $_POST['image'] !== '' ? $_POST['image'] : null;

        $question = getQuestion($pdo, $id);
        if (!$question || (!$isAdmin && strcasecmp($question['username'] ?? '', $currentUserName) !== 0)) {
            header('Location: /COMP1841/week10/questions.php?msg=forbidden');
            exit;
        }

        updateQuestion($pdo, $id, $questiontext, $question['userid'], $moduleid, $image);

        header('Location: /COMP1841/week10/questions.php');
        exit;
    } else {
        $question = getQuestion($pdo, $_GET['id'] ?? null);
        if (!$question || (!$isAdmin && strcasecmp($question['username'] ?? '', $currentUserName) !== 0)) {
            header('Location: /COMP1841/week10/questions.php?msg=forbidden');
            exit;
        }
        $title = 'Edit Your Question';
        $authors = []; // not used
        $modules = allModules($pdo);
        $formAction = '/COMP1841/week10/editquestion_user.php';

        ob_start();
        include __DIR__ . '/templates/editquestion.html.php';
        $output = ob_get_clean();
        include __DIR__ . '/templates/layout.html.php';
    }
} catch (PDOException $e) {
    header('Location: /COMP1841/week10/questions.php?msg=dberror');
    exit;
}
?>
