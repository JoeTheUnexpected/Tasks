<?php


namespace App\Core;


abstract class Model
{
    abstract public function tableName(): string;
    abstract public function attributes(): array;
    abstract public function labels(): array;

    public function create(array $attributes)
    {
        $paramNames = implode(', ', array_keys($attributes));
        $params = implode(', ', array_map(fn($attr) => ":$attr", array_keys($attributes)));

        $stmt = App::getInstance()->db->pdo->prepare("insert into {$this->tableName()} ($paramNames) values ($params)");
        foreach ($attributes as $attribute => $value) {
            if (gettype($this->{$attribute}) === 'boolean') {
                $stmt->bindValue(":$attribute", $value, \PDO::PARAM_BOOL);
            } else {
                $stmt->bindValue(":$attribute", $value);
            }
        }
        $stmt->execute();

        return App::getInstance()->db->pdo->lastInsertId();
    }
}