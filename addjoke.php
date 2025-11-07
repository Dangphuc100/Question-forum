<?php
include __DIR__ . '/includes/DatabaseConnection.php';

if (isset($_POST['joketext'])) {
try {
    $sql = 'INSERT INTO joke 
            SET joketext = :joketext,
                jokedate = CURDATE(),
                authorid = :authorid,
                categoryid = :categoryid';
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':joketext', $_POST['joketext'], PDO::PARAM_STR);
    $stmt->bindValue(':authorid', $_POST['authors'], PDO::PARAM_INT);
    $stmt->bindValue(':categoryid', $_POST['categoryid'], PDO::PARAM_INT);
    $stmt->execute();

    header('location: jokes.php');
} catch (PDOException $e) {
    $title = 'Error adding joke';
    $output = 'Database error: ' . $e->getMessage();
}
} else {
    include 'includes/DatabaseConnection.php';
    $title = 'Add a new joke';
    $sql_a='SELECT * FROM author';
    $authors = $pdo->query($sql_a);
    $sql_c='SELECT * FROM category';
    $categories = $pdo->query($sql_c);

    ob_start();
    include __DIR__ . '/templates/addjoke.html.php';
    $output = ob_get_clean();
}

include __DIR__ . '/templates/layout.html.php';
?>
