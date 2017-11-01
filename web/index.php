<?php

use Ecomm\Main;

\ini_set('display_errors', 1);
\ini_set('log_errors', 1);
\ini_set('display_startup_errors', 1);
\date_default_timezone_set("Europe/Sofia");
\error_reporting(-1);

require_once __DIR__ . '/../src/Main.php';

$main = new Main(__DIR__ . "/../config/dev.json");


$app = $main->getSlim();
$app->run();
