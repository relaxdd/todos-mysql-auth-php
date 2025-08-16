<?php

namespace Awenn2015\TestDemoTodos\Controllers;

class InitController {
  /**
   * @param int $status
   * @return void
   */
  public static function status(int $status) {
    http_response_code($status);
    exit;
  }

  /**
   * @param int $status
   * @param string $response
   * @return void
   */
  public static function send(int $status, string $response = ''): void {
    http_response_code($status);
    header('Content-Type: text/plain');
    echo trim($response);
    exit;
  }

  /**
   * @param int $status
   * @param array $response
   * @return void
   */
  public static function send_json(int $status, array $response = []): void {
    http_response_code($status);
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
  }
}
