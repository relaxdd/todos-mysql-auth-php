<!DOCTYPE html>
<html lang="<?= get_options('lang') ?>">

<head>
  <?php require_template('html-head') ?>
</head>

<body>
  <?php require_template('header') ?>

  <main>
    <div class="container">
      <h2 class="page_title"><?= get_page_title(); ?></h2>
      <div><?= get_page_content(); ?></div>
    </div>
  </main>
</body>

</html>