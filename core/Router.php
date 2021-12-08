<?php

namespace app\core;

class Router
{
  protected array $routes = [];
  public Request $request;
  public Response $response;

  public function __construct(Request $request, Response $response)
  {
    $this->request = $request;
    $this->response = $response;
  }

  public function get(string $path, $callback): void
  {
    $method = explode('::', __METHOD__)[1];
    $this->routes[$method][$path] = $callback;
  }

  public function post(string $path, $callback): void
  {
    $method = explode('::', __METHOD__)[1];
    $this->routes[$method][$path] = $callback;
  }

  public function resolve()
  {
    $path = $this->request->getPath();
    $method = $this->request->getMethod();
    $callback = $this->routes[$method][$path] ?? false;

    if (!$callback) {
      $this->response->setStatusCode(404);
      return $this->renderView('_404');
    }
    
    if (is_string($callback)) {
      return $this->renderView($callback);
    }

    if (is_array($callback)) {
      $callback[0] = new $callback[0]();
    }

    return call_user_func($callback);
  }

  public function renderView(string $view, array $params = null)
  {
    $layoutContent = $this->layoutContent();
    $viewContent = $this->renderOnlyView($view, $params);
    return str_replace('{{content}}', $viewContent, $layoutContent);
  }

  public function renderContent(string $viewContent)
  {
    $layoutContent = $this->layoutContent();
    return str_replace('{{content}}', $viewContent, $layoutContent);
  }

  protected function layoutContent()
  {
    ob_start();
    include_once Application::$ROOT_DIR."/views/layouts/main.php";
    return ob_get_clean();
  }

  protected function renderOnlyView(string $view, array $params = null)
  {
    foreach($params as $key => $value) {
      $$key = $value;
    }

    // Here start capturing the output buffer instead of outputing it directly into the page
    ob_start();
    include_once Application::$ROOT_DIR."/views/$view.php";
    // Then just here we get what was outputted to the buffer cleaned and we return it
    return ob_get_clean();
  }
}
