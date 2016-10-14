<?php

namespace Yajra\Datatables\Contracts;

/**
 * Interface DataTableScopeContract.
 *
 * @package Yajra\Datatables\Contracts
 * @author  Arjay Angeles <aqangeles@gmail.com>
 */
interface DataTableScopeContract
{
    /**
     * Apply a query scope.
     *
     * @param \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder $query
     * @return mixed
     */
    public function apply($query);
}
