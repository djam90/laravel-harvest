<?php

namespace Djam90\Harvest\Test;

use Djam90\Harvest\HarvestService;
use Djam90\Harvest\Services\ClientService;

class HarvestServiceTest extends TestCase
{
    /** @test */
    public function it_cannot_be_instantiated_without_credentials_in_config()
    {
        $this->expectException(\Exception::class);
        app('harvest');
    }

    /** @test */
    public function it_can_be_instantiated_with_credentials_in_config()
    {
        $this->setCredentials();

        $harvestService = app('harvest');
        $this->assertInstanceOf(HarvestService::class, $harvestService);
    }

    /** @test */
    public function it_should_have_a_client_service()
    {
        $this->setCredentials();

        $harvestService = app('harvest');
        $service = $harvestService->client;

        $this->assertInstanceOf(ClientService::class, $service);
    }

    /**
     * @test
     * @dataProvider servicesProvider
     * @param $key
     */
    public function it_should_support_all_services_via_property_access($key)
    {
        $this->setCredentials();

        $harvestService = app('harvest');

        $instanceName = 'Djam90\Harvest\Services\\' . ucfirst($key) . 'Service';
        $class = $harvestService->{$key};

        $this->assertInstanceOf($instanceName, $class);
    }

    /**
     * @test
     * @dataProvider servicesProvider
     * @param $key
     */
    public function it_should_support_all_services_via_method_access($key)
    {
        $this->setCredentials();

        $harvestService = app('harvest');

        $instanceName = 'Djam90\Harvest\Services\\' . ucfirst($key) . 'Service';
        $class = $harvestService->{$key}();

        $this->assertInstanceOf($instanceName, $class);
    }

    /**
     * @test
     */
    public function it_should_throw_exception_when_accessing_invalid_service_via_property()
    {
        $this->setCredentials();

        $harvestService = app('harvest');

        $this->expectException(\Exception::class);
        $harvestService->foo;
    }

    /**
     * @test
     */
    public function it_should_throw_exception_when_accessing_invalid_service_via_method()
    {
        $this->setCredentials();

        $harvestService = app('harvest');

        $this->expectException(\Exception::class);
        $harvestService->foo();
    }

    public function servicesProvider()
    {
        return array_map(function ($item) {
            return [$item];
        }, [
            'clientContact',
            'client',
            'company',
            'estimateItemCategory',
            'estimateMessage',
            'estimate',
            'expenseCategory',
            'expense',
            'invoiceItemCategory',
            'invoiceMessage',
            'invoicePayment',
            'invoice',
            'project',
            'projectTaskAssignment',
            'projectUserAssignment',
            'role',
            'task',
            'timeEntry',
            'userProjectAssignment',
            'user'
        ]);
    }
}