<?php
  $displayUser = $selectedUser ?: ($currentUserName ?: 'Guest');
  $fallbackUser = $currentUserName ?: 'Guest';
?>
<div style="max-width: 720px; margin: 0 auto; color:#1f2a30;">
  <?php if (!empty($feedback)): ?>
    <div style="padding:0.75rem; background:#eef7ff; border:1px solid #c8ddff; color:#245; margin-bottom:1rem;">
      <?= htmlspecialchars($feedback, ENT_QUOTES, 'UTF-8'); ?>
    </div>
  <?php endif; ?>

  <form action="" method="post" style="background:#ffffff; padding:20px; border:1px solid rgba(0,0,0,0.08); border-radius:12px; box-shadow: 0 10px 30px rgba(0,0,0,0.08);">
    <div style="display:flex; flex-direction:column; gap:16px;">
      <div>
        <label for="username_choice" style="display:block; margin-bottom:8px; font-weight:600;">Send as user:</label>
        <select id="username_choice" name="username_choice" style="width:100%; padding:10px; border:1px solid #cfd8dc; border-radius:8px; font-size:15px;">
          <option value="">(no user)</option>
          <?php foreach ($userChoices as $u): ?>
            <option value="<?= htmlspecialchars($u['username'], ENT_QUOTES, 'UTF-8'); ?>" <?= ($selectedUser === $u['username']) ? 'selected' : '' ?>>
              <?= htmlspecialchars($u['username'], ENT_QUOTES, 'UTF-8'); ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div>
        <label for="content" style="display:block; margin-bottom:8px; font-weight:600;">Contact message:</label>
        <textarea id="content" name="content" rows="4" style="width:100%; padding:12px; border:1px solid #cfd8dc; border-radius:8px; font-size:15px; min-height:140px;" required></textarea>
      </div>
      <div style="text-align:center;">
        <button type="submit" style="padding:12px 22px; border:none; background: linear-gradient(135deg, #0ea371, #1391cf); color:#fff; cursor:pointer; border-radius:8px; font-size:16px; font-weight:600; box-shadow: 0 6px 14px rgba(0,0,0,0.12);">Send contact</button>
      </div>
    </div>
  </form>
</div>
<script>
  (function () {
    const selectEl = document.getElementById('username_choice');
    if (!selectEl) return;
    // no current-user display to update, so nothing else required
  })();
</script>
