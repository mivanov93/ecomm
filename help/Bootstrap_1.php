<?php

namespace Ecomm;

if (isset($_SERVER['HTTP_CLIENT_IP']) ||
        isset($_SERVER['HTTP_X_FORWARDED_FOR']) ||
        !(in_array(@$_SERVER['REMOTE_ADDR'], ['127.0.0.1', 'fe80::1', '::1']) || php_sapi_name() === 'cli-server')
) {
    \header('HTTP/1.0 403 Forbidden');
    exit('You are not allowed to access this file. Check ' . basename(__FILE__) . ' for more information.');
}

$debug = true;

//\ini_set('error_log', __DIR__ . "/../logs/development.log");
\ini_set('display_errors', $debug);
\ini_set('log_errors', 1);
\ini_set('display_startup_errors', $debug);
\date_default_timezone_set("Europe/Sofia");
\error_reporting(-1);

$errorHandler = function ($errno, $message, $file, $line) {
    if (\error_reporting() === 0) {
        return;
    }
    throw new ErrorException($message, $errno, 1, $file, $line);
};
$shutdownHandler = function() use(&$debug) {
    $error = \error_get_last();

    if ($error === NULL) {
        return;
    }
    \http_response_code(500);
    header('Content-Type: application/json');
    $errId = \rand();
    \error_log("#{$errId} Shutdown eror: {$error['message']} "
            . "of type: {$error["type"]} in {$error["file"]} on line #{$error["line"]}");

    $jsonResp = ['errCategory' => 'Fatal', 'errType' => $error["type"],
        'errId' => $errId];
    if ($debug === true) {
        $jsonResp['errFile'] = $error["file"];
        $jsonResp['errLine'] = $error["line"];
        $jsonResp['errMsg'] = $error['message'];
    }
    echo \json_encode($jsonResp);
};
$exceptionHandler = function (Exception $ex) use(&$debug) {
    \http_response_code(500);
    \header('Content-Type: application/json');
    $errId = \rand();
    $errMsg = "Exception #{$errId} Msg: {$ex->getMessage()} Trace: {$ex->getTraceAsString()}";
    \error_log($errMsg);
    $jsonResp = ['errCategory' => 'Exception', 'errType' =>
        \basename(\str_replace('\\', '/', \get_class($ex))),
        'errId' => $errId];
    if ($debug === true) {
        $jsonResp['errCode'] = $ex->getCode();
        $jsonResp['errFile'] = $ex->getFile();
        $jsonResp['errLine'] = $ex->getLine();
        $jsonResp['errMsg'] = $ex->getMessage();
        $jsonResp['errTrace'] = $ex->getTraceAsString();
    }
    echo \json_encode($jsonResp);
};

\register_shutdown_function($shutdownHandler);
\set_error_handler($errorHandler);
\set_exception_handler($exceptionHandler);
//\ini_set('display_errors', );
//\ini_set('display_startup_errors', 0);


require __DIR__ . '/../../vendor/autoload.php';
