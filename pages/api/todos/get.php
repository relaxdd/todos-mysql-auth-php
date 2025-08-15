<?php

use Awenn2015\TestDemoTodos\Models\TodoModel;

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
  http_response_code(400);
  echo json_encode(['error' => 'Invalid request method']);
  exit;
}

global $mysql_pdo;
global $jwt_payload;

$user_id = $jwt_payload['user_id'] ?? 0;
$todo_model = new TodoModel($mysql_pdo);
$todos = $todo_model->loadAll($user_id);

die(json_encode($todos));