<?php
require_once __DIR__ . '/../app/app.php';

echo $app->getMode();
$app->run();