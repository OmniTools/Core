<?php
/**
 *
 */

declare(strict_types=1);

namespace OmniTools;

try {

    require '../vendor/autoload.php';

    define('CORE_DIR', realpath(__DIR__ . '/../..') . '/');

    http_response_code(500);
    date_default_timezone_set('Europe/Berlin');

    // Build dependency injection container
    $builder = new \DI\ContainerBuilder();
    $builder->useAnnotations(true);
    $builder->addDefinitions(CORE_DIR . 'config/application.php');

    $container = $builder->build();

    $dispatcher = $container->get(\OmniTools\Core\Dispatcher::class);
    $container->call([ $dispatcher, 'execute']);
}
catch ( \Exception $exception ) {

    if ($exception instanceof \OmniTools\Exception\AbstractException) {
        $message = get_class($exception) . '\\' . $exception->getMessage() . ': ' . $exception->getInfo();
    }
    else {
        $message = $exception->getMessage();
    }

    if (strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false) {

        $message = !empty($exception->getMessage()) ? $exception->getMessage() : get_class($exception);

        die($message);
    }


    p($message . ' on line ' . $exception->getLine() . ' in file ' . $exception->getFile());

    d($exception->getMessage());
}