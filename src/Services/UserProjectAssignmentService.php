<?php

namespace Djam90\Harvest\Services;

use Djam90\Harvest\BaseService;

class UserProjectAssignmentService extends BaseService
{
    protected $modelClass = \Djam90\Harvest\Models\UserProjectAssignment::class;

    protected $path = "project_assignments";

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

        return $this->transformResult($this->api->get($uri, $data));
    }

    /**
     * Get a specific page, useful for the getAll() method.
     *
     * @param int|null $userId
     * @param int $page
     * @param int|null $perPage
     * @return mixed
     */
    public function getPage($userId = null, $page, $perPage = null)
    {
        return $this->get($userId, null, $page, $perPage);
    }

    /**
     * Get all user project assignments.
     *
     * @param int|null $userId
     * @return \Djam90\Harvest\Objects\PaginatedCollection|mixed|static
     */
    public function getAll($userId = null)
    {
        if (is_null($userId)) {
            throw new \InvalidArgumentException("UserProjectAssignmentService does not support getAll without a user ID provided.");
        }

        $batch = $this->get($userId);
        $items = $batch->{$this->path};
        $totalPages = $batch->total_pages;

        if ($totalPages > 1) {
            while (!is_null($batch->next_page)) {
                $batch = $this->getPage($userId, $batch->next_page);
                $items = $items->merge($batch->{$this->path});
            }
        }
        return $this->transformResult($items);
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

        return $this->transformResult($this->api->get($uri));
    }
}