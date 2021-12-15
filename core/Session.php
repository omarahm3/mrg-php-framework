<?php

namespace app\core;

class Session
{
  protected const FLASH_KEY = 'flash_messages'; 

  public function __construct()
  {
    session_start();
    $flashMessages = $_SESSION[self::FLASH_KEY] ?? [];

    foreach ($flashMessages as $key => &$flashMessage) {
      $flashMessage['remove'] = true;
    }

    $_SESSION[self::FLASH_KEY] = $flashMessages;
  }

  public function __destruct()
  {
    $flashMessages = $_SESSION[self::FLASH_KEY] ?? [];

    foreach ($flashMessages as $key => &$flashMessage) {
      if ($flashMessage['remove']) {
        unset($flashMessages[$key]);
      }
    }

    $_SESSION[self::FLASH_KEY] = $flashMessages;
  }

  public function setFlash(string $key, string $message): void
  {
    $_SESSION[self::FLASH_KEY][$key] = [
      'message' => $message,
    ];
  }

  /**
   * @param string $key
   * @return mixed|null
   */
  public function getFlash(string $key)
  {
    return $_SESSION[self::FLASH_KEY][$key]['message'] ?? false;
  }
}
