<?php
/**
 *
 */

namespace OmniTools\Core;

class GenericObject
{
    /**
     *
     */
    public function __toString()
    {
        return 'Object ' . get_class($this);
    }
}