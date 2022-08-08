<?php

namespace Core\Transcript;

use App\Models\Habit;
use App\Models\Page;

class Transcript
{
    private string $transcript;
    private array $habit;
    private float $value;
    private string $type = 'tracker';
    private string $url;

    public function __construct(string $text)
    {
        $this->transcript = strtolower($text);

        $this->getInstructions();
    }

    private function getInstructions(): void
    {
        $array = explode(' ', $this->transcript);

        if ($array[0] === 'goto') {
            $this->setType('goto');

            $page = Page::getByName($array[1]);

            if (!empty($page)) {
                $endpoint = 'edit';
                if (!empty($array[2]) && $array[2] === '-v') {
                    $endpoint = 'view';
                }
                $this->setUrl('pages/' . $page['id'] . '/' . $endpoint);
            }
        } else {
            $habit = Habit::getByName($array[1]);

            if (is_numeric($array[0]) && !empty($habit)) {
                $this->value = $array[0];
                $this->habit = $habit;
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

    public function getType(): string
    {
        return $this->type;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    private function setType(string $string): void
    {
        $this->type = $string;
    }

    private function setUrl(string $url): void
    {
        $this->url = $url;
    }
}