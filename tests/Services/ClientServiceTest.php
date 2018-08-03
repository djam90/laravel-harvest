<?php

namespace Djam90\Harvest\Test\Services;

use Djam90\Harvest\Api\Gateway;
use Djam90\Harvest\HarvestService;
use Djam90\Harvest\Services\ClientService;
use Djam90\Harvest\Test\TestCase;
use Illuminate\Support\Collection;
use InvalidArgumentException;

class ClientServiceTest extends TestCase
{
    /**
     * @var ClientService $clientService
     */
    private $clientService;

    /**
     * @var HarvestService $harvestService
     */
    private $harvestService;

    public function setUp()
    {
        parent::setUp();

        $this->setCredentials();

        $this->harvestService = app('harvest');

        $this->clientService = $this->harvestService->client;
    }

    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(ClientService::class, $this->clientService, "Client Service can be instant'd");
    }

    /** @test */
    public function it_will_call_api_with_empty_array()
    {
        $stub = $this->getStubApi();

        $stub->expects($this->once())
            ->method('get')
            ->with(
                $this->equalTo('clients'),
                $this->equalTo([])
            );

        $this->clientService->setApi($stub);
        $this->clientService->get();
    }

    /**
     * @test
     * @dataProvider getDataProvider
     * @param $input
     * @param $output
     */
    public function it_will_call_api_with_correct_array_data_for_get($input, $output)
    {
        $stub = $this->getStubApi();

        $stub->expects($this->once())
            ->method('get')
            ->with(
                $this->equalTo('clients'),
                $this->arrayHasKey($output)
            );

        $this->clientService->setApi($stub);

        $this->clientService->get(...$input);
    }

    /** @test */
    public function it_will_call_api_with_correct_array_data_for_get_page()
    {
        $stub = $this->getStubApi();

        $stub->expects($this->once())
            ->method('get')
            ->with(
                $this->equalTo('clients'),
                $this->equalTo([
                    'page' => 3,
                    'per_page' => 24
                ])
            );

        $this->clientService->setApi($stub);

        $this->clientService->getPage(3, 24);
    }

    /** @test */
    public function it_will_call_api_with_correct_array_data_for_get_by_id()
    {
        $stub = $this->getStubApi();

        $stub->expects($this->once())
            ->method('get')
            ->with(
                $this->equalTo('clients/99')
            );

        $this->clientService->setApi($stub);

        $this->clientService->getById(99);
    }

    /**
     * @test
     */
    public function it_will_call_api_with_correct_array_data_for_create()
    {
        $name = "Foo";
        $address = "1 Foo Street";
        $currency = "GBP";

        $stub = $this->getStubApi();

        $stub->expects($this->once())
            ->method('post')
            ->with(
                $this->equalTo('clients'),
                $this->equalTo([
                    'name' => $name,
                ])
            );

        $this->clientService->setApi($stub);

        $this->clientService->create($name);

        // Test $isActive
        $stub = $this->getStubApi();

        $stub->expects($this->once())
            ->method('post')
            ->with(
                $this->equalTo('clients'),
                $this->equalTo([
                    'name' => $name,
                    'is_active' => true
                ])
            );

        $this->clientService->setApi($stub);

        $this->clientService->create($name, true);

        // Test $address
        $stub = $this->getStubApi();

        $stub->expects($this->once())
            ->method('post')
            ->with(
                $this->equalTo('clients'),
                $this->equalTo([
                    'name' => $name,
                    'address' => $address
                ])
            );

        $this->clientService->setApi($stub);

        $this->clientService->create($name, null, $address);

        // Test $currency
        $stub = $this->getStubApi();

        $stub->expects($this->once())
            ->method('post')
            ->with(
                $this->equalTo('clients'),
                $this->equalTo([
                    'name' => $name,
                    'currency' => $currency
                ])
            );

        $this->clientService->setApi($stub);

        $this->clientService->create($name, null, null, $currency);
    }

    /**
     * @test
     */
    public function it_will_throw_exception_for_invalid_currency_on_create()
    {
        $name = "Foo";
        $currency = "FOO";

        $stub = $this->getStubApi();

        $this->expectException(InvalidArgumentException::class);

        $this->clientService->setApi($stub);

        $this->clientService->create($name, null, null, $currency);
    }


    /**
     * @test
     */
    public function it_will_throw_exception_for_invalid_currency_on_update()
    {
        $id = 999;
        $name = "Foo";
        $currency = "FOO";

        $stub = $this->getStubApi();

        $this->expectException(InvalidArgumentException::class);

        $this->clientService->setApi($stub);

        $this->clientService->update($id, $name, null, null, $currency);
    }

    /**
     * @test
     */
    public function it_will_call_api_with_correct_array_data_for_update()
    {
        $name = ["Foo"];
        $is_active = [null, true];
        $address = [null, null, "1 Foo Street"];
        $currency = [null, null, null, "GBP"];

        collect(compact('name', 'is_active', 'address', 'currency'))->each(function ($item, $key) {
            $id = 999;

            $stub = $this->getStubApi();

            $stub->expects($this->once())
                ->method('patch')
                ->with(
                    $this->equalTo('clients/' . $id),
                    $this->arrayHasKey($key)
                );

            $this->clientService->setApi($stub);

            $this->clientService->update($id, ...$item);
        });
    }

    /**
     * @test
     */
    public function it_will_call_api_with_correct_data_for_delete()
    {
        $id = 999;

        $stub = $this->getStubApi();

        $stub->expects($this->once())
            ->method('delete')
            ->with(
                $this->equalTo('clients/' . $id)
            );

        $this->clientService->setApi($stub);

        $this->clientService->delete($id);
    }

    public function getDataProvider()
    {
        $keys = collect([
            'is_active',
            'updated_since',
            'page',
            'per_page'
        ]);

        return $this->convertKeysCollectionToDataProviderStructure($keys);
    }

    private function getStubApi()
    {
        $stub = $this->getMockBuilder(Gateway::class)
            ->setMethods(['get', 'post', 'patch', 'delete'])
            ->getMock();

        $stub->method('get')
            ->willReturn([]);

        $stub->method('post')
            ->willReturn([]);

        $stub->method('patch')
            ->willReturn([]);

        $stub->method('delete')
            ->willReturn([]);

        return $stub;
    }

    private function convertKeysCollectionToDataProviderStructure(Collection $keys)
    {
        return $keys->map(function ($item, $key) {
            $internal = [];

            // Add the input as an array
            $input = [];
            if ($key > 0) {
                for ($i = 0; $i < $key; $i++) {
                    $input[] = null;
                }
            }
            $input[] = true;

            $internal[] = $input;
            $internal[] = $item;

            return $internal;
        })->toArray();
    }
}