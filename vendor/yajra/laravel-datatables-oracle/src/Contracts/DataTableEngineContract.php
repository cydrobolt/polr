<?php

namespace Yajra\Datatables\Contracts;

/**
 * Interface DataTableEngineContract
 *
 * @package Yajra\Datatables\Contracts
 * @author  Arjay Angeles <aqangeles@gmail.com>
 */
interface DataTableEngineContract
{
    /**
     * Get results.
     *
     * @return mixed
     */
    public function results();

    /**
     * Count results.
     *
     * @return integer
     */
    public function count();

    /**
     * Count total items.
     *
     * @return integer
     */
    public function totalCount();

    /**
     * Set auto filter off and run your own filter.
     * Overrides global search.
     *
     * @param \Closure $callback
     * @param bool $globalSearch
     * @return $this
     */
    public function filter(\Closure $callback, $globalSearch = false);

    /**
     * Perform global search.
     *
     * @return void
     */
    public function filtering();

    /**
     * Perform column search.
     *
     * @return void
     */
    public function columnSearch();

    /**
     * Perform pagination.
     *
     * @return void
     */
    public function paging();

    /**
     * Perform sorting of columns.
     *
     * @return void
     */
    public function ordering();

    /**
     * Organizes works.
     *
     * @param bool $mDataSupport
     * @param bool $orderFirst
     * @return \Illuminate\Http\JsonResponse
     */
    public function make($mDataSupport = false, $orderFirst = false);
}
