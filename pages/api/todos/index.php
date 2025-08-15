<?php

$method = $_SERVER['REQUEST_METHOD'];
$handler = __DIR__ . '\\' . strtolower($method) . '.php';

if (!file_exists($handler)) {
  header('Content-Type: application/json');
  http_response_code(400);
  echo json_encode(['error' => 'Method not allowed']);
  exit;
}

require_once $handler;