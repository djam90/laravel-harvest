<?php

namespace Djam90\Harvest\Services;

use Djam90\Harvest\BaseService;

class ClientContactService extends BaseService
{

    /**
     * Get all contacts.
     *
     * Returns a list of your contacts. The contacts are returned sorted by creation date, with the most recently
     * created contacts appearing first.
     *
     * The response contains an object with a contacts property that contains an array of up to per_page contacts.
     * Each entry in the array is a separate contact object. If no more contacts are available, the resulting array will
     * be empty. Several additional pagination properties are included in the response to simplify paginating your
     * contacts.
     *
     * @param int|null $clientId Only return contacts belonging to the client with the given ID.
     * @param string|null $updatedSince Only return contacts that have been updated since the given date and time.
     * @param int $page The page number to use in pagination.
     * @param int $perPage The number of records to return per page.
     *
     * @return mixed
     */
    public function get($clientId = null, $updatedSince = null, $page = 1, $perPage = 100)
    {
        $uri = "contacts";

        $data = [
            'page' => $page,
            'per_page' => $perPage
        ];

        if (!is_null($clientId)) $data['client_id'] = $clientId;
        if (!is_null($updatedSince)) $data['updated_since'] = $updatedSince;

        return $this->httpGet($uri, $data);
    }

    /**
     * Get a contact by ID.
     *
     * Retrieves the contact with the given ID. Returns a contact object and a 200 OK response code if a valid
     * identifier was provided.
     *
     * @param $contactId
     * @return mixed
     */
    public function getById($contactId)
    {
        $uri = "contacts/" . $contactId;

        return $this->httpGet($uri);
    }

    /**
     * Create a new contact.
     *
     * Creates a new contact object. Returns a contact object and a 201 Created response code if the call succeeded.
     *
     * @param int $clientId The ID of the client associated with this contact.
     * @param string $firstName The first name of the contact.
     * @param string|null $lastName The last name of the contact.
     * @param string|null $title The title of the contact.
     * @param string|null $email The contact’s email address.
     * @param string|null $phoneOffice The contact’s office phone number.
     * @param string|null $phoneMobile The contact’s mobile phone number.
     * @param string|null $fax The contact’s fax number.
     *
     * @return mixed
     */
    public function create($clientId, $firstName, $lastName = null, $title = null, $email = null,
                           $phoneOffice = null, $phoneMobile = null, $fax = null)
    {
        $uri = "contacts";

        $data = [
            'client_id' => $clientId,
            'first_name' => $firstName
        ];

        if (!is_null($lastName)) $data['last_name'] = $lastName;
        if (!is_null($title)) $data['title'] = $title;
        if (!is_null($email)) $data['email'] = $email;
        if (!is_null($phoneOffice)) $data['phone_office'] = $phoneOffice;
        if (!is_null($phoneMobile)) $data['phone_mobile'] = $phoneMobile;
        if (!is_null($fax)) $data['fax'] = $fax;

        return $this->httpPost($uri, $data);
    }

    /**
     * Update a contact.
     *
     * Updates the specific contact by setting the values of the parameters passed. Any parameters not provided will be
     * left unchanged. Returns a contact object and a 200 OK response code if the call succeeded.
     *
     * @param int $contactId The contact ID.
     * @param int $clientId The ID of the client associated with this contact.
     * @param string|null $firstName The first name of the contact.
     * @param string|null $lastName The last name of the contact.
     * @param string|null $title The title of the contact.
     * @param string|null $email The contact’s email address.
     * @param string|null $phoneOffice The contact’s office phone number.
     * @param string|null $phoneMobile The contact’s mobile phone number.
     * @param string|null $fax The contact’s fax number.
     * @return mixed
     */
    public function update($contactId, $clientId, $firstName = null, $lastName = null, $title = null,
                                  $email = null, $phoneOffice = null, $phoneMobile = null, $fax = null)
    {
        $uri = "contacts/" . $contactId;

        $data = [];

        if (!is_null($clientId)) $data['client_id'] = $clientId;
        if (!is_null($firstName)) $data['first_name'] = $firstName;
        if (!is_null($lastName)) $data['last_name'] = $lastName;
        if (!is_null($title)) $data['title'] = $title;
        if (!is_null($email)) $data['email'] = $email;
        if (!is_null($phoneOffice)) $data['phone_office'] = $phoneOffice;
        if (!is_null($phoneMobile)) $data['phone_mobile'] = $phoneMobile;
        if (!is_null($fax)) $data['fax'] = $fax;

        return $this->patch($uri, $data);
    }

    /**
     * Delete a contact.
     *
     * Returns a 200 OK response code if the call succeeded.
     *
     * @param int $contactId The contact ID.
     * @return mixed
     */
    public function delete($contactId)
    {
        $uri = "contacts/" . $contactId;

        return $this->httpDelete($uri);
    }
}