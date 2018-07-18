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
```
HARVEST_ACCOUNT_ID=[YOUR_ACCOUNT_ID]
HARVEST_PERSONAL_ACCESS_TOKEN=[YOUR_ACCESS_TOKEN]
```

Import the HarvestService class into your constructor or controller methods:

```
use Djam90\Harvest\HarvestService;

public function __construct(HarvestService $harvestService)
{
    $this->harvestService = $harvestService;
}
```

Then you can use it in your methods:
```
public function foo()
{
    $user = $this->harvestService->user->getCurrentUser();
}
```

You can also use the Harvest facade:

```
use Harvest;

public function foo()
{
    // notice the user() is a method when using the Facade
    Harvest::user()->getCurrentUser();
}
```

### API
```
$harvestService = $this->harvestService;

$harvestService->user->getCurrentUser();
```