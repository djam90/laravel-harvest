<?php

namespace Djam90\Harvest\Services;

use Djam90\Harvest\BaseService;

class ProjectService extends BaseService
{
    protected $modelClass = \Djam90\Harvest\Models\Project::class;

    protected $path = "projects";

    /**
     * List all projects.
     *
     * @param boolean|null $isActive Pass true to only return active projects and false to return inactive projects.
     * @param integer|null $clientId Only return projects belonging to the client with the given ID.
     * @param mixed|null $updatedSince Only return projects that have been updated since the given date and time.
     * @param integer|null $page The page number to use in pagination.
     * @param integer|null $perPage The number of records to return per page.
     *
     * @return mixed
     */
    public function get($isActive = null, $clientId = null, $updatedSince = null, $page = null, $perPage = null)
    {
        $uri = "projects";

        $data = [];

        if (!is_null($isActive)) $data['is_active'] = $isActive;
        if (!is_null($clientId)) $data['client_id'] = $clientId;
        if (!is_null($updatedSince)) $data['updated_since'] = $updatedSince;
        if (!is_null($page)) $data['page'] = $page;
        if (!is_null($perPage)) $data['per_page'] = $perPage;

        // @todo validate An ISO 8601 formatted string containing a UTC date
        // and time.

        $projects = $this->api->get($uri, $data);

        return $this->transformResult($projects);
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
        return $this->get(null, null, null, $page, $perPage);
    }

    /**
     *  Retrieve a project.
     *
     * Retrieves the project with the given ID. Returns a project object and a 200 OK response code if a valid
     * identifier was provided.
     *
     * @param integer $projectId The project ID.
     *
     * @return mixed
     */
    public function getById($projectId)
    {
        $uri = "projects/" . $projectId;

        return $this->transformResult($this->api->get($uri));
    }

    /**
     * Create a project.
     *
     * Creates a new project object. Returns a project object and a 201 Created response code if the call succeeded.
     *
     * @param integer $clientId The client ID.
     * @param string $name The name of the project.
     * @param boolean $isBillable Whether the project is billable or not.
     * @param string $billBy The method by which the project is invoiced. Options: Project, Tasks, People, or none.
     * @param string $budgetBy The method by which the project is budgeted. Options: project (Hours Per Project),
     * project_cost (Total Project Fees), task (Hours Per Task), task_fees (Fees Per Task), person (Hours Per Person),
     * none (No Budget).
     * @param string|null $code The code associated with the project.
     * @param boolean|null $isActive Whether the project is active or archived. Defaults to true.
     * @param boolean|null $isFixedFee Whether the project is a fixed-fee project or not.
     * @param float|null $hourlyRate Rate for projects billed by Project Hourly Rate.
     * @param float|null $budget The budget in hours for the project when budgeting by time.
     * @param boolean|null $notifyWhenOverBudget Whether project managers should be notified when the project goes over budget. Defaults to false.
     * @param float|null $overBudgetNotificationPercentage Percentage value used to trigger over budget email alerts. Example: use 10.0 for 10.0%.
     * @param boolean|null $showBudgetToAll Option to show project budget to all employees. Does not apply to Total Project Fee projects. Defaults to false.
     * @param float|null $costBudget The monetary budget for the project when budgeting by money.
     * @param boolean|null $costBudgetIncludeExpenses Option for budget of Total Project Fees projects to include tracked expenses. Defaults to false.
     * @param float|null $fee The amount you plan to invoice for the project. Only used by fixed-fee projects.
     * @param string|null $notes Project notes.
     * @param mixed|null $startsOn Date the project was started.
     * @param mixed|null $endsOn Date the project will end.
     *
     * @return mixed
     */
    public function create(
        $clientId,
        $name,
        $isBillable,
        $billBy,
        $budgetBy,
        $code = null,
        $isActive = null,
        $isFixedFee = null,
        $hourlyRate = null,
        $budget = null,
        $notifyWhenOverBudget = null,
        $overBudgetNotificationPercentage = null,
        $showBudgetToAll = null,
        $costBudget = null,
        $costBudgetIncludeExpenses = null,
        $fee = null,
        $notes = null,
        $startsOn = null,
        $endsOn = null
    )
    {
        $uri = "projects";

        $data = [
            'client_id' => $clientId,
            'name' => $name,
            'is_billable' => $isBillable,
            'bill_by' => $billBy,
            'budget_by' => $budgetBy
        ];

        if (!is_null($code)) $data['code'] = $code;
        if (!is_null($isActive)) $data['is_active'] = $isActive;
        if (!is_null($isFixedFee)) $data['is_fixed_fee'] = $isFixedFee;
        if (!is_null($hourlyRate)) $data['hourly_rate'] = $hourlyRate;
        if (!is_null($budget)) $data['budget'] = $budget;
        if (!is_null($notifyWhenOverBudget)) $data['notify_when_over_budget'] = $notifyWhenOverBudget;
        if (!is_null($overBudgetNotificationPercentage)) $data['over_budget_notification_percentage'] = $overBudgetNotificationPercentage;
        if (!is_null($showBudgetToAll)) $data['show_budget_to_all'] = $showBudgetToAll;
        if (!is_null($costBudget)) $data['cost_budget'] = $costBudget;
        if (!is_null($costBudgetIncludeExpenses)) $data['cost_budget_include_expenses'] = $costBudgetIncludeExpenses;
        if (!is_null($fee)) $data['fee'] = $fee;
        if (!is_null($notes)) $data['notes'] = $notes;
        if (!is_null($startsOn)) $data['starts_on'] = $startsOn;
        if (!is_null($endsOn)) $data['ends_on'] = $endsOn;

        return $this->api->post($uri, $data);
    }

    /**
     * Update a project.
     *
     * Updates the specific project by setting the values of the parameters
     * passed. Any parameters not provided will be left unchanged.
     *
     * Returns a project object and a 200 OK response code if the call
     * succeeded.
     *
     * @param integer $projectId The project ID.
     * @param integer|null $clientId The client ID.
     * @param string|null $name The name of the project.
     * @param boolean|null $isBillable Whether the project is billable or not.
     * @param string|null $billBy The method by which the project is invoiced. Options: Project, Tasks, People, or none.
     * @param string|null $budgetBy The method by which the project is budgeted. Options: project (Hours Per Project),
     * project_cost (Total Project Fees), task (Hours Per Task), task_fees (Fees Per Task), person (Hours Per Person),
     * none (No Budget).
     * @param string|null $code The code associated with the project.
     * @param boolean|null $isActive Whether the project is active or archived. Defaults to true.
     * @param boolean|null $isFixedFee Whether the project is a fixed-fee project or not.
     * @param float|null $hourlyRate Rate for projects billed by Project Hourly Rate.
     * @param float|null $budget The budget in hours for the project when budgeting by time.
     * @param boolean|null $notifyWhenOverBudget Whether project managers should be notified when the project goes over budget. Defaults to false.
     * @param float|null $overBudgetNotificationPercentage Percentage value used to trigger over budget email alerts. Example: use 10.0 for 10.0%.
     * @param boolean|null $showBudgetToAll Option to show project budget to all employees. Does not apply to Total Project Fee projects. Defaults to false.
     * @param float|null $costBudget The monetary budget for the project when budgeting by money.
     * @param boolean|null $costBudgetIncludeExpenses Option for budget of Total Project Fees projects to include tracked expenses. Defaults to false.
     * @param float|null $fee The amount you plan to invoice for the project. Only used by fixed-fee projects.
     * @param string|null $notes Project notes.
     * @param mixed|null $startsOn Date the project was started.
     * @param mixed|null $endsOn Date the project will end.
     *
     * @return mixed
     */
    public function update(
        $projectId,
        $clientId = null,
        $name = null,
        $isBillable = null,
        $billBy = null,
        $budgetBy = null,
        $code = null,
        $isActive = null,
        $isFixedFee = null,
        $hourlyRate = null,
        $budget = null,
        $notifyWhenOverBudget = null,
        $overBudgetNotificationPercentage = null,
        $showBudgetToAll = null,
        $costBudget = null,
        $costBudgetIncludeExpenses = null,
        $fee = null,
        $notes = null,
        $startsOn = null,
        $endsOn = null
    )
    {
        $uri = "projects/" . $projectId;

        $data = [];

        if (!is_null($clientId)) $data['client_id'] = $clientId;
        if (!is_null($name)) $data['name'] = $name;
        if (!is_null($isBillable)) $data['is_billable'] = $isBillable;
        if (!is_null($billBy)) $data['bill_by'] = $billBy;
        if (!is_null($budgetBy)) $data['budget_by'] = $budgetBy;
        if (!is_null($code)) $data['code'] = $code;
        if (!is_null($isActive)) $data['is_active'] = $isActive;
        if (!is_null($isFixedFee)) $data['is_fixed_fee'] = $isFixedFee;
        if (!is_null($hourlyRate)) $data['hourly_rate'] = $hourlyRate;
        if (!is_null($budget)) $data['budget'] = $budget;
        if (!is_null($notifyWhenOverBudget)) $data['notify_when_over_budget'] = $notifyWhenOverBudget;
        if (!is_null($overBudgetNotificationPercentage)) $data['over_budget_notification_percentage'] = $overBudgetNotificationPercentage;
        if (!is_null($showBudgetToAll)) $data['show_budget_to_all'] = $showBudgetToAll;
        if (!is_null($costBudget)) $data['cost_budget'] = $costBudget;
        if (!is_null($costBudgetIncludeExpenses)) $data['cost_budget_include_expenses'] = $costBudgetIncludeExpenses;
        if (!is_null($fee)) $data['fee'] = $fee;
        if (!is_null($notes)) $data['notes'] = $notes;
        if (!is_null($startsOn)) $data['starts_on'] = $startsOn;
        if (!is_null($endsOn)) $data['ends_on'] = $endsOn;

        return $this->api->patch($uri, $data);
    }

    /**
     * Delete a project.
     *
     * Deletes a project and any time entries or expenses tracked to it. However, invoices associated with the project
     * will not be deleted. If you don’t want the project’s time entries and expenses to be deleted, you should archive
     * the project instead.
     *
     * @param integer $projectId The project ID.
     *
     * @return mixed
     */
    public function delete($projectId)
    {
        $uri = "projects/" . $projectId;

        return $this->api->delete($uri);
    }
}