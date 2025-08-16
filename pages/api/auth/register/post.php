<?php

use Awenn2015\TestDemoTodos\Controllers\AuthController;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  AuthController::send_json(400, ['error' => 'Invalid request method']);
}

AuthController::register();
AuthController::status(200);