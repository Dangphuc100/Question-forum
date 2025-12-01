<div class="content">
  <h2>Messages</h2>
  <?php if (empty($messages)): ?>
    <p>No messages yet.</p>
  <?php else: ?>
    <table class="question-table">
      <tr>
        <th>Date</th>
        <th>User</th>
        <th>Content</th>
        <th>Actions</th>
      </tr>
      <?php foreach ($messages as $msg): ?>
        <tr>
          <td><?= htmlspecialchars($msg['created_at'], ENT_QUOTES, 'UTF-8'); ?></td>
          <td><?= htmlspecialchars($msg['username'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
          <td><?= nl2br(htmlspecialchars($msg['content'], ENT_QUOTES, 'UTF-8')); ?></td>
          <td>
            <form action="/COMP1841/week10/admin/delete_message.php" method="post" onsubmit="return confirm('Delete this message?');" style="margin:0;">
              <input type="hidden" name="id" value="<?= htmlspecialchars($msg['id'], ENT_QUOTES, 'UTF-8'); ?>">
              <button type="submit" style="background:none; border:none; color:#d9534f; cursor:pointer;">Delete</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    </table>
  <?php endif; ?>
</div>
