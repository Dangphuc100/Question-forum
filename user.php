<?php
session_start();
include __DIR__ . '/includes/DatabaseConnection.php';
include __DIR__ . '/includes/DatabaseFunctions.php';
ensureUsernameColumn($pdo);

$next = $_GET['next'] ?? '/COMP1841/week10/addquestion_public.php';
$currentName = $_SESSION['current_user_name'] ?? '';
$messageKey = $_GET['msg'] ?? '';
$createdUser = $_GET['created_user'] ?? '';
$createdPass = $_GET['created_pass'] ?? '';
$userRows = allUsernames($pdo, $currentName);

$messages = [
  'deleted'   => 'User deleted and their questions removed.',
  'invalid'   => 'Please provide a valid username and email.',
  'emailtaken'=> 'That email is already used by another user.',
  'dberror'   => 'Database error while deleting user.',
  'forbidden' => 'The Admin account is protected.',
  'edited'    => 'User name updated.',
  'created'   => 'User added to the list.',
  'loggedin'  => 'Logged in successfully.',
  'missing'   => 'Please create or log in with an existing user before continuing.',
  'badpass'   => 'Incorrect password. Try again.'
];
?>
<?php
$title = 'Set User';
ob_start();
?>
<div class="user-card">
  <?php if (!empty($messageKey) && isset($messages[$messageKey])): ?>
    <?php
      $isError = in_array($messageKey, ['badpass','invalid','emailtaken','dberror','forbidden']);
      $bg = $isError ? '#fdecea' : '#eef7ff';
      $border = $isError ? '#f5c2c7' : '#c8ddff';
      $color = $isError ? '#842029' : '#245';
    ?>
    <div style="margin-bottom: 1rem; padding: 0.75rem; background:<?= $bg ?>; border:1px solid <?= $border ?>; color:<?= $color ?>;">
      <?= htmlspecialchars($messages[$messageKey], ENT_QUOTES, 'UTF-8') ?>
      <?php if ($messageKey === 'created' && $createdUser !== ''): ?>
        <br><strong>New credentials:</strong> <?= htmlspecialchars($createdUser, ENT_QUOTES, 'UTF-8') ?> / <?= htmlspecialchars($createdPass, ENT_QUOTES, 'UTF-8') ?>
      <?php endif; ?>
    </div>
  <?php endif; ?>

  <div class="auth-stack">
    <div class="auth-card">
      <h3 style="margin:0 0 0.5rem 0;">Create account</h3>
      <p style="margin:0 0 0.5rem 0; color:#566;">Leave fields blank to auto-generate username and password.</p>
      <form action="/COMP1841/week10/user_create.php" method="post" class="form-row">
        <input id="new_username" name="new_username" type="text" value="" placeholder="Username (optional)">
        <input id="new_email" name="new_email" type="email" value="" placeholder="Email">
        <input id="new_password" name="new_password" type="password" value="" placeholder="Password (optional)">
        <input type="hidden" name="redirect" value="/COMP1841/week10/user.php">
        <button type="submit" class="btn btn-primary">Add to list</button>
      </form>
    </div>

    <div class="auth-card">
      <h3 style="margin:0 0 0.5rem 0;">Login</h3>
      <p style="margin:0 0 0.5rem 0; color:#566;">Use an existing account by clicking <strong>Use</strong> in the list below and entering its password.</p>
    </div>
  </div>

  <section>
    <h3 style="margin-top:0; margin-bottom:0.5rem;">Existing users</h3>
    <p style="margin-top:0; color:#555;">Create a user above with email, then click Use to sign in as that user. Delete removes the user and all their questions.</p>
    <?php if (count($userRows) === 0): ?>
      <p style="margin:0.75rem 0;">No users found yet.</p>
    <?php else: ?>
      <table class="user-table">
        <tr>
          <th>User</th>
          <th>Email</th>
          <th>Questions</th>
          <th>Actions</th>
        </tr>
        <?php foreach ($userRows as $row): ?>
          <?php if (strcasecmp($row['username'], 'Admin') === 0) { continue; } ?>
          <tr>
            <td><?= htmlspecialchars($row['username'], ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= htmlspecialchars($row['email'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= htmlspecialchars($row['total'], ENT_QUOTES, 'UTF-8') ?></td>
            <td>
              <div class="user-actions">
                <?php $panelId = 'edit-' . htmlspecialchars($row['id'] ?? $row['username'], ENT_QUOTES, 'UTF-8'); ?>
                <?php $useId = 'use-' . htmlspecialchars($row['id'] ?? $row['username'], ENT_QUOTES, 'UTF-8'); ?>
                <button type="button" class="btn btn-use toggle-use" data-target="<?= $useId ?>">Use</button>
                <button type="button" class="btn btn-edit toggle-edit" data-target="<?= $panelId ?>">Edit</button>
                <form action="/COMP1841/week10/user_delete.php" method="post" style="margin:0;" onsubmit="return confirm('Delete this user and all their questions?');">
                  <input type="hidden" name="username" value="<?= htmlspecialchars($row['username'], ENT_QUOTES, 'UTF-8') ?>">
                  <input type="hidden" name="redirect" value="/COMP1841/week10/user.php">
                  <button type="submit" class="btn btn-delete">Delete</button>
                </form>
              </div>
              <div class="use-panel" id="<?= $useId ?>" style="display:none; margin-top:8px;">
                <form action="/COMP1841/week10/user_set.php" method="post" class="use-form">
                  <label style="font-size:0.85rem;">Password</label>
                  <input type="hidden" name="username" value="<?= htmlspecialchars($row['username'], ENT_QUOTES, 'UTF-8') ?>">
                  <input type="hidden" name="email" value="<?= htmlspecialchars($row['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                  <input type="password" name="password" value="" placeholder="Enter password">
                  <input type="hidden" name="redirect" value="/COMP1841/week10/addquestion_public.php">
                  <button type="submit" class="btn btn-primary">Use</button>
                </form>
              </div>
              <div class="edit-panel" id="<?= $panelId ?>" style="display:none; margin-top:8px;">
                <form action="/COMP1841/week10/user_edit.php" method="post" class="edit-form">
                  <input type="hidden" name="old_username" value="<?= htmlspecialchars($row['username'], ENT_QUOTES, 'UTF-8') ?>">
                  <input type="text" name="new_username" value="<?= htmlspecialchars($row['username'], ENT_QUOTES, 'UTF-8') ?>" placeholder="New username">
                  <input type="email" name="new_email" value="<?= htmlspecialchars($row['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="New email">
                  <input type="password" name="old_password" placeholder="Current password" required>
                  <input type="password" name="new_password" placeholder="New password" required>
                  <input type="hidden" name="redirect" value="/COMP1841/week10/user.php">
                  <button type="submit" class="btn btn-primary">Save</button>
                </form>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
      </table>
    <?php endif; ?>
  </section>
</div>
<style>
  .user-card { width: 920px; margin: 20px auto; background: #f9fdfc; padding: 24px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); border-radius: 12px; border:1px solid rgba(0,0,0,0.06); }
  .user-table { width: 100%; border-collapse: collapse; margin-top: 12px; }
  .user-table th { background: #d3d3d3; text-align: left; padding: 0.7rem; font-weight: 600; }
  .user-table td { padding: 0.7rem; background: #fff; border-bottom:1px solid #eee; }
  .user-actions { display:flex; gap:0.5rem; align-items:center; flex-wrap:wrap; }
  .use-form { display:grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap:0.4rem; margin:0; align-items:end; }
  .edit-form { display:grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap:0.4rem; margin:0; align-items:end; }
  .use-form input[type="password"], .edit-form input[type="text"], .edit-form input[type="email"], .edit-form input[type="password"] { padding:0.45rem; border:1px solid #cfd8dc; border-radius:6px; width:100%; }
  .btn { padding:0.45rem 0.8rem; border:1px solid #ccc; background:#f5f5f5; cursor:pointer; border-radius:4px; }
  .btn-edit { background:#f0ad4e; border-color:#ec971f; color:#fff; }
  .btn-delete { background:#d9534f; border-color:#c9302c; color:#fff; }
  .auth-stack { display:flex; flex-direction:column; gap:12px; margin-bottom: 1.25rem; }
  .auth-card { background:#f4f8fb; border:1px solid #dfe6ed; border-radius:10px; padding:12px 14px; box-shadow: 0 4px 10px rgba(0,0,0,0.04); }
  .form-row { display:grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 0.6rem; margin:0; align-items:center; }
  .form-row input[type="text"], .form-row input[type="email"], .form-row input[type="password"] { padding:0.55rem; border:1px solid #cfd8dc; border-radius:6px; width:100%; }
  .btn-primary { padding:0.55rem 1rem; border:1px solid #2c846e; background:#2c846e; color:#fff; border-radius:6px; cursor:pointer; }
  .form-inline { display:flex; gap:0.75rem; align-items:center; margin-bottom:1.5rem; flex-wrap:wrap; }
</style>
<script>
  function togglePanel(className) {
    document.querySelectorAll(className).forEach(function(btn){
      btn.addEventListener('click', function(){
        var targetId = this.getAttribute('data-target');
        var panel = document.getElementById(targetId);
        if (panel) {
          panel.style.display = panel.style.display === 'none' ? 'block' : 'none';
        }
      });
    });
  }
  togglePanel('.toggle-edit');
  togglePanel('.toggle-use');
</script>
<?php
$output = ob_get_clean();
include __DIR__ . '/templates/layout.html.php';
