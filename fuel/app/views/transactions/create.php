<link rel="stylesheet" href="/assets/css/common.css?v=<?php echo filemtime(DOCROOT . 'assets/css/common.css'); ?>">
<link rel="stylesheet" href="/assets/css/transactions.css?v=<?php echo filemtime(DOCROOT . 'assets/css/transactions.css'); ?>">

<div class="page-card tx-create-card">
<h1 class="tx-create-title">収支データ登録</h1>

<?php if (!empty($error)): ?>
  <p style="color:red;">
    <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
  </p>
<?php endif; ?>

<form method="post" action="<?php echo \Uri::create('transactions/create'); ?>" class="tx-create-form">
  <?php echo \Form::csrf(); ?>
  <div class="tx-field">
    <div class="tx-type-group">
      <label class="tx-type-option">
        <input type="radio" name="type" value="0" checked>
        <span>支出</span>
      </label>
      <label class="tx-type-option">
        <input type="radio" name="type" value="1">
        <span>収入</span>
      </label>
    </div>
  </div>

  <div class="tx-field">
    <label>金額</label><br>
    <input type="number" name="amount" min="1" required>
  </div>

  <div class="tx-field">
    <label>カテゴリ <span class="required">*</span></label><br>
    <input type="text" name="category" maxlength="50" required>
  </div>

  <div class="tx-field">
    <label>日付 <span class="required">*</span></label><br>
    <input type="date" name="date" required>
  </div>

  <div class="tx-field">
    <label>メモ（任意）</label><br>
    <input type="text" name="memo" maxlength="255">
  </div>

  <button type="submit" class="tx-submit-btn">登録(一覧へ)</button>
</form>
</div>
