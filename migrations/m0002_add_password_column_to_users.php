<?php

class m0002_add_password_column_to_users {
  public \app\core\Database $database;

  public function __construct()
  {
    $this->database = \app\core\Application::$app->db;
  }

  public function up()
  {
    $sql = "ALTER TABLE users ADD COLUMN password VARCHAR(512) NOT NULL;";

    $this->database->pdo->exec($sql);
  }

  public function down()
  {
    $sql = "ALTER TABLE users DROP COLUMN ;";

    $this->database->pdo->exec($sql);
  }
}
