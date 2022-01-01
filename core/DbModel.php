<?php

namespace app\core;

abstract class DbModel extends Model
{
  /**
   * Each model must implement this method to be able to interact with it's DB table
   */
  abstract public function tableName(): string;

  /**
   * Attributes are model properties and also will be used as model DB table columns
   * They're used in wide places on the framework, to build, handle the model itself
   */
  abstract public function attributes(): array;

  /**
   * Will handle insert new records of the model into the DB
   *
   * @return bool
   */
  public function save(): bool
  {
    $tableName = $this->tableName();
    $attributes = $this->attributes();
    $attributesCsv = implode(',', $attributes);
    $attributesParams = array_map(fn($attr) => ":$attr", $attributes);
    $paramsCsv = implode(',', $attributesParams);
    $statement = self::prepare("INSERT INTO $tableName ($attributesCsv) VALUES ($paramsCsv)");

    foreach ($attributes as $attribute) {
      $statement->bindValue(":$attribute", $this->{$attribute});
    }

    $statement->execute();
    return true;
  }

  public static function prepare(string $statement)
  {
    return Application::$app->db->pdo->prepare($statement);
  }
}
