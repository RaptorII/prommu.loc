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
    /**
     * @param $arVacs - array() ID of vacancies
     * @return array(query = array())
     */
    public static function getChatsByVacs($arVacs)
    {
        $arRes['query'] = Yii::app()->db->createCommand()
                            ->select("c.id,
                                c.id_theme, 
                                c.id_use, 
                                c.id_usp, 
                                c.is_resp, 
                                c.is_read,
                                ct.id_vac")
                            ->from('chat c')
                            ->leftjoin('chat_theme ct','c.id_theme=ct.id')
                            ->where(['in','ct.id_vac',$arVacs])
                            ->queryAll();

        return $arRes;    
    }
}
