<?php if (session_status() === PHP_SESSION_NONE) { session_start(); } ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title><?= $title ?></title>
  <link rel="stylesheet" href="/COMP1841/week10/questions.css">
</head>
<body>
<?php $isAdmin = isset($_SESSION['Authorised']) && $_SESSION['Authorised'] === 'Y'; ?>
<header>
  <h1>Welcome to Internet Question Forum-Client Area</h1>
</header>

<nav>
  <ul>
    <li><a class="<?= ($title === 'Home') ? 'active' : '' ?>" href="/COMP1841/week10/index.php">Home</a></li>
    <li><a class="<?= ($title === 'Question list') ? 'active' : '' ?>" href="/COMP1841/week10/questions.php">Questions List</a></li>
    <li><a class="<?= ($title === 'Contact') ? 'active' : '' ?>" href="/COMP1841/week10/message_send.php">Contact</a></li>
    <li><a class="<?= ($title === 'Set User') ? 'active' : '' ?>" href="/COMP1841/week10/user.php?next=/COMP1841/week10/addquestion_public.php">User Management</a></li>
    <li><a class="<?= ($title === 'Module Management') ? 'active' : '' ?>" href="/COMP1841/week10/module.php">Module Management</a></li>
    <li class="nav-right"><a class="<?= ($title === 'Admin Login') ? 'active' : '' ?>" href="<?= $isAdmin ? '/COMP1841/week10/admin/login/Logout.php' : '/COMP1841/week10/admin/login/Login.html' ?>">
      <?= $isAdmin ? 'Logout' : 'Admin Login' ?>
    </a></li>
  </ul>
</nav>

<main>
  <?= $output ?>
</main>
</body>
</html>
