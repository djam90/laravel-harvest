<?php

namespace Djam90\Harvest\Services;

use Djam90\Harvest\BaseService;

class ClientService extends BaseService
{
    /**
     * Get clients.
     *
     * Returns a list of your clients. The clients are returned sorted by creation date, with the most recently created
     * clients appearing first.
     *
     * @param bool|null $isActive Pass true to only return active clients and false to return inactive clients.
     * @param string|null $updatedSince Only return clients that have been updated since the given date and time.
     * @param int $page The page number to use in pagination.
     * @param int $perPage The number of records to return per page.
     *
     * @return mixed
     */
    public function get($isActive = null, $updatedSince = null, $page = 1, $perPage = 100)
    {
        $uri = "clients";

        $data = [
            'page' => $page,
            'per_page' => $perPage
        ];

        if (!is_null($isActive)) $data['is_active'] = $isActive;
        if (!is_null($updatedSince)) $data['updated_since'] = $updatedSince;

        // @todo validate An ISO 8601 formatted string containing a UTC date and time.

        return $this->httpGet($uri, $data);
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

        return $this->httpGet($uri);
    }

    /**
     * Create a client.
     *
     * Creates a new client object. Returns a client object and a 201 Created response code if the call succeeded.
     *
     * @param string $name A textual description of the client.
     * @param bool|null $isActive Whether the client is active, or archived. Defaults to true.
     * @param string|null $address A textual representation of the client’s physical address. May include new line characters.
     * @param string|null $currency The currency used by the client. If not provided, the company’s currency will be used.
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

        return $this->httpPost($uri, $data);
    }

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

        return $this->patch($uri, $data);
    }

    public function delete($clientId)
    {
        $uri = "clients/" . $clientId;

        return $this->httpDelete($uri);
    }
}