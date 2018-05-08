<?php

/**
 * Created by Vlasakh
 * Date: 21.09.16
 * Time: 15:04
 */
class ARModel extends CActiveRecord
{
    public $offset;
    public $limit;


    function __construct($scenario = 'insert')
    {
        parent::__construct($scenario = 'insert');

        $this->limit = MainConfig::$DEF_PAGE_LIMIT;
        $this->offset = 0;
    }
}