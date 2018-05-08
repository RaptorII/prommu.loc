<?php
/**
 * Date: 30.03.2016
 * Time: 10:33
 */

class Model
{
    public $limit ;
    public $offset;

    function __construct()
    {
        $this->limit = MainConfig::$DEF_PAGE_LIMIT;
        $this->offset = 0;
    }
}