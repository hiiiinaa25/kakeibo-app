<h1>ホーム</h1>

<p>
ようこそ、<?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?> さん
</p>

<ul>
  <li><a href="/transactions/create">収支データ登録</a></li>
  <li><a href="/transactions">収支一覧</a></li>
  <li><a href="/auth/logout">ログアウト</a></li>
</ul>