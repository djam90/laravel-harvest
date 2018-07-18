<?php

namespace Djam90\Harvest\Services;

use Djam90\Harvest\BaseService;

class InvoiceItemCategoryService extends BaseService
{
    protected $modelClass = \Djam90\Harvest\Models\InvoiceItemCategory::class;

    protected $path = "invoice_item_categories";

    /**
     * List all invoice item categories.
     *
     * Returns a list of your invoice item categories. The invoice item
     * categories are returned sorted by creation date, with the most recently
     * created invoice item categories appearing first.
     *
     * The response contains an object with a invoice_item_categories property
     * that contains an array of up to per_page invoice item categories. Each
     * entry in the array is a separate invoice item category object. If no
     * more invoice item categories are available, the resulting array will be
     * empty.
     *
     * Several additional pagination properties are included in the response to
     * simplify paginating your invoice item categories.
     *
     * @param mixed|null $updatedSince Only return invoice item categories that have been updated since the given date
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
        $uri = "invoice_item_categories";

        $data = [];

        if (!is_null($updatedSince)) $data['updated_since'] = $updatedSince;
        if (!is_null($page)) $data['page'] = $page;
        if (!is_null($perPage)) $data['per_page'] = $perPage;

        return $this->transformResult($this->api->get($uri, $data));
    }

    /**
     * Retrieve an invoice item category.
     *
     * Retrieves the invoice item category with the given ID.
     *
     * Returns an invoice item category object and a 200 OK response code if a
     * valid identifier was provided.
     *
     * @param integer $invoiceItemCategoryId The ID of the invoice item category.
     *
     * @return mixed
     */
    public function getById($invoiceItemCategoryId)
    {
        $uri = "invoice_item_categories/" . $invoiceItemCategoryId;

        $data = [];

        return $this->transformResult($this->api->get($uri, $data));
    }

    /**
     * Create an invoice item category.
     *
     * Creates a new invoice item category object.
     * Returns an invoice item category object and a 201 Created response code
     * if the call succeeded.
     *
     * @param string $name The name of the invoice item category.
     *
     * @return mixed
     */
    public function create($name)
    {
        $uri = "invoice_item_categories";

        $data = [
            'name' => $name,
        ];

        return $this->api->post($uri, $data);
    }

    /**
     * Update an invoice item category.
     *
     * Updates the specific invoice item category by setting the values of the
     * parameters passed. Any parameters not provided will be left unchanged.
     *
     * Returns an invoice item category object and a 200 OK response code if
     * the call succeeded.
     *
     * @param string $name The name of the invoice item category.
     *
     * @return mixed
     */
    public function update($name)
    {
        $uri = "invoice_item_categories";

        $data = [
            'name' => $name,
        ];

        return $this->api->patch($uri, $data);
    }

    /**
     * Delete an invoice item category.
     *
     * Delete an invoice item category.
     * Deleting an invoice item category is only possible if use_as_service and
     * use_as_expense are both false.
     *
     * Returns a 200 OK response code if the call succeeded.
     *
     * @param integer $invoiceItemCategoryId The invoice item category ID to be deleted.
     *
     * @return mixed
     */
    public function delete($invoiceItemCategoryId)
    {
        $uri = "invoice_item_categories/" . $invoiceItemCategoryId;

        return $this->api->delete($uri);
    }
}