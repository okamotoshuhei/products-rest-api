<?php

use Phalcon\Db\Adapter\Pdo\Mysql as PdoMysql;
use Phalcon\Logger;
use Phalcon\Logger\Adapter\File as LoggerFile;

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
    'logger',
    function () use ($config) {
        return new LoggerFile($config->application->logsDir . "app.log");
    }
);