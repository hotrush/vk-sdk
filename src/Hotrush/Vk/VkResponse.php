<?php

namespace Hotrush\Vk;

class VkResponse
{
    /**
     * Response status code
     *
     * @var int
     */
    private $statusCode;

    /**
     * Response headers array
     *
     * @var array
     */
    private $headers;

    /**
     * Response body array
     *
     * @var array
     */
    private $body;

    /**
     * VkResponse constructor.
     *
     * @param $statusCode
     * @param array $headers
     * @param array $body
     */
    public function __construct($statusCode, array $headers = [], array $body = [])
    {
        $this->statusCode = (int) $statusCode;
        $this->headers = $headers;
        $this->body = $this->$body;
    }

    /**
     * Get response status code
     *
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Get response headers
     *
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Get response body
     *
     * @return array
     */
    public function getBody()
    {
        return $this->body;
    }
}
