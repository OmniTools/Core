<?php
/**
 *
 */

namespace OmniTools\Core\Http;

class Post extends AbstractHttpData
{
    /**
     *
     */
    public function __construct()
    {
        $this->setData($_POST);
    }
}
