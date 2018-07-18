<?php

namespace Djam90\Harvest\Services;

use Djam90\Harvest\BaseService;
use Djam90\Harvest\Objects\User;
use Djam90\Harvest\Objects\PaginatedCollection;

class UserService extends BaseService
{
    protected $path = 'users';

    protected $modelClass = \Djam90\Harvest\Models\User::class;

    /**
     * List all users.
     *
     * Returns a list of your users. The users are returned sorted by creation date, with the most recently created
     * users appearing first.
     *
     * The response contains an object with a users property that contains an array of up to per_page users. Each entry
     * in the array is a separate user object. If no more users are available, the resulting array will be empty.
     *
     * Several additional pagination properties are included in the response to simplify paginating your users.
     *
     * @param boolean|null $isActive Pass true to only return active users and false to return inactive users.
     * @param mixed|null $updatedSince Only return users that have been updated since the given date and time.
     * @param integer|null $page The page number to use in pagination.
     * @param integer|null $perPage The number of records to return per page.
     *
     * @return mixed
     */
    public function get($isActive = null, $updatedSince = null, $page = null, $perPage = null)
    {
        $uri = "/users";

        $data = [];

        if (!is_null($isActive)) $data['is_active'] = $isActive;
        if (!is_null($updatedSince)) $data['updated_since'] = $updatedSince;
        if (!is_null($page)) $data['page'] = $page;
        if (!is_null($perPage)) $data['per_page'] = $perPage;

        $users = $this->api->get($uri, $data);

        return $this->transformResult($users);
    }

    /**
     * Retrieve the currently authenticated user.
     *
     * Retrieves the currently authenticated user. Returns a user object and a
     * 200 OK response code.
     *
     * @return mixed
     */
    public function getCurrentUser()
    {
        $uri = "users/me";

        $user = $this->api->get($uri);

        return $this->transformResult($user);
    }

    /**
     * Retrieve a user.
     *
     * Retrieves the user with the given ID. Returns a user object and a 200 OK response code if a valid identifier was
     * provided.
     *
     * @param integer $userId The user ID.
     *
     * @return mixed
     */
    public function getById($userId)
    {
        $uri = "users/" . $userId;

        return $this->transformResult($this->api->get($uri));
    }

    /**
     * Create a user.
     *
     * Creates a new user object. Returns a user object and a 201 Created response code if the call succeeded.
     *
     * If you want to add a new administrator, set is_admin to true. If you want to add a PM, set is_admin to false,
     * is_project_manager to true, and then set any of the optional permissions to true that you’d like. If you want to
     * add a regular user, set both is_admin and is_project_manager to false.
     *
     * @param string $firstName The first name of the user.
     * @param string $lastName The last name of the user.
     * @param string $email The email address of the user.
     * @param string|null $telephone The telephone number for the user.
     * @param string|null $timezone The user’s timezone. Defaults to the company’s timezone.
     * @param boolean|null $hasAccessToAllFutureProjects Whether the user should be automatically added to future projects. Defaults to false.
     * @param boolean|null $isContractor Whether the user is a contractor or an employee. Defaults to false.
     * @param boolean|null $isAdmin Whether the user has admin permissions. Defaults to false.
     * @param boolean|null $isProjectManager Whether the user has project manager permissions. Defaults to false.
     * @param boolean|null $canSeeRates Whether the user can see billable rates on projects. Only applicable to project managers. Defaults to false.
     * @param boolean|null $canCreateProjects Whether the user can create projects. Only applicable to project managers. Defaults to false.
     * @param boolean|null $canCreateInvoices Whether the user can create invoices. Only applicable to project managers. Defaults to false.
     * @param boolean|null $isActive Whether the user is active or archived. Defaults to true.
     * @param integer|null $weeklyCapacity The number of hours per week this person is available to work in seconds. Defaults to 126000 seconds (35 hours).
     * @param float|null $defaultHourlyRate The billable rate to use for this user when they are added to a project. Defaults to 0.
     * @param float|null $costRate The cost rate to use for this user when calculating a project’s costs vs billable amount. Defaults to 0.
     * @param array|null $roles The role names assigned to this person.
     *
     * @return mixed
     */
    public function create(
        $firstName,
        $lastName,
        $email,
        $telephone = null,
        $timezone = null,
        $hasAccessToAllFutureProjects = null,
        $isContractor = null,
        $isAdmin = null,
        $isProjectManager = null,
        $canSeeRates = null,
        $canCreateProjects = null,
        $canCreateInvoices = null,
        $isActive = null,
        $weeklyCapacity = null,
        $defaultHourlyRate = null,
        $costRate = null,
        $roles = null
    )
    {
        $uri = "users";

        $data = [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $email,
        ];

        if (!is_null($telephone)) $data['telephone'] = $telephone;
        if (!is_null($timezone)) $data['timezone'] = $timezone;
        if (!is_null($hasAccessToAllFutureProjects)) $data['has_access_to_all_future_projects'] = $hasAccessToAllFutureProjects;
        if (!is_null($isContractor)) $data['is_contractor'] = $isContractor;
        if (!is_null($isAdmin)) $data['is_admin'] = $isAdmin;
        if (!is_null($isProjectManager)) $data['is_project_manager'] = $isProjectManager;
        if (!is_null($canSeeRates)) $data['can_see_rates'] = $canSeeRates;
        if (!is_null($canCreateProjects)) $data['can_create_projects'] = $canCreateProjects;
        if (!is_null($canCreateInvoices)) $data['can_create_invoices'] = $canCreateInvoices;
        if (!is_null($isActive)) $data['is_active'] = $isActive;
        if (!is_null($weeklyCapacity)) $data['weekly_capacity'] = $weeklyCapacity;
        if (!is_null($defaultHourlyRate)) $data['default_hourly_rate'] = $defaultHourlyRate;
        if (!is_null($costRate)) $data['cost_rate'] = $costRate;
        if (!is_null($roles)) $data['roles'] = $roles;

        return $this->api->post($uri, $data);
    }

    /**
     * Update a user.
     *
     * Updates the specific user by setting the values of the parameters passed. Any parameters not provided will be
     * left unchanged. Returns a user object and a 200 OK response code if the call succeeded.
     *
     * @param integer $userId The user ID.
     * @param string|null $firstName The first name of the user.
     * @param string|null $lastName The last name of the user.
     * @param string|null $email The email address of the user.
     * @param string|null $telephone The telephone number for the user.
     * @param string|null $timezone The user’s timezone. Defaults to the company’s timezone.
     * @param boolean|null $hasAccessToAllFutureProjects Whether the user should be automatically added to future projects. Defaults to false.
     * @param boolean|null $isContractor Whether the user is a contractor or an employee. Defaults to false.
     * @param boolean|null $isAdmin Whether the user has admin permissions. Defaults to false.
     * @param boolean|null $isProjectManager Whether the user has project manager permissions. Defaults to false.
     * @param boolean|null $canSeeRates Whether the user can see billable rates on projects. Only applicable to project managers. Defaults to false.
     * @param boolean|null $canCreateProjects Whether the user can create projects. Only applicable to project managers. Defaults to false.
     * @param boolean|null $canCreateInvoices Whether the user can create invoices. Only applicable to project managers. Defaults to false.
     * @param boolean|null $isActive Whether the user is active or archived. Defaults to true.
     * @param integer|null $weeklyCapacity The number of hours per week this person is available to work in seconds. Defaults to 126000 seconds (35 hours).
     * @param float|null $defaultHourlyRate The billable rate to use for this user when they are added to a project. Defaults to 0.
     * @param float|null $costRate The cost rate to use for this user when calculating a project’s costs vs billable amount. Defaults to 0.
     * @param array|null $roles The role names assigned to this person.

     * @return mixed
     */
    public function update(
        $userId,
        $firstName = null,
        $lastName = null,
        $email = null,
        $telephone = null,
        $timezone = null,
        $hasAccessToAllFutureProjects = null,
        $isContractor = null,
        $isAdmin = null,
        $isProjectManager = null,
        $canSeeRates = null,
        $canCreateProjects = null,
        $canCreateInvoices = null,
        $isActive = null,
        $weeklyCapacity = null,
        $defaultHourlyRate = null,
        $costRate = null,
        $roles = null
    )
    {
        $uri = "users/" . $userId;

        $data = [];

        if (!is_null($firstName)) $data['first_name'] = $firstName;
        if (!is_null($lastName)) $data['last_name'] = $lastName;
        if (!is_null($email)) $data['email'] = $email;
        if (!is_null($telephone)) $data['telephone'] = $telephone;
        if (!is_null($timezone)) $data['timezone'] = $timezone;
        if (!is_null($hasAccessToAllFutureProjects)) $data['has_access_to_all_future_projects'] = $hasAccessToAllFutureProjects;
        if (!is_null($isContractor)) $data['is_contractor'] = $isContractor;
        if (!is_null($isAdmin)) $data['is_admin'] = $isAdmin;
        if (!is_null($isProjectManager)) $data['is_project_manager'] = $isProjectManager;
        if (!is_null($canSeeRates)) $data['can_see_rates'] = $canSeeRates;
        if (!is_null($canCreateProjects)) $data['can_create_projects'] = $canCreateProjects;
        if (!is_null($canCreateInvoices)) $data['can_create_invoices'] = $canCreateInvoices;
        if (!is_null($isActive)) $data['is_active'] = $isActive;
        if (!is_null($weeklyCapacity)) $data['weekly_capacity'] = $weeklyCapacity;
        if (!is_null($defaultHourlyRate)) $data['default_hourly_rate'] = $defaultHourlyRate;
        if (!is_null($costRate)) $data['cost_rate'] = $costRate;
        if (!is_null($roles)) $data['roles'] = $roles;

        return $this->api->patch($uri, $data);
    }


    /**
     * Delete a user.
     *
     * Delete a user. Deleting a user is only possible if they have no time entries or expenses associated with them.
     * Returns a 200 OK response code if the call succeeded.
     *
     * @param integer $userId The ID of the user to be deleted.
     *
     * @return mixed
     */
    public function delete($userId)
    {
        $uri = "users/" . $userId;

        return $this->api->delete($uri);
    }
}