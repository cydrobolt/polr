<?php

namespace Yajra\Datatables\Generators;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class DataTablesMakeCommand.
 *
 * @package Yajra\Datatables\Generators
 * @author  Arjay Angeles <aqangeles@gmail.com>
 */
class DataTablesMakeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'datatables:make';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new DataTable service class.';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'DataTable';

    /**
     * The model class to be used by dataTable.
     *
     * @var string
     */
    protected $model;

    /**
     * DataTable export filename.
     *
     * @var string
     */
    protected $filename;

    /**
     * Build the class with the given name.
     *
     * @param  string $name
     * @return string
     */
    protected function buildClass($name)
    {
        $stub = $this->files->get($this->getStub());
        $stub = $this->replaceNamespace($stub, $name)->replaceClass($stub, $name);

        return $this->replaceModelImport($stub)->replaceModel($stub)->replaceFilename($stub);
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__ . '/stubs/datatables.stub';
    }

    /**
     * Replace model name.
     *
     * @param string $stub
     * @return mixed
     */
    protected function replaceModel(&$stub)
    {
        $model = explode('\\', $this->model);
        $model = array_pop($model);
        $stub  = str_replace('ModelName', $model, $stub);

        return $this;
    }

    /**
     * Replace model import.
     *
     * @param string $stub
     * @return $this
     */
    protected function replaceModelImport(&$stub)
    {
        $stub = str_replace(
            'DummyModel', str_replace('\\\\', '\\', $this->model), $stub
        );

        return $this;
    }

    /**
     * Replace the filename.
     *
     * @param string $stub
     * @return string
     */
    protected function replaceFilename(&$stub)
    {
        $stub = str_replace(
            'DummyFilename', $this->filename, $stub
        );

        return $stub;
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['model', null, InputOption::VALUE_NONE, 'Use the provided name as the model.', null],
        ];
    }

    /**
     * Determine if the class already exists.
     *
     * @param  string $rawName
     * @return bool
     */
    protected function alreadyExists($rawName)
    {
        $name = $this->parseName($rawName);

        $this->setModel($rawName);
        $this->setFilename($rawName);

        return $this->files->exists($this->getPath($name));
    }

    /**
     * Parse the name and format according to the root namespace.
     *
     * @param  string $name
     * @return string
     */
    protected function parseName($name)
    {
        $rootNamespace = $this->laravel->getNamespace();

        if (Str::startsWith($name, $rootNamespace)) {
            return $name;
        }

        if (Str::contains($name, '/')) {
            $name = str_replace('/', '\\', $name);
        }

        if (! Str::contains(Str::lower($name), 'datatable')) {
            $name .= 'DataTable';
        }

        return $this->parseName($this->getDefaultNamespace(trim($rootNamespace, '\\')) . '\\' . $name);
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . "\\" . $this->laravel['config']->get('datatables.namespace.base', 'DataTables');
    }

    /**
     * Set the model to be used.
     *
     * @param string $name
     */
    protected function setModel($name)
    {
        $rootNamespace  = $this->laravel->getNamespace();
        $modelNamespace = $this->laravel['config']->get('datatables.namespace.model');
        $this->model    = $this->option('model')
            ? $rootNamespace . "\\" . ($modelNamespace ? $modelNamespace . "\\" : "") . $name
            : $rootNamespace . "\\User";
    }

    /**
     * Set the filename for export.
     *
     * @param string $name
     */
    protected function setFilename($name)
    {
        $this->filename = Str::lower(Str::plural($name));
    }
}
