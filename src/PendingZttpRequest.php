<?php

namespace Soyhuce\Zttp;

/**
 * Class PendingZttpRequest
 *
 * @package Soyhuce\Zttp
 */
class PendingZttpRequest
{
    /** @var \Illuminate\Support\Collection */
    private $beforeSendingCallbacks;
    /** @var string */
    private $bodyFormat;
    /** @var array */
    private $options;

    /**
     * PendingZttpRequest constructor.
     */
    public function __construct()
    {
        $this->beforeSendingCallbacks = collect();
        $this->bodyFormat = 'json';
        $this->options = [
            'http_errors' => false,
        ];
    }

    /**
     * Creates a new PendingZttpRequest
     *
     * @static
     * @param array ...$args
     * @return self
     */
    public static function new(...$args) : self
    {
        return new static(...$args);
    }

    /**
     * Disables http redirects
     *
     * @return self
     */
    public function withoutRedirecting() : self
    {
        return tap(
            $this,
            function () {
                return $this->options = array_merge_recursive(
                    $this->options,
                    [
                        'allow_redirects' => false,
                    ]
                );
            }
        );
    }

    /**
     * Disables https verification
     *
     * @return self
     */
    function withoutVerifying()
    {
        return tap(
            $this,
            function () {
                return $this->options = array_merge_recursive(
                    $this->options,
                    [
                        'verify' => false,
                    ]
                );
            }
        );
    }

    /**
     * Send parameters as json
     *
     * @return self
     */
    public function asJson() : self
    {
        return $this->bodyFormat('json')->contentType('application/json');
    }

    /**
     * Send parameters as form params
     *
     * @return self
     */
    public function asFormParams() : self
    {
        return $this->bodyFormat('form_params')->contentType('application/x-www-form-urlencoded');
    }

    /**
     * Send parameters as multipart
     *
     * @return self
     */
    public function asMultipart() : self
    {
        return $this->bodyFormat('multipart');
    }

    /**
     * Set parameters format
     *
     * @param string $format
     * @return self
     */
    public function bodyFormat(string $format) : self
    {
        return tap(
            $this,
            function () use ($format) {
                $this->bodyFormat = $format;
            }
        );
    }

    /**
     * Set Content-Type header
     *
     * @param $contentType
     * @return self
     */
    public function contentType(string $contentType) : self
    {
        return $this->withHeaders(['Content-Type' => $contentType]);
    }

    /**
     * Set Accept header
     *
     * @param string $accept
     * @return self
     */
    public function accept(string $accept) : self
    {
        return $this->withHeaders(['Accept' => $accept]);
    }

    /**
     * Set headers
     *
     * @param array $headers
     * @return self
     */
    function withHeaders(array $headers) : self
    {
        return tap(
            $this,
            function () use ($headers) {
                $this->options = array_merge_recursive(
                    $this->options,
                    [
                        'headers' => $headers,
                    ]
                );
            }
        );
    }

    /**
     * Set basic auth credentials
     *
     * @param string $username
     * @param string $password
     * @return self
     */
    public function withBasicAuth(string $username, string $password) : self
    {
        return tap(
            $this,
            function () use ($username, $password) {
                return $this->options = array_merge_recursive(
                    $this->options,
                    [
                        'auth' => [$username, $password],
                    ]
                );
            }
        );
    }

    /**
     * Set digest auth credentials
     *
     * @param string $username
     * @param string $password
     * @return self
     */
    public function withDigestAuth(string $username, string $password) : self
    {
        return tap(
            $this,
            function () use ($username, $password) {
                return $this->options = array_merge_recursive(
                    $this->options,
                    [
                        'auth' => [$username, $password, 'digest'],
                    ]
                );
            }
        );
    }

    /**
     * Set timeout
     *
     * @param int $seconds
     * @return $this
     */
    function timeout(int $seconds) : self
    {
        $this->options['timeout'] = $seconds;
        return $this;
    }

    /**
     * Add a beforeSending callback
     *
     * @param callable $callback
     * @return self
     */
    public function beforeSending(callable $callback) : self
    {
        return tap(
            $this,
            function () use ($callback) {
                $this->beforeSendingCallbacks[] = $callback;
            }
        );
    }

    /**
     * Sends a GET http request
     *
     * @param string $url
     * @param array $queryParams
     * @return ZttpResponse
     */
    public function get(string $url, array $queryParams = []) : ZttpResponse
    {
        return $this->send(
            'GET',
            $url,
            [
                'query' => $queryParams,
            ]
        );
    }

    /**
     * Sends a POST http request
     *
     * @param string $url
     * @param array $params
     * @return ZttpResponse
     */
    public function post(string $url, array $params = []) : ZttpResponse
    {
        return $this->send(
            'POST',
            $url,
            [
                $this->bodyFormat => $params,
            ]
        );
    }

    /**
     * Sends a PATCH http request
     *
     * @param string $url
     * @param array $params
     * @return ZttpResponse
     */
    public function patch(string $url, array $params = []) : ZttpResponse
    {
        return $this->send(
            'PATCH',
            $url,
            [
                $this->bodyFormat => $params,
            ]
        );
    }

    /**
     * Sends a PUT http request
     *
     * @param string $url
     * @param array $params
     * @return ZttpResponse
     */
    public function put(string $url, array $params = []) : ZttpResponse
    {
        return $this->send(
            'PUT',
            $url,
            [
                $this->bodyFormat => $params,
            ]
        );
    }

    /**
     * Sends a DELETE http request
     *
     * @param string $url
     * @param array $params
     * @return ZttpResponse
     */
    public function delete(string $url, array $params = []) : ZttpResponse
    {
        return $this->send(
            'DELETE',
            $url,
            [
                $this->bodyFormat => $params,
            ]
        );
    }

    /**
     * Sends the request
     *
     * @param string $method
     * @param string $url
     * @param array $options
     * @return ZttpResponse
     * @throws ConnectionException
     */
    public function send(string $method, string $url, array $options) : ZttpResponse
    {
        try {
            return new ZttpResponse($this->buildClient()->request($method, $url, $this->mergeOptions(
                    ['query' => $this->parseQueryParams($url)], $options
                )));
        } catch (\GuzzleHttp\Exception\ConnectException $e) {
            throw new ConnectionException($e->getMessage(), 0, $e);
        }
    }

    /**
     * Creates a new \GuzzleHttp\Client with a \GuzzleHttp\HandlerStack
     *
     * @return \GuzzleHttp\Client
     */
    protected function buildClient()
    {
        return new \GuzzleHttp\Client(['handler' => $this->buildHandlerStack()]);
    }

    /**
     * Creates a new \GuzzleHttp\HandlerStack
     *
     * @return \GuzzleHttp\HandlerStack
     */
    protected function buildHandlerStack()
    {
        return tap(
            \GuzzleHttp\HandlerStack::create(),
            function (\GuzzleHttp\HandlerStack $stack) {
                $stack->push($this->buildBeforeSendingHandler());
            }
        );
    }

    /**
     * Creates the closure
     *
     * @return \Closure
     */
    protected function buildBeforeSendingHandler()
    {
        return function ($handler) {
            return function ($request, $options) use ($handler) {
                return $handler($this->runBeforeSendingCallbacks($request), $options);
            };
        };
    }

    /**
     * Runs the callbacks before the request is sent
     *
     * @param $request
     * @return mixed
     */
    protected function runBeforeSendingCallbacks($request)
    {
        return tap(
            $request,
            function ($request) {
                $this->beforeSendingCallbacks->each->__invoke(new ZttpRequest($request));
            }
        );
    }

    /**
     * Merges the options
     *
     * @param array ...$options
     * @return array
     */
    protected function mergeOptions(...$options) : array
    {
        return array_merge_recursive($this->options, ...$options);
    }

    /**
     * Parses query params and put them in a key => value array
     *
     * @param string $url
     * @return array
     */
    protected function parseQueryParams(string $url) : array
    {
        return tap(
            [],
            function (&$query) use ($url) {
                parse_str(parse_url($url, PHP_URL_QUERY), $query);
            }
        );
    }
}
