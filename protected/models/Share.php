<?php

class Share
{
  public static $arr_word = array();
  public static $arr_setup = array();
  public static $arr_lang = array();
  public static $lang = null;

  public static $dict = null; // для тестов
  public static $empl_type = array(0 => 'не важно', 1 => 'временная', 2 => 'постоянная'); // для тестов

//  public static $userType = 0; // определяем тип пользователя 0 - не авторизирован
  public static $isHomePage = 0; // устанавливается в случае вызова главной
  public static $isBreadcrumbs = 1; // выводить breadcrumbs
  public static $viewData = array(); // для передачи данных в layout
  /** @var  UserProfile */
  public static $UserProfile; // Модель пользователя
  public static $isAjaxRequest = 0;

  public static $cssAsset = []; // css из manifest-a



  public static function arraySearch($inArray, $inKey, $inVal)
  {
    foreach ($inArray as $key => $val)
    {
      if( $val[$inKey] == $inVal ) return $key;
    } // end foreach

    return false;
  }



  public function getLangAjax()
  {
    $lang = 'ru';
    if (isset($_GET['lang'])) {
      $lg = $_GET['lang'];
      if ($lg == 'ru' || $lg == 'en') $lang = $lg;
    }

    return $lang;
  }



  public function getLang()
  {
    $lang = 'ru';
    //print_r($_POST);die;
    if (isset($_POST['field_lang'])) {
      $lg = $_POST['field_lang'];
      if ($lg == 'ru' || $lg == 'en') $lang = $lg;
    } else {
      if (isset($_GET['lang'])) {
        $lg = $_GET['lang'];
        if ($lg == 'ru' || $lg == 'en') $lang = $lg;
      }
    }

    return $lang;
  }



  public function getMenuTypeAjax()
  {
    $mtype = 1;
    if (isset($_GET['menu_type'])) {
      if (is_numeric($_GET['menu_type'])) {
        $mtype = $_GET['menu_type'];
      }
    }

    return $mtype;
  }



  public function getMenuType()
  {
    $mtype = 1;
    if (isset($_POST['field_menu_type'])) {
      if ($_POST['field_menu_type']) $mtype = $_POST['field_menu_type'];
    }

    return $mtype;
  }



  // ������� �������������� ����.
  public static function data_convert($data, $year, $time, $second)
  {
    $res = "";
    $part = explode(" ", $data);
    $ymd = explode("-", $part[0]);
    $hms = explode(":", $part[1]);
    if ($year == 1) {
      $res .= $ymd[2];
      $res .= "." . $ymd[1];
      $res .= "." . $ymd[0];
    }
    if ($time == 1) {
      $res .= " " . $hms[0];
      $res .= ":" . $hms[1];
      if ($second == 1) $res .= ":" . $hms[2];
    }

    return $res;
  }



  //��������� URI
  public static function getModuleName()
  {
    $url = Yii::app()->getUrlManager()->parseUrl(Yii::app()->getRequest());
    $arr = explode('/', $url);

    return $arr;
  }



  public static function CheckName($text)
  {
    if (empty($text))
      return "[?]";
    else
      return $text;
  }



  public static function getLanguages($page_name, $lang)
  {
    $res = Yii::app()->db->createCommand()
      ->select('id, keyword, value, page, lang')
      ->from('languages')
      ->where('page=:page and lang=:lang', array(':page' => $page_name, ':lang' => $lang))
      ->queryAll();

    $arr = array();
    foreach ($res as $row) {
      $arr[$row['keyword']] = $row['value'];
    }
    self::$arr_word = $arr;

    return $arr;
  }



  public static function lword($key)
  {
    if (isset(self::$arr_word[$key]))
      return self::$arr_word[$key];
    else
      return "***";
  }



  public static function getLangBtn()
  {
    $res = Yii::app()->db->createCommand()
      ->select('name, title')
      ->from('lang')
      ->queryAll();

    return $res;
  }



  //-----CONFIG------
  public static function setup($key)
  {
    if (self::$arr_setup == null) self::config();

    return self::$arr_setup[$key];
  }



  public static function getExtend($filename)
  {
    //$ext = pathinfo($filename, PATHINFO_EXTENSION);
    $pos = strpos($filename, '.');
    $pos2 = strpos($filename, '/');
    if ($pos2 > 0)
      $ext = substr($filename, $pos + 1, $pos2 - $pos - 1);
    else
      $ext = substr($filename, $pos + 1);

    return $ext;
  }



  public static function config()
  {
    $url = $_SERVER['HTTP_HOST'];
    $zone = 'com'; //self::getExtend($url);
    $file = $_SERVER['DOCUMENT_ROOT'] . '/protected/config/setup_' . $zone . '.ini';
    $r = array();
    if ($F = fopen($file, "r")) {
      while (($line = fgets($F)) !== false) {
        list($k, $v) = explode("\t", $line, 2);
        $r[trim($k)] = trim($v);
      }
      fclose($F);
    }
    self::$arr_setup = $r;

    return $r;
  }



  //-----LANG---------
  public static function getLangSelected()
  {
    $lang = Yii::app()->session['lang'];
    if (empty($lang)) {
      $lang = self::setup('LANG_DEFAULT');
      Yii::app()->session['lang'] = self::setup('LANG_DEFAULT');
    }
    if (self::$lang != $lang) {
      self::$lang = $lang;
      self::getLangs();
    }

    //self::$lang = $lang;
    return $lang;
  }



  public static function lng($key)
  {
    if (self::$lang == null) self::getLangSelected();
    if (self::$arr_lang == null) self::getLangs();

    //print_r(self::$arr_lang);
    return self::$arr_lang[$key];
  }



  // �������� ������������ (����������� ����� VK, FB, ������������� ...)
  public static function getCID($cid, $cname)
  {
    Yii::app()->session['cid'] = $cid;
    Yii::app()->session['cname'] = $cname;

    $res = Yii::app()->db->createCommand()
      ->select('cid,cname,id_user,email')
      ->from('user')
      ->where('cid=:cid and cname=:cname', array(':cid' => $cid, ':cname' => $cname))
      ->limit(1)
      ->queryRow();

    return $res;
  }



  public static function getLangs()
  {
    $file = $_SERVER['DOCUMENT_ROOT'] . '/langs/' . self::$lang . '.dat';
    $r = array();
    if ($F = fopen($file, "r")) {
      while (($line = fgets($F)) !== false) {
        list($k, $v) = explode("\t", $line, 2);
        $r[trim($k)] = trim($v);
      }
      fclose($F);
    }
    self::$arr_lang = $r;

    return $r;
  }
  //-------------------

  /*
public static function getUserID()
{
   $cookie_uid = '';
   if(isset(Yii::app()->request->cookies['uid'])){
      $cookie_uid = Yii::app()->request->cookies['uid']->value;
   }
   if($cookie_uid=='') return 0;
        $r = Yii::app()->db->createCommand()
      ->select("id_user")
      ->from('user_work')
      ->where("uid = :uid", array(':uid'=>$cookie_uid))
      ->queryRow();
   return $r['id_user'];
}
  */

  /**  ����� ����������
   */
  public static function getTotalAnketsOfResume()
  {
    // ����� ���-�� ����� � ������
    $total_promo = 0;
    if (!isset(Yii::app()->session['total_promo'])) {
      $total_promo = Yii::app()->db->createCommand()
        ->select('count(*) as cnt')
        ->from('resume')
        ->queryScalar();
    } else {
      $total_promo = Yii::app()->session['total_promo'];
    }

    return $total_promo;
  }



  public static function getTotalAnketsOfVacation()
  {
    // ����� ���-�� ����� � ����������
    $total_vac = 0;
    if (!isset(Yii::app()->session['total_vac'])) {
      $total_vac = Yii::app()->db->createCommand()
        ->select('count(*) as cnt')
        ->from('jobs')
        ->where('ispublic=1')
        ->queryScalar();
    } else {
      $total_vac = Yii::app()->session['total_vac'];
    }

    return $total_vac;
  }



  public static function setProtected($msg)
  {
    $msg = str_replace("[u]", "<u>", $msg);
    $msg = str_replace("[U]", "<u>", $msg);
    $msg = str_replace("[i]", "<i>", $msg);
    $msg = str_replace("[I]", "<i>", $msg);
    $msg = str_replace("[b]", "<B>", $msg);
    $msg = str_replace("[B]", "<B>", $msg);
    $msg = str_replace("[sub]", "<SUB>", $msg);
    $msg = str_replace("[SUB]", "<SUB>", $msg);
    $msg = str_replace("[sup]", "<SUP>", $msg);
    $msg = str_replace("[SUP]", "<SUP>", $msg);
    $msg = str_replace("[/u]", "</u>", $msg);
    $msg = str_replace("[/U]", "</u>", $msg);
    $msg = str_replace("[/i]", "</i>", $msg);
    $msg = str_replace("[/I]", "</i>", $msg);
    $msg = str_replace("[/b]", "</B>", $msg);
    $msg = str_replace("[/B]", "</B>", $msg);
    $msg = str_replace("[/SUB]", "</SUB>", $msg);
    $msg = str_replace("[/sub]", "</SUB>", $msg);
    $msg = str_replace("[/SUP]", "</SUP>", $msg);
    $msg = str_replace("[/sup]", "</SUP>", $msg);
    //$msg = eregi_replace("(.*)\\[url\\](.*)\\[/url\\](.*)","\\1<a href=\\2>\\2</a>\\3",$msg);
    $msg = str_replace("\n", " ", $msg);
    $msg = str_replace("\r", " ", $msg);
    $msg = str_replace("'", '"', $msg);

    return $msg;
  }



  public static function getPageNames($arrLinks)
  {

    $sql = "SELECT p.id, p.link, pc.name FROM pages p LEFT JOIN pages_content pc ON pc.page_id=p.id WHERE pc.lang='ru' AND (";

    foreach ($arrLinks as $link) {
      $sql .= " OR p.link like '$link'";
    }

    $sql .= ")";

    $sql = str_replace('AND ( OR', 'AND (', $sql);

    $res = Yii::app()->db->createCommand($sql)->queryAll();

    // ��������� ������������� ������
    $arr = array();

    foreach ($res as $r) {
      $arr[$r['link']] = $r['name'];
    }

    return $arr;
  }



  public static function PrintMechJson()
  {
    $res = Yii::app()->db->createCommand()
      ->select("key, val as name")
      ->from('dictionary')
      ->where("grp='MECH'")
      ->queryAll();
    echo "var mechInfo = {";
    foreach ($res as $r) {
      echo '"' . $r["key"] . '":"' . $r["name"] . '", ';
    }
    echo "end:1};\r\n";
  }



// >>> Для тестов Вячеслав ---------------------------------------------------------------------
  public static function checkCity($city)
  {
    $city_new = mb_strtoupper(mb_substr($city, 0, 1, 'UTF-8'), 'UTF-8') . mb_strtolower(mb_substr($city, 1, strlen($city) - 1, 'UTF-8'), 'UTF-8');
    $id_city = 0;
    $id_city = Yii::app()->db->createCommand()
      ->select('id_city')
      ->from('city')
      ->where('`name`=:name', array(':name' => $city_new))
      ->queryScalar();

    if (empty($id_city)) {
      Yii::app()->db->createCommand()
        ->insert('city', array(
          'name' => $city_new,
        ));
      $id_city = Yii::app()->db->getLastInsertID();
    }

    return $id_city;
  }



  public static function dateFormatToMySql($date)
  {
    $dt = explode(".", $date);

    return date('Y-m-d', strtotime($dt[2] . '-' . $dt[1] . '-' . $dt[0]));
  }



  public static function initDict()
  {
    if (!empty(self::$dict)) {
      return false;
    } else {
      $res = Yii::app()->db->createCommand()
        ->select("id, key")
        ->from('user_attr_dict')
        ->where("id_par=:parent", array(':parent' => 0))
        ->order('key')
        ->queryAll();

      self::$dict = [];
      foreach ($res as $r) {
        if (!empty($r['key'])) {
          self::$dict[$r['key']] = $r['id'];
        }
      }
    }

    return true;
  }



  public static function getDirectory($dict_name)
  {
    self::initDict();
    $parent_id = self::$dict[$dict_name];
    $res = Yii::app()->db->createCommand()
      ->select("id, key, name")
      ->from('user_attr_dict')
      ->where("id_par=:parent", array(':parent' => $parent_id))
      ->order('id')
      ->queryAll();

    // print_r($res);die;
    $lst = [];
    foreach ($res as $r) {
      $lst[$r['id']] = $r['name'];
    }

    return $lst;
  }



  public static function convMintoHour($time)
  {
    $min = $time % 60;
    $time = floor($time / 60);

    return str_pad($time, 2, '0', STR_PAD_LEFT) . ':' . str_pad($min, 2, '0', STR_PAD_LEFT);
  }



  public static function GenerateDropDownList($lst_name, $r, $id_selected)
  {
    echo '<select name="' . $lst_name . '" id="' . $lst_name . '">';
    $i = 1;
    foreach ($r as $key => $value) {
      $select = ($id_selected == $key) ? 'selected' : '';
      echo '<option id="' . $lst_name . '_' . $i++ . '" value="$row" ' . $select . '>' . $value . '</option>';
    }
    echo '</select>';
  }
// <<< Для тестов ------------------------------------------------------------------------------


  /**
   * @deprecated
   */
  public static function mail($inMail, $inSubj, $inMess, $addParams)
  {
//        $addParams = (object)$addParams;
//        $headers[] = "Content-Type: text/html; charset=\"utf-8\"";
//        if( $addParams->from ) $headers[] = "From: {$addParams->from}";
//        mail($inMail, $inSubj, $inMess, join(" \r\n", $headers));
//        //."X-Mailer: PHP/" . phpversion()

    $SM = Yii::app()->swiftMailer;

    // Get config
    $mailHost = '127.0.0.1';
    $mailPort = 25; // Optional

    // New transport
    $Transport = $SM->smtpTransport($mailHost, $mailPort);

    $Mailer = $SM->mailer($Transport);

    $content = file_get_contents("protected/views/mails/letter.html");
    $content = str_replace('{{{BODY}}}', $inMess, $content);

    // Plain text content
    $plainTextContent = "";

    // New message
    $Message = $SM
      ->newMessage($inSubj)
      ->setFrom(array('auto-mailer@prommu.com' => 'Prommu.com'))
      //        ->setTo(array('Zotaper@localhost.com' => 'Recipient Name'))
      ->setTo(array('Zotaper@yandex.ru' => 'Recipient Name'))
      ->addPart($content, 'text/html')
      ->setBody($plainTextContent);

    // Send mail
    $result = $Mailer->send($Message);
  }



  /**
   * Функция отправки писем
   * @param $inMail string - адрессат
   * @param $inSubj string - тема
   * @param $inMess string - тело письма
   * @param $addParams array : (опционально)
   *      From - отправитель
   *      Cc - копия
   *      Bcc - скрытая копия
   */
  public static function sendmail($inMail, $inSubj, $inMess, $addParams = array())
  {
    if(!filter_var($inMail, FILTER_VALIDATE_EMAIL)) // !!! проверка формата почты
      return false;

    if( isset($addParams['From']) ) $from = $addParams['From'];
    else $from = array('auto-mailer@prommu.com' => 'Prommu.com');
//file_put_contents('_sendmail.txt', print_r($inMess, true), FILE_APPEND | LOCK_EX);
    $SM = Yii::app()->swiftMailer;

    // Get config
    $mailHost = 'mail.companyreport.net';
    $mailPort = 25; // Optional
    $sec = 'null';
    // New transport
    $Transport = $SM->smtpTransport($mailHost, $mailPort, $sec)
      ->setUsername('noreply@prommu.com')
      ->setPassword('1I1OD6iL');

    $Mailer = $SM->mailer($Transport);

    $content = file_get_contents(Yii::app()->basePath . "/views/mails/letter.html");
    $content = str_replace('{{{BODY}}}', $inMess, $content);
    $plainTextContent = ""; // Plain text content

    $Message = $SM
      ->newMessage($inSubj)
      ->setFrom($from)
      ->setTo(array($inMail => ''))
      ->addPart($content, 'text/html')
      ->setBody($plainTextContent);

    if( isset($addParams['Cc']) ) $Message->setCc($addParams['Cc']);
    if( isset($addParams['Bcc']) ) $Message->setBcc($addParams['Bcc']);

    // Send mail
    return $Mailer->send($Message);
  }



  /**
   *  get part of text
   *
   */
  public static function getShortText($inStr, $inLen, $inTripoints = 0)
  {
    if (strlen($inStr) <= $inLen) {
      //        return array($inStr, 0);
      return $inStr;
    }
    else
    {
      for ($i = $inLen; $i < strlen($inStr); $i++) {
        if ($inStr[$i] == " ") {
          //                return array(substr($in_str, 0, $i) . ($in_tripoints ? "..." : ""), 1);
          return substr($inStr, 0, $i) . ($inTripoints ?: "");
        }
      } // endfor
    }
  }



  public static function is_set($inObj, $inVal)
  {
    if( is_object($inObj) )
    {
      return property_exists($inObj, $inVal);
    }
    else return false;
  }



  public static function endingYears($num,$year=true)
  {
    $num = (int)$num;
    if ($num < 21 && $num > 4) return ($year ? 'лет' : 'дней');
    $num = $num%10;
    if ($num == 1) return ($year ? 'год' : 'день');
    if ($num > 1 && $num < 5) return ($year ? 'года' : 'дня');
    return ($year ? 'лет' : 'дней');
  }
  /**
   * @param $id_user integer - id_user
   * @param $type integer - user`s type
   * @param $photo string - id`s photo
   * @param $size string - ['small', 'medium', 'big']
   * @param $gender integer(0|1)
   * @return string
   * Доп метод для формирования ссылки на картинку
   */
  public static function getPhoto($id_user, $type, $photo, $size='small', $gender=0)
  {
    $suffix = '169.jpg';
    switch ($size)
    {
      case 'xsmall': $suffix = '30.jpg'; break;
      case 'small': $suffix = '169.jpg'; break;
      case 'xmedium': $suffix = '100.jpg'; break;
      case 'medium': $suffix = '400.jpg'; break;
      case 'big': $suffix = '000.jpg'; break;
    }
    $str = 'users/' . $id_user . DS . $photo . $suffix;
    $path = Settings::getFilesRoot() . $str;
    $src = Settings::getFilesUrl() . $str;
    if((!$photo || !file_exists($path)))
    {
      if(self::isApplicant($type))
      {
        $src = $gender
          ? MainConfig::$LOGO_APPLICANT_MALE
          : MainConfig::$LOGO_APPLICANT_FEMALE;
      }
      if(self::isEmployer($type))
      {
        $src = MainConfig::$LOGO_EMPLOYER;
      }
      $size=='big' && $src=false; // если фото нет то полноэкранно ничего не показываем
    }
    return $src;
  }
  /**
   * @param $arr array id_user`s
   * @return array
   * получить данные пользователей по массиву
   */
    public static function getUsers($arr) 
    {
        $arRes = $arT = array();
        $arr = array_unique($arr);

        foreach ($arr as $v)
            !empty($v) && $arT[] = $v;
        $arr = $arT;

        if(!count($arr) || !array_filter($arr))
            return $arRes;

        $sql = Yii::app()->db->createCommand()
                ->select("
                    u.id_user,
                    u.email,
                    u.status,
                    u.is_online, 
                    r.isman,
                    CONCAT(r.firstname,' ',r.lastname) app_name,
                    r.photo app_photo,
                    e.name emp_name,    
                    CONCAT(e.firstname,' ',e.lastname) emp_fio,  
                    e.logo emp_photo")
                ->from('user u')
                ->leftjoin('resume r','r.id_user=u.id_user')
                ->leftjoin('employer e','e.id_user=u.id_user')
                ->where(array('in','u.id_user',$arr))
                ->queryAll();

        foreach ($sql as $v)
        {
            if($v['status']==UserProfile::$APPLICANT)
            {
                $arRes[$v['id_user']] = array(
                        'id' => $v['id_user'],
                        'email' => $v['email'],
                        'status' => $v['status'],
                        'is_online' => $v['is_online'],
                        'name' => $v['app_name'],
                        'photo' => $v['app_photo'],
                        'src' => self::getPhoto($v['id_user'],2,$v['app_photo'],'small',$v['isman']),
                        'profile' => MainConfig::$PAGE_PROFILE_COMMON . DS . $v['id_user'],
                        'profile_admin' => '/admin/PromoEdit/' . $v['id_user'],
                        'fio' => $v['app_name'],
                    );
            }
            if($v['status']==UserProfile::$EMPLOYER)
            {
                $arRes[$v['id_user']] = array(
                        'id' => $v['id_user'],
                        'email' => $v['email'],
                        'status' => $v['status'],
                        'is_online' => $v['is_online'],
                        'name' => $v['emp_name'],
                        'photo' => $v['emp_photo'],
                        'src' => self::getPhoto($v['id_user'],3,$v['emp_photo']),
                        'profile' => MainConfig::$PAGE_PROFILE_COMMON . DS . $v['id_user'],
                        'profile_admin' => '/admin/EmplEdit/' . $v['id_user'],
                        'fio' => (!empty(trim($v['emp_fio']))
                        ? trim($v['emp_fio']) : '(без имени)')
                    );
            }

            if(isset($arRes[$v['id_user']]) && empty($arRes[$v['id_user']]['name']))
            {
                $arRes[$v['id_user']]['name'] = '(безымянный)';
            }
            if(in_array($v['id_user'], [1766,2054]))
            {
                $arRes[$v['id_user']]['name'] = 'Администрация Prommu';
                $arRes[$v['id_user']]['src'] = '/theme/pic/prommu-adm.jpg';
                unset($arRes[$v['id_user']]['profile']);
            }
        }
        return $arRes;
    }
  /**
   * @param $arr array id_user`s
   * @return array
   * получить данные пользователей по массиву
   */
  public static function getUsersApi($id)
  {
    $arRes = $arT = array();


    $sql = Yii::app()->db->createCommand()
      ->select("
                    u.id_user,
                    u.email,
                    u.status,
                    u.is_online, 
                    r.isman,
                    CONCAT(r.firstname,' ',r.lastname) app_name,
                    r.photo app_photo,
                    e.name emp_name,    
                    e.logo emp_photo")
      ->from('user u')
      ->leftjoin('resume r','r.id_user=u.id_user')
      ->leftjoin('employer e','e.id_user=u.id_user')
      ->where(
        'u.id_user=:id',
        array(':id'=>$id)
      )
      ->queryAll();

    foreach ($sql as $v)
    {
      if($v['status']==UserProfile::$APPLICANT)
      {
        $arRes = array(
          'id' => $v['id_user'],
          'status' => $v['status'],
          'is_online' => $v['is_online'],
          'name' => $v['app_name'],
          'src' =>"https://files_prommu.com/users/".$v['id_user']."/".$v['app_photo'].".jpg",

        );
      }
      if($v['status']==UserProfile::$EMPLOYER)
      {
        $arRes = array(
          'id' => $v['id_user'],
          'status' => $v['status'],
          'is_online' => $v['is_online'],
          'name' => $v['emp_name'],
          'src' =>"https://files_prommu.com/users/".$v['id_user']."/".$v['emp_photo'].".jpg",

        );
      }

      if(isset($arRes[$v['id_user']]) && empty($arRes[$v['id_user']]['name']))
      {
        $arRes[$v['id_user']]['name'] = '(безымянный)';
      }
      if(in_array($v['id_user'], [1766,2054]))
      {
        $arRes[$v['id_user']]['name'] = 'Администрация Prommu';
        $arRes[$v['id_user']]['src'] = '/theme/pic/prommu-adm.jpg';
        unset($arRes[$v['id_user']]['profile']);
      }
    }
    return $arRes;
  }

  /**
   * @param $date string - BD data
   * @param $time string - BD data
   * @param $time bolean
   * @return string
   * красивая дата
   */
  public static function getPrettyDate($date, $time=false, $long=false)
  {
    if(!$time)
    {
      $pieces = explode(' ', $date);
      $unixD = strtotime($pieces[0] . ' 00:00:00');
      $unixDT = strtotime($pieces[0] . ' ' . $pieces[1]);
    }
    else
    {
      $unixD = strtotime($date . ' 00:00:00');
      $unixDT = strtotime($date . ' ' . $time);
    }

    $unixCur = mktime(0,0,0);
    $resDate = date(($long ? 'H:i' : 'G:i'),$unixDT);
    $dateY = date('Y',$unixD);
    $arMonths = array(
      1=>'января',2=>'февраля',3=>'марта',
      4=>'апреля',5=>'мая',6=>'июня',
      7=>'июля',8=>'августа',9=>'сентября',
      10=>'октября',11=>'ноября',12=>'декабря'
    );

    if($unixCur==$unixD)
      $resDate .= '';
    elseif($long)
    {
      $resDate .= ' ' . date('d',$unixD) . '.' . date('m',$unixD);
      $resDate .= '.' . date('y',$unixD);
    }
    else
    {
      if($unixCur == ($unixD+(60*60*24)))
        $resDate .= ' вчера';
      else
        $resDate .= ' ' . date('d',$unixD) . ' '
          . $arMonths[date('n',$unixD)];

      date('Y')!=$dateY && ($resDate .= ' ' . $dateY);
    }

    return $resDate;
  }
  /**
   *  @param $arData - array(table => data)
   *  запись в одно обращение
   */
  public static function multipleInsert($arData)
  {
    foreach ($arData as $table => $arInsert)
      if(count($arInsert))
      {
        Yii::app()->db->schema->commandBuilder
          ->createMultipleInsertCommand($table, $arInsert)
          ->execute();
      }
  }
  /**
   * @return bool
   */
  public static function isApplicant($param=false)
  {
    $status = ($param ? $param : self::$UserProfile->type);
    return  $status==2 ? true : false;
  }
  /**
   * @return bool
   */
  public static function isEmployer($param=false)
  {
    $status = ($param ? $param : self::$UserProfile->type);
    return  $status==3 ? true : false;
  }
  /**
   *
   */
  public static function isGuest($param=false)
  {
    return !(self::isApplicant($param) || self::isEmployer($param));
  }
  /**
   *
   */
  public static function getRating($pos, $neg)
  {
    echo '<span class="js-g-hashint" title="Всего">' . ($pos+$neg)
      . '</span> ( <span class="-green js-g-hashint" title="Положительный">' . $pos
      . '</span> / <span class="-red js-g-hashint" title="Отрицательный">' . $neg . '</span> )';
  }
  /**
   * @param $date - integer (unix time)
   * @param $format - string
   */
  public static function getDate($date, $format = 'd.m.Y G:i')
  {
    return !empty($date) ? date($format, $date) : ' - ';
  }
  /**
   * @param $str - string (d.m.Y)
   * @return string db date
   */
  public static function checkFormatDate($str)
  {
    if(substr_count($str, '.')==2)
    {
      list($d, $m, $y) = explode('.', $str);
      if(checkdate($m, $d, $y))
      {
        return "$y-$m-$d";
      }
    }
    return false;
  }
  /**
   * @param $str string
   * @return array
   */
  public static function explode($str)
  {
    $arRes = [];
    if(strpos($str,',')===false)
    {
      $arRes[] = $str;
    }
    else
    {
      $arRes = explode(',',$str);
    }
    return $arRes;
  }
  /**
   * @param $prefix - псевдоним таблицы
   * @param $name - название поля таблицы
   * @param $p1 - параметр даты ОТ (GET | POST)
   * @param $p2 - параметр даты ДО (GET | POST)
   * @param $arr - массив, который отправляется в CDbCriteria->condition(ссылка)
   * @param bool $time - доп. параметр для полей БД без указания времени
   */
  public static function setDateQuery($prefix, $name, $p1, $p2, &$arr, $time=true)
  {
    $rq = Yii::app()->getRequest();
    $d1 = Share::checkFormatDate($rq->getParam($p1));
    $d2 = Share::checkFormatDate($rq->getParam($p2));
    if($d1 && $d2)
    {
      $arr[] = "{$prefix}.{$name} between '{$d1}" . ($time ? " 00:00:00'" : "'")
        . " and '{$d2}" . ($time ? " 23:59:59'" : "'");
    }
    elseif($d1)
    {
      $arr[] = "{$prefix}.{$name} >= '{$d1}" . ($time ? " 00:00:00'" : "'");
    }
    elseif($d2)
    {
      $arr[] = "{$prefix}.{$name} <= '{$d2}" . ($time ? " 23:59:59'" : "'");
    }
  }
  /**
   * @param $v
   * @return string
   * получаем номер телефона РФ
   */
  public static function getPrettyPhone($v)
  {
    $arResult = ['code' => '', 'phone' => ''];
    if(strlen($v)==16)
    {
      $arResult['code'] = substr($v,0,2);
      $arResult['phone'] = substr($v, 2);
    }
    if(strlen($v)==12)
    {
      $arResult['code'] = substr($v,0,2);
      $arResult['phone'] = '(' . substr($v,2,3) . ')'
        . substr($v,5,3) . '-'
        . substr($v,8,2) . '-'
        . substr($v,10,2);
    }
    elseif(strlen($v)==11)
    {
      $arResult['code'] = '+' . substr($v,0,1);
      $arResult['phone'] = '(' . substr($v,1,3) . ')'
        . substr($v,4,3) . '-'
        . substr($v,7,2) . '-'
        . substr($v,9,2);
    }

    return $arResult;
  }
  /**
   * @param bool $network
   * @return bool
   * верификация названия соцсети
   */
  public static function isSocialNetwork($network=false)
  {
    !$network && $network=Yii::app()->request->getQuery('service');

    return in_array($network,[
      'facebook',
      'vkontakte',
      'odnoklassniki',
      'google_oauth',
      'yandex_oauth'
    ]);
  }
  /**
   * @param $phone - string
   * @param $code - code
   * Отправка кода на телефон для верификации юзера
   */
  public static function sendSMSCode($phone, $code)
  {
    $arGet = ['phone' => $phone, 'code' => $code];
    $url = /*'https://prommu.com'*/Subdomain::domainSite() . MainConfig::$PAGE_SEND_SMS_CODE . '?' . http_build_query($arGet);
    file_get_contents($url);
  }
  /**
   * @param $text - string
   * Отправка сообщения в багчат Телеграм
   */
  public static function sendToTelegramBug($text)
  {
    $text = urlencode($text);
    $sendto = "https://api.telegram.org/bot525649107:AAFWUj7O8t6V-GGt3ldzP3QBEuZOzOz-ij8/sendMessage?chat_id=@prommubag&text=$text";
    file_get_contents($sendto);
  }

    /**
     * Transliterate Rus to En
     * @param $str
     * @return string
     */
    public static function transliterateRusToEn($str) {
        $str = mb_strtolower($str, "utf-8");
        $converter = array(
            'а' => 'a',   'б' => 'b',   'в' => 'v',
            'г' => 'g',   'д' => 'd',   'е' => 'e',
            'ё' => 'e',   'ж' => 'zh',  'з' => 'z',
            'и' => 'i',   'й' => 'y',   'к' => 'k',
            'л' => 'l',   'м' => 'm',   'н' => 'n',
            'о' => 'o',   'п' => 'p',   'р' => 'r',
            'с' => 's',   'т' => 't',   'у' => 'u',
            'ф' => 'f',   'х' => 'h',   'ц' => 'c',
            'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',
            'ь' => '\'',  'ы' => 'y',   'ъ' => '\'',
            'э' => 'e',   'ю' => 'yu',  'я' => 'ya',

            'А' => 'A',   'Б' => 'B',   'В' => 'V',
            'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
            'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z',
            'И' => 'I',   'Й' => 'Y',   'К' => 'K',
            'Л' => 'L',   'М' => 'M',   'Н' => 'N',
            'О' => 'O',   'П' => 'P',   'Р' => 'R',
            'С' => 'S',   'Т' => 'T',   'У' => 'U',
            'Ф' => 'F',   'Х' => 'H',   'Ц' => 'C',
            'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',
            'Ь' => '\'',  'Ы' => 'Y',   'Ъ' => '\'',
            'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',
        );
        return strtr($str, $converter);
    }
}
