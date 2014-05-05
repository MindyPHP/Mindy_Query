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
 * @date 05/05/14.05.2014 14:49
 */

namespace Mindy\Query\Mysql;

trait Lookup
{
    /**
     * @param $field
     * @param $value
     * @return array
     */
    public function buildRange($field, $value)
    {
        list($start, $end) = $value;
        return [['between', $field, $start, $end], []];
    }

    /**
     * @param $field
     * @param $value
     * @return array
     */
    public function buildIendswith($field, $value)
    {
        return [['ilike', $field, '%' . $value, false], []];
    }

    /**
     * @param $field
     * @param $value
     * @return array
     */
    public function buildEndswith($field, $value)
    {
        return [['like', $field, '%' . $value, false], []];
    }

    /**
     * @param $field
     * @param $value
     * @return array
     */
    public function buildIStartswith($field, $value)
    {
        return [['ilike', $field, $value . '%', false], []];
    }

    /**
     * @param $field
     * @param $value
     * @return array
     */
    public function buildIcontains($field, $value)
    {
        return [['ilike', $field, $value], []];
    }

    /**
     * @param $field
     * @param $value
     * @return array
     */
    public function buildStartswith($field, $value)
    {
        return [['like', $field, $value . '%', false], []];
    }

    /**
     * @param $field
     * @param $value
     * @return array
     */
    public function buildLte($field, $value)
    {
        /* @var $this \Mindy\Query\QueryBuilder */
        $paramName = $this->makeParamKey($field);
        return [['and', $this->db->quoteColumnName($field) . ' <= :' . $paramName], [':' . $paramName => $value]];
    }

    /**
     * @param $field
     * @param $value
     * @return array
     */
    public function buildLt($field, $value)
    {
        /* @var $this \Mindy\Query\QueryBuilder */
        $paramName = $this->makeParamKey($field);
        return [['and', $this->db->quoteColumnName($field) . ' < :' . $paramName], [':' . $paramName => $value]];
    }

    /**
     * @param $field
     * @param $value
     * @return array
     */
    public function buildContains($field, $value)
    {
        return [['like', $field, $value], []];
    }

    /**
     * @param $field
     * @param $value
     * @return array
     */
    public function buildExact($field, $value)
    {
        return [[$field => $value], []];
    }

    /**
     * @param $field
     * @param $value
     * @return array
     */
    public function buildIsnull($field, $value)
    {
        if ($value) {
            return [[$field => null], []];
        } else {
            return [['not', [$field => null]], []];
        }
    }

    /**
     * @param $field
     * @param $value
     * @return array
     */
    public function buildIn($field, $value)
    {
        if (is_object($value) && get_class($value) == __CLASS__) {
            return [['and', $this->db->quoteColumnName($field) . ' IN (' . $value->allSql() . ')'], []];
        }

        return [['in', $field, $value], []];
    }

    /**
     * @param $field
     * @param $value
     * @return array
     */
    public function buildGte($field, $value)
    {
        /* @var $this \Mindy\Query\QueryBuilder */
        $paramName = $this->makeParamKey($field);
        return [['and', $this->db->quoteColumnName($field) . ' >= :' . $paramName], [':' . $paramName => $value]];
    }

    /**
     * @param $field
     * @param $value
     * @return array
     */
    public function buildGt($field, $value)
    {
        /* @var $this \Mindy\Query\QueryBuilder */
        $paramName = $this->makeParamKey($field);
        return [['and', $this->db->quoteColumnName($field) . ' > :' . $paramName], [':' . $paramName => $value]];
    }

    /**
     * @param $field
     * @param $value
     * @return array
     * @throws \Mindy\Exception\Exception
     */
    public function buildIregex($field, $value)
    {
        /* @var $this \Mindy\Query\QueryBuilder */
        if (!is_string($value)) {
            $value = (string)$value;
        }

        $paramName = $this->makeParamKey($field);
        return [['and', $this->db->quoteColumnName($field) . " REGEXP :" . $paramName], [':' . $paramName => $value]];
    }

    /**
     * @param $field
     * @param $value
     * @return array
     * @throws \Mindy\Exception\Exception
     */
    public function buildRegex($field, $value)
    {
        /* @var $this \Mindy\Query\QueryBuilder */
        if (!is_string($value)) {
            $value = (string)$value;
        }

        $paramName = $this->makeParamKey($field);
        return [['and', $this->db->quoteColumnName($field) . " REGEXP BINARY :" . $paramName], [':' . $paramName => $value]];
    }

    /**
     * @param $field
     * @param $value
     * @return array
     */
    public function buildSearch($field, $value)
    {
        throw new \Exception('Not implemented');
    }

    /**
     * @param $field
     * @param $value
     * @return array
     */
    public function buildSecond($field, $value)
    {
        return $this->buildDateTimeCondition($field, $value, "SECOND");
    }

    /**
     * @param $field
     * @param $value
     * @return array
     */
    public function buildMinute($field, $value)
    {
        return $this->buildDateTimeCondition($field, $value, "MINUTE");
    }

    /**
     * @param $field
     * @param $value
     * @return array
     */
    public function buildHour($field, $value)
    {
        return $this->buildDateTimeCondition($field, $value, "HOUR");
    }

    /**
     * @param $field
     * @param $value
     * @return array
     */
    public function buildWeek_day($field, $value)
    {
        /* @var $this \Mindy\Query\QueryBuilder */
        if (!is_string($value)) {
            $value = (string)$value;
        }

        $paramName = $this->makeParamKey($field);
        // TODO: this works only with MYSQL, PostgreSQL need EXTRACT(DOW FROM `field`)
        return [['and', "DAYOFWEEK(" . $this->db->quoteColumnName($field) . ") = :" . $paramName], [':' . $paramName => $value]];
    }

    /**
     * @param $field
     * @param $value
     * @return array
     */
    public function buildDay($field, $value)
    {
        return $this->buildDateTimeCondition($field, $value, "DAY");
    }

    /**
     * @param $field
     * @param $value
     * @return array
     */
    public function buildMonth($field, $value)
    {
        return $this->buildDateTimeCondition($field, $value, "MONTH");
    }

    /**
     * @param $field
     * @param $value
     * @return array
     */
    public function buildYear($field, $value)
    {
        return $this->buildDateTimeCondition($field, $value, "YEAR");
    }

    /**
     * @param $field
     * @param $value
     * @param $extract
     * @return array
     */
    public function buildDateTimeCondition($field, $value, $extract = "YEAR")
    {
        /* @var $this \Mindy\Query\QueryBuilder */
        if (!is_string($value)) {
            $value = (string)$value;
        }

        $paramName = $this->makeParamKey($field);
        return [['and', "EXTRACT(" . $extract . " FROM " . $this->db->quoteColumnName($field) . ") = :" . $paramName], [':' . $paramName => $value]];
    }
}