<?php

namespace Djam90\Harvest\Services;

use Djam90\Harvest\BaseService;

class ExpenseCategoryService extends BaseService
{
    /**
     * List all expense categories.
     *
     * Returns a list of your expense categories. The expense
     * categories are returned sorted by creation date, with the most recently
     * created expense categories appearing first.
     *
     * The response contains an object with a expense_categories property
     * that contains an array of up to per_page expense categories. Each
     * entry in the array is a separate expense category object. If no
     * more expense categories are available, the resulting array will be
     * empty.
     *
     * Several additional pagination properties are included in the response to
     * simplify paginating your expense categories.
     *
     * @param boolean|null $isActive Pass true to only return active expense categories and false to return inactive
     * expense categories.
     * @param mixed|null $updatedSince Only return expense categories that have been updated since the given date
     * and time.
     * @param integer|null $page The page number to use in pagination. For instance, if you make a list request and
     * receive 100 records, your subsequent call can include page=2 to retrieve the next page of the list. (Default: 1)
     * @param integer|null $perPage The number of records to return per page. Can range between 1 and 100. (Default:
     * 100)
     *
     * @return mixed
     */
    public function get($isActive = null, $updatedSince = null, $page = null,
                        $perPage = null)
    {
        $uri = "expense_categories";

        $data = [];

        if (!is_null($isActive)) $data['is_active'] = $isActive;
        if (!is_null($updatedSince)) $data['updated_since'] = $updatedSince;
        if (!is_null($page)) $data['page'] = $page;
        if (!is_null($perPage)) $data['per_page'] = $perPage;

        return $this->api->get($uri, $data);
    }

    /**
     * Retrieve an expense category.
     *
     * Retrieves the expense category with the given ID.
     *
     * Returns an expense category object and a 200 OK response code if a
     * valid identifier was provided.
     *
     * @param integer $expenseCategoryId The ID of the expense category.
     *
     * @return mixed
     */
    public function getById($expenseCategoryId)
    {
        $uri = "expense_categories/" . $expenseCategoryId;

        $data = [];

        return $this->api->get($uri, $data);
    }

    /**
     * Create an expense category.
     *
     * Creates a new expense category object.
     * Returns an expense category object and a 201 Created response code
     * if the call succeeded.
     *
     * @param string $name The name of the expense category.
     * @param string|null $unitName The unit name of the expense category.
     * @param float|null $unitPrice The unit price of the expense category.
     * @param boolean|null $isActive Whether the expense category is active or archived. Defaults to true.
     *
     * @return mixed
     */
    public function create($name, $unitName = null, $unitPrice = null,
                           $isActive = null)
    {
        $uri = "expense_categories";

        $data = [
            'name' => $name,
        ];

        if (!is_null($unitName)) $data['unit_name'] = $unitName;
        if (!is_null($unitPrice)) $data['unit_price'] = $unitPrice;
        if (!is_null($isActive)) $data['is_active'] = $isActive;

        return $this->api->post($uri, $data);
    }

    /**
     * Update an expense category.
     *
     * Updates the specific expense category by setting the values of the
     * parameters passed. Any parameters not provided will be left unchanged.
     *
     * Returns an expense category object and a 200 OK response code if
     * the call succeeded.
     *
     * @param integer $expenseCategoryId The expense category ID.
     * @param string $name The name of the expense category.
     * @param string|null $unitName The unit name of the expense category.
     * @param float|null $unitPrice The unit price of the expense category.
     * @param boolean|null $isActive Whether the expense category is active or archived. Defaults to true.
     *
     * @return mixed
     */
    public function update($expenseCategoryId, $name = null, $unitName = null,
                           $unitPrice = null, $isActive = null)
    {
        $uri = "expense_categories/" . $expenseCategoryId;

        $data = [];

        if (!is_null($name)) $data['name'] = $name;
        if (!is_null($unitName)) $data['unit_name'] = $unitName;
        if (!is_null($unitPrice)) $data['unit_price'] = $unitPrice;
        if (!is_null($isActive)) $data['is_active'] = $isActive;


        return $this->api->patch($uri, $data);
    }

    /**
     * Delete an expense category.
     *
     * Delete an expense category. Returns a 200 OK response code if the call
     * succeeded.
     *
     * @param integer $expenseCategoryId The expense category ID to be deleted.
     *
     * @return mixed
     */
    public function delete($expenseCategoryId)
    {
        $uri = "expense_categories/" . $expenseCategoryId;

        return $this->api->delete($uri);
    }
}