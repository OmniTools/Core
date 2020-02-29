<?php
/**
 *
 */

namespace OmniTools\Core;

abstract class AbstractController extends GenericObject
{
    protected $action;
    protected $publicActions = [ ];

    /**
     *
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     *
     */
    public function getExtensionPath(): string
    {
        return dirname(dirname(dirname(dirname(dirname($this->getPath()))))) . '/';
    }

    /**
     *
     */
    abstract public function getPath(): string;

    /**
     *
     */
    public function getActionUri(string $action, array $payload = null): string
    {
        preg_match('#^OmniTools\\\\(\w+)\\\\Plugin\\\\(\w+)\\\\Controller\\\\(.*?)\\\\Controller$#i', get_class($this), $match);

        $url = $match[1] . '/' . $match[2] . '/' . str_replace('\\', '/', $match[3]) . '/' . $action;

        if (!empty($payload)) {
            $url .= '?' . http_build_query($payload);
        }

        return $url;
    }

    /**
     *
     */
    public function getUri($action = 'index', $controller = null, $plugin = null, $extension = null, array $payload = null): string
    {
        preg_match('#^OmniTools\\\\(\w+)\\\\Plugin\\\\(\w+)\\\\Controller\\\\(.*?)\\\\Controller$#i', get_class($this), $match);

        if ($controller === null) {
            $controller = $match[3];
        }

        if ($plugin === null) {
            $plugin = $match[2];
        }

        if ($extension === null) {
            $extension = $match[1];
        }

        $uri = $extension . '/' . $plugin . '/' . $controller . '/' . $action;

        if (!empty($payload)) {
            $uri .= '?' . http_build_query($payload);
        }

        return $uri;
    }

    /**
     *
     */
    public function isActionPublicCallable(string $action): bool
    {
        return in_array($action, $this->publicActions);
    }

    /**
     *
     */
    protected function render(string $file = null, array $data = null)
    {
        $response = new \OmniTools\Core\View\Response;

        if ($file === null) {
            $file = ucfirst($this->getAction());
        }

        $response->setFile($file . '.html.twig');

        if ($data !== null) {
            $response->setData($data);
        }

        return $response;
    }

    /**
     *
     */
    public function renderMenu(
        \OmniTools\Core\View $view,
        \OmniTools\Core\View\Front $front,
        \DI\Container $container
    ): string
    {
        // Generate menu class
        $menuClass = substr(get_class($this), 0, -10) . 'Menu';

        if (!class_exists($menuClass)) {
            return (string) null;
        }

        $menu = new $menuClass;
        $menu->setController($this);

        $action = method_exists($menu, 'generate' . ucfirst($this->action)) ? 'generate' . ucfirst($this->action) : 'generate';


        if (!empty($xaction = $front->getMenuAction())) {

            if ($xaction != 'index') {
                $action = 'generate' . ucfirst($xaction);
            }
        }

        $menuData = $container->call([ $menu, $action ]);

        $view->assign('menuData', $menuData);

        $viewFile = 'menu/Menu.html.twig';

        return $view->render($viewFile);
    }

    /**
     *
     */
    public function setAction(string $action): AbstractController
    {
        $this->action = $action;

        return $this;
    }
}
