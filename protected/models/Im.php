<?php
/**
 * Date: 22.04.2016
 *
 * Модель переписки
 */

abstract class Im extends Model
{
    /** @var  UserProfile */
    protected $Profile;


    /**
     * Im constructor.
     * @param $Profile UserProfile
     */
    function __construct($Profile = null)
    {
        parent::__construct();

        $this->Profile = $Profile ?: Share::$UserProfile;
    }

}
