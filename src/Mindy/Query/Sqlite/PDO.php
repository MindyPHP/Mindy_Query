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
 * @date 05/05/14.05.2014 16:34
 */

namespace Mindy\Query\Sqlite;


use Mindy\Query\Exception;

class PDO extends \PDO
{
    public function __construct($dsn, $username, $passwd, $options)
    {
        parent::__construct($dsn, $username, $passwd, $options);

        $regexCreated = $this->sqliteCreateFunction('regexp', function($pattern, $value) {
            return preg_match($pattern, $value);
        }, 2);

        if($regexCreated === false) {
            // TODO logging "Failed creating function regexp"
            throw new Exception("Failed creating function regexp");
        }
    }
}
