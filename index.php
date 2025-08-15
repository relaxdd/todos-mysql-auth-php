<?php

use Awenn2015\TestDemoTodos\Services\JwtService;

const ABS_PATH = __DIR__;
require_once ABS_PATH . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(ABS_PATH);
$dotenv->load();

$mysql_pdo = db_connect();
$site_pages = db_query($mysql_pdo, "SELECT * FROM pages", true);
$load_options = autoload_options($mysql_pdo);

$redirect_rules = unserialize($load_options['redirect_rules']);
$request_uri = explode('?', $_SERVER['REQUEST_URI'])[0] ?? '';
$request_uri = $request_uri !== '/' ? rtrim($request_uri, '/') : '/';
$current_page = array_find($site_pages, fn(array $it) => $it['uri'] === $request_uri);

if (array_key_exists($request_uri, $redirect_rules)) {
  header('Location: /' . $redirect_rules[$request_uri]);
  exit;
}

$jwt_token = $_COOKIE['auth_token'] ?? '';
$jwt_payload = JwtService::decode($jwt_token);
$is_not_valid_token = !$jwt_token || $jwt_payload === false;

define('IsAuthorized', !$is_not_valid_token);

if ($is_not_valid_token) {
  unset($_COOKIE['auth_token']);
  setcookie('auth_token', '', time() - 3600, '/');
}

if ($current_page === null) {
  http_response_code(404);

  if (!str_contains($request_uri, '/api'))
    require ABS_PATH . '/pages/errors/404.php';
  else {
    header('Content-Type: application/json');
    echo json_encode(['error' => '404 Not Found']);
  }

  exit;
}

if ($current_page['private'] && !IsAuthorized) {
  $current_page = null;
  http_response_code(401);

  if (!str_contains($request_uri, '/api'))
    require ABS_PATH . '/pages/errors/401.php';
  else {
    header('Content-Type: application/json');
    echo json_encode(['error' => '401 Unauthorized']);
  }

  exit;
}

require ABS_PATH . '/pages/' . $current_page['file'] . '.php';