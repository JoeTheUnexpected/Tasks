# Test tasks

---

Нашел тестовое задание ([ссылка][1]), придумал дополнительные требования, что бы было сложнее и интереснее и выполнил.

Для сборки выполнить команду `docker-compose up -d --build`<br>
Приложение запускается на `localhost:8080`

При запуске в первый раз выполнить команду `docker exec test-tasks-app php migrate.php`<br>
Эта команда создаст необходимые таблицы, заполнит таблицу с задачами демонстрационными данными и создаст пользователя с ролью `admin`, логином `admin@admin.com` и паролем `admin`

[1]: https://docs.google.com/document/d/1Wn_BBhmrF8S5iwgqo5cH63GAM6XTXLi4glp7ZxammIM/edit