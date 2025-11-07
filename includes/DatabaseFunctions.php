<?php
function query($pdo, $sql, $parameters = []){
  $query = $pdo->prepare($sql);
  $query->execute($parameters);
  return $query;
}
function updateJoke($pdo, $jokeId, $joketext) {
    $query = 'UPDATE joke SET joketext = :joketext WHERE id = :id';
    $parameters = [':joketext' => $joketext, ':id' => $jokeId];
    query($pdo, $query, $parameters);
}
function deleteJoke($pdo, $id) {
    $query = 'DELETE FROM joke WHERE id = :id';
    $parameters = [':id' => $id];
    query($pdo, 'DELETE FROM joke WHERE id = :id', $parameters);
}

function insertJoke($pdo, $joketext, $authorid, $categoryid) {
    $query = 'INSERT INTO joke (joketext, jokedate, authorid, categoryid)
              VALUES (:joketext, CURDATE(), :authorid, :categoryid)';
    $parameters = [
        ':joketext' => $joketext,
        ':authorid' => $authorid,
        ':categoryid' => $categoryid
    ];
    query($pdo, $query, $parameters);
}

function getJoke($pdo, $id) {
  $parameters = [':id' => $id];
  $query = query($pdo, 'SELECT * FROM joke WHERE id = :id', $parameters);
  return $query->fetch();
}

function totalJokes($pdo) {
    $query = $pdo->query('SELECT COUNT(*) FROM joke');
    $query->execute();
    $row = $query->fetch();
    return $row [0];
}
function allAuthors($pdo) {
    $authors = query($pdo, 'SELECT * FROM author');
    return $authors->fetchAll();
}

function allCategories($pdo) {
    $categories = query($pdo, 'SELECT * FROM category');
    return $categories->fetchAll();
}
function allJokes($pdo) {
    $jokes = query($pdo, 'SELECT joke.id, joketext, author.name AS authorname, 
                                 author.email, category.categoryName 
                          FROM joke
                          INNER JOIN author ON authorid = author.id
                          INNER JOIN category ON categoryid = category.id');
    return $jokes->fetchAll();
}

?>
