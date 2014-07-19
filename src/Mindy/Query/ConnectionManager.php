<?php
/**
 *
 *
 * All rights reserved.
 *
 * @author Falaleev Maxim
 * @email max@studio107.ru
 * @version 1.0
 * @company Studio107
 * @site http://studio107.ru
 * @date 19/07/14.07.2014 16:08
 */

namespace Mindy\Query;


use Mindy\Helper\Creator;
use Mindy\Helper\Traits\Accessors;
use Mindy\Helper\Traits\Configurator;
use Mindy\Query\Exception\UnknownDatabase;

class ConnectionManager
{
    use Accessors, Configurator;

    /**
     * @var array
     */
    public $databases = [];

    /**
     * @var Connection[]
     */
    protected static $_databases = [];

    public function init()
    {
        foreach ($this->databases as $name => $config) {
            if (is_array($config)) {
                self::$_databases[$name] = Creator::createObject($config);
            } elseif ($config instanceof Connection) {
                self::$_databases[$name] = $config;
            }
        }
    }

    public static function getDb($db = null)
    {
        if ($db === null) {
            $db = 'default';
        }

        if (!isset(self::$_databases[$db])) {
            throw new UnknownDatabase();
        }

        return self::$_databases[$db];
    }
}
