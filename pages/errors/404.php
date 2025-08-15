<!doctype html>
<html lang="<?= get_options('lang') ?>">
<head>
  <?php require_template('html-head', ['error' => '404']) ?>
</head>
<body>
<?php require_template('header') ?>

<main>
<div class="container">
  <p>Такой страницы больше нет на сайте :(</p>
  <p>Перейти на <a href="/">Главную</a></p>
</div>
</main>
</body>
</html>