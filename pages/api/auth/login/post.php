<?php

use Awenn2015\TestDemoTodos\Controllers\AuthController;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(400);
  header('Content-Type: application/json');
  echo json_encode(['error' => 'Invalid request method']);
  exit;
}

AuthController::login();