<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title><?= $title ?></title>
  <link rel="stylesheet" href="jokes.css">
</head>
<body>
<header>
  <h1>Greenwich Y hihi</h1>
</header>

<nav>
  <a href="index.php">Home</a>
  <a href="jokes.php">Jokes List</a>
  <a href="addjoke.php">Add a new joke</a>
</nav>

<main>
  <?= $output ?>
</main>
</body>
</html>
