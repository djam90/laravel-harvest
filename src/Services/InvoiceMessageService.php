<?php

namespace Djam90\Harvest\Services;

use Djam90\Harvest\BaseService;

class InvoiceMessageService extends BaseService
{
    /**
     * List all messages for an invoice.
     *
     * Returns a list of messages associated with a given invoice. The invoice
     * messages are returned sorted by creation date, with the most recently
     * created messages appearing first.
     *
     * The response contains an object with an invoice_messages property that
     * contains an array of up to per_page messages. Each entry in the array is
     * a separate message object. If no more messages are available, the
     * resulting array will be empty.
     *
     * Several additional pagination properties are included in the response to
     * simplify paginating your messages.
     *
     * @param integer $invoiceId The invoice ID.
     * @param mixed|null $updatedSince Only return invoice messages that have been updated since the given date and
     * time.
     * @param integer $page The page number to use in pagination. For instance, if you make a list request and receive
     * 100 records, your subsequent call can include page=2 to retrieve the next page of the list. (Default: 1)
     * @param integer $perPage The number of records to return per page. Can range between 1 and 100. (Default: 100)
     *
     * @return mixed
     */
    public function get($invoiceId, $updatedSince = null, $page = null,
                        $perPage = null)
    {
        $uri = "invoices/" . $invoiceId . "/messages";

        $data = [];

        if (!is_null($updatedSince)) $data['updated_since'] = $updatedSince;
        if (!is_null($page)) $data['page'] = $page;
        if (!is_null($perPage)) $data['per_page'] = $perPage;

        return $this->api->get($uri, $data);
    }

    /**
     * Create an invoice message.
     *
     * Creates a new invoice message object. Returns an invoice message object and a 201 Created response code if the
     * call succeeded.
     *
     * @param integer $invoiceId The ID of the invoice that a message is being created for.
     * @param array $recipients Array of recipient parameters. See below for details.
     * @param string|null $subject The message subject.
     * @param string|null $body The message body.
     * @param boolean|null $includeLinkToClientInvoice Include client invoice URL in the message email?
     * @param boolean|null $attachPdf Attach a PDF of the invoice to the message email?
     * @param boolean|null $sendMeACopy If set to true, a copy of the message email will be sent to the current user.
     * @param boolean|null $thankYou If set to true, a thank you message email will be sent.
     * @param boolean|null $eventType If provided, runs an event against the invoice.
     *
     * Array structure for $recipients
     *
     * @var string $name Name of the message recipient. (optional)
     * @var string $email Email of the message recipient. (required)
     *
     * @return mixed
     */
    public function create($invoiceId, $recipients, $subject = null,
                           $body = null, $includeLinkToClientInvoice = null,
                           $attachPdf = null, $sendMeACopy = null,
                           $thankYou = null, $eventType = null)
    {
        $uri = "invoices/" . $invoiceId . "/messages";

        $data = [
            'recipients' => $recipients,
        ];

        if (!is_null($subject)) $data['subject'] = $subject;
        if (!is_null($body)) $data['body'] = $body;
        if (!is_null($includeLinkToClientInvoice)) {
            $data['include_link_to_client_invoice'] =
                $includeLinkToClientInvoice;
        }
        if (!is_null($attachPdf)) $data['attach_pdf'] = $attachPdf;
        if (!is_null($sendMeACopy)) $data['send_me_a_copy'] = $sendMeACopy;
        if (!is_null($thankYou)) $data['thank_you'] = $thankYou;
        if (!is_null($eventType)) $data['event_type'] = $eventType;

        return $this->api->post($uri, $data);
    }

    /**
     * Delete an invoice message.
     *
     * Delete an invoice message. Returns a 200 OK response code if the call
     * succeeded.
     *
     * @param integer $invoiceId The invoice ID.
     * @param integer $messageId The message ID.
     *
     * @return mixed
     */
    public function delete($invoiceId, $messageId)
    {
        $uri = "invoices/" . $invoiceId . "/messages/" . $messageId;

        return $this->api->delete($uri);
    }

    /**
     * Mark a draft invoice as sent.
     *
     * Creates a new invoice message object and marks the invoice as sent.
     * Returns an invoice message object and a 201 Created response code if the
     * call succeeded.
     *
     * @param integer $invoiceId The invoice ID.
     *
     * @return mixed
     */
    public function markDraftInvoiceAsSent($invoiceId)
    {
        $uri = "invoices/" . $invoiceId . "/messages";

        $data = [
            'event_type' => 'send',
        ];

        return $this->api->post($uri, $data);
    }

    /**
     * Mark an open invoice as closed.
     *
     * Creates a new invoice message object and marks the invoice as closed.
     * Returns an invoice message object and a 201 Created response code if the
     * call succeeded.
     *
     * @param integer $invoiceId The invoice ID.
     *
     * @return mixed
     */
    public function markOpenInvoiceAsClosed($invoiceId)
    {
        $uri = "invoices/" . $invoiceId . "/messages";

        $data = [
            'event_type' => 'close',
        ];

        return $this->api->post($uri, $data);
    }

    /**
     * Re-open a closed invoice.
     *
     * Creates a new invoice message object and re-opens a closed invoice.
     * Returns an invoice message object and a 201 Created response code if the
     * call succeeded.
     *
     * @param integer $invoiceId The invoice ID.
     *
     * @return mixed
     */
    public function reopenClosedInvoice($invoiceId)
    {
        $uri = "invoices/" . $invoiceId . "/messages";

        $data = [
            'event_type' => 're-open',
        ];

        return $this->api->post($uri, $data);
    }

    /**
     * Mark an open invoice as a draft.
     *
     * Creates a new invoice message object and marks an open invoice as a
     * draft. Returns an invoice message object and a 201 Created response code
     * if the call succeeded.
     *
     * @param integer $invoiceId The invoice ID.
     *
     * @return mixed
     */
    public function markOpenInvoiceAsDraft($invoiceId)
    {
        $uri = "invoices/" . $invoiceId . "/messages";

        $data = [
            'event_type' => 'draft',
        ];

        return $this->api->post($uri, $data);
    }
}