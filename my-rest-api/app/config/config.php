<?php

use Phalcon\Config;

return new Config(
    [
        "database" => [
            "adapter"  => "Mysql",
            "host"     => "localhost",
            "username" => "root",
            "password" => "P@ssword#123",
            "dbname"   => "objects",
        ],
        "application" => [
            "controllersDir" => APP_PATH . "/controllers/",
            "modelsDir"      => APP_PATH . "/models/",
            "viewsDir"       => APP_PATH . "/views/",
            "tasksDir"       => APP_PATH . "/tasks/",
            "formsDir"       => APP_PATH . "/forms/",
            "commonDir"      => APP_PATH . "/common/",
            "logsDir"        => BASE_PATH . "/logs/",
            "baseUri"        => "/",
        ],
        "oauthGithub" => [
            'clientId'          => 'ce9d00f0a3e2ef83f3f4',
            'clientSecret'      => '795d9e8bc6f3ef2541093eb2603d41c7da25d20f',
        ],
    ]
);
