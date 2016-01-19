<?php

namespace Laravel\Lumen\Exceptions;

use Exception;
use Illuminate\Http\Response;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Symfony\Component\Console\Application as ConsoleApplication;
use Symfony\Component\Debug\ExceptionHandler as SymfonyExceptionHandler;

class Handler implements ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        if ($this->shouldReport($e)) {
            app('Psr\Log\LoggerInterface')->error($e);
        }
    }

    /**
     * Determine if the exception should be reported.
     *
     * @param  \Exception  $e
     * @return bool
     */
    public function shouldReport(Exception $e)
    {
        return ! $this->shouldntReport($e);
    }

    /**
     * Determine if the exception is in the "do not report" list.
     *
     * @param  \Exception  $e
     * @return bool
     */
    protected function shouldntReport(Exception $e)
    {
        foreach ($this->dontReport as $type) {
            if ($e instanceof $type) {
                return true;
            }
        }

        return false;
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function render($request, Exception $e)
    {
        $response = (new SymfonyExceptionHandler(env('APP_DEBUG', false)))->createResponse($e);

        return $this->toIlluminateResponse($response, $e);
    }

    /**
     * Map exception into an illuminate response.
     *
     * @param  \Symfony\Component\HttpFoundation\Response  $response
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    protected function toIlluminateResponse($response, Exception $e)
    {
        $response = new Response($response->getContent(), $response->getStatusCode(), $response->headers->all());

        $response->exception = $e;

        return $response;
    }

    /**
     * Render an exception to the console.
     *
     * @param  \Symfony\Component\Console\Output\OutputInterface  $output
     * @param  \Exception  $e
     * @return void
     */
    public function renderForConsole($output, Exception $e)
    {
        (new ConsoleApplication)->renderException($e, $output);
    }
}
