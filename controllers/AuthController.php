<?php

namespace app\controllers;

use app\core\Controller;
use app\core\Request;
use app\models\User;

class AuthController extends Controller
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
    $user = new User();

    if ($request->isGet()) {
      return $this->render('register', [
        'model' => $user,
      ]);
    }

    $user->loadData($request->getBody());

    if ($user->validate() && $user->save()) {
      return 'Success';
    }

    return $this->render('register', [
      'model' => $user,
    ]);
  }
}
