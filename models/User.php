<?php

namespace app\models;

use app\core\DbModel;

class User extends DbModel
{
  const STATUS_INACTIVE = 0;
  const STATUS_ACTIVE = 1;
  const STATUS_DELETED = 2;

  public string $firstname = '';

  public string $lastname = '';

  public string $email = '';

  public string $password = '';

  public string $confirmPassword = '';

  public int $status = self::STATUS_INACTIVE;

  public function tableName(): string
  {
    return 'users';
  }

  public function attributes(): array
  {
    return ['firstname', 'lastname', 'email', 'password', 'status'];
  }

  /**
   * Here we override parent::save method just to hash user password before inserting data into DB
   *
   * @return bool
   */
  public function save(): bool
  {
    $this->password = password_hash($this->password, PASSWORD_DEFAULT);
    $this->status = self::STATUS_INACTIVE;

    return parent::save();
  }

  public function rules(): array
  {
    return [
      'firstname' => [self::RULE_REQUIRED],
      'lastname' => [self::RULE_REQUIRED],
      'email' => [self::RULE_REQUIRED, self::RULE_EMAIL, [self::RULE_UNIQUE, 'class' => self::class]],
      'password' => [self::RULE_REQUIRED, [self::RULE_MIN_LENGTH, 'value' => 8], [self::RULE_MAX_LENGTH, 'value' => 16]],
      'confirmPassword' => [self::RULE_REQUIRED, [self::RULE_MATCH, 'match' => 'password']],
    ];
  }
}

