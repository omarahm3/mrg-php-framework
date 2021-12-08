<?php

namespace app\core;

class Application
{
  public static string $ROOT_DIR;
  public static Application $app;
  public Router $router;
  public Request $request;
  public Response $response;
  public Controller $controller;

  public function __construct($rootPath)
  {
    self::$ROOT_DIR = $rootPath;
    self::$app = $this;
    $this->request = new Request();
    $this->response = new Response();
    $this->router = new Router($this->request, $this->response);
  }

  public function run(): void
  {
    echo $this->router->resolve();
  }

  public function setController($controller): void
  {
    $this->controller = $controller;
  }

  public function getController()
  {
    return $this->controller;
  }
}
