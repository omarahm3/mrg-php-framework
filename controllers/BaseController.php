<?php

namespace app\controllers;

use app\core\Application;

class BaseController
{
  public function render(string $view, array $params = [])
  {
    return Application::$app->router->renderView($view, $params);
  }
}
