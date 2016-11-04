<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        HttpException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception $e
     * @return void
     */
    public function report(Exception $e)
    {
        return parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        if ($request->wantsJson()) {
            $response = [
                'errors' => 'Whoops, something went wrong.'
            ];

            if (config('app.debug')) {
                $response['exception'] = get_class($e);
                $response['message'] = $e->getMessage();
                $response['trace'] = $e->getTrace();
            }

            $status = 400;
            if ($e instanceof HttpException) {
                $status = $e->getStatusCode();
            }

            return response()->json($response, $status);
        }

        if (!config('app.debug') && $e instanceof HttpException) {
            if ($e instanceof NotFoundHttpException) {
                if (env('SETTING_REDIRECT_404')) {
                    return redirect()->to(env('SETTING_INDEX_REDIRECT'));
                }
                return response(view('errors.404'), $e->getStatusCode());
            } elseif ($e->getStatusCode() >= 500) {
                return response(view('errors.500'), $e->getStatusCode());
            } else {
                $errorDetails = ['status_code' => $e->getStatusCode(), 'status_message' => $e->getMessage()];
                return response(view('errors.generic', $errorDetails), $e->getStatusCode());
            }
        }

        return parent::render($request, $e);
    }
}
