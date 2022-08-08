<?php

namespace Core\Transcript;

use App\Models\Tracker;

class Instructions
{
    private Transcript $transcript;

    public function __construct(Transcript $transcript)
    {
        $this->transcript = $transcript;
    }

    public function execute(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        if ($this->transcript->getType() === 'tracker') {
            if (!empty($this->transcript->getHabit()) && $this->transcript->getValue() > 0) {
                $tracker = [
                    'habit_id' => $this->transcript->getHabit()['id'],
                    'date' => date('Y-m-d H:i:s'),
                    'value' => $this->transcript->getValue()
                ];
                Tracker::create($tracker);
            }
        } else {
            if ($this->transcript->getUrl()) {
                echo json_encode(['url' => $this->transcript->getUrl()]);
            }
        }

    }
}