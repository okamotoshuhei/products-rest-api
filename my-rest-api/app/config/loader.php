<?php

use Phalcon\Loader;

$loader = new Loader();

$loader->registerNamespaces(
    [
        'Modules\Controllers'         => $config->application->controllersDir,
        'Modules\Models\Entities'     => $config->application->modelsDir . 'entities/',
        'Modules\Models\Services'     => $config->application->modelsDir . 'services/',
        'Modules\Common\Json'         => $config->application->commonDir . 'json/',
        'Modules\Forms'               => $config->application->formsDir,
    ]
);

$loader->register();


require_once BASE_PATH . '/vendor/autoload.php';
