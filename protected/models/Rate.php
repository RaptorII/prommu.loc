<?php

abstract class Rate extends Model
{
    public $viewTpl;

    protected $id; // id user
    /** @var UserProfile  */
    protected $UserProfile; // id user

    function __construct($inProps)
    {
        $this->id = $inProps['id'];
        $this->UserProfile = $inProps['userProfile'];
    }


    abstract public function getViewData();
}
