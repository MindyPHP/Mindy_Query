<?php

/**
 * All rights reserved.
 *
 * @author Falaleev Maxim
 * @email max@studio107.ru
 * @version 1.0
 * @company Studio107
 * @site http://studio107.ru
 * @date 05/02/15 16:11
 */

namespace Mindy\Query;

trait OrmUtils
{
    private $_paramsCount = 0;

    /**
     * Makes key for param
     * @param $fieldName
     * @return string
     */
    public function makeParamKey($fieldName)
    {
        $this->_paramsCount += 1;
        $fieldName = str_replace(['`', '{{', '}}', '%', '[[', ']]'], '', $fieldName);
        $fieldName = str_replace('.', '_', $fieldName);
        return $fieldName . $this->_paramsCount;
    }
}
