<?php

namespace Djam90\Harvest\Objects;

class EstimateMessageRecipient
{
    /**
     * @var string $name Name of the message recipient.
     */
    protected $name;

    /**
     * @var string $email Email of the message recipient.
     */
    protected $email;

    /**
     * EstimateMessageRecipient constructor.
     *
     * @param string $name Name of the message recipient.
     * @param string $email Email of the message recipient.
     */
    public function __construct($name, $email)
    {
        $this->name = $name;
        $this->email = $email;
    }

    /**
     * Convert this object to an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'name' => $this->name,
            'email' => $this->email
        ];
    }
}