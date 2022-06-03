<?php

namespace Core;

class Request extends Validation
{
    protected array $request;

    public function __construct(array $request = [])
    {
        if (!empty($request)) {
            $this->request = $request;
        } else {
            $this->request = $_REQUEST;
        }
    }

    public function all(): array
    {
        return $this->request;
    }

    public function get($key)
    {
        return $this->request[$key];
    }
}