<div class="content">
  <p><?= $totalQuestions ?> questions have been submitted to the Internet Question Forum.</p>
  <form method="get" action="/COMP1841/week10/questions.php" style="margin-bottom:12px;">
    <input type="text" name="q" value="<?= htmlspecialchars($_GET['q'] ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="Search question text" style="padding:8px; width:260px;">
    <button type="submit" style="padding:8px 12px;">Search</button>
  </form>

  <?php 
     $imagesPath = '/COMP1841/week10/images';
  ?>
  <div style="overflow-x:auto;">
   <table class="question-table">
  <tr>
    <th>Image</th>
    <th>Question Text</th>
    <th>Module</th>
    <th>User</th>
    <th>Actions</th>
  </tr>

  <?php foreach ($questions as $question): ?>
    <tr>
      <td class="question-image">
        <?php 
          $imgName = isset($question['image']) && !empty($question['image']) ? $question['image'] : 'pic3.jpg';
          
          $imgFileSystem = realpath(__DIR__ . '/../images/' . $imgName);
          if ($imgFileSystem && file_exists($imgFileSystem)):
        ?>
          <img src="<?= $imagesPath ?>/<?= htmlspecialchars($imgName, ENT_QUOTES, 'UTF-8') ?>" 
               alt="Selected image"
               style="max-width: 120px; max-height: 120px; object-fit: contain;">
        <?php else: ?>
          <img src="<?= $imagesPath ?>/pic3.jpg" 
               alt="Default image"
               style="max-width: 120px; max-height: 120px; object-fit: contain;">
        <?php endif; ?>
      </td>

      <td class="question-text">
        <strong>Question Text:</strong>
        <?= htmlspecialchars($question['questiontext'], ENT_QUOTES, 'UTF-8') ?>
      </td>

      <td class="question-category">
        <strong>Module:</strong>
        <?= htmlspecialchars($question['modulename'] ?? '', ENT_QUOTES, 'UTF-8') ?>
        <div class="question-date" style="margin-top:0.5rem; font-size:0.9rem; color:#666;">
          Added:
          <?php
            $qd = $question['questiondate'] ?? null;
            if ($qd && $qd !== '0000-00-00') {
                $ts = strtotime($qd);
                if ($ts !== false) {
                    echo ' ' . date('d/m/Y', $ts);
                } else {
                    echo ' ' . htmlspecialchars($qd, ENT_QUOTES, 'UTF-8');
                }
            } else {
                echo '';
            }
          ?>
        </div>
      </td>
      <td class="question-author">
        User: <?= htmlspecialchars($question['username'] ?? $question['authorname'] ?? '', ENT_QUOTES, 'UTF-8') ?>
      </td>
      <td>
        <?php
          $userMatch = isset($currentUserName, $question['username']) && strcasecmp($currentUserName, $question['username']) === 0;
          $canDelete = (!empty($isAdmin)) || $userMatch;
          $canEdit = (!empty($isAdmin)) || $userMatch;
          $editUrl = !empty($isAdmin)
            ? '/COMP1841/week10/admin/editquestion.php?id=' . (int)$question['id']
            : '/COMP1841/week10/editquestion_user.php?id=' . (int)$question['id'];
        ?>
        <div style="display:flex; gap:8px; justify-content:center; flex-wrap:wrap;">
        <?php if ($canDelete || $canEdit): ?>
          <?php if ($canDelete): ?>
          <form action="/COMP1841/week10/deletequestion_public.php" method="post" onsubmit="return confirm('Delete this question?');" style="margin:0;">
            <input type="hidden" name="id" value="<?= $question['id'] ?>">
            <button type="submit" style="background:#d9534f; border:none; color:#fff; padding:6px 10px; border-radius:4px; cursor:pointer;">Delete</button>
          </form>
          <?php endif; ?>
          <?php if ($canEdit): ?>
            <a href="<?= $editUrl ?>" style="background:#2c846e; color:#fff; padding:6px 10px; border-radius:4px; text-decoration:none;">Edit</a>
          <?php endif; ?>
        <?php else: ?>
          <span style="color:#777; font-size:0.9rem;">Set your user to manage your questions</span>
        <?php endif; ?>
        </div>
      </td>
    </tr>
  <?php endforeach; ?>
</table>
  </div>
</div>
