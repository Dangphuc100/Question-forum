<form action="editjoke.php" method="post">
  <input type="hidden" name="id" value="<?= $joke['id']; ?>">
  <label for="joketext">Edit your joke:</label><br>
  <textarea id="joketext" name="joketext" rows="4" cols="50">
<?= htmlspecialchars($joke['joketext'], ENT_QUOTES, 'UTF-8'); ?></textarea><br>
  <input type="submit" value="Save">
</form>
