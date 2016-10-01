<?php

namespace Yajra\Datatables\Processors;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;
use Yajra\Datatables\Helper;

/**
 * Class DataProcessor.
 *
 * @package Yajra\Datatables
 * @author  Arjay Angeles <aqangeles@gmail.com>
 */
class DataProcessor
{
    /**
     * @var int
     */
    protected $start;

    /**
     * Columns to escape value.
     *
     * @var array
     */
    protected $escapeColumns = [];

    /**
     * Processed data output
     *
     * @var array
     */
    protected $output = [];

    /**
     * @var array
     */
    protected $appendColumns = [];

    /**
     * @var array
     */
    protected $editColumns = [];

    /**
     * @var array
     */
    protected $excessColumns = [];

    /**
     * @var mixed
     */
    protected $results;

    /**
     * @var array
     */
    protected $templates;

    /**
     * @var bool
     */
    protected $includeIndex;

    /**
     * @param mixed $results
     * @param array $columnDef
     * @param array $templates
     * @param int $start
     */
    public function __construct($results, array $columnDef, array $templates, $start)
    {
        $this->results       = $results;
        $this->appendColumns = $columnDef['append'];
        $this->editColumns   = $columnDef['edit'];
        $this->excessColumns = $columnDef['excess'];
        $this->escapeColumns = $columnDef['escape'];
        $this->includeIndex  = $columnDef['index'];
        $this->templates     = $templates;
        $this->start         = $start;
    }

    /**
     * Process data to output on browser
     *
     * @param bool $object
     * @return array
     */
    public function process($object = false)
    {
        $this->output = [];
        $indexColumn  = Config::get('datatables.index_column', 'DT_Row_Index');

        foreach ($this->results as $row) {
            $data  = Helper::convertToArray($row);
            $value = $this->addColumns($data, $row);
            $value = $this->editColumns($value, $row);
            $value = $this->setupRowVariables($value, $row);
            $value = $this->removeExcessColumns($value);

            if ($this->includeIndex) {
                $value[$indexColumn] = ++$this->start;
            }

            $this->output[] = $object ? $value : $this->flatten($value);
        }

        return $this->escapeColumns($this->output);
    }

    /**
     * Process add columns.
     *
     * @param mixed $data
     * @param mixed $row
     * @return array
     */
    protected function addColumns($data, $row)
    {
        foreach ($this->appendColumns as $key => $value) {
            $value['content'] = Helper::compileContent($value['content'], $data, $row);
            $data             = Helper::includeInArray($value, $data);
        }

        return $data;
    }

    /**
     * Process edit columns.
     *
     * @param mixed $data
     * @param mixed $row
     * @return array
     */
    protected function editColumns($data, $row)
    {
        foreach ($this->editColumns as $key => $value) {
            $value['content'] = Helper::compileContent($value['content'], $data, $row);
            Arr::set($data, $value['name'], $value['content']);
        }

        return $data;
    }

    /**
     * Setup additional DT row variables.
     *
     * @param mixed $data
     * @param mixed $row
     * @return array
     */
    protected function setupRowVariables($data, $row)
    {
        $processor = new RowProcessor($data, $row);

        return $processor
            ->rowValue('DT_RowId', $this->templates['DT_RowId'])
            ->rowValue('DT_RowClass', $this->templates['DT_RowClass'])
            ->rowData('DT_RowData', $this->templates['DT_RowData'])
            ->rowData('DT_RowAttr', $this->templates['DT_RowAttr'])
            ->getData();
    }

    /**
     * Remove declared hidden columns.
     *
     * @param array $data
     * @return array
     */
    protected function removeExcessColumns(array $data)
    {
        foreach ($this->excessColumns as $value) {
            unset($data[$value]);
        }

        return $data;
    }

    /**
     * Flatten array with exceptions.
     *
     * @param array $array
     * @return array
     */
    public function flatten(array $array)
    {
        $return     = [];
        $exceptions = ['DT_RowId', 'DT_RowClass', 'DT_RowData', 'DT_RowAttr'];

        foreach ($array as $key => $value) {
            if (in_array($key, $exceptions)) {
                $return[$key] = $value;
            } else {
                $return[] = $value;
            }
        }

        return $return;
    }

    /**
     * Escape column values as declared.
     *
     * @param array $output
     * @return array
     */
    protected function escapeColumns(array $output)
    {
        return array_map(function ($row) {
            if ($this->escapeColumns == '*') {
                $row = $this->escapeRow($row, $this->escapeColumns);
            } else {
                foreach ($this->escapeColumns as $key) {
                    if (array_get($row, $key)) {
                        array_set($row, $key, e(array_get($row, $key)));
                    }
                }
            }

            return $row;
        }, $output);
    }

    /**
     * Escape all values of row.
     *
     * @param array $row
     * @param string|array $escapeColumns
     * @return array
     */
    protected function escapeRow(array $row, $escapeColumns)
    {
        foreach ($row as $key => $value) {
            if (is_array($value)) {
                $row[$key] = $this->escapeRow($value, $escapeColumns);
            } else {
                $row[$key] = e($value);
            }
        }

        return $row;
    }
}
