<?php

namespace Djam90\Harvest\Models;

use Djam90\Harvest\HarvestService;

class Project extends Base
{
    /**
     * Get the latest associated time entry.
     *
     * @return TimeEntry
     */
    public function getLatestTimeEntry()
    {
        $harvestService = app('harvest');

        return $harvestService->timeEntry->get(
            null,
            null,
            $this->id,
            null,
            null,
            null,
            null,
            null,
            1,
            1
        )->time_entries->first();
    }

    /**
     * Get the earliest associated time entry.
     *
     * @return TimeEntry
     */
    public function getEarliestTimeEntry()
    {
        $harvestService = app('harvest');

        return $harvestService->timeEntry->getLastItem();
    }
}