<?php
namespace Ecomm\App;

require_once __DIR__ . '/../vendor/autoload.php';

$dbCfg = json_decode(file_get_contents(__DIR__ . '/../config/db.json'));
$app = new \Slim\Slim(
        array(
    'version' => $composer->version,
    'debug' => false,
    'templates.path' => __DIR__ . '/../app/templates'
        )
);
