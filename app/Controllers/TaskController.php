<?php

namespace App\Controllers;

use App\Models\Category;
use App\Models\Task;
use Core\Base\BaseController;
use Core\Builder\FormBuilder\FormBuilder;

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
        $addEditForm = $this->addEditForm('/tasks');

        echo $this->renderView('app/task/new.html.twig', ['form' => $addEditForm->getHtml()]);
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

        echo $this->renderView('app/task/edit.html.twig', [
            'task' => $task,
            'form' => $form->getHtml(),
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
            'value' => $task['name'] ?? '',
        ]);

        $form->addInput([
            'type' => 'datetime-local',
            'name' => 'date_start',
            'label' => 'Date start',
            'value' => $task['date_start'] ?? date('Y-m-d\TH:i')
        ]);

        $form->addInput([
            'type' => 'datetime-local',
            'name' => 'date_end',
            'label' => 'Date end',
            'value' => $task['date_end'] ?? date('Y-m-d\TH:i')
        ]);

        $form->addSelect([
            'name' => 'done',
            'label' => 'Done',
            'value' => $task['done'] ?? 0,
            'options' => [['name' => 'Yes', 'value' => 1], ['name' => 'No', 'value' => 0]]
        ]);

        $form->setSubmit(['label' => 'Save']);

        return $form;
    }
}