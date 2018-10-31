<?php

namespace Djam90\Harvest\Services;

use Djam90\Harvest\BaseService;

class TaskService extends BaseService
{
    protected $modelClass = \Djam90\Harvest\Models\Task::class;

    protected $path = "tasks";

    /**
     * List all tasks.
     *
     * Returns a list of your tasks. The tasks are returned sorted by creation
     * date, with the most recently created tasks appearing first.
     *
     * The response contains an object with a tasks property that contains an
     * array of up to per_page tasks. Each entry in the array is a separate
     * task object. If no more tasks are available, the resulting array will be
     * empty.
     *
     * Several additional pagination properties are included in the response to
     * simplify paginating your tasks.
     *
     * @param boolean|null $isActive Pass true to only return active tasks and false to return inactive tasks.
     * @param mixed|null $updatedSince Only return tasks that have been updated since the given date and time.
     * @param integer|null $page The page number to use in pagination. For instance, if you make a list request and
     * receive 100 records, your subsequent call can include page=2 to retrieve the next page of the list. (Default: 1)
     * @param integer|null $perPage The number of records to return per page. Can range between 1 and 100. (Default: 100)
     *
     * @return mixed
     */
    public function get($isActive = null, $updatedSince = null, $page = null, $perPage = null)
    {
        $uri = "tasks";

        $data = [
            'page' => $page,
            'per_page' => $perPage
        ];

        if (!is_null($isActive)) $data['is_active'] = $isActive;
        if (!is_null($updatedSince)) $data['updated_since'] = $updatedSince;

        // @todo validate An ISO 8601 formatted string containing a UTC date
        // and time.

        return $this->transformResult($this->api->get($uri, $data));
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
     * Get all tasks
     *
     * @param boolean|null $isActive Pass true to only return active tasks and false to return inactive tasks.
     * @param mixed|null $updatedSince Only return tasks that have been updated since the given date and time.
     *
     * @return mixed
     */
    public function getAll(
        $isActive = null,
        $updatedSince = null
    )
    {
        $batch = $this->get($isActive, $updatedSince);
        $items = $batch->{$this->path};
        $totalPages = $batch->total_pages;

        if ($totalPages > 1) {
            while (!is_null($batch->next_page)) {
                $batch = $this->getPage($isActive, $updatedSince, $batch->next_page);
                $items = $items->merge($batch->{$this->path});
            }
        }
        return $this->transformResult($items);
    }

    /**
     * Retrieve a task.
     *
     * Retrieves the task with the given ID. Returns a task object and a 200 OK
     * response code if a valid identifier was provided.
     *
     * @param integer $taskId The task ID.
     *
     * @return mixed
     */
    public function getById($taskId)
    {
        $uri = "tasks/" . $taskId;

        return $this->transformResult($this->api->get($uri));
    }

    /**
     * Create a task.
     *
     * Creates a new task object. Returns a task object and a 201 Created
     * response code if the call succeeded.
     *
     * @param string $name The name of the task.
     * @param boolean|null $billableByDefault Used in determining whether default tasks should be marked billable when creating a new project. Defaults to true.
     * @param float|null $defaultHourlyRate The default hourly rate to use for this task when it is added to a project. Defaults to 0.
     * @param boolean|null $isDefault Whether this task should be automatically added to future projects. Defaults to false.
     * @param boolean|null $isActive Whether this task is active or archived. Defaults to true.
     *
     * @return mixed
     */
    public function create(
        $name,
        $billableByDefault = null,
        $defaultHourlyRate = null,
        $isDefault = null,
        $isActive = null
    )
    {
        $uri = "tasks";

        $data = [
            'name' => $name,
        ];

        if (!is_null($billableByDefault)) $data['billable_by_default'] =
            $billableByDefault;
        if (!is_null($defaultHourlyRate)) $data['default_hourly_rate'] =
            $defaultHourlyRate;
        if (!is_null($isDefault)) $data['is_default'] = $isDefault;
        if (!is_null($isActive)) $data['is_active'] = $isActive;

        return $this->api->post($uri, $data);
    }

    /**
     * Update a task.
     *
     * Updates the specific task by setting the values of the parameters passed.
     * Any parameters not provided will be left unchanged. Returns a task object
     * and a 200 OK response code if the call succeeded.
     *
     * @param integer $taskId The task ID.
     * @param string|null $name The name of the task.
     * @param boolean|null $billableByDefault Used in determining whether default tasks should be marked billable when
     * creating a new project.
     * @param float|null $defaultHourlyRate The default hourly rate to use for this task when it is added to a project.
     * @param boolean|null $isDefault Whether this task should be automatically added to future projects.
     * @param boolean|null $isActive Whether this task is active or archived.
     *
     * @return mixed
     */
    public function update(
        $taskId,
        $name = null,
        $billableByDefault = null,
        $defaultHourlyRate = null,
        $isDefault = null,
        $isActive = null
    )
    {
        $uri = "tasks/" . $taskId;

        $data = [];

        if (!is_null($name)) $data['name'] = $name;
        if (!is_null($billableByDefault)) $data['billable_by_default'] =
            $billableByDefault;
        if (!is_null($defaultHourlyRate)) $data['default_hourly_rate'] =
            $defaultHourlyRate;
        if (!is_null($isDefault)) $data['is_default'] = $isDefault;
        if (!is_null($isActive)) $data['is_active'] = $isActive;

        return $this->api->patch($uri, $data);
    }

    /**
     * Delete a task.
     *
     * Delete a task. Deleting a task is only possible if it has no time
     * entries associated with it. Returns a 200 OK response code if the call
     * succeeded.
     *
     * @param integer $taskId The task ID.
     *
     * @return mixed
     */
    public function delete($taskId)
    {
        $uri = "tasks/" . $taskId;

        return $this->api->delete($uri);
    }
}
