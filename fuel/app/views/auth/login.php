<h1>ログイン</h1>

<!-- エラーがあれば表示 -->
<?php if (!empty($error)): ?>
<p style="color:red;">
<?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
</p>
<?php endif; ?>
 
<form method="post" action="/auth/login">

  <!-- メールアドレス -->
  <div>
    <label>メールアドレス *</label><br>
    <input type="email" name="email" required>
  </div>
  <!-- パスワード -->
    <div>
    <label>パスワード *</label><br>
    <input type="password" name="password" required>
  </div>
  <!-- 送信/ -->
  <button type="submit">ログイン</button>
</form>

<p>アカウントお持ちでない場合はこちら<a href="/auth/register">新規会員登録</a></p>