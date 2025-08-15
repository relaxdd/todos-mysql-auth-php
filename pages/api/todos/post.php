<?php

use Awenn2015\TestDemoTodos\Models\TodoModel;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Content-Type: application/json');
  http_response_code(400);
  echo json_encode(['error' => 'Invalid request method']);
  exit;
}

/** @var string $return_type */
$return_type = $_POST['return-type'] ?? 'json';
/** @var string|null $title */
$title = $_POST['title'] ?? null;

if (!$title) {
  switch ($return_type) {
    case 'json':
      header('Content-Type: application/json');
      http_response_code(400);
      echo json_encode(['error' => 'Missing title for a new todo record']);
      break;
    case 'html':
      header('Location: /?error=c3q1&field=title');
      break;
  }

  exit;
}

global $mysql_pdo;
global $jwt_payload;

$user_id = $jwt_payload['user_id'] ?? 0;
$todo_model = new TodoModel($mysql_pdo);
$result = $todo_model->create($user_id, $title);

if ($result === false) {
  switch ($return_type) {
    case 'json':
      header('Content-Type: application/json');
      http_response_code(500);
      echo json_encode(['error' => 'Failed to create a new todo record']);
      break;
    case 'html':
      header('Location: /?error=x1n4&field=title');
      break;
  }

  exit;
}

switch ($return_type) {
  case 'json':
    header('Content-Type: application/json');
    http_response_code(201);
    echo json_encode(['result' => $result]);
    break;
  case 'html':
    header('Location: /?success=true');
    break;
}