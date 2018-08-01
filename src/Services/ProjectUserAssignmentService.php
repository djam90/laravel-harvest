<?php

namespace Djam90\Harvest\Services;

use Djam90\Harvest\BaseService;

class ProjectUserAssignmentService extends BaseService
{
    protected $modelClass = \Djam90\Harvest\Models\ProjectUserAssignment::class;

    protected $path = "user_assignments";

    /**
     * List all user assignments.
     *
     * Returns a list of your user assignments for the project identified by
     * $projectId. The user assignments are returned sorted by creation date,
     * with the most recently created user assignments appearing first.
     *
     * The response contains an object with a user_assignments property that
     * contains an array of up to per_page user assignments. Each entry in the
     * array is a separate user assignment object. If no more user assignments
     * are available, the resulting array will be empty.
     *
     * Several additional pagination properties are included in the response to
     * simplify paginating your user assignments.
     *
     * @param integer $projectId The project ID.
     * @param boolean|null $isActive Pass true to only return active user assignments and false to return inactive user assignments.
     * @param mixed|null $updatedSince Only return user assignments that have been updated since the given date and time.
     * @param integer|null $page The page number to use in pagination. For instance, if you make a list request and
     * receive 100 records, your subsequent call can include page=2 to retrieve the next page of the list. (Default: 1)
     * @param integer|null $perPage The number of records to return per page. Can range between 1 and 100. (Default:
     * 100)
     *
     * @return mixed
     */
    public function get($projectId, $isActive = null, $updatedSince = null, $page = null, $perPage = null)
    {
        $uri = "projects/" . $projectId . "/user_assignments";

        $data = [];

        if (!is_null($isActive)) $data['is_active'] = $isActive;
        if (!is_null($updatedSince)) $data['updated_since'] = $updatedSince;
        if (!is_null($page)) $data['page'] = $page;
        if (!is_null($perPage)) $data['per_page'] = $perPage;

        // @todo validate An ISO 8601 formatted string containing a UTC date
        // and time.

        return $this->transformResult($this->api->get($uri, $data));
    }

    /**
     * Get a specific page, useful for the getAll() method.
     *
     * @param int $projectId
     * @param int $page
     * @param int|null $perPage
     * @return mixed
     */
    public function getPage($projectId, $page, $perPage = null)
    {
        return $this->get($projectId, null, null, $page, $perPage);
    }

    /**
     * Get all project user assignments.
     *
     * @param int|null $projectId
     * @return \Djam90\Harvest\Objects\PaginatedCollection|mixed|static
     */
    public function getAll($projectId = null)
    {
        if (is_null($projectId)) {
            throw new \InvalidArgumentException("ProjectUserAssignmentService does not support getAll without a project ID provided.");
        }

        $batch = $this->get($projectId);
        $items = $batch->{$this->path};
        $totalPages = $batch->total_pages;

        if ($totalPages > 1) {
            while (!is_null($batch->next_page)) {
                $batch = $this->getPage($projectId, $batch->next_page);
                $items = $items->merge($batch->{$this->path});
            }
        }
        return $this->transformResult($items);
    }

    /**
     * Retrieve a user assignment.
     *
     * Retrieves the user assignment with the given ID. Returns a user
     * assignment object and a 200 OK response code if a valid identifier was
     * provided.
     *
     * @param integer $projectId The project ID.
     * @param integer $userAssignmentId The user assignment ID.
     *
     * @return mixed
     */
    public function getById($projectId, $userAssignmentId)
    {
        $uri = "projects/" . $projectId . "/user_assignments/" .
            $userAssignmentId;

        return $this->transformResult($this->api->get($uri));
    }

    /**
     * Create a user assignment.
     *
     * Creates a new user assignment object. Returns a user assignment object
     * and a 201 Created response code if the call succeeded.
     *
     * @param integer $projectId The project ID.
     * @param integer $userId The user ID.
     * @param boolean|null $isActive Whether the user assignment is active or archived. Defaults to true.
     * @param mixed|null $isProjectManager Determines if the user has project manager permissions for the project.
     * Defaults to false for users with Regular User permissions and true for those with Project Managers or
     * Administrator permissions.
     * @param float|null $hourlyRate Rate used when the project's bill_by is People. Defaults to 0.
     * @param float|null $budget Budget used when the project's budget_by is person.
     *
     * @return mixed
     */
    public function create($projectId, $userId, $isActive = null,
                           $isProjectManager = null, $hourlyRate = null,
                           $budget = null)
    {
        $uri = "projects/" . $projectId . "/user_assignments";

        $data = [
            'user_id' => $userId,
        ];

        if (!is_null($isActive)) $data['is_active'] = $isActive;
        if (!is_null($isProjectManager)) $data['is_project_manager'] =
            $isProjectManager;
        if (!is_null($hourlyRate)) $data['hourly_rate'] = $hourlyRate;
        if (!is_null($budget)) $data['budget'] = $budget;

        return $this->api->post($uri, $data);
    }

    /**
     * Update a user assignment.
     *
     * Updates the specific user assignment by setting the values of the
     * parameters passed. Any parameters not provided will be left unchanged.
     *
     * Returns a user assignment object and a 200 OK response code if the call
     * succeeded.
     *
     * @param integer $projectId The project ID.
     * @param integer $userAssignmentId The user assignment ID.
     * @param boolean|null $isActive Whether the user assignment is active or archived.
     * @param boolean|null $isProjectManager Determines if the user has project manager permissions for the project.
     * @param float|null $hourlyRate Rate used when the project's bill_by is People.
     * @param float|null $budget Budget used when the project's budget_by is person.
     *
     * @return mixed
     */
    public function update($projectId, $userAssignmentId, $isActive = null,
                           $isProjectManager = null, $hourlyRate = null,
                           $budget = null)
    {
        $uri = "projects/" . $projectId . "/user_assignments/" .
            $userAssignmentId;

        $data = [];

        if (!is_null($isActive)) $data['is_active'] = $isActive;
        if (!is_null($isProjectManager)) $data['is_project_manager'] =
            $isProjectManager;
        if (!is_null($hourlyRate)) $data['hourly_rate'] = $hourlyRate;
        if (!is_null($budget)) $data['budget'] = $budget;

        return $this->api->patch($uri, $data);
    }

    /**
     * Delete a user assignment.
     *
     * Delete a user assignment. Deleting a user assignment is only possible if
     * it has no time entries or expenses associated with it.
     *
     * Returns a 200 OK response code if the call succeeded.
     *
     * @param integer $projectId The project ID.
     * @param integer $userAssignmentId The user assignment ID.
     *
     * @return mixed
     */
    public function delete($projectId, $userAssignmentId)
    {
        $uri = "projects/" . $projectId . "/user_assignments/" .
            $userAssignmentId;

        return $this->api->delete($uri);
    }
}