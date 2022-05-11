<!doctype html>
<html lang="ru">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <?php if (\App\Core\App::getInstance()->session->getErrors('email') || \App\Core\App::getInstance()->session->getErrors('password')): ?>
        <script>
            window.onload = function () {
              document.getElementById('dropdownMenu').click()
            }
        </script>
    <?php endif; ?>

    <title><?= $this->title ?></title>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="/">Главная</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="/tasks">Задачи</a>
                </li>
                <?php if (!is_null(\App\Core\App::getInstance()->user)): ?>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="/tasks/create">Создать задачу</a>
                </li>
                <?php endif; ?>
            </ul>
            <?php if (!is_null(\App\Core\App::getInstance()->user)): ?>
            <span><?= \App\Core\App::getInstance()->user->name ?></span>
            <a class="btn btn-sm btn-outline-secondary ms-2" href="/logout">Выйти</a>
            <?php else: ?>
            <div class="dropdown">
                <button class="btn btn-sm btn-outline-secondary dropdown-toggle"
                        type="button"
                        id="dropdownMenu"
                        data-bs-toggle="dropdown"
                        aria-expanded="false">
                    Войти
                </button>
                <div class="dropdown-menu dropdown-menu-end" style="min-width: 20rem;">
                    <form class="p-3" action="/login" method="post">
                        <?php
                        \App\Core\Component::include('inputs/email', [
                            'id' => 'email',
                            'label' => 'Email',
                            'name' => 'email',
                            'value' => \App\Core\App::getInstance()->session->old('email'),
                            'error' => \App\Core\App::getInstance()->session->getFirstError('email')
                        ]);

                        \App\Core\Component::include('inputs/password', [
                            'id' => 'password',
                            'label' => 'Пароль',
                            'name' => 'password',
                            'value' => '',
                            'error' => \App\Core\App::getInstance()->session->getFirstError('password')
                        ]);

                        \App\Core\Component::include('inputs/checkbox', [
                            'id' => 'remember_me',
                            'label' => 'Запомнить меня',
                            'name' => 'remember_me',
                            'value' => \App\Core\App::getInstance()->session->old('remember_me'),
                        ]); ?>

                        <button type="submit" class="btn btn-primary">Войти</button>
                    </form>
                </div>
            </div>
            <a class="btn btn-sm btn-outline-secondary ms-2" href="/register">Зарегистрироваться</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<div class="container">
    {{content}}
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script>
</body>
</html>
