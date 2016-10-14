<?php

namespace Yajra\Datatables\Generators;

use Illuminate\Console\GeneratorCommand;

/**
 * Class DataTablesScopeCommand.
 *
 * @package Yajra\Datatables\Generators
 * @author  Arjay Angeles <aqangeles@gmail.com>
 */
class DataTablesScopeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'datatables:scope';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new DataTable Scope class.';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'DataTable Scope';

    /**
     * Get the default namespace for the class.
     *
     * @param  string $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\DataTables\Scopes';
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__ . '/stubs/scopes.stub';
    }
}
