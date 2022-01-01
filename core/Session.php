<?php

namespace app\core;

/**
 * @class Session
 */
class Session
{
  protected const FLASH_KEY = 'flash_messages'; 

  public function __construct()
  {
    session_start();
    // Get all flash messages from session
    $flashMessages = $_SESSION[self::FLASH_KEY] ?? [];

    // Mark all flash messages to be removed
    foreach ($flashMessages as $key => &$flashMessage) {
      $flashMessage['remove'] = true;
    }

    // Save flash messages to session
    $_SESSION[self::FLASH_KEY] = $flashMessages;
  }

  public function __destruct()
  {
    $flashMessages = $_SESSION[self::FLASH_KEY] ?? [];

    // Remove all marked removed flash messages
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
      'remove' => false,
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
