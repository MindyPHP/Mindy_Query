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
 * @date 27/05/14.05.2014 19:03
 */

namespace Mindy\Query;


class PDO extends \PDO
{
    /**
     * @var array Database drivers that support savepoints.
     */
    protected $savepointTransactions = ["pgsql", "mysql"];

    /**
     * @var int The current transaction level.
     */
    protected $transLevel = 0;

    protected function nestable()
    {
        return in_array($this->getAttribute(PDO::ATTR_DRIVER_NAME), $this->savepointTransactions);
    }

    public function beginTransaction()
    {
        if (!$this->nestable() || $this->transLevel == 0) {
            parent::beginTransaction();
        } else {
            $this->exec("SAVEPOINT LEVEL{$this->transLevel}");
        }

        $this->transLevel++;
    }

    public function commit()
    {
        $this->transLevel--;

        if (!$this->nestable() || $this->transLevel == 0) {
            parent::commit();
        } else {
            $this->exec("RELEASE SAVEPOINT LEVEL{$this->transLevel}");
        }
    }

    public function rollBack()
    {
        $this->transLevel--;

        if (!$this->nestable() || $this->transLevel == 0) {
            parent::rollBack();
        } else {
            $this->exec("ROLLBACK TO SAVEPOINT LEVEL{$this->transLevel}");
        }
    }

}
