<?php

namespace Djam90\Harvest\Services;

use Djam90\Harvest\BaseService;

class UserService extends BaseService
{
    public function getUser()
    {
        $uri = "users/me.json";

        return $this->httpGet($uri);
    }
}