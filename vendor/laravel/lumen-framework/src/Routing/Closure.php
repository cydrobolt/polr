<?php

namespace Laravel\Lumen\Routing;

use Closure as BaseClosure;
use Illuminate\Http\Request;
use Illuminate\Validation\Validator;

class Closure
{
    use DispatchesJobs, ValidatesRequests {
        ValidatesRequests::buildFailedValidationResponse as baseBuildFailedValidationResponse;
        ValidatesRequests::formatValidationErrors as baseFormatValidationErrors;
    }

    /**
     * The response builder callback.
     *
     * @var \Closure
     */
    protected static $responseBuilder;

    /**
     * The error formatter callback.
     *
     * @var \Closure
     */
    protected static $errorFormatter;

    /**
     * Set the response builder callback.
     *
     * @param  \Closure  $callback
     * @return void
     */
    public static function buildResponseUsing(BaseClosure $callback)
    {
        static::$responseBuilder = $callback;
    }

    /**
     * Set the error formatter callback.
     *
     * @param  \Closure  $callback
     * @return void
     */
    public static function formatErrorsUsing(BaseClosure $callback)
    {
        static::$errorFormatter = $callback;
    }

    /**
     * {@inheritdoc}
     */
    protected function buildFailedValidationResponse(Request $request, array $errors)
    {
        if (isset(static::$responseBuilder)) {
            return call_user_func(static::$responseBuilder, $request, $errors);
        }

        return $this->baseBuildFailedValidationResponse($request, $errors);
    }

    /**
     * {@inheritdoc}
     */
    protected function formatValidationErrors(Validator $validator)
    {
        if (isset(static::$errorFormatter)) {
            return call_user_func(static::$errorFormatter, $validator);
        }

        return $this->baseFormatValidationErrors($validator);
    }
}
