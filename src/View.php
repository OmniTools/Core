<?php
/**
 *
 */

namespace OmniTools\Core;

class View
{
    protected $variables = [ ];
    protected $loader;
    protected $twig;

    /**
     *
     */
    public function __construct()
    {
        $this->loader = new \Twig\Loader\FilesystemLoader(CORE_DIR . 'app/vendor/omnitools/template/resources/private/views/');
        $this->twig = new \Twig\Environment($this->loader, [
         //   'cache' => CORE_DIR . 'files/cache/view/',
        ]);


        // TODO move to re-usable twig extension later
        $filter = new \Twig\TwigFilter('truncate', function ($string, $input = 20) {

            if (strlen($string) <= $input) {
                return $string;
            }

            return substr($string, 0, $input) . ' ...';
        });
        $this->twig->addFilter($filter);
    }

    /**
     *
     */
    public function render(string $file, array $payload = null): string
    {
        if ($payload !== null) {
            $payload = array_merge($this->variables, $payload);
        }
        else {
            $payload = $this->variables;
        }

        $file = str_replace(CORE_DIR, '', $file);

        return $this->twig->render($file, $payload);
    }

    /**
     *
     */
    public function addExtension($extension): void
    {
        $this->twig->addExtension($extension);
    }

    /**
     *
     */
    public function addPath(string $path, string $namespace = null)
    {
        if ($namespace === null) {
            $this->loader->addPath($path);
        }
        else {
            $this->loader->addPath($path, $namespace);
        }
    }

    /**
     *
     */
    public function assign(string $varname, $value): void
    {
        $this->variables[$varname] = $value;
    }
}
