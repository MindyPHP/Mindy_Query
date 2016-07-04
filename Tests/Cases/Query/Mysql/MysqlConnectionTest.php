<?php

/**
 * @group db
 * @group pgsql
 */
class MysqlConnectionTest extends ConnectionTest
{
    protected $driverName = 'mysql';

    public function testConnection()
    {
        $this->getConnection(true);
    }
}
