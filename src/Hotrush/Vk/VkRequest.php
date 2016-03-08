<?php

namespace Hotrush\Vk;

use Hotrush\Vk\Exceptions\VkException;

class VkRequest
{
    /**
     * Request method
     *
     * @var string
     */
    private $method;

    /**
     * Request params array
     *
     * @var array
     */
    private $params;

    /**
     * OAuth access token
     *
     * @var null|string
     */
    private $accessToken;

    /**
     * Api endpoint
     *
     * @var string
     */
    private $endpoint;

    /**
     * VkRequest constructor.
     * @param $method
     * @param $endpoint
     * @param array $params
     * @param null $accessToken
     */
    public function __construct($method, $endpoint, array $params = [], $accessToken = null)
    {
        $this->setMethod($method);
        $this->endpoint = (string) $endpoint;
        $this->setParams($params);
        $this->accessToken = $accessToken;
    }

    /**
     * Set request method
     *
     * @param $method
     * @throws VkException
     */
    private function setMethod($method)
    {
        if (!in_array(strtoupper($method), ['GET','POST']))
        {
            throw new VkException('Invalid request method');
        }

        $this->method = strtoupper($method);
    }

    /**
     * Set request params
     *
     * @param array $params
     */
    private function setParams(array $params = [])
    {
        $this->params = $params;
    }

    /**
     * Get request method
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Get request endpoint
     *
     * @return string
     */
    public function getEndpoint()
    {
        return $this->endpoint;
    }

    /**
     * Get request params
     *
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Get request access token
     *
     * @return null|string
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * Get request params formatted for guzzle client
     *
     * @return array
     */
    public function getRequestParams()
    {
        $requestParams = [];

        switch($this->method)
        {
            case 'GET':
                $requestParams['query'] = $this->params;
                if ($this->accessToken)
                {
                    $requestParams['query']['access_token'] = $this->accessToken;
                }
                break;
            case 'POST':
                $requestParams['form_params'] = $this->params;
                if ($this->accessToken)
                {
                    $requestParams['form_params']['access_token'] = $this->accessToken;
                }
                break;
        }
        $requestParams['headers'] = $this->getDefaultHeaders();

        return $requestParams;
    }

    /**
     * Get default request headers
     *
     * @return array
     */
    public function getDefaultHeaders()
    {
        return [
            'User-Agent' => 'hotrush/vk-sdk '.Vk::VK_SDK_VERSION,
            'Accept' => 'application/json',
        ];
    }
}
