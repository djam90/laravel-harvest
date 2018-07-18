<?php

namespace Djam90\Harvest\Services;

use Djam90\Harvest\BaseService;

class CompanyService extends BaseService
{
    protected $path = 'company';

    protected $modelClass = \Djam90\Harvest\Models\Company::class;

    /**
     * Get the company.
     *
     * Retrieves the company for the currently authenticated user. Returns a
     * company object and a 200 OK response code.
     *
     * @return mixed
     */
    public function get()
    {
        $uri = "company";

        return $this->transformResult($this->api->get($uri));
    }
}