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

        $regexpCallback = function($pattern, $value) {
            if(preg_match('/'.$pattern.'/', $value)) {
                return true;
            }
            return false;
        };

        if($this->sqliteCreateFunction('regexp', $regexpCallback, 2) === false) {
            // TODO logging "Failed creating function regexp"
            throw new Exception("Failed creating function regexp");
        }

        $iregexpCallback = function($pattern, $value) {
            if(preg_match('/'.$pattern.'/i', $value)) {
                return true;
            }
            return false;
        };

        if($this->sqliteCreateFunction('iregexp', $iregexpCallback, 2) === false) {
            // TODO logging "Failed creating function iregexp"
            throw new Exception("Failed creating function iregexp");
        }
    }
}
