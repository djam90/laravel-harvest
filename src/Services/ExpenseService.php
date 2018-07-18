<?php

namespace Djam90\Harvest\Services;

use Djam90\Harvest\BaseService;

class ExpenseService extends BaseService
{
    protected $modelClass = \Djam90\Harvest\Models\Expenses::class;

    protected $path = "expenses";

    /**
     * Get expenses.
     *
     * Returns a list of your expenses. The expenses are returned sorted by the spent_at date, with the most recent
     * expenses appearing first.
     *
     * @param integer|null $userId Only return expenses belonging to the user with the given ID.
     * @param integer|null $clientId Only return expenses belonging to the client with the given ID.
     * @param integer|null $projectId Only return expenses associated with the project with the given ID.
     * @param boolean|null $isBilled Pass true to only return expenses that have been invoiced and false to return expenses that have not been invoiced.
     * @param mixed|null $updatedSince Only return expenses that have been updated since the given date and time.
     * @param mixed|null $from Only return expenses with a spent_date on or after the given date.
     * @param mixed|null $to Only return expenses with a spent_date on or before the given date.
     * @param integer|null $page The page number to use in pagination.
     * @param integer|null $perPage The number of records to return per page.
     *
     * @return mixed
     */
    public function get($userId = null, $clientId = null, $projectId = null,
                        $isBilled = null, $updatedSince = null, $from = null,
                        $to = null, $page = null, $perPage = null)
    {
        $uri = "expenses";

        $data = [];

        if (!is_null($userId)) $data['user_id'] = $userId;
        if (!is_null($clientId)) $data['client_id'] = $clientId;
        if (!is_null($projectId)) $data['project_id'] = $projectId;
        if (!is_null($isBilled)) $data['is_billed'] = $isBilled;
        if (!is_null($from)) $data['from'] = $from;
        if (!is_null($to)) $data['to'] = $to;
        if (!is_null($updatedSince)) $data['updated_since'] = $updatedSince;
        if (!is_null($page)) $data['page'] = $page;
        if (!is_null($perPage)) $data['per_page'] = $perPage;

        return $this->transformResult($this->api->get($uri, $data));
    }

    /**
     * Retrieve an expense.
     *
     * Retrieves the expense with the given ID. Returns an expense object and a 200 OK response code if a valid
     * identifier was provided.
     *
     * @param integer $expenseId The expense ID.
     *
     * @return mixed
     */
    public function getById($expenseId)
    {
        $uri = "expenses/" . $expenseId;

        return $this->transformResult($this->api->get($uri));
    }

    /**
     * Create an expense.
     *
     * Creates a new expense object. Returns an expense object and a 201 Created response code if the call succeeded.
     *
     * Either units or total_cost is required. units is required if using a unit-based expense category. total_cost is
     * required if not using a unit-based expense category.
     *
     * @param integer $projectId The ID of the project associated with this expense.
     * @param integer $expenseCategoryId The ID of the expense category this expense is being tracked against.
     * @param mixed $spentDate Date the expense occurred.
     * @param integer|null $userId The ID of the user associated with this expense. Defaults to the ID of the currently authenticated user.
     * @param integer|null $units The quantity of units to use in calculating the total_cost of the expense.
     * @param float|null $totalCost The total amount of the expense.
     * @param string|null $notes Any additional notes to include on the expense.
     * @param boolean|null $billable Whether this expense is billable or not. Defaults to true.
     * @param string|null $receipt A receipt file to attach to the expense.
     *
     * @return mixed
     */
    public function create(
        $projectId,
        $expenseCategoryId,
        $spentDate,
        $userId = null,
        $units = null,
        $totalCost = null,
        $notes = null,
        $billable = null,
        $receipt = null
    )
    {
        $uri = "expenses";

        $data = [
            'project_id' => $projectId,
            'expense_category_id' => $expenseCategoryId,
            'spent_date' => $spentDate,
        ];

        if (!is_null($userId)) $data['user_id'] = $userId;
        if (!is_null($units)) $data['units'] = $units;
        if (!is_null($totalCost)) $data['total_cost'] = $totalCost;
        if (!is_null($notes)) $data['notes'] = $notes;
        if (!is_null($billable)) $data['billable'] = $billable;
        if (!is_null($receipt)) $data['receipt'] = $receipt;

        return $this->api->post($uri, $data);
    }

    /**
     * Update an expense.
     *
     * Updates the specific expense by setting the values of the parameters passed. Any parameters not provided will be
     * left unchanged.
     *
     * Returns an expense object and a 200 OK response code if the call succeeded.
     *
     * @param integer $expenseId The expense ID.
     * @param integer|null $projectId The ID of the project associated with this expense.
     * @param integer|null $expenseCategoryId The ID of the expense category this expense is being tracked against.
     * @param mixed|null $spentDate Date the expense occurred.
     * @param integer|null $userId The ID of the user associated with this expense. Defaults to the ID of the currently authenticated user.
     * @param integer|null $units The quantity of units to use in calculating the total_cost of the expense.
     * @param float|null $totalCost The total amount of the expense.
     * @param string|null $notes Any additional notes to include on the expense.
     * @param boolean|null $billable Whether this expense is billable or not. Defaults to true.
     * @param string|null $receipt A receipt file to attach to the expense.
     * @param boolean|null $deleteReceipt Whether an attached expense receipt should be deleted. Pass true to delete the expense receipt.
     *
     * @return mixed
     */
    public function update(
        $expenseId,
        $projectId = null,
        $expenseCategoryId = null,
        $spentDate = null,
        $userId = null,
        $units = null,
        $totalCost = null,
        $notes = null,
        $billable = null,
        $receipt = null,
        $deleteReceipt = null
    )
    {
        $uri = "expenses/" . $expenseId;

        $data = [];

        if (!is_null($projectId)) $data['project_id'] = $projectId;
        if (!is_null($expenseCategoryId)) $data['expense_category_id'] = $expenseCategoryId;
        if (!is_null($spentDate)) $data['spent_date'] = $spentDate;
        if (!is_null($userId)) $data['user_id'] = $userId;
        if (!is_null($units)) $data['units'] = $units;
        if (!is_null($totalCost)) $data['total_cost'] = $totalCost;
        if (!is_null($notes)) $data['notes'] = $notes;
        if (!is_null($billable)) $data['billable'] = $billable;
        if (!is_null($receipt)) $data['receipt'] = $receipt;
        if (!is_null($deleteReceipt)) $data['delete_receipt'] = $deleteReceipt;

        return $this->api->patch($uri, $data);
    }

    /**
     * Delete an expense.
     *
     * Returns a 200 OK response code if the call succeeded.
     *
     * @param integer $expenseId The ID of the expense to be deleted.
     *
     * @return mixed
     */
    public function delete($expenseId)
    {
        $uri = "expenses/" . $expenseId;

        return $this->api->delete($uri);
    }
}