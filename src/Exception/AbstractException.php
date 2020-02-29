<?php

namespace OmniTools\Core\Exception;

use Throwable;

abstract class AbstractException extends \Exception
{
    protected $info = null;

    /**
     *
     */
    public function __construct($message = null, $info = null)
    {
        $this->info = $info;

        parent::__construct($message);
    }

    /**
     *
     */
    public function getInfo()
    {
        return $this->info;
    }
}
