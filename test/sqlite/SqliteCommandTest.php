<?php

namespace Mindy\Query\Tests;
use Mindy\QueryBuilder\Database\Sqlite\Adapter;

/**
 * @group db
 * @group sqlite
 */
class SqliteCommandTest extends CommandTest
{
    protected $driverName = 'sqlite';

    public function getAdapter()
    {
        return new Adapter();
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return require(__DIR__ . '/config.php');
    }
}
