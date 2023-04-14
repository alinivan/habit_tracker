<?php

namespace App\Controllers;

use App\Models\Habit;
use App\Models\Routine;
use App\Models\RoutineCategory;
use App\Models\Tracker;
use Core\Base\BaseController;
use Core\Builder\FormBuilder\FormBuilder;

class RoutineController extends BaseController
{
    public function index()
    {


//        dd(Tracker::all());






        $routineCategories = RoutineCategory::all();
        $routines = array_pluck(Routine::all(), 'routine_category_id');

        foreach ($routineCategories as &$routineCategory) {
            $routineCategory['routines'] = $routines[$routineCategory['id']];
        }

        $form = new FormBuilder();
        $form->setAction('routines/edit');
        $form->setMethod('POST');
        $form->addInput([
            'type' => 'text',
            'name' => 'name',
            'label' => 'Name',
            'value' => $category['name'] ?? '',
        ]);

        $habits = array_remap(Habit::all(), 'id');


        echo $this->renderView('app/routine/index.html.twig', [
            'routineCategories' => $routineCategories,
            'habits' => $habits
        ]);
    }
}