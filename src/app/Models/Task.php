<?php


namespace App\Models;


use App\Core\App;
use App\Core\Model;

class Task extends Model
{
    public string $user_name = '';
    public string $user_email = '';
    public string $text = '';
    public bool $completed = false;

    public function tableName(): string
    {
        return 'tasks';
    }

    public function attributes(): array
    {
        return ['user_name', 'user_email', 'text', 'completed'];
    }

    public function labels(): array
    {
        return [
            'user_name' => 'Имя пользователя',
            'user_email' => 'Email',
            'text' => 'Текст',
            'completed' => 'Завершена',
        ];
    }

    public function paginate(int $offset, int $limit, string $sortBy, string $sortOrder)
    {
        $sortBy = property_exists($this, $sortBy) ? $sortBy : 'id';
        $sortOrder = strtolower($sortOrder) === 'desc' ? 'desc' : 'asc';

        $stmt = App::getInstance()->db->pdo->prepare("select * from {$this->tableName()} order by $sortBy $sortOrder limit :limit offset :offset");
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    public function count(): int
    {
        $stmt = App::getInstance()->db->pdo->prepare("select count(*) from {$this->tableName()}");
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function getById(int $id)
    {
        $stmt = App::getInstance()->db->pdo->prepare("select * from {$this->tableName()} where id = :id limit 1");
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        $stmt->setFetchMode(\PDO::FETCH_CLASS, self::class);
        return $stmt->fetch();
    }

    public function create(array $attributes): void
    {
        parent::create($attributes);

        App::getInstance()->session->setFlash('success', 'Задача успешно создана');
        App::getInstance()->session->unsetOld();
    }

    public function update(array $attributes): void
    {
        $params = implode(', ', array_map(fn($attr) => "$attr = :$attr", array_keys($attributes)));

        $stmt = App::getInstance()->db->pdo->prepare("update {$this->tableName()} set $params where id = {$this->id}");
        foreach ($attributes as $attribute => $value) {
            if (gettype($this->{$attribute}) === 'boolean') {
                $stmt->bindValue(":$attribute", $value, \PDO::PARAM_BOOL);
            } else {
                $stmt->bindValue(":$attribute", $value);
            }
        }
        $stmt->execute();

        App::getInstance()->session->setFlash('success', 'Задача успешно обновлена');
        App::getInstance()->session->unsetOld();
    }

    public function delete(): void
    {
        $stmt = App::getInstance()->db->pdo->prepare("delete from {$this->tableName()} where id = {$this->id}");
        $stmt->execute();

        App::getInstance()->session->setFlash('success', "Задача #{$this->id} удалена");
    }
}