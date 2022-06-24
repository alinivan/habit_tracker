<?php

namespace App\Controllers;

use App\Models\Habit;
use App\Models\Tracker;
use App\Services\TrackerService;
use Core\Base\BaseController;
use Core\Builder\Form;
use Core\Builder\Modal;
use Core\Helpers\Date;

class TrackerController extends BaseController
{
    private Tracker $tracker;
    private Habit $habit;

    public function __construct()
    {
        $this->tracker = new Tracker();
        $this->habit = new Habit();
        parent::__construct();
    }
    
    public function index()
    {

        $tracker_html = (new TrackerService())->getTracker(IMPORT_START_DATE, Date::getStartAndEndDate()['end_date'], false);

        echo $this->renderView('app/tracker/index.html.twig', ['tracker_html' => $tracker_html]);
    }

    public function new()
    {
        $habit_id = $_REQUEST['habit_id'];
        $habit = $this->habit->get($habit_id);

        $form = new Form();

        $form->input([
            'type' => 'datetime-local',
            'name' => 'date',
            'label' => 'Date',
            'label_class' => 'text-gray-900',
            'value' => date('Y-m-d\TH:i')
        ]);

        if ($habit['value_type'] == 'number') {
            $form->input([
                'type' => 'number',
                'name' => 'value',
                'label_class' => 'text-gray-900',
                'label' => 'Minutes',
                'value' => 0
            ]);
        } else if ($habit['value_type'] == 'boolean') {
            $form->input([
                'type' => 'hidden',
                'name' => 'value',
                'label_class' => 'text-gray-900',
                'value' => 1
            ]);
        }

        $form->input([
            'type' => 'hidden',
            'name' => 'habit_id',
            'value' => $habit_id,
        ]);


        $modal = new Modal();
        $modal->title($habit['name']);
        $save = "ajax('tracker/create', '" . $form->getId() . "', 'POST')";

        $modal->btn(['onclick' => $save, 'label' => 'Save']);
        $modal->content($form->html());

        echo $this->renderView('app/tracker/new.html.twig', ['modal' => $modal->html()]);
    }

    public function create()
    {
        if (!$_REQUEST['date']) {
            $_REQUEST['date'] = date('Y-m-d H:i');
        }
        $this->tracker->create($_REQUEST);
        #redirect('/tracker');
    }
}