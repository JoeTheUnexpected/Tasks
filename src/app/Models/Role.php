<?php


namespace App\Models;


use App\Core\App;
use App\Core\Model;

class Role extends Model
{
    public string $name = '';

    public function tableName(): string
    {
        return 'roles';
    }

    public function attributes(): array
    {
        return ['name'];
    }

    public function labels(): array
    {
        return ['name' => 'Название'];
    }

    public function getByName(string $name)
    {
        $stmt = App::getInstance()->db->pdo->prepare("select * from {$this->tableName()} where name = :name");
        $stmt->bindValue(':name', $name);
        $stmt->execute();
        $stmt->setFetchMode(\PDO::FETCH_CLASS, self::class);

        return $stmt->fetch();
    }
}