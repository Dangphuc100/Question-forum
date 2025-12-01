<?php 
  $imagesPath = '/COMP1841/week10/images';
  $imageFiles = array_values(array_diff(scandir(__DIR__ . '/../images'), ['.', '..']));
?>
<div style="max-width: 960px; margin: 0 auto; color:#1f2a30;">
  <div style="margin-bottom: 1rem; color:#0f2b33;">
    <strong>Current user:</strong> <?= htmlspecialchars($currentUserName ?? 'Guest', ENT_QUOTES, 'UTF-8'); ?>
  </div>
  <form action="" method="post" style="background:#ffffff; padding:20px; border:1px solid rgba(0,0,0,0.08); border-radius:12px; box-shadow: 0 10px 30px rgba(0,0,0,0.08);">
    <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap:18px; margin-bottom:16px;">
      <div style="grid-column:1 / -1;">
        <label for="questiontext" style="display:block; margin-bottom:8px; font-weight:600;">Type your question here:</label>
        <textarea id="questiontext" name="questiontext" rows="3" style="width:100%; padding:12px; border:1px solid #cfd8dc; border-radius:8px; font-size:15px;" required></textarea>
      </div>
      <div>
        <label for="moduleid" style="display:block; margin-bottom:8px; font-weight:600;">Select a module:</label>
        <select name="moduleid" id="moduleid" style="width:100%; padding:10px; border:1px solid #cfd8dc; border-radius:8px; font-size:15px;">
          <option value="">Select a module</option>
          <?php foreach ($modules as $module): ?>
            <option value="<?= htmlspecialchars($module['id'], ENT_QUOTES, 'UTF-8'); ?>">
              <?= htmlspecialchars($module['modulename'], ENT_QUOTES, 'UTF-8'); ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div>
        <label for="image" style="display:block; margin-bottom:8px; font-weight:600;">Select an image:</label>
        <select name="image" id="image" onchange="previewImage(this.value)" style="width:100%; padding:10px; border:1px solid #cfd8dc; border-radius:8px; font-size:15px;">
          <option value="">Select an image</option>
          <?php foreach ($imageFiles as $img): ?>
            <option value="<?= htmlspecialchars($img, ENT_QUOTES, 'UTF-8'); ?>">
              <?= htmlspecialchars(pathinfo($img, PATHINFO_FILENAME), ENT_QUOTES, 'UTF-8'); ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div>
        <label style="display:block; margin-bottom:8px; font-weight:600;">Preview:</label>
        <div id="preview-container" style="border:1px dashed #90a4ae; padding:10px; border-radius:10px; background:#f7fbfc; min-height:140px; display:flex; align-items:center; justify-content:center;">
          <img id="image-preview" 
               src="" 
               alt="Selected image preview"
               style="max-width: 100%; max-height: 180px; display: none; margin:0 auto;">
          <span id="preview-placeholder" style="color:#607d8b; font-size:14px;">No image selected</span>
        </div>
      </div>
    </div>
    <div style="text-align:center;">
      <input type="submit" value="Add Question" style="padding:12px 22px; border:none; background: linear-gradient(135deg, #0ea371, #1391cf); color:#fff; cursor:pointer; border-radius:8px; font-size:16px; font-weight:600; box-shadow: 0 6px 14px rgba(0,0,0,0.12);">
    </div>
  </form>
</div>

<script>
  function previewImage(filename) {
    var img = document.getElementById('image-preview');
    var placeholder = document.getElementById('preview-placeholder');
    var imagesPath = '<?= $imagesPath ?>'; 
    if (!filename) {
      img.style.display = 'none';
      if (placeholder) { placeholder.style.display = 'block'; }
      return;
    }
    img.style.display = 'block';
    if (placeholder) { placeholder.style.display = 'none'; }
    img.src = imagesPath + '/' + encodeURIComponent(filename);
  }
  window.onload = function() {
    // start with preview hidden until user chooses
    var img = document.getElementById('image-preview');
    if (img) { img.style.display = 'none'; }
  };
</script>
