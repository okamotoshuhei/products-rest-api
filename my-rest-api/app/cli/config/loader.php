<?php

use Phalcon\Loader;

$loader = new Loader();

$loader->registerNamespaces(
    [
        'Modules\Models\Entities'     => $config->application->modelsDir . 'entities/',
        'Modules\Models\Services'     => $config->application->modelsDir . 'services/',
    ]
);

// Taskディレクトリについて名前空間での読み込みができなかった為、ディレクトリを登録
$loader->registerDirs(
    [
        $config->application->tasksDir,
    ]
);

$loader->register();
