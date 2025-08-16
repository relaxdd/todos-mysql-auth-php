<?php

namespace Awenn2015\TestDemoTodos\Controllers;

use Error;

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

  /**
   * Checks the request body/query for required properties.
   * 
   * @param array $properties Array properties that need to be checked for existence
   * @param string|null $method HTTP method, only "GET" and "POST" values are allowed
   * @return string|true Returns "true" upon successful verification, otherwise the name of the missing property
   */
  public static function check_required(array $properties, string|null $method = null): string|true {
    $allowed = ['GET', 'POST'];
    $method = mb_strtoupper($method);

    if (!in_array($method, $allowed)) {
      throw new Error('The $method argument is invalid, only "GET" and "POST" values are allowed.');
    }

    $request = null;
    switch ($method) {
      case null:
        $request = &$_REQUEST;
      case 'GET':
        $request = &$_GET;
      case 'POST':
        $request = &$_POST;
    }

    foreach ($properties as $property) {
      if (!array_key_exists($property, $request) || empty(trim($request[$property]))) {
        return $property;
      }
    }

    return true;
  }
}
