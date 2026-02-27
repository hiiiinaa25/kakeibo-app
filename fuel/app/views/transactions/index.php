<h1>収支一覧</h1>

<p><a href="<?php echo \Uri::create('transactions/create'); ?>">収支登録</a></p>
<?php if (empty($can_soft_delete)): ?>
  <p style="color:#b45309;">論理削除を使うには transactions.deleted_at カラムの追加が必要です。</p>
<?php endif; ?>
<table border="1" cellpadding="6" cellspacing="0" style="margin-bottom:12px;">
  <tr>
    <th>収入合計</th>
    <th>支出合計</th>
    <th>残高</th>
  </tr>
  <tr>
    <td><?php echo number_format((int) $income_total); ?> 円</td>
    <td><?php echo number_format((int) $expense_total); ?> 円</td>
    <td><?php echo number_format((int) $balance); ?> 円</td>
  </tr>
</table>

<?php if (empty($transactions)): ?>
  <p>データがありません。</p>
<?php else: ?>
  <div id="transactions-list">
  <table border="1" cellpadding="6" cellspacing="0">
    <thead>
      <tr>
        <th>ID</th>
        <th>種別</th>
        <th>金額</th>
        <th>カテゴリ</th>
        <th>日付</th>
        <th>メモ</th>
        <th>登録日時</th>
        <th>操作</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($transactions as $row): ?>
        <tr id="transaction-row-<?php echo (int) $row['id']; ?>">
          <td><?php echo (int) $row['id']; ?></td>
          <td><?php echo htmlspecialchars(\Model_Transaction::label_for_type($row['type']), ENT_QUOTES, 'UTF-8'); ?></td>
          <td><?php echo number_format((int) $row['amount']); ?> 円</td>
          <td><?php echo htmlspecialchars($row['category'], ENT_QUOTES, 'UTF-8'); ?></td>
          <td><?php echo htmlspecialchars(isset($row['date']) ? $row['date'] : (isset($row['created_at']) ? substr($row['created_at'], 0, 10) : ''), ENT_QUOTES, 'UTF-8'); ?></td>
          <td><?php echo htmlspecialchars(isset($row['memo']) ? (string) $row['memo'] : '', ENT_QUOTES, 'UTF-8'); ?></td>
          <td><?php echo htmlspecialchars(isset($row['created_at']) ? $row['created_at'] : '', ENT_QUOTES, 'UTF-8'); ?></td>
          <td>
            <a href="<?php echo \Uri::create('transactions/edit/' . (int) $row['id']); ?>">編集</a>
            <?php if (!empty($can_soft_delete)): ?>
              &nbsp;
              <button type="button" data-transaction-id="<?php echo (int) $row['id']; ?>" data-bind="click: deleteTransaction">削除</button>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  </div>

  <?php if (!empty($can_soft_delete)): ?>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/knockout/3.5.1/knockout-min.js"></script>
  <script>
  (function () {
    function requestDelete(button) {
      var id = button.getAttribute('data-transaction-id');
      if (!id) {
        return;
      }

      if (!window.confirm('この収支データを削除しますか？')) {
        return;
      }

      var xhr = new XMLHttpRequest();
      xhr.open('POST', '<?php echo \Uri::create('transactions/delete'); ?>/' + encodeURIComponent(id), true);
      xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
      xhr.onreadystatechange = function () {
        if (xhr.readyState !== 4) {
          return;
        }

        var message = '削除に失敗しました。';
        try {
          var result = JSON.parse(xhr.responseText || '{}');
          if (result.message) {
            message = result.message;
          }
        } catch (e) {}

        if (xhr.status >= 200 && xhr.status < 300) {
          var row = document.getElementById('transaction-row-' + id);
          if (row && row.parentNode) {
            row.parentNode.removeChild(row);
          }
          return;
        }

        alert(message);
      };
      xhr.send('');
    }

    function ViewModel() {}
    ViewModel.prototype.deleteTransaction = function (_, event) {
      requestDelete(event.currentTarget);
    };

    var root = document.getElementById('transactions-list');
    if (!root) {
      return;
    }

    if (window.ko && typeof window.ko.applyBindings === 'function') {
      window.ko.applyBindings(new ViewModel(), root);
      return;
    }

    var buttons = root.querySelectorAll('button[data-transaction-id]');
    for (var i = 0; i < buttons.length; i++) {
      buttons[i].addEventListener('click', function (event) {
        requestDelete(event.currentTarget);
      });
    }
  })();
  </script>
  <?php endif; ?>
<?php endif; ?>

<p><a href="<?php echo \Uri::create('/'); ?>">ホームへ戻る</a></p>
