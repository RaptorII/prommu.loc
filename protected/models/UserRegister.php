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
  // contact type
  public static $LOGIN_TYPE_EMAIL = 0;
  public static $LOGIN_TYPE_PHONE = 1;
  // image
  public static $MAX_FILE_SIZE = 10; // 10 Мб
  public static $FILE_FORMAT = ['jpg','jpeg','png'];
  public static $MIN_IMAGE_SIZE = 400;
  public static $MAX_IMAGE_SIZE = 4500;
  public static $DEFAULT_IMAGE_SIZE = 1600;
  public static $DIR_PERMISSIONS = 0755; // permission for dir in creating
  //
  public $step;
  public $user;
  public $data;
  public $filesRoot;
  public $filesUrl;

  function __construct()
  {
    $rq = Yii::app()->request;
    // step
    if(isset($rq->cookies['urs']))
    {
      $cookie = $rq->cookies['urs']->value;
      for ($i=1000; $i<=6000; $i+=1000)
      {
        if(md5($i . self::$SALT)==$cookie)
        {
          $this->step = intval($i/1000);
        }
      }
    }
    else
    {
      $this->setStep(1);
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
    //
    $this->data = $this->getData();
    //
    $this->filesRoot = Settings::getFilesRoot() . '/users/' . $this->data['id_user'];
    $this->filesUrl = Settings::getFilesUrl() . '/users/' . $this->data['id_user'];
  }
  /**
   * @param $step
   * устанавливаем новый шаг регистрации в куки
   */
  public function setStep($step)
  {
    $this->step = $step;
    $value = intval($step) * 1000;
    Yii::app()->request->cookies['urs'] = new CHttpCookie('urs', md5($value . self::$SALT));
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
          $arData['login_type'] = self::$LOGIN_TYPE_EMAIL;
          if($arUser['login']!=$value)
          {
            $arData['code'] = rand(1111, 9999);
            $arData['time_code'] = time();
            $arData['token'] = md5($arData['code'] . $arData['time_code'] . $this->user);
          }
        }
      }
    }
    //
    if($step==3)
    {
      $arUser = $this->getData();
      if(!$arUser['is_confirm'])
      {
        $data['code'] == $arUser['code']
          ? $arData = ['is_confirm'=>1, 'is_confirm_time'=>time()]
          : $arErrors['code'] = 'Введен некорректный код подтверждения';
      }
    }
    //
    if($step==4)
    {
      $value1 = filter_var($data['password'],FILTER_SANITIZE_FULL_SPECIAL_CHARS);
      $value2 = filter_var($data['r-password'],FILTER_SANITIZE_FULL_SPECIAL_CHARS);
      if($value1 != $value2)
      {
        $arErrors['r-password'] = 'Значения полей не совпадают';
      }
      if($value1 < self::$MIN_PASSWORD_LENGTH)
      {
        $arErrors['password'] = 'Пароль должен состоять минимум из шести символов';
      }
      else
      {
        $this->saveNewUser($value1);
      }
    }

    if(!count($arErrors))
    {
      $result = $this->setData($arData);
      if(!$result)
      {
        $arErrors['system'] = 'Ошибка записи данных';
      }
      elseif($step==2 && isset($arData['code'])) // отправляем код для подтверждения
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
      $arGet = [
        'type' => $arData['type'],
        't' => $arData['token'],
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

      Mailing::set((Share::isApplicant($arData['type']) ? 27 : 28), [
        'email_user' => $arData['login'],
        'code_user' => $arData['code'],
        'link_user' => Subdomain::site() . MainConfig::$PAGE_REGISTER . '?' . http_build_query($arGet),
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
    $arRes = [];
    $arRes['input'] = $this->getData();
    $arRes['time_to_repeat'] = $this->isTimeToRepeat($arRes['input']['time_code']);
    if(!$arRes['time_to_repeat'])
    {
      $time = time();
      $this->sendCode();
      $this->setData(['time_code'=>$time]);
      $arRes['time_to_repeat'] = $this->isTimeToRepeat($time);
    }
    return $arRes;
  }
  /**
   * @return bool
   * подтверждение с помощью токена
   */
  public function checkEmailLink()
  {
    $token = Yii::app()->getRequest()->getParam('t');
    if(empty($token))
      return false;

    $query = Yii::app()->db->createCommand()
      ->select('*')
      ->from('user_register')
      ->where('token=:token',[':token'=>$token])
      ->queryRow();

    if(!isset($query['id']))
      return false;

    $this->user = $query['user'];
    $this->setStep((!empty($query['id_user']) ? 5 : 4));
    if(!$query['is_confirm'])
    {
      $this->setData(['is_confirm'=>1, 'is_confirm_time'=>time()]);
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
    // user
    $model = new User();
    $id_user = $model->registerUser([
      'login' => $arUser['login'],
      'passw' => md5($password), // !!!!!! небезопасно
      'email' => $arUser['login'],
      'status' => $arUser['type'],
      'isblocked' => $model::$ISBLOCKED_EXPECT,
      'ismoder' => $model::$ISMODER_INACTIVE,
      'access_time' => $date,
      'crdate' => $date,
      'mdate' => $date
    ]);
    //
    $this->setData(['id_user'=>$id_user]);
    // analytic
    $model = new Analytic();
    $model->registerUser([
      'id_us' => $id_user,
      'name' => (Share::isApplicant($arUser['type'])
        ? $arUser['name'] . ' ' . $arUser['surname']
        : $arUser['name']),
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
  }














  //
  // !!!
  //
  public function saveImage()
  {
    $arRes = ['error'=>[]];
		$result = $this->existenceDir($this->filesRoot);
		if(!$result)
    {
      $arRes['error'][] = 'Ошибка сохранения, обратитесь к администратору';
      return $arRes['error'];
    }

		$mSize = self::$MAX_FILE_SIZE * 1024 * 1024; // переводим в байты

    $fName = $_FILES['upload']['name'];
    $info = new SplFileInfo($fName);
    $type = mb_strtolower($info->getExtension());

    if($_FILES['upload']['error']) // ошибка передачи файла на сервер
    {
      $arRes['error'][] = "Ошибка передачи файла '{$fName}' на сервер";
    }
    if($_FILES['upload']['size']>$mSize) // ошибка передачи файла на сервер
    {
      $arRes['error'][] = "Размер файла '{$fName}' больше допустимого значения (" . self::$MAX_FILE_SIZE . "Мб)";
    }

    if(!in_array($type,self::$FILE_FORMAT)) // проверяем формат на корректность
    {
      $arRes['error'][] = "У файла '{$fName}' некорректный формат";
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
        $size = self::$MIN_IMAGE_SIZE; // проверяем на минимальную ширину/высоту
        if($size>0 && ($fSize[0]<$size || $fSize[1]<$size))
        {
          $arRes['error'][] = "Файл '{$fName}' меньше допустимого значения ({$size}x{$size})";
          unlink($filePath);
        }
        $size = self::$MAX_IMAGE_SIZE; // проверяем на максимальную ширину/высоту
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
}