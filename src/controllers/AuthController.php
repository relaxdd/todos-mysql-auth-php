<?php

namespace Awenn2015\TestDemoTodos\Controllers;

use Awenn2015\TestDemoTodos\Services\JwtService;

class AuthController extends InitController {
  public static function login() {
    $url = '/auth/login';
    $http_referer = ($_GET['http_referer'] ?? '') === ltrim($url, '/');
    $check_required = self::check_required(['user-login', 'user-password'], 'POST');

    $login = $_POST['user-login'] ?? null;
    $password = $_POST['user-password'] ?? null;

    if ($check_required !== true) {
      if (!$http_referer)
        self::send_json(400, ['error' => 'Required properties are missing in the request body']);
      else {
        switch ($check_required) {
          case 'user-login':
            redirect($url, ['error' => 'e0x6', 'field' => 'login']);
          case 'user-password':
            redirect($url, ['error' => 'e0x6', 'field' => 'password', 'login' => $login]);
        }
      }
    }

    /*
     * ================================
     */

    if (preg_match('/[А-яЁё]/', $password)) {
      if ($http_referer) {
        redirect($url, ['error' => 'e1c3', 'field' => 'password', 'login' => $login]);
      } else {
        $message = 'Invalid authorization data, the password must not contain Cyrillic characters.';
        self::send_json(400, ['error' => $message]);
      }
    }

    /*
     * ================================
     */

    global $mysql_pdo;

    $statement = $mysql_pdo->prepare('SELECT * FROM users WHERE login = ? LIMIT 1');
    $statement->execute([$login]);
    $result = $statement->fetch(\PDO::FETCH_ASSOC);

    if (!$result || !password_verify($password, $result['password'])) {
      if ($http_referer) {
        redirect($url, ['error' => 'e0b7', 'field' => 'password', 'login' => $login]);
      } else {
        self::send_json(400, ['error' => 'Incorrect authorization data']);
      }
    }

    $time = time();
    $expires = $time + (86400 * 7);

    $jwt_token = JwtService::generate([
      'iat' => $time,
      'exp' => $expires,
      'login' => $login,
      'user_id' => $result['id'],
    ]);

    $options = [
      'expires' => $expires,
      'path' => '/',
      'secure' => true,
      'httponly' => true,
      'samesite' => 'Lax',
    ];

    setcookie('auth_token', $jwt_token, $options);

    if ($http_referer)
      redirect('/');
    else {
      self::send_json(200, ['success' => true]);
    }
  }

  public static function register() {
    $url = '/auth/register';
    $http_referer = ($_GET['http_referer'] ?? '') === ltrim($url, '/');
    $check_required = self::check_required(['user-login', 'user-email', 'user-password', 'repeat-password'], 'POST');

    $login = $_POST['user-login'] ?? null;
    $email = $_POST['user-email'] ?? null;
    $password = $_POST['user-password'] ?? null;
    $repeat_password = $_POST['repeat-password'] ?? null;

    if ($check_required !== true) {
      if (!$http_referer)
        self::send_json(400, ['error' => 'Required properties are missing in the request body']);
      else {
        switch ($check_required) {
          case 'user-login':
            redirect($url, ['error' => 'e0x6', 'field' => 'login']);
          case 'user-email':
            redirect($url, ['error' => 'e0x6', 'field' => 'email', 'login' => $login]);
          case 'user-password':
            redirect($url, ['error' => 'e0x6', 'field' => 'password', 'login' => $login, 'email' => $email]);
          case 'repeat-password':
            redirect($url, ['error' => 'e0x6', 'field' => 'repeat-password', 'login' => $login, 'email' => $email]);
        }
      }
    }

    /*
     * ================================
     */

    $login = trim($login, " \n\r\t\v\0-_");
    if (!preg_match('/^[\da-zA-Z_-]+$/', $login)) {
      if ($http_referer) {
        redirect($url, ['error' => 'y2n5', 'field' => 'login']);
      } else {
        self::send_json(400, ['error' => 'Incorrect authorization data']);
      }
    }

    $email = trim($email);
    if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
      if ($http_referer) {
        redirect($url, ['error' => 'm7d2', 'field' => 'email']);
      } else {
        self::send_json(400, ['error' => 'Incorrect authorization data']);
      }
    }

    // TODO: Password
    // TODO: Repeat password

    /*
     * ================================
     */

    self::send_json(500, ['error' => 'Not Implemented!']);
  }
}
