<?php

namespace Mindy\Query;

use Mindy\Helper\Creator;
use Mindy\Query\Exception\Exception;

/**
 * Class ConnectionManager
 * @package Mindy\Query
 */
class ConnectionManager
{
    const DEFAULT_CONNECTION_NAME = 'default';
    /**
     * @var Connection[]
     */
    private $_databases = [];

    /**
     * ConnectionManager constructor.
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        foreach ($options as $key => $value) {
            if (in_array($key, ['databases']) && is_array($value)) {
                foreach ($value as $name => $config) {
                    $this->_databases[$name] = $config instanceof Connection ? $config : Creator::createObject($config);
                }
            } else {
                $this->_databases[$key] = $value instanceof Connection ? $value : Creator::createObject($value);
            }
        }
    }

    /**
     * @param null|string $name
     * @return Connection
     * @throws Exception
     */
    public function getDb($db = null)
    {
        if ($db instanceof Connection) {
            return $db;
        }

        if (empty($db)) {
            $db = self::DEFAULT_CONNECTION_NAME;
        }

        if ($this->hasDb($db)) {
            return $this->_databases[$db];
        } else {
            throw new Exception('Unknown connection: ' . $db);
        }
    }

    /**
     * @param $name
     * @return bool
     */
    public function hasDb($name)
    {
        return isset($this->_databases[$name]);
    }
}