<?php

namespace app\core;

class Database
{
  public \PDO $pdo;

  public function __construct(array $config)
  {
    $dsn = $config['dsn'] ?? '';
    $user = $config['user'] ?? '';
    $password = $config['password'] ?? '';

    $this->pdo = new \PDO($dsn, $user, $password);
    $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
  }

  private function createMigrationsTable(): void
  {
    $this->pdo->exec("CREATE TABLE IF NOT EXISTS migrations(
      id INT AUTO_INCREMENT PRIMARY KEY,
      migration VARCHAR(255),
      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=INNODB;");
  }

  /**
   * @return array|false
   */
  private function getAppliedMigrations()
  {
    $query = $this->pdo->prepare('SELECT migration FROM migrations;');
    $query->execute();

    return $query->fetchAll(\PDO::FETCH_COLUMN);
  }

  public function applyMigrations(): void
  {
    $this->createMigrationsTable();
    $appliedMigrations = $this->getAppliedMigrations();

    $migrations = [];
    $files = scandir(Application::$ROOT_DIR . '/migrations');
    $toApplyMigrations = array_diff($files, $appliedMigrations);

    foreach ($toApplyMigrations as $migration) {
      if (in_array($migration, ['.', '..'])) {
        continue;
      }

      require_once Application::$ROOT_DIR . '/migrations/' . $migration;

      $className = pathinfo($migration, PATHINFO_FILENAME);
      $instance = new $className();

      echo "Applying migration [$migration]" . PHP_EOL;
      $instance->up();
      echo "Finished applying migration [$migration]" . PHP_EOL;
      $migrations[] = $migration;
    }

    if (!empty($migrations)) {
      $this->saveMigrations($migrations);
    } else {
      echo "All migrations are already applied" . PHP_EOL;
    }
  }

  private function saveMigrations(array $migrations)
  {
    $migrationsString = implode(',', array_map(fn ($migration) => "('$migration')", $migrations));
    $query = $this->pdo->prepare("INSERT INTO migrations (migration) VALUES ($migrationsString)");
    $query->execute();
  }

  public function prepare($sql)
  {
    return $this->pdo->prepare($sql);
  }
}

