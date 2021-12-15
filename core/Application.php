<?php

namespace app\core;

class Application
{
  public static string $ROOT_DIR;
  public static Application $app;
  public Router $router;
  public Request $request;
  public Response $response;
  public Session $session;
  public Controller $controller;
  public Database $db;

  public function __construct($rootPath, array $config)
  {
    self::$ROOT_DIR = $rootPath;
    self::$app = $this;
    $this->request = new Request();
    $this->response = new Response();
    $this->session = new Session();
    $this->router = new Router($this->request, $this->response);
    $this->db = new Database($config['db']);
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
