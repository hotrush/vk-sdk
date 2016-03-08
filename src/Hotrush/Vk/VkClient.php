<?php

namespace Hotrush\Vk;

use GuzzleHttp\Client as HttpClient;

class VkClient
{
    /**
     * Base api url for api
     */
    const API_BASE_URL = 'https://api.vk.com/method/';

    /**
     * Guzzle http client instance
     *
     * @var HttpClient
     */
    private $client;

    /**
     * VkClient constructor.
     */
    public function __construct()
    {
        $this->client = new HttpClient([
            'base_uri' => static::API_BASE_URL,
        ]);
    }

    /**
     * Send request to api and get a VkResponse instance
     *
     * @param VkRequest $request
     * @return VkResponse
     */
    public function sendRequest(VkRequest $request)
    {
        $response = $this->client->request(
            $request->getMethod(),
            $request->getEndpoint(),
            $request->getRequestParams()
        );

        return new VkResponse(
            $response->getStatusCode(),
            $response->getHeaders(),
            json_decode($response->getBody()->getContents(), true)
        );
    }

    /**
     * Send file upload request to api
     *
     * @param VkUploadRequest $request
     * @return VkResponse
     */
    public function sendUploadRequest(VkUploadRequest $request)
    {
        $response = $this->client->request(
            'POST',
            $request->getUrl(),
            [
                'multipart' => [
                    [
                        'name' => $request->getName(),
                        'contents' => $request->getContents()
                    ]
                ]
            ]
        );

        return new VkResponse(
            $response->getStatusCode(),
            $response->getHeaders(),
            json_decode($response->getBody()->getContents(), true)
        );
    }
}
