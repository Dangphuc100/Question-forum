<div class="content">
  <p><?= $totalJokes ?> jokes have been submitted to the Internet Joke Database.</p>

 <table class="joke-table">
  <tr>
    <th>Image</th>
    <th>Joke Text</th>
    <th>Joke Category</th>
    <th>Author</th>
  </tr>

  <?php foreach ($jokes as $joke): ?>
    <tr>
      <td class="joke-image">
        <?php if ($joke['categoryName'] == 'meo meo'): ?>
          <img src="image/meocry.jpg" alt="meomeo">
        <?php else: ?>
          <img src="image/okay.jpg" alt="okey">
        <?php endif; ?>
      </td>

      <td class="joke-text">
        <strong>Joke Text:</strong>
        <?= htmlspecialchars($joke['joketext'], ENT_QUOTES, 'UTF-8') ?>
      </td>

      <td class="joke-category">
        <strong>Joke Category:</strong>
        <?= htmlspecialchars($joke['categoryName'], ENT_QUOTES, 'UTF-8') ?>
      </td>

      <td class="joke-author">
        (by <?= htmlspecialchars($joke['authorname'], ENT_QUOTES, 'UTF-8') ?>)
        <a href="editjoke.php?id=<?= $joke['id'] ?>">Edit</a>
      </td>

      <td class="joke-delete">
        <form action="deletejoke.php" method="post">
          <input type="hidden" name="id" value="<?= $joke['id'] ?>">
          <input type="submit" value="Delete">
        </form>
      </td>
    </tr>
  <?php endforeach; ?>
</table>
</div>
