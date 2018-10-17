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
    
     public function actionRestorecode(){
        $code = rand(111111, 999999);
        $phone = $_POST['phone'];
        $email = $_POST['email'];

        if($email){
             $message = sprintf("Ваш код подтверждения: %s ",$code);
       
 
           Share::sendmail($email, "Prommu.com Код подтверждения", $message);

        $res = Yii::app()->db->createCommand()->delete('activate', 'phone=:phone', array(':phone'=> "$email"));
        $rest = Yii::app()->db->createCommand()
                        ->insert('activate', array('id' => $code,
                            'id' => $code,
                            'code' => $code,
                            'phone' => $email,
                            'date' => date("Y-m-d h-i-s"),
                            ));

        }
        elseif($phone) {


        $res = Yii::app()->db->createCommand()->delete('activate', 'phone=:phone', array(':phone'=> "$phone"));
        $rest = Yii::app()->db->createCommand()
                        ->insert('activate', array('id' => $code,
                            'id' => $code,
                            'code' => $code,
                            'phone' => $phone,
                            'date' => date("Y-m-d h-i-s"),
                            ));

        $postdata = array( 
        'phone' => $phone,
        'code' =>  $code, 
        ); 
        $post = http_build_query($postdata); 
        $ch = curl_init("https://prommu.com/api.teles_test"); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($ch, CURLOPT_POST, 1); 
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post); 
        $response = curl_exec($ch); 
        curl_close($ch); 

         

         if($_GET['auth'] == 1) {
             $res = Yii::app()->db->createCommand()
            ->select('id')
            ->from('user')
            ->where('email=:email', array(':email'=>$email))
            ->queryRow();

            $auth = new Auth();
            $auth->Authorize(['id' => $cloud['id']]);


         }
         echo CJSON::encode($response);
     }

    }

    public function actionConfirm(){

       $code = $_POST['code'];
       $phone = $_POST['phone'];
       $email = $_POST['email'];
        $emails['code'] = '100';
       if($email) {
         $res = Yii::app()->db->createCommand()
            ->select('code')
            ->from('activate')
             ->where('phone=:phone', array(':phone'=>$email))
            ->queryRow();

           

        if($res['code'] == $code && !empty($res)){

            $res = Yii::app()->db->createCommand()
            ->update('user', array(
                'confirmEmail' => 1,
            ), 'email=:email', array(':email' => $email));

            $emails['code'] = '200';
        }
        else {
            $emails['code'] = '100';
        }

       } elseif($phone){

        $res = Yii::app()->db->createCommand()
            ->select('code')
            ->from('activate')
             ->where('phone=:phone', array(':phone'=>$phone))
            ->queryRow();

          if($res['code'] == $code && !empty($res)){

            $res = Yii::app()->db->createCommand()
            ->update('user', array(
                'confirmPhone' => 1,
                'login' => "+".$phone,
            ), 'id_user=:id_user', array(':id_user' => Share::$UserProfile->id));

            $emails['code'] = '200';
        }
        else {
            $emails['code'] = '100';
        }
    }
       
         echo CJSON::encode($emails);

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
        $res = (new Services())->createServiceOrder();
        echo CJSON::encode($res);
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
            if( Share::$UserProfile->type == 2 ) $Im = new ImApplic();
            elseif( Share::$UserProfile->type == 3 ) $Im = new ImEmpl();
            if( $Im )
            {
                $res = $Im->sendUserMessages();
                echo CJSON::encode($res);
            } // endif
        } else echo CJSON::encode(array('error' => 101));
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
        if( Share::$UserProfile->type == 2 )
        {
            $res = (new ResponsesApplic)->setVacationResponse();
            $html = $this->render('vac-response-tpl', array('viData' => $res), array(), true);
            echo CJSON::encode(array_merge($res, array('html' => $html)));
        } // endif
        Yii::app()->end();
    }


    /**
     * ПОменять статус отклика
     */
    public function actionSetResponseStatus()
    {
        if( in_array(Share::$UserProfile->type, [2,3]) )
        {
            $response = Share::$UserProfile->type == 2 ? new ResponsesApplic() : new ResponsesEmpl();
            $res = $response->setResponseStatus();
            echo CJSON::encode($res);
        } // endif
        Yii::app()->end();
    }



    /**
     * @deprecated
     */
    public function actionGetEmpls()
    {
        $res = (new Employer())->employers();
        header('Content-type: application/json');
        echo CJSON::encode($res);
        Yii::app()->end();
    }



    // получаем соискателей в поиске на главной
    public function actionGetApplic()
    {
         $res = (new Vacancy())->getPosts();

        header('Content-type: application/json');
        echo CJSON::encode($res);
        Yii::app()->end();
    }



    // получаем вакансии для поиска на главной
    public function actionGetSearchVacs()
    {
        $res = (new Vacancy())->getPosts();

        header('Content-type: application/json');
        echo CJSON::encode($res);
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
        echo CJSON::encode(Share::$UserProfile->emailVerification());
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
        
        $model = new Project();
        if($model->hasAccess($data['project'])) {
            switch ($_SERVER['REQUEST_METHOD']) {
                case 'GET':
                    if($data['type']==='coordinates') // получение координат передвижения С
                        $result = $model->getСoordinates($data);
                    break;
                case 'POST':
                    if($data['type']==='coordinates') // запись координат от С
                        $result = $model->recordReport($data);
                    if($data['type']==='del-index') // удаление объектов адресной программы
                        $result = $model->deleteLocation($data);
                    if(in_array(
                            $data['type'], 
                            ['new-task','change-task','all-dates-task','all-users-task','delete-task']
                        )
                    )
                        $result = $model->changeTask($data); 
                    break;
            }
        }
        if($data['type']==='convert') {
            switch ($_SERVER['REQUEST_METHOD']) {
                case 'POST':
                    if($data['to']==='project') {
                        $convert = new ProjectConvertVacancy();
                        $result = $convert->vacancyConvertToProject($data);
                        if(!$result['error'])
                            $model->createProject($result);
                    }
                    
                    break;
            }
        }    
        echo CJSON::encode($result);
    }
}
