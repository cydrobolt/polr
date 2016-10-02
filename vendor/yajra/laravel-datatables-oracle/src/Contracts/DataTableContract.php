<?php

namespace Yajra\Datatables\Contracts;

/**
 * Interface DataTableContract
 *
 * @package Yajra\Datatables\Contracts
 * @author  Arjay Angeles <aqangeles@gmail.com>
 */
interface DataTableContract
{
    /**
     * Render view.
     *
     * @param $view
     * @param array $data
     * @param array $mergeData
     * @return \Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function render($view, $data = [], $mergeData = []);

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax();

    /**
     * @return \Yajra\Datatables\Html\Builder
     */
    public function html();

    /**
     * @return \Yajra\Datatables\Html\Builder
     */
    public function builder();

    /**
     * @return \Yajra\Datatables\Request
     */
    public function request();

    /**
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder|\Illuminate\Support\Collection
     */
    public function query();
}
