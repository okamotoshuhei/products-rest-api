<?php

use Phalcon\Mvc\View;
use Phalcon\Db\Adapter\Pdo\Mysql as PdoMysql;
use League\OAuth2\Client\Provider\Github as OAuthGithub;
use Phalcon\Session\Adapter\Files;
use Phalcon\Logger;
use Phalcon\Logger\Adapter\File as LoggerFile;
use Phalcon\Security;
use Phalcon\Events\Manager;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Di;


$di->set(
    'dispatcher',
    function() {
        $eventsManager = new Manager();
        $eventsManager->attach('dispatch:beforeException', function($event, $dispatcher, $exception) {

            $dispatcher->forward(
                [
                    'controller' => 'error',
                    'action'     => 'show500',
                ]
            );
            return false;
        });

        $logger = Di::get('logger');
        if($exception){
            $logger->error("Exception : " . $exception);
        } else {
            $logger->warning("Exception has occurred!");
        }

        $dispatcher = new Dispatcher();
        $dispatcher->setEventsManager($eventsManager);
        return $dispatcher;
    }, true
);

$di->set(
    "view",
    function () use ($config) {
        $view = new View();
        $view->setViewsDir($config->application->viewsDir);
        return $view;
    }
);

$di->set(
    "db",
    function () use ($config) {
        return new PdoMysql(
            [
                "host"     => $config->database->host,
                "username" => $config->database->username,
                "password" => $config->database->password,
                "dbname"   => $config->database->dbname,
                'charset'  => 'utf8mb4',
            ]
        );
    }
);

$di->set(
    'session',
    function(){
        $session = new Files();
        $session->start();
        return $session;
    }
);

$di->setShared(
    'oauthProviderGithub',
    function() use ($config){
        return new OAuthGithub(
            [
                'clientId'          => $config->oauthGithub->clientId,
                'clientSecret'      => $config->oauthGithub->clientSecret,
            ]
        );
    }
);

$di->set(
    'logger',
    function () use ($config) {
        return new LoggerFile($config->application->logsDir . "app.log");
    }
);

$di->set(
    'security',
    function () {
        $security = new Security();
        $security->setWorkFactor(12);
        return $security;
    }
);