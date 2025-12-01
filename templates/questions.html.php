<div class="content">
 <p><?= $totalQuestions ?> questions have been submitted to the Internet Question Forum.</p>
 <form method="get" action="/COMP1841/week10/admin/questions.php" style="margin-bottom:12px;">
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
          $imgName = $question['image'] ?? 'pic2.jng';
          $imgFullPath = __DIR__ . '/../images/' . $imgName;
          $imgWebPath = $imagesPath . '/' . $imgName;
          
          if (file_exists($imgFullPath)):
        ?>
          <img src="<?= htmlspecialchars($imgWebPath, ENT_QUOTES, 'UTF-8') ?>" 
               alt="Question image"
               style="max-width: 120px; max-height: 120px; object-fit: contain;">
        <?php else: ?>
          <img src="<?= $imagesPath ?>/pic2.jng" 
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
                // format date as dd/mm/YYYY
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

      <td style="min-width:140px;">
        <div style="display:flex; gap:10px; align-items:center; justify-content:center;">
          <form action="deletequestion.php" method="post" onsubmit="return confirm('Are you sure you want to delete this question?');" style="margin:0;">
            <input type="hidden" name="id" value="<?= $question['id'] ?>">
            <button type="submit" style="background:#d9534f; color:#fff; border:none; padding:6px 10px; border-radius:4px; cursor:pointer;">Delete</button>
          </form>
          <?php if (!empty($isAdmin)): ?>
            <a href="/COMP1841/week10/admin/editquestion.php?id=<?= $question['id'] ?>" style="background:#2c846e; color:#fff; padding:6px 10px; border-radius:4px; text-decoration:none;">Edit</a>
          <?php endif; ?>
        </div>
      </td>
      
    </tr>
  <?php endforeach; ?>
</table>
 </div>
</div>
