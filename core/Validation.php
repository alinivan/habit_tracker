<?php

namespace Core;

class Validation
{
    protected array $request;
    protected array $rules;

    public function validate(array $rules): bool
    {
        $this->setRules($rules);

        foreach ($this->request as $k => $field) {
            if ($this->rules[$k] == 'required') {
                if (empty($field)) {
                    return false;
                }
            }
        }

        return true;
    }

    public function setRules(array $rules): void
    {
        $this->rules = $rules;
    }
}