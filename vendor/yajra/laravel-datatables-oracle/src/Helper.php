<?php

namespace Yajra\Datatables;

use DateTime;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Illuminate\View\Compilers\BladeCompiler;

/**
 * Class Helper.
 *
 * @package Yajra\Datatables
 * @author  Arjay Angeles <aqangeles@gmail.com>
 */
class Helper
{
    /**
     * Places item of extra columns into results by care of their order.
     *
     * @param array $item
     * @param array $array
     * @return array
     */
    public static function includeInArray($item, $array)
    {
        if (self::isItemOrderInvalid($item, $array)) {
            return array_merge($array, [$item['name'] => $item['content']]);
        } else {
            $count = 0;
            $last  = $array;
            $first = [];
            foreach ($array as $key => $value) {
                if ($count == $item['order']) {
                    return array_merge($first, [$item['name'] => $item['content']], $last);
                }

                unset($last[$key]);
                $first[$key] = $value;

                $count++;
            }
        }
    }

    /**
     * Check if item order is valid.
     *
     * @param array $item
     * @param array $array
     * @return bool
     */
    protected static function isItemOrderInvalid($item, $array)
    {
        return $item['order'] === false || $item['order'] >= count($array);
    }

    /**
     * Determines if content is callable or blade string, processes and returns.
     *
     * @param string|callable $content Pre-processed content
     * @param array $data data to use with blade template
     * @param mixed $param parameter to call with callable
     * @return string Processed content
     */
    public static function compileContent($content, array $data, $param)
    {
        if (is_string($content)) {
            return static::compileBlade($content, static::getMixedValue($data, $param));
        } elseif (is_callable($content)) {
            return $content($param);
        }

        return $content;
    }

    /**
     * Parses and compiles strings by using Blade Template System.
     *
     * @param string $str
     * @param array $data
     * @return string
     * @throws \Exception
     */
    public static function compileBlade($str, $data = [])
    {
        if (view()->exists($str)) {
            return view($str, $data)->render();
        }

        $empty_filesystem_instance = new Filesystem();
        $blade                     = new BladeCompiler($empty_filesystem_instance, 'datatables');
        $parsed_string             = $blade->compileString($str);

        ob_start() && extract($data, EXTR_SKIP);

        try {
            eval('?>' . $parsed_string);
        } catch (\Exception $e) {
            ob_end_clean();
            throw $e;
        }

        $str = ob_get_contents();
        ob_end_clean();

        return $str;
    }

    /**
     * Get a mixed value of custom data and the parameters.
     *
     * @param  array $data
     * @param  mixed $param
     * @return array
     */
    public static function getMixedValue(array $data, $param)
    {
        $param = self::castToArray($param);

        foreach ($data as $key => $value) {
            if (isset($param[$key])) {
                $data[$key] = $param[$key];
            }
        }

        return $data;
    }

    /**
     * Cast the parameter into an array.
     *
     * @param mixed $param
     * @return array
     */
    public static function castToArray($param)
    {
        if ($param instanceof \stdClass) {
            $param = (array) $param;

            return $param;
        }

        if ($param instanceof Arrayable) {
            return $param->toArray();
        }

        return $param;
    }

    /**
     * Get equivalent or method of query builder.
     *
     * @param string $method
     * @return string
     */
    public static function getOrMethod($method)
    {
        if (! Str::contains(Str::lower($method), 'or')) {
            return 'or' . ucfirst($method);
        }

        return $method;
    }

    /**
     * Wrap value depending on database type.
     *
     * @param string $database
     * @param string $value
     * @return string
     */
    public static function wrapDatabaseValue($database, $value)
    {
        $parts  = explode('.', $value);
        $column = '';
        foreach ($parts as $key) {
            $column = static::wrapDatabaseColumn($database, $key, $column);
        }

        return substr($column, 0, strlen($column) - 1);
    }

    /**
     * Database column wrapper.
     *
     * @param string $database
     * @param string $key
     * @param string $column
     * @return string
     */
    public static function wrapDatabaseColumn($database, $key, $column)
    {
        switch ($database) {
            case 'mysql':
                $column .= '`' . str_replace('`', '``', $key) . '`' . '.';
                break;

            case 'sqlsrv':
                $column .= '[' . str_replace(']', ']]', $key) . ']' . '.';
                break;

            case 'pgsql':
            case 'sqlite':
                $column .= '"' . str_replace('"', '""', $key) . '"' . '.';
                break;

            default:
                $column .= $key . '.';
        }

        return $column;
    }

    /**
     * Converts array object values to associative array.
     *
     * @param mixed $row
     * @return array
     */
    public static function convertToArray($row)
    {
        $data = $row instanceof Arrayable ? $row->toArray() : (array) $row;
        foreach (array_keys($data) as $key) {
            if (is_object($data[$key]) || is_array($data[$key])) {
                $data[$key] = self::convertToArray($data[$key]);
            }
        }

        return $data;
    }

    /**
     * @param array $data
     * @return array
     */
    public static function transform(array $data)
    {
        return array_map(function ($row) {
            return self::transformRow($row);
        }, $data);
    }

    /**
     * Transform row data into an array.
     *
     * @param mixed $row
     * @return array
     */
    protected static function transformRow($row)
    {
        foreach ($row as $key => $value) {
            if ($value instanceof DateTime) {
                $row[$key] = $value->format('Y-m-d H:i:s');
            } else {
                if (is_object($value)) {
                    $row[$key] = (string) $value;
                } else {
                    $row[$key] = $value;
                }
            }
        }

        return $row;
    }

    /**
     * Build parameters depending on # of arguments passed.
     *
     * @param array $args
     * @return array
     */
    public static function buildParameters(array $args)
    {
        $parameters = [];

        if (count($args) > 2) {
            $parameters[] = $args[0];
            foreach ($args[1] as $param) {
                $parameters[] = $param;
            }
        } else {
            foreach ($args[0] as $param) {
                $parameters[] = $param;
            }
        }

        return $parameters;
    }

    /**
     * Replace all pattern occurrences with keyword
     *
     * @param array $subject
     * @param string $keyword
     * @param string $pattern
     * @return array
     */
    public static function replacePatternWithKeyword(array $subject, $keyword, $pattern = '$1')
    {
        $parameters = [];
        foreach ($subject as $param) {
            if (is_array($param)) {
                $parameters[] = self::replacePatternWithKeyword($param, $keyword, $pattern);
            } else {
                $parameters[] = str_replace($pattern, $keyword, $param);
            }
        }

        return $parameters;
    }
}
