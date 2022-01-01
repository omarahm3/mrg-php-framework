<?php

namespace app\core\form;

use app\core\Model;

/**
 * Used to create and render a form and its fields
 *
 * @class Form
 */
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

  /**
   * Will create new form field and return it
   *
   * @param Model $model
   * @param string $attribute
   * @return Field
   */
  public function field(Model $model, string $propertyName): Field
  {
    return new Field($model, $propertyName);
  }
}
