<?php

namespace Djam90\Harvest;

use Exception;
use GuzzleHttp\Client;
use Djam90\Harvest\Api\Gateway;
use Djam90\Harvest\Models\Base;
use Djam90\Harvest\Objects\PaginatedCollection;

class BaseService
{
    protected $api;

    /**
     * @var Client $apiClient
     */
    protected $apiClient;

    protected $uri;
    private $token;
    private $account_id;

    protected $modelClass = Base::class;

    /**
     * BaseService constructor.
     * @param Gateway $api
     */
    public function __construct(Gateway $api)
    {
        $this->api = $api;
    }

    public function getAll()
    {
        $batch = $this->get();
        $items = $batch->{$this->path};
        $totalPages = $batch->total_pages;

        if ($totalPages > 1) {
            while (!is_null($batch->next_page)) {
                $batch = $this->getPage($batch->next_page);
                $items = $items->merge($batch->{$this->path});
            }
        }
        return $this->transformResult($items);
    }

    public function transformResult($result)
    {
        $path = $this->path;

        if (isset($result->total_entries)) {
            $items = $result->$path;
            $result->$path = collect($items)->map(function ($item) {
                return $this->mapToModel($item);
            });

            $result->path = $path;
            $result->service = $this;
            return new PaginatedCollection($result);

        } else if ($result instanceof \Illuminate\Support\Collection) {
            return $result->map(function ($item) {
                return $this->mapToModel($item);
            });

        } else {
            return $this->mapToModel($result);
        }
    }

    public function mapToModel($result)
    {
        $class = $this->modelClass;

        return new $class($result);
    }
}