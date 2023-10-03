<?php

namespace App\TestData;

class ApiDataStorage
{
    private $data = [];

    public function get(string $key)
    {
        return $this->data[$key] ?? null;
    }

    public function set(string $key, $value)
    {
        $this->data[$key] = $value;
    }
}
