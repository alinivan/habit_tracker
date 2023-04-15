<?php

namespace App\Controllers;

use App\Models\Task;
use Core\Base\BaseController;
use Core\Builder\FormBuilder\FormBuilder;
use Core\Builder\Modal;

class TaskController extends BaseController
{
    public function index(): void
    {
        echo $this->renderView('app/task/index.html.twig', [
            'tasks' => Task::all()
        ]);
    }

    public function new(): void
    {
        $form = $this->addEditForm("/tasks");

        $modal = new Modal();
        $modal->setTitle('Add task');
        $save = "ajax('tasks', '" . $form->getId() . "', 'POST')";

        $modal->addBtn(['onclick' => $save, 'label' => 'Save']);
        $modal->setContent($form->getHtml());

        echo $this->renderView('app/task/edit.html.twig', [
            'modal' => $modal->getHtml()
        ]);

    }

    public function create(): void
    {
        Task::create($_REQUEST);

        redirect('/tasks');
    }

    public function show(int $id): void
    {
        echo $this->renderView('app/task/show.html.twig', ['task' => Task::get($id)]);
    }

    public function edit(int $id): void
    {
        $task = Task::get($id);

        $form = $this->addEditForm("/tasks/$id", $task);

        $modal = new Modal();
        $modal->setTitle($task['name']);
        $url = 'tasks/' . $task['id'];
        $save = "ajax('$url', '" . $form->getId() . "', 'POST')";

        $modal->addBtn(['onclick' => $save, 'label' => 'Save']);
        $modal->setContent($form->getHtml());

        echo $this->renderView('app/task/edit.html.twig', [
            'task' => $task,
            'modal' => $modal->getHtml()
        ]);
    }

    public function update(int $id): void
    {
        Task::modify($id, $_REQUEST);
        redirect('/tasks');
    }

    public function destroy(int $id): void
    {
        Task::delete($id);
        redirect('/tasks');
    }

    public function addEditForm(string $action, array $task = []): FormBuilder
    {
        $form = new FormBuilder();
        $form->setAction($action);
        $form->setMethod('POST');
        $form->addInput([
            'type' => 'text',
            'name' => 'name',
            'label' => 'Name',
            'label_class' => 'text-gray-900',
            'value' => $task['name'] ?? '',
        ]);

        $form->addSelect([
            'name' => 'done',
            'label' => 'Done',
            'value' => $task['done'] ?? 0,
            'label_class' => 'text-gray-900',
            'options' => [['name' => 'Yes', 'value' => 1], ['name' => 'No', 'value' => 0]]
        ]);

        $task['date_start'] = $task['date_start'] ?: $_REQUEST['date'] . ' 00:00:00';

        $form->addInput([
            'type' => 'datetime-local',
            'name' => 'date_start',
            'label' => 'Date start',
            'label_class' => 'text-gray-900',
            'value' => $task['date_start'] ?? date('Y-m-d\TH:i')
        ]);

        $form->addInput([
            'type' => 'datetime-local',
            'name' => 'date_end',
            'label' => 'Date end',
            'label_class' => 'text-gray-900',
            'value' => $task['date_end'] ?? date('Y-m-d\TH:i')
        ]);

        return $form;
    }
}