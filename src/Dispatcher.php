<?php
/**
 *
 */

namespace OmniTools\Core;

class Dispatcher
{
    /**
     *
     */
    private function convertRequestToControllerClass(string $request): array
    {
        if (substr($request, -1) == '/') {
            $request = substr($request, 0, -1);
        }

        $segments = explode('/', $request);

        $extension = array_shift($segments);
        $plugin = array_shift($segments);

        if (count($segments) == 1) {
            $segements[] = 'index';
        }

        $action = array_pop($segments);

        if (ctype_upper($action[0])) {
            $segments[] = $action;
            $action = 'index';
        }

        $controllerClass = '\\OmniTools\\' . $extension . '\\Plugin\\' . $plugin . '\\Controller\\' . implode('\\', $segments) . '\\Controller';

        return [ $controllerClass, $action ];
    }

    /**
     * 
     */
    public function execute(
        \DI\Container $container,
        Session $session,
        \OmniTools\Core\Http\Get $get,
        View $view,
        Config $config,
        View\Front $front
    ): string
    {

        $view->assign('config', $config);

        if (dirname($_SERVER['SCRIPT_NAME']) != '/') {
            $request = str_replace(dirname($_SERVER['SCRIPT_NAME']) . '/', '', $_SERVER['REQUEST_URI']);
            define('SERVER_PATH', '//' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']) . '/');
        }
        else {
            $request = $_SERVER['REQUEST_URI'];
            define('SERVER_PATH', '//' . $_SERVER['HTTP_HOST'] . '/');
        }

        if (!empty($request) and $request[0] == '/') {
            $request = substr($request, 1);
        }

        $request = explode('?', $request)[0];

        if (empty($request)) {
            $request = 'Core/Dashboard/Dashboard/index';
        }

        // Route request to api
        if (substr($request, 0, 4) == 'api/') {

            // Dispatch api call
            $container->call([ \OmniTools\Core\Api\Dispatcher::class, 'execute' ], [
                'request' => $request
            ]);

            exit;
        }

        [ $controllerClass, $controllerAction ] = $this->convertRequestToControllerClass($request);

        if (!class_exists($controllerClass)) {
            throw new \OmniTools\Core\Exception\RuntimeError('ControllerClassMissing: ' . $controllerClass);
        }

        $controller = $container->get($controllerClass);

        if (!$session->isLoggedIn() and !$controller->isActionPublicCallable($controllerAction)) {

            $request = 'Core/Session/Login/index';

            [ $controllerClass, $controllerAction ] = $this->convertRequestToControllerClass($request);

            $controller = $container->get($controllerClass);
        }

        if (!method_exists($controller, $controllerAction . 'Action')) {
            throw new \OmniTools\Core\Exception\RuntimeError('ControllerActionMissing: ' . $controllerAction . 'Action');
        }

        $controller->setAction($controllerAction);

        $translator = new \Symfony\Component\Translation\Translator('de_DE');
        $translator->addLoader('file', new \Symfony\Component\Translation\Loader\PhpFileLoader());
        $translator->addResource('file', CORE_DIR . 'app/vendor/omnitools/core/resources/private/language/de_DE.php', 'de_DE');
        $translator->addResource('file', CORE_DIR . 'app/vendor/omnitools/addresses/resources/private/language/de_DE.php', 'de_DE');
        
        $languageFile = $controller->getExtensionPath() . 'resources/private/language/de_DE.php';

        if (file_exists($languageFile)) {
            $translator->addResource('file', $languageFile, 'de_DE');
        }

        $languageFile = $controller->getPath() . 'resources/private/language/de_DE.php';

        if (file_exists($languageFile)) {
            $translator->addResource('file', $languageFile, 'de_DE');
        }

        $view->addExtension(new \Symfony\Bridge\Twig\Extension\TranslationExtension($translator));

        // Assign data to view
        $view->assign('front', $front);
        $view->assign('get', $get);
        $view->assign('controller', $controller);
        $view->assign('serverpath', SERVER_PATH);

        if (file_exists($viewsDirectory = $controller->getPath() . 'resources/private/views/')) {
            $view->addPath($viewsDirectory);
        }

        try {
            // Perform controller action
            $response = $container->call([ $controller, $controllerAction . 'Action' ]);
        }
        catch ( \OmniTools\Core\Exception\InvalidContext $e ) {

            d($controllerAction);
            d($e->getMessage());
        }


        if (!$response instanceof \OmniTools\Core\View\Response) {
            throw new \OmniTools\Core\Exception\RuntimeError('Received unexpected controller response.');
        }

        if ($response instanceof \OmniTools\Core\View\ResponseRedirect) {

            $url = dirname($_SERVER['SCRIPT_NAME']) != '/' ? dirname($_SERVER['SCRIPT_NAME'])  . '/' . $response->getUri() : '/' . $response->getUri();

            http_response_code(303);

            header('Location: ' . $url);
            exit;
        }
        elseif ($response instanceof \OmniTools\Core\View\ResponseJson) {

            http_response_code(200);
            header('Content-Type: application/json');

            die(json_encode($response->getData()));
        }

        // Render controller html
        $controllerhtml = $view->render($response->getFile(), $response->getData());
        $view->assign('controllerHtml', $controllerhtml);

        // Render menu
        $menuHtml = $container->call([ $controller, 'renderMenu' ]);
        $view->assign('menuHtml', $menuHtml);

        // Render base layout html
        $html = $view->render($front->getLayout() . '.html.twig');

        http_response_code(200);

        header('Content-Type: text/html; charset=UTF-8');
        header("Content-Length: " . strlen($html) ."; ");

        die($html);
    }
}
