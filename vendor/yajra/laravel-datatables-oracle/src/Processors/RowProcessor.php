<?php

namespace Yajra\Datatables\Processors;

use Illuminate\Support\Arr;
use Yajra\Datatables\Helper;

/**
 * Class RowProcessor.
 *
 * @package Yajra\Datatables
 * @author  Arjay Angeles <aqangeles@gmail.com>
 */
class RowProcessor
{
    /**
     * @var mixed
     */
    private $data;

    /**
     * @var mixed
     */
    private $row;

    /**
     * @param mixed $data
     * @param mixed $row
     */
    public function __construct($data, $row)
    {
        $this->data = $data;
        $this->row  = $row;
    }

    /**
     * Process DT RowId and Class value.
     *
     * @param string $attribute
     * @param string|callable $template
     * @return $this
     */
    public function rowValue($attribute, $template)
    {
        if (! empty($template)) {
            if (! is_callable($template) && Arr::get($this->data, $template)) {
                $this->data[$attribute] = Arr::get($this->data, $template);
            } else {
                $this->data[$attribute] = Helper::compileContent($template, $this->data, $this->row);
            }
        }

        return $this;
    }

    /**
     * Process DT Row Data and Attr.
     *
     * @param string $attribute
     * @param array $template
     * @return $this
     */
    public function rowData($attribute, array $template)
    {
        if (count($template)) {
            $this->data[$attribute] = [];
            foreach ($template as $key => $value) {
                $this->data[$attribute][$key] = Helper::compileContent($value, $this->data, $this->row);
            }
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }
}
