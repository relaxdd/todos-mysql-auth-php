<!doctype html>
<html lang="<?= get_options('lang') ?>">
<head>
  <?php require_template('html-head', ['error' => '401']) ?>
</head>
<body>
<?php require_template('header') ?>

<main>
  <div class="container">
    <p>Что бы получить доступ к этой странице вам нужно авторизоваться</p>
    <p>Перейти на страницу <a href="/auth/login">Аутентификации</a></p>
  </div>
</main>
</body>
</html>