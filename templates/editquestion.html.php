<?php $actionUrl = isset($formAction) ? $formAction : 'editquestion.php'; ?>
<form action="<?= htmlspecialchars($actionUrl, ENT_QUOTES, 'UTF-8') ?>" method="post">
  <input type="hidden" name="id" value="<?= $question['id']; ?>">

  <label for="questiontext">Edit your question:</label><br>
  <textarea id="questiontext" name="questiontext" rows="4" cols="50"><?= htmlspecialchars($question['questiontext'], ENT_QUOTES, 'UTF-8'); ?></textarea><br>

  <div style="margin-top:1rem; display:flex; gap:2rem; align-items:flex-start;">
    <div>
      <label for="moduleid">Module:</label><br>
      <select name="moduleid" id="moduleid" style="min-width:200px;">
        <option value="">-- Keep current --</option>
        <?php 
          $currentModule = $question['moduleid'] ?? $question['categoryid'] ?? '';
          foreach ($modules as $module): ?>
          <option value="<?= $module['id'] ?>" <?= ($currentModule == $module['id']) ? 'selected' : '' ?>><?= htmlspecialchars($module['modulename'], ENT_QUOTES, 'UTF-8') ?></option>
        <?php endforeach; ?>
      </select>
    </div>

    <div>
      <label for="image">Image:</label><br>
      <?php $images = array_diff(scandir(__DIR__ . '/../images'), ['.', '..']); ?>
      <select name="image" id="image" onchange="previewImage(this.value)" style="min-width:200px;">
        <option value="">-- Keep current --</option>
        <?php foreach ($images as $img): ?>
          <option value="<?= htmlspecialchars($img, ENT_QUOTES, 'UTF-8') ?>" <?= (isset($question['image']) && $question['image'] == $img) ? 'selected' : '' ?>><?= htmlspecialchars($img, ENT_QUOTES, 'UTF-8') ?></option>
        <?php endforeach; ?>
      </select>
      <div style="margin-top:0.5rem;">
        <img id="image-preview" src="/COMP1841/week10/images/<?= htmlspecialchars($question['image'] ?? 'pic3.jpg', ENT_QUOTES, 'UTF-8') ?>" alt="Preview" style="max-width:120px; max-height:120px; border:1px solid #ccc; padding:3px;">
      </div>
    </div>
  </div>

  <div style="margin-top:1rem;">
    <input type="submit" value="Save">
  </div>
</form>

<script>
  function previewImage(filename) {
    var img = document.getElementById('image-preview');
    if (!filename) { return; }
    img.src = '/COMP1841/week10/images/' + encodeURIComponent(filename);
  }
</script>
