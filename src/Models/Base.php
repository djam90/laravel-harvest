<?php

namespace Djam90\Harvest\Models;

use Carbon\Carbon;

class Base
{
    private $carbonParseable = [
        'accepted_at',
        'closed_at',
        'created_at',
        'declined_at',
        'due_date',
        'ends_on',
        'issue_date',
        'over_budget_notification_date',
        'paid_at',
        'period_end',
        'period_start',
        'sent_at',
        'spent_date',
        'starts_on',
        'timer_started_at',
        'updated_at',
    ];

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
        if (in_array($key, $this->carbonParseable) && !is_null($value)) {
            return new Carbon($value);
        }

        switch($key) {
            default:
                return $value;
                break;
        }
    }
}