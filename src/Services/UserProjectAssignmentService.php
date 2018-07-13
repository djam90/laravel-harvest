<?php

namespace Djam90\Harvest\Services;

use Djam90\Harvest\BaseService;

class UserProjectAssignmentService extends BaseService
{
    /**
     * List all project assignments.
     *
     * Returns a list of your project assignments for the user identified by $userId. The project assignments are
     * returned sorted by creation date, with the most recently created project assignments appearing first.
     *
     * The response contains an object with a project_assignments property that contains an array of up to per_page
     * project assignments. Each entry in the array is a separate project assignment object. If no more project
     * assignments are available, the resulting array will be empty.
     *
     * Several additional pagination properties are included in the response to simplify paginating your project
     * assignments.
     *
     * @param integer $userId The user ID.
     * @param mixed|null $updatedSince Only return users that have been updated since the given date and time.
     * @param integer|null $page The page number to use in pagination.
     * @param integer|null $perPage The number of records to return per page.
     *
     * @return mixed
     */
    public function get($userId, $updatedSince = null, $page = null, $perPage = null)
    {
        $uri = "/users/" . $userId . "/project_assignments";

        $data = [];

        if (!is_null($updatedSince)) $data['updated_since'] = $updatedSince;
        if (!is_null($page)) $data['page'] = $page;
        if (!is_null($perPage)) $data['per_page'] = $perPage;

        return $this->httpGet($uri, $data);
    }

    /**
     * List all project assignments for the currently authenticated user.
     *
     * Returns a list of your project assignments for the currently authenticated user. The project assignments are
     * returned sorted by creation date, with the most recently created project assignments appearing first.
     *
     * The response contains an object with a project_assignments property that contains an array of up to per_page
     * project assignments. Each entry in the array is a separate project assignment object. If no more project
     * assignments are available, the resulting array will be empty.
     *
     * Several additional pagination properties are included in the response to simplify paginating your project
     * assignments.
     *
     * @param integer|null $page The page number to use in pagination.
     * @param integer|null $perPage The number of records to return per page.
     *
     * @return mixed
     */
    public function getForCurrentUser($page = null, $perPage = null)
    {
        $uri = "users/me/project_assignments";

        return $this->httpGet($uri);
    }
}