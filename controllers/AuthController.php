<?php

namespace app\controllers;

use app\core\Application;
use app\core\Controller;
use app\core\Request;
use app\core\Response;
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

  /**
   * Handle both register GET/POST
   *
   * @param Request $request
   * @param Response $response
   */
  public function register(Request $request, Response $response)
  {
    $user = new User();

    // In case we want to render the register view only
    // We also pass User model to render user fields in case there are some validations errors
    if ($request->isGet()) {
      return $this->render('register', [
        'model' => $user,
      ]);
    }

    $user->loadData($request->getBody());

    if ($user->validate() && $user->save()) {
      Application::$app->session->setFlash('success', 'Thanks for registering');
      return $response->redirect('/');
    }

    return $this->render('register', [
      'model' => $user,
    ]);
  }
}
