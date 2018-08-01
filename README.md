# laravel-harvest

## A Laravel wrapper around the Harvest time-tracking API 

### General Info
- Requires Laravel 5.4 or above
- Only supports Harvest API v2

### Installation Instructions
Simply run in your terminal `composer require djam90/laravel-harvest`

The package should automatically register the service provider.

You may need to publish the vendor config file by running `php artisan vendor:publish`

### Usage
Add the following to your .env file:

*Please note that the Harvest details must be from an Admin user*
```bash
HARVEST_ACCOUNT_ID=[YOUR_ACCOUNT_ID]
HARVEST_PERSONAL_ACCESS_TOKEN=[YOUR_ACCESS_TOKEN]
```

Import the HarvestService class into your constructor or controller methods:

```php
use Djam90\Harvest\HarvestService;

public function __construct(HarvestService $harvestService)
{
    $this->harvestService = $harvestService;
}
```

Then you can use it in your methods:
```php
public function foo()
{
    $user = $this->harvestService->user->getCurrentUser();
}
```

You can also use the Harvest facade:

```php
use Harvest;

public function foo()
{
    // notice the user() is a method when using the Facade
    Harvest::user()->getCurrentUser();
}
```

### Available Services
The following services can be accessed either using the harvest service or the facade:

```
clientContact
client
company
estimateItemCategory
estimateMessage
estimate
expenseCategory
expense
invoiceItemCategory
invoiceMessage
invoicePayment
invoice
project
projectTaskAssignment
projectUserAssignment
role
task
timeEntry
userProjectAssignment
user
```

#### Example
```
$company = $harvestService->company->get();
$users = $harvestService->user->get();

$company = Harvest::company()->get();
$users = Harvest::user()->get();
```

### Public API
#### clientContact
```php
get($clientId = null, $updatedSince = null, $page = null, $perPage = null)
getPage($page, $perPage = null)
getById($contactId)
create($clientId, $firstName, $lastName = null, $title = null, $email = null, $phoneOffice = null, $phoneMobile = null, $fax = null)
update(contactId, $clientId, $firstName = null, $lastName = null, $title = null, $email = null, $phoneOffice = null, $phoneMobile = null, $fax = null)
delete($contactId)
```

#### client
```php
get($isActive = null, $updatedSince = null, $page = null, $perPage = null)
getPage($page, $perPage = null)
getById($clientId)
create($name, $isActive = null, $address = null, $currency = null)
update($clientId, $name = null, $isActive = null, $address = null, $currency = null)
delete($clientId)
```

#### company
```php
get()
```

#### estimateItemCategory
```php
get($updatedSince = null, $page = null, $perPage = null)
getPage($page, $perPage = null)
getById($estimateItemCategoryId)
create($name)
update($name)
delete($estimateItemCategoryId)
```

#### estimateMessage
```php
get($estimateId, $updatedSince = null, $page = null, $perPage = null)
getPage($estimateId = null, $page, $perPage = null)
getAll()
create($estimateId, array $recipients, $subject = null, $body = null, $sendMeACopy = null, $eventType = null)
delete($estimateId, $messageId)
markDraftEstimateAsSent($estimateId)
markOpenEstimateAsAccepted($estimateId)
markOpenEstimateAsDeclined($estimateId)
reopenClosedEstimate($estimateId)
updateEstimateMessage($estimateId, $eventType)
```

#### estimate
```php
get($clientId = null, $updatedSince = null, $from = null, $to = null, $state = null, $page = null, $perPage = null)
getPage($page, $perPage = null)
getById($estimateId)
create($clientId, $number = null, $purchaseOrder = null, $tax = null, $tax2 = null, $discount = null, $subject = null, $notes = null, $currency = null, $issueDate = null, $lineItems = null)
update($estimateId, $clientId = null, $number = null, $purchaseOrder = null, $tax = null, $tax2 = null, $discount = null, $subject = null, $notes = null, $currency = null, $issueDate = null, $lineItems = null)
createLineItem($estimateId, $kind, $unitPrice, $description = null, $quantity = null, $taxed = null, $taxed2 = null)
updateLineItem($estimateId, $lineItemId, $kind = null, $description = null, $quantity = null, $unitPrice = null, $taxed = null, $taxed2 = null)
deleteLineItem($estimateId, $lineItemId)
delete($estimateId)
```

#### expenseCategory
```php
get($isActive = null, $updatedSince = null, $page = null, $perPage = null)
getPage($page, $perPage = null)
getById($expenseCategoryId)
create($name, $unitName = null, $unitPrice = null, $isActive = null)
update($expenseCategoryId, $name = null, $unitName = null, $unitPrice = null, $isActive = null)
delete($expenseCategoryId)
```

#### expense
```php
get($userId = null, $clientId = null, $projectId = null, $isBilled = null, $updatedSince = null, $from = null, $to = null, $page = null, $perPage = null)
getPage($page, $perPage = null)
getById($expenseId)
create($projectId, $expenseCategoryId, $spentDate, $userId = null, $units = null, $totalCost = null, $notes = null, $billable = null, $receipt = null)
update($expenseId, $projectId = null, $expenseCategoryId = null, $spentDate = null, $userId = null, $units = null, $totalCost = null, $notes = null, $billable = null, $receipt = null, $deleteReceipt = null)
delete($expenseId)
```

#### invoiceItemCategory
```php
get($updatedSince = null, $page = null, $perPage = null)
getPage($page, $perPage = null)
getById($invoiceItemCategoryId)
create($name)
update($name)
delete($invoiceItemCategoryId)
```

#### invoiceMessage
```php
get($invoiceId, $updatedSince = null, $page = null, $perPage = null)
getPage($invoiceId, $page, $perPage = null)
getAll($invoiceId)
create($invoiceId, $recipients, $subject = null, $body = null, $includeLinkToClientInvoice = null, $attachPdf = null, $sendMeACopy = null, $thankYou = null, $eventType = null)
delete($invoiceId, $messageId)
markDraftInvoiceAsSent($invoiceId)
markOpenInvoiceAsClosed($invoiceId)
reopenClosedInvoice($invoiceId)
markOpenInvoiceAsDraft($invoiceId)
```

#### invoicePayment
```php
get($invoiceId, $updatedSince = null, $page = null,$perPage = null)
getPage($invoiceId, $page, $perPage = null)
getAll($invoiceId)
create($invoiceId, $amount, $paidAt = null, $notes = null)
delete($invoiceId, $paymentId)
```

#### invoice
```php
get($clientId = null, $projectId = null, $updatedSince = null, $page = null, $perPage = null)
getPage($page, $perPage = null)
getById($invoiceId)
create($clientId, $retainerId = null, $estimateId = null, $number = null, $purchaseOrder = null, $tax = null, $tax2 = null, $discount = null, $subject = null, $notes = null, $currency = null, $issueDate = null, $dueDate = null, $lineItems = null)
update($invoiceId, $clientId, $retainerId = null, $estimateId = null, $number = null, $purchaseOrder = null, $tax = null, $tax2 = null, $discount = null, $subject = null, $notes = null, $currency = null, $issueDate = null, $dueDate = null, $lineItems = null)
createLineItem($invoiceId, $kind, $unitPrice, $projectId = null, $description = null, $quantity = null, $taxed = null, $taxed2 = null)
updateLineItem($invoiceId, $lineItemId, $kind = null, $unitPrice = null, $projectId = null, $description = null, $quantity = null, $taxed = null, $taxed2 = null)
deleteLineItem($invoiceId, $lineItemId)
delete($invoiceId)
```

#### project
```php
get($isActive = null, $clientId = null, $updatedSince = null, $page = null, $perPage = null)
getPage($page, $perPage = null)
getById($projectId)
create($clientId, $name, $isBillable, $billBy, $budgetBy, $code = null, $isActive = null, $isFixedFee = null, $hourlyRate = null, $budget = null, $notifyWhenOverBudget = null, $overBudgetNotificationPercentage = null, $showBudgetToAll = null, $costBudget = null, $costBudgetIncludeExpenses = null, $fee = null, $notes = null, $startsOn = null, $endsOn = null)
update($projectId, $clientId = null, $name = null, $isBillable = null, $billBy = null, $budgetBy = null, $code = null, $isActive = null, $isFixedFee = null, $hourlyRate = null, $budget = null, $notifyWhenOverBudget = null, $overBudgetNotificationPercentage = null, $showBudgetToAll = null, $costBudget = null, $costBudgetIncludeExpenses = null, $fee = null, $notes = null, $startsOn = null, $endsOn = null)
delete($projectId)
```

#### projectTaskAssignment
```php
get($projectId, $isActive = null, $updatedSince = null, $page = null, $perPage = null)
getPage($projectId, $page, $perPage = null)
getAll($projectId)
getById($projectId, $taskAssignmentId)
create($projectId, $taskId, $isActive = null, $billable = null, $hourlyRate = null, $budget = null)
update($projectId, $taskAssignmentId, $isActive = null, $billable = null, $hourlyRate = null, $budget = null)
delete($projectId, $taskAssignmentId)
```

#### projectUserAssignment
```php
get($projectId, $isActive = null, $updatedSince = null, $page = null, $perPage = null)
getPage($projectId, $page, $perPage = null)
getAll($projectId)
getById($projectId, $userAssignmentId)
create($projectId, $userId, $isActive = null, $isProjectManager = null, $hourlyRate = null, $budget = null)
update($projectId, $userAssignmentId, $isActive = null, $isProjectManager = null, $hourlyRate = null, $budget = null)
delete($projectId, $userAssignmentId)
```

#### role
```php
get($page = null, $perPage = null)
getPage($page, $perPage = null)
getById($roleId)
create($name, $userIds = null)
update($roleId, $name, $userIds = null)
delete($roleId)
```

#### task
```php
get($isActive = null, $updatedSince = null, $page = null, $perPage = null)
getPage($page, $perPage = null)
getById($taskId)
create($name, $billableByDefault = null, $defaultHourlyRate = null, $isDefault = null, $isActive = null)
update($taskId, $name = null, $billableByDefault = null, $defaultHourlyRate = null, $isDefault = null, $isActive = null)
delete($taskId)
```

#### timeEntry
```php
get($userId, $clientId, $projectId, $isBilled = null, $isRunning = null, $updatedSince = null, $from = null, $to = null, $page = null, $perPage = null)
getPage($userId = null, $clientId = null, $projectId = null, $page, $perPage = null)
getAll($userId = null, $clientId = null, $projectId = null)
getById($timeEntryId)
createForDuration($projectId, $taskId, $spentDate, $userId = null, $hours = null, $notes = null, $externalReference = null)
createForStartAndEndTime($projectId, $taskId, $spentDate, $userId = null, $startedTime = null, $endedTime = null, $notes = null, $externalReference = null)
update($timeEntryId, $projectId = null, $taskId = null, $spentDate = null, $startedTime = null, $endedTime = null, $hours = null, $notes = null, $externalReference = null)
delete($timeEntryId)
restart($timeEntryId)
stop($timeEntryId)
```

#### userProjectAssignment
```php
get($userId, $updatedSince = null, $page = null, $perPage = null)
getPage($userId = null, $page, $perPage = null)
getAll($userId)
getForCurrentUser($page = null, $perPage = null)
```

#### user
```php
get($isActive = null, $updatedSince = null, $page = null, $perPage = null)
getPage($page, $perPage = null)
getCurrentUser()
getById($userId)
create($firstName, $lastName, $email, $telephone = null, $timezone = null, $hasAccessToAllFutureProjects = null, $isContractor = null, $isAdmin = null, $isProjectManager = null, $canSeeRates = null, $canCreateProjects = null, $canCreateInvoices = null, $isActive = null, $weeklyCapacity = null, $defaultHourlyRate = null, $costRate = null, $roles = null)
update($userId, $firstName = null, $lastName = null, $email = null, $telephone = null, $timezone = null, $hasAccessToAllFutureProjects = null, $isContractor = null, $isAdmin = null, $isProjectManager = null, $canSeeRates = null, $canCreateProjects = null, $canCreateInvoices = null, $isActive = null, $weeklyCapacity = null, $defaultHourlyRate = null, $costRate = null, $roles = null)
delete($userId)
```