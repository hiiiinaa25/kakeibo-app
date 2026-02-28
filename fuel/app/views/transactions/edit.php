<link rel="stylesheet" href="/assets/css/common.css">
<link rel="stylesheet" href="/assets/css/transactions.css">

<h1>収支データ編集</h1>

<?php if (!empty($error)): ?>
  <p style="color:red;">
    <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
  </p>
<?php endif; ?>

<form method="post" action="<?php echo \Uri::create('transactions/update/' . (int) $id); ?>">
  <?php echo \Form::csrf(); ?>
  <div>
    <label>種別 *</label><br>
    <label><input type="radio" name="type" value="0" <?php echo ((int) $form['type'] === 0) ? 'checked' : ''; ?>> 支出</label>
    <label><input type="radio" name="type" value="1" <?php echo ((int) $form['type'] === 1) ? 'checked' : ''; ?>> 収入</label>
  </div>

  <div>
    <label>金額（円）*</label><br>
    <input type="number" name="amount" min="1" required value="<?php echo htmlspecialchars((string) $form['amount'], ENT_QUOTES, 'UTF-8'); ?>">
  </div>

  <div>
    <label>カテゴリ *</label><br>
    <input type="text" name="category" maxlength="50" required value="<?php echo htmlspecialchars((string) $form['category'], ENT_QUOTES, 'UTF-8'); ?>">
  </div>

  <div>
    <label>日付 *</label><br>
    <input type="date" name="date" required value="<?php echo htmlspecialchars((string) $form['date'], ENT_QUOTES, 'UTF-8'); ?>">
  </div>

  <div>
    <label>メモ（任意）</label><br>
    <input type="text" name="memo" maxlength="255" value="<?php echo htmlspecialchars((string) $form['memo'], ENT_QUOTES, 'UTF-8'); ?>">
  </div>

  <button type="submit">更新</button>
</form>

<p><a href="<?php echo \Uri::create('transactions'); ?>">一覧へ戻る</a></p>
