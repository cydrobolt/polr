<?php

namespace Yajra\Datatables\Services;

use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Facades\Config;
use Maatwebsite\Excel\Classes\LaravelExcelWorksheet;
use Maatwebsite\Excel\Writers\LaravelExcelWriter;
use Yajra\Datatables\Contracts\DataTableButtonsContract;
use Yajra\Datatables\Contracts\DataTableContract;
use Yajra\Datatables\Contracts\DataTableScopeContract;
use Yajra\Datatables\Datatables;
use Yajra\Datatables\Transformers\DataTransformer;

/**
 * Class DataTable.
 *
 * @package Yajra\Datatables\Services
 * @author  Arjay Angeles <aqangeles@gmail.com>
 */
abstract class DataTable implements DataTableContract, DataTableButtonsContract
{
    /**
     * @var \Yajra\Datatables\Datatables
     */
    protected $datatables;

    /**
     * @var \Illuminate\Contracts\View\Factory
     */
    protected $viewFactory;

    /**
     * Datatables print preview view.
     *
     * @var string
     */
    protected $printPreview = 'datatables::print';

    /**
     * List of columns to be exported.
     *
     * @var string|array
     */
    protected $exportColumns = '*';

    /**
     * List of columns to be printed.
     *
     * @var string|array
     */
    protected $printColumns = '*';

    /**
     * Query scopes.
     *
     * @var \Yajra\Datatables\Contracts\DataTableScopeContract[]
     */
    protected $scopes = [];

    /**
     * Export filename.
     *
     * @var string
     */
    protected $filename = '';

    /**
     * DataTable constructor.
     *
     * @param \Yajra\Datatables\Datatables $datatables
     * @param \Illuminate\Contracts\View\Factory $viewFactory
     */
    public function __construct(Datatables $datatables, Factory $viewFactory)
    {
        $this->datatables  = $datatables;
        $this->viewFactory = $viewFactory;
    }

    /**
     * Process dataTables needed render output.
     *
     * @param string $view
     * @param array $data
     * @param array $mergeData
     * @return \Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function render($view, $data = [], $mergeData = [])
    {
        if ($this->request()->ajax() && $this->request()->wantsJson()) {
            return $this->ajax();
        }

        if ($action = $this->request()->get('action') AND in_array($action, ['print', 'csv', 'excel', 'pdf'])) {
            if ($action == 'print') {
                return $this->printPreview();
            }

            return call_user_func_array([$this, $action], []);
        }

        return $this->viewFactory->make($view, $data, $mergeData)->with('dataTable', $this->html());
    }

    /**
     * Get Datatables Request instance.
     *
     * @return \Yajra\Datatables\Request
     */
    public function request()
    {
        return $this->datatables->getRequest();
    }

    /**
     * Display printable view of datatables.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function printPreview()
    {
        $data = $this->getDataForPrint();

        return $this->viewFactory->make($this->printPreview, compact('data'));
    }

    /**
     * Get mapped columns versus final decorated output.
     *
     * @return array
     */
    protected function getDataForPrint()
    {
        $columns = $this->printColumns();

        return $this->mapResponseToColumns($columns, 'printable');
    }

    /**
     * Get printable columns.
     *
     * @return array|string
     */
    protected function printColumns()
    {
        return is_array($this->printColumns) ? $this->printColumns : $this->getColumnsFromBuilder();
    }

    /**
     * Get columns definition from html builder.
     *
     * @return array
     */
    protected function getColumnsFromBuilder()
    {
        return $this->html()->getColumns();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\Datatables\Html\Builder
     */
    public function html()
    {
        return $this->builder();
    }

    /**
     * Get Datatables Html Builder instance.
     *
     * @return \Yajra\Datatables\Html\Builder
     */
    public function builder()
    {
        return $this->datatables->getHtmlBuilder();
    }

    /**
     * Map ajax response to columns definition.
     *
     * @param mixed $columns
     * @param string $type
     * @return array
     */
    protected function mapResponseToColumns($columns, $type)
    {
        return array_map(function ($row) use ($columns, $type) {
            if ($columns) {
                return (new DataTransformer())->transform($row, $columns, $type);
            }

            return $row;
        }, $this->getAjaxResponseData());
    }

    /**
     * Get decorated data as defined in datatables ajax response.
     *
     * @return array
     */
    protected function getAjaxResponseData()
    {
        $this->datatables->getRequest()->merge(['length' => -1]);

        $response = $this->ajax();
        $data     = $response->getData(true);

        return $data['data'];
    }

    /**
     * Export results to Excel file.
     *
     * @return void
     */
    public function excel()
    {
        $this->buildExcelFile()->download('xls');
    }

    /**
     * Build excel file and prepare for export.
     *
     * @return \Maatwebsite\Excel\Writers\LaravelExcelWriter
     */
    protected function buildExcelFile()
    {
        /** @var \Maatwebsite\Excel\Excel $excel */
        $excel = app('excel');

        return $excel->create($this->getFilename(), function (LaravelExcelWriter $excel) {
            $excel->sheet('exported-data', function (LaravelExcelWorksheet $sheet) {
                $sheet->fromArray($this->getDataForExport());
            });
        });
    }

    /**
     * Get export filename.
     *
     * @return string
     */
    public function getFilename()
    {
        return $this->filename ?: $this->filename();
    }

    /**
     * Set export filename.
     *
     * @param string $filename
     * @return DataTable
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'export_' . time();
    }

    /**
     * Get mapped columns versus final decorated output.
     *
     * @return array
     */
    protected function getDataForExport()
    {
        $columns = $this->exportColumns();

        return $this->mapResponseToColumns($columns, 'exportable');
    }

    /**
     * Get export columns definition.
     *
     * @return array|string
     */
    private function exportColumns()
    {
        return is_array($this->exportColumns) ? $this->exportColumns : $this->getColumnsFromBuilder();
    }

    /**
     * Export results to CSV file.
     *
     * @return void
     */
    public function csv()
    {
        $this->buildExcelFile()->download('csv');
    }

    /**
     * Export results to PDF file.
     *
     * @return mixed
     */
    public function pdf()
    {
        if ('snappy' == Config::get('datatables.pdf_generator', 'excel')) {
            return $this->snappyPdf();
        } else {
            $this->buildExcelFile()->download('pdf');
        }
    }

    /**
     * PDF version of the table using print preview blade template.
     *
     * @return mixed
     */
    public function snappyPdf()
    {
        $data   = $this->getDataForPrint();
        $snappy = app('snappy.pdf.wrapper');
        $snappy->setOptions([
            'no-outline'    => true,
            'margin-left'   => '0',
            'margin-right'  => '0',
            'margin-top'    => '10mm',
            'margin-bottom' => '10mm',
        ])->setOrientation('landscape');

        return $snappy->loadView($this->printPreview, compact('data'))
                      ->download($this->getFilename() . ".pdf");
    }

    /**
     * Add basic array query scopes.
     *
     * @param \Yajra\Datatables\Contracts\DataTableScopeContract $scope
     * @return $this
     */
    public function addScope(DataTableScopeContract $scope)
    {
        $this->scopes[] = $scope;

        return $this;
    }

    /**
     * Apply query scopes.
     *
     * @param \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder $query
     * @return mixed
     */
    protected function applyScopes($query)
    {
        foreach ($this->scopes as $scope) {
            $scope->apply($query);
        }

        return $query;
    }

    /**
     * Get default builder parameters.
     *
     * @return array
     */
    protected function getBuilderParameters()
    {
        return [
            'order'   => [[0, 'desc']],
            'buttons' => [
                'create',
                'export',
                'print',
                'reset',
                'reload',
            ],
        ];
    }
}
