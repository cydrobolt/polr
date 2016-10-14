<?php

namespace Yajra\Datatables\Engines;

use Closure;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Yajra\Datatables\Request;

/**
 * Class CollectionEngine.
 *
 * @package Yajra\Datatables\Engines
 * @author  Arjay Angeles <aqangeles@gmail.com>
 */
class CollectionEngine extends BaseEngine
{
    /**
     * Collection object
     *
     * @var \Illuminate\Support\Collection
     */
    public $collection;

    /**
     * Collection object
     *
     * @var \Illuminate\Support\Collection
     */
    public $original_collection;

    /**
     * CollectionEngine constructor.
     *
     * @param \Illuminate\Support\Collection $collection
     * @param \Yajra\Datatables\Request $request
     */
    public function __construct(Collection $collection, Request $request)
    {
        $this->request             = $request;
        $this->collection          = $collection;
        $this->original_collection = $collection;
        $this->columns             = array_keys($this->serialize($collection->first()));
    }

    /**
     * Serialize collection
     *
     * @param  mixed $collection
     * @return mixed|null
     */
    protected function serialize($collection)
    {
        return $collection instanceof Arrayable ? $collection->toArray() : (array) $collection;
    }

    /**
     * Set auto filter off and run your own filter.
     * Overrides global search.
     *
     * @param \Closure $callback
     * @param bool $globalSearch
     * @return $this
     */
    public function filter(Closure $callback, $globalSearch = false)
    {
        $this->overrideGlobalSearch($callback, $this, $globalSearch);

        return $this;
    }

    /**
     * Append debug parameters on output.
     *
     * @param  array $output
     * @return array
     */
    public function showDebugger(array $output)
    {
        $output["input"] = $this->request->all();

        return $output;
    }

    /**
     * Count total items.
     *
     * @return integer
     */
    public function totalCount()
    {
        return $this->count();
    }

    /**
     * Count results.
     *
     * @return integer
     */
    public function count()
    {
        return $this->collection->count();
    }

    /**
     * Perform sorting of columns.
     *
     * @return void
     */
    public function ordering()
    {
        if ($this->orderCallback) {
            call_user_func($this->orderCallback, $this);

            return;
        }

        foreach ($this->request->orderableColumns() as $orderable) {
            $column           = $this->getColumnName($orderable['column']);
            $this->collection = $this->collection->sortBy(
                function ($row) use ($column) {
                    $data = $this->serialize($row);

                    return Arr::get($data, $column);
                }
            );

            if ($orderable['direction'] == 'desc') {
                $this->collection = $this->collection->reverse();
            }
        }
    }

    /**
     * Perform global search.
     *
     * @return void
     */
    public function filtering()
    {
        $columns          = $this->request['columns'];
        $this->collection = $this->collection->filter(
            function ($row) use ($columns) {
                $data                  = $this->serialize($row);
                $this->isFilterApplied = true;
                $found                 = [];

                $keyword = $this->request->keyword();
                foreach ($this->request->searchableColumnIndex() as $index) {
                    $column = $this->getColumnName($index);
                    if (! $value = Arr::get($data, $column)) {
                        continue;
                    }

                    if ($this->isCaseInsensitive()) {
                        $found[] = Str::contains(Str::lower($value), Str::lower($keyword));
                    } else {
                        $found[] = Str::contains($value, $keyword);
                    }
                }

                return in_array(true, $found);
            }
        );
    }

    /**
     * Perform column search.
     *
     * @return void
     */
    public function columnSearch()
    {
        $columns = $this->request->get('columns');
        for ($i = 0, $c = count($columns); $i < $c; $i++) {
            if ($this->request->isColumnSearchable($i)) {
                $this->isFilterApplied = true;

                $column  = $this->getColumnName($i);
                $keyword = $this->request->columnKeyword($i);

                $this->collection = $this->collection->filter(
                    function ($row) use ($column, $keyword) {
                        $data = $this->serialize($row);

                        $value = Arr::get($data, $column);

                        if ($this->isCaseInsensitive()) {
                            return strpos(Str::lower($value), Str::lower($keyword)) !== false;
                        } else {
                            return strpos($value, $keyword) !== false;
                        }
                    }
                );
            }
        }
    }

    /**
     * Perform pagination.
     *
     * @return void
     */
    public function paging()
    {
        $this->collection = $this->collection->slice(
            $this->request['start'],
            (int) $this->request['length'] > 0 ? $this->request['length'] : 10
        );
    }

    /**
     * Get results.
     *
     * @return mixed
     */
    public function results()
    {
        return $this->collection->all();
    }

    /**
     * Organizes works.
     *
     * @param bool $mDataSupport
     * @param bool $orderFirst
     * @return \Illuminate\Http\JsonResponse
     */
    public function make($mDataSupport = false, $orderFirst = true)
    {
        return parent::make($mDataSupport, $orderFirst);
    }
}
