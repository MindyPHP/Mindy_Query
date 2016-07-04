<?php
use Mindy\Helper\Creator;
use Mindy\Query\Connection;

/**
 * Created by PhpStorm.
 * User: max
 * Date: 30/06/16
 * Time: 10:37
 */

class ConnectionManagerTest extends \PHPUnit_Framework_TestCase
{
    public function testCreator()
    {
        $configs = [
            [
                'class' => \Mindy\Query\ConnectionManager::class,
                ['default' => ['class' => Connection::class]]
            ],
            [
                'class' => \Mindy\Query\ConnectionManager::class,
                'databases' => ['default' => ['class' => Connection::class]]
            ],
            [
                'class' => \Mindy\Query\ConnectionManager::class,
                'connections' => ['default' => Connection::class]
            ]
        ];
        foreach ($configs as $config) {
            $this->assertTrue(Creator::createObject($config)->hasConnection('default'));
        }
    }

    public function testInit()
    {
        $connections = [
            'default' => [
                'class' => Connection::class
            ]
        ];

        $cm = new \Mindy\Query\ConnectionManager(['connections' => $connections]);
        $this->assertNotNull($cm->getConnection('default'));
        $this->assertTrue($cm->getConnection('default') instanceof Connection);
        $this->assertTrue($cm->getConnection() instanceof Connection);
        $connection = $cm->getConnection();
        $this->assertTrue($cm->getConnection($connection) instanceof Connection);
    }

    public function testMissing()
    {
        $connections = [
            'sqlite' => [
                'class' => Connection::class
            ]
        ];
        $cm = new \Mindy\Query\ConnectionManager(['connections' => $connections]);
        $this->assertFalse($cm->hasConnection('default'));
    }
}