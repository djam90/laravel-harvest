<?php

namespace Djam90\Harvest\Services;

use Djam90\Harvest\BaseService;

class EstimateService extends BaseService
{
    protected $modelClass = \Djam90\Harvest\Models\Estimate::class;

    protected $path = "estimates";

    /**
     * List all estimates.
     *
     * @param integer|null $clientId Only return estimates belonging to the client with the given ID.
     * @param mixed|null $updatedSince Only return estimates that have been updated since the given date and time.
     * @param mixed|null $from Only return estimates with an issue_date on or after the given date.
     * @param mixed|null $to Only return estimates with an issue_date on or before the given date.
     * @param string|null $state Only return estimates with a state matching the value provided. Options: draft, sent,
     * accepted, or declined.
     * @param integer|null $page The page number to use in pagination.
     * @param integer|null $perPage The number of records to return per page.
     *
     * @return mixed
     */
    public function get($clientId = null, $updatedSince = null, $from = null,
                        $to = null, $state = null, $page = null,
                        $perPage = null)
    {
        $uri = "estimates";

        $data = [];

        if (!is_null($clientId)) $data['client_id'] = $clientId;
        if (!is_null($updatedSince)) $data['updated_since'] = $updatedSince;
        if (!is_null($from)) $data['from'] = $from;
        if (!is_null($to)) $data['to'] = $to;
        if (!is_null($state)) $data['state'] = $state;
        if (!is_null($page)) $data['page'] = $page;
        if (!is_null($perPage)) $data['per_page'] = $perPage;

        // @todo validate An ISO 8601 formatted string containing a UTC date
        // and time.

        return $this->transformResult($this->api->get($uri, $data));
    }

    /**
     *  Retrieve an estimate.
     *
     * Retrieves the estimate with the given ID. Returns an estimate object and
     * a 200 OK response code if a valid identifier was provided.
     *
     * @param integer $estimateId The estimate ID.
     *
     * @return mixed
     */
    public function getById($estimateId)
    {
        $uri = "estimates/" . $estimateId;

        return $this->transformResult($this->api->get($uri));
    }

    /**
     * Create a estimate.
     *
     * Creates a new estimate object. Returns a project object and a 201
     * Created response code if the call succeeded.
     *
     * @param integer $clientId The client ID.
     * @param string|null $number If no value is set, the number will be automatically generated.
     * @param string|null $purchaseOrder The purchase order number.
     * @param float|null $tax This percentage is applied to the subtotal, including line items and discounts. Example:
     * use 10.0 for 10.0%.
     * @param float|null $tax2 This percentage is applied to the subtotal, including line items and discounts. Example:
     * use 10.0 for 10.0%.
     * @param float|null $discount This percentage is subtracted from the subtotal. Example: use 10.0 for 10.0%.
     * @param string|null $subject The estimate subject.
     * @param string|null $notes Any additional notes to include on the estimate.
     * @param string|null $currency The currency used by the estimate. If not provided, the client’s currency will be
     * used.
     * @param mixed|null $issueDate Date the estimate was issued. Defaults to today’s date.
     * @param array|null $lineItems Array of line item parameters.
     *
     * Array structure for $lineItems:
     *
     * @var string $kind The name of an estimate item category.
     * @var string $description Text description of the line item. (optional)
     * @var integer $quantity The unit quantity of the item. Defaults to 1. (optional)
     * @var float $unit_price The individual price per unit.
     * @var boolean $taxed Whether the estimate’s tax percentage applies to this line item. Defaults to false.
     * (optional)
     * @var boolean $taxed2 Whether the estimate’s tax2 percentage applies to this line item. Defaults to false.
     * (optional)
     *
     * @return mixed
     */
    public function create($clientId, $number = null, $purchaseOrder = null,
                           $tax = null, $tax2 = null, $discount = null,
                           $subject = null, $notes = null, $currency = null,
                           $issueDate = null, $lineItems = null)
    {
        $uri = "estimates";

        $data = [
            'client_id' => $clientId,
        ];

        if (!is_null($number)) $data['number'] = $number;
        if (!is_null($purchaseOrder)) $data['purchase_order'] = $purchaseOrder;
        if (!is_null($tax)) $data['tax'] = $tax;
        if (!is_null($tax2)) $data['tax2'] = $tax2;
        if (!is_null($discount)) $data['discount'] = $discount;
        if (!is_null($subject)) $data['subject'] = $subject;
        if (!is_null($notes)) $data['notes'] = $notes;
        if (!is_null($currency)) $data['currency'] = $currency;
        if (!is_null($issueDate)) $data['issue_date'] = $issueDate;
        if (!is_null($lineItems)) $data['line_items'] = $lineItems;

        return $this->api->post($uri, $data);
    }

    /**
     * Update an estimate.
     *
     * Updates the specific estimate by setting the values of the parameters
     * passed. Any parameters not provided will be left unchanged.
     *
     * Returns an estimate object and a 200 OK response code if the call
     * succeeded.
     *
     * @param integer $estimateId The estimate ID.
     * @param integer|null $clientId The client ID.
     * @param string|null $number If no value is set, the number will be automatically generated.
     * @param string|null $purchaseOrder The purchase order number.
     * @param float|null $tax This percentage is applied to the subtotal, including line items and discounts. Example:
     * use 10.0 for 10.0%.
     * @param float|null $tax2 This percentage is applied to the subtotal, including line items and discounts. Example:
     * use 10.0 for 10.0%.
     * @param float|null $discount This percentage is subtracted from the subtotal. Example: use 10.0 for 10.0%.
     * @param string|null $subject The estimate subject.
     * @param string|null $notes Any additional notes to include on the estimate.
     * @param string|null $currency The currency used by the estimate. If not provided, the client’s currency will be
     * used.
     * @param mixed|null $issueDate Date the estimate was issued. Defaults to today’s date.
     * @param array|null $lineItems Array of line item parameters.
     *
     * Array structure for $lineItems:
     *
     * @var integer $id Unique ID for the line item.
     * @var string $kind The name of an estimate item category.
     * @var string $description Text description of the line item.
     * @var integer $quantity The unit quantity of the item. Defaults to 1.
     * @var float $unit_price The individual price per unit.
     * @var boolean $taxed Whether the estimate’s tax percentage applies to this line item. Defaults to false.
     * (optional)
     * @var boolean $taxed2 Whether the estimate’s tax2 percentage applies to this line item. Defaults to false.
     * (optional)
     *
     * @return mixed
     */
    public function update($estimateId, $clientId = null, $number = null,
                           $purchaseOrder = null, $tax = null, $tax2 = null,
                           $discount = null, $subject = null, $notes = null,
                           $currency = null, $issueDate = null,
                           $lineItems = null)
    {
        $uri = "estimates/" . $estimateId;

        $data = [];

        if (!is_null($clientId)) $data['client_id'] = $clientId;
        if (!is_null($number)) $data['number'] = $number;
        if (!is_null($purchaseOrder)) $data['purchase_order'] = $purchaseOrder;
        if (!is_null($tax)) $data['tax'] = $tax;
        if (!is_null($tax2)) $data['tax2'] = $tax2;
        if (!is_null($discount)) $data['discount'] = $discount;
        if (!is_null($subject)) $data['subject'] = $subject;
        if (!is_null($notes)) $data['notes'] = $notes;
        if (!is_null($currency)) $data['currency'] = $currency;
        if (!is_null($issueDate)) $data['issue_date'] = $issueDate;
        if (!is_null($lineItems)) $data['line_items'] = $lineItems;

        return $this->api->patch($uri, $data);
    }

    /**
     * Create an estimate line item.
     *
     * Create a line item on an estimate. Returns a 200 OK response code if the
     * call succeeded.
     *
     * @param integer $estimateId The estimate ID.
     * @param string $kind The name of an estimate item category.
     * @param float $unitPrice The individual price per unit.
     * @param string|null $description Text description of the line item.
     * @param integer|null $quantity The unit quantity of the item. Defaults to 1.
     * @param boolean|null $taxed Whether the estimate’s tax percentage applies to this line item. Defaults to false.
     * @param boolean|null $taxed2 Whether the estimate’s tax2 percentage applies to this line item. Defaults to false.
     *
     * @return mixed
     */
    public function createLineItem($estimateId, $kind, $unitPrice,
                                   $description = null, $quantity = null,
                                   $taxed = null, $taxed2 = null)
    {
        return $this->update($estimateId, null, null, null, null, null, null,
            null, null, null, null, [
                [
                    'kind' => $kind,
                    'description' => $description,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'taxed' => $taxed,
                    'taxed2' => $taxed2
                ]
            ]);
    }

    /**
     * Update an estimate line item.
     *
     * Update an exisitng line item on an estimate. Returns a 200 OK response
     * code if the call succeeded.
     *
     * @param integer $estimateId The estimate ID.
     * @param integer $lineItemId The invoice line item ID.
     * @param string|null $kind The name of an estimate item category.
     * @param string|null $description Text description of the line item.
     * @param integer|null $quantity The unit quantity of the item. Defaults to 1.
     * @param float|null $unitPrice The individual price per unit.
     * @param boolean|null $taxed Whether the estimate’s tax percentage applies to this line item. Defaults to false.
     * @param boolean|null $taxed2 Whether the estimate’s tax2 percentage applies to this line item. Defaults to false.
     *
     * @return mixed
     */
    public function updateLineItem($estimateId, $lineItemId, $kind = null,
                                   $description = null, $quantity = null,
                                   $unitPrice = null, $taxed = null,
                                   $taxed2 = null)
    {
        return $this->update($estimateId, null, null, null, null, null, null,
            null, null, null, null, [
                [
                    'id' => $lineItemId,
                    'kind' => $kind,
                    'description' => $description,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'taxed' => $taxed,
                    'taxed2' => $taxed2
                ]
            ]);
    }

    /**
     * Delete an estimate line item.
     *
     * Delete a line item from an estimate. Returns a 200 OK response code if
     * the call succeeded.
     *
     * @param integer $estimateId The estimate ID.
     * @param integer $lineItemId The line item ID.
     *
     * @return mixed
     */
    public function deleteLineItem($estimateId, $lineItemId)
    {
        return $this->update($estimateId, null, null, null, null, null, null,
            null, null, null, null, [
                [
                    'id' => $lineItemId,
                    '_destroy' => true
                ]
            ]);
    }

    /**
     * Delete an estimate.
     *
     * Delete an estimate. Returns a 200 OK response code if the call
     * succeeded.
     *
     * @param integer $estimateId The estimate ID.
     *
     * @return mixed
     */
    public function delete($estimateId)
    {
        $uri = "estimates/" . $estimateId;

        return $this->api->delete($uri);
    }
}