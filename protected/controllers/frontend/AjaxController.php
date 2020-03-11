<?php

class AjaxController extends AppController
{
    public $layout = '//layouts/ajax';


    function __construct($id, $module = null)
    {
        parent::__construct($id, $module);

        // проверка авторизации
//        $this->doAuth();

        Share::$isAjaxRequest = 1;
    }
    /**
     * фиксируем в БД клиент ID гугл и яндекса
     */
    public function actionCreateClient()
    {
      $rq = Yii::app()->getRequest();
      $ips  = @$_SERVER['HTTP_CLIENT_IP'];
      $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
      $remote  = @$_SERVER['REMOTE_ADDR'];
        
      if(filter_var($ips, FILTER_VALIDATE_IP)) $ip = $ips;
      elseif(filter_var($forward, FILTER_VALIDATE_IP)) $ip = $forward;
      else $ip = $remote;

      $arRes = [
        'ip'=>$ip,
        'client'=>$rq->getParam('ga') ? $rq->getParam('ga') : $rq->getParam('client')
      ];
      if(!empty($rq->getParam('ym')))
      {
        $arRes['ym_client'] = $rq->getParam('ym');
      }
      $urh = Yii::app()->request->cookies['urh']->value;
      if(!empty($urh))
      {
        $arRes['user'] = $urh;
      }
      if(empty($arRes['client'])) // каким-то непонятным образом проскакивают юзеры без клиента
      {
        Yii::app()->end();
        file_put_contents('client2.txt', date('d.m.Y H:i')."\n".print_r($arRes,true)."\n", FILE_APPEND | LOCK_EX);
        return;
      }

      file_put_contents('client.txt', date('d.m.Y H:i')."\t".' client - '.$arRes['client'].' ip - '.$ip."\n", FILE_APPEND | LOCK_EX);
        
      $query = Yii::app()->db->createCommand()
        ->select("*")
        ->from('user_client')
        ->where('ip=:ip', [':ip'=>$ip])
        ->queryRow();
                
      if(!$query)
      {
         Yii::app()->db->createCommand()
           ->insert('user_client', $arRes);
      }
      else
      {
        if($arRes['client']!=$query['client'])
        {
          $arRes['is_send_to_ga'] = 0;
        }
        Yii::app()->db->createCommand()
          ->update('user_client', $arRes, 'ip like :ip', [':ip'=>$ip]);
      }
      Yii::app()->session['is_set_client'] = true;
      Yii::app()->end();
    }
    // actionIndex вызывается всегда, когда action не указан явно.
    function actionIndex()
    {
        $input = Yii::app()->request->getPost('input');
        //$input = '123';
        // для примера будем приводить строку к верхнему регистру
        $output = mb_strtoupper($input, 'utf-8');

        // если запрос асинхронный, то нам нужно отдать только данные
        if (Yii::app()->request->isAjaxRequest) {
            echo CHtml::encode($output);
            // Завершаем приложение
            Yii::app()->end();
        } else {
            // если запрос не асинхронный, отдаём форму полностью
            $this->render('//site/menuTree/_form', array(
                'input' => $input,
                'output' => $output,
            ));
        }
    }



    /**
     * Отправка лого на сервер
     */
    public function actionPostLogoFile()
    {
        if( in_array(Share::$UserProfile->type, [2,3]) )
        {
            $res = (new UploadLogo())->processUploadedLogoFile();
            echo CJSON::encode($res);
        } // endif
        Yii::app()->end();
    }
  /**
   *  верификация контакта, отправка кода (почты/телефона)
   */
  public function actionRestorecode()
  {
    if(Share::isGuest())
      return false;

    $code = rand(1000, 9999);
    $phone = Yii::app()->getRequest()->getParam('phone');
    $email = Yii::app()->getRequest()->getParam('email');
    $arInsert = ['code' => $code, 'date' => date('Y-m-d H:i:s')];

    if(!empty($email))
    {
      $arInsert['email'] = $email;
      // Письмо с кодом для пользователя
      Mailing::set(24, ['email_user'=>$email, 'code'=>$code]);
      $delCond = 'email=:type';
      $arDelParams = [':type'=>$email];
    }
    elseif(!empty($phone))
    {
      $arInsert['phone'] = $phone;

      $post = http_build_query(['phone'=>$phone, 'code'=>$code]);
      $ch = curl_init("https://prommu.com/api.teles_test");
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
      $response = curl_exec($ch);
      curl_close($ch);
      echo CJSON::encode($response);
      $delCond = 'phone=:type';
      $arDelParams = [':type'=>$phone];
    }

    if(isset($arInsert['email']) || isset($arInsert['phone']))
    {
      Yii::app()->db->createCommand()->delete('activate', $delCond, $arDelParams);
      Yii::app()->db->createCommand()->insert('activate', $arInsert);
    }
  }
  /**
   *  верификация контакта, проверка кода (почты/телефона)
   */
  public function actionConfirm()
  {
    if(Share::isGuest())
      return false;

    $rq = Yii::app()->getRequest();
    $code = $rq->getParam('code');
    $phone = $rq->getParam('phone');
    $email = $rq->getParam('email');
    $result = 100;

    if(!empty($email))
    {
      $query = Yii::app()->db->createCommand()
        ->select('id')
        ->from('activate')
        ->where(
          'email=:email and code=:code',
          [':email'=>$email, ':code'=>$code]
        )
        ->queryScalar();

      if($query)
      {
        Yii::app()->db->createCommand()->update(
          'user',
          ['confirmEmail'=>1],
          'email=:email',
          [':email'=>$email]
        );
        $result = 200;
      }
    }
    elseif(!empty($phone))
    {
      $query = Yii::app()->db->createCommand()
        ->select('id')
        ->from('activate')
        ->where(
          'phone=:phone and code=:code',
          [':phone'=>$phone, ':code'=>$code]
        )
        ->queryScalar();

      if($query)
      {
        Yii::app()->db->createCommand()->update(
          'user',
          ['confirmPhone'=>1, 'login'=>"+".$phone],
          'id_user=:id_user',
          [':id_user' => Share::$UserProfile->id]
        );
        $result = 200;
      }
    }
    echo CJSON::encode(['code'=>$result]);
    Yii::app()->end();
  }




    /**
     * Отправка файлов на сервер
     */
    public function actionUploaduni()
    {
        $fn = filter_var(Yii::app()->getRequest()->getParam('fn'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $op = Yii::app()->getRequest()->getParam('op');

        $Upluni = (new Uploaduni());
        if( $op == '1' ) $res = $Upluni->processUploadedFile($fn);
        elseif( $op == '2' ) $res = $Upluni->deleteFile();

        echo CJSON::encode($res);
        Yii::app()->end();
    }



    /**
     * Отправка файлов на сервер
     */
    public function actionUploaduniEx()
    {
        // index файла в _FILES
        $fn = filter_var(Yii::app()->getRequest()->getParam('fn'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $op = Yii::app()->getRequest()->getParam('op');

//        echo CJSON::encode(array('error' => -101));
//        return;

        $Upluni = (new Uploaduni());
        if( $op == '1' ) $res = $Upluni->processUploadedFileEx($fn);
        elseif( $op == '2' ) $res = $Upluni->deleteFileEx();

        echo CJSON::encode($res);
        Yii::app()->end();
    }



    /**
     * Сохранение обрезанного лого
     */
    public function actionCropLogo()
    {
        if( in_array(Share::$UserProfile->type, [2,3]) )
        {
            $res = Share::$UserProfile->proccessLogo();
            echo CJSON::encode($res);
        } // endif
        Yii::app()->end();
    }



    /**
     * Отправка получаем название услуги
     */
    public function actionCreateServiceOrder()
    {
      $model = new ServiceGuestOrder();
      echo CJSON::encode($model->setOrder());
      Yii::app()->end();
    }



    /**
     * Отправка получаем название услуги
     */
    public function actionGetService()
    {
        $id = filter_var(Yii::app()->getRequest()->getParam('id'), FILTER_SANITIZE_NUMBER_INT);
        $res = (new Services())->getServiceData(null, $id);
        echo CJSON::encode($res);
        Yii::app()->end();
    }


    /**
     * Отправка сообщения пользователя
     */
    public function actionSendUserMesages()
    {
      if( Yii::app()->getRequest()->isPostRequest )
      {
        $Im = Share::isApplicant()
          ? (new ImApplic())
          : (new ImEmpl());
        $arResult = $Im->sendUserMessages();
      }
      else
      {
        $arResult = ['error' => 101];
      }
      echo CJSON::encode($arResult);
      Yii::app()->end();
    }



    /**
     * Получаем сообщения пользователя
     */
    public function actionGetNewMesages()
    {
        if( Share::$UserProfile->type == 2 ) $Im = new ImApplic();
        elseif( Share::$UserProfile->type == 3 ) $Im = new ImEmpl();
        if( $Im )
        {
            $res = $Im->getNewMessages();
            echo CJSON::encode($res);
        } // endif
        Yii::app()->end();
    }


    /**
     * Получаем сообщения пользователя
     */
    public function actionGetUserMesages()
    {
        if( Share::$UserProfile->type == 2 ) $Im = new ImApplic();
        elseif( Share::$UserProfile->type == 3 ) $Im = new ImEmpl();
        if( $Im )
        {
            $res = $Im->getUserMessages();
            echo CJSON::encode($res);
        } // endif
        Yii::app()->end();
    }



    /**
     * Откликаемся на вакансию
     */
    public function actionSetVacationResponse()
    {
        $result = array('error' => 1);
        if(Share::isApplicant()) // applicant
        {
            $result = (new ResponsesApplic)->setVacationResponse();
        }
        elseif(Share::isEmployer()) // employer
        {
            $result['message'] = 'Нам очень жаль:( но Вы зарегистрированы '
                . 'как работодатель и не можете откликнуться на вакансию';
        }
        else // guest
        {
            $result['message'] = '<p>Для отклика на вакансию необходимо <a href="' 
                . Yii::app()->controller->createUrl(MainConfig::$PAGE_REGISTER,['p'=>1]) 
                . '">зарегистрироваться</a> на портале.</p><p>Если Вы ранее уже регистрировались'
                . '- необходимо <a href="' . MainConfig::$PAGE_LOGIN . '">авторизоваться</a></p>';
        }

        echo CJSON::encode($result);
        Yii::app()->end();
    }


    /**
     * ПОменять статус отклика
     */
    public function actionSetResponseStatus()
    {
      if(!Share::isGuest())
      {
        $model = Share::isApplicant()
          ? new ResponsesApplic()
          : new ResponsesEmpl();
        $arRes = $model->setResponseStatus();
        echo CJSON::encode($arRes);
      }
      Yii::app()->end();
    }
    /**
     * получаем работодателей в поиске на главной
     */
    public function actionGetEmpls()
    {
      $result = (new Employer())->getEmployersSearch();
      header('Content-type: application/json');
      echo CJSON::encode($result);
      Yii::app()->end();
    }
    /**
     * получаем соискателей в поиске на главной
     */
    public function actionGetApplic()
    {
      $result = (new Promo())->getApplicantsSearch();
      header('Content-type: application/json');
      echo CJSON::encode($result);
      Yii::app()->end();
    }
    /**
     * получаем вакансии для поиска на главной
     */
    public function actionGetSearchVacs()
    {
      $result = (new Vacancy())->getVacanciesSearch();
      header('Content-type: application/json');
      echo CJSON::encode($result);
      Yii::app()->end();
    }



    public function actionGetEmplContacts()
    {
        $res = (new UserProfileEmpl())->getContacts();

        echo CJSON::encode($res);
    }



    public function actionGetEmplRate()
    {
        $res = (new ProfileFactory())->makeProfile(['id' => 1, 'type' => 3])->getRate();
//        $res = $Profille->getRate();

        echo CJSON::encode($res);
    }



    function actionListmenu()
    {
        $share = new Share;
//   		$lang = $share->getLangAjax();
        $lang = $_GET['lang'];
        $menu_type = $share->getMenuTypeAjax();
        $menu = new Menu;
        if (Yii::app()->user->isGuest)
            echo "Access denied!";
        else {
            $html = $menu->getMenuListHtml($lang, $menu_type);
            echo $html;
        }
        Yii::app()->end();
    }



    function actionDeleteMenu()
    {
        $share = new Share;
        //$lang = $share->getLangAjax();
        $lang = $_GET['lang'];
        $menu_type = $share->getMenuTypeAjax();
        $id = $_GET['id'];
        $menu = new Menu;
        $html = $menu->delMenu($id, $lang, $menu_type);
        echo $html;
        Yii::app()->end();
    }



    function actionChangePosMenu()
    {
        $id = $_GET['id'];
        $switch = $_GET['switch'];
        $share = new Share;
        //$lang = $share->getLangAjax();
        $lang = $_GET['lang'];
        $menu_type = $share->getMenuTypeAjax();
        $menu = new Menu;
        $html = $menu->getMenuListOfPos($id, $switch, $lang, $menu_type);
        echo $html;
        Yii::app()->end();
    }



    public function actionUpload()
    {
        $this->render('nicUpload');
    }



    public function actionSetLang()
    {
        $lang = $_GET['lang'];
        Yii::app()->session['lang'] = $lang;
        echo $lang;
        Yii::app()->end();
    }



    public function actionEditLanguage()
    {
        $id = intval($_GET['id']);
        $lang = $_GET['lang'];
        $page = $_GET['page'];
        $keyword = $_GET['keyword'];
        $value = $_GET['value'];

        $model = new Languages;
        if ($id == 0)
            echo $model->Add($lang, $page, $keyword, $value);
        else
            echo $model->Edit($id, $lang, $page, $keyword, $value);
    }



    public function actionGetLanguage()
    {
        $id = intval($_GET['id']);
        $model = new Languages;
        $res = $model->GetRow($id);
        echo CJSON::encode($res);
    }



    public function actionDelLanguage()
    {
        $id = intval($_GET['id']);
        $model = new Languages;
        $res = $model->DeleteRow($id);
        echo $res;
    }



// ************* Cities ************************
    public function actionAddCity()
    {
        $name = $_GET['name'];
        $model = new City;
        $model->AddName($name);
        echo("Ok");
    }



    public function actionEditCity()
    {
        $name = $_GET['name'];
        $id = $_GET['id'];
        $model = new City;
        $model->EditName($name, $id);
        echo("Ok");
    }



    public function actionSetActiveCity($id)
    {
        $id = $_GET['id'];
        $model = new City;
        $res = $model->SetActive($id);
        if (!isset($res)) $res = 0;
        echo($res);
    }



    public function actionGetCities()
    {
        $id = Yii::app()->getRequest()->getParam('idco') ?: 0;
        $filter = Yii::app()->getRequest()->getParam('filter');
        $limit = Yii::app()->getRequest()->getParam('limit');
        $getCity = Yii::app()->getRequest()->getParam('getCity');
//        $res = (new City)->GetList($id, $limit, $filter);

        if ($getCity) $res = (new City)->getCityList($id, $filter, $limit);
        else $res = (new City)->GetList($id, $limit, $filter);
        echo CJSON::encode($res);
    }



    public function actionGetVacs()
    {
        $res = (new SearchVac)->getVacs();
        $res['length'] = count($res);
        echo CJSON::encode($res);
    }



    public function actionGetMetro()
    {
        $id = Yii::app()->getRequest()->getParam('idcity') ?: 0;
        $res = (new City)->GetListMetro($id, Share::$UserProfile->exInfo->id);
        echo CJSON::encode($res);
    }



// ************* University ************************
    public function actionAddUniversity()
    {
        $name = $_GET['name'];
        $model = new University;
        $model->AddName($name);
        echo("Ok");
    }



    public function actionEditUniversity()
    {
        $name = $_GET['name'];
        $id = $_GET['id'];
        $model = new University;
        $model->EditName($name, $id);
        echo("Ok");
    }



    public function actionSetActiveUniversity($id)
    {
        $id = $_GET['id'];
        $model = new University;
        $res = $model->SetActive($id);
        if (!isset($res)) $res = 0;
        echo($res);
    }



    public function actionGetUniversity()
    {
        $filter = $_GET['name_startsWith'];
        $model = new University;
        $res = $model->GetList(10, $filter);
        echo CJSON::encode($res);
    }



// ************* Banners ************************
    public function actionAddBanners()
    {
        $name = $_GET['name'];
        $link = $_GET['linkbanner'];
        $file_banner = $_GET['filebanner'];
        $model = new Banners;
        $model->AddName($name, $link, $file_banner);
        echo("Ok");
    }



    public function actionEditBanners()
    {
        $name = $_GET['name'];
        $link = $_GET['linkbanner'];
        $file_banner = $_GET['filebanner'];
        $id = $_GET['id'];
        $model = new Banners;
        $model->EditName($name, $link, $file_banner, $id);
        echo("Ok");
    }



    public function actionSetActiveBanners($id)
    {
        $id = $_GET['id'];
        $model = new Banners;
        $res = $model->SetActive($id);
        if (!isset($res)) $res = 0;
        echo($res);
    }



    public function actionGetBanners()
    {
        $filter = $_GET['name_startsWith'];
        $model = new Banners;
        $res = $model->GetList(10, $filter);
        echo CJSON::encode($res);
    }



    public function actionGetRa()
    {
        $filter = $_GET['name_startsWith'];
        $model = new Ra;
        $res = $model->GetList(10, $filter);
        echo CJSON::encode($res);
    }



    public function actionGetTopMenu()
    {
        $lang = $_GET['lang'];
        $utype = $_GET['utype'];
        $mtype = 1;  // главное меню

        $menu = new Menu;

        header("Content-type: application/json");
        if ($utype > 0) {
            echo $menu->getTwoTree(0, $lang, $mtype, $utype, 1);
        } else {
            echo $menu->getTree(0, $lang, $mtype, 0);
        }
    }



    public function actionMail()
    {
        $mail = $_GET['mail'];
        $login = $_GET['l'];

        $res = Yii::app()->db->createCommand()
            ->select('id_user, status')
            ->from('user')
            ->where('login = :login AND email=:email', array(':login' => $login, ':email' => $mail))
            ->queryRow();

        $id_user = $res['id_user'];
        $status = $res['status'];
        //$tm = new Date().getTime();

        $mtime = microtime(true);
        $time = floor($mtime);
        $ms = $mtime - $time;

        $token = md5(md5($id_user) . md5($ms));

        Yii::app()->db->createCommand()
            ->insert('user_activate', array(
                'id_user' => $id_user,
                'token' => $token,
                'status' => $status
            ));


        $docroot = $_SERVER['DOCUMENT_ROOT'];
        include_once($docroot . "/mail.php");
        $res = sendEmail($mail, $token);
        header("Content-type: application/json");
        if ($res)
            echo '{"message":"Письмо доставлено"}';
        else
            echo '{"message":"Почтовый адрес неверный"}';
    }



    public function actionCheckCid()
    {
        $cid = $_GET['cid'];
        $cname = $_GET['cname'];

        $res = Share::getCID($cid, $cname);
        if ($res['id_user'] > 0)
            echo '1';
        else
            echo '0';
    }



    protected function doAuth()
    {
        $params = (object)[];

        return (new Auth())->doAuth($params);
    }
    /*
    *   проверка emaila пользователя на уникальность в системе при редактировании профиля
    */
    public function actionEmailVerification()
    {
      $rq = Yii::app()->getRequest();
      $oldEmail = $rq->getParam('oemail');
      $newEmail = $rq->getParam('nemail');
      echo CJSON::encode(UserProfile::emailVerification($newEmail,$oldEmail));
    }

    public function actionPhoneVerification()
    {
      $rq = Yii::app()->getRequest();
      $oldPhone = '+' . $rq->getParam('ophone');
      $newPhone = '+' . $rq->getParam('nphone');
      echo CJSON::encode(UserProfile::phoneVerification($newPhone,$oldPhone));
    }
    /*
    *   отправка снимка с камеры на сервер
    */
    public function actionPostLogoSnapshot()
    {
        if( in_array(Share::$UserProfile->type, [2,3]) )
        {
            $res = (new UploadLogo())->processUploadedLogoSnapshot();
            echo CJSON::encode($res);
        } // endif
        Yii::app()->end();
    }


    /**
    *      прием файла с субдомена
    */
    public function actionAcceptFileFromSubdomain()
    {
        $send = 400;
        if(Yii::app()->getRequest()->isPostRequest){  
            if($im = base64_decode($_POST['img'])){

                file_put_contents($_POST['path'], base64_decode($_POST['img']));
                $send = 200;
            }
        }
        echo $send;
        Yii::app()->end();
    }

    /**
    *      удаление файла через субдомен
    */
    public function actionDelThroughSubdomain()
    {
        $send = 400;
        if(Yii::app()->getRequest()->isPostRequest){
            file_exists($_POST['path'] . '.jpg') && unlink($_POST['path'] . '.jpg'); // for company original
            file_exists($_POST['path'] . '30.jpg')  && unlink($_POST['path'] . '30.jpg');
            file_exists($_POST['path'] . '100.jpg') && unlink($_POST['path'] . '100.jpg');
            file_exists($_POST['path'] . '169.jpg') && unlink($_POST['path'] . '169.jpg');
            file_exists($_POST['path'] . '400.jpg') && unlink($_POST['path'] . '400.jpg');
            file_exists($_POST['path'] . '000.jpg') && unlink($_POST['path'] . '000.jpg');
            $send = 200;
        }
        echo $send;
        Yii::app()->end();
    }
    /*
    *       сохраняем настройки
    */
    public function actionSaveSettings()
    {
        if( in_array(Share::$UserProfile->type, [2,3]) )
        {
            echo CJSON::encode(Share::$UserProfile->saveSettings(Share::$UserProfile->id));
        }
    }
    /*
    *
    */
    public function actionSetideaattrib()
    {
        if( in_array(Share::$UserProfile->type, [2,3]) )
        {
            $model = new Ideas;
            if(Yii::app()->getRequest()->getParam('rating')){
                echo CJSON::encode($model->setRating());
            }
            if(Yii::app()->getRequest()->getParam('comment')){
                echo CJSON::encode($model->setComment());
            }
        }
        else{
            echo CJSON::encode(array('type'=>'guest'));
        }
    }
    /*
    *       Глобальный экшн для ГЕО проектов
    */
    public function actionProject()
    {
        $result = array('error'=>true);
        $data = Yii::app()->getRequest()->getParam('data');
        $data = json_decode($data, true, 5, JSON_BIGINT_AS_STRING);
        if(!isset($data['type']))
            $data['type'] = Yii::app()->getRequest()->getParam('type');
        
        $model = new Project();
        if(in_array($data['type'],['xls-index','xls-staff']) && $_SERVER['REQUEST_METHOD']==='POST')
        {
            $props = array(
                    'title' => 'test',
                    'type' => $data['type']
                );

            $props['project'] = strlen($data['id']) ? $data['id'] : rand(10000,99999);
            $props['link'] = $props['project'] . '.' . (end(explode('.', $_FILES['xls']['name'])));
            $uploadfile = $model->XLS_UPLOAD_PATH . $props['link'];


            if (move_uploaded_file($_FILES['xls']['tmp_name'], $uploadfile))
                $result = $model->confirmXls($props);
            else
                $result['message'] = 'server';

            unlink($uploadfile);
        }
        if($data['type']==='convert')
        {
            $convert = new ProjectConvertVacancy();
            switch ($_SERVER['REQUEST_METHOD']) {
                case 'POST':
                    if($data['to']==='project') {
                        $result = $convert->vacancyConvertToProject($data);
                        if(!$result['error']) {
                            $project = $model->createProject($result);
                            $result['link'] = MainConfig::$PAGE_PROJECT_LIST . '/' . $project;
                        }
                        $result['link'] = MainConfig::$PAGE_PROJECT_LIST . '/' . $project;
                    }
                    if($data['to']==='vacancy') {
                        $data['staff'] = $model->getAllStaffProject($data['id']);
                        $data['index'] = $model->getIndex($data['id']);
                        $data['project'] = $model->getProjectData($data['id']);
                        $result = $convert->projectConvertToVacancy($data);
                    }                    
                    break;
            }
        }
        elseif($model->hasAccess($data['project']))
        {
            switch ($_SERVER['REQUEST_METHOD']) {
                case 'GET':
                    if($data['type']==='coordinates') // получение координат передвижения С
                        $result = $model->getСoordinatesAjax($data);
                    if($data['type']==='userdata')
                    {
                        $result = $model->getUserTasks($data);
                        $user = intval($data['user']);
                        if($user) {
                            $result['user'] = $model->getUserMainInfo($user);
                            $result['user'] = $model->buildUserData($result['user'][0]);
                        }
                    }
                    break;
                case 'POST':
                    if($data['type']==='coordinates') // запись координат от С
                        $result = $model->recordReport($data);
                    if($data['type']==='del-index') // удаление объектов адресной программы
                        $result = $model->deleteLocation($data);
                    if(in_array(
                            $data['type'], 
                            ['new-task','change-task','all-dates-task','all-users-task','delete-task','change-task-status']
                        )
                    )
                        $result = $model->changeTask($data); 
                    if($data['type']==='task')
                    {
                        $result = $model->taskAjaxHandler($data); 
                    }
                    break;
            }
        }
    
        echo CJSON::encode($result);
    }
    /*
    *       Глобальный экшн для чатов
    */
    public function actionChat()
    {
        $result = array('error'=>true);
        $data = Yii::app()->getRequest()->getParam('data');
        $data = json_decode($data, true, 5, JSON_BIGINT_AS_STRING);
        $type = Share::$UserProfile->type;

        if(!in_array($type, [2,3]))
        {
            echo CJSON::encode($result);
            Yii::app()->end();
        }

        if(intval($data['user'])>0)
        {
            if($data['new'])
            {

            if( Share::$UserProfile->type == 2 ) $Im = new ImApplic();
            elseif( Share::$UserProfile->type == 3 ) $Im = new ImEmpl();
            if( $Im )

                $arParams = array(
                    'new' => $data['user'],
                    'vid' => $data['vacancy']
                );
            }
            $result = ['eeeeeeeeeer'=>1];
        }
        else
        {
            $model = new VacDiscuss();
            $result = $model->recordMessage($data);            
        }
        echo CJSON::encode($result);
    }
    /**
     *  для регистрационного попапа С
     */
    public function actionProfile()
    {
        $data = Yii::app()->getRequest()->getParam('data');
        $data = json_decode($data, true, 5, JSON_BIGINT_AS_STRING);

        if(Share::isApplicant() || Share::isEmployer())
        {
            Share::$UserProfile->savePopupData($data);
        }
    }
    /**
     *  проверка самозанятого
     */
    public function actionSelf_employed()
    {
        $arRes = array('error'=>true,'response'=>[]);

        if(!Share::isGuest())
        {
          $model = new User();
          $model->checkSelfEmployed($arRes);
        }

        echo CJSON::encode($arRes);
        Yii::app()->end();
    }
    /**
     *  проверка ообщений от админа
     */
    public function actionAdminMessages()
    {
      $arRes = ['error'=>true];
      if(Share::isGuest())
      {
        $arRes['is_guest'] = true;
        echo CJSON::encode($arRes);
        Yii::app()->end();
      }
      $data = Yii::app()->getRequest()->getParam('data');
      $data = json_decode($data, true, 5, JSON_BIGINT_AS_STRING);
      if(!empty($data['agree']))
      {
        $id = intval($data['agree']);
        $model = new AdminMessageReceiver();
        $result = $model->setReaded($id,Share::$UserProfile->id);
        $arRes['id'] = $id;
        $arRes['id2'] = Share::$UserProfile->id;
        $arRes['error'] = !$result;
      }
      else
      {
        $model = new AdminMessage();
        $arRes['items'] = $model->getNewMessages(Share::$UserProfile->id);
        $arRes['error'] = $arRes['items']==false;
      }
      // Установка статуса юзера "В сети"
      User::enableUserOnlineStatus(Share::$UserProfile->id);

      echo CJSON::encode($arRes);
      Yii::app()->end();
    }
    /**
     *
     */
    public function actionRegisterAvatar()
    {
      $rq = Yii::app()->getRequest();
      if(Share::isGuest() || !$rq->isAjaxRequest)
      {
        Yii::app()->end();
      }
      $post = $rq->getParam('data');
      $post = json_decode($post, true, 5, JSON_BIGINT_AS_STRING);

      $model = new UserRegister(Share::$UserProfile->id);
      if(isset($_FILES['upload'])) // только загрузка файлов
      {
        $data = $model->saveImage();
        echo CJSON::encode($data);
        Yii::app()->end();
      }
      elseif (isset($post['width']) && isset($post['height'])) // редактирование аватара
      {
        $data = ($post['edit']==1
          ? $model->onlyEditImage($post)
          : $model->editImage($post));
        echo CJSON::encode($data);
        Yii::app()->end();
      }
      elseif (isset($post['delfile'])) // удаление файлов
      {
        $model->deleteImage($post['delfile']);
        Yii::app()->end();
      }
    }
  /**
   *  список городов для нового универсального списка городов
   */
  public function actionGetCitiesByName()
  {
    $rq = Yii::app()->getRequest();
    $country = $rq->getParam('country') ?: 0;
    $query = $rq->getParam('query');
    $limit = $rq->getParam('limit', '10');
    echo CJSON::encode(City::getCitiesByName($query, $country, $limit));
    Yii::app()->end();
  }
}
