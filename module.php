<?php
session_start();
include __DIR__ . '/includes/DatabaseConnection.php';
include __DIR__ . '/includes/DatabaseFunctions.php';

$modules = allModules($pdo);
$messageKey = $_GET['msg'] ?? '';

$messages = [
  'created' => 'Module added to the list.',
  'deleted' => 'Module removed.',
  'invalid' => 'Please choose a valid module to delete.',
  'edited'  => 'Module renamed.',
  'dberror' => 'Database error while updating modules.'
];

$title = 'Module Management';
ob_start();
?>
<div style="max-width: 960px; margin: 0 auto;">

  <?php if (!empty($messageKey) && isset($messages[$messageKey])): ?>
    <div style="margin-bottom: 1rem; padding: 0.75rem; background:#eef7ff; border:1px solid #c8ddff; color:#245;">
      <?= htmlspecialchars($messages[$messageKey], ENT_QUOTES, 'UTF-8') ?>
    </div>
  <?php endif; ?>

  <form action="/COMP1841/week10/module_create.php" method="post" style="display:flex; gap:0.75rem; align-items:center; margin-bottom:1.5rem; flex-wrap:wrap;">
    <label for="new_module" style="font-weight:bold;">Create module:</label>
    <input id="new_module" name="new_module" type="text" value="" placeholder="Enter new module name" style="flex:1; min-width:260px; padding:0.5rem;">
    <input type="hidden" name="redirect" value="/COMP1841/week10/module.php">
    <button type="submit" style="padding:0.5rem 1rem;">Add to list</button>
  </form>

  <section style="background:#fafafa; border:1px solid #ddd; border-radius:6px; padding:16px;">
    <h3 style="margin-top:0; margin-bottom:0.75rem;">Existing modules</h3>
    <?php if (count($modules) === 0): ?>
      <p style="margin:0.75rem 0;">No modules found yet.</p>
    <?php else: ?>
      <table class="module-table">
        <tr>
          <th>Module</th>
          <th>Actions</th>
        </tr>
        <?php foreach ($modules as $row): ?>
          <tr>
            <td><?= htmlspecialchars($row['modulename'], ENT_QUOTES, 'UTF-8') ?></td>
            <td>
              <div class="module-actions">
                <form action="/COMP1841/week10/module_edit.php" method="post" class="edit-form" style="margin:0;">
                  <input type="hidden" name="module_id" value="<?= (int)$row['id'] ?>">
                  <input type="hidden" name="new_module" value="<?= htmlspecialchars($row['modulename'], ENT_QUOTES, 'UTF-8') ?>">
                  <input type="hidden" name="redirect" value="/COMP1841/week10/module.php">
                  <button type="button" class="btn btn-edit" data-name="<?= htmlspecialchars($row['modulename'], ENT_QUOTES, 'UTF-8') ?>">Edit</button>
                </form>
                <form action="/COMP1841/week10/module_delete.php" method="post" style="margin:0;" onsubmit="return confirm('Delete this module?');">
                  <input type="hidden" name="module_id" value="<?= (int)$row['id'] ?>">
                  <input type="hidden" name="redirect" value="/COMP1841/week10/module.php">
                  <button type="submit" class="btn btn-delete">Delete</button>
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
  .module-table { width: 100%; border-collapse: collapse; margin-top: 12px; }
  .module-table th { background: #d3d3d3; text-align: left; padding: 0.6rem; }
  .module-table td { padding: 0.6rem; background: #fff; }
  .module-actions { display:flex; gap:0.5rem; align-items:center; }
  .btn { padding:0.35rem 0.7rem; border:1px solid #ccc; background:#f5f5f5; cursor:pointer; }
  .btn-edit { background:#f0ad4e; border-color:#ec971f; color:#fff; }
  .btn-delete { background:#d9534f; border-color:#c9302c; color:#fff; }
</style>
<script>
  document.querySelectorAll('.edit-form .btn-edit').forEach(function(btn){
    btn.addEventListener('click', function(){
      var current = this.getAttribute('data-name') || '';
      var newer = prompt('Enter new name for module "' + current + '"', current);
      if (newer && newer.trim() !== '') {
        var form = this.closest('form');
        form.querySelector('input[name="new_module"]').value = newer.trim();
        form.submit();
      }
    });
  });
</script>
<?php
$output = ob_get_clean();
include __DIR__ . '/templates/layout.html.php';
