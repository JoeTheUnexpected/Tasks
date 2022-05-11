<?php


namespace App\Core;


class Db
{
    public static $instance;
    public \PDO $pdo;

    private function __construct()
    {
        $this->pdo = new \PDO('mysql:host=db;dbname=tasks_db;', 'user', 'password');
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    private function __clone()
    {

    }

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new static;
        }

        return self::$instance;
    }

    public function applyMigrations()
    {
        $this->createMigrationsTable();

        $appliedMigrations = $this->getAppliedMigrations();

        $newMigrations = [];
        $files = scandir(dirname(App::$ROOT_PATH) . "/migrations");
        $migrationsToApply = array_diff_key($files, $appliedMigrations);
        foreach ($migrationsToApply as $migration) {
            if ($migration === '.' || $migration === '..') {
                continue;
            }

            require_once dirname(App::$ROOT_PATH) . "/migrations/$migration";
            $className = pathinfo($migration, PATHINFO_FILENAME);
            $instance = new $className;
            $this->log("Applying migration $migration");
            $instance->up();
            $this->log("Applied migration $migration");
            $newMigrations[] = $migration;
        }

        if (!empty($newMigrations)) {
            $this->saveMigrations($newMigrations);
        } else {
            $this->log('All migrations are applied');
        }
    }

    private function createMigrationsTable()
    {
        $this->pdo->exec("create table if not exists migrations (
            id int auto_increment primary key,
            migration varchar(255),
            created_at timestamp default current_timestamp
        ) engine=innodb;");
    }

    private function getAppliedMigrations()
    {
        $stmt = $this->pdo->prepare("select migration from migrations");
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_COLUMN);
    }

    private function saveMigrations(array $migrations)
    {
        $str = implode(',', array_map(fn ($m) => "('$m')", $migrations));
        $stmt = $this->pdo->prepare("insert into migrations (migration) values $str");
        $stmt->execute();
    }

    private function log(string $message)
    {
        echo '[' . date('Y-m-d H:i:s') . '] - ' . $message . PHP_EOL;
    }
}