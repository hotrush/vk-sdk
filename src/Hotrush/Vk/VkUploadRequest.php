<?php

namespace Hotrush\Vk;

class VkUploadRequest
{
    /**
     * Request upload url
     *
     * @var string
     */
    private $url;

    /**
     * Upload name
     *
     * @var string
     */
    private $name;

    /**
     * Upload content
     *
     * @var resource
     */
    private $contents;

    /**
     * VkUploadsRequest constructor.
     *
     * @param $url
     * @param $name
     * @param $contents
     */
    public function __construct($url, $name, $contents)
    {
        $this->url = $url;
        $this->name = $name;
        $this->contents = $contents;
    }

    /**
     * Get upload url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Get upload name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get upload content
     *
     * @return resource
     */
    public function getContents()
    {
        return $this->contents;
    }
}
