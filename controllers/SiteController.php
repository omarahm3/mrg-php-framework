<?php

namespace app\controllers;

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

  public function handleContact()
  {
    return 'Handle submitted data';
  }
}
