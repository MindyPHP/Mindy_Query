<?php

if(is_dir(__DIR__ . '/../vendor')) {
    require __DIR__ . '/../vendor/autoload.php';
}

require __DIR__ . '/../src.php';
require __DIR__ . '/TestCase.php';
require __DIR__ . '/DatabaseTestCase.php';

require __DIR__ . '/Cases/Query/QueryBuilderTest.php';
require __DIR__ . '/Cases/Query/CommandTest.php';
require __DIR__ . '/Cases/Query/ConnectionTest.php';
require __DIR__ . '/Cases/Query/QueryTest.php';
require __DIR__ . '/Cases/Query/SchemaTest.php';
