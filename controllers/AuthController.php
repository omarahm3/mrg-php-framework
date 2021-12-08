<?php

namespace app\controllers;

use app\core\Request;

class AuthController extends BaseController
{
  public function __construct()
  {
    $this->setLayout('auth');
  }
  
  public function login()
  {
    return $this->render('login');
  }
  
  public function register(Request $request)
  {
    if ($request->isGet()) {
      return $this->render('register');
    }

    return 'Handle submitted data';
  }
  
}
