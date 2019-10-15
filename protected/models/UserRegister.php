<?php
/**
 * Created by PhpStorm.
 * User: Dmitry Derevyanko
 * Date: 15.10.2019
 * Time: 12:37
 */

class UserRegister
{
  public static $SOUL = 987654123;
  /**
   * @return int
   */
  public function getStep()
  {
    $rq = Yii::app()->request;
    $step = 1000;
    if(isset($rq->cookies['urs']))
    {
      $cookie = $rq->cookies['urs']->value;
      for ($i=1000; $i<=6000; $i+=1000)
      {
        md5($i . self::$SOUL)==$cookie && $step=$i;
      }
    }
    else
    {
      $this->setStep(1);
    }

    return intval($step/1000);
  }
  /**
   * @param $step
   */
  public function setStep($step)
  {
    $value = intval($step) * 1000;
    Yii::app()->request->cookies['urs'] = new CHttpCookie('urs', md5($value . self::$SOUL));
  }
  /**
   * @param $step - integer
   * @param $data - array
   * @return array - errors
   */
  public function setDataByStep($step, $data)
  {
    $arErrors = $arData = [];
    if($step==1)
    {
      if(!in_array($data['type'],[UserProfile::$EMPLOYER, UserProfile::$APPLICANT]))
      {
        $arErrors[] = 'Неподходящий тип пользователя';
      }
      else
      {
        $arData['type'] = $data['type'];
      }
    }

    if(!$this->setData($arData))
    {
      $arErrors[] = 'Ошибка записи данных';
    }
    return $arErrors;
  }
  /**
   * @param $arr - array(field => value)
   * @return bool
   */
  private function setData($arr)
  {
    $rq = Yii::app()->request;
    if(!count($arr) || !isset($rq->cookies['PHPSESSID']))
      return false;

    $hash = $rq->cookies['PHPSESSID']->value;
    // обновление данных
    $query = Yii::app()->db->createCommand()
      ->update('user_register',$arr,'hash=:hash',[':hash'=>$hash]);
    // создание записи
    if(!$query)
    {
      $arr['hash'] = $hash;
      $query = Yii::app()->db->createCommand()
        ->insert('user_register',$arr);
    }
    return $query;
  }
  /**
   * @return mixed
   */
  public function getData()
  {
    $rq = Yii::app()->request;
    if(!isset($rq->cookies['PHPSESSID']))
      return false;

    $hash = $rq->cookies['PHPSESSID']->value;

    return Yii::app()->db->createCommand()
      ->select('*')
      ->from('user_register')
      ->where('hash=:hash',[':hash'=>$hash])
      ->queryRow();
  }
}