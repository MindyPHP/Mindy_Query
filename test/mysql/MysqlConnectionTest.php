<?php

namespace Mindy\Query\Tests;

/**
 * @group db
 * @group pgsql
 */
class MysqlConnectionTest extends ConnectionTest
{
    protected $driverName = 'mysql';

    public function testConnection()
    {
        $this->getDb(true);
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return require(__DIR__ . '/config.php');
    }
}
