<?php

namespace Djam90\Harvest\Services;

use Djam90\Harvest\BaseService;
use Djam90\Harvest\Models\Client;

class ClientService extends BaseService
{
    protected $modelClass = \Djam90\Harvest\Models\Client::class;

    protected $path = "clients";

    /**
     * Get clients.
     *
     * Returns a list of your clients. The clients are returned sorted by
     * creation date, with the most recently created clients appearing first.
     *
     * @param boolean|null $isActive Pass true to only return active clients and false to return inactive clients.
     * @param string|null $updatedSince Only return clients that have been updated since the given date and time.
     * @param integer|null $page The page number to use in pagination.
     * @param integer|null $perPage The number of records to return per page.
     *
     * @return mixed
     */
    public function get($isActive = null, $updatedSince = null, $page = null, $perPage = null)
    {
        $uri = "clients";

        $data = [];

        if (!is_null($isActive)) $data['is_active'] = $isActive;
        if (!is_null($updatedSince)) $data['updated_since'] = $updatedSince;
        if (!is_null($page)) $data['page'] = $page;
        if (!is_null($perPage)) $data['per_page'] = $perPage;

        // @todo validate An ISO 8601 formatted string containing a UTC date
        // and time.

        $clients = $this->api->get($uri, $data);

        return $this->transformResult($clients);
    }

    /**
     * Get a specific page, useful for the getAll() method.
     *
     * @param int $page
     * @param int|null $perPage
     * @return mixed
     */
    public function getPage($page, $perPage = null)
    {
        return $this->get(null, null, $page, $perPage);
    }

    /**
     * Get a client by ID.
     *
     * Retrieves the client with the given ID.
     *
     * @param int $clientId The client ID.
     *
     * @return mixed
     */
    public function getById($clientId)
    {
        $uri = "clients/" . $clientId;

        $client = $this->api->get($uri);

        return $this->transformResult($client);
    }

    /**
     * Create a client.
     *
     * Creates a new client object. Returns a client object and a 201 Created response code if the call succeeded.
     *
     * @param string $name A textual description of the client.
     * @param bool|null $isActive Whether the client is active, or archived. Defaults to true.
     * @param string|null $address A textual representation of the client's physical address. May include new line
     * characters.
     * @param string|null $currency The currency used by the client. If not provided, the company's currency will be used.
     *
     * @return mixed
     */
    public function create($name, $isActive = null, $address = null, $currency = null)
    {
        $uri = "clients";

        $data = [
            'name' => $name,
        ];

        if (!is_null($isActive)) $data['is_active'] = $isActive;
        if (!is_null($address)) $data['address'] = $address;
        if (!is_null($currency)) $data['currency'] = $currency;

        // @todo implement currency validation as per
        // https://help.getharvest.com/api-v2/introduction/overview/supported-currencies/

        return $this->api->post($uri, $data);
    }

    /**
     * Update a client.
     *
     * Updates the specific client by setting the values of the parameters
     * passed. Any parameters not provided will be left unchanged.
     *
     * Returns a client object and a 200 OK response code if the call succeeded.
     *
     * @param integer $clientId The client ID.
     * @param string|null $name A textual description of the client.
     * @param boolean|null $isActive Whether the client is active, or archived.
     * @param string|null $address 	A textual representation of the client’s physical address. May include new line
     * characters.
     * @param string|null $currency The currency used by the client.
     *
     * @return mixed
     */
    public function update($clientId, $name = null, $isActive = null, $address = null, $currency = null)
    {
        $uri = "clients/" . $clientId;

        $data = [];

        if (!is_null($name)) $data['name'] = $name;
        if (!is_null($isActive)) $data['is_active'] = $isActive;
        if (!is_null($address)) $data['address'] = $address;
        if (!is_null($currency)) $data['currency'] = $currency;

        // @todo implement currency validation as per
        // https://help.getharvest.com/api-v2/introduction/overview/supported-currencies/

        return $this->api->patch($uri, $data);
    }

    /**
     * Delete a client.
     *
     * Delete a client. Deleting a client is only possible if it has no
     * projects, invoices, or estimates associated with it.
     *
     * Returns a 200 OK response code if the call succeeded.
     *
     * @param integer $clientId The client ID.
     *
     * @return mixed
     */
    public function delete($clientId)
    {
        $uri = "clients/" . $clientId;

        return $this->api->delete($uri);
    }
}