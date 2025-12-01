<?php
include __DIR__ . '/../includes/DatabaseConnection.php';
include __DIR__ . '/../includes/DatabaseFunctions.php';

deleteQuestion($pdo, $_POST['id']);

header('location: questions.php');
?>