<?php
/**
 *
 */

namespace OmniTools\Core\Api;

use DI\Container;

class Dispatcher
{
    /**
     *
     */
    public function execute(
        string $request,
        \DI\Container $container,
        \OmniTools\Core\Config $config,
        \Doctrine\ORM\EntityManagerInterface $entityManager,
        \OmniTools\Core\Session $session
    )
    {
        try {
            // Start database transaction
            $entityManager->getConnection()->beginTransaction();

            $authed = false;

            if (!empty($authToken = $_SERVER['HTTP_X_AUTH_TOKEN'])) {

                $token = $config->get('api.auth.supertoken');

                if ($token != $authToken) {
                    throw new Exception\NotAuthorized('Auth token is invalid.');
                }

                $authed = true;
            }

            if (!$authed) {
                throw new Exception\NotAuthorized('Authentication missing.');
            }

            // Determine api user
            $userRepository = $entityManager->getRepository(\OmniTools\Core\Persistence\Entity\User::class);

            $sql = 'SELECT * FROM `user` WHERE JSON_CONTAINS(roles, \'[ "API" ]\') LIMIT 1';

            $stmt = $entityManager->getConnection()->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll();

            if (!count($result)) {

                $user = new \OmniTools\Core\Persistence\Entity\User([
                    'email' => 'autocreated@example.org',
                    'roles' => [ 'API' ]
                ]);

                $entityManager->persist($user);
                $entityManager->flush();
            }
            else {
                $user = new \OmniTools\Core\Persistence\Entity\User($result[0]);
            }

            $user = $userRepository->find($user->getId());
            $session->login($user);

            // Analyze request
            $request = substr($request, 4);

            $segments = explode('/', $request);
            $extension = array_shift($segments);
            $plugin = array_shift($segments);
            $action = array_pop($segments);

            // Init api controller
            $apiControllerClass = '\OmniTools\\' . $extension . '\Plugin\\' . $plugin . '\Api';

            if (!empty($segments)) {
                $apiControllerClass .= '\\' . implode('\\', $segments);
            }

            $apiControllerClass .= '\Controller';

            if (!class_exists($apiControllerClass) ) {
                throw new \Exception('Api controller missing.');
            }

            $apiController = $container->get($apiControllerClass);

            // Parse payload
            $payloadRaw = file_get_contents('php://input');
            $payloadData = json_decode($payloadRaw, true);

            if (!empty($payloadRaw) and $payloadData === null) {
                switch(json_last_error()) {
                    case JSON_ERROR_SYNTAX:
                        throw new \Exception('Payload JSON syntax error.');
                }
            }

            if (!empty($payloadData)) {
                $apiController->setPayload($payloadData);
                $apiController->setPayloadRaw($payloadRaw);
            }


/*
            $translator = new \Symfony\Component\Translation\Translator('de_DE');
            $translator->addLoader('file', new \Symfony\Component\Translation\Loader\PhpFileLoader());
            $translator->addResource('file', CORE_DIR . 'app/vendor/omnitools/core/resources/private/language/de_DE.php', 'de_DE');
            $translator->addResource('file', CORE_DIR . 'app/vendor/omnitools/addresses/resources/private/language/de_DE.php', 'de_DE');

            // $languageFile = $controller->getExtensionPath() . 'resources/private/language/de_DE.php';

            if (file_exists($languageFile)) {
                $translator->addResource('file', $languageFile, 'de_DE');
            }

            $languageFile = $controller->getPath() . 'resources/private/language/de_DE.php';

            if (file_exists($languageFile)) {
                $translator->addResource('file', $languageFile, 'de_DE');
            }

            $view->addExtension(new \Symfony\Bridge\Twig\Extension\TranslationExtension($translator));
*/


            // Compose controller action
            $action = $action . 'ActionUsing' . ucfirst(strtolower($_SERVER['REQUEST_METHOD']));

            if (!method_exists($apiController, $action)) {
                throw new \Exception(sprintf('Api controller action %s not callable.', $action));
            }

            $response = $container->call([ $apiControllerClass, $action ]);

            // Commit database transaction
            $entityManager->getConnection()->commit();

            http_response_code(200);
            header('Content-Type: application/json');

            if (is_array($response)) {
                die(json_encode($response));
            }

            d($response);
        }
        catch (Exception\NotAuthorized $e) {

            // Kill database transaction
            $entityManager->getConnection()->rollBack();

            http_response_code(401);
            die(json_encode([
                'error' => $e->getMessage()
            ]));
        }
        catch (\Exception $e) {

            // Kill database transaction
            $entityManager->getConnection()->rollBack();

            http_response_code(500);
            die(json_encode([
                'error' => $e->getMessage()
            ]));
        }
    }
}
