<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo htmlspecialchars((string) $title, ENT_QUOTES, 'UTF-8'); ?></title>
  <?php $colors = (array) \Config::get('ui_colors', array()); ?>
  <style>
    :root {
      --color-bg: <?php echo htmlspecialchars((string) \Arr::get($colors, 'bg', '#e8f3ff'), ENT_QUOTES, 'UTF-8'); ?>;
      --color-surface: <?php echo htmlspecialchars((string) \Arr::get($colors, 'surface', '#ffffff'), ENT_QUOTES, 'UTF-8'); ?>;
      --color-border: <?php echo htmlspecialchars((string) \Arr::get($colors, 'border', '#dbeafe'), ENT_QUOTES, 'UTF-8'); ?>;
      --color-text: <?php echo htmlspecialchars((string) \Arr::get($colors, 'text', '#0f172a'), ENT_QUOTES, 'UTF-8'); ?>;
      --color-link: <?php echo htmlspecialchars((string) \Arr::get($colors, 'link', '#1456a0'), ENT_QUOTES, 'UTF-8'); ?>;
      --color-link-hover: <?php echo htmlspecialchars((string) \Arr::get($colors, 'link_hover', '#0f3f78'), ENT_QUOTES, 'UTF-8'); ?>;
      --color-muted-border: <?php echo htmlspecialchars((string) \Arr::get($colors, 'muted_border', '#cbd5e1'), ENT_QUOTES, 'UTF-8'); ?>;
      --color-muted-bg: <?php echo htmlspecialchars((string) \Arr::get($colors, 'muted_bg', '#f1f5f9'), ENT_QUOTES, 'UTF-8'); ?>;
      --color-input-bg: <?php echo htmlspecialchars((string) \Arr::get($colors, 'input_bg', '#e3e3e3'), ENT_QUOTES, 'UTF-8'); ?>;
      --color-input-border: <?php echo htmlspecialchars((string) \Arr::get($colors, 'input_border', '#94a3b8'), ENT_QUOTES, 'UTF-8'); ?>;
      --color-button-bg: <?php echo htmlspecialchars((string) \Arr::get($colors, 'button_bg', '#0f172a'), ENT_QUOTES, 'UTF-8'); ?>;
      --color-button-text: <?php echo htmlspecialchars((string) \Arr::get($colors, 'button_text', '#ffffff'), ENT_QUOTES, 'UTF-8'); ?>;
      --color-button-hover: <?php echo htmlspecialchars((string) \Arr::get($colors, 'button_hover', '#111827'), ENT_QUOTES, 'UTF-8'); ?>;
      --color-danger: <?php echo htmlspecialchars((string) \Arr::get($colors, 'danger', '#dc2626'), ENT_QUOTES, 'UTF-8'); ?>;
      --color-warning: <?php echo htmlspecialchars((string) \Arr::get($colors, 'warning', '#b45309'), ENT_QUOTES, 'UTF-8'); ?>;
      --color-income-text: <?php echo htmlspecialchars((string) \Arr::get($colors, 'income_text', '#1d4ed8'), ENT_QUOTES, 'UTF-8'); ?>;
      --color-income-bg: <?php echo htmlspecialchars((string) \Arr::get($colors, 'income_bg', '#dbeafe'), ENT_QUOTES, 'UTF-8'); ?>;
      --color-expense-text: <?php echo htmlspecialchars((string) \Arr::get($colors, 'expense_text', '#b91c1c'), ENT_QUOTES, 'UTF-8'); ?>;
      --color-expense-bg: <?php echo htmlspecialchars((string) \Arr::get($colors, 'expense_bg', '#fee2e2'), ENT_QUOTES, 'UTF-8'); ?>;
      --color-balance-text: <?php echo htmlspecialchars((string) \Arr::get($colors, 'balance_text', '#166534'), ENT_QUOTES, 'UTF-8'); ?>;
      --color-balance-bg: <?php echo htmlspecialchars((string) \Arr::get($colors, 'balance_bg', '#dcfce7'), ENT_QUOTES, 'UTF-8'); ?>;
    }
  </style>
  <?php foreach ((array) $styles as $style): ?>
    <?php
      $name = trim((string) $style);
      if ($name === '')
      {
          continue;
      }
      $path = 'assets/css/' . $name . '.css';
      $href = '/' . $path;
      if (is_file(DOCROOT . $path))
      {
          $href .= '?v=' . filemtime(DOCROOT . $path);
      }
    ?>
    <link rel="stylesheet" href="<?php echo htmlspecialchars($href, ENT_QUOTES, 'UTF-8'); ?>">
  <?php endforeach; ?>
</head>
<body>
<?php echo $content; ?>
</body>
</html>
