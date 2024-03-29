<?php

namespace App\Controllers;

use App\Models\Task;
use Core\Base\BaseController;

class RoutineController extends BaseController
{
    public function index()
    {
        $tasksView = [];

        $tasks = Task::all();

        foreach ($tasks as $task) {
            $tasksView[] = [
                'id' => $task['id'],
                'title' => $task['name'],
                'start' => $task['date_start'],
                'end' => $task['date_end'] ?? 0,
                'classNames' => $task['done'] ? 'calendar-done' : ''
            ];
        }

        echo $this->renderView('app/routine/index.html.twig', [
            'tasks' => $tasksView
        ]);
    }
}