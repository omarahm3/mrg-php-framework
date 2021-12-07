<?php

namespace app\controllers;

use app\core\Application;

class SiteController
{
  public function showHome()
  {
    $params = [
      'name' => 'MrGeek',
    ];

    return Application::$app->router->renderView('home', $params);
  }

  public function showContact()
  {
    return Application::$app->router->renderView('contact');
  }

  public function handleContact()
  {
    return 'Handle submitted data';
  }
}
