<?php
/**
 *
 */

namespace OmniTools\Core\View;

abstract class AbstractMenu
{
    protected $controller;

    /**
     *
     */
    public function setController(\OmniTools\Core\AbstractController $controller): void
    {
        $this->controller = $controller;
    }
}
