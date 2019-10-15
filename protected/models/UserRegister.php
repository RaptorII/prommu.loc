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
}