<?php

namespace Djam90\Harvest\Services;

use Djam90\Harvest\BaseService;

class InvoicePaymentService extends BaseService
{
    protected $modelClass = \Djam90\Harvest\Models\InvoicePayment::class;

    protected $path = "invoice_payments";

    /**
     * List all payments for an invoice.
     *
     * Returns a list of payments associate with a given invoice. The payments
     * are returned sorted by creation date, with the most recently created
     * payments appearing first.
     *
     * The response contains an object with an invoice_payments property that
     * contains an array of up to per_page payments. Each entry in the array is
     * a separate payment object. If no more payments are available, the
     * resulting array will be empty. Several additional pagination properties
     * are included in the response to simplify paginating your payments.
     *
     * @param integer $invoiceId The invoice ID.
     * @param string|null $updatedSince Only return invoice payments that have been updated since the given date and time.
     * @param integer $page The page number to use in pagination.
     * @param integer $perPage The number of records to return per page.
     *
     * @return mixed
     */
    public function get($invoiceId, $updatedSince = null, $page = null,
                        $perPage = null)
    {
        $uri = "invoices/" . $invoiceId . "/payments";

        $data = [];

        if (!is_null($updatedSince)) $data['updated_since'] = $updatedSince;
        if (!is_null($page)) $data['page'] = $page;
        if (!is_null($perPage)) $data['per_page'] = $perPage;

        return $this->transformResult($this->api->get($uri, $data));
    }

    /**
     * Create an invoice payment.
     *
     * Creates a new invoice payment object. Returns an invoice payment object
     * and a 201 Created response code if the call succeeded.
     *
     * @param integer $invoiceId The ID of the invoice that a message is being created for.
     * @param float $amount The amount of the payment.
     * @param string|null $paidAt Date and time the payment was made.
     * @param string|null $notes Any notes to be associated with the payment.
     *
     * @return mixed
     */
    public function create($invoiceId, $amount, $paidAt = null, $notes = null)
    {
        $uri = "invoices/" . $invoiceId . "/payments";

        $data = [
            'amount' => $amount,
        ];

        if (!is_null($paidAt)) $data['paid_at'] = $paidAt;
        if (!is_null($notes)) $data['notes'] = $notes;

        return $this->api->post($uri, $data);
    }

    /**
     * Delete an invoice payment.
     *
     * Delete an invoice payment. Returns a 200 OK response code if the call
     * succeeded.
     *
     * @param integer $invoiceId The invoice ID.
     * @param integer $paymentId The payment ID.
     *
     * @return mixed
     */
    public function delete($invoiceId, $paymentId)
    {
        $uri = "invoices/" . $invoiceId . "/payments/" . $paymentId;

        return $this->api->delete($uri);
    }
}