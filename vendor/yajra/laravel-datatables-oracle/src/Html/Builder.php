<?php

namespace Yajra\Datatables\Html;

use Collective\Html\FormBuilder;
use Collective\Html\HtmlBuilder;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\View\Factory;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

/**
 * Class Builder.
 *
 * @package Yajra\Datatables\Html
 * @author  Arjay Angeles <aqangeles@gmail.com>
 */
class Builder
{
    /**
     * @var Collection
     */
    public $collection;

    /**
     * @var Repository
     */
    public $config;

    /**
     * @var Factory
     */
    public $view;

    /**
     * @var HtmlBuilder
     */
    public $html;

    /**
     * @var UrlGenerator
     */
    public $url;

    /**
     * @var FormBuilder
     */
    public $form;

    /**
     * @var string|array
     */
    protected $ajax = '';

    /**
     * @var array
     */
    protected $tableAttributes = ['class' => 'table', 'id' => 'dataTableBuilder'];

    /**
     * @var string
     */
    protected $template = '';

    /**
     * @var array
     */
    protected $attributes = [];

    /**
     * Lists of valid DataTables Callbacks.
     *
     * @link https://datatables.net/reference/option/.
     * @var array
     */
    protected $validCallbacks = [
        'createdRow',
        'drawCallback',
        'footerCallback',
        'formatNumber',
        'headerCallback',
        'infoCallback',
        'initComplete',
        'preDrawCallback',
        'rowCallback',
        'stateLoadCallback',
        'stateLoaded',
        'stateLoadParams',
        'stateSaveCallback',
        'stateSaveParams',
    ];

    /**
     * @param Repository $config
     * @param Factory $view
     * @param HtmlBuilder $html
     * @param UrlGenerator $url
     * @param FormBuilder $form
     */
    public function __construct(
        Repository $config,
        Factory $view,
        HtmlBuilder $html,
        UrlGenerator $url,
        FormBuilder $form
    ) {
        $this->config     = $config;
        $this->view       = $view;
        $this->html       = $html;
        $this->url        = $url;
        $this->collection = new Collection;
        $this->form       = $form;
    }

    /**
     * Generate DataTable javascript.
     *
     * @param  null $script
     * @param  array $attributes
     * @return string
     */
    public function scripts($script = null, array $attributes = ['type' => 'text/javascript'])
    {
        $script = $script ?: $this->generateScripts();

        return '<script' . $this->html->attributes($attributes) . '>' . $script . '</script>' . PHP_EOL;
    }

    /**
     * Get generated raw scripts.
     *
     * @return string
     */
    public function generateScripts()
    {
        $args = array_merge(
            $this->attributes, [
                'ajax'    => $this->ajax,
                'columns' => $this->collection->toArray(),
            ]
        );

        $parameters = $this->parameterize($args);

        return sprintf(
            $this->template(),
            $this->tableAttributes['id'], $parameters
        );
    }

    /**
     * Generate DataTables js parameters.
     *
     * @param  array $attributes
     * @return string
     */
    public function parameterize($attributes = [])
    {
        $parameters = (new Parameters($attributes))->toArray();

        list($ajaxDataFunction, $parameters) = $this->encodeAjaxDataFunction($parameters);
        list($columnFunctions, $parameters) = $this->encodeColumnFunctions($parameters);
        list($callbackFunctions, $parameters) = $this->encodeCallbackFunctions($parameters);

        $json = json_encode($parameters);

        $json = $this->decodeAjaxDataFunction($ajaxDataFunction, $json);
        $json = $this->decodeColumnFunctions($columnFunctions, $json);
        $json = $this->decodeCallbackFunctions($callbackFunctions, $json);

        return $json;
    }

    /**
     * Encode ajax data function param.
     *
     * @param array $parameters
     * @return mixed
     */
    protected function encodeAjaxDataFunction($parameters)
    {
        $ajaxData = '';
        if (isset($parameters['ajax']['data'])) {
            $ajaxData                   = $parameters['ajax']['data'];
            $parameters['ajax']['data'] = "#ajax_data#";
        }

        return [$ajaxData, $parameters];
    }

    /**
     * Encode columns render function.
     *
     * @param array $parameters
     * @return array
     */
    protected function encodeColumnFunctions(array $parameters)
    {
        $columnFunctions = [];
        foreach ($parameters['columns'] as $i => $column) {
            unset($parameters['columns'][$i]['exportable']);
            unset($parameters['columns'][$i]['printable']);
            unset($parameters['columns'][$i]['footer']);

            if (isset($column['render'])) {
                $columnFunctions[$i]                 = $column['render'];
                $parameters['columns'][$i]['render'] = "#column_function.{$i}#";
            }
        }

        return [$columnFunctions, $parameters];
    }

    /**
     * Encode DataTables callbacks function.
     *
     * @param array $parameters
     * @return array
     */
    protected function encodeCallbackFunctions(array $parameters)
    {
        $callbackFunctions = [];
        foreach ($parameters as $key => $callback) {
            if (in_array($key, $this->validCallbacks)) {
                $callbackFunctions[$key] = $this->compileCallback($callback);
                $parameters[$key]        = "#callback_function.{$key}#";
            }
        }

        return [$callbackFunctions, $parameters];
    }

    /**
     * Compile DataTable callback value.
     *
     * @param mixed $callback
     * @return mixed|string
     */
    private function compileCallback($callback)
    {
        if (is_callable($callback)) {
            return value($callback);
        } elseif ($this->view->exists($callback)) {
            return $this->view->make($callback)->render();
        }

        return $callback;
    }

    /**
     * Decode ajax data method.
     *
     * @param string $function
     * @param string $json
     * @return string
     */
    protected function decodeAjaxDataFunction($function, $json)
    {
        return str_replace("\"#ajax_data#\"", $function, $json);
    }

    /**
     * Decode columns render functions.
     *
     * @param array $columnFunctions
     * @param string $json
     * @return string
     */
    protected function decodeColumnFunctions(array $columnFunctions, $json)
    {
        foreach ($columnFunctions as $i => $function) {
            $json = str_replace("\"#column_function.{$i}#\"", $function, $json);
        }

        return $json;
    }

    /**
     * Decode DataTables callbacks function.
     *
     * @param array $callbackFunctions
     * @param string $json
     * @return string
     */
    protected function decodeCallbackFunctions(array $callbackFunctions, $json)
    {
        foreach ($callbackFunctions as $i => $function) {
            $json = str_replace("\"#callback_function.{$i}#\"", $function, $json);
        }

        return $json;
    }

    /**
     * Get javascript template to use.
     *
     * @return string
     */
    protected function template()
    {
        return $this->view->make(
            $this->template ?: $this->config->get('datatables.script_template', 'datatables::script')
        )->render();
    }

    /**
     * Sets HTML table attribute(s).
     *
     * @param string|array $attribute
     * @param mixed $value
     * @return $this
     */
    public function setTableAttribute($attribute, $value = null)
    {
        if (is_array($attribute)) {
            $this->setTableAttributes($attribute);
        } else {
            $this->tableAttributes[$attribute] = $value;
        }

        return $this;
    }

    /**
     * Sets multiple HTML table attributes at once.
     *
     * @param array $attributes
     * @return $this
     */
    public function setTableAttributes(array $attributes)
    {
        foreach ($attributes as $attribute => $value) {
            $this->setTableAttribute($attribute, $value);
        }

        return $this;
    }

    /**
     * Retrieves HTML table attribute value.
     *
     * @param string $attribute
     * @return mixed
     * @throws \Exception
     */
    public function getTableAttribute($attribute)
    {
        if (! array_key_exists($attribute, $this->tableAttributes)) {
            throw new \Exception("Table attribute '{$attribute}' does not exist.");
        }

        return $this->tableAttributes[$attribute];
    }

    /**
     * Add a column in collection using attributes.
     *
     * @param  array $attributes
     * @return $this
     */
    public function addColumn(array $attributes)
    {
        $this->collection->push(new Column($attributes));

        return $this;
    }

    /**
     * Add a Column object in collection.
     *
     * @param \Yajra\Datatables\Html\Column $column
     * @return $this
     */
    public function add(Column $column)
    {
        $this->collection->push($column);

        return $this;
    }

    /**
     * Set datatables columns from array definition.
     *
     * @param array $columns
     * @return $this
     */
    public function columns(array $columns)
    {
        foreach ($columns as $key => $value) {
            if (! is_a($value, Column::class)) {
                if (is_array($value)) {
                    $attributes = array_merge(['name' => $key, 'data' => $key], $this->setTitle($key, $value));
                } else {
                    $attributes = [
                        'name'  => $value,
                        'data'  => $value,
                        'title' => $this->getQualifiedTitle($value),
                    ];
                }

                $this->collection->push(new Column($attributes));
            } else {
                $this->collection->push($value);
            }
        }

        return $this;
    }

    /**
     * Set title attribute of an array if not set.
     *
     * @param string $title
     * @param array $attributes
     * @return array
     */
    public function setTitle($title, array $attributes)
    {
        if (! isset($attributes['title'])) {
            $attributes['title'] = $this->getQualifiedTitle($title);
        }

        return $attributes;
    }

    /**
     * Convert string into a readable title.
     *
     * @param string $title
     * @return string
     */
    public function getQualifiedTitle($title)
    {
        return Str::title(str_replace(['.', '_'], ' ', Str::snake($title)));
    }

    /**
     * Add a checkbox column.
     *
     * @param  array $attributes
     * @return $this
     */
    public function addCheckbox(array $attributes = [])
    {
        $attributes = array_merge([
            'defaultContent' => '<input type="checkbox" ' . $this->html->attributes($attributes) . '/>',
            'title'          => $this->form->checkbox('', '', false, ['id' => 'dataTablesCheckbox']),
            'data'           => 'checkbox',
            'name'           => 'checkbox',
            'orderable'      => false,
            'searchable'     => false,
            'exportable'     => false,
            'printable'      => true,
            'width'          => '10px',
        ], $attributes);
        $this->collection->push(new Column($attributes));

        return $this;
    }

    /**
     * Add a action column.
     *
     * @param  array $attributes
     * @return $this
     */
    public function addAction(array $attributes = [])
    {
        $attributes = array_merge([
            'defaultContent' => '',
            'data'           => 'action',
            'name'           => 'action',
            'title'          => 'Action',
            'render'         => null,
            'orderable'      => false,
            'searchable'     => false,
            'exportable'     => false,
            'printable'      => true,
            'footer'         => '',
        ], $attributes);
        $this->collection->push(new Column($attributes));

        return $this;
    }

    /**
     * Add a index column.
     *
     * @param  array $attributes
     * @return $this
     */
    public function addIndex(array $attributes = [])
    {
        $indexColumn = Config::get('datatables.index_column', 'DT_Row_Index');
        $attributes  = array_merge([
            'defaultContent' => '',
            'data'           => $indexColumn,
            'name'           => $indexColumn,
            'title'          => '',
            'render'         => null,
            'orderable'      => false,
            'searchable'     => false,
            'exportable'     => false,
            'printable'      => true,
            'footer'         => '',
        ], $attributes);
        $this->collection->push(new Column($attributes));

        return $this;
    }

    /**
     * Setup ajax parameter
     *
     * @param  string|array $attributes
     * @return $this
     */
    public function ajax($attributes)
    {
        $this->ajax = $attributes;

        return $this;
    }

    /**
     * Generate DataTable's table html.
     *
     * @param array $attributes
     * @param bool $drawFooter
     * @return string
     */
    public function table(array $attributes = [], $drawFooter = false)
    {
        $this->tableAttributes = array_merge($this->tableAttributes, $attributes);

        $th       = $this->compileTableHeaders();
        $htmlAttr = $this->html->attributes($this->tableAttributes);

        $tableHtml = '<table ' . $htmlAttr . '>';
        $tableHtml .= '<thead><tr>' . implode('', $th) . '</tr></thead>';
        if ($drawFooter) {
            $tf = $this->compileTableFooter();
            $tableHtml .= '<tfoot><tr>' . implode('', $tf) . '</tr></tfoot>';
        }
        $tableHtml .= '</table>';

        return $tableHtml;
    }

    /**
     * Compile table headers and to support responsive extension.
     *
     * @return array
     */
    private function compileTableHeaders()
    {
        $th = [];
        foreach ($this->collection->toArray() as $row) {
            $thAttr = $this->html->attributes(
                array_only($row, ['class', 'id', 'width', 'style', 'data-class', 'data-hide'])
            );
            $th[]   = '<th ' . $thAttr . '>' . $row['title'] . '</th>';
        }

        return $th;
    }

    /**
     * Compile table footer contents.
     *
     * @return array
     */
    private function compileTableFooter()
    {
        $footer = [];
        foreach ($this->collection->toArray() as $row) {
            $footer[] = '<th>' . $row['footer'] . '</th>';
        }

        return $footer;
    }

    /**
     * Configure DataTable's parameters.
     *
     * @param  array $attributes
     * @return $this
     */
    public function parameters(array $attributes = [])
    {
        $this->attributes = array_merge($this->attributes, $attributes);

        return $this;
    }

    /**
     * Set custom javascript template.
     *
     * @param string $template
     * @return $this
     */
    public function setTemplate($template)
    {
        $this->template = $template;

        return $this;
    }

    /**
     * Get collection of columns.
     *
     * @return Collection
     */
    public function getColumns()
    {
        return $this->collection;
    }
}
