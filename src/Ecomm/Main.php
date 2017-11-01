<?php

namespace Ecomm;

use Doctrine\ORM\EntityManager;
use Ecomm\Auth\Acl;
use Ecomm\Auth\AuthAdapter;
use Ecomm\Entity\User;
use Ecomm\Services\DoctrineService;
use Ecomm\Utils\JsonCfg;
use JeremyKendall\Slim\Auth\Bootstrap as AuthBoostrap;
use Slim\Slim;
use Zend\Authentication\AuthenticationService;

require_once __DIR__ . '/../../vendor/autoload.php';

class Main {

    private $slim;

    public function __construct($jsonCfgPath) {

        $jsonCfg = new JsonCfg($jsonCfgPath);
        $this->slim = new Slim($jsonCfg->getAsArray());
        $docSrv = new DoctrineService($jsonCfg->getAsArray()['doctrine']);
        $docSrv->setup();
        /* @var $em EntityManager */
        $em = $docSrv->getEm();
        $em->getRepository(User::class);
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
                $app->response()->setBody('logged in');
            } else {
                $app->redirect('/unauthorized');
            }
        })->via('POST')->name('login');
        $app->get('/logout', function () use ($app) {
            $app->authenticator->logout();
            $app->redirect('/posts');
        });
        $app->map('/myposts', function() use($app) {
            /* @var $auth AuthenticationService */
            $auth = $app->auth->getIdentity();
            var_dump($auth);
            $app->response()->body("\n all posts \n");
        })->via('GET');
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
