<?php


namespace App\Controllers;


use App\Core\App;
use App\Core\Controller;
use App\Models\User;
use App\Requests\UserRequest;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->setLayout('auth');
    }

    public function register()
    {
        $this->render('auth/register');
    }

    public function store()
    {
        $user = new User;

        $user->create((new UserRequest($user))->validated());

        App::getInstance()->response->redirect('/');
    }

    public function login()
    {
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_SPECIAL_CHARS);
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS);
        $rememberMe = isset($_POST['remember_me']);

        App::getInstance()->session->setOld('email', $email);
        App::getInstance()->session->setOld('remember_me', $rememberMe);

        $user = (new User)->findOne($email, 'email');

        if (!$user) {
            App::getInstance()->session->setError('email', 'Пользователь с таким Email не зарегистрирован');
            App::getInstance()->response->back();
        }

        if (!password_verify($password, $user->password)) {
            App::getInstance()->session->setError('password', 'Неправильно введен пароль');
            App::getInstance()->response->back();
        }

        App::getInstance()->session->set('user', $user->id);
        App::getInstance()->user = $user;

        if ($rememberMe) {
            App::getInstance()->user->setTokens();
        }

        App::getInstance()->response->back();
    }

    public function logout()
    {
        App::getInstance()->session->remove('user');
        App::getInstance()->user->deleteTokens();
        App::getInstance()->user = null;

        App::getInstance()->response->back();
    }
}