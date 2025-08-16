<?php
$form_errors = [
  'e0x6' => 'The field must be filled in',
  'e0b7' => 'The authentication data was entered incorrectly',
  'e1c3' => 'The password contains cyrillic characters',
];

$field = $_GET['field'] ?? null;
$error = $_GET['error'] ?? null;
$text_errors = $form_errors[$error] ?? 'Unknown field error';
?>
<!doctype html>
<html lang="<?= get_options('lang') ?>">

<head>
  <?php require_template('html-head') ?>
  <link rel="stylesheet" href="/assets/css/form.css">
</head>

<body>
  <?php require_template('header') ?>

  <main>
    <div class="container">
      <h2 class="page_title">Register</h2>

      <form method="POST" action="/api/v1/auth/register?http_referer=auth%2Fregister">
        <table class="form-table">
          <tr class="form-row">
            <td class="form-label">Login<span class="form-label--req">*</span></td>

            <td class="form-input">
              <div class="form-input--ceil">
                <input
                  type="text"
                  name="user-login" class="form-control"
                  value="<?= $_GET['login'] ?? '' ?>"
                  required="">

                <?php if ($error && $field === 'login') : ?>
                  <small style="color: red;"><?= $text_errors ?></small>
                <?php endif; ?>
              </div>
            </td>
          </tr>

          <tr class="form-row">
            <td class="form-label">Email<span class="form-label--req">*</span></td>

            <td class="form-input">
              <div class="form-input--ceil">
                <input
                  type="email"
                  name="user-email" class="form-control"
                  value="<?= $_GET['email'] ?? '' ?>"
                  required="">

                <?php if ($error && $field === 'email') : ?>
                  <small style="color: red;"><?= $text_errors ?></small>
                <?php endif; ?>
              </div>
            </td>
          </tr>

          <tr class="form-row">
            <td class="form-label">Password<span class="form-label--req">*</span></td>
            <td class="form-input">
              <div class="form-input--ceil">
                <input
                  type="password"
                  name="user-password"
                  class="form-control"
                  autocomplete="new-password"
                  required="">

                <?php if ($error && $field === 'password') : ?>
                  <small style="color: red;"><?= $text_errors ?></small>
                <?php endif; ?>
              </div>
            </td>
          </tr>

          <tr class="form-row">
            <td class="form-label">Repeat password<span class="form-label--req">*</span></td>
            <td class="form-input">
              <div class="form-input--ceil">
                <input
                  type="password"
                  name="repeat-password"
                  class="form-control"
                  autocomplete="new-password"
                  required="">

                <?php if ($error && $field === 'repeat-password') : ?>
                  <small style="color: red;"><?= $text_errors ?></small>
                <?php endif; ?>
              </div>
            </td>
          </tr>
        </table>

        <input type="submit" class="btn-primary" value="SEND">
      </form>
    </div>
  </main>
</body>

</html>