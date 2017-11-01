<?php

// doctrine.php


use Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\Version;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;

require __DIR__ . '/../vendor/autoload.php';

$jsonCfg = new Ecomm\Utils\JsonCfg(__DIR__ . "/../config/dev.json");
$cfg = $jsonCfg->getAsArray()['doctrine'];

$path = [realpath(__DIR__ . '/../src/Ecomm/Entity')];

$devMode = true;

$config = Setup::createAnnotationMetadataConfiguration($path, $devMode, null, null, false);

$connectionOptions = $cfg['connection'];

$em = EntityManager::create($connectionOptions, $config);

$helpers = new HelperSet(array(
    'db' => new ConnectionHelper($em->getConnection()),
    'em' => new EntityManagerHelper($em)
        ));


// replace the ConsoleRunner::run() statement with:
$cli = new Application('Doctrine Command Line Interface', Version::VERSION);
$cli->setCatchExceptions(true);
$cli->setHelperSet($helpers);

// Register All Doctrine Commands
ConsoleRunner::addCommands($cli);
$webDev = new \Ecomm\Commands\WebServer('webserver:run', __DIR__ . "/../web", __DIR__ . "/../web/index.php", 8080);
$cli->add($webDev);
// Runs console application
$cli->run();


