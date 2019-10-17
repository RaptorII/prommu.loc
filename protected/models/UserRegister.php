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
  public static $STRLENGTH = 64;
  /**
   * @return int
   * получаем шаг регистрации из куков, или устанавливаем шаг 1
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
   * устанавливаем новый шаг регистрации в куки
   */
  public function setStep($step)
  {
    $value = intval($step) * 1000;
    Yii::app()->request->cookies['urs'] = new CHttpCookie('urs', md5($value . self::$SOUL));
  }
  /**
   * удаляем шаг из куков
   */
  public static function clearStep()
  {
    unset(Yii::app()->request->cookies['urs']);
  }
  /**
   * @return bool
   * проверка запуска процесса регистрации
   */
  public static function beginRegister()
  {
    $rq = Yii::app()->request;
    if(!isset($rq->cookies['PHPSESSID']))
      return false;

    $hash = $rq->cookies['PHPSESSID']->value;

    $result = Yii::app()->db->createCommand()
      ->select('count(id)')
      ->from('user_register')
      ->where('hash=:hash',[':hash'=>$hash])
      ->queryScalar();

    return boolval($result);
  }
  /**
   * @param $step - integer
   * @param $data - array
   * @return array - errors
   * Проверка полей регистрации
   */
  public function setDataByStep($step, $data)
  {
    $arErrors = $arData = [];
    //
    if($step==1)
    {
      if(!in_array($data['type'],[UserProfile::$EMPLOYER, UserProfile::$APPLICANT]))
      {
        $arErrors['type'] = 'Неподходящий тип пользователя';
      }
      else
      {
        $arData['type'] = $data['type'];
      }
    }
    //
    if($step==2)
    {
      $field = Share::isApplicant($data['type']) ? 'Имя' : 'Название компании';
      $value = filter_var($data['name'],FILTER_SANITIZE_FULL_SPECIAL_CHARS);
      $value = trim($value);
      if(strlen($value) > self::$STRLENGTH)
      {
        $arErrors['name'] = "В поле '{$field}' указано слишком много символов";
      }
      elseif(!strlen($value))
      {
        $arErrors['name'] = "В поле '{$field}' есть некорректные символы или поле пустое";
      }
      else
      {
        $arData['name'] = $value;
      }

      if(Share::isApplicant($data['type']))
      {
        $value = filter_var($data['surname'],FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $value = trim($value);
        if(strlen($value) > self::$STRLENGTH)
        {
          $arErrors['surname'] = "В поле 'Фамилия' указано слишком много символов";
        }
        elseif(!strlen($value))
        {
          $arErrors['surname'] = "В поле 'Фамилия' есть некорректные символы или поле пустое";
        }
        else
        {
          $arData['surname'] = $value;
        }
      }

      if(!filter_var($data['login'],FILTER_VALIDATE_EMAIL))
      {
        $value = preg_replace("/[^0-9]/", '', $data['login']);
        if(strlen($value)==10) // RF
        {
          $data['login'] = '7' . $value;
        }
        elseif (strlen($value)==11 && in_array(strval($value){0}, ['7','8'])) // RF
        {
          $data['login'] = $value;
        }
        elseif(
          strlen($value)==13
          &&
          in_array(substr($value,0,3),['380','375'])
        ) // Ukraine | Belarus
        {
          $data['login'] = $value;
        }
        else
        {
          $arErrors['login'] = "Введите действительный номер мобильного телефона или эл. адрес";
        }
      }
      else
      {
        $arData['login'] = filter_var($data['login'],FILTER_SANITIZE_EMAIL);
      }
    }

    if(!count($arErrors) && !$this->setData($arData))
    {
      $arErrors['system'] = 'Ошибка записи данных';
    }
    return $arErrors;
  }
  /**
   * @param $arr - array(field => value)
   * @return bool
   * запись данных пользователя
   */
  private function setData($arr)
  {
    $rq = Yii::app()->request;
    if(!isset($rq->cookies['PHPSESSID']))
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
   * получение данных по юзеру
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