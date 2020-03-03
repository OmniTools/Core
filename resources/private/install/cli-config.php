<?php
/**
 *
 */

use Doctrine\ORM\Tools\Console\ConsoleRunner;

require 'vendor/autoload.php';

$localconfig = require('../config/localconfig.php');

$paths = [
    __DIR__ . '/vendor/omnitools/core/src/Persistence/Entity',
    __DIR__ . '/vendor/omnitools/apartments-rental/src/Persistence/Entity',
    __DIR__ . '/vendor/omnitools/addresses/src/Persistence/Entity'
];

$config = \Doctrine\ORM\Tools\Setup::createAnnotationMetadataConfiguration($paths);

// php vendor/bin/doctrine orm:generate-proxies
$config->setProxyDir(__DIR__ . '/../files/cache/proxies/');

# set up configuration parameters for doctrine.
# Make sure you have installed the php7.0-sqlite package.
$connectionParams = array(
    'dbname' => $localconfig['database']['dbname'],
    'user' => $localconfig['database']['user'],
    'password' => $localconfig['database']['password'],
    'host' => 'localhost',
    'driver' => 'pdo_mysql',
    'charset' => 'utf8'
);

$entityManager = \Doctrine\ORM\EntityManager::create($connectionParams, $config);

return ConsoleRunner::createHelperSet($entityManager);
