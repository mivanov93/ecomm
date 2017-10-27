<?php

use Slim\Slim;

error_reporting(\E_ALL);
\ini_set('display_errors', 1);


require __DIR__.'/../vendor/autoload.php';

$app = new Slim();
$app->get('/hello/:name', function ($name) {
    echo "Hello, " . $name;
    new Test();
});
$app->run();
