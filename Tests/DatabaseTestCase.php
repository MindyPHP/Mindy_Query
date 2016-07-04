<?php
/**
 * All rights reserved.
 *
 * @author Falaleev Maxim
 * @email max@studio107.ru
 * @version 1.0
 * @company Studio107
 * @site http://studio107.ru
 * @date 06/01/14.01.2014 17:18
 */

use Mindy\Helper\Creator;
use Mindy\Query\Connection;
use Mindy\Query\ConnectionManager;
use Mindy\QueryBuilder\LegacyLookupBuilder;
use Mindy\QueryBuilder\LookupBuilder\Legacy;
use Mindy\QueryBuilder\QueryBuilder;

class DatabaseTestCase extends TestCase
{
    /**
     * @var ConnectionManager
     */
    protected $cm;

    protected $driverName = 'mysql';

    protected $config = [];

    private static $params = [];

    public function setUp()
    {
        parent::setUp();

        if (is_file(__DIR__ . '/config_local.php')) {
            $this->config = include(__DIR__ . '/config_local.php');
        } else {
            $this->config = include(__DIR__ . '/config.php');
        }

        $this->cm = new ConnectionManager($this->config);
        $pdo_database = 'pdo_' . $this->driverName;

        if (!extension_loaded('pdo') || !extension_loaded($pdo_database)) {
            $this->markTestSkipped('pdo and ' . $pdo_database . ' extension are required.');
        }
    }

    protected function tearDown()
    {
        $this->cm->getConnection($this->driverName)->close();
    }

    /**
     * @param bool $reset whether to clean up the test database
     * @param bool $open whether to open and populate test database
     * @return \Mindy\Query\Connection
     */
    public function getConnection($reset = true, $open = true)
    {
        if (!$reset) {
            return $this->cm->getConnection($this->driverName);
        }
        return $this->prepareDatabase($this->config[$this->driverName]['fixture'], $open);
    }

    public function prepareDatabase($fixture, $open = true)
    {
        $db = $this->cm->getConnection($this->driverName);
        if (!$open) {
            return $db;
        }
        $db->open();
        if ($fixture !== null) {
            $lines = explode(';', file_get_contents($fixture));
            foreach ($lines as $line) {
                if (trim($line) !== '') {
                    if ($db->pdo->exec($line) === false) {
                        var_dump($db->pdo->errorInfo());
                        die(1);
                    }
                }
            }
        }
        return $db;
    }

    /**
     * Returns a test configuration param from /data/config.php
     * @param  string $name params name
     * @param  mixed $default default value to use when param is not set.
     * @return mixed  the value of the configuration param
     */
    public static function getParam($name, $default = null)
    {
        if (static::$params === null) {
            static::$params = require(__DIR__ . '/config.php');
        }
        return isset(static::$params[$name]) ? static::$params[$name] : $default;
    }

    protected function getAdapter()
    {
        switch ($this->driverName) {
            case "sqlite":
                return new \Mindy\QueryBuilder\Sqlite\Adapter;
            case "pgsql":
                return new \Mindy\QueryBuilder\Pgsql\Adapter;
            case "mysql":
                return new \Mindy\QueryBuilder\Mysql\Adapter;
        }

        throw new Exception('Unknown driver');
    }

    protected function getSchema()
    {
        $connection = $this->getConnection();
        if (isset($connection->schemaMap[$this->driverName])) {
            $schemaClass = $connection->schemaMap[$this->driverName];
            return new $schemaClass($connection);
        }
        throw new Exception('Unknown driver');
    }

    protected function getQueryBuilder()
    {
        $adapter = $this->getAdapter();
        $lookupBuilder = new Legacy;
        $schema = $this->getSchema();
        return new QueryBuilder($adapter, $lookupBuilder, $schema);
    }
}
