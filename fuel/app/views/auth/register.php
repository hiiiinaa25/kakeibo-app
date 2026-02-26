<h1>新規会員登録</h1>

<form method="post" action="/auth/register">
  <div>
    <label>ユーザー名 *</label><br>
    <input type="text" name="username" required>
  </div>

  <div>
    <label>メールアドレス *</label><br>
    <input type="email" name="email" required>
  </div>

  <div>
    <label>パスワード *</label><br>
    <input type="password" name="password" minlength="8" required>
  </div>

  <div>
    <label>パスワード(確認) *</label><br>
    <input type="password" name="password_confirm" minlength="8" required>
  </div>

  <button type="submit">登録</button>
</form>

<p>すでにアカウントをお持ちの方はこちら<a href="/auth/login">ログイン</a></p>
  