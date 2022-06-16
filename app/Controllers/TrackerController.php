<?php

namespace App\Controllers;

use App\Models\Habit;
use App\Models\Tracker;
use Core\Builder\Form;
use Core\Builder\Modal;

class TrackerController extends AbstractController
{
    public function index()
    {
        $tracker = array_pluck(Tracker::all(), 'date_ymd');
        $habits = array_remap(Habit::all(), 'id');

        $new_tracker = [];
        foreach ($tracker as $date => $items) {
            foreach ($items as $item) {
                @$new_tracker[$date][$habits[$item['habit_id']]['name']] += $item['value'];
                ksort($new_tracker[$date]);
            }
        }

        /*
         * array[date][habit_name] = sum(value)
         *
         */


        /*foreach ($tracker as $k => $v) {
            $habit = Habit::get($v['habit_id']);
            $date = date('H:i', strtotime($v['date']));
            $minutes = $v['value'];
            $tracker[$k]['habit'] = $habit;
            $tracker[$k]['hour'] = $date;

            if ($habit['value_type'] === 'number') {
                $tracker[$k]['start_hour'] = date('H:i', strtotime("- $minutes minutes", strtotime($v['date'])));
            }
        }
        $days = array_pluck($tracker, 'date_ymd');*/

        echo $this->renderView('app/tracker/index.html.twig', ['days' => $new_tracker]);
    }

    public function new()
    {
        $habit_id = $_REQUEST['habit_id'];
        $habit = Habit::get($habit_id);

        $form = new Form();

        if ($habit['value_type'] == 'number') {
            $form->input([
                'type' => 'number',
                'name' => 'value',
                'label' => 'Minutes',
                'value' => 0
            ]);
        } else if ($habit['value_type'] == 'boolean') {
            $form->input([
                'type' => 'checkbox',
                'name' => 'value',
                'label' => 'Done?',
                'value' => 1
            ]);
        }

        $form->input([
            'type' => 'datetime-local',
            'name' => 'date',
            'label' => 'Date',
            'value' => date('Y-m-d\TH:i')
        ]);

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
        Tracker::insert($_REQUEST);
        #redirect('/tracker');
    }
}