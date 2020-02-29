<?php
/**
 *
 */

namespace OmniTools\Core\View;

abstract class AbstractWidget
{
    /**
     *
     */
    abstract public function getPath(): string;

    /**
     *
     */
    public function render(
        \OmniTools\Core\View $view,
        array $payload
    ): string
    {
        // Obtain view file
        $view->addPath($this->getPath());
        $viewFile = 'resources/private/views/View.html.twig';

        foreach ($payload as $key => $value) {
            $view->assign($key, $value);
        }

        return $view->render($viewFile);
    }
}
