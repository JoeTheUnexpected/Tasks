<?php


class m003_roles
{
    public function up()
    {
        \App\Core\App::getInstance()->db->pdo->exec('create table if not exists roles (
            id int auto_increment primary key,
            name varchar(50) not null
        ) engine=innodb');

        \App\Core\App::getInstance()->db->pdo->exec('create table if not exists role_user (
            role_id int not null,
            user_id int not null,
            foreign key (role_id) references roles (id),
            foreign key (user_id) references users (id),
            primary key (role_id, user_id)
        ) engine=innodb');

        \App\Core\App::getInstance()->db->pdo->exec('insert ignore into roles (id, name) values (1, \'admin\'), (2, \'user\')');
        \App\Core\App::getInstance()->db->pdo->exec('insert ignore into role_user (role_id, user_id) values (1, 1)');
    }

    public function down()
    {
        \App\Core\App::getInstance()->db->pdo->exec('drop table if exists roles');

        \App\Core\App::getInstance()->db->pdo->exec('drop table if exists role_users');
    }
}