<?php

use JeremyKendall\Password\PasswordValidator;
use JeremyKendall\Slim\Auth\Adapter\Db\PdoAdapter;
use JeremyKendall\Slim\Auth\Bootstrap;
use Slim\Slim;

require_once __DIR__ . "/../src/Ecomm/Bootstrap.php";
$app = new Slim();
$db = new PDO();
$adapter = new PdoAdapter(
        $db, $dbname, $user_col, $pass_col, new PasswordValidator()
);
$authBootstrap = new Bootstrap($app, $adapter, $acl);
$authBootstrap->bootstrap();

$app->notFound(function () use ($app) {
    /* @var $resp \Slim\Http\Response */
    /* @var $app Slim */
    $resp = $app->response();
    $resp->body(json_encode('not found'));
});

$app->get('/', function ($name) use ($app) {
    echo 123;
    /* @var $resp \Slim\Http\Response */
    /* @var $app Slim */
    $resp = $app->response();
    $resp->body('he24llo ' . $name);
});
$app->get('/hello/:name', function ($name) use ($app) {
    /* @var $resp \Slim\Http\Response */
    /* @var $app Slim */
    $resp = $app->response();
    $resp->body('he24llo ' . $name);
});
$app->run();
