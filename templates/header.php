<?php
$is_login_page = get_current_page()?->file === 'auth/login';
?>
<header class="header">
  <div class="container">
    <div class="header_shell">
      <h1 class="header_brand">MyTodoList</h1>

      <nav class="header_shell">
        <ul class="header_shell__list">
          <li class="header_shell__item">
            <a class="header_shell__link" href="/">Todos</a>
          </li>

          <li class="header_shell__item">
            <a class="header_shell__link" href="/about">About me</a>
          </li>

          <?php if (IsAuthorized) : ?>
            <li class="header_shell__item">
              <a class="header_shell__link" href="/auth/logout">Logout</a>
            </li>
          <?php elseif ($is_login_page) : ?>
            <li class="header_shell__item">
              <a class="header_shell__link" href="/auth/register">Register</a>
            </li>
          <?php else : ?>
            <li class="header_shell__item">
              <a class="header_shell__link" href="/auth/login">Login</a>
            </li>
          <?php endif; ?>
        </ul>
      </nav>
    </div>
  </div>
</header>