<?php
/**
 * Created by PhpStorm.
 * User: Dmitry Derevyanko
 * Date: 15.10.2019
 * Time: 12:37
 */

class UserRegister
{
  public static $SALT = 987654123;
  public static $STRLENGTH = 64;
  public static $MIN_PASSWORD_LENGTH = 6;
  public static $REPEAT_SEND_CODE_TIME = 120; // seconds before repeat sending confirm code
  public static $VIEW_TEMPLATE = '/user/register/step_';
  // Ступени регистрации (и поле user_register_page_cnt => page)
  public static $STEP_TYPE = 1; // выбор типа пользователя
  public static $STEP_LOGIN = 2; // ввод имени фамилии (название компании) и логина
  public static $STEP_CODE = 3; // ввод подтверждающего кода
  public static $STEP_PASSWORD = 4; // ввод пароля
  public static $STEP_AVATAR = 5; // установка аватара
  public static $PAGE_USER_LEAD = 6; // прокладочная страница /user/lead
  public static $PAGE_ACTIVE_PROFILE = 7; // прокладочная страница /user/active_profile
  //
  public static $URL_STEPS = [
    1 => 'type',
    2 => 'login',
    3 => 'code',
    4 => 'password',
    5 => 'avatar'
  ];
  // contact type
  public static $LOGIN_TYPE_EMAIL = 0;
  public static $LOGIN_TYPE_PHONE = 1;
  // image
  public static $DEFAULT_IMAGE_SIZE = 1600;
  public static $DIR_PERMISSIONS = 0755; // permission for dir in creating
  public static $EDIT_IMAGE_SUFFIX = '400'; // permission for dir in creating
  //
  public $step;
  public $user;
  public $data;
  public $errors;
  public $view;
  public $filesRoot;
  public $filesUrl;
  //
  //
  //
  public $profile;

  function __construct($id_user=false)
  {
    if($id_user) // только работа с аватаром
    {
      $this->step = false;
      $this->user = false;
      $this->errors = false;
      $this->view = false;
      $this->data['type'] = Share::$UserProfile->type;
      $this->setProfile($id_user);
      $this->filesRoot = $this->profile->filesRoot;
      $this->filesUrl = $this->profile->filesUrl;
    }
    else // полная регистрация
    {
      $rq = Yii::app()->request;
      // step
      if(isset($rq->cookies['urs']))
      {
        $this->step = self::getStep();
        if(!in_array(
          $this->step,
          [self::$STEP_TYPE,self::$STEP_LOGIN,self::$STEP_CODE,self::$STEP_PASSWORD,self::$STEP_AVATAR]
        ))
        {
          $this->step = 0;
          throw new CHttpException(404, 'Error');
        }
      }
      else
      {
        $this->setStep(self::$STEP_TYPE);
      }
      // user
      if(isset($rq->cookies['urh']))
      {
        $this->user = $rq->cookies['urh']->value;
      }
      else
      {
        $this->user = self::setUser();
        $rq->cookies['urh'] = new CHttpCookie('urh', $this->user);
      }
      // data
      $this->data = $this->getData();
      if(!is_array($this->data))
      {
        $this->setStep(self::$STEP_TYPE);
      }
      if(!Yii::app()->getRequest()->isAjaxRequest)
      {
        $pages = new PagesContent(); // страница с условиями пользования сайтом
        $lang = Yii::app()->session['lang'];
        $this->data['condition'] = $pages->getPageContent('conditions',$lang);
      }
      $this->data['time_to_repeat'] = $this->isTimeToRepeat($this->data['time_code']);
      // view
      $this->view = self::$VIEW_TEMPLATE . $this->step;
      // profile
      $this->setProfile();
      //
      $this->errors = [];
      //
      $this->filesRoot = Settings::getFilesRoot() . 'registers/' . $this->data['id'];
      $this->filesUrl = Settings::getFilesUrl() . 'registers/' . $this->data['id'];
    }
  }
  /**
   * @param $step
   * устанавливаем новый шаг регистрации в куки
   */
  public function setStep($step)
  {
    $this->step = $step;
    $this->view = self::$VIEW_TEMPLATE . $step;
    $value = intval($step) * 1000;
    Yii::app()->request->cookies['urs'] = new CHttpCookie('urs', md5($value . self::$SALT));
  }
  /**
   * создаем значение urh для куки
   */
  public static function setUser()
  {
    return md5(time() . rand(1111111,9999999) . self::$SALT);
  }
  /**
   * @param $step - int
   * @return int
   */
  public static function getStep($step = false)
  {
    $result = 1;
    if($step)
    {
      for ($i=1000; $i<=6000; $i+=1000)
      {
        if(md5($i . self::$SALT)==$step)
        {
          $result = intval($i/1000);
        }
      }
    }
    elseif(isset(Yii::app()->request->cookies['urs']))
    {
      $cookie = Yii::app()->request->cookies['urs']->value;
      for ($i=1000; $i<=6000; $i+=1000)
      {
        if(md5($i . self::$SALT)==$cookie)
        {
          $result = intval($i/1000);
        }
      }
    }
    return $result;
  }
  /**
   * удаляем процесс регистрации
   */
  public static function clearRegister()
  {
    self::clearStep();
    self::clearUser();
  }
  /**
   * удаляем шаг из куков
   */
  public static function clearStep()
  {
    unset(Yii::app()->request->cookies['urs']);
  }
  /**
   * удаляем идентификатор юзера из куков
   */
  public static function clearUser()
  {
    unset(Yii::app()->request->cookies['urh']);
  }
  /**
   * @return bool
   * проверка запуска процесса регистрации
   */
  public static function beginRegister()
  {
    $rq = Yii::app()->request;
    if(!isset($rq->cookies['urh']))
      return false;

    $result = Yii::app()->db->createCommand()
      ->select('count(id)')
      ->from('user_register')
      ->where(
        'user=:user',
        [':user'=>$rq->cookies['urh']->value]
      )
      ->queryScalar();

    return boolval($result);
  }

  private function setProfile($id_user=false)
  {
    if(!$id_user && !$this->data['user'])
      return false;

    $id_user && $this->data['id_user'] = $id_user;

    $this->profile = (Share::isApplicant($this->data['type'])
      ? new UserProfileApplic(['id'=>$this->data['id_user']])
      : new UserProfileEmpl(['id'=>$this->data['id_user']]));
    $this->profile instanceof UserProfile && $this->profile->setUserData();
  }
  /**
   * @return array - errors
   * Проверка полей регистрации
   */
  public function setDataByStep($post)
  {
    $arData = [];
    //
    if($this->step==self::$STEP_TYPE)
    {
      if(!in_array($post['type'],[UserProfile::$EMPLOYER, UserProfile::$APPLICANT]))
      {
        $this->errors['type'] = 'Неподходящий тип пользователя';
      }
      else
      {
        $arData = [
          'type' => $post['type'],
          'referer' => filter_var($post['referer'], FILTER_SANITIZE_FULL_SPECIAL_CHARS),
          'transition' => filter_var($post['transition'], FILTER_SANITIZE_FULL_SPECIAL_CHARS),
          'canal' => '',//filter_var($post['canal'], FILTER_SANITIZE_FULL_SPECIAL_CHARS),
          'campaign' => filter_var($post['campaign'], FILTER_SANITIZE_FULL_SPECIAL_CHARS),
          'content' => filter_var($post['content'], FILTER_SANITIZE_FULL_SPECIAL_CHARS),
          'keywords' => filter_var($post['keywords'], FILTER_SANITIZE_FULL_SPECIAL_CHARS),
          'point' => filter_var($post['point'], FILTER_SANITIZE_FULL_SPECIAL_CHARS),
          'last_referer' => filter_var($post['last_referer'], FILTER_SANITIZE_FULL_SPECIAL_CHARS),
          'ip' => $post['ip'],
          'pm_source' => $post['pm_source'],
          'client' => $post['client'],
          'subdomen' => Subdomain::getId(),
          'date' => time()
        ];
        // transition
        $arData['transition'] = explode(",", $arData['transition'])[0];
        // campaign
        $arData['campaign'] = explode(",", $arData['campaign'])[0];
        // content
        $arData['content'] = explode(",", $arData['content'])[0];
        // keywords
        $model = new Auth();
        $arData['keywords'] = $model->encoderSys($arData['keywords']);
        // pm_source
        empty($arData['pm_source']) && $arData['pm_source']='none';
        // client
        $arData['client'] = substr($arData['client'], 6, 100);
        empty($arData['client']) && $arData['client'] = ' ';
        // ip
        $ips  = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote  = @$_SERVER['REMOTE_ADDR'];
        if(filter_var($ips, FILTER_VALIDATE_IP))
        {
          $arData['ip'] = $ips;
        }
        elseif(filter_var($forward, FILTER_VALIDATE_IP))
        {
          $arData['ip'] = $forward;
        }
        else
        {
          $arData['ip'] = $remote;
        }
        empty($arData['ip']) && $arData['ip'] = ' ';
        //
        //
        // правила от Кутишевского
        if(!empty($arData['transition'])) // utm_source - not empty
        {
          if(
            strripos($arData['transition'],'facebook')!==false
            ||
            strripos($arData['transition'],'vk')!==false
            ||
            strripos($arData['transition'],'smm_mytarget')!==false
            ||
            strripos($arData['transition'],'smm_yellowjob')!==false
            ||
            strripos($arData['transition'],'instagram')!==false
          )
          {
            $arData['canal'] = 'smm';
            $arData['transition']=='facebook_smm' && $arData['transition']='facebook';
          }
          else if(
            strripos($arData['transition'],'yandex')!==false
            ||
            strripos($arData['transition'],'google')!==false
          )
          {
            $arData['canal'] = 'ppc';
          }
        }
        else  // utm_source - empty
        {
          if (empty($arData['last_referer']) || $arData['last_referer']=='(none)')
          {
            $arData['canal'] = 'direct';
          }
          else if (
            strripos($arData['last_referer'], 'yandex') !== false
            ||
            strripos($arData['last_referer'], 'google') !== false
          )
          {
            $arData['canal'] = 'organic';
          }
          elseif(strripos($arData['last_referer'],'away.vk.com')!==false)
          {
            $arData['canal'] = 'smm';
          }
          else
          {
            $arData['canal'] = 'referal';
          }
        }
        // Продолжение
        empty($arData['canal']) && $arData['canal'] = 'direct';
        // устанавливаем Тип трафика
        switch ($arData['canal'])
        {
          case 'smm': $arData['referer']='cpc'; break;
          case 'direct': $arData['referer']='typein'; break;
          case 'referal': $arData['referer']='referal'; break;
          case 'organic': $arData['referer']='search'; break;
          default: break;
        }
        // устанавливаем Источник
        ($arData['transition']=='https:' || $arData['transition']=='http:') && $arData['transition']='direct';
        if(empty($arData['transition']))
        {
          if($arData['canal']=='direct')
          {
            $arData['transition']='direct';
          }
          elseif ($arData['canal']=='referal')
          {
            if(empty($arData['last_referer']) || $arData['last_referer']=='(none)')
            {
              $arData['transition']='direct';
            }
            else // вытягиваем домен
            {
              $uri = strtolower(trim($arData['last_referer']));
              if(strripos($uri,'https')!==false)
              {
                $uri = preg_replace('%^(https:\/\/)*(www.)*%usi','',$uri);
              }
              else
              {
                $uri = preg_replace('%^(http:\/\/)*(www.)*%usi','',$uri);
              }
              $arData['transition'] = preg_replace('%\/.*$%usi','',$uri);
            }
          }
          elseif ($arData['canal']=='organic')
          {
            if(strripos($arData['last_referer'], 'yandex') !== false)
            {
              $arData['transition'] = 'yandex';
            }
            elseif(strripos($arData['last_referer'], 'google') !== false)
            {
              $arData['transition'] = 'google';
            }
            else
            {
              $arData['transition']='search';
            }
          }
          else
          {
            $arData['transition']='(none)';
          }
        }
        //
        foreach ($arData as $k => $v)
        {
          $this->data[$k] = $v;
        }
      }
    }
    //
    if($this->step==self::$STEP_LOGIN)
    {
      $field = Share::isApplicant($this->data['type']) ? 'Имя' : 'Название компании';
      $this->data['name'] = filter_var($post['name'],FILTER_SANITIZE_FULL_SPECIAL_CHARS);
      if(Share::isApplicant($this->data['type']))
      {
        $this->data['name'] = preg_replace('/[^a-zA-Zа-яА-ЯЁё]/ui', '',$this->data['name']);
      }
      $this->data['name'] = trim($this->data['name']);
      if(strlen($this->data['name']) > self::$STRLENGTH)
      {
        $this->errors['name'] = "В поле '{$field}' указано слишком много символов";
      }
      elseif(!strlen($this->data['name']))
      {
        $this->errors['name'] = "В поле '{$field}' есть некорректные символы или поле пустое";
      }
      else
      {
        $arData['name'] = $this->data['name'];
      }

      if(Share::isApplicant($this->data['type']))
      {
        $this->data['surname'] = filter_var($post['surname'],FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $this->data['surname'] = preg_replace('/[^a-zA-Zа-яА-ЯЁё]/ui', '',$this->data['surname']);
        $this->data['surname'] = trim($this->data['surname']);
        if(strlen($this->data['surname']) > self::$STRLENGTH)
        {
          $this->errors['surname'] = "В поле 'Фамилия' указано слишком много символов";
        }
        elseif(!strlen($this->data['surname']))
        {
          $this->errors['surname'] = "В поле 'Фамилия' есть некорректные символы или поле пустое";
        }
        else
        {
          $arData['surname'] = $this->data['surname'];
        }
      }

      if(!filter_var($post['login'],FILTER_VALIDATE_EMAIL))
      {
        $value = preg_replace("/[^0-9]/", '', $post['login']);
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
          $this->data['login'] = $arData['login'];
          $arData['login_type'] = self::$LOGIN_TYPE_PHONE;
          $this->data['login_type'] = $arData['login_type'];
        }*/
        else
        {
          $isPhone = false;
          $this->errors['login'] = "Введите действительный номер мобильного телефона или эл. адрес";
        }

        if($isPhone)
        {
          $model = new User();
          $is_user = $model->checkLogin($value,$isPhone);
          if($is_user)
          {
            $this->errors['login'] = "Данный телефон уже используется. <a href=\""
              . MainConfig::$PAGE_LOGIN . "\">Авторизоваться</a>";
          }
          else
          {
            $arData['login'] = $value;
            $arData['login_type'] = self::$LOGIN_TYPE_PHONE;
            $this->data['login_type'] = $arData['login_type'];
            if($this->data['login']!=$value)
            {
              $arData['code'] = rand(1111, 9999);
              $this->data['code'] = '';
              $arData['time_code'] = time();
              $this->data['time_to_repeat'] = $this->isTimeToRepeat($arData['time_code']);
              $arData['is_confirm'] = 0;
              $arData['is_confirm_time'] = false;
            }
          }
        }
        $this->data['login'] = $value;
      }
      else
      {
        $value = filter_var($post['login'],FILTER_SANITIZE_EMAIL);
        $model = new User();
        $is_user = $model->checkLogin($value);
        if($is_user)
        {
          $this->errors['login'] = "Данный эл. адрес уже используется. <a href=\""
            . MainConfig::$PAGE_LOGIN . "\">Авторизоваться</a>";
        }
        else
        {
          $arData['login'] = $value;
          $arData['login_type'] = self::$LOGIN_TYPE_EMAIL;
          $this->data['login_type'] = $arData['login_type'];
          if($this->data['login']!=$value)
          {
            $arData['code'] = rand(1111, 9999);
            $this->data['code'] = '';
            $arData['time_code'] = time();
            $this->data['time_to_repeat'] = $this->isTimeToRepeat($arData['time_code']);
            $arData['token'] = md5($arData['code'] . $arData['time_code'] . $this->user);
            $arData['is_confirm'] = 0;
            $arData['is_confirm_time'] = false;
          }
        }
        $this->data['login'] = $value;
      }
    }
    //
    if($this->step==self::$STEP_CODE && !$this->data['is_confirm'])
    {
      if($this->data['code'] == $post['code'])
      {
        $arData['is_confirm'] = 1;
        $this->data['is_confirm'] = 1;
        $arData['is_confirm_time'] = time();
        $this->data['is_confirm_time'] = $arData['is_confirm_time'];
      }
      else
      {
        $this->errors['code'] = 'Введен некорректный код подтверждения';
      }
      $this->data['code'] = $post['code'];
    }
    //
    if($this->step==self::$STEP_PASSWORD)
    {
      $value1 = filter_var($post['password'],FILTER_SANITIZE_FULL_SPECIAL_CHARS);
      $value2 = filter_var($post['r-password'],FILTER_SANITIZE_FULL_SPECIAL_CHARS);
      if($value1 != $value2)
      {
        $this->errors['r-password'] = 'Значения полей не совпадают';
      }
      if(strlen($value1) < self::$MIN_PASSWORD_LENGTH)
      {
        $this->errors['password'] = 'Пароль должен состоять минимум из шести символов';
      }
      else
      {
        $arData['password'] = md5($value1); // !!!!!! небезопасно
      }
      $this->data['password'] = $post['password'];
      $this->data['r-password'] = $post['r-password'];
    }
    //
    if($this->step==self::$STEP_AVATAR)
    {
      if(empty($this->data['avatar']))
      {
        $this->errors['avatar'] = 'Необходимо загрузить фото';
      }
      else
      {
        // теперь можно и в лиды
        $this->saveNewUser();
        $this->saveNewUserPhoto();
        $model = new Auth();
        $model->Authorize(['id'=>$this->profile->id]);
      }
    }

    if(!count($this->errors))
    {
      $result = count($arData) ? $this->setData($arData) : true;
      if(!$result)
      {
        $this->errors['system'] = 'Ошибка записи данных';
      }
      elseif($this->step==self::$STEP_LOGIN && isset($arData['code'])) // отправляем код для подтверждения
      {
        $this->sendCode();
      }
      // переход на следующую ступень(или выход)
      if($this->step==self::$STEP_AVATAR)
      {
        self::clearRegister();
        $this->step = false;
      }
      else
      {
        $this->setStep($this->step + 1);
      }
    }
  }
  /**
   * @param $arr - array(field => value)
   * @return bool
   * запись данных пользователя
   */
  private function setData($arr)
  {
    $arUser = $this->getData();
    if(intval($arUser['id']))
    {
      $this->deleteData();
      foreach ($arUser as $key => $v)
      {
        !isset($arr[$key]) && $arr[$key] = $v;
      }
    }

    $arr['user'] = $this->user;

    return Yii::app()->db->createCommand()
      ->insert('user_register',$arr);
  }
  /**
   * @return bool
   */
  public function deleteData()
  {
    return Yii::app()->db->createCommand()
      ->delete('user_register','user=:user',[':user'=>$this->user]);
  }
  /**
   * @return mixed
   * получение данных по юзеру
   */
  public function getData()
  {
    return Yii::app()->db->createCommand()
      ->select('*')
      ->from('user_register')
      ->where('user=:user',[':user'=>$this->user])
      ->queryRow();
  }
  /**
   * отправка кода для подтверждания
   */
  private function sendCode()
  {
    $arData = $this->getData();
    // email
    if($arData['login_type']==self::$LOGIN_TYPE_EMAIL)
    {
      Mailing::set((Share::isApplicant($arData['type']) ? 28 : 29), [
        'email_user' => $arData['login'],
        'code_user' => $arData['code'],
        'link_user' => self::getEmailLink($arData),
        'posts_list' => '<li>' . implode('</li><li>', Vacancy::getPostsList()) . '</li>'
      ]);
    }
    // email
    if($arData['login_type']==self::$LOGIN_TYPE_PHONE)
    {
      $arGet = ['phone' => $arData['login'], 'code' => $arData['code']];
      //$log = date('d.m.Y H:i') . ' id=' . $arData['id'] . ' phone=' . $arData['login'] . ' code=' . $arData['code'] . '   ';
      //file_put_contents(__DIR__ . "/_log_UserRegister_phone.txt", print_r($log, true), FILE_APPEND);
      file_get_contents(Subdomain::site() . MainConfig::$PAGE_SEND_SMS_CODE . '?' . http_build_query($arGet));
    }
  }
  /**
   * @param $data - array (user_register row)
   */
  private static function getEmailLink($data)
  {
    $arGet = [
      'type' => $data['type'],
      't' => $data['token'],
      'referer' => $data['referer'],
      'transition' => $data['transition'],
      'canal' => $data['canal'],
      'campaign' => $data['campaign'],
      'content' => $data['content'],
      'keywords' => $data['keywords'],
      'point' => $data['point'],
      'last_referer' => $data['last_referer'],
      'ip' => $data['ip'],
      'client' => $data['client'],
      'pm' => $data['pm_source'],
    ];
    return Subdomain::site() . MainConfig::$PAGE_REGISTER
      . '?' . http_build_query($arGet);
  }
  /**
   * @param $token - string
   * @param $step - int | string
   * @return string
   */
  private static function getEmailNotificationLink($token, $step)
  {
    $arGet = [
      't' => $token,
      's' => md5($step . self::$SALT)
    ];
    return Subdomain::site() . MainConfig::$PAGE_REGISTER
      . '?' . http_build_query($arGet);
  }
  /**
   * @param $time - unix
   * @return bool
   */
  public function isTimeToRepeat($time)
  {
    $timer = $time + self::$REPEAT_SEND_CODE_TIME;
    $curTime = time();
    return (($timer > $curTime) ? ($timer - $curTime) : 0);
  }
  /**
   * @return array
   * Повтор отправки кода подтверждения
   */
  public function repeatSendCode()
  {
    if( !$this->data['time_to_repeat'] && !$this->data['is_confirm'] )
    {
      $time = time();
      $this->sendCode();
      $this->setData(['time_code'=>$time]);
      $this->data['time_to_repeat'] = $this->isTimeToRepeat($time);
    }
  }
  /**
   * @return bool
   * подтверждение с помощью токена
   */
  public function checkEmailLink()
  {
    $token = Yii::app()->getRequest()->getParam('t');
    $step = Yii::app()->getRequest()->getParam('s');
    if(empty($token))
      return false;

    $query = Yii::app()->db->createCommand()
      ->select('*')
      ->from('user_register')
      ->where('token=:token',[':token'=>$token])
      ->queryRow();

    if(!isset($query['id']))
      return false;

    if(!empty($step))
    {
      if($step==md5(self::$STEP_AVATAR . self::$SALT))
      {
        $this->user = $query['user'];
        Yii::app()->request->cookies['urh'] = new CHttpCookie('urh', $this->user);
        $this->setStep(self::$STEP_AVATAR);
        return MainConfig::$PAGE_REGISTER . DS . self::$URL_STEPS[self::$STEP_AVATAR];
      }
      elseif ($step==md5('profile' . self::$SALT))
      {
        $model = new Auth();
        $model->Authorize(['id'=>$query['id_user']]);
        return MainConfig::$PAGE_PROFILE;
      }
      else
      {
        return false;
      }
    }
    else
    {
      $this->user = $query['user'];
      Yii::app()->request->cookies['urh'] = new CHttpCookie('urh', $this->user);
      $this->setStep(empty($query['password']) ? self::$STEP_PASSWORD : self::$STEP_AVATAR);
      if( !$query['is_confirm'] )
      {
        $this->setData(['is_confirm'=>1, 'is_confirm_time'=>time()]);
      }
      return MainConfig::$PAGE_REGISTER . DS
        . self::$URL_STEPS[(empty($query['password']) ? self::$STEP_PASSWORD : self::$STEP_AVATAR)];
    }
  }
  /**
   * @param $arSocial - array ([messenger => user>messenger, birthday => birthday])
   * доведение до состояния старой регистрации
   */
  private function saveNewUser($arSocial=['messenger'=>'','birthday'=>null,'gender'=>0])
  {
    $arUser = $this->getData();

    $arUser['client'] = Yii::app()->db->createCommand()
      ->select("client")
      ->from('user_client a')
      ->where('a.ip=:ip', [':ip'=>$arUser['ip']])
      ->queryScalar();

    $date = date('Y-m-d H:i:s');
    $name = (Share::isApplicant($arUser['type'])
      ? $arUser['name'] . ' ' . $arUser['surname']
      : $arUser['name']);
    // user
    $model = new User();
    $is_user = $model->checkLogin($arUser['login']);
    if($is_user>0 && $arUser['id_user']==$is_user)
    {
      return;
    }
    //
    $id_user = $model->registerUser([
      'login' => $arUser['login'],
      'passw' => $arUser['password'],
      'email' => $arUser['login'],
      'status' => $arUser['type'],
      'isblocked' => $model::$ISBLOCKED_NOT_FULL_ACTIVE,
      'ismoder' => $model::$ISMODER_INACTIVE,
      'access_time' => $date,
      'crdate' => $date,
      'mdate' => $date,
      'confirmEmail' => $arUser['login_type']==self::$LOGIN_TYPE_EMAIL,
      'confirmPhone' => $arUser['login_type']==self::$LOGIN_TYPE_PHONE,
      'messenger' => $arSocial['messenger'],
      'ip' => $arUser['ip'] // чтоб при выгрузке не перекрывались поля
    ]);
    //
    $this->setData(['id_user'=>$id_user]);
    // analytic
    $model = new Analytic();
    $model->registerUser([
      'id_us' => $id_user,
      'name' => $name,
      'date' =>  date('Y-m-d H:i:s', $arUser['date']),
      'type' => $arUser['type'],
      'referer' => $arUser['referer'],
      'canal' => $arUser['canal'],
      'campaign' => $arUser['campaign'],
      'content' => $arUser['content'],
      'keywords' => $arUser['keywords'],
      'transition' => $arUser['transition'],
      'point' => $arUser['point'],
      'last_referer' => $arUser['last_referer'],
      'active' => 1, // !!!
      'subdomen' => $arUser['subdomen'],
      'client' => $arUser['client'],
      'ip' => $arUser['ip'],
      'source' => $arUser['pm_source'],
    ]);
    // user_activate
    $model = new Auth();
    $arJson = [
      'canal' => $arUser['canal'],
      'referer' => $arUser['referer'],
      'transition' => $arUser['transition'],
      'campaign' => $arUser['campaign'],
      'content' => $arUser['content'],
      'keywords' => $arUser['keywords'],
      'point' => $arUser['point'],
      'last_referer' => $arUser['last_referer'],
      'email' => $arUser['login'],
      'fname' => $arUser['name'],
      'lname' => $arUser['surname'],
      'name'  => $arUser['name'],
      'messenger' => $arSocial['messenger'],
      'type' => $arUser['type']
    ];

    $model->userActivateInsertUpdate([
      'id_user' => $id_user,
      'token' => md5($arUser['login'] . $date . $arUser['password']),
      'data' => json_encode($arJson),
      'dt_create' => $date,
    ]);
    // resume
    if(Share::isApplicant($arUser['type']))
    {
      if($arSocial['birthday']!=null)
      {
        $d1 = DateTime::createFromFormat('Y-m-d', $arSocial['birthday']);
        $d2 = DateTime::createFromFormat('j.n.Y', $arSocial['birthday']);
        if($d1 && $d1->format('Y-m-d') === $arSocial['birthday'])
        {
          $arSocial['birthday'] = $d1->format('Y-m-d');
        }
        elseif($d2 && $d2->format('j.n.Y') === $arSocial['birthday'])
        {
          $arSocial['birthday'] = $d2->format('Y-m-d');
        }
      }

      $model = new Promo();
      $model->registerUser([
        'id_user' => $id_user,
        'firstname' => (!empty($arUser['name']) ? $arUser['name'] : ''),
        'lastname' => (!empty($arUser['surname']) ? $arUser['surname'] : ''),
        'date_public' => $date,
        'mdate' => $date,
        'isblocked' => User::$ISBLOCKED_NOT_FULL_ACTIVE,
        'birthday' => $arSocial['birthday']
      ]);
    }
    // employer
    if(Share::isEmployer($arUser['type']))
    {
      $model = new Employer();
      $model->registerUser([
        'id_user' => $id_user,
        'name' => $arUser['name'],
        'type' => $model::$TYPE_DIRECT_EMPLOYER,
        'crdate' => $date,
        'isblocked' => User::$ISBLOCKED_NOT_FULL_ACTIVE
      ]);
    }
    //
    $this->setProfile($id_user);
    // письмо админу
    Mailing::set(27,[
      'id_user' => $id_user,
      'email_user' => $arUser['login_type']==self::$LOGIN_TYPE_EMAIL
        ? $arUser['login'] : 'нет',
      'phone_user' => $arUser['login_type']==self::$LOGIN_TYPE_PHONE
        ? $arUser['login'] : 'нет',
      'type_user' => Share::isApplicant($arUser['type'])
        ? 'Соискатель' : 'Работодатель',
      'name_user' => $name,
      'referer_seo' => $arUser['referer'],
      'transition_seo' => $arUser['transition'],
      'canal_seo' => $arUser['canal'],
      'campaign_seo' => $arUser['campaign'],
      'content_seo' => $arUser['content'],
      'keywords_seo' => $arUser['keywords'],
      'point_seo' => $arUser['point'],
      'l_referer_seo' => $arUser['last_referer'],
      'ip_seo' => $arUser['ip'],
      'ga_seo' => $arUser['client'],
      'pm_source_seo' => $arUser['pm_source'],
      'subdomain_seo' => Subdomain::getSiteName(),
    ],$arUser['type']);
  }
  /**
   * Метод для крона, для отправки писем юзерам с незавершенной регистрацией
   */
  public static function setRegisterNotificetions()
  {
    $d1 = strtotime('-2 days');
    $d2 = strtotime('-1 days');
    $query = Yii::app()->db->createCommand()
      ->select('*')
      ->from('user_register')
      ->where(
        'date BETWEEN :date1 AND :date2',
        [':date1'=>$d1, ':date2'=>$d2]
      )
      ->queryAll();

    if(!count($query))
      return false;

    $arIdUser = $arData = $arPhoto = [];

    foreach ($query as $user)
    {
      if($user['login_type']!=self::$LOGIN_TYPE_EMAIL)
        continue;

      // неподтвержденные email-ы
      if(!$user['is_confirm'])
      {
        $event = Share::isApplicant($user['type']) ? 28 : 29;
        Mailing::set($event,[
          'email_user' => $user['login'],
          'code_user' => $user['code'],
          'link_user' => self::getEmailLink($user)
        ]);
      }
      // собираем юзеров для напоминалки по фото
      if($user['is_confirm'] && empty($user['avatar']) && empty($user['id_user']))
      {
        $arPhoto[] = $user;
      }
      // собираем юзеров с id_user
      if(!empty($user['id_user']))
      {
        $arIdUser[] = $user['id_user'];
        $arData[$user['id_user']] = $user;
      }
    }
    //
    // проверка наличия фото у неполностью активных юзеров
    if(count($arPhoto))
    {
      foreach ($arPhoto as $user)
      {
        $event = Share::isApplicant($user['type']) ? 30 : 31;
        Mailing::set($event,[
          'email_user' => $user['login'],
          'link_user' => self::getEmailNotificationLink(
            $user['token'],
            self::$STEP_AVATAR
          )
        ]);
      }
    }
    //
    // проверка неполностью активных пользователей
    $arNotActive = Yii::app()->db->createCommand()
      ->select('id_user')
      ->from('user')
      ->where([
        'and',
        [
          'isblocked=:isblocked',
          [':isblocked'=>User::$ISBLOCKED_NOT_FULL_ACTIVE]
        ],
        ['in','id_user',$arIdUser]
      ])
      ->queryColumn();

    if(count($arNotActive))
    {
      foreach ($arNotActive as $id_user)
      {
        $event = Share::isApplicant($arData[$id_user]['type']) ? 32 : 33;
        Mailing::set($event,[
          'email_user' => $arData[$id_user]['login'],
          'link_user' => self::getEmailNotificationLink(
            $arData[$id_user]['token'],
            'profile'
          )
        ]);
      }
    }
  }

  public function saveNewUserFromSocialNetwork($arr)
  {
    $loginType = filter_var($arr['email'],FILTER_VALIDATE_EMAIL);
    $code =  rand(1111, 9999);
    $time = time();

    $this->setData([
      'login' => $arr['login'],
      'login_type' => $loginType,
      'name' => $arr['name'],
      'surname' => $arr['surname'],
      'code' => $code,
      'token' => md5($code . $time . $this->user),
      'time_code' => $time,
      'is_confirm' => 1,
      'is_confirm_time' => $time,
      'social' => 1,
      'password' => md5($arr['password']) // !!!!!! небезопасно
    ]);

    $this->saveNewUser([
      'messenger' => $arr['messenger'],
      'birthday' => $arr['birthday'],
      'gender' => $arr['gender']
    ]);
    return $this->getData();
  }
  //
  //
  //
  // !!!
  //
  //
  //
  public function saveImage()
  {
    $arRes = ['error'=>[]];
    $result = $this->existenceDir($this->filesRoot);
    if(!$result)
    {
      $arRes['error'][] = 'Ошибка сохранения, обратитесь к администратору';
      return $arRes;
    }

		$mSize = UserProfile::$MAX_FILE_SIZE * 1024 * 1024; // переводим в байты

    $fName = $_FILES['upload']['name'];
    $info = new SplFileInfo($fName);
    $type = mb_strtolower($info->getExtension());

    if($_FILES['upload']['error']) // ошибка передачи файла на сервер
    {
      $arRes['error'][] = "Ошибка передачи файла на сервер";
    }
    else
    {
      if($_FILES['upload']['size']>$mSize) // ошибка передачи файла на сервер
      {
        $arRes['error'][] = "Размер файла больше допустимого значения ("
          . UserProfile::$MAX_FILE_SIZE . "Мб)";
      }

      if(!in_array($type,UserProfile::$AR_FILE_FORMAT)) // проверяем формат на корректность
      {
        $arRes['error'][] = "У файла некорректный формат";
      }

      $newName = date('YmdHis') . rand(1000,9999) . '.' . $type;
      $filePath = $this->filesRoot . DS . $newName;
      $src = $this->filesUrl . DS . $newName;
      $result = move_uploaded_file($_FILES['upload']["tmp_name"], $filePath);
      if($result) // файл успешно перемещен
      {
        $fSize = getimagesize($filePath);
        if($fSize)
        {
          $size = UserProfile::$MIN_IMAGE_SIZE; // проверяем на минимальную ширину/высоту
          if($size>0 && ($fSize[0]<$size || $fSize[1]<$size))
          {
            $arRes['error'][] = "Файл меньше допустимого значения ({$size}x{$size})";
            unlink($filePath);
          }
          $size = UserProfile::$MAX_IMAGE_SIZE; // проверяем на максимальную ширину/высоту
          if($size>0 && ($fSize[0]>$size || $fSize[1]>$size))
          {
            $arRes['error'][] = "Файл больше допустимого значения ({$size}x{$size})";
            unlink($filePath);
          }
          $this->resizeImage($filePath, $filePath, self::$DEFAULT_IMAGE_SIZE); // сжимаем до допустимых размеров
        }
        $arRes['success'] = [
          'name' => $newName,
          'oldname' => $fName,
          'path' => $src
        ];
      }
      else
      {
        $arRes['error'][] = "Ошибка загрузки файла на сервер";
      }
    }

    return $arRes;
  }
  /**
   * @param $path - string
   */
  public function existenceDir($path)
  {
    $arPath = explode('/',$path);
    $dirPath = '';
    $arRes = true;
    for ($i=0, $n=count($arPath); $i<$n; $i++)
    {
      $dirPath .= $arPath[$i] . '/';
      if(!is_dir($dirPath))
      {
        $arRes = mkdir($dirPath, self::$DIR_PERMISSIONS);
      }
      if(!$arRes)
        break;
    }
    return $arRes;
  }
  /**
   * @param $inPath - string, path to file input
   * @param $outPath - string, path to file output
   * @param $size - integer, maximum size
   */
  private function resizeImage($inPath, $outPath, $size)
  {
    $quality = 90;
    $imgProps = getimagesize($inPath); // Get dimensions
    list($oldW, $oldH) = $imgProps;
    $ratioOrig = $oldW / $oldH;

    if( $ratioOrig > 1 ) // альбомный
    {
      $newW = ($oldW>$size) ? $size : $oldW;
      $newH = ($oldW>$size) ? ($newW/$ratioOrig) : $oldH;
    }
    else // портретный
    {
      $newH = ($oldH>$size) ? $size : $oldH;
      $newW = ($oldH>$size) ? ($newH*$ratioOrig) : $oldW;
    }
    //  Создание нового полноцветного изображения
    $image_p = imagecreatetruecolor($newW, $newH);

    switch($imgProps['mime'])
    {
      case "image/jpeg": $image = imagecreatefromjpeg($inPath); break;
      case "image/pjpeg": $image = imagecreatefromjpeg($inPath); break;
      case "image/png": $image = imagecreatefrompng($inPath); break;
      case "image/x-png": $image = imagecreatefrompng($inPath); break;
      case "image/gif": $image = imagecreatefromgif($inPath); break;
      default: return; break;
    }
    // Копирование и изменение размера изображения с ресемплированием
    $result = imagecopyresampled($image_p, $image, 0, 0, 0, 0, $newW, $newH, $oldW, $oldH);
    $result = imagejpeg($image_p, $outPath, $quality); // записываем изображение в файл
    imagedestroy($image_p);
    imagedestroy($image);
  }
  /**
   * @param $arData - array()
   * @param $arDimensions - array(suffix => dimensions)
   * @param $arParams - array()
   */
  public function editImage($arData)
  {
    $quality = 90;
    $arRes = ['error'=>[],'items'=>[]];
    $arDimensions = (!empty($this->data['id_user'])
      ? $this->profile->arYiiUpload['imgDimensions']
      : [self::$EDIT_IMAGE_SUFFIX => intval(self::$EDIT_IMAGE_SUFFIX)]);
    $filePath = $this->filesRoot . DS . $arData['name'];
    $info = new SplFileInfo($arData['name']);
    $type = mb_strtolower($info->getExtension());
    $typeLen = strlen($type) + 1; // прибавляем точку
    $filePathWithoutExt = substr($filePath, 0, (strlen($filePath)-$typeLen)); // without '.<type>'
    $inOutFile = $filePathWithoutExt . 'tmp.jpg';

    $image = imagecreatefromjpeg($filePath);

    $imgProps = getimagesize($filePath);
    list($oldW, $oldH) = $imgProps; // get old width and old height
    $srcImgW = $oldW;
    $srcImgH = $oldH;
    $tmpImgW = $arData['width'];
    $tmpImgH = $arData['height'];
    $degrees = $arData['rotate'];
    $srcX = $arData['x'];
    $srcY = $arData['y'];

    // Rotate the source image
    if(is_numeric($degrees) && $degrees != 0)
    {
      // PHP's degrees is opposite to CSS's degrees
      $image = imagerotate( $image, -$degrees, 0);
      $deg = abs($degrees) % 180;
      $arc = ($deg > 90 ? (180 - $deg) : $deg) * M_PI / 180;
      $srcImgW = $oldW * cos($arc) + $oldH * sin($arc);
      $srcImgH = $oldW * sin($arc) + $oldH * cos($arc);
      // Fix rotated image miss 1px issue when degrees < 0
      $srcImgW -= 1;
      $srcImgH -= 1;
    }

    if($srcX <= -$tmpImgW || $srcX > $srcImgW)
    {
      $srcX = $srcW = $dstX = $dstW = 0;
    }
    elseif($srcX <= 0)
    {
      $dstX = -$srcX;
      $srcX = 0;
      $srcW = $dstW = min($srcImgW, $tmpImgW + $srcX);
    }
    elseif($srcX <= $srcImgW)
    {
      $dstX = 0;
      $srcW = $dstW = min($tmpImgW, $srcImgW - $srcX);
    }

    if($srcW <= 0 || $srcY <= -$tmpImgH || $srcY > $srcImgH)
    {
      $srcY = $srcH = $dstY = $dstH = 0;
    }
    elseif($srcY <= 0)
    {
      $dstY = -$srcY;
      $srcY = 0;
      $srcH = $dstH = min($srcImgH, $tmpImgH + $srcY);
    }
    elseif($srcY <= $srcImgH)
    {
      $dstY = 0;
      $srcH = $dstH = min($tmpImgH, $srcImgH - $srcY);
    }
    // Scale to destination position and size
    $ratio = $tmpImgW / $dstW;
    $dstX /= $ratio;
    $dstY /= $ratio;
    $dstW /= $ratio;
    $dstH /= $ratio;
    //  Создание нового полноцветного изображения
    $dstImage = imagecreatetruecolor($dstW, $dstH);
    // Add transparent background to destination image
    imagefill($dstImage, 0, 0, imagecolorallocate($dstImage, 255, 255, 255));
    // Копирование и изменение размера изображения с ресемплированием
    $result = imagecopyresampled($dstImage, $image, $dstX, $dstY, $srcX, $srcY, $dstW, $dstH, $srcW, $srcH);
    $result = imagejpeg($dstImage, $inOutFile, $quality); // записываем изображение в файл
    $arRes['name'] = pathinfo($arData['name'], PATHINFO_FILENAME);
    foreach ($arDimensions as $suffix => $size)
    {
      $this->resizeImage($inOutFile, "{$filePathWithoutExt}{$suffix}.jpg", $size);
      $arRes['items'][$suffix] = $this->filesUrl . DS . $arRes['name'] . "{$suffix}.jpg";
    }
    $result = imagejpeg($image, $filePathWithoutExt . UserProfile::$ORIGINAL_IMAGE_SUFFIX . ".jpg");
    $arRes['items'][UserProfile::$ORIGINAL_IMAGE_SUFFIX] = $this->filesUrl
      . DS . $arRes['name'] . UserProfile::$ORIGINAL_IMAGE_SUFFIX . ".jpg";

    imagedestroy($image);
    imagedestroy($dstImage);
    unlink($filePath);
    unlink($inOutFile);
    // удаляем весь ранее созданный хлам
    /*
    $glob = glob($this->profile->filesRoot . DS);
    foreach ($glob as $file)
    {
      file_put_contents(__DIR__ . "/_user_log.txt", print_r($file, true), FILE_APPEND);
      //unlink($file);
    }
    */
    if(!empty($this->data['id_user']))
    {
      $this->profile->saveRegisterPhoto($arRes['name']);
    }
    else
    {
      $this->setData(['avatar' => $arRes['name']]);
    }

    return $arRes;
  }
  /**
   * @param $arData
   * @return array
   * метод для редактирования уже загруженного файла
   */
  public function onlyEditImage($arData)
  {
    $arDimensions = (!empty($this->data['id_user'])
      ? $this->profile->arYiiUpload['imgDimensions']
      : [self::$EDIT_IMAGE_SUFFIX => intval(self::$EDIT_IMAGE_SUFFIX)]);
    $arData['name'] = date('YmdHis') . rand(1000,9999) . '.jpg';
    $filePathWithoutExt = $this->filesRoot . DS . $arData['oldName'];
    $filePathFull = $this->filesRoot
      . DS . $arData['oldName'] . UserProfile::$ORIGINAL_IMAGE_SUFFIX . '.jpg';
    $filePath = $this->filesRoot . DS . $arData['name'];
    $image = imagecreatefromjpeg($filePathFull);
    $result = imagejpeg($image, $filePath, 100);
    unlink($filePathFull);
    foreach ($arDimensions as $suffix => $size)
    {
      unlink($filePathWithoutExt . $suffix . '.jpg');
    }
    $arData['oldName'] .= '.jpg';

    return $this->editImage($arData);
  }

  public function deleteImage($file)
  {
    $path = $this->filesRoot . DS . $file;

    if(file_exists($path))
    {
      unlink($path);
    }
  }
  /**
   * Перенос фоток с регистрации в профиль
   */
  private function saveNewUserPhoto()
  {
    $oldUserImagePath = $this->filesRoot . DS . $this->data['avatar']; // путь к картинкам реги
    $userImagePath = $this->profile->arYiiUpload['filePath'] . DS . $this->data['avatar']; // путь к картинкам юзера
    $this->existenceDir($this->profile->arYiiUpload['filePath']); // создаем директорию при необходимости
    // делаем все форматы обрезанного изображения согласно типа профиля
    foreach ($this->profile->arYiiUpload['imgDimensions'] as $suffix => $size)
    {
      $this->resizeImage(
        $oldUserImagePath . self::$EDIT_IMAGE_SUFFIX . '.jpg',
        $userImagePath . "{$suffix}.jpg",
        $size
      );
    }
    // переносим оригинал изображения в директорию изображений юзера
    $fullImage = UserProfile::$ORIGINAL_IMAGE_SUFFIX . ".jpg";
    $image = imagecreatefromjpeg($oldUserImagePath . $fullImage);
    imagejpeg($image, $userImagePath . $fullImage);
    // удаляем изображения с директории реги
    unlink($oldUserImagePath . $suffix . '.jpg');
    unlink($oldUserImagePath . $fullImage);
    // сохраняем в профиле новое фото
    $this->profile->saveRegisterPhoto($this->data['avatar']);
  }
  /**
   * @param $arr
   * @return array|CDbDataReader
   * лиды для АБ тестирования
   */
  public static function getLeadByHashArray($arr)
  {
    return Yii::app()->db->createCommand()
      ->select('user')
      ->from('user_register')
      ->where([
        'and',
        'id_user IS NOT NULL',
        ['in','user',$arr]
      ])
      ->queryColumn();
  }
}
