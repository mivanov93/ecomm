<?php

namespace Ecomm;

use Ecomm\Auth\Acl;
use Ecomm\Auth\AuthAdapter;
use Ecomm\Utils\JsonCfg;
use JeremyKendall\Slim\Auth\Bootstrap as AuthBoostrap;
use Slim\Slim;

require_once __DIR__ . '/../vendor/autoload.php';

class Main {

    private $slim;

    public function __construct($jsonCfgPath) {

        $jsonCfg = new JsonCfg($jsonCfgPath);
        $this->slim = new Slim($jsonCfg->getAsArray());
        $adapter = new AuthAdapter();
        $acl = new Acl();

        $authBootstrap = new AuthBoostrap($this->slim, $adapter, $acl);
        $authBootstrap->bootstrap();
        $this->setRoutes();
    }

    private function setRoutes() {
        $app = $this->slim;
        
        $app->notFound(function() use ($app) {
            echo "Current route is " . $app->request()->getPathInfo();
        });
        
        $app->map('/login', function () use ($app) {
            $username = $app->request->post('username');
            $password = $app->request->post('password');

            $result = $app->authenticator->authenticate($username, $password);

            if ($result->isValid()) {
                $app->redirect('/myposts');
            } else {
                $app->redirect('/unauthorized');
            }
        })->via('POST')->name('login');

        $app->map('/myposts', function() use($app) {
            $app->response()->body("\n all posts \n");
            var_dump($_COOKIE);
        })->via('GET','POST');
        $app->map('/unauthorized', function () use ($app) {
            $app->response()->setStatus(401);
            $app->response()->body("\n unauthorized \n");
        })->via('GET', 'POST');
    }

    public function getSlim() {
        return $this->slim;
    }

}

//$dbCfg = json_decode(file_get_contents(__DIR__ . '/../config/db.json'));
//$app = new \Slim\Slim(
//        array(
//    'version' => $composer->version,
//    'debug' => false,
//    'templates.path' => __DIR__ . '/../app/templates'
//        )
//);
