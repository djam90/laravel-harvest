<?php

namespace Djam90\Harvest;

use \Illuminate\Support\Facades\Facade;

class HarvestFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'harvest';
    }
}