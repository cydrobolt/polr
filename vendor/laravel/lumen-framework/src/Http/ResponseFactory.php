<?php

namespace Laravel\Lumen\Http;

use Illuminate\Support\Str;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Support\Arrayable;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ResponseFactory
{
    /**
     * Return a new response from the application.
     *
     * @param  string  $content
     * @param  int     $status
     * @param  array   $headers
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function make($content = '', $status = 200, array $headers = [])
    {
        return new Response($content, $status, $headers);
    }

    /**
     * Return a new JSON response from the application.
     *
     * @param  string|array  $data
     * @param  int    $status
     * @param  array  $headers
     * @param  int    $options
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function json($data = [], $status = 200, array $headers = [], $options = 0)
    {
        if ($data instanceof Arrayable) {
            $data = $data->toArray();
        }

        return new JsonResponse($data, $status, $headers, $options);
    }

    /**
     * Create a new file download response.
     *
     * @param  \SplFileInfo|string  $file
     * @param  string  $name
     * @param  array   $headers
     * @param  null|string  $disposition
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function download($file, $name = null, array $headers = [], $disposition = 'attachment')
    {
        $response = new BinaryFileResponse($file, 200, $headers, true, $disposition);

        if (! is_null($name)) {
            return $response->setContentDisposition($disposition, $name, str_replace('%', '', Str::ascii($name)));
        }

        return $response;
    }
}
