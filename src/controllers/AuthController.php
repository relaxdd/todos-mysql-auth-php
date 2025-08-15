<?php

namespace Awenn2015\TestDemoTodos\Controllers;

use Awenn2015\TestDemoTodos\Services\JwtService;

class AuthController {
  public static function login() {
    $http_referer = isset($_GET['http_referer']) && $_GET['http_referer'] === 'auth/login';
    $login = $_POST['user-login'] ?? null;
    $password = $_POST['user-password'] ?? null;

    if (!$login) {
      if ($http_referer) {
        $url = '/auth/login';
        redirect($url, ['error' => 'e0x6', 'field' => 'login']);
      } else {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid authorization data']);
        exit;
      }
    }

    if (!$password) {
      if ($http_referer) {
        $url = '/auth/login';
        redirect($url, ['error' => 'e0x6', 'field' => 'password', 'login' => $login]);
      } else {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid authorization data']);
        exit;
      }
    }

    if (preg_match('/[А-яЁё]/', $password)) {
      if ($http_referer) {
        $url = '/auth/login';
        redirect($url, ['error' => 'e1c3', 'field' => 'password', 'login' => $login]);
      } else {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid authorization data']);
        exit;
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
      header("Location: /auth/login?error=e0b7&field=password&login=$login");
      exit;
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
      http_response_code(200);
      header('Content-Type: application/json');
      echo json_encode(['success' => true]);
      exit;
    }
  }

  public static function register() {
    
  }
}
