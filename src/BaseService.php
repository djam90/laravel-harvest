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

    /**
     * Get all items.
     *
     * @return PaginatedCollection|mixed|static
     */
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

    /**
     * Get the last page.
     *
     * @return PaginatedCollection
     */
    public function getLastPage()
    {
        $batch = $this->getPage(1, 1);
        $totalPages = $batch->total_pages;

        if ($totalPages > 1) {
            return $this->getPage($totalPages, 1);
        }

        return $batch;
    }

    /**
     * Get the last item, from the last page.
     *
     * @return mixed
     */
    public function getLastItem()
    {
        return $this->getLastPage()->{$this->path}->first();
    }

    /**
     * Transform the results set.
     *
     * @param $result
     * @return PaginatedCollection|mixed|static
     */
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

    /**
     * Transform an item into its designated model class.
     *
     * @param $result
     * @return mixed
     */
    public function mapToModel($result)
    {
        $class = $this->modelClass;

        return new $class($result);
    }
}