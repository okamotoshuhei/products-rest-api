<?php

use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Application;

error_reporting(E_ALL);

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

try {

    $di = new FactoryDefault();

    $config = include APP_PATH ."/config/config.php";

    include APP_PATH . '/config/router.php';

    include APP_PATH . "/config/loader.php";

    include APP_PATH . "/config/services.php";

    $application = new Application($di);
    
    $response = $application->handle();
    $response->send();

} catch (Phalcon\Exception $e) {
    echo 'Exception: ', $e->getMessage();
} catch (PDOException $e) {
    echo $e->getMessage();
}