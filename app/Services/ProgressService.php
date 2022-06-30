<?php

namespace App\Services;

use Core\View\ViewManager;

class ProgressService extends ViewManager
{
    public function test(): string
    {
        $areas_1 = [
            'Routine pillars' => [
                'Meditation', 'Sport'
            ],
            'Excel in' => [
                'Programming'
            ]
        ];

        $areas_2 = [
            'Improve on' => [
                'English', 'Soft-skills'
            ],
            'Tools for Improvement' => [
                'Reading and Summarizing'
            ]
        ];

        return $this->renderView('app/progress/goals.html.twig', [
            'title' => 'How?',
            'description' => 'The direction is already set, now we need to focus on being on the right path for most of the time',
            'areas_1' => $areas_1,
            'areas_2' => $areas_2,
        ]);
    }

}