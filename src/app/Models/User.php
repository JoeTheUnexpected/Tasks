<?php


namespace App\Models;


use App\Core\App;
use App\Core\Model;

class User extends Model
{
    public string $name = '';
    public string $email = '';
    public string $password = '';

    public function tableName(): string
    {
        return 'users';
    }

    public function attributes(): array
    {
        return ['name', 'email', 'password'];
    }

    public function labels(): array
    {
        return [
            'name' => 'Имя',
            'email' => 'Email',
            'password' => 'Пароль'
        ];
    }

    public function create(array $attributes)
    {
        $userId = parent::create($attributes);

        (new User)->findOne($userId)->attachRole('user');

        App::getInstance()->session->unsetOld();
    }

    public function findOne(string $value, string $key = 'id')
    {
        $stmt = App::getInstance()->db->pdo->prepare("select * from {$this->tableName()} where $key = :$key limit 1");
        $stmt->bindValue(":$key", $value);
        $stmt->execute();
        $stmt->setFetchMode(\PDO::FETCH_CLASS, self::class);

        return $stmt->fetch();
    }

    public function setTokens()
    {
        $selector = bin2hex(random_bytes(16));
        $validator = bin2hex(random_bytes(32));

        $stmt = App::getInstance()->db->pdo->prepare("update {$this->tableName()} set selector = :selector, validator = :validator where id = {$this->id}");
        $stmt->bindValue(':selector', $selector);
        $stmt->bindValue(':validator', password_hash($validator, PASSWORD_DEFAULT));
        $stmt->execute();

        setcookie('remember_me', $selector . ':' . $validator, time() + (20 * 365 * 24 * 60 * 60), '/');
    }

    public function deleteTokens()
    {
        $stmt = App::getInstance()->db->pdo->prepare("update {$this->tableName()} set selector = null, validator = null where id = {$this->id}");
        $stmt->execute();

        setcookie('remember_me', null, -1, '/');
    }

    public function attachRole(string $roleName)
    {
        $role = (new Role)->getByName($roleName);

        $stmt = App::getInstance()->db->pdo->prepare("insert into role_user (role_id, user_id) values (:role_id, :user_id)");
        $stmt->bindValue(':role_id', $role->id);
        $stmt->bindValue(':user_id', $this->id);
        $stmt->execute();
    }

    public function isAdmin()
    {
        $role = (new Role)->getByName('admin');

        $stmt = App::getInstance()->db->pdo->prepare("select 1 from role_user where role_id = :role_id and user_id = :user_id limit 1");
        $stmt->bindValue(':role_id', $role->id);
        $stmt->bindValue(':user_id', $this->id);
        $stmt->execute();

        return $stmt->fetch();
    }
}