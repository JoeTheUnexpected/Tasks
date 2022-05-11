<?php


class m002_users
{
    public function up()
    {
        \App\Core\App::getInstance()->db->pdo->exec('create table if not exists users (
            id int auto_increment primary key,
            name varchar(50) not null,
            email varchar(50) not null,
            password varchar(60) not null,
            selector varchar(60) default null,
            validator varchar(60) default null
        ) engine=innodb');

        \App\Core\App::getInstance()->db->pdo->exec('insert ignore into users (id, name, email, password) values (1, \'Admin\', \'admin@admin.com\', \'' . password_hash('admin', PASSWORD_DEFAULT) . '\')');
    }

    public function down()
    {
        \App\Core\App::getInstance()->db->pdo->exec('drop table if exists users');
    }
}