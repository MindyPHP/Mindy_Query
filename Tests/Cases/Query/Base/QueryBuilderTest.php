<?php

/**
 * Created by PhpStorm.
 * User: max
 * Date: 30/06/16
 * Time: 15:47
 */
abstract class QueryBuilderTest extends DatabaseTestCase
{
    public function testAdapter()
    {
        $adapter = $this->getAdapter();
        $connection = $this->getConnection();
        $this->assertTrue($connection->getAdapter() instanceof $adapter);
        $this->assertTrue($connection->getQueryBuilder()->getAdapter() instanceof $adapter);
        $this->assertTrue($connection->getSchema()->getAdapter() instanceof $adapter);
    }

    public function testQueryBuilder()
    {
        $qb = $this->getQueryBuilder();
        $connection = $this->getConnection();
        $this->assertTrue($connection->getQueryBuilder() instanceof $qb);
    }

    public function testLookupBuilder()
    {

    }

    public function testLookupCollection()
    {

    }
}