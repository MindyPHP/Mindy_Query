<?php

use Mindy\Helper\Alias;
use Mindy\Helper\Creator;
use Mindy\Query\Connection;
use Mindy\Query\Transaction;

/**
 * @group db
 * @group sqlite
 */
class SqliteConnectionTest extends ConnectionTest
{
    protected $driverName = 'sqlite';

    public function testConstruct()
    {
        $connection = $this->getConnection(false);
        $params = $this->config[$this->driverName];
        $this->assertEquals($params['dsn'], $connection->dsn);
    }

    public function testTransactionIsolation()
    {
        $connection = $this->getConnection(true);
        $transaction = $connection->beginTransaction(Transaction::READ_UNCOMMITTED);
        $transaction->rollBack();
        $transaction = $connection->beginTransaction(Transaction::SERIALIZABLE);
        $transaction->rollBack();
    }
}
