<?php

namespace app\controllers;

use app\core\Request;

class SiteController extends BaseController
{
  public function showHome()
  {
    $params = [
      'name' => 'MrGeek',
    ];

    return $this->render('home', $params);
  }

  public function showContact()
  {
    return $this->render('contact');
  }

  public function handleContact(Request $request)
  {
    $body = $request->getBody();
    echo "<pre>";
    var_dump($body);
    echo "</pre>";
    exit();
    
    return 'Handle submitted data';
  }
}
