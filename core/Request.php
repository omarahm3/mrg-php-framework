<?php

namespace app\core;

class Request
{
  public function getPath()
  {
    $path = $_SERVER['REQUEST_URI'] ?? '/';
    $position = strpos($path, '?');

    if (!$position) {
      return $path;
    }

    return substr($path, 0, $position);
  }

  public function getMethod()
  {
    return strtolower($_SERVER['REQUEST_METHOD']);
  }

  private function sanitizeInputValue(string $value, int $type): ?string
  {
    return filter_input($type, $value, FILTER_SANITIZE_SPECIAL_CHARS);
  }

  public function getBody()
  {
    $body = [];
    $params = $this->getMethod() === 'get' ? $_GET : $_POST;
    $methodType = $this->getMethod() === 'get' ? INPUT_GET : INPUT_POST;

    foreach ($params as $key => $value) {
      $body[$key] = $this->sanitizeInputValue($key, $methodType);
    }

    return $body;
  }
}
