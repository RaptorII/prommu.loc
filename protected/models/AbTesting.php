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
    $yagla = Yii::app()->getRequest()->getParam('yagla');
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
    if(!empty($yagla) && strripos($result,'/user/register/type')===false)
    {
      $result .= "?yagla=$yagla";
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
      'applicant_all' => [
        self::$LINK_APPLICANT => [
          'page' => self::$AR_LINKS[self::$LINK_APPLICANT],
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
      ],
      'employer_all' => [
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
        ]
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
          $arResult['applicant_all'][$v->url]['cnt']++; // таблица 3
        }
        else
        {
          $arResult['employer']['cnt']++; // таблица 2
          $arResult['employer_all'][$v->url]['cnt']++; // таблица 4
        }
        // таблица 3 и 4
        $arUser[] = $v->user;
      }

      $arRegisters = UserRegister::getLeadByHashArray($arUser);
      if(count($arRegisters))
      {
        foreach ($query as $v)
        {
          if(in_array($v->user, $arRegisters))
          {
            if($v->type==UserProfile::$APPLICANT)
            {
              $arResult['applicant_all'][$v->url]['cnt_lead']++; // таблица 3
            }
            else
            {
              $arResult['employer_all'][$v->url]['cnt_lead']++; // таблица 4
            }
          }
        }
      }

      foreach ($arResult['applicant_all'] as $url => $v)
      {
        if($v['cnt']>0 && $v['cnt_lead']>0)
        {
          $result = $v['cnt_lead'] / $v['cnt'] * 100;
          $arResult['applicant_all'][$url]['conversion'] = round($result, 2);
        }
      }
      foreach ($arResult['employer_all'] as $url => $v)
      {
        if($v['cnt']>0 && $v['cnt_lead']>0)
        {
          $result = $v['cnt_lead'] / $v['cnt'] * 100;
          $arResult['employer_all'][$url]['conversion'] = round($result, 2);
        }
      }
    }

    return $arResult;
  }
}