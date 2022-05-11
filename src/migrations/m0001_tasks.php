<?php


class m0001_tasks
{
    public function up()
    {
        \App\Core\App::getInstance()->db->pdo->exec('create table if not exists tasks (
            id int auto_increment primary key,
            user_name varchar(50) not null,
            user_email varchar(50) not null,
            text text not null,
            completed bool default 0
        ) engine=innodb');

        if (\App\Core\App::getInstance()->db->pdo->query('select count(*) from tasks')->fetchColumn() === 0) {
            $faker = \Faker\Factory::create();

            $stmt = \App\Core\App::getInstance()->db->pdo->prepare('insert into tasks (user_name, user_email, text) values (:name, :email, :text)');
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':text', $text);
            for ($i = 0; $i < 20; $i++) {
                $name = $faker->name;
                $email = $faker->unique()->email;
                $text = $faker->sentences(3, true);

                $stmt->execute();
            }
        }
    }

    public function down()
    {
        \App\Core\App::getInstance()->db->pdo->exec('drop table if exists tasks');
    }
}