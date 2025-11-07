<?php
include __DIR__ . '/includes/DatabaseConnection.php';

if (isset($_POST['joketext'])) {
    $sql = 'UPDATE joke SET joketext = :joketext WHERE id = :id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':joketext', $_POST['joketext']);
    $stmt->bindValue(':id', $_POST['id']);
    $stmt->execute();
    header('location: jokes.php');
} else {
    $sql = 'SELECT * FROM joke WHERE id = :id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $_GET['id']);
    $stmt->execute();
    $joke = $stmt->fetch();

    $title = 'Edit Joke';
    ob_start();
    include __DIR__ . '/templates/editjoke.html.php';
    $output = ob_get_clean();
    include __DIR__ . '/templates/layout.html.php';
}
?>
