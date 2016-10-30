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
        if (env('APP_DEBUG') === false) {
            if ($e instanceof NotFoundHttpException) {
                if (env('SETTING_REDIRECT_404')) {
                    // Redirect 404s to SETTING_INDEX_REDIRECT
                    return redirect()->to(env('SETTING_INDEX_REDIRECT'));
                }
                // Otherwise, show a nice error page
                return view('errors.404');
            }
            if ($e instanceof HttpException) {
                $status_code = $e->getStatusCode();
                $status_message = $e->getMessage();
                Log::critical("$status_code error: $status_message");

                if ($status_code == 500) {
                    // Render a nice error page for 500s
                    return view('errors.500');
                } else {
                    // If not 500, render generic page
                    return response(
                        view('errors.generic', [
                            'status_code' => $status_code,
                            'status_message' => $status_message
                        ]),
                        $status_code);
                }
            }
        }
        return parent::render($request, $e);
    }
}
