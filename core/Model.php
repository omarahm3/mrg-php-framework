<?php

namespace app\core;

use Exception;

abstract class Model implements ModelInterface
{
  public const RULE_REQUIRED = 'required';
  public const RULE_EMAIL = 'email';
  public const RULE_MIN_LENGTH = 'min_length';
  public const RULE_MAX_LENGTH = 'max_length';
  public const RULE_MATCH = 'match';
  public const RULE_UNIQUE = 'unique';

  public array $errors = [];

  /**
   * Will return validation rules of the Model
   * It is used to validate each of the model attributes
   *
   * Rules example:
   *   [
   *     'password' =>
   *     [
   *       self::RULE_REQUIRED,
   *       [
   *         self::RULE_MIN_LENGTH,
   *         'value' => 8
   *       ],
   *       [
   *         self::RULE_MAX_LENGTH,
   *         'value' => 16
   *       ]
   *     ]
   *   ]
   *
   * @return array
   */
  abstract public function rules(): array;

  /**
   * This will use passed $data to assign it to each property of the model instance
   * Data passed must be an associative array with keys exactly the same as model's properties
   *
   * @param array $data
   */
  public function loadData(array $data): void
  {
    foreach ($data as $key => $value) {
      if (property_exists($this, $key)) {
        $this->{$key} = $value;
      }
    }
  }

  /**
   * Will handle validating model's data according to model's predefined rules
   *
   * TODO: There so much crap and if conditions in this method, it will be better to refactor the whole model validation part
   *
   * @return bool
   */
  public function validate(): bool
  {
    foreach ($this->rules() as $propertyName => $rules) {
      $value = $this->{$propertyName};
      foreach ($rules as $rule) {
        $ruleName = $rule;

        if (is_array($rule)) {
          $ruleName = $rule[0];
        }

        if ($ruleName === self::RULE_REQUIRED && !$value) {
          $this->addError($propertyName, self::RULE_REQUIRED);
        }

        if ($ruleName === self::RULE_EMAIL && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
          $this->addError($propertyName, self::RULE_EMAIL);
        }

        if ($ruleName === self::RULE_MIN_LENGTH && !isset($rule['value'])) {
          throw new Exception(sprintf('[%s] Must have a "value" property', self::RULE_MIN_LENGTH));
        }

        if ($ruleName === self::RULE_MAX_LENGTH && !isset($rule['value'])) {
          throw new Exception(sprintf('[%s] Must have a "value" property', self::RULE_MAX_LENGTH));
        }

        if ($ruleName === self::RULE_MATCH && !isset($rule['match'])) {
          throw new Exception(sprintf('[%s] Must have a "value" property', self::RULE_MAX_LENGTH));
        }

        if ($ruleName === self::RULE_MIN_LENGTH && strlen($value) < $rule['value']) {
          $this->addError($propertyName, self::RULE_MIN_LENGTH, $rule);
        }

        if ($ruleName === self::RULE_MAX_LENGTH && strlen($value) > $rule['value']) {
          $this->addError($propertyName, self::RULE_MAX_LENGTH, $rule);
        }

        if ($ruleName === self::RULE_MATCH && $value !== $this->{$rule['match']}) {
          $this->addError($propertyName, self::RULE_MATCH, $rule);
        }

        // TODO example of why should we handle the whole validation better
        if ($ruleName === self::RULE_UNIQUE) {
          $className = $rule['class'];

          if (!isset($className)) {
            throw new Exception(sprintf('[%s] Must have a "class" property', self::RULE_UNIQUE));
          }

          $uniqueProperty = $rule['attribute'] ?? $propertyName;
          $tableName = $className::tableName();

          $statement = Application::$app->db->prepare("SELECT * FROM $tableName WHERE $uniqueProperty = :property");
          $statement->bindValue(':property', $value);
          $statement->execute();
          $record = $statement->fetchObject();

          if ($record) {
            $this->addError($propertyName, self::RULE_UNIQUE, ['field' => $propertyName]);
          }
        }
      }
    }

    return empty($this->errors);
  }

  public function addError(string $propertyName, string $rule, array $params = []): void
  {
    $message = $this->errorMessages()[$rule] ?? '';

    foreach ($params as $key => $value) {
      $message = str_replace(sprintf('{%s}', $key), $value, $message);
    }

    $this->errors[$propertyName][] = $message;
  }

  public function errorMessages(): array
  {
    return [
      self::RULE_REQUIRED => 'This field is required',
      self::RULE_EMAIL => 'This field must be a valid email',
      self::RULE_MIN_LENGTH => 'Minimum length must be {value}',
      self::RULE_MAX_LENGTH => 'Maximum length must be {value}',
      self::RULE_MATCH => 'This field must be the same as {match}',
      self::RULE_UNIQUE => 'Record with this {field} already exists',
    ];
  }

  public function hasError($propertyName): bool
  {
    return isset($this->errors[$propertyName]) && count($this->errors[$propertyName]) > 0;
  }

  public function getFirstError($propertyName)
  {
    return $this->errors[$propertyName][0] ?? false;
  }
}
