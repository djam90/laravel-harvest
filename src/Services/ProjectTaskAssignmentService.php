<?php

namespace Djam90\Harvest\Services;

use Djam90\Harvest\BaseService;

/**
 * Admin or project manager permissions required.
 */
class ProjectUserAssignmentService extends BaseService
{
    /**
     * List all task assignments.
     *
     * Returns a list of your task assignments for the project identified by
     * $projectId. The task assignments are returned sorted by creation date,
     * with the most recently created task assignments appearing first.
     *
     * The response contains an object with a task_assignments property that
     * contains an array of up to $perPage task assignments. Each entry in the
     * array is a separate task assignment object. If no more task assignments
     * are available, the resulting array will be empty.
     *
     * Several additional pagination properties are included in the response to
     * simplify paginating your task assignments.
     *
     * @param integer $projectId The project ID.
     * @param boolean|null $isActive Pass true to only return active task assignments and false to return inactive task
     * assignments.
     * @param mixed|null $updatedSince Only return task assignments that have been updated since the given date and
     * time.
     * @param integer|null $page The page number to use in pagination. For instance, if you make a list request and
     * receive 100 records, your subsequent call can include page=2 to retrieve the next page of the list. (Default: 1)
     * @param integer|null $perPage The number of records to return per page. Can range between 1 and 100. (Default:
     * 100)
     *
     * @return mixed
     */
    public function get($projectId, $isActive = null, $updatedSince = null,
                        $page = null, $perPage = null)
    {
        $uri = "projects/" . $projectId . "/task_assignments";

        $data = [];

        if (!is_null($isActive)) $data['is_active'] = $isActive;
        if (!is_null($updatedSince)) $data['updated_since'] = $updatedSince;
        if (!is_null($page)) $data['page'] = $page;
        if (!is_null($perPage)) $data['per_page'] = $perPage;

        // @todo validate An ISO 8601 formatted string containing a UTC date
        // and time.

        return $this->api->get($uri, $data);
    }

    /**
     * Retrieve a task assignment.
     *
     * Retrieves the task assignment with the given ID. Returns a task
     * assignment object and a 200 OK response code if a valid identifier was
     * provided.
     *
     * @param integer $projectId The project ID.
     * @param integer $taskAssignmentId The task assignment ID.
     *
     * @return mixed
     */
    public function getById($projectId, $taskAssignmentId)
    {
        $uri = "projects/" . $projectId . "/task_assignments/" .
            $taskAssignmentId;

        return $this->api->get($uri);
    }

    /**
     * Create a task assignment.
     *
     * Creates a new task assignment object. Returns a task assignment object
     * and a 201 Created response code if the call succeeded.
     *
     * @param integer $projectId The project ID.
     * @param integer $taskId The task ID.
     * @param boolean|null $isActive Whether the task assignment is active or archived. Defaults to true.
     * @param boolean|null $billable Whether the task assignment is billable or not. Defaults to false.
     * @param float|null $hourlyRate Rate used when the project's bill_by is Tasks. Defaults to null when billing by
     * task hourly rate, otherwise 0.
     * @param float|null $budget Budget used when the project's budget_by is task or task_fees.
     *
     * @return mixed
     */
    public function create($projectId, $taskId, $isActive = null,
                           $billable = null, $hourlyRate = null, $budget = null)
    {
        $uri = "projects/" . $projectId . "/task_assignments";

        $data = [
            'task_id' => $taskId,
        ];

        if (!is_null($isActive)) $data['is_active'] = $isActive;
        if (!is_null($billable)) $data['billable'] = $billable;
        if (!is_null($hourlyRate)) $data['hourly_rate'] = $hourlyRate;
        if (!is_null($budget)) $data['budget'] = $budget;

        return $this->api->post($uri, $data);
    }

    /**
     * Update a task assignment.
     *
     * Updates the specific task assignment by setting the values of the
     * parameters passed. Any parameters not provided will be left
     * unchanged.
     *
     * Returns a task assignment object and a 200 OK response code if the call
     * succeeded.
     *
     * @param integer $projectId The project ID.
     * @param integer $taskAssignmentId The task assignment ID.
     * @param boolean|null $isActive Whether the task assignment is active or archived.
     * @param boolean|null $billable Whether the task assignment is billable or not.
     * @param float|null $hourlyRate Rate used when the project's bill_by is Tasks.
     * @param float|null $budget Budget used when the project's budget_by is task or task_fees.
     *
     * @return mixed
     */
    public function update($projectId, $taskAssignmentId, $isActive = null,
                           $billable = null, $hourlyRate = null,
                           $budget = null)
    {
        $uri = "projects/" . $projectId . "/task_assignments/" .
            $taskAssignmentId;

        $data = [];

        if (!is_null($isActive)) $data['is_active'] = $isActive;
        if (!is_null($billable)) $data['billable'] = $billable;
        if (!is_null($hourlyRate)) $data['hourly_rate'] = $hourlyRate;
        if (!is_null($budget)) $data['budget'] = $budget;

        return $this->api->patch($uri, $data);
    }

    /**
     * Delete a task assignment.
     *
     * Delete a task assignment. Deleting a task assignment is only possible if
     * it has no time entries associated with it.
     *
     * Returns a 200 OK response code if the call succeeded.
     *
     * @param integer $projectId The project ID.
     * @param integer $taskAssignmentId The task assignment ID.
     * @return mixed
     */
    public function delete($projectId, $taskAssignmentId)
    {
        $uri = "projects/" . $projectId . "/task_assignments/" .
            $taskAssignmentId;

        return $this->api->delete($uri);
    }
}