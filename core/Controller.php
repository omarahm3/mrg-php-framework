<?php

namespace app\core;

class Controller
{
  public string $layout = 'main';

  public function setLayout(string $layout): void
  {
    $this->layout = $layout;
  }
  
  /**
   * Wrapper around Router::renderView method
   *
   * @param string $view
   * @param array|null $params
   */
  public function render(string $view, array $params = [])
  {
    return Application::$app->router->renderView($view, $params);
  }
}
