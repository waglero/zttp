<?php

namespace Soyhuce\Zttp;

/**
 * Class ZttpResponse
 *
 * @package Soyhuce\Zttp
 *
 * @mixin \GuzzleHttp\Psr7\Response
 */
class ZttpResponse
{
    /** @var \GuzzleHttp\Psr7\Response */
    private $response;

    /**
     * ZttpResponse constructor.
     *
     * @param $response
     */
    public function __construct(\GuzzleHttp\Psr7\Response $response)
    {
        $this->response = $response;
    }

    /**
     * Returns the response body as string
     *
     * @return string
     */
    function body() : string
    {
        return (string)$this->response->getBody();
    }

    /**
     * Returns the response body as string
     *
     * @return array
     */
    public function json() : array
    {
        return (array)json_decode($this->response->getBody(), true);
    }

    /**
     * Returns the value of the header
     *
     * @param string $header
     * @return string
     */
    public function header(string $header) : string
    {
        return $this->response->getHeaderLine($header);
    }

    /**
     * Returns the headers
     *
     * @return array
     */
    public function headers() : array
    {
        return collect($this->response->getHeaders())->mapWithKeys(
            function ($v, $k) {
                return [$k => $v[0]];
            }
        )->all();
    }

    /**
     * Returns the status code
     *
     * @return int
     */
    public function status() : int
    {
        return $this->response->getStatusCode();
    }

    /**
     * true if the request was successful, false otherwise
     *
     * @return bool
     */
    public function isSuccess()
    {
        return $this->status() >= 200 && $this->status() < 300;
    }

    /**
     * True if the request was successful, false otherwise
     * Alias for isSuccess
     *
     * @return bool
     */
    public function isOk() : bool
    {
        return $this->isSuccess();
    }

    /**
     * true if the request is a redirection, false otherwise
     *
     * @return bool
     */
    public function isRedirect() : bool
    {
        return $this->status() >= 300 && $this->status() < 400;
    }

    /**
     * true if an error was made by the client, false otherwise
     *
     * @return bool
     */
    public function isClientError() : bool
    {
        return $this->status() >= 400 && $this->status() < 500;
    }

    /**
     * true if an error was made by the server, false otherwise
     *
     * @return bool
     */
    public function isServerError() : bool
    {
        return $this->status() >= 500;
    }

    function __call($method, $args)
    {
        return $this->response->{$method}(...$args);
    }
}

