<?php
/**
 * Created by PhpStorm.
 * User: Dmitry Derevyanko
 * Date: 08.11.2019
 * Time: 17:40
 */

class UserRegisterAdmin extends CActiveRecord
{
  public $limit;

  public static $STATE_PROFILE = 'profile';
  public static $STATE_AVATAR = 'avatar';
  public static $STATE_CODE = 'code';

  public $getType;
  public $getState;
  public $getId;
  public $view;
  public $item;

  function __construct()
  {
    parent::__construct();
    $this->limit = 20;

    $rq = Yii::app()->getRequest();
    $this->getType = $rq->getParam('user');
    $this->getState = $rq->getParam('state');
    $this->getId = $rq->getParam('id');

    $this->view = 'register/' . (intval($this->getId) ? 'item' : 'list');
  }

  public static function model($className=__CLASS__)
  {
    return parent::model($className);
  }

  public function tableName()
  {
    return 'user_register';
  }
  /**
   * @param $state - string ( profile | avatar | code )
   * @param $type - integer ( 2 | 3 )
   * @return CActiveDataProvider
   */
  public function search($state, $type)
  {
    $criteria = new CDbCriteria;
    $get = Yii::app()->getRequest()->getParam('UserRegisterAdmin');
    $bDate = Yii::app()->getRequest()->getParam('b_date');
    $bDate = strtotime($bDate);
    $eDate = Yii::app()->getRequest()->getParam('e_date');
    $eDate = strtotime($eDate);

    $arCondition = [];

    switch ($state)
    {
      case self::$STATE_PROFILE:
        $this->is_confirm = 1;
        $criteria->join = "LEFT JOIN user_photos up ON up.id_user=t.id_user"
          . " LEFT JOIN user u ON u.id_user=t.id_user";
        $arCondition[] = "((t.social=0 AND up.photo IS NOT NULL) OR (t.social=1 AND up.photo IS NULL)) "
          . "AND u.isblocked=" . User::$ISBLOCKED_NOT_FULL_ACTIVE;
        break;
      case self::$STATE_AVATAR:
        $this->is_confirm = 1;
        $criteria->join = "LEFT JOIN user_photos up ON up.id_user=t.id_user"
         . " LEFT JOIN user u ON u.id_user=t.id_user";
        $arCondition[] = "up.photo IS NULL AND u.isblocked=" . User::$ISBLOCKED_NOT_FULL_ACTIVE;
        break;
      case self::$STATE_CODE:
        $this->is_confirm = 0;
        break;
    }
    // дата создания
    if(intval($bDate) && intval($eDate))
    {
      $arCondition[] = "t.date between $bDate and $eDate";
    }
    elseif(intval($bDate))
    {
      $arCondition[] = "t.date >= $bDate";
    }
    elseif(intval($eDate))
    {
      $arCondition[] = "t.date <= $eDate";
    }
    // объединяем условия
    if(count($arCondition))
    {
      $criteria->condition = implode(' and ', $arCondition);
    }

    if(in_array($type, [UserProfile::$EMPLOYER, UserProfile::$APPLICANT]))
    {
      $this->type = $type;
      $criteria->compare('t.type', $this->type);
    }
    $criteria->compare('t.is_confirm', $this->is_confirm);
    $this->id = $get['id'];
    $criteria->compare('t.id', $this->id, true);
    $this->name = $get['name'];
    $criteria->compare('t.name', $this->name, true);
    $this->surname = $get['surname'];
    $criteria->compare('t.surname', $this->surname, true);
    $this->login = $get['login'];
    $criteria->compare('t.login', $this->login, true);
    $this->subdomen = $get['subdomen'];
    $criteria->compare('t.subdomen', $this->subdomen);

    return new CActiveDataProvider(
      $this,
      [
        'criteria' => $criteria,
        'pagination' => ['pageSize' => $this->limit],
        'sort' => ['defaultOrder' => 'id desc']
      ]
    );
  }
  /**
   *
   */
  public function getData()
  {
    $this->item = $this::model()->findByPk($this->getId);
  }
}