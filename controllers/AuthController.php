<?php

namespace app\controllers;

use app\core\Request;

class AuthController extends BaseController
{
  /**
   * undocumented function
   *
   * @return void
   */
  public function login()
  {
    return $this->render('login');
  }
  
  /**
   * undocumented function
   *
   * @return void
   */
  public function register(Request $request)
  {
    if ($request->isGet()) {
      return $this->render('register');
    }

    return 'Handle submitted data';
  }
  
}
