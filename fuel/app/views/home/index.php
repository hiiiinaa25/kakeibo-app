<h1>ホーム</h1>

<p>
ようこそ、<?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?> さん
</p>
<?php if (!empty($last_login_at)): ?>
<p>前回ログイン: <?php echo htmlspecialchars($last_login_at, ENT_QUOTES, 'UTF-8'); ?></p>
<?php endif; ?>

<ul>
  <li><a href="<?php echo \Uri::create('transactions/create'); ?>">収支データ登録</a></li>
  <li><a href="<?php echo \Uri::create('transactions/index'); ?>">収支一覧</a></li>
  <li><a href="<?php echo \Uri::create('auth/logout'); ?>">ログアウト</a></li>
</ul>
