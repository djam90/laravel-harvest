<?php


namespace Djam90\Harvest\Services;

use Djam90\Harvest\BaseService;
use Djam90\Harvest\Objects\EstimateMessageRecipient;

class EstimateMessageService extends BaseService
{
    /**
     * List all messages for an estimate.
     *
     * Returns a list of messages associated with a given estimate. The estimate
     * messages are returned sorted by creation date, with the most recently
     * created messages appearing first.
     *
     * The response contains an object with an estimate_messages property that
     * contains an array of up to per_page messages. Each entry in the array is
     * a separate message object. If no more messages are available, the
     * resulting array will be empty. Several additional pagination properties
     * are included in the response to simplify paginating your messages.
     *
     * @param integer $estimateId The estimate ID.
     * @param mixed|null $updatedSince Only return estimate messages that have been updated since the given date and
     * time.
     * @param integer|null $page The page number to use in pagination.
     * @param integer|null $perPage The number of records to return per page.
     *
     * @return mixed
     */
    public function get($estimateId, $updatedSince = null, $page = null,
                        $perPage = null)
    {
        $uri = "estimates/" . $estimateId . "/messages";

        $data = [];

        if (!is_null($updatedSince)) $data['updated_since'] = $updatedSince;
        if (!is_null($page)) $data['page'] = $page;
        if (!is_null($perPage)) $data['per_page'] = $perPage;

        // @todo validate An ISO 8601 formatted string containing a UTC date
        // and time.

        return $this->httpGet($uri, $data);
    }

    /**
     * Create an estimate message.
     *
     * Creates a new estimate message object. Returns an estimate message
     * object and a 201 Created response code if the call succeeded.
     *
     * @param integer $estimateId The estimate ID.
     * @param array $recipients An array of EstimateMessageRecipient objects.
     * @param string|null $subject The message subject.
     * @param string|null $body The message body.
     * @param boolean|null $sendMeACopy If set to true, a copy of the message email will be sent to the current user.
     * Defaults to false.
     * @param string|null $eventType If provided, runs an event against the estimate. Options: "accept", "decline",
     * "re-open", or "send".
     *
     * @return mixed
     */
    public function create($estimateId, array $recipients, $subject = null,
                           $body = null, $sendMeACopy = null, $eventType = null)
    {
        $uri = "estimates/" . $estimateId . "/messages";

        $data = [
            'recipients' => array_map(function (EstimateMessageRecipient $item) {
                return $item->toArray();
            }, $recipients),
        ];

        if (!is_null($subject)) $data['subject'] = $subject;
        if (!is_null($body)) $data['body'] = $body;
        if (!is_null($sendMeACopy)) $data['send_me_a_copy'] = $sendMeACopy;
        if (!is_null($eventType)) $data['event_type'] = $eventType;

        return $this->httpPost($uri, $data);
    }

    /**
     * Delete an estimate message.
     *
     * Delete an estimate message. Returns a 200 OK response code if the call
     * succeeded.
     *
     * @param integer $estimateId The estimate ID.
     * @param integer $messageId The message ID.
     *
     * @return mixed
     */
    public function delete($estimateId, $messageId)
    {
        $uri = "estimates/" . $estimateId . "/messages/" . $messageId;
        
        return $this->httpDelete($uri);
    }

    /**
     * Mark a draft estimate as sent.
     *
     * Creates a new estimate message object and marks the estimate as sent.
     * Returns an estimate message object and a 201 Created response code if
     * the call succeeded.
     *
     * @param integer $estimateId The estimate ID.
     *
     * @return mixed
     */
    public function markDraftEstimateAsSent($estimateId)
    {
        return $this->updateEstimateMessage($estimateId, 'send');
    }

    /**
     * Mark an open estimate as accepted.
     *
     * Creates a new estimate message object and marks the estimate as accepted.
     * Returns an estimate message object and a 201 Created response code if
     * the call succeeded.
     *
     * @param integer $estimateId The estimate ID.
     *
     * @return mixed
     */
    public function markOpenEstimateAsAccepted($estimateId)
    {
        return $this->updateEstimateMessage($estimateId, 'accept');
    }

    /**
     * Mark an open estimate as declined.
     *
     * Creates a new estimate message object and marks the estimate as declined.
     * Returns an estimate message object and a 201 Created response code if
     * the call succeeded.
     *
     * @param integer $estimateId The estimate ID.
     *
     * @return mixed
     */
    public function markOpenEstimateAsDeclined($estimateId)
    {
        return $this->updateEstimateMessage($estimateId, 'decline');
    }

    /**
     * Re-open a closed estimate
     *
     * Creates a new estimate message object and re-opens a closed estimate.
     * Returns an estimate message object and a 201 Created response code if
     * the call succeeded.
     *
     * @param integer $estimateId The estimate ID.
     *
     * @return mixed
     */
    public function reopenClosedEstimate($estimateId)
    {
        return $this->updateEstimateMessage($estimateId, 're-open');
    }

    /**
     * Create an estimate message object with a given event type.
     *
     * @param integer $estimateId The estimate ID.
     * @param string $eventType The event type of the message object.
     *
     * @return mixed
     */
    private function updateEstimateMessage($estimateId, $eventType)
    {
        $uri = "estimates/" . $estimateId . "/messages";

        $data = [
            'event_type' => $eventType
        ];

        return $this->httpPost($uri, $data);
    }
}