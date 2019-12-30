<?php
/**
 * Created by PhpStorm.
 * User: Dmitry Derevyanko
 * Date: 02.12.2019
 * Time: 11:59
 */

class UserRegisterPageCounter extends CActiveRecord
{
  public static function model($className=__CLASS__)
  {
    return parent::model($className);
  }

  public function tableName()
  {
    return 'user_register_page_cnt';
  }

  public static function set($page)
  {
    if(isset(Yii::app()->request->cookies['urh']))
    {
      return self::setData(Yii::app()->request->cookies['urh'], $page);
    }
    else
    {
      return false;
    }
  }

  public static function setByIdUser($id_user, $page)
  {
    $user = Yii::app()->db->createCommand()
      ->select('user')
      ->from('user_register')
      ->where('id_user=:id_user',[':id_user'=>$id_user])
      ->queryScalar();

    if(!$user)
    {
      return false;
    }

    self::setData($user, $page);
  }

  private static function setData($user, $page)
  {
    return Yii::app()->db->createCommand()
      ->insert(
        'user_register_page_cnt',
        ['user' => $user, 'page' => $page, 'time' => time()]
      );
  }
  /**
   *
   */
  public function searchAll()
  {
    $rq = Yii::app()->getRequest();
    $arGet = $rq->getParam('Registers');
    $page = $rq->getParam('page');
    $bDate = strtotime($rq->getParam('bdate'));
    $eDate = strtotime($rq->getParam('edate'));
    $bDateCreate = strtotime($rq->getParam('bdate_create'));
    $eDateCreate = strtotime($rq->getParam('edate_create'));

    $arRes = [
      'id' => [],
      'limit' => 100,
      'items' => []
    ];
    $arCondition = [];
    $arRes['offset'] = (intval($page)-1) * intval($arRes['limit']);
    $arRes['offset']<0 && $arRes['offset']=0;
    // id
    $value = filter_var($arGet['id'], FILTER_SANITIZE_NUMBER_INT);
    $value>0 && $arCondition[]="ur.id={$value}";
    // user type
    $value = $arGet['type'];
    if(in_array($value, [UserProfile::$EMPLOYER,UserProfile::$APPLICANT]))
    {
      $arCondition[]="ur.type={$value}";
    }
    else if($value==1)
    {
      $arCondition[]="ur.id IS NULL";
    }
    // page
    $value = $arGet['page_type'];
    if(in_array($value,[1,2,3,4,5,6,7]))
    {
      $arCondition[]="urpc.page={$value}";
    }
    // time_page
    if(intval($bDate) && intval($eDate))
    {
      $eDate+=86400;
      $arCondition[] = "urpc.time between $bDate and $eDate";
    }
    elseif(intval($bDate))
    {
      $arCondition[] = "urpc.time >= $bDate";
    }
    elseif(intval($eDate))
    {
      $eDate+=86400;
      $arCondition[] = "urpc.time <= $eDate";
    }
    // time_create
    if(intval($bDateCreate) && intval($eDateCreate))
    {
      $eDateCreate+=86400;
      $arCondition[] = "ur.date between $bDateCreate and $eDateCreate";
    }
    elseif(intval($bDateCreate))
    {
      $arCondition[] = "ur.date >= $bDateCreate";
    }
    elseif(intval($eDateCreate))
    {
      $eDateCreate+=86400;
      $arCondition[] = "ur.date <= $eDateCreate";
    }
    //
    // social
    $value = $arGet['social'];
    $value==1 && $arCondition[]="ur.social=1";
    // lead
    $value = $arGet['lead'];
    $value==1 && $arCondition[]="ur.id_user IS NOT NULL";
    //
    $condition = '';
    if(count($arCondition))
    {
      $condition = implode(' and ',$arCondition);
    }

    $arRes['id'] = Yii::app()->db->createCommand()
      ->select('DISTINCT(urpc.user)')
      ->from('user_register_page_cnt urpc')
      ->leftjoin('user_register ur','ur.user=urpc.user')
      ->where($condition)
      ->order('urpc.time desc')
      ->queryColumn();

    if(!count($arRes['id']))
      return $arRes;

    $arId = [];
    for ($i=$arRes['offset'], $n=count($arRes['id']); $i<$n; $i++ )
    {
      if(($i < ($arRes['offset'] + $arRes['limit'])) && isset($arRes['id'][$i]))
        $arId[] = $arRes['id'][$i];
    }

    $query = Yii::app()->db->createCommand()
      ->select('ur.id, ur.id_user, ur.type, ur.social, urpc.page, urpc.user, urpc.time, ur.date')
      ->from('user_register_page_cnt urpc')
      ->leftjoin('user_register ur','ur.user=urpc.user')
      ->where(['in','urpc.user',$arId])
      ->queryAll();

    foreach ($query as $v)
    {
      if(!isset($arRes['items'][$v['user']]))
      {
        $arRes['items'][$v['user']] = [
          'id' => $v['id'],
          'type' => $v['type'],
          'page_1' => 0,
          'page_2' => 0,
          'page_3' => 0,
          'page_4' => 0,
          'page_5' => 0,
          'page_6' => 0,
          'page_7' => 0,
          'time_page' => Share::getDate($v['time']),
          'time_create' => Share::getDate($v['date']),
          'social' => $v['social'],
          'id_user' => $v['id_user'],
        ];
      }

      $arRes['items'][$v['user']]['page_' . $v['page']]++;
      $arRes['items'][$v['user']]['time'] = Share::getDate($v['time']);
      $arRes['items'][$v['user']]['date'] = Share::getDate($v['date']);
    }

    usort(
      $arRes['items'],
      function($a, $b){ return ($a['time'] > $b['time']) ? -1 : 1; }
    );

    $arRes['pages'] = new CPagination(count($arRes['id']));
    $arRes['pages']->pageSize = $arRes['limit'];
    $objPager = (object)['limit'=>$arRes['limit'],'offset'=>$arRes['offset']];
    $arRes['pages']->applyLimit($objPager);

    return $arRes;
  }
  /**
   * @param id_user - User => id_user
   * @return boolean
   * Проверяем заход на страницу формирования лида (если <=0 - переходим на /user/lead)
   */
  public static function isSetData($id_user)
  {
    // отрабатывает только на /user/lead
    if(Yii::app()->request->url!==MainConfig::$PAGE_AFTER_REGISTER)
    {
      return -1;
    }

    $user = Yii::app()->db->createCommand()
      ->select('user')
      ->from('user_register')
      ->where('id_user=:id_user', [':id_user' => $id_user])
      ->queryScalar();
    // и только если есть регистрация с данным id_user
    if($user)
    {
      Yii::app()->request->cookies['urh'] = new CHttpCookie('urh', $user);
      $query = Yii::app()->db->createCommand()
        ->select('count(id)')
        ->from('user_register_page_cnt')
        ->where(
          'user=:user and page=:page',
          [
            ':user' => $user,
            ':page' => UserRegister::$PAGE_USER_LEAD
          ]
        )
        ->queryScalar();
      return intval($query);
    }

    return 1;
  }
}