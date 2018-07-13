<?php

namespace Djam90\Harvest\Services;

use Djam90\Harvest\BaseService;

class RoleService extends BaseService
{
    /**
     * List all roles.
     *
     * Returns a list of roles in the account. The roles are returned sorted by
     * creation date, with the most recently created roles appearing first.
     *
     * The response contains an object with a roles property that contains an
     * array of up to per_page roles. Each entry in the array is a separate
     * role object. If no more roles are available, the resulting array will be
     * empty.
     *
     * Several additional pagination properties are included in the
     * response to simplify paginating your roles.
     *
     * @param integer|null $page The page number to use in pagination.
     * @param integer|null $perPage The number of records to return per page.
     *
     * @return mixed
     */
    public function get($page = null, $perPage = null)
    {
        $uri = "roles";

        $data = [];

        if (!is_null($page)) $data['page'] = $page;
        if (!is_null($perPage)) $data['per_page'] = $perPage;

        return $this->httpGet($uri, $data);
    }

    /**
     * Get a role by ID.
     *
     * Retrieves the role with the given ID.
     *
     * @param integer $roleId The role ID.
     *
     * @return mixed
     */
    public function getById($roleId)
    {
        $uri = "roles/" . $roleId;

        return $this->httpGet($uri);
    }

    /**
     * Create a role.
     *
     * Creates a new role object. Returns a role object and a 201 Created
     * response code if the call succeeded.
     *
     * @param string $name The name of the role.
     * @param array|null $userIds The IDs of the users assigned to this role.
     *
     * @return mixed
     */
    public function create($name, $userIds = null)
    {
        $uri = "roles";

        $data = [
            'name' => $name,
        ];

        if (!is_null($userIds)) $data['user_ids'] = $userIds;

        return $this->httpPost($uri, $data);
    }

    /**
     * Update a role.
     *
     * Updates the specific role by setting the values of the parameters
     * passed. Any parameters not provided will be left unchanged.
     *
     * Returns a role object and a 200 OK response code if the call succeeded.
     *
     * @param integer $roleId The role ID.
     * @param string $name A textual description of the role.
     * @param array|null $userIds The IDs of the users assigned to this role.
     *
     * @return mixed
     */
    public function update($roleId, $name, $userIds = null)
    {
        $uri = "roles/" . $roleId;

        $data = [
            'name' => $name
        ];

        if (!is_null($userIds)) $data['user_ids'] = $userIds;

        return $this->httpPatch($uri, $data);
    }

    /**
     * Delete a role.
     *
     * Delete a role. Deleting a role will unlink it from any users it was
     * assigned to. Returns a 200 OK response code if the call succeeded.
     *
     * @param integer $roleId The role ID.
     *
     * @return mixed
     */
    public function delete($roleId)
    {
        $uri = "roles/" . $roleId;

        return $this->httpDelete($uri);
    }
}