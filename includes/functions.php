<?php

use Awenn2015\TestDemoTodos\Data\CurrentPage;

if (!function_exists('var_print')) {
  function var_print(mixed $var): void {
    echo '<pre>' . var_export($var, true) . '</pre>';
  }
}

function require_template(string $template, array $args = []): void {
  require ABS_PATH . "/templates/$template.php";
}

if (!function_exists('array_find')) {
  function array_find(array $array, callable $callback) {
    foreach ($array as $key => $value) {
      if ($callback($value, $key, $array) === true) {
        return $value;
      }
    }

    return null;
  }
}

/**
 * @param PDO $pdo
 * @return array
 */
function autoload_options(PDO $pdo): array {
  $result = db_query($pdo, 'SELECT `name`, `value` FROM `options` WHERE `autoload` = 1', true);
  $temp = [];

  foreach ($result as $row) {
    $temp[$row['name']] = $row['value'];
  }

  return $temp;
}

/**
 * @param string $name
 * @return string|null
 */
function get_options(string $name): string|null {
  global $load_options;
  return $load_options[$name] ?? null;
}

/**
 * @param null|string $error
 * @return string
 */
function get_page_title(?string $error = null): string {
  $errors = [
    '401' => '401 - Not Authorized',
    '404' => '404 - Not Found',
  ];

  if (array_key_exists($error, $errors)) {
    return $errors[$error];
  }

  global $load_options;
  global $current_page;

  return $load_options['site_title'] . ' – ' . $current_page['title'];
}

/**
 * @return CurrentPage
 */
function get_current_page(): CurrentPage|null {
  global $current_page;
  return CurrentPage::init($current_page);
}

/**
 * @param string $to
 * @param array $query
 */
function redirect(string $to, array $query = []) {
  header('Location: ' . $to . (!empty($query) ? '?' . http_build_query($query) : ''));
  exit;
}
