<?php

namespace Djam90\Harvest;

use Djam90\Harvest\Services\ClientContactService;
use Djam90\Harvest\Services\ClientService;
use Djam90\Harvest\Services\CompanyService;
use Djam90\Harvest\Services\InvoiceMessageService;
use Djam90\Harvest\Services\InvoiceService;
use Djam90\Harvest\Services\UserService;
use Exception;
use \GuzzleHttp\Client;

class HarvestService extends BaseService
{
    /**
     * @var UserService $user
     */
    private $user;

    /**
     * @var InvoiceService $invoice
     */
    private $invoice;

    /**
     * @var ClientContactService $clientContact
     */
    private $clientContact;

    /**
     * @var ClientService $client
     */
    private $client;

    /**
     * @var CompanyService $company
     */
    private $company;

    /**
     * @var InvoiceMessageService $invoiceMessage
     */
    private $invoiceMessage;

    /**
     * HarvestService constructor.
     *
     * @param ClientContactService $clientContactService
     * @param ClientService $clientService
     * @param CompanyService $companyService
     * @param InvoiceService $invoiceService
     * @param UserService $harvestUserService
     */
    public function __construct(
        ClientContactService $clientContactService,
        ClientService $clientService,
        CompanyService $companyService,
        InvoiceService $invoiceService,
        InvoiceMessageService $invoiceMessageService,
        UserService $harvestUserService
    )
    {
        parent::__construct();
        $this->client = $clientService;
        $this->clientContact = $clientContactService;
        $this->company = $companyService;
        $this->invoice = $invoiceService;
        $this->invoiceMessage = $invoiceMessageService;
        $this->user = $harvestUserService;
    }

    public function __get($property)
    {
        switch ($property) {
            case 'company':
                return $this->company;
                break;

            case 'client':
                return $this->client;
                break;

            case 'clientContact':
                return $this->clientContact;
                break;

            case 'invoice':
                return $this->invoice;
                break;

            case 'invoiceMessage':
                return $this->invoiceMessage;
                break;

            case 'user':
                return $this->user;
                break;

            default:
                throw new Exception("Property not found");
                break;
        }
    }

}