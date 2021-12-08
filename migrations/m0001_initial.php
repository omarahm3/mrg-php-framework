<?php

class m0001_initial {
  public \app\core\Database $database;

  public function __construct()
  {
    $this->database = \app\core\Application::$app->db;
  }

  public function up()
  {
    $sql = "CREATE TABLE users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        email VARCHAR(255) NOT NULL,
        firstname VARCHAR(255) NOT NULL,
        lastname VARCHAR(255) NOT NULL,
        status TINYINT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=INNODB;";

    $this->database->pdo->exec($sql);
  }

  public function down()
  {
    $sql = "DROP TABLE users;";

    $this->database->pdo->exec($sql);
  }
}