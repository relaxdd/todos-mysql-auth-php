<?php

namespace Awenn2015\TestDemoTodos\Data;

class CurrentPage {
  public int $id;
  public string $uri;
  public string $title;
  public string $file;
  public bool $private;
  public string $content;

  public function __construct(array $current_page) {
    $this->id = (int)($current_page['id'] ?? 0);
    $this->uri = (string)($current_page['uri'] ?? '');
    $this->title = (string)($current_page['title'] ?? '');
    $this->file = (string)($current_page['file'] ?? '');
    $this->private = (bool)(int)($current_page['private'] ?? 0);
    $this->content = (string)($current_page['content'] ?? '');
  }

  public static function init(array|null $current_page): CurrentPage|null {
    return !$current_page ? null : new CurrentPage($current_page);
  }
}
