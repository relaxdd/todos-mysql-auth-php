<?php

namespace Awenn2015\TestDemoTodos\Models;

use PDO;

readonly class TodoModel {
  public function __construct(private PDO $pdo) {}

  public function loadAll(int $user_id): array {
    return self::loadAllStatic($this->pdo, $user_id);
  }

  public function load(int $id, int $user_id = 0): array|null {
    return self::loadStatic($this->pdo, $id, $user_id);
  }

  public function create(int $user_id, string $title): false|string {
    $query = 'INSERT INTO `todos` (`title`, `user_id`) VALUES (?, ?);';

    $statement = $this->pdo->prepare($query);
    $statement->execute([$title, $user_id]);

    return $this->pdo->lastInsertId();
  }

  public function update(int $id, bool $completed): bool {
    $query = 'UPDATE `todos` SET `completed` = ? WHERE `id` = ?';
    $statement = $this->pdo->prepare($query);
    return $statement->execute([+$completed, $id]);
  }

  // *************************** //

  public static function loadAllStatic(PDO $pdo, int $user_id): array {
    if (!$user_id) return [];
    $query = 'SELECT * FROM `todos` WHERE `user_id` = ? LIMIT 50';

    $statement = $pdo->prepare($query);
    $statement->execute([$user_id]);

    return $statement->fetchAll(PDO::FETCH_ASSOC);
  }

  public static function loadStatic(PDO $pdo, int $id, int $user_id = 0): array|null {
    if (!$id) return null;
    $query = 'SELECT * FROM `todos` WHERE `id` = ? AND `user_id` = ? LIMIT 1';

    $statement = $pdo->prepare($query);
    $statement->execute([$id, $user_id]);

    return $statement->fetch(PDO::FETCH_ASSOC);
  }
}