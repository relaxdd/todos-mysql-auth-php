<?php

namespace Awenn2015\TestDemoTodos\Services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtService {
  private const string SecretKey = 'IpHbRsS$Lus~wb4cUrWgsJy5I|fRDi}M';

  public static function generate(array $payload): string {
    $initial = ['iss' => 'https://learn-php.local'];
    return JWT::encode(array_merge($initial, $payload), self::SecretKey, 'HS256');
  }

  public static function decode(string $jwt): array|false {
    try {
      return (array)JWT::decode($jwt, new Key(self::SecretKey, 'HS256'));
    } catch (\Exception $e) {
      return false;
    }
  }
}