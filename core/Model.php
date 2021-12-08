<?php

namespace app\core;

abstract class Model implements ModelInterface
{
  public const RULE_REQUIRED = 'required';
  public const RULE_EMAIL = 'email';
  public const RULE_MIN_LENGTH = 'min_length';
  public const RULE_MAX_LENGTH = 'max_length';
  public const RULE_MATCH = 'match';

  public array $errors = [];

  abstract public function rules(): array;

  public function loadData(array $data): void
  {
    foreach ($data as $key => $value) {
      if (property_exists($this, $key)) {
        $this->{$key} = $value;
      }
    }
  }

  public function validate(): bool
  {
    foreach ($this->rules() as $attribute => $rules) {
      $value = $this->{$attribute};
      foreach ($rules as $rule) {
        $ruleName = $rule;

        if (is_array($ruleName)) {
          $ruleName = $ruleName[0];
        }

        if ($ruleName === self::RULE_REQUIRED && !$value) {
          $this->addError($attribute, self::RULE_REQUIRED);
        }

        if ($ruleName === self::RULE_EMAIL && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
          $this->addError($attribute, self::RULE_EMAIL);
        }

        if ($ruleName === self::RULE_MIN_LENGTH && !isset($rule['value'])) {
          throw new \Exception(sprintf('[%s] Must have a "value" property', self::RULE_MIN_LENGTH));
        }

        if ($ruleName === self::RULE_MAX_LENGTH && !isset($rule['value'])) {
          throw new \Exception(sprintf('[%s] Must have a "value" property', self::RULE_MAX_LENGTH));
        }

        if ($ruleName === self::RULE_MATCH && !isset($rule['match'])) {
          throw new \Exception(sprintf('[%s] Must have a "value" property', self::RULE_MAX_LENGTH));
        }

        if ($ruleName === self::RULE_MIN_LENGTH && strlen($value) < $rule['value']) {
          $this->addError($attribute, self::RULE_MIN_LENGTH, $rule);
        }

        if ($ruleName === self::RULE_MAX_LENGTH && strlen($value) > $rule['value']) {
          $this->addError($attribute, self::RULE_MAX_LENGTH, $rule);
        }

        if ($ruleName === self::RULE_MATCH && $value !== $this->{$rule['match']}) {
          $this->addError($attribute, self::RULE_MATCH, $rule);
        }
      }
    }

    return empty($this->errors);
  }

  public function addError(string $attribute, string $rule, array $params = []): void
  {
    $message = $this->errorMessages()[$rule] ?? '';

    foreach ($params as $key => $value) {
      $message = str_replace(sprintf('{%s}', $key), $value, $message);
    }

    $this->errors[$attribute][] = $message;
  }

  public function errorMessages(): array
  {
    return [
      self::RULE_REQUIRED => 'This field is required',
      self::RULE_EMAIL => 'This field must be a valid email',
      self::RULE_MIN_LENGTH => 'Minimum length must be {value}',
      self::RULE_MAX_LENGTH => 'Maximum length must be {value}',
      self::RULE_MATCH => 'This field must be the same as {match}',
    ];
  }

  public function hasError($attribute): bool
  {
    return isset($this->errors[$attribute]) && count($this->errors[$attribute]) > 0;
  }

  public function getFirstError($attribute)
  {
    return $this->errors[$attribute][0] ?? false;
  }
}