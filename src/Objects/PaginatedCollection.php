<?php

namespace Djam90\Harvest\Objects;

use Djam90\Harvest\BaseService;

class PaginatedCollection
{
    /**
     * @var BaseService $service
     */
    private $service;

    public $path;
    public $next_page;
    public $per_page;
    public $previous_page;
    public $first_page;
    public $last_page;

    public function __construct($data)
    {
        $this->path = $data->path;
        $this->service = $data->service;

        foreach ($data as $key => $val)
        {
            $this->$key = $val;
        }
    }

    public function getCollection()
    {
        return $this->{$this->path};
    }

    public function getNextPage()
    {
        return $this->getPage($this->next_page, $this->per_page);
    }

    public function getPreviousPage()
    {
        return $this->getPage($this->previous_page, $this->per_page);
    }

    public function getFirstPage()
    {
        return $this->getPage($this->first_page, $this->per_page);
    }

    public function getLastPage()
    {
        return $this->service->getPage($this->last_page, $this->per_page);
    }

    public function getPage($page, $perPage = null)
    {
        return $this->service->getPage($page, $perPage);
    }
}