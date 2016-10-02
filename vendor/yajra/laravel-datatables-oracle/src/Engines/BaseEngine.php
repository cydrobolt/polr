<?php

namespace Yajra\Datatables\Engines;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use League\Fractal\Resource\Collection;
use Yajra\Datatables\Contracts\DataTableEngineContract;
use Yajra\Datatables\Helper;
use Yajra\Datatables\Processors\DataProcessor;

/**
 * Class BaseEngine.
 *
 * @package Yajra\Datatables\Engines
 * @author  Arjay Angeles <aqangeles@gmail.com>
 */
abstract class BaseEngine implements DataTableEngineContract
{
    /**
     * Datatables Request object.
     *
     * @var \Yajra\Datatables\Request
     */
    public $request;

    /**
     * Database connection used.
     *
     * @var \Illuminate\Database\Connection
     */
    protected $connection;

    /**
     * Builder object.
     *
     * @var \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    protected $query;

    /**
     * Query builder object.
     *
     * @var \Illuminate\Database\Query\Builder
     */
    protected $builder;

    /**
     * Array of result columns/fields.
     *
     * @var array
     */
    protected $columns = [];

    /**
     * DT columns definitions container (add/edit/remove/filter/order/escape).
     *
     * @var array
     */
    protected $columnDef = [
        'index'     => false,
        'append'    => [],
        'edit'      => [],
        'excess'    => ['rn', 'row_num'],
        'filter'    => [],
        'order'     => [],
        'escape'    => [],
        'blacklist' => ['password', 'remember_token'],
        'whitelist' => '*',
    ];

    /**
     * Query type.
     *
     * @var string
     */
    protected $query_type;

    /**
     * Extra/Added columns.
     *
     * @var array
     */
    protected $extraColumns = [];

    /**
     * Total records.
     *
     * @var int
     */
    protected $totalRecords = 0;

    /**
     * Total filtered records.
     *
     * @var int
     */
    protected $filteredRecords = 0;

    /**
     * Auto-filter flag.
     *
     * @var bool
     */
    protected $autoFilter = true;

    /**
     * Callback to override global search.
     *
     * @var \Closure
     */
    protected $filterCallback;

    /**
     * Parameters to passed on filterCallback.
     *
     * @var mixed
     */
    protected $filterCallbackParameters;

    /**
     * DT row templates container.
     *
     * @var array
     */
    protected $templates = [
        'DT_RowId'    => '',
        'DT_RowClass' => '',
        'DT_RowData'  => [],
        'DT_RowAttr'  => [],
    ];

    /**
     * Output transformer.
     *
     * @var \League\Fractal\TransformerAbstract
     */
    protected $transformer = null;

    /**
     * Database prefix
     *
     * @var string
     */
    protected $prefix;

    /**
     * Database driver used.
     *
     * @var string
     */
    protected $database;

    /**
     * [internal] Track if any filter was applied for at least one column
     *
     * @var boolean
     */
    protected $isFilterApplied = false;

    /**
     * Fractal serializer class.
     *
     * @var string|null
     */
    protected $serializer = null;

    /**
     * Custom ordering callback.
     *
     * @var \Closure
     */
    protected $orderCallback;

    /**
     * Array of data to append on json response.
     *
     * @var array
     */
    private $appends = [];

    /**
     * Setup search keyword.
     *
     * @param  string $value
     * @return string
     */
    public function setupKeyword($value)
    {
        if ($this->isSmartSearch()) {
            $keyword = '%' . $value . '%';
            if ($this->isWildcard()) {
                $keyword = $this->wildcardLikeString($value);
            }
            // remove escaping slash added on js script request
            $keyword = str_replace('\\', '%', $keyword);

            return $keyword;
        }

        return $value;
    }

    /**
     * Check if DataTables uses smart search.
     *
     * @return bool
     */
    protected function isSmartSearch()
    {
        return Config::get('datatables.search.smart', true);
    }

    /**
     * Get config use wild card status.
     *
     * @return bool
     */
    public function isWildcard()
    {
        return Config::get('datatables.search.use_wildcards', false);
    }

    /**
     * Adds % wildcards to the given string.
     *
     * @param string $str
     * @param bool $lowercase
     * @return string
     */
    public function wildcardLikeString($str, $lowercase = true)
    {
        $wild   = '%';
        $length = Str::length($str);
        if ($length) {
            for ($i = 0; $i < $length; $i++) {
                $wild .= $str[$i] . '%';
            }
        }
        if ($lowercase) {
            $wild = Str::lower($wild);
        }

        return $wild;
    }

    /**
     * Will prefix column if needed.
     *
     * @param string $column
     * @return string
     */
    public function prefixColumn($column)
    {
        $table_names = $this->tableNames();
        if (count(
            array_filter($table_names, function ($value) use (&$column) {
                return strpos($column, $value . '.') === 0;
            })
        )) {
            // the column starts with one of the table names
            $column = $this->prefix . $column;
        }

        return $column;
    }

    /**
     * Will look through the query and all it's joins to determine the table names.
     *
     * @return array
     */
    public function tableNames()
    {
        $names          = [];
        $query          = $this->getQueryBuilder();
        $names[]        = $query->from;
        $joins          = $query->joins ?: [];
        $databasePrefix = $this->prefix;
        foreach ($joins as $join) {
            $table   = preg_split('/ as /i', $join->table);
            $names[] = $table[0];
            if (isset($table[1]) && ! empty($databasePrefix) && strpos($table[1], $databasePrefix) == 0) {
                $names[] = preg_replace('/^' . $databasePrefix . '/', '', $table[1]);
            }
        }

        return $names;
    }

    /**
     * Get Query Builder object.
     *
     * @param mixed $instance
     * @return mixed
     */
    public function getQueryBuilder($instance = null)
    {
        if (! $instance) {
            $instance = $this->query;
        }

        if ($this->isQueryBuilder()) {
            return $instance;
        }

        return $instance->getQuery();
    }

    /**
     * Check query type is a builder.
     *
     * @return bool
     */
    public function isQueryBuilder()
    {
        return $this->query_type == 'builder';
    }

    /**
     * Add column in collection.
     *
     * @param string $name
     * @param string|callable $content
     * @param bool|int $order
     * @return $this
     */
    public function addColumn($name, $content, $order = false)
    {
        $this->extraColumns[] = $name;

        $this->columnDef['append'][] = ['name' => $name, 'content' => $content, 'order' => $order];

        return $this;
    }

    /**
     * Add DT row index column on response.
     *
     * @return $this
     */
    public function addIndexColumn()
    {
        $this->columnDef['index'] = true;

        return $this;
    }

    /**
     * Edit column's content.
     *
     * @param string $name
     * @param string|callable $content
     * @return $this
     */
    public function editColumn($name, $content)
    {
        $this->columnDef['edit'][] = ['name' => $name, 'content' => $content];

        return $this;
    }

    /**
     * Remove column from collection.
     *
     * @return $this
     */
    public function removeColumn()
    {
        $names                     = func_get_args();
        $this->columnDef['excess'] = array_merge($this->columnDef['excess'], $names);

        return $this;
    }

    /**
     * Declare columns to escape values.
     *
     * @param string|array $columns
     * @return $this
     */
    public function escapeColumns($columns = '*')
    {
        $this->columnDef['escape'] = $columns;

        return $this;
    }

    /**
     * Allows previous API calls where the methods were snake_case.
     * Will convert a camelCase API call to a snake_case call.
     * Allow query builder method to be used by the engine.
     *
     * @param  string $name
     * @param  array $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        $name = Str::camel(Str::lower($name));
        if (method_exists($this, $name)) {
            return call_user_func_array([$this, $name], $arguments);
        } elseif (method_exists($this->getQueryBuilder(), $name)) {
            call_user_func_array([$this->getQueryBuilder(), $name], $arguments);
        } else {
            trigger_error('Call to undefined method ' . __CLASS__ . '::' . $name . '()', E_USER_ERROR);
        }

        return $this;
    }

    /**
     * Sets DT_RowClass template.
     * result: <tr class="output_from_your_template">.
     *
     * @param string|callable $content
     * @return $this
     */
    public function setRowClass($content)
    {
        $this->templates['DT_RowClass'] = $content;

        return $this;
    }

    /**
     * Sets DT_RowId template.
     * result: <tr id="output_from_your_template">.
     *
     * @param string|callable $content
     * @return $this
     */
    public function setRowId($content)
    {
        $this->templates['DT_RowId'] = $content;

        return $this;
    }

    /**
     * Set DT_RowData templates.
     *
     * @param array $data
     * @return $this
     */
    public function setRowData(array $data)
    {
        $this->templates['DT_RowData'] = $data;

        return $this;
    }

    /**
     * Add DT_RowData template.
     *
     * @param string $key
     * @param string|callable $value
     * @return $this
     */
    public function addRowData($key, $value)
    {
        $this->templates['DT_RowData'][$key] = $value;

        return $this;
    }

    /**
     * Set DT_RowAttr templates.
     * result: <tr attr1="attr1" attr2="attr2">.
     *
     * @param array $data
     * @return $this
     */
    public function setRowAttr(array $data)
    {
        $this->templates['DT_RowAttr'] = $data;

        return $this;
    }

    /**
     * Add DT_RowAttr template.
     *
     * @param string $key
     * @param string|callable $value
     * @return $this
     */
    public function addRowAttr($key, $value)
    {
        $this->templates['DT_RowAttr'][$key] = $value;

        return $this;
    }

    /**
     * Override default column filter search.
     *
     * @param string $column
     * @param string|Closure $method
     * @return $this
     * @internal param $mixed ...,... All the individual parameters required for specified $method
     * @internal string $1 Special variable that returns the requested search keyword.
     */
    public function filterColumn($column, $method)
    {
        $params                             = func_get_args();
        $this->columnDef['filter'][$column] = ['method' => $method, 'parameters' => array_splice($params, 2)];

        return $this;
    }

    /**
     * Order each given columns versus the given custom sql.
     *
     * @param array $columns
     * @param string $sql
     * @param array $bindings
     * @return $this
     */
    public function orderColumns(array $columns, $sql, $bindings = [])
    {
        foreach ($columns as $column) {
            $this->orderColumn($column, str_replace(':column', $column, $sql), $bindings);
        }

        return $this;
    }

    /**
     * Override default column ordering.
     *
     * @param string $column
     * @param string $sql
     * @param array $bindings
     * @return $this
     * @internal string $1 Special variable that returns the requested order direction of the column.
     */
    public function orderColumn($column, $sql, $bindings = [])
    {
        $this->columnDef['order'][$column] = ['method' => 'orderByRaw', 'parameters' => [$sql, $bindings]];

        return $this;
    }

    /**
     * Set data output transformer.
     *
     * @param \League\Fractal\TransformerAbstract $transformer
     * @return $this
     */
    public function setTransformer($transformer)
    {
        $this->transformer = $transformer;

        return $this;
    }

    /**
     * Set fractal serializer class.
     *
     * @param string $serializer
     * @return $this
     */
    public function setSerializer($serializer)
    {
        $this->serializer = $serializer;

        return $this;
    }

    /**
     * Organizes works.
     *
     * @param bool $mDataSupport
     * @param bool $orderFirst
     * @return \Illuminate\Http\JsonResponse
     */
    public function make($mDataSupport = false, $orderFirst = false)
    {
        $this->totalRecords = $this->totalCount();

        if ($this->totalRecords) {
            $this->orderRecords(! $orderFirst);
            $this->filterRecords();
            $this->orderRecords($orderFirst);
            $this->paginate();
        }

        return $this->render($mDataSupport);
    }

    /**
     * Sort records.
     *
     * @param  boolean $skip
     * @return void
     */
    public function orderRecords($skip)
    {
        if (! $skip) {
            $this->ordering();
        }
    }

    /**
     * Perform necessary filters.
     *
     * @return void
     */
    public function filterRecords()
    {
        if ($this->autoFilter && $this->request->isSearchable()) {
            $this->filtering();
        }

        if (is_callable($this->filterCallback)) {
            call_user_func($this->filterCallback, $this->filterCallbackParameters);
        }

        $this->columnSearch();
        $this->filteredRecords = $this->isFilterApplied ? $this->count() : $this->totalRecords;
    }

    /**
     * Apply pagination.
     *
     * @return void
     */
    public function paginate()
    {
        if ($this->request->isPaginationable()) {
            $this->paging();
        }
    }

    /**
     * Render json response.
     *
     * @param bool $object
     * @return \Illuminate\Http\JsonResponse
     */
    public function render($object = false)
    {
        $output = array_merge([
            'draw'            => (int) $this->request['draw'],
            'recordsTotal'    => $this->totalRecords,
            'recordsFiltered' => $this->filteredRecords,
        ], $this->appends);

        if (isset($this->transformer)) {
            $fractal = app('datatables.fractal');

            if ($this->serializer) {
                $fractal->setSerializer(new $this->serializer);
            }

            //Get transformer reflection
            //Firs method parameter should be data/object to transform
            $reflection = new \ReflectionMethod($this->transformer, 'transform');
            $parameter  = $reflection->getParameters()[0];

            //If parameter is class assuming it requires object
            //Else just pass array by default
            if ($parameter->getClass()) {
                $resource = new Collection($this->results(), $this->createTransformer());
            } else {
                $resource = new Collection(
                    $this->getProcessedData($object),
                    $this->createTransformer()
                );
            }

            $collection     = $fractal->createData($resource)->toArray();
            $output['data'] = $collection['data'];
        } else {
            $output['data'] = Helper::transform($this->getProcessedData($object));
        }

        if ($this->isDebugging()) {
            $output = $this->showDebugger($output);
        }

        return new JsonResponse($output);
    }

    /**
     * Get or create transformer instance.
     *
     * @return \League\Fractal\TransformerAbstract
     */
    protected function createTransformer()
    {
        if ($this->transformer instanceof \League\Fractal\TransformerAbstract) {
            return $this->transformer;
        }

        return new $this->transformer();
    }

    /**
     * Get processed data
     *
     * @param bool|false $object
     * @return array
     */
    private function getProcessedData($object = false)
    {
        $processor = new DataProcessor(
            $this->results(),
            $this->columnDef,
            $this->templates,
            $this->request['start']
        );

        return $processor->process($object);
    }

    /**
     * Check if app is in debug mode.
     *
     * @return bool
     */
    public function isDebugging()
    {
        return Config::get('app.debug', false);
    }

    /**
     * Append debug parameters on output.
     *
     * @param  array $output
     * @return array
     */
    public function showDebugger(array $output)
    {
        $output['queries'] = $this->connection->getQueryLog();
        $output['input']   = $this->request->all();

        return $output;
    }

    /**
     * Update flags to disable global search
     *
     * @param  \Closure $callback
     * @param  mixed $parameters
     * @param  bool $autoFilter
     */
    public function overrideGlobalSearch(\Closure $callback, $parameters, $autoFilter = false)
    {
        $this->autoFilter               = $autoFilter;
        $this->isFilterApplied          = true;
        $this->filterCallback           = $callback;
        $this->filterCallbackParameters = $parameters;
    }

    /**
     * Get config is case insensitive status.
     *
     * @return bool
     */
    public function isCaseInsensitive()
    {
        return Config::get('datatables.search.case_insensitive', false);
    }

    /**
     * Append data on json response.
     *
     * @param mixed $key
     * @param mixed $value
     * @return $this
     */
    public function with($key, $value = '')
    {
        if (is_array($key)) {
            $this->appends = $key;
        } elseif (is_callable($value)) {
            $this->appends[$key] = value($value);
        } else {
            $this->appends[$key] = value($value);
        }

        return $this;
    }

    /**
     * Override default ordering method with a closure callback.
     *
     * @param \Closure $closure
     * @return $this
     */
    public function order(\Closure $closure)
    {
        $this->orderCallback = $closure;

        return $this;
    }

    /**
     * Update list of columns that is not allowed for search/sort.
     *
     * @param  array $blacklist
     * @return $this
     */
    public function blacklist(array $blacklist)
    {
        $this->columnDef['blacklist'] = $blacklist;

        return $this;
    }

    /**
     * Update list of columns that is not allowed for search/sort.
     *
     * @param  string|array $whitelist
     * @return $this
     */
    public function whitelist($whitelist = '*')
    {
        $this->columnDef['whitelist'] = $whitelist;

        return $this;
    }

    /**
     * Set smart search config at runtime.
     *
     * @param bool $bool
     * @return $this
     */
    public function smart($bool = true)
    {
        Config::set('datatables.search.smart', $bool);

        return $this;
    }

    /**
     * Check if column is blacklisted.
     *
     * @param string $column
     * @return bool
     */
    protected function isBlacklisted($column)
    {
        if (in_array($column, $this->columnDef['blacklist'])) {
            return true;
        }

        if ($this->columnDef['whitelist'] === '*' || in_array($column, $this->columnDef['whitelist'])) {
            return false;
        }

        return true;
    }

    /**
     * Get column name to be use for filtering and sorting.
     *
     * @param integer $index
     * @param bool $wantsAlias
     * @return string
     */
    protected function getColumnName($index, $wantsAlias = false)
    {
        $column = $this->request->columnName($index);

        // DataTables is using make(false)
        if (is_numeric($column)) {
            $column = $this->getColumnNameByIndex($index);
        }

        if (Str::contains(Str::upper($column), ' AS ')) {
            $column = $this->extractColumnName($column, $wantsAlias);
        }

        return $column;
    }

    /**
     * Get column name by order column index.
     *
     * @param int $index
     * @return mixed
     */
    protected function getColumnNameByIndex($index)
    {
        $name = isset($this->columns[$index]) && $this->columns[$index] <> '*' ? $this->columns[$index] : $this->getPrimaryKeyName();

        return in_array($name, $this->extraColumns, true) ? $this->getPrimaryKeyName() : $name;
    }

    /**
     * If column name could not be resolved then use primary key.
     *
     * @return string
     */
    protected function getPrimaryKeyName()
    {
        if ($this->isEloquent()) {
            return $this->query->getModel()->getKeyName();
        }

        return 'id';
    }

    /**
     * Check if the engine used was eloquent.
     *
     * @return bool
     */
    protected function isEloquent()
    {
        return $this->query_type === 'eloquent';
    }

    /**
     * Get column name from string.
     *
     * @param string $str
     * @param bool $wantsAlias
     * @return string
     */
    protected function extractColumnName($str, $wantsAlias)
    {
        $matches = explode(' as ', Str::lower($str));

        if (! empty($matches)) {
            if ($wantsAlias) {
                return array_pop($matches);
            } else {
                return array_shift($matches);
            }
        } elseif (strpos($str, '.')) {
            $array = explode('.', $str);

            return array_pop($array);
        }

        return $str;
    }

    /**
     * Check if the current sql language is based on oracle syntax.
     *
     * @return bool
     */
    protected function isOracleSql()
    {
        return in_array($this->database, ['oracle', 'oci8']);
    }

    /**
     * Set total records manually.
     *
     * @param int $total
     * @return $this
     */
    public function setTotalRecords($total)
    {
        $this->totalRecords = $total;

        return $this;
    }
}
