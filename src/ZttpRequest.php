<?php

namespace Soyhuce\Zttp;

use Psr\Http\Message\RequestInterface;

/**
 * Class ZttpRequest
 *
 * @package Soyhuce\Zttp
 * @author  philippe
 */
class ZttpRequest
{
    /** @var RequestInterface */
    private $request;

    /**
     * ZttpRequest constructor.
     *
     * @param RequestInterface $request
     */
    public function __construct(RequestInterface $request)
    {
        $this->request = $request;
    }

    /**
     * Returns request's url
     *
     * @return string
     */
    public function url() : string
    {
        return (string)$this->request->getUri();
    }

    /**
     * Returns request's method
     *
     * @return string
     */
    function method() : string
    {
        return $this->request->getMethod();
    }

    /**
     * Returns request's body
     *
     * @return string
     */
    function body() : string
    {
        return (string)$this->request->getBody();
    }

    /**
     * Returns request's headers
     *
     * @return array
     */
    function headers() : array
    {
        return collect($this->request->getHeaders())->mapWithKeys(
            function ($values, $header) {
                return [$header => $values[0]];
            }
        )->all();
    }
}
