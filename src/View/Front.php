<?php
/**
 *
 */

namespace OmniTools\Core\View;

class Front extends \OmniTools\Core\GenericObject
{
    protected $config = [ ];
    protected $localconfig;
    protected $container;

    /**
     *
     */
    public function __construct(
        \DI\Container $container,
        \OmniTools\Core\Config $config
    )
    {
        $this->localconfig = $config;
        $this->container = $container;
    }

    /**
     *
     */
    public function flash(string $message, $type = 'Success'): void
    {
        $key = md5($message . '_' . $type);

        if (!isset($_SESSION['omnitools']['front']['messages'])) {
            $_SESSION['omnitools']['front']['messages'] = [];
        }

        $_SESSION['omnitools']['front']['messages'][$key] = [
            'message' => $message,
            'type' => $type
        ];
    }

    /**
     *
     */
    public function getAssetPath($localpath): string
    {
        preg_match('#^(.*?)\/(.*?)\/(.*?)\/(.*?)$#', $localpath, $match);

        $class = '\\OmniTools\\' . $match[1] . '\\Plugin\\' . $match[2] . '\\Controller\\' . $match[3] . '\\Controller';
        $controller = new $class;

        $path = $controller->getPath() . 'resources/public/' . $match[4];

        $info = pathinfo($path);
        $hash = md5($localpath);

        $pathSegment = substr($hash, 0, 2) . '/' . substr($hash, 2, 2) . '/' . substr($hash, 4) . '.' . $info['extension'];
        $cachepath = CORE_DIR . 'files/assets/' . $pathSegment;

        if (!file_exists($cachepath)) {

            $oldumask = umask(0);

            if (!file_exists(dirname($cachepath))) {
                mkdir(dirname($cachepath), 0777, true);
            }

            copy($path, $cachepath);

            umask($oldumask);
        }

        return $this->localconfig->get('assets.public') . $pathSegment;
    }

    /**
     *
     */
    public function getButtons(): array
    {
        if (empty($this->config['buttons'])) {
            return [];
        }

        $buttons = [];

        foreach($this->config['buttons'] as $button) {
            $buttons[] = new \OmniTools\Core\View\MenuItem($button[0], $button[1] ?? null, $button[2] ?? null, $button[3] ?? null, $button[4] ?? null, $button[5] ?? []);
        }

        return $buttons;
    }

    /**
     *
     */
    public function getLayout(): string
    {
        return $this->config['layout'] ?? 'Base';
    }

    /**
     *
     */
    public function getMenuAction(): ?string
    {
        return $this->config['menu']['action'] ?? null;
    }

    /**
     *
     */
    public function getMessages(): array
    {
        if (empty($_SESSION['omnitools']['front']['messages'])) {
            return [];
        }

        $messages = $_SESSION['omnitools']['front']['messages'];

        $_SESSION['omnitools']['front']['messages'] = [];

        return $messages;
    }

    /**
     *
     */
    public function getTitle(): string
    {
        return $this->config['title'] ?? (string) null;
    }

    /**
     *
     */
    public function getTopMenu()
    {
        return $this->container->get(\OmniTools\Core\View\Interfaces\TopMenuInterface::class);
    }

    /**
     *
     */
    public function getUri($extension, $plugin, $controller, $action = 'index', array $payload = null): string
    {
        $url = $extension . '/' . $plugin . '/' . $controller . '/' . $action;

        if (!empty($payload)) {
            $url .= '?' . http_build_query($payload);
        }

        return $url;
    }

    /**
     *
     */
    public function config(array $data): void
    {
        foreach ($data as $key => $value) {
            $this->config[$key] = $value;
        }
    }

    /**
     *
     */
    public function renderBreadcrumb(): ?string
    {
        if (empty($this->config['breadcrumb'])) {
            return (string) null;
        }

        $html = '<nav class="title-breadcrumb">';

        foreach ($this->config['breadcrumb'] as $breadcrumb) {

            if (!empty($breadcrumb[1])) {
                $html .= '<a href="' . $breadcrumb[1] . '">' . $breadcrumb[0] . '</a>';
            }
            else {
                $html .= '<a>' . $breadcrumb[0] . '</a>';
            }
        }

        $html .= '</nav>';

        return $html;
    }

    /**
     *
     */
    public function renderPartial(string $partialClass, array $payload = null): string
    {
        $partialClass = '/OmniTools/' . $partialClass . '/Partial';
        $partialClass = str_replace('/', '\\', $partialClass);

        $partial = new $partialClass;

        if ($payload === null) {
            $payload = [];
        }

        $partial->setPayload($payload);

        return $this->container->call([ $partial, 'render' ], [
            'payload' => $payload
        ]);
    }

    /**
     *
     */
    public function renderWidget(string $widgetClass)
    {
        $widgetClass = '\OmniTools\\' . str_replace('/', '\\', $widgetClass) . '\Widget';

        $widget = new $widgetClass;
        $payload = [];

        if (method_exists($widget, 'onBeforeRendering')) {
            $payload = $this->container->call([$widget, 'onBeforeRendering']);
        }

        $html = $this->container->call([ $widget, 'render' ], [ 'payload' => $payload ]);

        return $html;
    }
}
