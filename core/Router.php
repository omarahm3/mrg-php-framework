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
    $this->routes['get'][$path] = $callback;
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

    return $callback();
  }

  public function renderView(string $view)
  {
    $layoutContent = $this->layoutContent();
    $viewContent = $this->renderOnlyView($view);
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

  protected function renderOnlyView(string $view)
  {
    // Here start capturing the output buffer instead of outputing it directly into the page
    ob_start();
    include_once Application::$ROOT_DIR."/views/$view.php";
    // Then just here we get what was outputted to the buffer cleaned and we return it
    return ob_get_clean();
  }
}
