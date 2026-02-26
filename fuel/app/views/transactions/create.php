<h1>収支データ登録</h1>

<?php if (!empty($error)): ?>
  <p style="color:red;">
    <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
  </p>
<?php endif; ?>

<form method="post" action="/transactions/create">

  <div>
    <label>種類 *</label><br>
    <label><input type="radio" name="type" value="0" checked> 支出</label>
    <label><input type="radio" name="type" value="1"> 収入</label>
  </div>

  <div>
    <label>金額（円） *</label><br>
    <input type="number" name="amount" min="1" required>
  </div>

  <div>
    <label>カテゴリ *</label><br>
    <input type="text" name="category" maxlength="50" required>
  </div>

  <div>
    <label>日付 *</label><br>
    <input type="date" name="date" required>
  </div>

  <div>
    <label>メモ（任意）</label><br>
    <input type="text" name="memo" maxlength="255">
  </div>

  <button type="submit">登録</button>
</form>

<p><a href="/">ホームに戻る</a></p>