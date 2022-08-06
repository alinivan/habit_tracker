<?php

namespace Core\Transcript;

use App\Models\Habit;

class Transcript
{
    private string $transcript;
    private array $habit;
    private float $value;

    public function __construct(string $text)
    {
        $this->transcript = strtolower($text);

        $this->getInstructions();
    }

    private function getInstructions(): void
    {
        $array = explode(' ', $this->transcript);

        //habit
        $habits = Habit::all();

        foreach ($habits as &$habit) {
            $habit['name'] = strtolower($habit['name']);
        }

        foreach ($habits as $v) {
            if (array_search($v['name'], $array)) {
                $this->habit = $v;
            }
        }

        //value
        foreach ($array as $item) {
            if (is_numeric($item)) {
                $this->value = $item;
            }
        }
    }

    public function getHabit(): array
    {
        return $this->habit;
    }

    public function getValue(): float
    {
        return $this->value;
    }
}