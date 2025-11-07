<form action="" method="post">
  <label for="joketext">Type your joke here:</label><br>
  <textarea id="joketext" name="joketext" rows="3" cols="40"></textarea>

  <select name="authors">
      <option value="">Select an author</option>
      <?php foreach ($authors as $author): ?>
        <option value="<?= $author['id']; ?>"><?= htmlspecialchars($author['name'], ENT_QUOTES, 'UTF-8'); ?></option>
      <?php endforeach; ?>
  </select>
<select name="categoryid" id="categoryid">
  <option value="">Select a category</option>
  <?php foreach ($categories as $category): ?>
    <option value="<?= htmlspecialchars($category['id'], ENT_QUOTES, 'UTF-8'); ?>">
      <?= htmlspecialchars($category['categoryName'], ENT_QUOTES, 'UTF-8'); ?>
    </option>
  <?php endforeach; ?>
</select>

  <input type="submit" name='submit' value="Add">
</form>