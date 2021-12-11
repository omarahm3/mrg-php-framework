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
    $registerModel = new User();

    if ($request->isGet()) {
      return $this->render('register', [
        'model' => $registerModel,
      ]);
    }

    $registerModel->loadData($request->getBody());

    if ($registerModel->validate() && $registerModel->register()) {
      return 'Success';
    }
    
    return $this->render('register', [
      'model' => $registerModel,
    ]);
  }

}
