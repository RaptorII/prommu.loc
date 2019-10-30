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
  //
  public static $STEP_TYPE = 1;
  public static $STEP_LOGIN = 2;
  public static $STEP_CODE = 3;
  public static $STEP_PASSWORD = 4;
  public static $STEP_AVATAR = 5;
  // contact type
  public static $LOGIN_TYPE_EMAIL = 0;
  public static $LOGIN_TYPE_PHONE = 1;
  // image
  public static $DEFAULT_IMAGE_SIZE = 1600;
  public static $DIR_PERMISSIONS = 0755; // permission for dir in creating
  //
  public $step;
  public $user;
  public $data;
  public $errors;
  public $view;
  public $profile;

  function __construct()
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
      $this->user = md5(time() . rand(1111111,9999999) . self::$SALT);
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
   * удаляем шаг из куков
   */
  public static function clearStep()
  {
    unset(Yii::app()->request->cookies['urs']);
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
          'canal' => filter_var($post['canal'], FILTER_SANITIZE_FULL_SPECIAL_CHARS),
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
        // canal
        $arData['canal'] = explode(",", $arData['canal'])[0];
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
        $this->data = $arData;
      }
    }
    //
    if($this->step==self::$STEP_LOGIN)
    {
      $field = Share::isApplicant($this->data['type']) ? 'Имя' : 'Название компании';
      $this->data['name'] = filter_var($post['name'],FILTER_SANITIZE_FULL_SPECIAL_CHARS);
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
              if($this->data['id_user'])
              {
                $arData['id_user'] = false;
                $this->deleteNewUser();
              }
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
            if($this->data['id_user'])
            {
              $arData['id_user'] = false;
              $this->deleteNewUser();
            }
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
      if($value1 < self::$MIN_PASSWORD_LENGTH)
      {
        $this->errors['password'] = 'Пароль должен состоять минимум из шести символов';
      }
      elseif(!$this->data['id_user'])
      {
        $this->saveNewUser($value1);
      }
      $this->data['password'] = $post['password'];
      $this->data['r-password'] = $post['r-password'];
    }
    //
    if($this->step==self::$STEP_AVATAR)
    {
      if(Share::isApplicant($this->profile) && empty($this->profile->exInfo->photo))
      {
        $this->errors['avatar'] = 'Необходимо загрузить фото';
      }
      elseif(Share::isEmployer($this->profile) && empty($this->profile->exInfo->logo))
      {
        $this->errors['avatar'] = 'Необходимо загрузить фото';
      }
      else
      {
        // теперь можно и авторизовать
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
      $this->step==self::$STEP_AVATAR
        ? self::clearStep()
        : $this->setStep($this->step + 1);
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
        return MainConfig::$PAGE_REGISTER;
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
      $this->setStep(!empty($query['id_user']) ? self::$STEP_AVATAR : self::$STEP_PASSWORD);
      if( !$query['is_confirm'] )
      {
        $this->setData(['is_confirm'=>1, 'is_confirm_time'=>time()]);
      }
      return MainConfig::$PAGE_REGISTER;
    }
  }
  /**
   * @param $password
   * доведение до состояния старой регистрации
   */
  private function saveNewUser($password)
  {
    $arUser = $this->getData();
    $date = date('Y-m-d H:i:s');
    $name = (Share::isApplicant($arUser['type'])
      ? $arUser['name'] . ' ' . $arUser['surname']
      : $arUser['name']);
    // user
    $model = new User();
    $id_user = $model->registerUser([
      'login' => $arUser['login'],
      'passw' => md5($password), // !!!!!! небезопасно
      'email' => $arUser['login'],
      'status' => $arUser['type'],
      'isblocked' => $model::$ISBLOCKED_NOT_FULL_ACTIVE,
      'ismoder' => $model::$ISMODER_INACTIVE,
      'access_time' => $date,
      'crdate' => $date,
      'mdate' => $date,
      'confirmEmail' => $arUser['login_type']==self::$LOGIN_TYPE_EMAIL,
      'confirmPhone' => $arUser['login_type']==self::$LOGIN_TYPE_PHONE
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
      'active' => 0, // !!!
      'subdomen' => $arUser['subdomen'],
      'client' => $arUser['client'],
      'ip' => $arUser['ip'],
      'source' => $arUser['pm_source'],
    ]);
    // resume
    if(Share::isApplicant($arUser['type']))
    {
      $model = new Promo();
      $model->registerUser([
        'id_user' => $id_user,
        'firstname' => $arUser['name'],
        'lastname' => $arUser['surname'],
        'date_public' => $date,
        'mdate' => $date
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
   * удаление из старой системы всвязи со сменой логина
   */
  private function deleteNewUser()
  {
    // user
    $model = new User();
    $model->deleteRegisterUser($this->data['id_user']);
    // analytic
    $model = new Analytic();
    $model->deleteRegisterUser($this->data['id_user']);
    // resume
    if(Share::isApplicant($this->data['type']))
    {
      $model = new Promo();
      $model->deleteRegisterUser($this->data['id_user']);
    }
    // employer
    if(Share::isEmployer($this->data['type']))
    {
      $model = new Employer();
      $model->deleteRegisterUser($this->data['id_user']);
    }
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

    $arIdUser = $arData = [];
    foreach ($query as $user)
    {
      // неподтвержденные email-ы
      if(!$user['is_confirm'] && $user['login_type']==self::$LOGIN_TYPE_EMAIL)
      {
        $event = Share::isApplicant($user['type']) ? 28 : 29;
        Mailing::set($event,[
          'email_user' => $user['login'],
          'code_user' => $user['code'],
          'link_user' => self::getEmailLink($user)
        ]);
      }
      // собираем юзеров с id_user
      if(!empty($user['id_user']))
      {
        $arIdUser[] = $user['id_user'];
        $arData[$user['id_user']] = $user;
      }
    }

    if(!count($arIdUser))
      return false;

    $arIdUser = [21926];
    $arData = [];
    $arData[21926] = [
      'login_type' => self::$LOGIN_TYPE_EMAIL,
      'login' => 'deedddde16@gmail.com',
      'type' => 2,
      'token' => 'd09e4b34974b65ae1be0f619c37063c1'
    ];


    // проверка наличия фото у неполностью активных юзеров
    $query = Yii::app()->db->createCommand()
      ->select('up.id_user')
      ->from('user_photos up')
      ->join('user u','u.id_user=up.id_user')
      ->where([
        'and',
        [
          'u.isblocked=:isblocked',
          [':isblocked'=>User::$ISBLOCKED_NOT_FULL_ACTIVE]
        ],
        ['in','up.id_user',$arIdUser]
      ])
      ->group('id_user')
      ->queryColumn();

    $arPhoto = [];
    foreach ($arIdUser as $id_user)
    {
      if(
        !in_array($id_user,$query)
        &&
        $arData[$id_user]['login_type']==self::$LOGIN_TYPE_EMAIL
      )
      {
        $event = Share::isApplicant($arData[$id_user]['type']) ? 30 : 31;
        Mailing::set($event,[
          'email_user' => $arData[$id_user]['login'],
          'link_user' => self::getEmailNotificationLink(
            $arData[$id_user]['token'],
            self::$STEP_AVATAR
          )
        ]);
        $arPhoto[] = $id_user;
      }
    }
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
        if(
          !in_array($id_user,$arPhoto)
          &&
          $arData[$id_user]['login_type']==self::$LOGIN_TYPE_EMAIL
        )
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
		$result = $this->existenceDir($this->profile->filesRoot);
		if(!$result)
    {
      $arRes['error'][] = 'Ошибка сохранения, обратитесь к администратору';
      return $arRes['error'];
    }

		$mSize = $this->profile->arYiiUpload['maxFileSize'] * 1024 * 1024; // переводим в байты

    $fName = $_FILES['upload']['name'];
    $info = new SplFileInfo($fName);
    $type = mb_strtolower($info->getExtension());

    if($_FILES['upload']['error']) // ошибка передачи файла на сервер
    {
      $arRes['error'][] = "Ошибка передачи файла '{$fName}' на сервер";
    }
    if($_FILES['upload']['size']>$mSize) // ошибка передачи файла на сервер
    {
      $arRes['error'][] = "Размер файла '{$fName}' больше допустимого значения ("
        . $this->profile->arYiiUpload['maxFileSize'] . "Мб)";
    }

    if(!in_array($type,$this->profile->arYiiUpload['fileFormat'])) // проверяем формат на корректность
    {
      $arRes['error'][] = "У файла '{$fName}' некорректный формат";
    }

    $newName = date('YmdHis') . rand(1000,9999) . '.' . $type;
    $filePath = $this->profile->filesRoot . DS . $newName;
    $src = $this->profile->filesUrl . DS . $newName;
    $result = move_uploaded_file($_FILES['upload']["tmp_name"], $filePath);
    if($result) // файл успешно перемещен
    {
      $fSize = getimagesize($filePath);
      if($fSize)
      {
        $size = $this->profile->arYiiUpload['minImageSize']; // проверяем на минимальную ширину/высоту
        if($size>0 && ($fSize[0]<$size || $fSize[1]<$size))
        {
          $arRes['error'][] = "Файл '{$fName}' меньше допустимого значения ({$size}x{$size})";
          unlink($filePath);
        }
        $size = $this->profile->arYiiUpload['maxImageSize']; // проверяем на максимальную ширину/высоту
        if($size>0 && ($fSize[0]>$size || $fSize[1]>$size))
        {
          $arRes['error'][] = "Файл '{$fName}' больше допустимого значения ({$size}x{$size})";
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
      $arRes['error'][] = "Ошибка загрузки файла '{$fName}' на сервер";
    }

    return $arRes;
  }
  /**
   * @param $path - string
   */
  private function existenceDir($path)
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
   * @param $arParams - array()
   */
  public function editImage($arData)
  {
    $quality = 90;
    $arRes = ['error'=>[],'items'=>[]];

    $filePath = $this->profile->filesRoot . DS . $arData['name'];
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
    foreach ($this->profile->arYiiUpload['imgDimensions'] as $suffix => $size)
    {
      $this->resizeImage($inOutFile, "{$filePathWithoutExt}{$suffix}.jpg", $size);
      $arRes['items'][$suffix] = $this->profile->filesUrl . DS . $arRes['name'] . "{$suffix}.jpg";
    }
    $result = imagejpeg($image, $filePathWithoutExt . $this->profile->arYiiUpload['imgOrigSuFFix'] . ".jpg");
    $arRes['items'][$this->profile->arYiiUpload['imgOrigSuFFix']] = $this->profile->filesUrl
      . DS . $arRes['name'] . $this->profile->arYiiUpload['imgOrigSuFFix'] . ".jpg";

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

    $this->profile->saveRegisterPhoto($arRes['name']);

    return $arRes;
  }
  /**
   * @param $arData
   * @return array
   * метод для редактирования уже загруженного файла
   */
  public function onlyEditImage($arData)
  {
    $arData['name'] = date('YmdHis') . rand(1000,9999) . '.jpg';
    $filePathWithoutExt = $this->profile->filesRoot . DS . $arData['oldName'];
    $filePathFull = $this->profile->filesRoot
      . DS . $arData['oldName'] . $this->profile->arYiiUpload['imgOrigSuFFix'] . '.jpg';
    $filePath = $this->profile->filesRoot . DS . $arData['name'];
    $image = imagecreatefromjpeg($filePathFull);
    $result = imagejpeg($image, $filePath, 100);
    unlink($filePathFull);
    foreach ($this->profile->arYiiUpload['imgDimensions'] as $suffix => $size)
    {
      unlink($filePathWithoutExt . $suffix . '.jpg');
    }
    $arData['oldName'] .= '.jpg';

    return $this->editImage($arData);
  }
}