<?php

namespace OmniTools\Core\View;

class ResponseRedirect extends Response
{
    private $uri;

    /**
     *
     */
    public function __construct($uri, array $payload = null)
    {
        $this->uri = $uri;
    }

    /**
     *
     */
    public function getUri(): string
    {
        return $this->uri;
    }
}
