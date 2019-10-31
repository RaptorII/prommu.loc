<?php

/**
 * Date: 18.02.2016
 * Time: 10:12
 */

abstract class UserProfile extends CModel
{
    public $id; // id user
    /** @deprecated */
    public $fio;
    /** @deprecated */
    public $login;
    /** @var UserExInfo */
    public $exInfo;
    public $type;
    public $ismoder;

    public $limit ;
    public $offset;

    public $viewTpl;

    public $photosMax; // макс кол-во фоток у соискателя
    // YiiUpload params
    public $arYiiUpload;

    public $filesRoot;
    public $filesUrl;
    // constant
    public static $APPLICANT = 2;
    public static $EMPLOYER = 3;

    function __construct($inProps)
    {
        $props = (object)$inProps;
        $this->id = $props->id;

        $this->limit = MainConfig::$DEF_PAGE_LIMIT;
        $this->offset = 0;

        $this->filesRoot = Settings::getFilesRoot() . 'users/' . $this->id;
        $this->filesUrl = Settings::getFilesUrl() . 'users/' . $this->id;
        // YiiUpload
        $this->arYiiUpload = [
                'fileLimit' => 5,
                'fileFormat' => ['jpg','jpeg','png'],
                'callButtonText' => 'Загрузить фото',
                'useWebcam' => true,
                'minImageSize' => 350,
                'maxImageSize' => 4500,
                'imageEditor' => true,
                'maxFileSize' => 10, // в мегабайтах(по умолчанию 5)
                'imageSignature' => true, // разрешить подпись фото
                'imgOrigSuFFix' => '000', // суфикс к заголовку измененного, возможно повернутого но не обрезаного изображения
                'filePath' => $this->filesRoot,
                'fileUrl' => $this->filesUrl,
                'objSaveMethod' => 'savePhoto',
                'reloadAfterUpload' => true
            ];
    }



    /**
     * Отдаёт рейтинг пользователя
     * пока используется только в UserProfileEmpl
     * @return array
     */
    public function getRate() { return []; }



    /**
     * Тип пользователя
     * @param $inId - user id
     * @return id, type
     */
    static public function getUserType($inId)
    {
        $sql = "SELECT
              (SELECT r.id FROM resume r WHERE r.id_user = {$inId}) rid,
              (SELECT e.id FROM employer e WHERE e.id_user = {$inId}) eid";
        $res = Yii::app()->db->createCommand($sql)->queryRow();

        if( $res['rid'] ) return array('id' => $res['rid'], 'type' => 2);
        elseif( $res['eid'] ) return array('id' => $res['eid'], 'type' => 3);
        else return array('id' => 0, 'type' => 0);
    }



    /**
     * Устанавливаем данные для профиля пользователя
     */
    public function setUserData()
    {
        if( !Share::is_set($this->exInfo, 'id') ) $this->exInfo = (object)$this->getUserData($this->id);
    }


    /**
     * фабрика модели рейтинга
     * @param $inProps: id - user id
     * @return Rate
     */
    abstract public function makeRate($inProps);


    /**
     * фабрика модели отклика
     * @return Responses
     */
    abstract public function makeResponse();


    /**
     * фабрика модели чатов
     * @return Im
     */
    abstract public function makeChat();



    /**
     * получаем данные профиля для API
     * @param array $props
     * @return mixed
     */
    abstract public function getProfileDataAPI($props);

    abstract public function getProfileDataView($inID = 0);
    abstract public function getProfileDataEdit();
    abstract public function getRateCount($inID = 0);
    abstract public function getCommentsCount($inID = 0);
    abstract public function proccessLogo();

//    abstract public function getResponses();
//    abstract public function getResponsesCount();
//    abstract public function getNewResponses();

    public function attributeNames() { }



    /**
     * Получаем значение статуса пользователя
     * @param $inName string параметра
     * @return string статуса
     */
    public function getUserStatus($inName)
    {
        if( $this->exInfo->statuses )
        {
            $statuses = get_object_vars(json_decode($this->exInfo->statuses));
            return isset($statuses[$inName]) ? $statuses[$inName] : false;
        }
        else return false;
    }



    /**
     * Устанавливаем значение статуса пользователя
     * @param $inProps array key - название статуса, val - значение статуса
     */
    public function setUserStatus($inProps)
    {
        if( is_object($this->exInfo->statuses) ) $statuses = array_merge(get_object_vars($this->exInfo->statuses), array($inProps['key'] => $inProps['val']));
        else $statuses = array($inProps['key'] => $inProps['val']);

        $res = Yii::app()->db->createCommand()
            ->update('user', array(
                'statuses' => json_encode($statuses),
            ), 'id_user = :id_user'
            , array(':id_user' => Share::$UserProfile->id));
    }



    protected abstract function getUserData($inId);
    /**
     *  @param $id - ineger (ID - user)
     *  @param $type - string (applicant | employer)
     *  Показать контактные данные работодателя или соискателя
     */
    public function showContactData($id, $type)
    {
        if(!intval($id))
            return false;

        $query = Yii::app()->db->createCommand()
                    ->select('COUNT(vs.id)')
                    ->from('vacation_stat vs')
                    ->leftjoin('empl_vacations ev', 'ev.id=vs.id_vac');
        $filter = 'ev.id_user=:id_emp AND vs.id_promo=:id_app AND vs.status>4';
        
        $queryEmpl = Yii::app()->db->createCommand()
                    ->select('em.employer_contact')
                    ->from('employer em');
        $filter_Empl = 'em.id_user=:id_emp';

        if($type==='applicant' && Share::isEmployer())
        {
            $params = array(
                    ':id_emp' => Share::$UserProfile->id,
                    ':id_app' => $id
                );
            return $query->where($filter,$params)->queryScalar() > 0;
        }
        if($type==='employer' && Share::isApplicant())
        {
            $params = array(
                    ':id_app' => Share::$UserProfile->exInfo->id_resume,
                    ':id_emp' => $id
                );
            
            $params_Empl = array(
                    ':id_emp' => $id
                );
                
                
            $flag = $query->where($filter,$params)->queryScalar();
            $flag_Contact = $queryEmpl->where($filter_Empl,$params_Empl)->queryRow();
            
            if($flag > 0 && (int)$flag_Contact['employer_contact'] > 0){
                $res = 1;
            } else $res = 0;
            
            return $res;
        }
        return false;   
    }
    /**
     * @param $arr - array [ column => value ]
     * @param $id_user - integer
     */
    public function getUserAttribute($arr, $id_user=false)
    {
        $conditions = $params = array();
        if(!is_array($arr))
        {
          return false;
        }
        else
        {
          foreach ($arr as $key => $value)
          {
            $conditions[] = "ua.$key=:$key";
            $params[":$key"] = $value;
          }
        }
        if(!isset($conditions['ua.id_us']))
        {
          $conditions[] = "ua.id_us=:id_us";
          $params[":id_us"] = ($id_user ?: $this->id);
        }
        $conditions = implode(' AND ',$conditions);

        $arRes = Yii::app()->db->createCommand()
                    ->select('ua.id_us,
                        ua.id_attr,
                        ua.key,
                        ua.val,
                        ua.crdate,
                        uad.id_par,
                        uad.name,
                        uad.postself')
                    ->from('user_attribs ua')
                    ->leftjoin('user_attr_dict uad','uad.id=ua.id_attr')
                    ->where($conditions,$params)
                    ->queryAll();

        return (count($arRes) ? $arRes : false);
    }
    /**
     * @param $key - string
     * @param $value - mixed
     * @param $id_user - integer
     */
    public function setUserAttribute($key, $value, $id_user=false)
    {
        $id_user = ($id_user ?: $this->id);
        if(empty($key) || empty($value))
            return false;

        $dict = Yii::app()->db->createCommand()
                    ->select("*")
                    ->from("user_attr_dict uad")
                    ->where("uad.key=:key",[":key"=>$key])
                    ->queryRow();

        if(!isset($dict['id']))
            return false;

        $query = Yii::app()->db->createCommand()
                    ->select('*')
                    ->from('user_attribs')
                    ->where(
                        'id_attr=:id_attr and id_us=:id_user',
                        [':id_attr'=>$dict['id'],':id_user'=>$id_user]
                    )
                    ->queryRow();

        if(isset($query['id_attr']))
        {
             return Yii::app()->db->createCommand()
                    ->update(
                        'user_attribs',
                        [
                            'val'=>$value,
                            'crdate'=>date('Y-m-d H:i:s')
                        ],
                        'id_attr=:id_attr and id_us=:id_user',
                        [':id_attr'=>$dict['id'],':id_user'=>$id_user]
                    );
        }

        return Yii::app()->db->createCommand()
                    ->insert(
                        'user_attribs',
                        [
                            'id_us' => $id_user,
                            'id_attr' => $dict['id'],
                            'key' => $dict['key'],
                            'type' => $dict['type'],
                            'val' => $value,
                            'crdate'=>date('Y-m-d H:i:s')
                        ]
                    );
    }
}



/**
 * Класс описания расширенных данных профиля для (IDE)
 */
class UserExInfo
{
    public $id;
    public $login;
    public $email;
    /**
     * @var int promo(2) or employer(3) or guest
     */
    public $status;
    /**
     * @var int 0 - form normal
     */
    public $isblocked;
     public $ismoder;
    /**
     * @var int user_work.id user auth tokens
     */
    public $wid;
    public $id_resume;
    public $lastname;
    public $firstname;
    public $fio;
    public $photo;
    /**
     * @var int id employer
     */
    public $eid;
    public $efio;
    /**
     * @var string company name
     */
    public $name;
    public $logo;
    public $statuses;
}
