<?php
try {
    include __DIR__ . '/includes/DatabaseConnection.php';
    include __DIR__ . '/includes/DatabaseFunctions.php';
    $sql = 'SELECT joke.id, joketext, author.name AS authorname, email, category.categoryName
            FROM joke
            INNER JOIN author ON authorid = author.id
            INNER JOIN category ON category.id = joke.categoryid';

    $jokes = $pdo->query($sql);
    $title = 'Joke list';
    $totalJokes = totalJokes($pdo);
    ob_start();
    include __DIR__ . '/templates/jokes.html.php';
    $output = ob_get_clean();
} catch (PDOException $e) {
    $title = 'An error has occurred';
    $output = 'Database error: ' . $e->getMessage();
}

include __DIR__ . '/templates/layout.html.php';
?>
