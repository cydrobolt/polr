<?php

namespace Yajra\Datatables;

use Exception;
use Illuminate\Http\Request as IlluminateRequest;

/**
 * Class Request.
 *
 * @property array columns
 * @package Yajra\Datatables
 * @author  Arjay Angeles <aqangeles@gmail.com>
 */
class Request extends IlluminateRequest
{
    /**
     * Check if request uses legacy code
     *
     * @throws Exception
     */
    public function checkLegacyCode()
    {
        if (! $this->get('draw') && $this->get('sEcho')) {
            throw new Exception('DataTables legacy code is not supported! Please use DataTables 1.10++ coding convention.');
        } elseif (! $this->get('draw') && ! $this->get('columns')) {
            throw new Exception('Insufficient parameters');
        }
    }

    /**
     * Check if Datatables is searchable.
     *
     * @return bool
     */
    public function isSearchable()
    {
        return $this->get('search')['value'] != '';
    }

    /**
     * Get column's search value.
     *
     * @param integer $index
     * @return string
     */
    public function columnKeyword($index)
    {
        return $this->columns[$index]['search']['value'];
    }

    /**
     * Check if Datatables must uses regular expressions
     *
     * @param integer $index
     * @return string
     */
    public function isRegex($index)
    {
        return $this->columns[$index]['search']['regex'] === 'true';
    }

    /**
     * Get orderable columns
     *
     * @return array
     */
    public function orderableColumns()
    {
        if (! $this->isOrderable()) {
            return [];
        }

        $orderable = [];
        for ($i = 0, $c = count($this->get('order')); $i < $c; $i++) {
            $order_col = (int) $this->get('order')[$i]['column'];
            $order_dir = $this->get('order')[$i]['dir'];
            if ($this->isColumnOrderable($order_col)) {
                $orderable[] = ['column' => $order_col, 'direction' => $order_dir];
            }
        }

        return $orderable;
    }

    /**
     * Check if Datatables ordering is enabled.
     *
     * @return bool
     */
    public function isOrderable()
    {
        return $this->get('order') && count($this->get('order')) > 0;
    }

    /**
     * Check if a column is orderable.
     *
     * @param  integer $index
     * @return bool
     */
    public function isColumnOrderable($index)
    {
        return $this->get('columns')[$index]['orderable'] == 'true';
    }

    /**
     * Get searchable column indexes
     *
     * @return array
     */
    public function searchableColumnIndex()
    {
        $searchable = [];
        for ($i = 0, $c = count($this->get('columns')); $i < $c; $i++) {
            if ($this->isColumnSearchable($i, false)) {
                $searchable[] = $i;
            }
        }

        return $searchable;
    }

    /**
     * Check if a column is searchable.
     *
     * @param integer $i
     * @param bool $column_search
     * @return bool
     */
    public function isColumnSearchable($i, $column_search = true)
    {
        $columns = $this->get('columns');
        if ($column_search) {
            return $columns[$i]['searchable'] == 'true' && $columns[$i]['search']['value'] != '';
        }

        return $columns[$i]['searchable'] == 'true';
    }

    /**
     * Get global search keyword
     *
     * @return string
     */
    public function keyword()
    {
        return $this->get('search')['value'];
    }

    /**
     * Get column identity from input or database.
     *
     * @param integer $i
     * @return string
     */
    public function columnName($i)
    {
        $column = $this->get('columns')[$i];

        return isset($column['name']) && $column['name'] <> '' ? $column['name'] : $column['data'];
    }

    /**
     * Check if Datatables allow pagination.
     *
     * @return bool
     */
    public function isPaginationable()
    {
        return ! is_null($this->get('start')) && ! is_null($this->get('length')) && $this->get('length') != -1;
    }
}
