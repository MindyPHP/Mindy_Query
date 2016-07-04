<?php

if (is_dir(__DIR__ . '/../vendor')) {
    require __DIR__ . '/../vendor/autoload.php';
}

require __DIR__ . '/TestCase.php';
require __DIR__ . '/DatabaseTestCase.php';

function d()
{
    $debug = debug_backtrace();
    $args = func_get_args();
    $data = [
        'data' => $args,
        'debug' => [
            'file' => isset($debug[0]['file']) ? $debug[0]['file'] : null,
            'line' => isset($debug[0]['line']) ? $debug[0]['line'] : null,
        ]
    ];
    \Mindy\Helper\Dumper::dump($data);
    die();
}
