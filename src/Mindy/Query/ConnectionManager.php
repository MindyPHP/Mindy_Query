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
    private $_connections = [];

    /**
     * ConnectionManager constructor.
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        foreach ($options as $key => $value) {
            if (in_array($key, ['databases', 'connections']) && is_array($value)) {
                foreach ($value as $name => $config) {
                    $this->_connections[$name] = $config instanceof Connection ? $config : Creator::createObject($config);
                }
            } else {
                $this->_connections[$key] = $value instanceof Connection ? $value : Creator::createObject($value);
            }
        }
    }

    /**
     * @param null|string $name
     * @return Connection
     * @throws Exception
     */
    public function getConnection($name = null)
    {
        if ($name instanceof Connection) {
            return $name;
        }

        if (empty($name)) {
            $name = self::DEFAULT_CONNECTION_NAME;
        }

        if ($this->hasConnection($name)) {
            return $this->_connections[$name];
        } else {
            throw new Exception('Unknown connection: ' . $name);
        }
    }

    /**
     * @param $name
     * @return bool
     */
    public function hasConnection($name)
    {
        return isset($this->_connections[$name]);
    }
}
