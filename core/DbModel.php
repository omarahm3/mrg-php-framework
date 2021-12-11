<?php

namespace app\core;

abstract class DbModel extends Model
{
  abstract public function tableName(): string;

  abstract public function attributes(): array;

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