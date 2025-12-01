<?php
$title = 'Internet Questions Database';
ob_start();
include '../templates/home.html.php';
$output = ob_get_clean();
include '../templates/adminlayout.html.php';
?>