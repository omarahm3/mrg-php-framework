<?php

namespace app\core;

/**
 * Router is responsible for handling or creating any of the application routes
 *
 * @class Router
 */
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

  /**
   * This function responsible for resolving current path and compare it against application routes
   * 
   * In case route doesn't exist, it will render the 404 page
   *
   * Routes can be 3 types:
   *    - If the callback is a 'string', then resolve function will expect a view file
   *    - If the callback is 'array', then resolve function will expect a controller and it will call this method
   *    - If the callback is a 'function', then resolve function will just call this function
   * 
   * @return string
   */
  public function resolve(): string
  {
    $path = $this->request->getPath();
    $method = $this->request->getMethod();
    $callback = $this->routes[$method][$path] ?? false;

    // In case no callback for this path, just render 404 page
    if (!$callback) {
      $this->response->setStatusCode(404);
      return $this->renderView('_404');
    }

    // Handle rendering only views
    if (is_string($callback)) {
      return $this->renderView($callback);
    }

    // Handle rendering controller actions
    if (is_array($callback)) {
      Application::$app->controller = new $callback[0]();
      $callback[0] = Application::$app->controller;
    }

    // Here handle any callback closure function
    return call_user_func($callback, $this->request, $this->response);
  }

  /**
   * This will render certain view file, given an optional parameters to inject into the runtime of the view file
   * It is used mostly by routes actions/callbacks
   *
   * @param string $view
   * @param array|null $params
   * @return string
   */
  public function renderView(string $view, array $params = null): string
  {
    $layoutContent = $this->layoutContent();
    $viewContent = $this->renderOnlyView($view, $params);
    return str_replace('{{content}}', $viewContent, $layoutContent);
  }

  /**
   * Get the layout view content, layout retrieved from controller
   *
   * @return string
   */
  protected function layoutContent(): string
  {
    $layout = Application::$app->controller->layout;
    ob_start();
    include_once Application::$ROOT_DIR."/views/layouts/$layout.php";
    return ob_get_clean();
  }

  /**
   * This will render only the view content aside from the layout and will handle passing parameters to view file if any
   *
   * @param string $view
   * @param array|null $params
   * @return string
   */
  protected function renderOnlyView(string $view, array $params = null)
  {
    foreach($params as $key => $value) {
      $$key = $value;
    }

    // Here start capturing the output buffer instead of outputting it directly into the page
    ob_start();
    include_once Application::$ROOT_DIR."/views/$view.php";
    // Then just here we get what was outputted to the buffer cleaned and we return it
    return ob_get_clean();
  }
}
