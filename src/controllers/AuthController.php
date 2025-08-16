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
        self::send_json(400, ['error' => 'В теле запроса отсутствуют обязательные свойства']);
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
        $message = 'Неверные данные для авторизации, пароль не должен содержать символов кириллицы.';
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
        self::send_json(400, ['error' => 'Неверные данные для авторизации']);
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
        self::send_json(400, ['error' => 'В теле запроса отсутствуют обязательные свойства']);
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

    $email = trim($email);
    $password = trim($password);
    $login = trim($login, " \n\r\t\v\0-_");
    $repeat_password = trim($repeat_password);

    if (!preg_match('/^[\da-zA-Z_-]{4,191}$/', $login)) {
      if ($http_referer) {
        redirect($url, ['error' => 'y2n5', 'field' => 'login']);
      } else {
        self::send_json(400, ['error' => 'Неверные данные для авторизации']);
      }
    }

    if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
      if ($http_referer) {
        redirect($url, ['error' => 'm7d2', 'field' => 'email', 'login' => $login]);
      } else {
        self::send_json(400, ['error' => 'Неверные данные для авторизации']);
      }
    }

    if ($password !== $repeat_password) {
      if (!$http_referer)
        self::send_json(400, ['error' => "Пароли не совпадают"]);
      else {
        redirect($url, [
          'error' => "Пароли не совпадают",
          'field' => 'repeat-password',
          'login' => $login,
          'email' => $email,
        ]);
      }
    }

    $validate_password = self::validate_password($password, $repeat_password);

    if ($validate_password !== true) {
      if (!$http_referer)
        self::send_json(400, ['error' => $validate_password]);
      else {
        redirect($url, [
          'error' => $validate_password,
          'field' => 'password',
          'login' => $login,
          'email' => $email,
        ]);
      }
    }

    /*
     * ================================
     */

    self::send_json(500, ['error' => 'Not Implemented!']);
  }

  /*
   * ===========================================
   *             Private methods
   * ===========================================
   */

  /**
   * @param string $password
   * @param string $repeat_password
   * @return true|string
   */
  private static function validate_password(string $password, string $repeat_password): true|string {
    if (strlen($password) < 8) {
      return "Пароль должен содержать минимум 8 символов";
    }

    if (!preg_match('/[0-9]/', $password)) {
      return "Пароль должен содержать хотя бы одну цифру";
    }

    if (!preg_match('/[a-z]/', $password)) {
      return "Пароль должен содержать хотя бы одну строчную букву";
    }

    if (!preg_match('/[A-Z]/', $password)) {
      return "Пароль должен содержать хотя бы одну заглавную букву";
    }

    if (!preg_match('/[^a-zA-Z0-9]/', $password)) {
      return "Пароль должен содержать хотя бы один специальный символ";
    }

    return true;
  }
}
