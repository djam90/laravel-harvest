<?php

namespace Djam90\Harvest\Services;

use Djam90\Harvest\BaseService;

class InvoiceService extends BaseService
{
    /**
     * Get invoices.
     *
     * Returns a list of your invoices. The invoices are returned sorted by
     * issue date, with the most recently issued invoices appearing first.
     *
     * @param integer|null $clientId Only return invoices belonging to the client with the given ID.
     * @param integer|null $projectId Only return invoices associated with the project with the given ID.
     * @param string|null $updatedSince Only return invoices that have been updated since the given date and time.
     * @param integer|null $page The page number to use in pagination.
     * @param integer|null $perPage The number of records to return per page.
     *
     * @return mixed
     */
    public function get($clientId = null, $projectId = null,
                        $updatedSince = null, $page = null, $perPage = null)
    {
        $uri = "invoices";

        $data = [];

        if (!is_null($clientId)) $data['client_id'] = $clientId;
        if (!is_null($projectId)) $data['project_id'] = $projectId;
        if (!is_null($updatedSince)) $data['updated_since'] = $updatedSince;
        if (!is_null($page)) $data['page'] = $page;
        if (!is_null($perPage)) $data['per_page'] = $perPage;

        return $this->httpGet($uri, $data);
    }

    /**
     * Retrieve an invoice.
     *
     * Retrieves the invoice with the given ID. Returns an invoice object and a 
     * 200 OK response code if a valid identifier was provided.
     *
     * @param integer $invoiceId The invoice ID.
     * 
     * @return mixed
     */
    public function getById($invoiceId)
    {
        $uri = "invoices/" . $invoiceId;

        return $this->httpGet($uri);
    }

    /**
     * Create an invoice.
     *
     * Creates a new invoice object. Returns an invoice object and a 201 
     * Created response code if the call succeeded.
     *
     * For tax, tax2, and discount use 10.0 for 10.0%.
     *
     * @param integer $clientId The ID of the client this invoice belongs to.
     * @param integer|null $retainerId The ID of the retainer associated with this invoice.
     * @param integer|null $estimateId The ID of the estimate associated with this invoice.
     * @param string|null $number If no value is set, the number will be automatically generated.
     * @param string|null $purchaseOrder The purchase order number.
     * @param float|null $tax This percentage is applied to the subtotal, including line items and discounts.
     * @param float|null $tax2 This percentage is applied to the subtotal, including line items and discounts.
     * @param float|null $discount This percentage is subtracted from the subtotal.
     * @param string|null $subject The invoice subject.
     * @param string|null $notes Any additional notes to include on the invoice.
     * @param string|null $currency The currency used by the invoice. If not provided, the client's currency will be 
     * used.
     * @param string|null $issueDate Date the invoice was issued. Defaults to today's date.
     * @param string|null $dueDate Date the invoice is due. Defaults to the issue_date.
     * @param array|null $lineItems Array of line item parameters
     *
     * Array structure for $lineItems
     *
     * @var integer $project_id The ID of the project associated with this line item. (optional)
     * @var string $kind The name of an invoice item category. (required)
     * @var string $description The name of an invoice item category. (optional)
     * @var integer $quantity The unit quantity of the item. Defaults to 1. (optional)
     * @var float $unit_price The individual price per unit. (required)
     * @var bool $taxed Whether the invoice's tax percentage applies to this line item. Defaults to false. (optional)
     * @var bool $taxed2 Whether the invoice's tax2 percentage applies to this line item. Defaults to false. (optional)
     *
     * @return mixed
     */
    public function create($clientId, $retainerId = null, $estimateId = null, 
                           $number = null, $purchaseOrder = null, $tax = null, 
                           $tax2 = null, $discount = null, $subject = null,
                           $notes = null, $currency = null, $issueDate = null, 
                           $dueDate = null, $lineItems = null)
    {
        $uri = "invoices";

        $data = [
            'client_id' => $clientId
        ];

        if (!is_null($retainerId)) $data['retainer_id'] = $retainerId;
        if (!is_null($estimateId)) $data['estimate_id'] = $estimateId;
        if (!is_null($number)) $data['number'] = $number;
        if (!is_null($purchaseOrder)) $data['purchase_order'] = $purchaseOrder;
        if (!is_null($tax)) $data['tax'] = $tax;
        if (!is_null($tax2)) $data['tax2'] = $tax2;
        if (!is_null($discount)) $data['discount'] = $discount;
        if (!is_null($subject)) $data['subject'] = $subject;
        if (!is_null($notes)) $data['notes'] = $notes;
        if (!is_null($currency)) $data['currency'] = $currency;
        if (!is_null($issueDate)) $data['issue_date'] = $issueDate;
        if (!is_null($dueDate)) $data['due_date'] = $dueDate;
        if (!is_null($lineItems)) $data['line_items'] = $lineItems;

        return $this->httpPost($uri, $data);
    }

    /**
     * Update an invoice.
     *
     * Updates the specific invoice by setting the values of the parameters
     * passed. Any parameters not provided will be left unchanged.
     *
     * Returns an invoice object and a 200 OK response code if the call
     * succeeded.
     *
     * For tax, tax2, and discount use 10.0 for 10.0%.
     *
     * @param integer $invoiceId The invoice ID.
     * @param integer|null $clientId The ID of the client this invoice belongs to.
     * @param integer|null $retainerId The ID of the retainer associated with this invoice.
     * @param integer|null $estimateId The ID of the estimate associated with this invoice.
     * @param string|null $number If no value is set, the number will be automatically generated.
     * @param string|null $purchaseOrder The purchase order number.
     * @param float|null $tax This percentage is applied to the subtotal, including line items and discounts.
     * @param float|null $tax2 This percentage is applied to the subtotal, including line items and discounts.
     * @param float|null $discount This percentage is subtracted from the subtotal.
     * @param string|null $subject The invoice subject.
     * @param string|null $notes Any additional notes to include on the invoice.
     * @param string|null $currency The currency used by the invoice. If not provided, the client's currency will be
     * used.
     * @param string|null $issueDate Date the invoice was issued. Defaults to today's date.
     * @param string|null $dueDate Date the invoice is due. Defaults to the issue_date.
     * @param array|null $lineItems Array of line item parameters
     *
     * Array structure for $lineItems
     *
     * @var integer $project_id The ID of the project associated with this line item. (optional)
     * @var string $kind The name of an invoice item category. (required)
     * @var string $description The name of an invoice item category. (optional)
     * @var integer $quantity The unit quantity of the item. Defaults to 1. (optional)
     * @var float $unit_price The individual price per unit. (required)
     * @var bool $taxed Whether the invoice's tax percentage applies to this line item. Defaults to false. (optional)
     * @var bool $taxed2 Whether the invoice's tax2 percentage applies to this line item. Defaults to false. (optional)
     *
     * @return mixed
     */
    public function update($invoiceId, $clientId, $retainerId = null,
                           $estimateId = null, $number = null,
                           $purchaseOrder = null, $tax = null, $tax2 = null,
                           $discount = null, $subject = null, $notes = null,
                           $currency = null, $issueDate = null, $dueDate = null,
                           $lineItems = null)
    {
        $uri = "invoices/" . $invoiceId;

        $data = [];

        if (!is_null($clientId)) $data['client_id'] = $clientId;
        if (!is_null($retainerId)) $data['retainer_id'] = $retainerId;
        if (!is_null($estimateId)) $data['estimate_id'] = $estimateId;
        if (!is_null($number)) $data['number'] = $number;
        if (!is_null($purchaseOrder)) $data['purchase_order'] = $purchaseOrder;
        if (!is_null($tax)) $data['tax'] = $tax;
        if (!is_null($tax2)) $data['tax2'] = $tax2;
        if (!is_null($discount)) $data['discount'] = $discount;
        if (!is_null($subject)) $data['subject'] = $subject;
        if (!is_null($notes)) $data['notes'] = $notes;
        if (!is_null($currency)) $data['currency'] = $currency;
        if (!is_null($issueDate)) $data['issue_date'] = $issueDate;
        if (!is_null($dueDate)) $data['due_date'] = $dueDate;
        if (!is_null($lineItems)) $data['line_items'] = $lineItems;

        return $this->httpPatch($uri, $data);
    }

    /**
     * Create an invoice item.
     *
     * Create a new line item on an invoice. Returns a 200 OK response code if
     * the call succeeded.
     *
     * @param integer $invoiceId The invoice ID.
     * @param string $kind The name of an invoice item category.
     * @param float $unitPrice The individual price per unit.
     * @param integer|null $projectId The ID of the project associated with this line item.
     * @param string|null $description The name of an invoice item category.
     * @param integer|null $quantity The unit quantity of the item. Defaults to 1.
     * @param boolean|null $taxed Whether the invoice's tax percentage applies to this line item. Defaults to false.
     * @param boolean|null $taxed2 Whether the invoice's tax2 percentage applies to this line item. Defaults to false.
     *
     * @return mixed
     */
    public function createLineItem($invoiceId, $kind, $unitPrice,
                                   $projectId = null, $description = null,
                                   $quantity = null, $taxed = null,
                                   $taxed2 = null)
    {
        $uri = "invoices/" . $invoiceId;

        $data = [
            'line_items' => [
                [
                    'kind' => $kind,
                    'unit_price' => $unitPrice,
                ],
            ],

        ];

        if (!is_null($projectId)) $data['line_items'][0]['project_id'] =
            $projectId;
        if (!is_null($description)) $data['line_items'][0]['description'] =
            $description;
        if (!is_null($quantity)) $data['line_items'][0]['quantity'] = $quantity;
        if (!is_null($taxed)) $data['line_items'][0]['taxed'] = $taxed;
        if (!is_null($taxed2)) $data['line_items'][0]['taxed2'] = $taxed2;

        return $this->httpPost($uri, $data);
    }

    /**
     * Create an invoice item.
     *
     * Create a new line item on an invoice. Returns a 200 OK response code if
     * the call succeeded.
     *
     * @param integer $invoiceId The invoice ID.
     * @param integer $lineItemId The ID of the line item being updated.
     * @param string $kind The name of an invoice item category.
     * @param float $unitPrice The individual price per unit.
     * @param integer|null $projectId The ID of the project associated with this line item.
     * @param string|null $description The name of an invoice item category.
     * @param integer|null $quantity The unit quantity of the item. Defaults to 1.
     * @param boolean|null $taxed Whether the invoice's tax percentage applies to this line item. Defaults to false.
     * @param boolean|null $taxed2 Whether the invoice's tax2 percentage applies to this line item. Defaults to false.
     *
     * @return mixed
     */
    public function updateLineItem($invoiceId, $lineItemId, $kind = null,
                                   $unitPrice = null, $projectId = null,
                                   $description = null, $quantity = null,
                                   $taxed = null, $taxed2 = null)
    {
        $uri = "invoices/" . $invoiceId;

        $data = [
            'line_items' => [
                [
                    'id' => $lineItemId,
                ],
            ]
        ];

        if (!is_null($kind)) $data['line_items'][0]['kind'] = $kind;
        if (!is_null($unitPrice)) $data['line_items'][0]['unit_price'] =
            $unitPrice;
        if (!is_null($projectId)) $data['line_items'][0]['project_id'] =
            $projectId;
        if (!is_null($description)) $data['line_items'][0]['description'] =
            $description;
        if (!is_null($quantity)) $data['line_items'][0]['quantity'] = $quantity;
        if (!is_null($taxed)) $data['line_items'][0]['taxed'] = $taxed;
        if (!is_null($taxed2)) $data['line_items'][0]['taxed2'] = $taxed2;

        return $this->httpPatch($uri, $data);
    }

    /**
     * Delete an invoice line item.
     *
     * Delete a line item from an invoice. Returns a 200 OK response code if
     * the call succeeded.
     *
     * @param integer $invoiceId The ID of the invoice where the line item is to be deleted from.
     * @param integer $lineItemId The ID of the invoice line item being deleted.
     *
     * @return mixed
     */
    public function deleteLineItem($invoiceId, $lineItemId)
    {
        $uri = "invoices/" . $invoiceId;

        $data = [
            'line_items' => [
                [
                    'id' => $lineItemId,
                    '_destroy' => true
                ]
            ]
        ];

        return $this->httpPatch($uri, $data);
    }

    /**
     * Delete an invoice.
     *
     * Returns a 200 OK response code if the call succeeded.
     *
     * @param integer $invoiceId The ID of the invoice to be deleted.
     *
     * @return mixed
     */
    public function delete($invoiceId)
    {
        $uri = "invoices/" . $invoiceId;

        return $this->httpDelete($uri);
    }
}