<?php

namespace Soyhuce\Zttp;

/**
 * Class Zttp
 *
 * @package Soyhuce\Zttp
 *
 * @method static ZttpRequest withoutRedirecting()
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

    /**
     * Returns client singleton and creates it if needed
     *
     * @static
     * @return \GuzzleHttp\Client
     */
    public static function client() : \GuzzleHttp\Client
    {
        return static::$client ? : static::$client = new \GuzzleHttp\Client();
    }
}
