<?php
/**
 * Date: 23.05.2016
 *
 * Модель отзывов
 */

abstract class Comments extends Model
{
    protected $idUser;
    protected $idProfile;
    protected $typeUser;

    function __construct()
    {
        $id = filter_var(Yii::app()->getRequest()->getParam('id', 0), FILTER_SANITIZE_NUMBER_INT);
        $type = UserProfile::getUserType($id);

        $this->idUser = $id;
        $this->idProfile = $type['id'];
        $this->typeUser = $type['type'];
    }



    /**
     * Получаем отзывы
     */
    public function getComments()
    {
        $id = $this->idUser;
        $type = $this->typeUser;

        if( $id && $type )
        {
            if( $type == 2 ) $Up = new UserProfileApplic(array('id' => $id));
            else $Up = new UserProfileEmpl(array('id' => $id));
            $data['profile']['commcount'] = $Up->getCommentsCount($this->idProfile);
            $data['profile']['rate'] = $Up->getRateCount($id);
            $data['profile']['data'] = $Up->getUserProfileData($id);
            $data['profile']['data']['fio'] = $data['profile']['data']['firstname'] . ' ' . $data['profile']['data']['lastname'];
            $data['profile']['data']['avatar'] = $type == 2 ?
                DS . MainConfig::$PATH_APPLIC_LOGO . DS . (!$data['profile']['data']['photo'] ?
                    MainConfig::$DEF_LOGO : ($data['profile']['data']['photo']) . '400.jpg')
                : DS . MainConfig::$PATH_EMPL_LOGO . DS . (!$data['profile']['data']['logo'] ?
                    MainConfig::$DEF_LOGO_EMPL : ($data['profile']['data']['logo']) . '400.jpg');
;
//            $data['profile']['data'] = $Up->getUserProfileData($id);

            // получаем комментарии
            $data['comments'] = $this->getCommentsData();

            // устанавливаем комментарии как прочитанные
            if( $id == Share::$UserProfile->id ) $this->setCommentsProcessed();
        }
        else
        {
            return array('error' => 1, "message" => "Пользователь не определен");
        } // endif

        return $data;
    }



    /**
     * Получаем новости
     */
    abstract public function getCommentsCount();
    abstract protected function getCommentsData();
    abstract protected function setCommentsProcessed();
}