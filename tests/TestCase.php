<?php

namespace Djam90\Harvest\Test;

use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return ['Djam90\Harvest\HarvestServiceProvider'];
    }
}