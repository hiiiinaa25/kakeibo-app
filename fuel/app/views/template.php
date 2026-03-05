<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo htmlspecialchars((string) $title, ENT_QUOTES, 'UTF-8'); ?></title>
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
