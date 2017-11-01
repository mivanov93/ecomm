<?php

// doctrine.php


use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\Version;
use Ecomm\Commands\WebServer;
use Ecomm\Utils\JsonCfg;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Helper\HelperSet;

require __DIR__ . '/../vendor/autoload.php';

$jsonCfg = new JsonCfg(__DIR__ . "/../config/dev.json");
$cfg = $jsonCfg->getAsArray()['doctrine'];

$path = [realpath(__DIR__ . '/../src/Ecomm/Entity')];

$devMode = true;

$config = Setup::createAnnotationMetadataConfiguration($path, $devMode, null, null, false);
//AnnotationRegistry::registerLoader(
//        function ($class) {
//    $file = str_replace("\\", DIRECTORY_SEPARATOR, $class) . ".php";
//    $file = str_replace('Symfony/Component/Validator', '', $file);
//    $file = str_replace('Symfony\Component\Validator', '', $file);
//    $fileToInclude = __DIR__ . '/../vendor/symfony/validator/' . $file;
//
//    if (file_exists($fileToInclude)) {
//        // file exists makes sure that the loader fails silently
//        require_once $fileToInclude;
//
//        return true;
//    }
//
//    $fileToInclude = __DIR__ . '/../vendor/symfony/validator/Constraints/' . $file;
//    if (file_exists($fileToInclude)) {
//        // file exists makes sure that the loader fails silently
//        require_once $fileToInclude;
//
//        return true;
//    }
//}
//);
//
//AnnotationRegistry::registerFile(
//        __DIR__ . "/../vendor/symfony/doctrine-bridge/Validator/Constraints/UniqueEntity.php"
//);
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
$webDev = new WebServer('webserver:run', __DIR__ . "/../web", __DIR__ . "/../web/index.php", 8080);
$cli->add($webDev);
// Runs console application
$cli->run();


