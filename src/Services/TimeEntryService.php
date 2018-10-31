<?php

namespace Djam90\Harvest\Services;

use Djam90\Harvest\BaseService;

class TimeEntryService extends BaseService
{
    protected $modelClass = \Djam90\Harvest\Models\TimeEntry::class;

    protected $path = "time_entries";

    /**
     * List all time entries.
     *
     * Returns a list of your time entries. The time entries are returned
     * sorted by creation date, with the most recently created time entries
     * appearing first.
     *
     * The response contains an object with a time_entries property that
     * contains an array of up to per_page time entries. Each entry in the
     * array is a separate time entry object. If no more time entries are
     * available, the resulting array will be empty.
     *
     * Several additional pagination properties are included in the response to
     * simplify paginating your time entries.
     *
     * @param integer $userId The user ID.
     * @param integer $clientId The client ID.
     * @param integer $projectId The project ID.
     * @param boolean|null $isBilled Pass true to only return time entries that have been invoiced and false to return time entries that have not been invoiced.
     * @param boolean|null $isRunning Pass true to only return running time entries and false to return non-running time entries.
     * @param mixed|null $updatedSince Only return time entries that have been updated since the given date and time.
     * @param mixed|null $from Only return time entries with a spent_date on or after the given date.
     * @param mixed|null $to Only return time entries with a spent_date on or before the given date.
     * @param integer|null $page The page number to use in pagination. For instance, if you make a list request and
     * receive 100 records, your subsequent call can include page=2 to retrieve the next page of the list. (Default: 1)
     * @param integer|null $perPage The number of records to return per page. Can range between 1 and 100. (Default:
     * 100)
     *
     * @return mixed
     */
    public function get(
        $userId,
        $clientId,
        $projectId,
        $isBilled = null,
        $isRunning = null,
        $updatedSince = null,
        $from = null,
        $to = null,
        $page = null,
        $perPage = null
    )
    {
        $uri = "time_entries";

        $data = [
            'user_id' => $userId,
            'client_id' => $clientId,
            'project_id' => $projectId,
        ];

        if (!is_null($isBilled)) $data['is_billed'] = $isBilled;
        if (!is_null($isRunning)) $data['is_running'] = $isRunning;
        if (!is_null($updatedSince)) $data['updated_since'] = $updatedSince;
        if (!is_null($from)) $data['from'] = $from;
        if (!is_null($to)) $data['to'] = $to;
        if (!is_null($page)) $data['page'] = $page;
        if (!is_null($perPage)) $data['per_page'] = $perPage;

        // @todo validate An ISO 8601 formatted string containing a UTC date
        // and time.

        return $this->transformResult($this->api->get($uri, $data));
    }

    /**
     * Get a specific page, useful for the getAll() method.
     *
     * @param null $userId
     * @param null $clientId
     * @param null $projectId
     * @param int $page
     * @param int|null $perPage
     * @return mixed
     */
    public function getPage($userId = null, $clientId = null, $projectId = null, $page, $perPage = null)
    {
        return $this->get($userId, $clientId, $projectId, null, null, null, null, null, $page, $perPage);
    }

    /**
     * Get the last page, useful for getting the last item.
     *
     * @param int|null $userId
     * @param int|null $clientId
     * @param int|null $projectId
     * @return \Djam90\Harvest\Objects\PaginatedCollection|mixed
     */
    public function getLastPage($userId = null, $clientId = null, $projectId = null)
    {
        $batch = $this->getPage($userId, $clientId, $projectId, 1, 1);
        $totalPages = $batch->total_pages;

        if ($totalPages > 1) {
            return $this->getPage($userId, $clientId, $projectId, $totalPages, 1);
        }

        return $batch;
    }

    /**
     * Get all time entries.
     *
     * @param int|null $userId
     * @param int|null $clientId
     * @param int|null $projectId
     * @param boolean|null $isBilled Pass true to only return time entries that have been invoiced and false to return time entries that have not been invoiced.
     * @param boolean|null $isRunning Pass true to only return running time entries and false to return non-running time entries.
     * @param mixed|null $updatedSince Only return time entries that have been updated since the given date and time.
     * @param mixed|null $from Only return time entries with a spent_date on or after the given date.
     * @param mixed|null $to Only return time entries with a spent_date on or before the given date.
     * @return \Djam90\Harvest\Objects\PaginatedCollection|mixed|static
     */
    public function getAll(
        $userId = null,
        $clientId = null,
        $projectId = null,
        $isBilled = null,
        $isRunning = null,
        $updatedSince = null,
        $from = null,
        $to = null
    )
    {
        if (is_null($userId) && is_null($clientId) && is_null($projectId)) {
            throw new \InvalidArgumentException("TimeEntryService does not support getAll without a user ID, client ID or project ID provided.");
        }

        $batch = $this->get($userId, $clientId, $projectId, $isBilled, $isRunning, $updatedSince, $from, $to);
        $items = $batch->{$this->path};
        $totalPages = $batch->total_pages;

        if ($totalPages > 1) {
            while (!is_null($batch->next_page)) {
                $batch = $this->getPage($userId, $clientId, $projectId, $batch->next_page);
                $items = $items->merge($batch->{$this->path});
            }
        }
        return $this->transformResult($items);
    }

    /**
     * Retrieve a time entry.
     *
     * Retrieves the time entry with the given ID. Returns a time entry object
     * and a 200 OK response code if a valid identifier was provided.
     *
     * @param integer $timeEntryId The time entry ID.
     *
     * @return mixed
     */
    public function getById($timeEntryId)
    {
        $uri = "time_entries/" . $timeEntryId;

        return $this->transformResult($this->api->get($uri));
    }

    /**
     * Create a time entry via duration.
     *
     * Creates a new time entry object. Returns a time entry object and a 201
     * Created response code if the call succeeded.
     *
     * You should only use this method to create time entries when your account
     * is configured to track time via duration. You can verify this by
     * visiting the Settings page in your Harvest account or by checking if
     * wants_timestamp_timers is false in the Company API.
     *
     * @param integer $projectId The project ID.
     * @param integer $taskId The task ID.
     * @param mixed $spentDate The ISO 8601 formatted date the time entry was spent.
     * @param integer|null $userId The ID of the user to associate with the time entry. Defaults to the currently authenticated user's ID.
     * @param float|null $hours The current amount of time tracked. If provided, the time entry will be created with
     * the specified hours and is_running will be set to false. If not provided, hours will be set to 0.0 and
     * is_running will be set to true.
     * @param string|null $notes Any notes to be associated with the time entry.
     * @param object|null $externalReference An object containing the id, group_id, and permalink of the external reference.
     *
     * @return mixed
     */
    public function createForDuration(
        $projectId,
        $taskId,
        $spentDate,
        $userId = null,
        $hours = null,
        $notes = null,
        $externalReference = null
    )
    {
        $uri = "time_entries";

        $data = [
            'project_id' => $projectId,
            'task_id' => $taskId,
            'spent_date' => $spentDate,
        ];

        if (!is_null($userId)) $data['user_id'] = $userId;
        if (!is_null($hours)) $data['hours'] = $hours;
        if (!is_null($notes)) $data['notes'] = $notes;
        if (!is_null($externalReference)) $data['external_reference'] =
            $externalReference;

        return $this->api->post($uri, $data);
    }

    /**
     * Create a time entry via start and end time.
     *
     * Creates a new time entry object. Returns a time entry object and a 201
     * Created response code if the call succeeded.
     *
     * You should only use this method to create time entries when your account
     * is configured to track time via start and end time. You can verify this
     * by visiting the Settings page in your Harvest account or by checking if
     * wants_timestamp_timers is true in the Company API.
     *
     * @param integer $projectId The project ID.
     * @param integer $taskId The task ID.
     * @param mixed $spentDate The ISO 8601 formatted date the time entry was spent.
     * @param integer|null $userId The user ID.
     * @param mixed|null $startedTime The time the entry started. Defaults to the current time. Example: “8:00am”.
     * @param mixed|null $endedTime The time the entry ended. If provided, is_running will be set to false. If not provided,
     * is_running will be set to true.
     * @param string|null $notes Any notes to be associated with the time entry.
     * @param object|null $externalReference An object containing the id, group_id, and permalink of the external reference.
     *
     * @return mixed
     */
    public function createForStartAndEndTime(
        $projectId,
        $taskId,
        $spentDate,
        $userId = null,
        $startedTime = null,
        $endedTime = null,
        $notes = null,
        $externalReference = null
    )
    {
        $uri = "time_entries";

        $data = [
            'project_id' => $projectId,
            'task_id' => $taskId,
            'spent_date' => $spentDate,
        ];

        if (!is_null($userId)) $data['user_id'] = $userId;
        if (!is_null($startedTime)) $data['started_time'] = $startedTime;
        if (!is_null($endedTime)) $data['ended_time'] = $endedTime;
        if (!is_null($notes)) $data['notes'] = $notes;
        if (!is_null($externalReference)) $data['external_reference'] =
            $externalReference;

        return $this->api->post($uri, $data);
    }

    /**
     * Update a time entry.
     *
     * Updates the specific time entry by setting the values of the parameters
     * passed. Any parameters not provided will be left unchanged.
     *
     * Returns a time entry object and a 200 OK response code if the call
     * succeeded.
     *
     * @param integer $timeEntryId The time entry ID.
     * @param integer|null $projectId The project ID.
     * @param integer|null $taskId The task ID.
     * @param mixed|null $spentDate The ISO 8601 formatted date the time entry was spent.
     * @param mixed|null $startedTime The time the entry started. Defaults to the current time. Example: “8:00am”.
     * @param mixed|null $endedTime The time the entry ended.
     * @param float|null $hours The current amount of time tracked.
     * @param string|null $notes Any notes to be associated with the time entry.
     * @param object|null $externalReference An object containing the id, group_id, and permalink of the external
     * reference.
     *
     * @return mixed
     */
    public function update(
        $timeEntryId,
        $projectId = null,
        $taskId = null,
        $spentDate = null,
        $startedTime = null,
        $endedTime = null,
        $hours = null, $notes = null,
        $externalReference = null
    )
    {
        $uri = "time_entries/" . $timeEntryId;

        $data = [];

        if (!is_null($projectId)) $data['project_id'] = $projectId;
        if (!is_null($taskId)) $data['task_id'] = $taskId;
        if (!is_null($spentDate)) $data['task_id'] = $spentDate;
        if (!is_null($startedTime)) $data['started_time'] = $startedTime;
        if (!is_null($endedTime)) $data['ended_time'] = $endedTime;
        if (!is_null($hours)) $data['hours'] = $hours;
        if (!is_null($notes)) $data['notes'] = $notes;
        if (!is_null($externalReference)) $data['external_reference'] =
            $externalReference;

        return $this->api->patch($uri, $data);
    }

    /**
     * Delete a time entry.
     *
     * Delete a time entry. Deleting a time entry is only possible if it's not
     * closed and the associated project and task haven't been archived.
     *
     * However, Admins can delete closed entries. Returns a 200 OK response
     * code if the call succeeded.
     *
     * @param integer $timeEntryId The time entry ID.
     *
     * @return mixed
     */
    public function delete($timeEntryId)
    {
        $uri = "time_entries/" . $timeEntryId;

        return $this->api->delete($uri);
    }

    /**
     * Restart a stopped time entry.
     *
     * Restarting a time entry is only possible if it isn't currently running.
     * Returns a 200 OK response code if the call succeeded.
     *
     * @param integer $timeEntryId The time entry ID.
     *
     * @return mixed
     */
    public function restart($timeEntryId)
    {
        $uri = "time_entries/" . $timeEntryId . "/restart";

        $data = [];

        return $this->api->patch($uri, $data);
    }

    /**
     * Stop a running time entry.
     *
     * Stopping a time entry is only possible if it's currently running.
     * Returns a 200 OK response code if the call succeeded.
     *
     * @param integer $timeEntryId The time entry ID.
     *
     * @return mixed
     */
    public function stop($timeEntryId)
    {
        $uri = "time_entries/" . $timeEntryId . "/stop";

        $data = [];

        return $this->api->patch($uri, $data);
    }
}
