<?php

return [
    'cubrid' => [
        'class' => '\Mindy\Query\Connection',
        'dsn' => 'cubrid:dbname=demodb;host=localhost;port=33000',
        'username' => 'dba',
        'password' => '',
        'fixture' => __DIR__ . '/data/cubrid.sql',
    ],
    'mysql' => [
        'class' => '\Mindy\Query\Connection',
        'dsn' => 'mysql:host=127.0.0.1;dbname=test',
        'username' => 'root',
        'password' => '',
        'fixture' => __DIR__ . '/data/mysql.sql',
    ],
    'sqlite' => [
        'class' => '\Mindy\Query\Connection',
        'dsn' => 'sqlite::memory:',
        'fixture' => __DIR__ . '/data/sqlite.sql',
    ],
    'sqlsrv' => [
        'class' => '\Mindy\Query\Connection',
        'dsn' => 'sqlsrv:Server=localhost;Database=test',
        'username' => '',
        'password' => '',
        'fixture' => __DIR__ . '/data/mssql.sql',
    ],
    'pgsql' => [
        'class' => '\Mindy\Query\Connection',
        'dsn' => 'pgsql:host=localhost;dbname=test;port=5432;',
        'username' => 'root',
        'password' => '',
        'fixture' => __DIR__ . '/data/postgres.sql',
    ],
];
