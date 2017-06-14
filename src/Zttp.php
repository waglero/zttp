<?php

namespace Soyhuce\Zttp;

/**
 * Class Zttp
 *
 * @package Soyhuce\Zttp
 *
 * @method static ZttpRequest withoutRedirecting()
 * @method static ZttpRequest withoutVerifying()
 * @method static ZttpRequest asJson()
 * @method static ZttpRequest asFormParams()
 * @method static ZttpRequest bodyFormat(string $format)
 * @method static ZttpRequest contentType(string $contentType)
 * @method static ZttpRequest accept(string $accept)
 * @method static ZttpRequest withHeaders(array $headers)
 * @method static ZttpResponse get(string $url, array $params = [])
 * @method static ZttpResponse post(string $url, array $params = [])
 * @method static ZttpResponse patch(string $url, array $params = [])
 * @method static ZttpResponse put(string $url, array $params = [])
 * @method static ZttpResponse delete(string $url, array $params = [])
 * @method static ZttpResponse send(string $method, string $url, array $options)
 */
class Zttp
{
    /** @var \GuzzleHttp\Client */
    static $client;

    public static function __callStatic($method, $args)
    {
        return ZttpRequest::new(static::client())->{$method}(...$args);
    }

<<<<<<< HEAD
    /**
     * Returns client singleton and creates it if needed
     *
     * @static
     * @return \GuzzleHttp\Client
     */
    public static function client() : \GuzzleHttp\Client
||||||| merged common ancestors
    static function client()
    {
        return static::$client ?: static::$client = new \GuzzleHttp\Client;
    }
}

class ZttpRequest
{
    function __construct($client)
    {
        $this->client = $client;
        $this->bodyFormat = 'json';
        $this->options = [
            'http_errors' => false,
        ];
    }

    static function new(...$args)
    {
        return new self(...$args);
    }

    function withoutRedirecting()
    {
        return tap($this, function ($request) {
            return $this->options = array_merge_recursive($this->options, [
                'allow_redirects' => false
            ]);
        });
    }

    function asJson()
    {
        return $this->bodyFormat('json')->contentType('application/json');
    }

    function asFormParams()
    {
        return $this->bodyFormat('form_params')->contentType('application/x-www-form-urlencoded');
    }

    function bodyFormat($format)
    {
        return tap($this, function ($request) use ($format) {
            $this->bodyFormat = $format;
        });
    }

    function contentType($contentType)
    {
        return $this->withHeaders(['Content-Type' => $contentType]);
    }

    function accept($header)
    {
        return $this->withHeaders(['Accept' => $header]);
    }

    function withHeaders($headers)
    {
        return tap($this, function ($request) use ($headers) {
            return $this->options = array_merge_recursive($this->options, [
                'headers' => $headers
            ]);
        });
    }

    function get($url, $queryParams = [])
=======
    static function client()
    {
        return static::$client ?: static::$client = new \GuzzleHttp\Client;
    }
}

class ZttpRequest
{
    function __construct($client)
    {
        $this->client = $client;
        $this->bodyFormat = 'json';
        $this->options = [
            'http_errors' => false,
        ];
    }

    static function new(...$args)
    {
        return new self(...$args);
    }

    function withoutRedirecting()
    {
        return tap($this, function ($request) {
            return $this->options = array_merge_recursive($this->options, [
                'allow_redirects' => false
            ]);
        });
    }

    function withoutVerifying()
    {
         return tap($this, function ($request) {
            return $this->options = array_merge_recursive($this->options, [
                'verify' => false
            ]);
        });
    }

    function asJson()
    {
        return $this->bodyFormat('json')->contentType('application/json');
    }

    function asFormParams()
    {
        return $this->bodyFormat('form_params')->contentType('application/x-www-form-urlencoded');
    }

    function bodyFormat($format)
    {
        return tap($this, function ($request) use ($format) {
            $this->bodyFormat = $format;
        });
    }

    function contentType($contentType)
    {
        return $this->withHeaders(['Content-Type' => $contentType]);
    }

    function accept($header)
    {
        return $this->withHeaders(['Accept' => $header]);
    }

    function withHeaders($headers)
    {
        return tap($this, function ($request) use ($headers) {
            return $this->options = array_merge_recursive($this->options, [
                'headers' => $headers
            ]);
        });
    }

    function get($url, $queryParams = [])
>>>>>>> kitetail/master
    {
        return static::$client ? : static::$client = new \GuzzleHttp\Client();
    }
}
