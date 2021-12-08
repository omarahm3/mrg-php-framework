<?php

namespace app\core\form;

use app\core\Model;

class Form
{
  public static function begin(string $action, string $method): Form
  {
    echo sprintf('<form method="%s" action="%s">', $method, $action);
    return new self();
  }

  public static function end(): void
  {
    echo '</form>';
  }

  public function field(Model $model, string $attribute): Field
  {
    return new Field($model, $attribute);
  }
}