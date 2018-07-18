<?php

namespace Djam90\Harvest\Objects;

class BaseObject
{
    public function __construct($data)
    {
        $this->hydrate($data);
    }

    public function hydrate($data)
    {
        $this->original_data = $data;

        foreach ($data as $key => $value) {
            $this->{$key} = $this->processValue($key, $value);
        }
    }

    public function processValue($key, $value)
    {
        switch($key) {
            case 'created_at':
            case 'updated_at':    
                return new \Carbon\Carbon($value);
                break;
            default: 
                return $value;
                break;
        }
    }
}