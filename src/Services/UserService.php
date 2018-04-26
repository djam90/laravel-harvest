<?php

namespace Djam90\Harvest\Services;

use Djam90\Harvest\BaseService;

class UserService extends BaseService
{
    /**
     * Retrieve the currently authenticated user.
     *
     * Retrieves the currently authenticated user. Returns a user object and a
     * 200 OK response code.
     *
     * @return mixed
     */
    public function getUser()
    {
        $uri = "users/me";

        return $this->httpGet($uri);
    }
}