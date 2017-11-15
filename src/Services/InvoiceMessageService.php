<?php

namespace Djam90\Harvest\Services;

use Djam90\Harvest\BaseService;

class InvoiceMessageService extends BaseService
{
    public function get($invoiceId, $updatedSince = null, $page = 1, $perPage = 100)
    {
        $uri = "invoices/" . $invoiceId . "/messages";

        $data = [];

        if (!is_null($updatedSince)) $data['updated_since'] = $updatedSince;
        if (!is_null($page)) $data['page'] = $page;
        if (!is_null($perPage)) $data['per_page'] = $perPage;

        return $this->httpGet($uri, $data);
    }

    /**
     * Create an invoice message.
     *
     * Creates a new invoice message object. Returns an invoice message object and a 201 Created response code if the
     * call succeeded.
     *
     * @param int $invoiceId The ID of the invoice that a message is being created for.
     * @param array $recipients Array of recipient parameters. See below for details.
     * @param string|null $subject The message subject.
     * @param string|null $body The message body.
     * @param bool|null $includeLinkToClientInvoice Include client invoice URL in the message email?
     * @param bool|null $attachPdf Attach a PDF of the invoice to the message email?
     * @param bool|null $sendMeACopy If set to true, a copy of the message email will be sent to the current user.
     * @param bool|null $thankYou If set to true, a thank you message email will be sent.
     * @param bool|null $eventType If provided, runs an event against the invoice.
     *
     * Array structure for $recipients
     *
     * @var string $name Name of the message recipient. (optional)
     * @var string $email Email of the message recipient. (required)
     *
     * @return mixed
     */
    public function create($invoiceId, $recipients, $subject = null, $body = null, $includeLinkToClientInvoice = null,
                           $attachPdf = null, $sendMeACopy = null, $thankYou = null, $eventType = null)
    {
        $uri = "invoices/" . $invoiceId . "/messages";

        $data = [
            'recipients' => $recipients,
        ];

        if (!is_null($subject)) $data['subject'] = $subject;
        if (!is_null($body)) $data['body'] = $body;
        if (!is_null($includeLinkToClientInvoice)) $data['include_link_to_client_invoice'] = $includeLinkToClientInvoice;
        if (!is_null($attachPdf)) $data['attach_pdf'] = $attachPdf;
        if (!is_null($sendMeACopy)) $data['send_me_a_copy'] = $sendMeACopy;
        if (!is_null($thankYou)) $data['thank_you'] = $thankYou;
        if (!is_null($eventType)) $data['event_type'] = $eventType;

        return $this->httpPost($uri, $data);
    }

    /**
     * Delete an invoice message.
     *
     * Delete an invoice message. Returns a 200 OK response code if the call succeeded.
     *
     * @param int $invoiceId The invoice ID.
     * @param int $messageId The message ID.
     *
     * @return mixed
     */
    public function delete($invoiceId, $messageId)
    {
        $uri = "invoices/" . $invoiceId . "/messages/" . $messageId;

        return $this->httpDelete($uri);
    }

    /**
     * Mark a draft invoice as sent.
     *
     * Creates a new invoice message object and marks the invoice as sent. Returns an invoice message object and a 201
     * Created response code if the call succeeded.
     *
     * @param int $invoiceId The invoice ID.
     *
     * @return mixed
     */
    public function markDraftInvoiceAsSent($invoiceId)
    {
        $uri = "invoices/" . $invoiceId . "/messages";

        $data = [
            'event_type' => 'send',
        ];

        return $this->httpPost($uri, $data);
    }

    /**
     * Mark an open invoice as closed.
     *
     * Creates a new invoice message object and marks the invoice as closed. Returns an invoice message object and a 201
     * Created response code if the call succeeded.
     *
     * @param int $invoiceId The invoice ID.
     *
     * @return mixed
     */
    public function markOpenInvoiceAsClosed($invoiceId)
    {
        $uri = "invoices/" . $invoiceId . "/messages";

        $data = [
            'event_type' => 'close',
        ];

        return $this->httpPost($uri, $data);
    }

    /**
     * Re-open a closed invoice.
     *
     * Creates a new invoice message object and re-opens a closed invoice. Returns an invoice message object and a 201
     * Created response code if the call succeeded.
     *
     * @param int $invoiceId The invoice ID.
     *
     * @return mixed
     */
    public function reopenClosedInvoice($invoiceId)
    {
        $uri = "invoices/" . $invoiceId . "/messages";

        $data = [
            'event_type' => 're-open',
        ];

        return $this->httpPost($uri, $data);
    }

    /**
     * Mark an open invoice as a draft.
     *
     * Creates a new invoice message object and marks an open invoice as a draft. Returns an invoice message object and
     * a 201 Created response code if the call succeeded.
     *
     * @param int $invoiceId The invoice ID.
     *
     * @return mixed
     */
    public function markOpenInvoiceAsDraft($invoiceId)
    {
        $uri = "invoices/" . $invoiceId . "/messages";

        $data = [
            'event_type' => 'draft',
        ];

        return $this->httpPost($uri, $data);
    }
}