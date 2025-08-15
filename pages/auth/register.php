<!doctype html>
<html lang="<?= get_options('lang') ?>">

<head>
  <?php require_template('html-head') ?>
</head>

<body>
  <?php require_template('header') ?>

  <main>
    <div class="container">
      <h2 class="page_title">Sign Up</h2>

      <form action="/api/v1/auth/register?http_referer=auth%2Fregister">

      </form>
    </div>
  </main>
</body>

</html>