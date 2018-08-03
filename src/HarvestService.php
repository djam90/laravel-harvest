<?php

namespace Djam90\Harvest;

use Djam90\Harvest\Helpers\Currency;
use Exception;
use Djam90\Harvest\Services\ClientContactService;
use Djam90\Harvest\Services\ClientService;
use Djam90\Harvest\Services\CompanyService;
use Djam90\Harvest\Services\EstimateItemCategoryService;
use Djam90\Harvest\Services\EstimateMessageService;
use Djam90\Harvest\Services\EstimateService;
use Djam90\Harvest\Services\ExpenseCategoryService;
use Djam90\Harvest\Services\ExpenseService;
use Djam90\Harvest\Services\InvoiceItemCategoryService;
use Djam90\Harvest\Services\InvoiceMessageService;
use Djam90\Harvest\Services\InvoicePaymentService;
use Djam90\Harvest\Services\InvoiceService;
use Djam90\Harvest\Services\ProjectService;
use Djam90\Harvest\Services\ProjectTaskAssignmentService;
use Djam90\Harvest\Services\ProjectUserAssignmentService;
use Djam90\Harvest\Services\RoleService;
use Djam90\Harvest\Services\TaskService;
use Djam90\Harvest\Services\TimeEntryService;
use Djam90\Harvest\Services\UserProjectAssignmentService;
use Djam90\Harvest\Services\UserService;

class HarvestService extends BaseService
{
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
     * @var EstimateItemCategoryService
     */
    private $estimateItemCategory;

    /**
     * @var EstimateMessageService
     */
    private $estimateMessage;

    /**
     * @var EstimateService
     */
    private $estimate;

    /**
     * @var ExpenseCategoryService
     */
    private $expenseCategory;

    /**
     * @var ExpenseService
     */
    private $expense;

    /**
     * @var InvoiceItemCategoryService
     */
    private $invoiceItemCategory;

    /**
     * @var InvoiceMessageService $invoiceMessage
     */
    private $invoiceMessage;

    /**
     * @var InvoicePaymentService
     */
    private $invoicePayment;

    /**
     * @var InvoiceService $invoice
     */
    private $invoice;

    /**
     * @var ProjectService $project
     */
    private $project;

    /**
     * @var ProjectTaskAssignmentService
     */
    private $projectTaskAssignment;

    /**
     * @var ProjectUserAssignmentService
     */
    private $projectUserAssignment;

    /**
     * @var RoleService
     */
    private $role;

    /**
     * @var TaskService
     */
    private $task;

    /**
     * @var TimeEntryService
     */
    private $timeEntry;

    /**
     * @var UserProjectAssignmentService
     */
    private $userProjectAssignment;

    /**
     * @var UserService $user
     */
    private $user;

    /**
     * HarvestService constructor.
     *
     * @param ClientContactService $clientContactService
     * @param ClientService $clientService
     * @param CompanyService $companyService
     * @param EstimateItemCategoryService $estimateItemCategoryService
     * @param EstimateMessageService $estimateMessageService
     * @param EstimateService $estimateService
     * @param ExpenseCategoryService $expenseCategoryService
     * @param ExpenseService $expenseService
     * @param InvoiceItemCategoryService $invoiceItemCategoryService
     * @param InvoiceMessageService $invoiceMessageService
     * @param InvoicePaymentService $invoicePaymentService
     * @param InvoiceService $invoiceService
     * @param ProjectService $projectService
     * @param ProjectTaskAssignmentService $projectTaskAssignmentService
     * @param ProjectUserAssignmentService $projectUserAssignmentService
     * @param RoleService $roleService
     * @param TaskService $taskService
     * @param TimeEntryService $timeEntryService
     * @param UserProjectAssignmentService $userProjectAssignmentService
     * @param UserService $harvestUserService
     * @throws Exception
     */
    public function __construct(
        ClientContactService $clientContactService,
        ClientService $clientService,
        CompanyService $companyService,
        EstimateItemCategoryService $estimateItemCategoryService,
        EstimateMessageService $estimateMessageService,
        EstimateService $estimateService,
        ExpenseCategoryService $expenseCategoryService,
        ExpenseService $expenseService,
        InvoiceItemCategoryService $invoiceItemCategoryService,
        InvoiceMessageService $invoiceMessageService,
        InvoicePaymentService $invoicePaymentService,
        InvoiceService $invoiceService,
        ProjectService $projectService,
        ProjectTaskAssignmentService $projectTaskAssignmentService,
        ProjectUserAssignmentService $projectUserAssignmentService,
        RoleService $roleService,
        TaskService $taskService,
        TimeEntryService $timeEntryService,
        UserProjectAssignmentService $userProjectAssignmentService,
        UserService $harvestUserService
    )
    {
        parent::__construct(
            new Api\Gateway(),
            new Currency()
        );

        $this->client = $clientService;
        $this->clientContact = $clientContactService;
        $this->company = $companyService;
        $this->estimateItemCategory = $estimateItemCategoryService;
        $this->estimateMessage = $estimateMessageService;
        $this->estimate = $estimateService;
        $this->expenseCategory = $expenseCategoryService;
        $this->expense = $expenseService;
        $this->invoiceItemCategory = $invoiceItemCategoryService;
        $this->invoiceMessage = $invoiceMessageService;
        $this->invoicePayment = $invoicePaymentService;
        $this->invoice = $invoiceService;
        $this->project = $projectService;
        $this->projectTaskAssignment = $projectTaskAssignmentService;
        $this->projectUserAssignment = $projectUserAssignmentService;
        $this->role = $roleService;
        $this->task = $taskService;
        $this->timeEntry = $timeEntryService;
        $this->userProjectAssignment = $userProjectAssignmentService;
        $this->user = $harvestUserService;
    }

    /**
     * @param $property
     * @return mixed
     * @throws Exception
     */
    public function __get($property)
    {
        if (property_exists($this, $property)) {
            return $this->{$property};
        } else {
            throw new \Exception("API service does not exist for service $property.");
        }
    }

    /**
     * @param $method
     * @param array $arguments
     * @return mixed
     * @throws Exception
     */
    public function __call($method, array $arguments)
    {
        return $this->__get($method);
    }
}