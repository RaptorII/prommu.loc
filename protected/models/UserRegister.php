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
  //
  public static $LOGIN_TYPE_EMAIL = 0;
  public static $LOGIN_TYPE_PHONE = 1;
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
        $arData = [
          'type' => $data['type'],
          'referer' => filter_var($data['referer'], FILTER_SANITIZE_FULL_SPECIAL_CHARS),
          'transition' => filter_var($data['transition'], FILTER_SANITIZE_FULL_SPECIAL_CHARS),
          'canal' => filter_var($data['canal'], FILTER_SANITIZE_FULL_SPECIAL_CHARS),
          'campaign' => filter_var($data['campaign'], FILTER_SANITIZE_FULL_SPECIAL_CHARS),
          'content' => filter_var($data['content'], FILTER_SANITIZE_FULL_SPECIAL_CHARS),
          'keywords' => filter_var($data['keywords'], FILTER_SANITIZE_FULL_SPECIAL_CHARS),
          'point' => filter_var($data['point'], FILTER_SANITIZE_FULL_SPECIAL_CHARS),
          'last_referer' => filter_var($data['last_referer'], FILTER_SANITIZE_FULL_SPECIAL_CHARS),
          'ip' => $data['ip'],
          'pm_source' => $data['pm_source'],
          'client' => $data['client'],
          'subdomen' => Subdomain::getId(),
          'date' => time()
        ];
        empty($arData['pm_source']) && $arData['pm_source']='none';
        $arData['client'] = substr($arData['client'], 6, 100);
        empty($arData['client']) && $arData['client'] = ' ';
        empty($arData['ip']) && $arData['ip'] = ' ';
      }
    }
    //
    if($step==2)
    {
      $arUser = $this->getData();
      $field = Share::isApplicant($arUser['type']) ? 'Имя' : 'Название компании';
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

      if(Share::isApplicant($arUser['type']))
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
        $isPhone = false;
        if(strlen($value)==10) // RF
        {
          $value = '7' . $value;
          $isPhone = true;
        }
        elseif (strlen($value)==11 && in_array(substr($value,0,1), ['7','8'])) // RF
        {
          $isPhone = true;
        }
        /*elseif(
          strlen($value)==13
          &&
          in_array(substr($value,0,3),['380','375'])
        ) // Ukraine | Belarus
        {
          $arData['login'] = $value;
          $arData['login_type'] = self::$LOGIN_TYPE_PHONE;
        }*/
        else
        {
          $arErrors['login'] = "Введите действительный номер мобильного телефона или эл. адрес";
        }

        $model = new User();
        $is_user = $model->checkLogin($value,$isPhone);
        if($is_user)
        {
          $arErrors['login'] = "Данный телефон уже используется. <a href=\""
            . MainConfig::$PAGE_LOGIN . "\">Авторизоваться</a>";
        }
        else
        {
          $arData['login'] = $value;
          $arData['login_type'] = self::$LOGIN_TYPE_PHONE;
          if($arUser['login']!=$value)
          {
            $arData['code'] = rand(1111, 9999);
            $arData['time_code'] = time();
          }
        }
      }
      else
      {
        $value = filter_var($data['login'],FILTER_SANITIZE_EMAIL);
        $model = new User();
        $is_user = $model->checkLogin($value);
        if($is_user)
        {
          $arErrors['login'] = "Данный эл. адрес уже используется. <a href=\""
            . MainConfig::$PAGE_LOGIN . "\">Авторизоваться</a>";
        }
        else
        {
          $arData['login'] = $value;
          $arData['login_type'] = self::$LOGIN_TYPE_PHONE;
          if($arUser['login']!=$value)
          {
            $arData['code'] = rand(1111, 9999);
            $arData['time_code'] = time();
          }
        }
      }
    }

    if(!count($arErrors))
    {
      $result = $this->setData($arData);
      if(!$result)
      {
        $arErrors['system'] = 'Ошибка записи данных';
      }
      elseif($step==2) // отправляем код для подтверждения
      {
        $this->sendCode();
      }
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
   * @return bool
   */
  public function deleteData()
  {
    $rq = Yii::app()->request;
    if(!isset($rq->cookies['PHPSESSID']))
      return false;

    $hash = $rq->cookies['PHPSESSID']->value;

    return Yii::app()->db->createCommand()
      ->delete('user_register','hash=:hash',[':hash'=>$hash]);
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

  private function sendCode()
  {
    /*$arData = $this->getData();
    $token = md5($arData['hash'] . time() . $arData['login']);
    $arGet = [
      'type' => $arData['type'],
      't' => $token,
      'referer' => $arData['referer'],
      'transition' => $arData['transition'],
      'canal' => $arData['canal'],
      'campaign' => $arData['campaign'],
      'content' => $arData['content'],
      'keywords' => $arData['keywords'],
      'point' => $arData['point'],
      'last_referer' => $arData['last_referer'],
      'ip' => $arData['ip'],
      'client' => $arData['client'],
      'pm' => $arData['pm_source'],
    ];
    $link  = Subdomain::site() . MainConfig::$PAGE_ACTIVATE . '?' . http_build_query($arGet);

    if(Share::isApplicant($arData['type']))
    {
      Mailing::set(27,[
        'user_link' =>  $link,

      ]);
    }
    elseif(Share::isEmployer($arData['type']))
    {

    }*/
  }
}