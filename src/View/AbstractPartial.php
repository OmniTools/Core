<?php
/**
 *
 */

namespace OmniTools\Core\View;

abstract class AbstractPartial
{
    protected $payload = [];

    /**
     *
     */
    protected function assign($key, $value): void
    {
        $this->payload[$key] = $value;
    }

    /**
     *
     */
    protected function getViewNamespace(): string
    {
        return str_replace('\\', '_', get_class($this));
    }

    /**
     *
     */
    abstract public function getPath(): string;

    /**
     *
     */
    public function render(
        \DI\Container $container,
        \OmniTools\Core\View $view,
        array $payload
    ): string
    {
        if (method_exists($this, 'onBeforeRendering')) {
            $container->call([ $this, 'onBeforeRendering' ]);
        }

        $view->addPath($this->getPath() . 'resources/private/views/', $this->getViewNamespace());

        $payload = array_merge($payload, $this->payload);

        $html = $view->render('@' . $this->getViewNamespace() . '/View.html.twig', $payload);

        return $html;
    }

    /**
     *
     */
    public function setPayload(array $payload): void
    {
        $this->payload = $payload;
    }
}