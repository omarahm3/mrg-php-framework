<?php

namespace app\controllers;

use app\core\Application;

class BaseController
{
  public string $layout = 'main';

  public function setLayout(string $layout): void
  {
    $this->layout = $layout;
  }
  
  public function render(string $view, array $params = [])
  {
    return Application::$app->router->renderView($view, $params);
  }
}
