<?php

// doctrine.php


use Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\Version;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Helper\HelperSet;

require __DIR__ . '/../vendor/autoload.php';

$path = [__DIR__.'/../src/Entities'];
$devMode = true;

$config = Setup::createAnnotationMetadataConfiguration($path, $devMode);

$connectionOptions = array(
    'driver' => 'pdo_mysql',
    'host' => '',
    'dbname' => '',
    'user' => '',
    'password' => '',
);

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

// Runs console application
$cli->run();


