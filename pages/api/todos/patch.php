<?php

use Awenn2015\TestDemoTodos\Models\TodoModel;

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'PATCH') {
  http_response_code(400);
  die(json_encode([
    'error' => 'Invalid request method',
    'message' => 'Request method must be PATCH',
  ]));
}

$content_type = getallheaders()['Content-Type'] ?? '';

if (!str_contains($content_type, 'application/json')) {
  http_response_code(400);
  die(json_encode([
    'error' => 'Invalid request body',
    'message' => 'Body type must be application/json',
  ]));
}

/** @var array|null $request */
$request = null;

try {
  $request = json_decode(file_get_contents('php://input'), true);
} catch (Exception $e) {
  http_response_code(400);

  die(json_encode([
    'error' => 'Invalid request body',
    'message' => 'Body data is invalid json',
  ]));
}

$id = $request['id'] ?? null;
$completed = $request['completed'] ?? null;

if (!$id || !is_int($id) || !is_int($completed)) {
  http_response_code(400);

  die(json_encode([
    'error' => 'Invalid request',
    'message' => 'Required data is missing or their type is invalid',
  ]));
}

global $mysql_pdo;
global $jwt_payload;

$todo_model = new TodoModel($mysql_pdo);
$find = $todo_model->load($id, $jwt_payload['user_id'] ?? 0);

if (!$find) {
  http_response_code(400);

  die(json_encode([
    'error' => 'Invalid request',
    'message' => 'There is no record with this ID or you are not the owner of it',
  ]));
}

$result = $todo_model->update($id, !$completed);

if (!$result) {
  http_response_code(500);

  die(json_encode([
    'error' => 'Failed to update todo record',
  ]));
}

die(json_encode([
  'message' => 'Todo record has been successfully updated',
]));