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

    public function setCredentials()
    {
        config()->set('harvest.uri', 'foo');
        config()->set('harvest.personal_access_token', 'foo');
        config()->set('harvest.account_id', 123);
    }
}