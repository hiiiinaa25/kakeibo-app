<link rel="stylesheet" href="/assets/css/common.css">
<link rel="stylesheet" href="/assets/css/auth.css">

<div class="auth-card">
<h1 class="auth-title">新規会員登録</h1>

<form method="post" action="/auth/register">
  <div class="field">
    <label>お名前 <span class="required">*</span></label><br>
    <input type="text" name="username" required>
  </div>

  <div class="field">
    <label>メールアドレス <span class="required">*</span></label><br>
    <input type="email" name="email" required>
  </div>

  <div class="field">
    <label>パスワード <span class="required">*</span></label><br>
    <input type="password" name="password" minlength="8" required>
  </div>

  <div class="field">
    <label>パスワード(確認) <span class="required">*</span></label><br>
    <input type="password" name="password_confirm" minlength="8" required>
  </div>

  <button type="submit" class="primary-btn">登録</button>
</form>

<p class="auth-footer">すでにアカウントお持ちもの場合はこちら<a href="/auth/login">ログイン</a></p>
</div>
  
