<?php

use Phalcon\Di\FactoryDefault\Cli as CliDI;
use Phalcon\Cli\Console as ConsoleApp;
use Phalcon\Cli\Dispatcher;
use Phalcon\Db\Adapter\Pdo\Mysql as PdoMysql;
use Phalcon\Logger;
use Phalcon\Logger\Adapter\File as LoggerFile;


error_reporting(E_ALL);

define('BASE_PATH', dirname(__DIR__) .'/../');
define('APP_PATH', BASE_PATH . '/app');

$di = new CliDI();

$config = include APP_PATH ."/config/config.php";

include APP_PATH . "/cli/config/loader.php";

include APP_PATH . "/cli/config/services.php";

$console = new ConsoleApp($di);

$arguments = [];
foreach ($argv as $k => $arg) {
    if ($k == 1) {
        $arguments["task"] = $arg;
    } elseif ($k == 2) {
        $arguments["action"] = $arg;
    } elseif ($k >= 3) {
        $arguments["params"][] = $arg;
    }
}

try {
    $console->handle($arguments);
} catch (\Phalcon\Exception $e) {
    fwrite(STDERR, $e->getMessage() . PHP_EOL);
    exit(1);
}