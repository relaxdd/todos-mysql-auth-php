<?php

function db_connect(): PDO {
  $host = $_ENV['MYSQL_HOST'] ?? '';
  $user = $_ENV['MYSQL_USER'] ?? '';
  $password = $_ENV['MYSQL_PASSWORD'] ?? '';
  $database = $_ENV['MYSQL_DATABASE'] ?? '';

  return new PDO("mysql:host=$host;dbname=$database", $user, $password);
}

function db_query(PDO $pdo, string $query, bool $many = false): mixed {
  $query = $pdo->query($query);

  return $many
    ? $query->fetchAll(PDO::FETCH_ASSOC)
    : $query->fetch(PDO::FETCH_ASSOC);
}
