<?php

namespace Hotrush\Vk;

use Hotrush\Vk\Exceptions\VkException;

class Vk
{
    /**
     * VK-SDK version
     */
    const VK_SDK_VERSION = '0.1.0';

    /**
     * Env variable name for App ID
     */
    const APP_ID_ENV_NAME = 'VK_APP_ID';

    /**
     * Env variable name for App Secret
     */
    const APP_SECRET_ENV_NAME = 'VK_APP_SECRET';

    /**
     * Default vk api version
     */
    const API_VERSION_DEFAULT = '5.45';

    /**
     * VkApp instance
     *
     * @var VkApp
     */
    private $app;

    /**
     * VkClient instance
     *
     * @var VkClient
     */
    private $client;

    /**
     * Vk constructor.
     *
     * @param array $config
     * @throws VkException
     */
    public function __construct(array $config = [])
    {
        $config = array_merge([
            'app_id' => getenv(self::APP_ID_ENV_NAME),
            'app_secret' => getenv(self::APP_SECRET_ENV_NAME),
            'api_version' => getenv(self::API_VERSION_DEFAULT),
        ], $config);

        if (!$config['app_id'])
        {
            throw new VkException('Required VK "app_id" is not provided');
        }

        if (!$config['app_secret'])
        {
            throw new VkException('Required VK "app_secret" is not provided');
        }

        $this->app = new VkApp($config['app_id'], $config['app_secret']);

        $this->client = new VkClient();
    }

    /**
     * Get the app instance
     *
     * @return VkApp
     */
    public function getApp()
    {
        return $this->app;
    }

    /**
     * Get the VkClient instance
     *
     * @return VkClient
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Send a get request
     *
     * @param $endpoint
     * @param array $params
     * @param null $accessToken
     * @return VkResponse
     */
    public function get($endpoint, $params = [], $accessToken = null)
    {
        return $this->sendRequest(
            'GET',
            $endpoint,
            $params,
            $accessToken
        );
    }

    /**
     * Send a post request
     *
     * @param $endpoint
     * @param array $params
     * @param null $accessToken
     * @return VkResponse
     */
    public function post($endpoint, $params = [], $accessToken = null)
    {
        return $this->sendRequest(
            'POST',
            $endpoint,
            $params,
            $accessToken
        );
    }

    public function uploadWallPhoto($photoContents, $accessToken, $userId = null, $groupId = null)
    {
        $requestParams = [];
        if ($groupId)
        {
            $requestParams['group_id'] = $groupId;
        }

        $uploadServerResponse = $this->post('photos.getWallUploadServer', $requestParams, $accessToken);

        $uploadResponse = $this->post($uploadServerResponse->getBody()['response']['upload_url'], [
            'photo' => $photoContents
        ]);

        $savePhotoParams = [
            'photo' => $uploadResponse->getBody()['photo'],
            'server' => $uploadResponse->getBody()['server'],
            'hash' => $uploadResponse->getBody()['hash'],
        ];

        if ($userId)
        {
            $savePhotoParams['user_id'] = $userId;
        }
        else if ($groupId)
        {
            $savePhotoParams['group_id'] = $groupId;
        }

        return $this->post('photos.saveWallPhoto', $savePhotoParams);
    }

    /**
     * Send request to api
     *
     * @param $method
     * @param $endpoint
     * @param array $params
     * @param null $accessToken
     * @return VkResponse
     */
    private function sendRequest($method, $endpoint, $params = [], $accessToken = null)
    {
        $request = $this->createRequest($method, $endpoint, $params, $accessToken);

        return $this->client->sendRequest($request);
    }

    /**
     * Create a request instance with data provided
     *
     * @param $method
     * @param $endpoint
     * @param array $params
     * @param null $accessToken
     * @return VkRequest
     */
    private function createRequest($method, $endpoint, $params = [], $accessToken = null)
    {
        return new VkRequest(
            $method,
            $endpoint,
            $params,
            $accessToken
        );
    }
}
