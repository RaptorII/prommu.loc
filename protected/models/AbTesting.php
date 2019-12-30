<?php
/**
 * Created by PhpStorm.
 * User: Dmitry Derevyanko
 * Date: 25.12.2019
 * Time: 12:08
 */

class AbTesting extends CActiveRecord
{
  public static $AR_LINKS = ['/about/prom','/about/empl','/user/register/type'];
  public static $LINK_APPLICANT = 0;
  public static $LINK_EMPLOYER = 1;
  public static $LINK_REGISTER = 2;

  function __construct()
  {
    parent::__construct();
  }

  public static function model($className=__CLASS__)
  {
    return parent::model($className);
  }

  public function tableName()
  {
    return 'ab_testing';
  }
  /**
   * @return bool|mixed
   * определение ссылки для редиректа(или false в случае неудачи)
   */
  public function getLink()
  {
    $result = false;

    UserRegister::clearUser();
    $this->user = UserRegister::setUser();
    Yii::app()->request->cookies['urh'] = new CHttpCookie('urh', $this->user);

    $type = Yii::app()->getRequest()->getParam('type');
    if(in_array($type,['soiskatel','rabotodatel']))
    {
      $this->type = ($type=='soiskatel' ? UserProfile::$APPLICANT : UserProfile::$EMPLOYER);

      $query = self::model()->find( // ищем последий переход
        [
          'condition' => 'type=:type',
          'order' => 'id desc',
          'params' => [':type' => $this->type]
        ]
      );

      if(!$query) // записи отсутствуют
      {
        $this->url = self::$LINK_REGISTER;
        $result = self::$AR_LINKS[$this->url];
      }
      else
      {
        if($query->type==UserProfile::$APPLICANT) // соискатель
        {
          $this->url = $query->url==self::$LINK_APPLICANT
            ? self::$LINK_REGISTER
            : self::$LINK_APPLICANT;
          $result = self::$AR_LINKS[$this->url];
        }
        else // работодатель
        {
          $this->url = $query->url==self::$LINK_EMPLOYER
            ? self::$LINK_REGISTER
            : self::$LINK_EMPLOYER;
          $result = self::$AR_LINKS[$this->url];
        }
      }

      $this->date = time();
      $this->setIsNewRecord(true);
      $this->save();
    }
    return $result;
  }
  /**
   *
   */
  public function getData()
  {
    $arResult = [
      'applicant' => [
        'link' => '/ab/soiskatel',
        'cnt' => 0
      ],
      'employer' => [
        'link' => '/ab/rabotodatel',
        'cnt' => 0
      ],
      'all' => [
        self::$LINK_APPLICANT => [
          'page' => self::$AR_LINKS[self::$LINK_APPLICANT],
          'cnt' => 0,
          'cnt_lead' => 0,
          'conversion' => 0,
        ],
        self::$LINK_EMPLOYER => [
          'page' => self::$AR_LINKS[self::$LINK_EMPLOYER],
          'cnt' => 0,
          'cnt_lead' => 0,
          'conversion' => 0,
        ],
        self::$LINK_REGISTER => [
          'page' => self::$AR_LINKS[self::$LINK_REGISTER],
          'cnt' => 0,
          'cnt_lead' => 0,
          'conversion' => 0,
        ],
      ]
    ];
    //
    $get = Yii::app()->getRequest()->getParam('Ab_testing');
    $bdate = Share::checkFormatDate($get['bdate']);
    $edate = Share::checkFormatDate($get['edate']);
    $condition = '';

    if($bdate && $edate)
    {
      $condition = "date between " . strtotime("$bdate 00:00:00")
        . " and " . strtotime("$edate 23:59:59");
    }
    else if($bdate)
    {
      $condition = "date > " . strtotime("$bdate 00:00:00");
    }
    else if ($edate)
    {
      $condition = "date < " . strtotime("$edate 23:59:59");
    }

    if(!empty($condition))
    {
      $query = self::model()->findAll(['condition'=>$condition]);
    }
    else
    {
      $query = self::model()->findAll();
    }
    //
    if(count($query))
    {
      $arUser = [];
      foreach ($query as $v)
      {
        if($v->type==UserProfile::$APPLICANT)
        {
          $arResult['applicant']['cnt']++; // таблица 1
        }
        else
        {
          $arResult['employer']['cnt']++; // таблица 2
        }
        // таблица 3
        $arResult['all'][$v->url]['cnt']++;
        $arUser[] = $v->user;
      }

      $arRegisters = UserRegister::getLeadByHashArray($arUser);
      if(count($arRegisters))
      {
        foreach ($query as $v)
        {
          if(in_array($v->user, $arRegisters))
          {
            $arResult['all'][$v->url]['cnt_lead']++;
          }
        }
      }

      foreach ($arResult['all'] as $url => $v)
      {
        if($v['cnt']>0 && $v['cnt_lead']>0)
        {
          $arResult['all'][$url]['conversion'] = $v['cnt_lead'] / $v['cnt'] * 100;
          $arResult['all'][$url]['conversion'] = round($arResult['all'][$url]['conversion'], 2);
        }
      }
    }

    return $arResult;
  }
}