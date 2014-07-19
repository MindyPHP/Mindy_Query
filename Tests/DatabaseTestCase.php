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

use Mindy\Query\Connection;
use Mindy\Query\ConnectionManager;

class DatabaseTestCase extends TestCase
{
    protected $database;
    protected $driverName = 'mysql';

    /**
     * @var Connection
     */
    protected $db;

    public function setUp()
    {
        parent::setUp();

        if (is_file(__DIR__ . '/config_local.php')) {
            $databases = include(__DIR__ . '/config_local.php');
        } else {
            $databases = include(__DIR__ . '/config.php');
        }
        $manager = new ConnectionManager([
            'databases' => $databases
        ]);
        $this->database = $databases[$this->driverName];
        $pdo_database = 'pdo_' . $this->driverName;

        if (!extension_loaded('pdo') || !extension_loaded($pdo_database)) {
            $this->markTestSkipped('pdo and ' . $pdo_database . ' extension are required.');
        }
    }

    protected function tearDown()
    {
        if ($this->db) {
            $this->db->close();
        }
    }

    /**
     * @param bool $reset whether to clean up the test database
     * @param bool $open whether to open and populate test database
     * @return \Mindy\Query\Connection
     */
    public function getConnection($reset = true, $open = true)
    {
        if (!$reset && $this->db) {
            return $this->db;
        }
        $db = new Connection;
        $db->dsn = $this->database['dsn'];
        if (isset($this->database['username'])) {
            $db->username = $this->database['username'];
            $db->password = $this->database['password'];
        }
        if (isset($this->database['attributes'])) {
            $db->attributes = $this->database['attributes'];
        }
        if ($open) {
            $db->open();
            $lines = explode(';', file_get_contents($this->database['fixture']));
            foreach ($lines as $line) {
                if (trim($line) !== '') {
                    $db->pdo->exec($line);
                }
            }
        }
        $this->db = $db;
        return $db;
    }
}
