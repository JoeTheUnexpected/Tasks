<?php


namespace App\Core;


use App\Models\User;

class App
{
    private static App $instance;

    public static string $ROOT_PATH;
    public Router $router;
    public Request $request;
    public Response $response;
    public View $view;
    public Db $db;
    public Session $session;
    public ?User $user;

    private function __construct()
    {
        self::$ROOT_PATH = dirname(__DIR__);

        $this->router = new Router;
        $this->request = new Request;
        $this->response = new Response;
        $this->view = new View;
        $this->db = Db::getInstance();
        $this->session = new Session;
    }

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new static;
        }

        return self::$instance;
    }

    private function __clone()
    {

    }

    public function run()
    {
        try {
            $this->auth();
            $this->router->resolve(self::$instance);
        } catch (\Exception $e) {
            $this->view->renderView('error', [
                'e' => $e
            ]);
        }

    }

    private function auth()
    {
        $userId = $this->session->get('user');
        if (!$userId) {
            $token = filter_input(INPUT_COOKIE, 'remember_me');
            if ($token) {
                [$selector, $validator] = explode(':', $token);
                $user = (new User)->findOne($selector, 'selector');
                if (password_verify($validator, $user->validator)) {
                    $this->session->set('user', $user->id);
                    $this->user = $user;
                    return;
                }
            }

            $this->user = null;
            return;
        }

        $user = (new User)->findOne($userId);
        if (!$user) {
            $this->user = null;
            return;
        }

        $this->user = $user;
    }
}