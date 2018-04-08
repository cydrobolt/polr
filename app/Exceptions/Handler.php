<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Response;

use App\Exceptions\Api\ApiException;

class Handler extends ExceptionHandler {
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
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        return parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        if (env('APP_DEBUG') != true) {
            // Render nice error pages if debug is off
            if ($e instanceof NotFoundHttpException) {
                // Handle 404 exceptions
                if (env('SETTING_REDIRECT_404')) {
                    // Redirect 404s to SETTING_INDEX_REDIRECT
                    return redirect()->to(env('SETTING_INDEX_REDIRECT'));
                }
                // Otherwise, show a nice error page
                return response(view('errors.404'), 404);
            }
            if ($e instanceof HttpException) {
                // Handle HTTP exceptions thrown by public-facing controllers
                $status_code = $e->getStatusCode();
                $status_message = $e->getMessage();

                if ($status_code == 500) {
                    // Render a nice error page for 500s
                    return response(view('errors.500'), 500);
                }
                else {
                    // If not 500, render generic page
                    return response(
                        view('errors.generic', [
                            'status_code' => $status_code,
                            'status_message' => $status_message
                        ]), $status_code);
                }
            }
            if ($e instanceof ApiException) {
                // Handle HTTP exceptions thrown by API controllers
                $status_code = $e->getCode();
                $encoded_status_message = $e->getEncodedErrorMessage();
                if ($e->response_type == 'json') {
                    return response($encoded_status_message, $status_code)
                        ->header('Content-Type', 'application/json')
                        ->header('Access-Control-Allow-Origin', '*');
                }

                return response($encoded_status_message, $status_code)
                    ->header('Content-Type', 'text/plain')
                    ->header('Access-Control-Allow-Origin', '*');
            }
        }

        return parent::render($request, $e);
    }
}
