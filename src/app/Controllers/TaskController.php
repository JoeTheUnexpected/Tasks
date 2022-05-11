<?php


namespace App\Controllers;


use App\Core\App;
use App\Core\Controller;
use App\Core\Exception;
use App\Models\Task;
use App\Requests\TaskRequest;

class TaskController extends Controller
{
    public function index()
    {
        $task = new Task;

        $limit = 3;
        $offset = (($_GET['page'] ?? 1) - 1) * $limit;
        $sortBy = $_GET['sort_by'] ?? 'id';
        $sortOrder = $_GET['sort_order'] ?? 'asc';

        $tasks = $task->paginate($offset, $limit, $sortBy, $sortOrder);
        $tasksCount = $task->count();

        foreach (getallheaders() as $name => $value) {
            if ($name === 'Accept' && $value === 'application/json') {
                echo json_encode($tasks);
                die;
            }
        }

        $linksCount = intval(ceil($tasksCount / $limit));

        $this->render('tasks/index', compact('tasks', 'pagination', 'linksCount'));
    }

    public function create()
    {
        $this->render('tasks/create');
    }

    public function store()
    {
        $task = new Task;

        $task->create((new TaskRequest($task))->validated());

        App::getInstance()->response->back();
    }

    public function show(int $id)
    {
        $task = (new Task())->getById($id);

        if (!$task) {
            throw new Exception('Страница не найдена', 404);
        }

        $this->render('tasks/show', compact('task'));
    }

    public function edit(int $id)
    {
        $task = (new Task())->getById($id);

        if (!$task) {
            throw new Exception('Страница не найдена', 404);
        }

        $this->render('tasks/edit', compact('task'));
    }

    public function update(int $id)
    {
        $task = (new Task())->getById($id);

        if (!$task) {
            App::getInstance()->response->back();
        }

        $task->update((new TaskRequest($task))->validated());

        App::getInstance()->response->back();
    }

    public function destroy(int $id)
    {
        $task = (new Task())->getById($id);

        if (!$task) {
            App::getInstance()->response->back();
        }

        $task->delete();

        App::getInstance()->response->redirect('/tasks');
    }
}