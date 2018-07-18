<?php

namespace Djam90\Harvest\Services;

use Djam90\Harvest\BaseService;

class ClientContactService extends BaseService
{
    protected $path = 'contacts';

    protected $modelClass = \Djam90\Harvest\Models\Contact::class;

    /**
     * Get all contacts.
     *
     * Returns a list of your contacts. The contacts are returned sorted by
     * creation date, with the most recently created contacts appearing first.
     *
     * The response contains an object with a contacts property that contains
     * an array of up to per_page contacts. Each entry in the array is a
     * separate contact object. If no more contacts are available, the
     * resulting array will be empty.
     *
     * Several additional pagination properties are included in the response to
     * simplify paginating your contacts.
     *
     * @param integer|null $clientId Only return contacts belonging to the client with the given ID.
     * @param string|null $updatedSince Only return contacts that have been updated since the given date and time.
     * @param integer|null $page The page number to use in pagination.
     * @param integer|null $perPage The number of records to return per page.
     *
     * @return mixed
     */
    public function get($clientId = null, $updatedSince = null, $page = null,
                        $perPage = null)
    {
        $uri = "contacts";

        $data = [];

        if (!is_null($clientId)) $data['client_id'] = $clientId;
        if (!is_null($updatedSince)) $data['updated_since'] = $updatedSince;
        if (!is_null($page)) $data['page'] = $page;
        if (!is_null($perPage)) $data['per_page'] = $perPage;

        return $this->transformResult($this->api->get($uri, $data));
    }

    /**
     * Get a contact by ID.
     *
     * Retrieves the contact with the given ID. Returns a contact object and a
     * 200 OK response code if a valid identifier was provided.
     *
     * @param integer $contactId The contact ID.
     *
     * @return mixed
     */
    public function getById($contactId)
    {
        $uri = "contacts/" . $contactId;

        return $this->transformResult($this->api->get($uri));
    }

    /**
     * Create a new contact.
     *
     * Creates a new contact object. Returns a contact object and a 201 Created
     * response code if the call succeeded.
     *
     * @param integer $clientId The ID of the client associated with this contact.
     * @param string $firstName The first name of the contact.
     * @param string|null $lastName The last name of the contact.
     * @param string|null $title The title of the contact.
     * @param string|null $email The contact's email address.
     * @param string|null $phoneOffice The contact's office phone number.
     * @param string|null $phoneMobile The contact's mobile phone number.
     * @param string|null $fax The contact's fax number.
     *
     * @return mixed
     */
    public function create($clientId, $firstName, $lastName = null,
                           $title = null, $email = null, $phoneOffice = null,
                           $phoneMobile = null, $fax = null)
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

        return $this->api->post($uri, $data);
    }

    /**
     * Update a contact.
     *
     * Updates the specific contact by setting the values of the parameters
     * passed. Any parameters not provided will be left unchanged.
     *
     * Returns a contact object and a 200 OK response code if the call
     * succeeded.
     *
     * @param integer $contactId The contact ID.
     * @param integer $clientId The ID of the client associated with this contact.
     * @param string|null $firstName The first name of the contact.
     * @param string|null $lastName The last name of the contact.
     * @param string|null $title The title of the contact.
     * @param string|null $email The contact's email address.
     * @param string|null $phoneOffice The contact's office phone number.
     * @param string|null $phoneMobile The contact's mobile phone number.
     * @param string|null $fax The contact's fax number.
     *
     * @return mixed
     */
    public function update($contactId, $clientId, $firstName = null,
                           $lastName = null, $title = null, $email = null,
                           $phoneOffice = null, $phoneMobile = null,
                           $fax = null)
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

        return $this->api->patch($uri, $data);
    }

    /**
     * Delete a contact.
     *
     * Returns a 200 OK response code if the call succeeded.
     *
     * @param integer $contactId The contact ID.
     *
     * @return mixed
     */
    public function delete($contactId)
    {
        $uri = "contacts/" . $contactId;

        return $this->api->delete($uri);
    }
}