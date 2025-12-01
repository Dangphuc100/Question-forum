<?php
include 'includes/DatabaseConnection.php';
function totalQuestions(){
    $query = $pdo->prepare('SELECT COUNT(*) FROM question');
    $query ->execute();
    $row = $query->fetch();
    return $row[0];
}
echo totalQuestions($pdo);

