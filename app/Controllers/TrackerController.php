<?php

namespace App\Controllers;

use App\Models\Habit;
use App\Models\Tracker;
use App\Services\TrackerService;
use Core\Base\BaseController;
use Core\Builder\FormBuilder\FormBuilder;
use Core\Builder\Modal;
use Core\Helpers\Date;
use Core\Transcript\Instructions;
use Core\Transcript\Transcript;

class TrackerController extends BaseController
{
    public function index()
    {
        $trackerHtml = (new TrackerService())->getTracker(IMPORT_START_DATE, Date::getStartAndEndDate()['end_date'], false);

        echo $this->renderView('app/tracker/index.html.twig', ['trackerHtml' => $trackerHtml]);
    }

    public function new()
    {
        $habitId = $_REQUEST['habit_id'];
        $routineCategoryId = $_REQUEST['routine_category_id'];
        $habit = Habit::get($habitId);

        $form = new FormBuilder();
        $form->addInput([
            'type' => 'datetime-local',
            'name' => 'date',
            'label' => 'Date',
            'label_class' => 'text-gray-900',
            'value' => date('Y-m-d\TH:i')
        ]);

        if ($habit['value_type'] == 'number') {
            $form->addInput([
                'type' => 'number',
                'name' => 'value',
                'label_class' => 'text-gray-900',
                'label' => 'Minutes',
                'value' => 0
            ]);
        } else if ($habit['value_type'] == 'boolean') {
            $form->addInput([
                'type' => 'hidden',
                'name' => 'value',
                'label_class' => 'text-gray-900',
                'value' => 1
            ]);
        }

        $form->addInput([
            'type' => 'hidden',
            'name' => 'habit_id',
            'value' => $habitId,
        ]);

        if ($routineCategoryId) {
            $form->addInput([
                'type' => 'hidden',
                'name' => 'routine_category_id',
                'value' => $routineCategoryId,
            ]);
        }

        $modal = new Modal();
        $modal->setTitle($habit['name']);
        $save = "ajax('tracker/create', '" . $form->getId() . "', 'POST')";

        $modal->addBtn(['onclick' => $save, 'label' => 'Save']);
        $modal->setContent($form->getHtml());

        echo $this->renderView('app/tracker/new.html.twig', [
            'modal' => $modal->getHtml()
        ]);
    }

    public function create()
    {
        if (!$_REQUEST['date']) {
            $_REQUEST['date'] = date('Y-m-d H:i');
        }
        Tracker::create($_REQUEST);
        #redirect('/tracker');
    }

    public function fastCreate()
    {
        if ($_REQUEST['search']) {
            $transcript = new Transcript($_REQUEST['search']);
            $instructions = new Instructions($transcript);
            $instructions->execute();
        }
    }

    public function upload()
    {
        $input = $_FILES['audio_data']['tmp_name'];
        $output = APP_ROOT . '/storage/voice/' . time() . '.wav';
        move_uploaded_file($input, $output);
    }
}