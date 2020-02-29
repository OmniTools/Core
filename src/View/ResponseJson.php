<?php

namespace OmniTools\Core\View;

class ResponseJson extends Response
{
    /**
     *
     */
    public function __construct(array $data = null)
    {
        $this->data = $data;
    }
}
