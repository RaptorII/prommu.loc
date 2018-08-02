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

    public function accessMessage($idto){
        if(Share::$UserProfile->type == 3){
            $result['user'] = Yii::app()->db->createCommand()
            ->select("u.ismoder,u.isblocked")
            ->from('user u')
            ->where('u.id_user=:st', array(':st'=>Share::$UserProfile->id))
            ->queryRow();

            if($idto){
                $result['new'] = Yii::app()->db->createCommand()
                ->select("r.id")
                ->from('resume r')
                ->where('r.id=:st', array(':st'=>$idto))
                ->queryRow();
            }   
        } else {
            $result['user'] = Yii::app()->db->createCommand()
            ->select("u.ismoder,u.isblocked")
            ->from('user u')
            ->where('u.id_user=:st', array(':st'=>Share::$UserProfile->id))
            ->queryRow();

            if($idto){
                $result['new'] = Yii::app()->db->createCommand()
                ->select("e.id")
                ->from('employer e')
                ->where('e.id=:st', array(':st'=>$idto))
                ->queryRow();
            }
            
        }

        return $result;
    }
}
