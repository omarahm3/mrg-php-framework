<?php

namespace app\core\form;

use app\core\Model;

/**
 * This represent form field instance where its customizable directly through view files
 *
 * @class Field
 */
class Field
{
  private const PLACEHOLDER_MAIN_CLASS = '{main_class}';
  private const PLACEHOLDER_LABEL_CLASS = '{label_class}';
  private const PLACEHOLDER_LABEL_CONTENT = '{label_content}';
  private const PLACEHOLDER_INPUT_CLASS = '{input_class}';
  private const PLACEHOLDER_INPUT_TYPE = '{input_type}';
  private const PLACEHOLDER_INPUT_NAME = '{input_name}';
  private const PLACEHOLDER_INPUT_VALUE = '{input_value}';

  public Model $model;
  public string $propertyName;

  private array $mainClass = [];
  private array $labelClass = [];
  private string $labelContent = '';
  private array $inputClass = [];
  private string $inputType = 'text';
  private string $inputName = '';

  public function __construct(Model $model, string $propertyName)
  {
    $this->model = $model;
    $this->propertyName = $propertyName;
  }

  /**
   * Will return a template of a Bootstrap form field
   * By inserting some placeholders into the HTML string
   *
   * @return string
   */
  private function template(): string
  {
    return sprintf('
      <div class="%s">
        <label class="%s">%s</label>
        <input type="%s" class="%s" name="%s" value="%s">
        <div class="invalid-feedback">%s</div>
      </div>
    ',
      self::PLACEHOLDER_MAIN_CLASS,
      self::PLACEHOLDER_LABEL_CLASS,
      self::PLACEHOLDER_LABEL_CONTENT,
      self::PLACEHOLDER_INPUT_TYPE,
      self::PLACEHOLDER_INPUT_CLASS,
      self::PLACEHOLDER_INPUT_NAME,
      self::PLACEHOLDER_INPUT_VALUE,
      $this->model->getFirstError($this->propertyName)
    );
  }

  /**
   * Will return a ready to render HTML string after parsing the template
   *
   * @return string
   */
  private function render(): string
  {
    $html = $this->template();

    if ($this->model->hasError($this->propertyName)) {
      $this->inputClass[] = ' is-invalid';
    }

    return str_replace(
      [
        self::PLACEHOLDER_MAIN_CLASS,
        self::PLACEHOLDER_LABEL_CLASS,
        self::PLACEHOLDER_INPUT_CLASS,
        self::PLACEHOLDER_LABEL_CONTENT,
        self::PLACEHOLDER_INPUT_TYPE,
        self::PLACEHOLDER_INPUT_NAME,
        self::PLACEHOLDER_INPUT_VALUE,
      ],
      [
        implode(' ', $this->mainClass),
        implode(' ', $this->labelClass),
        implode(' ', $this->inputClass),
        $this->labelContent,
        $this->inputType,
        $this->inputName,
        $this->model->{$this->propertyName},
      ], $html);
  }

  /**
   * Will handle rendering the actual Field instance by calling self::render method
   *
   * @return string
   */
  public function __toString(): string
  {
    return $this->render();
  }

  /**
   * @param string $mainClass
   * @return Field
   */
  public function mainClass(string $mainClass): Field
  {
    $this->mainClass = explode(' ', $mainClass);
    return $this;
  }

  /**
   * @param string $labelClass
   * @return Field
   */
  public function labelClass(string $labelClass): Field
  {
    $this->labelClass = explode(' ', $labelClass);
    return $this;
  }

  /**
   * @param string $inputClass
   * @return Field
   */
  public function inputClass(string $inputClass): Field
  {
    $this->inputClass = explode(' ', $inputClass);
    return $this;
  }

  /**
   * @param string $inputType
   * @return Field
   */
  public function inputType(string $inputType): Field
  {
    $this->inputType = $inputType;
    return $this;
  }

  /**
   * @return Field
   */
  public function text(): Field
  {
    $this->inputType = 'text';
    return $this;
  }

  /**
   * @return Field
   */
  public function email(): Field
  {
    $this->inputType = 'email';
    return $this;
  }

  /**
   * @return Field
   */
  public function password(): Field
  {
    $this->inputType = 'password';
    return $this;
  }

  /**
   * @param string $inputName
   * @return Field
   */
  public function inputName(string $inputName): Field
  {
    $this->inputName = $inputName;
    return $this;
  }

  /**
   * @param string $labelContent
   * @return Field
   */
  public function labelContent(string $labelContent): Field
  {
    $this->labelContent = $labelContent;
    return $this;
  }
}
