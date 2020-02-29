<?php
/**
 *
 */

namespace OmniTools\Core\Http;

class Get extends AbstractHttpData
{
    /**
     *
     */
    public function __construct()
    {
        $this->setData($_GET);
    }
}
