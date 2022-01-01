<?php

namespace app\core;

/**
 * @class Request
 */
class Request
{
  /**
   * Will parse current route and return the endpoint
   *
   * @return string
   */
  public function getPath(): string
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

  public function isGet(): bool
  {
    return $this->getMethod() === 'get';
  }

  public function isPost(): bool
  {
    return $this->getMethod() === 'post';
  }

  /**
   * Not the best way to handle XSS attacks, but this to at least sanitize input fields
   */
  private function sanitizeInputValue(string $value, int $type): ?string
  {
    return filter_input($type, $value, FILTER_SANITIZE_SPECIAL_CHARS);
  }

  /**
   * Will parse and return POST/GET body params
   *
   * @return array
   */
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
