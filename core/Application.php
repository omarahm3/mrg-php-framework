<?php

namespace app\core;

/**
 * The core instance of the app, where we have access to everything
 *
 * @class Application
 */
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

  public function __construct(string $rootPath, array $config)
  {
    self::$ROOT_DIR = $rootPath;
    self::$app = $this;
    $this->request = new Request();
    $this->response = new Response();
    $this->session = new Session();
    $this->router = new Router($this->request, $this->response);
    $this->db = new Database($config['db']);
  }

  /**
   * This is responsible for spinning up the whole application, by just resolving the routes
   */
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
