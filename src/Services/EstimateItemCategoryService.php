<?php

namespace Djam90\Harvest\Services;

use Djam90\Harvest\BaseService;

class EstimateItemCategoryService extends BaseService
{
    /**
     * List all estimate item categories.
     *
     * Returns a list of your estimate item categories. The estimate item
     * categories are returned sorted by creation date, with the most recently
     * created estimate item categories appearing first.
     *
     * The response contains an object with a estimate_item_categories property
     * that contains an array of up to per_page estimate item categories. Each
     * entry in the array is a separate estimate item category object. If no
     * more estimate item categories are available, the resulting array will be
     * empty.
     *
     * Several additional pagination properties are included in the response to
     * simplify paginating your estimate item categories.
     *
     * @param mixed|null $updatedSince Only return estimate item categories that have been updated since the given date
     * and time.
     * @param integer|null $page The page number to use in pagination. For instance, if you make a list request and
     * receive 100 records, your subsequent call can include page=2 to retrieve the next page of the list. (Default: 1)
     * @param integer|null $perPage The number of records to return per page. Can range between 1 and 100. (Default:
     * 100)
     *
     * @return mixed
     */
    public function get($updatedSince = null, $page = null, $perPage = null)
    {
        $uri = "estimate_item_categories";

        $data = [];

        if (!is_null($updatedSince)) $data['updated_since'] = $updatedSince;
        if (!is_null($page)) $data['page'] = $page;
        if (!is_null($perPage)) $data['per_page'] = $perPage;

        return $this->httpGet($uri, $data);
    }

    /**
     * Retrieve an estimate item category.
     *
     * Retrieves the estimate item category with the given ID.
     *
     * Returns an estimate item category object and a 200 OK response code if a
     * valid identifier was provided.
     *
     * @param integer $estimateItemCategoryId The ID of the estimate item category.
     *
     * @return mixed
     */
    public function getById($estimateItemCategoryId)
    {
        $uri = "estimate_item_categories/" . $estimateItemCategoryId;

        $data = [];

        return $this->httpGet($uri, $data);
    }

    /**
     * Create an estimate item category.
     *
     * Creates a new estimate item category object.
     * Returns an estimate item category object and a 201 Created response code
     * if the call succeeded.
     *
     * @param string $name The name of the estimate item category.
     *
     * @return mixed
     */
    public function create($name)
    {
        $uri = "estimate_item_categories";

        $data = [
            'name' => $name,
        ];

        return $this->httpPost($uri, $data);
    }

    /**
     * Update an estimate item category.
     *
     * Updates the specific estimate item category by setting the values of the
     * parameters passed. Any parameters not provided will be left unchanged.
     *
     * Returns an estimate item category object and a 200 OK response code if
     * the call succeeded.
     *
     * @param string $name The name of the estimate item category.
     *
     * @return mixed
     */
    public function update($name)
    {
        $uri = "estimate_item_categories";

        $data = [
            'name' => $name,
        ];

        return $this->httpPatch($uri, $data);
    }

    /**
     * Delete an estimate item category.
     *
     * Delete an estimate item category.
     * Deleting an estimate item category is only possible if use_as_service and
     * use_as_expense are both false.
     *
     * Returns a 200 OK response code if the call succeeded.
     *
     * @param integer $estimateItemCategoryId The estimate item category ID to be deleted.
     *
     * @return mixed
     */
    public function delete($estimateItemCategoryId)
    {
        $uri = "estimate_item_categories/" . $estimateItemCategoryId;

        return $this->httpDelete($uri);
    }
}