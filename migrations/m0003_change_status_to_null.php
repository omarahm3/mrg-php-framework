<?php

class m0003_change_status_to_null {
  public \app\core\Database $database;

  public function __construct()
  {
    $this->database = \app\core\Application::$app->db;
  }

  public function up()
  {
    $sql = "ALTER TABLE users MODIFY status TINYINT NULL;";

    $this->database->pdo->exec($sql);
  }

  public function down()
  {
    $sql = "ALTER TABLE users MODIFY status TINYINT NOT NULL";

    $this->database->pdo->exec($sql);
  }
}
