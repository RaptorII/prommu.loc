<?php

//namespace Controllers;

class UserController extends AppController
{
    /**
     * Declares class-based actions.
     */
    public function actions()
    {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
            ),
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&view=FileName
            'page' => array(
                'class' => 'CViewAction',
            ),
        );
    }
    
      public function actionProCreate(){
   
        $project = new Project();
        $result = $project->createProject($_POST);
        $this->redirect("http://dev.prommu.com/user/projects");

      }

      public function actionOutstaffing(){
        $order = new PrommuOrder();
        $order->outstaffing($_POST);
        Yii::app()->user->setFlash('success', '1');
        $this->redirect("https://prommu.com/services");
      }


      public function actionPostback(){
        $method = $_GET['method'];
        $account = $_GET['params']['account'];
        $orderSum = (int)$_GET['params']['orderSum'];
        $unitpayId = $_GET['params']['unitpayId'];
        if($method == "check"){
            $account = $_GET['params']['account'];
            $arr = explode(".", $account);
            $account = $arr[0];
            $count = count($arr);
            if($arr[2] == "sms"){
                for ($i = $count; $i > 2 ; $i --) {
                $name = $arr[1];
                $user = $arr[$i];
               $res = Yii::app()->db->createCommand()
                ->update('service_cloud', array(
                    'key'=> $unitpayId,
                ), 'id_user=:id_user AND name=:name AND user=:user', array(':id_user' => "$account", ':name' => "$name", ':user' => $user));
                }
            }elseif($arr[2] == "push"){
                for ($i = $count; $i > 2 ; $i --) {
                $name = $arr[1];
                $user = $arr[$i];
               $res = Yii::app()->db->createCommand()
                ->update('service_cloud', array(
                    'key'=> $unitpayId,
                ), 'id_user=:id_user AND name=:name AND user=:user', array(':id_user' => "$account", ':name' => "$name", ':user' => $user));
                }
            } elseif($arr[2] == "email"){
                for ($i = $count; $i > 2 ; $i --) {
                $name = $arr[1];
                $user = $arr[$i];
               $res = Yii::app()->db->createCommand()
                ->update('service_cloud', array(
                    'key'=> $unitpayId,
                ), 'id_user=:id_user AND name=:name AND user=:user', array(':id_user' => "$account", ':name' => "$name", ':user' => $user));
                }
            } else {

            for ($i = $count; $i > 0 ; $i --) {
                $name = $arr[$i];
               $res = Yii::app()->db->createCommand()
                ->update('service_cloud', array(
                    'key'=> $unitpayId,
                ), 'id_user=:id_user AND name=:name', array(':id_user' => "$account", ':name' => "$name"));
                }
            }

        }
        if($method == "pay"){
            $unitpayId = $_GET['params']['unitpayId'];
            $account = $_GET['params']['account'];
            $arr = explode(".", $account);
            $account = $arr[0];
            $count = count($arr);

             $sql = "SELECT ru.email
            FROM user ru
            WHERE ru.id_user = {$account}";
        /** @var $res CDbCommand */
            $res = Yii::app()->db->createCommand($sql);
            $rest = $res->queryRow();


            $content = file_get_contents(Yii::app()->basePath . "/views/mails/app/services.html");
                  // $content = str_replace('#APPNAME#', "пользователь", $content);
                  $content = str_replace('#SERVICENAME#', "успешно подключена", $content);
                 $content = str_replace('#SERVICEACTION#', "и отображается во вкладке услуги" , $content);
                 $content = str_replace('#SERVICELINK#', "https://prommu.com/services", $content);

              Share::sendmail($rest['email'], "Prommu.com. Действие по услугам", $content);

            if($arr[2] == "sms"){
                for ($i = 3; $i < $count; $i ++) {
                $name = $arr[1];
                $user = $arr[$i];
               $res = Yii::app()->db->createCommand()
                ->update('service_cloud', array(
                    'status'=> 1,
                ), 'id_user=:id_user AND name=:name AND user=:user', array(':id_user' => "$account", ':name' => "$name", ':user'=> $user));

                $api = new Api();
                $api->teleProm($user, $unitpayId);

                }
            } elseif($arr[2] == "vacancy") {
            for ($i = $count; $i > 0 ; $i --) {
                $name = $arr[$i];

                 $res = Yii::app()->db->createCommand()
                ->update('service_cloud', array(
                    'status'=> 1,
                ), 'id_user=:id_user AND name=:name', array(':id_user' => "$account", ':name' => "$name"));

                $result = Yii::app()->db->createCommand()
                ->update('empl_vacations', array(
                    'ispremium' => 1,
                    'crdate' => date("Y-m-d"),
                    'mdate' => date("Y-m-d"),
                ), 'id=:id', array(':id' => $name));

                }
            } elseif($arr[2] == "push") {
            for ($i = $count; $i > 0 ; $i --) {
                $name = $arr[$i];

                $res = Yii::app()->db->createCommand()
                ->update('service_cloud', array(
                    'status'=> 1,
                ), 'id_user=:id_user AND name=:name AND user=:user', array(':id_user' => "$account", ':name' => "$name", ':user'=> $user));

                $link = "https://prommu.com/vacancy/$user";
                $text = "Работодатель приглашает на вакансию";

                $sql = "SELECT r.push
                FROM user_push r
                WHERE r.id = {$user}";
                $res = Yii::app()->db->createCommand($sql)->queryRow();
                if($res) {
                $type = "vacancy";
                $api = new Api();
                $api->getPushApi($res['push'], $type, $text, $link);

                }
                }
            } elseif($arr[2] == "email") {
                for ($i = 3; $i < $count; $i ++) {
                 $name = $arr[1];
                $user = $arr[$i];


                $res = Yii::app()->db->createCommand()
                ->update('service_cloud', array(
                    'status'=> 1,
                ), 'id_user=:id_user AND name=:name AND user=:user', array(':id_user' => "$account", ':name' => "$name", ':user'=> $user));

                $sql = "SELECT  e.title
                FROM empl_vacations e
                WHERE e.id = {$name}";
                $vacancy = Yii::app()->db->createCommand($sql)->queryAll();

             $sql = "SELECT  u.email, e.name, e.firstname, e.lastname
                FROM employer e
                LEFT JOIN user u ON u.id_user = e.id_user
                WHERE e.id_user = {$account}";
            $empl = Yii::app()->db->createCommand($sql)->queryAll();

            $sql = "SELECT  e.id, u.email, e.firstname, e.lastname
                FROM resume e
                LEFT JOIN user u ON u.id_user = e.id_user
                WHERE e.id_user = {$user}";
            $resume = Yii::app()->db->createCommand($sql)->queryAll();

            // $res = Yii::app()->db->createCommand()
            //     ->insert('vacation_stat', array(
            //         'id_vac' => $name,
            //         'id_promo' => $resume[0]['id'],
            //         'isresponse' => 2,
            //         'status' => 4,
            //         'date' => date("Y-m-d h-i-s")
            //         ));

            $message = '<p style="font-size:16px;"Работодатель'.$id_user.' '.$empl[0]['lastname'].' '.$empl[0]['firstname'].'<br/> </p>
                    <br/>

                <p style=" font-size:16px;">
                 <br/>Компания: '.$empl[0]['name'].'<br/>
              Приглашает на вакансию:  '.$name.' '.$vacancy[0]['title'].'<br/>
              Ссылка на вакансию:  <a href="https://prommu.com/vacancy/'.$name.'">'.$vacancy[0]['title'].'</a><br/>

                    <br/>';
                    if(strpos($resume[0]['email'], "@") !== false){
                        Share::sendmail($empl[0]['email'], "Prommu.com. Приглашение На Вакансию", $message);
                        Share::sendmail('denisgresk@gmail.com', "Prommu.com. Приглашение На Вакансию", $message);
                    }
                }
            }

        }
         $data['result']['message'] = "Запрос успешно обработан";
        $data = json_encode($data);
        echo $data;
      }



    public function actionPush(){

       $id = Share::$UserProfile->id;
       $invite = $_POST['invite'] ? $_POST['invite'] : $_GET['invite'];
       $respond = $_POST['respond'] ? $_POST['respond'] : $_GET['respond'];;
       $workday = $_POST['workday'] ? $_POST['workday'] : $_GET['workday'];;
       $mess = $_POST['mess'] ? $_POST['mess'] : $_GET['mess'];;
       $rate = $_POST['rate'] ? $_POST['rate'] : $_GET['rate'];;
       $all = $_POST['all'] ? $_POST['all'] : $_GET['all'];;

       if($all == 2){
        $rate = $respond = $mess = $workday = $invite = 0;
       }
        if(empty($rate)){
            $rate = 0;
        }
        if(empty($mess)){
            $mess = 0;
        }
        if(empty($invite)){
            $invite = 0;
        }
        if(empty($respond)){
            $respond = 0;
        }
        if(empty($workday)){
            $workday = 0;
        }

        $sql = "SELECT r.id
            FROM push_config r
            WHERE r.id = {$id}";
        $res= Yii::app()->db->createCommand($sql)->queryScalar();

        if($res['id']){
            Yii::app()->db->createCommand()
                ->update('push_config', array(
                    'new_mess' => $mess,
                    'new_invite' => $invite,
                    'new_respond' => $respond,
                    'new_workday'=> $workday,
                    'new_rate' => $rate,
                ), 'id = :id', array(':id' => $id));
        } else {
            $res = Yii::app()->db->createCommand()
                    ->insert('push_config', array(
                        'new_workday' => $workday,
                        'new_mess' => $mess,
                        'new_invite' => $invite,
                        'new_respond' => $respond,
                        'new_rate' => $rate,
                        'id' => $id,
                    ));
        }

        $this->redirect("https://prommu.com/services/push-notification");

    }

    public function actionAnalytic(){

        $vac = new Vacancy();
        $data = $vac->getVacanciesPrem();
        $view = MainConfig::$VIEWS_ANALYTIC;
        $this->render($view, array('viData' => $data, 'photodata' => $pht), array('nobc' => '1'));

    }

    public function actionMessenger($cloud){
        if($cloud == "") $cloud = $_GET;

        $analyt = Yii::app()->request->cookies['sbjs_current'];
        $analy = explode("|||", $analyt);
        if(!empty($analyt)){
            $data['referer'] = explode("=",$analy[2])[1];
        $data['transition'] = explode("=",$analy[0])[1];
        $data['canal'] = explode("=",$analy[1])[1];
        $data['campaign'] = explode("=",$analy[3])[1];
        $data['content'] = explode("=",$analy[4])[1];
        $data['keywords'] = explode("=",$analy[5])[1];
        $data['point'] = $_GET['point'];
        $data['last_referer'] = $_GET['last_referer'];
        } else {
            $data['referer'] = "(none)";
        $data['transition'] = "(none)";
        $data['canal'] = "(none)";
        $data['campaign'] = "(none)";
        $data['content'] = "(none)";
        $data['keywords'] = "(none)";
        $data['point'] = "(none)";
        $data['last_referer'] = "(none)";
        }
        $data['email'] = $cloud['email'];
        $data['name'] = $cloud['fname'].' '.$cloud['lname'];
        $data['fname'] = $cloud['fname'];
        $data['lname'] = $cloud['lname'];
        $data['gender'] = $cloud['gender'];
        $data['birthday'] = $cloud['birthday'];
        $data['gender'] = $cloud['gender'];

        $data['messenger'] = $cloud['id'];
        $data['type'] = $cloud['type'];
        $email =  $cloud['email'];

        $Auth = new Auth();
        $register = $Auth->registerAuth($data);
        $this->redirect($register);

    }

    public function actionMessnotemail(){
        $data['email'] = $_GET['email'];
        $data['fname'] = $_GET['fname'];
        $data['lname'] = $_GET['lname'];
        $data['name'] = $_GET['name'];
        $data['gender'] = $_GET['gender'];
        $data['birthday'] = $_GET['birthday'];
        $data['gender'] = $_GET['gender'];
        $data['messenger'] = $_GET['messenger'];
        $data['type'] = $_GET['type'];
        $data['referer'] = $_GET['referer'];
        $data['transition'] = $_GET['transition'];
        $data['canal'] = $_GET['canal'];
        $data['campaign'] = $_GET['campaign'];
        $data['content'] = $_GET['content'];
        $data['keywords'] = $_GET['keywords'];
        $data['point'] = $_GET['point'];
        $data['last_referer'] = $_GET['last_referer'];

         $usData = Yii::app()->db->createCommand()
             ->select("u.email, u.id_user, u.status")
             ->from('user u')
             ->where('u.email = :email', array(':email' => $data['email']))
             ->queryRow();

         if(!empty($usData)) {
            $link  = Subdomain::getUrl() . '/message';
            $this->redirect( $link);
         } else {


            $Auth = new Auth();
            $register = $Auth->registerAuth($data);
            $this->redirect($register);
        }

    }



    public function actionLogin()
    {
        $serviceName = Yii::app()->request->getQuery('service');
        $type =  Yii::app()->request->getQuery('type');

        if (isset($serviceName)) {

            /** @var $eauth EAuthServiceBase */
            $eauth = Yii::app()->eauth->getIdentity($serviceName);
            $eauth->redirectUrl = Yii::app()->user->returnUrl;
            $eauth->cancelUrl = $this->createAbsoluteUrl('user/login');

            try {
                if ($eauth->authenticate()) {

                    // var_dump($eauth->getAttributes());//$eauth->getIsAuthenticated(),
                    $identity = new EAuthUserIdentity($eauth);

                    // successful authentication
                    if ($identity->authenticate()) {
                        Yii::app()->user->login($identity);
                        if($eauth->getIsAuthenticated()){
                             // var_dump($eauth->getAttributes());
                            $auth = new Auth();
                            $cloud = $auth->authChekin($eauth->getAttributes()['id']);
                           if($cloud) {
                            $auth->AuthorizeNet(['id' => $cloud['id']]);
                            $this->redirect(MainConfig::$PAGE_PROFILE);
                           }
                           else {
                            $data = $eauth->getAttributes();

                            $usData = Yii::app()->db->createCommand()
                            ->select("u.email")
                            ->from('user u')
                            ->where('u.email = :email', array(':email' => $data['email']))
                            ->queryRow();
                            if(!empty($usData)) {
                                $link  = Subdomain::getUrl() . '/message';
                                $this->redirect( $link);
                            } else {

                                if($type==3) {

                                    //$pth = $auth->loadLogoEmpl($eauth->getAttributes()['photo']);
                                    $view = MainConfig::$VIEWS_REGISTER_FB;
                                    //$data['photo'] = $pth;
                                    $data['type'] = 3;
                                     ///$data[0] = $pth;
                                    if($data['email'] != ""){
                                        $this->actionMessenger($data);
                                    } else $this->render($view, array('viData' => $data, 'photodata' => $pht), array('nobc' => '1'));

                                } else {

                                   /// $pth = $auth->loadLogo($eauth->getAttributes()['photo']);
                                    $view = MainConfig::$VIEWS_REGISTER_FB;
                                    ///$data['photo'] = $pth;
                                     ///$data[0] = $pth;
                                    $data['type'] = 2;
                                    if($data['email'] != ""){
                                        $this->actionMessenger($data);
                                    } else $this->render($view, array('viData' => $data, 'photodata' => $pht), array('nobc' => '1'));

                                }
                                $this->render($view, array('viData' => $data, 'photodata' => $pht), array('nobc' => '1'));
                            }

                           }
                        }


                    }
                    else {
                        // close popup window and redirect to cancelUrl
                        $eauth->cancel();
                    }
                }

                // Something went wrong, redirect to login page
                $this->redirect(array('user/login'));
            }
            catch (EAuthException $e) {
                // save authentication error to session
                Yii::app()->user->setFlash('error', 'EAuthException: '.$e->getMessage());

                // close popup window and redirect to cancelUrl
                $eauth->redirect($eauth->getCancelUrl());
            }
        }

        Share::$isBreadcrumbs = 0;

        if( Share::$UserProfile->type == 2 || Share::$UserProfile->type == 3 ) $this->redirect(MainConfig::$PAGE_PROFILE);
        else{
            $this->setBreadcrumbs($title = 'Авторизация', MainConfig::$PAGE_LOGIN);
            $this->setPageTitle($title);
            $this->render(MainConfig::$VIEWS_LOGIN, null, array('nobc' => 1));
        }
    }



    public function actionProfile()
    {
        // no profile for guest
        !in_array(Share::$UserProfile->type, [2,3]) && $this->redirect(MainConfig::$PAGE_LOGIN);

        Subdomain::profileRedirect(Share::$UserProfile->id);

        // Magnific Popup
        Yii::app()->getClientScript()->registerCssFile(Yii::app()->baseUrl.'/jslib/magnific-popup/magnific-popup-min.css');
        Yii::app()->getClientScript()->registerScriptFile(Yii::app()->baseUrl.'/jslib/magnific-popup/jquery.magnific-popup.min.js', CClientScript::POS_END);
        // Cropper
        Yii::app()->getClientScript()->registerCssFile(Yii::app()->baseUrl.'/jslib/cropperjs/cropper.min.css');
        Yii::app()->getClientScript()->registerScriptFile(Yii::app()->baseUrl.'/jslib/cropperjs/cropper.min.js', CClientScript::POS_END);
        Yii::app()->getClientScript()->registerScriptFile(Yii::app()->baseUrl.'/theme/js/jquery-ui.min.js', CClientScript::POS_END);
        Yii::app()->getClientScript()->registerScriptFile(Yii::app()->baseUrl.'/theme/js/jquery.form-validator.min.js', CClientScript::POS_END);
        Yii::app()->getClientScript()->registerScriptFile(Yii::app()->baseUrl.'/theme/js/jquery.mask.min.js', CClientScript::POS_END);
        Yii::app()->getClientScript()->registerScriptFile("/theme/js/dev/pages/prof_user.js", CClientScript::POS_END);

        //Yii::app()->getClientScript()->registerCssFile('/theme/css/page-profile.css');

        $this->setBreadcrumbs($title = 'Мой профиль', MainConfig::$PAGE_PROFILE);
        $this->setPageTitle($title);

        $this->ViewModel->addContentClass('page-ankety');
        $this->render($this->ViewModel->pageProfile,
                array('viData' => Share::$UserProfile->getProfileDataView(),
                        'flagOwnProfile' => true,
                        'idus' => Share::$UserProfile->id,
                    ));
    }



    public function actionEditprofile()
    {
        Share::$UserProfile->type < 1 && $this->redirect(MainConfig::$PAGE_LOGIN); // no profile for guest


        // save data
        if( Yii::app()->getRequest()->isPostRequest)
        {
            $res = Share::$UserProfile->saveProfileData();
            if(!$res['err']) $this->redirect(MainConfig::$PAGE_PROFILE);
        // del photo
        } elseif( Yii::app()->getRequest()->getParam('del') )
        {
            $res = Share::$UserProfile->delProfilePhoto();
            $s1 = $this->ViewModel->replaceInUrl('', 'del', null);
            $this->redirect($_SERVER['HTTP_REFERER']);
        // сделать фото главным
        } elseif( Yii::app()->getRequest()->getParam('dm') )
        {
            $res = Share::$UserProfile->setPhotoAsLogo();
            $s1 = $this->ViewModel->replaceInUrl('', 'dm', null);
            $this->redirect($_SERVER['HTTP_REFERER']);
        } // endif

        $this->setBreadcrumbsEx(array('Мой профиль', MainConfig::$PAGE_PROFILE), array($title = 'Редактирование профиля', MainConfig::$PAGE_EDIT_PROFILE));


        // Magnific Popup
        Yii::app()->getClientScript()->registerCssFile(Yii::app()->baseUrl.'/jslib/magnific-popup/magnific-popup-min.css');
        Yii::app()->getClientScript()->registerScriptFile(Yii::app()->baseUrl.'/jslib/magnific-popup/jquery.magnific-popup.min.js', CClientScript::POS_END);

        // Cropper
        Yii::app()->getClientScript()->registerCssFile(Yii::app()->baseUrl.'/jslib/cropperjs/cropper.min.css');
        Yii::app()->getClientScript()->registerScriptFile(Yii::app()->baseUrl.'/jslib/cropperjs/cropper.min.js', CClientScript::POS_END);
        Yii::app()->getClientScript()->registerScriptFile(Yii::app()->baseUrl.'/theme/js/jquery-ui.min.js', CClientScript::POS_END);
        Yii::app()->getClientScript()->registerScriptFile(Yii::app()->baseUrl.'/theme/js/jquery.form-validator.min.js', CClientScript::POS_END);
        Yii::app()->getClientScript()->registerScriptFile(Yii::app()->baseUrl.'/theme/js/jquery.mask.min.js', CClientScript::POS_END);

        if( Share::$UserProfile->type == 3 ){
        }
        else{
            if(Yii::app()->getRequest()->getParam('ep')){
                Yii::app()->getClientScript()->registerCssFile("/" . MainConfig::$PATH_CSS . DS . Share::$cssAsset['prof_edit_applic.css']);
                //Yii::app()->getClientScript()->registerScriptFile("/theme/js/dev/pages/prof_edit_applic.js", CClientScript::POS_END);
            }
        }

        $this->render($this->ViewModel->pageEditProfile,
                array('viData' => Share::$UserProfile->getProfileDataEdit(), 'viErrorData' => $res),
                array('htmlTitle' => 'Редактирование профиля')
            );
    }



    public function actionLogout()
    {
        unset(Yii::app()->session['au_uid']);
        unset(Yii::app()->session['au_token']);
        unset(Yii::app()->session['au_exptime']);
        unset(Yii::app()->session['au_us_type']);
        unset(Yii::app()->session['au_us_data']);

        unset(Yii::app()->request->cookies['prommu']);

        $this->redirect(Yii::app()->homeUrl);
    }



    public function actionAuth()
    {
        $res = $this->doAuth();


        // auth ok
        if( $res['auth'] )
        {
            $type = Share::$UserProfile->type;
            if( $type == 2 || $type == 3 ) $this->redirect(MainConfig::$PAGE_PROFILE);
            else $this->redirect(Yii::app()->createUrl('index.php'));

        // auth fail
        } else {
            Yii::app()->user->setFlash('auErrMess', $res['message']);
            $this->redirect(MainConfig::$PAGE_LOGIN);
        } // endif
    }



    public function actionRegister()
    {
        // applicant
        $getS = Yii::app()->getRequest()->getParam('s');
        $getP = Yii::app()->getRequest()->getParam('p');

        if($getS == 2)
        {
            $this->setPageTitle('Завершение регистрации');
            $this->render(MainConfig::$VIEWS_REGISTER_COMPLETE, ['type' => $getS], array('nobc' => '1'));
        }
        else
        {
            if($getP=='' || $getP=='1' || $getP=='2')
            {
                $this->proccessRegister();
            }
            else
            {
                throw new CHttpException(404, 'Error');
            }
        }
    }


    public function actionRegisterMail()
    {
        // aplicant
        $type = Yii::app()->getRequest()->getParam('p');
        if( $type == '1' )
        {
            $data = Share::$UserProfile->getRegisterData($type);
            $view = MainConfig::$VIEWS_REGISTER_APPLICANT;

        // employer
        } else {
            $data = Share::$UserProfile->getRegisterData($type);
            $view = MainConfig::$VIEWS_REGISTER_APPLICANT;
        } // endif

        $this->render($view, array('viData', $data));
    }



    /**
     * Активация пользователя
     */
    public function actionActivate()
    {
        $data = (new Auth())->activateUser();
        Share::$UserProfile->type<1 && $this->redirect($data); // no profile for guest

        $isPopup = Yii::app()->getRequest()->getParam('npopup');
        if($isPopup){
            $city = Yii::app()->getRequest()->getParam('city');
            Subdomain::popupRedirect($city,Share::$UserProfile->id);
        }

        // save data
        if( Yii::app()->getRequest()->isPostRequest && Yii::app()->getRequest()->getParam('email') )
        {
            $res = Share::$UserProfile->saveProfileData();
            if(!$res['err']) $this->redirect(MainConfig::$PAGE_PROFILE);
        }

        $this->setBreadcrumbsEx(
            array('Мой профиль', MainConfig::$PAGE_PROFILE),
            array($title = 'Редактирование профиля', MainConfig::$PAGE_EDIT_PROFILE)
        );
        $this->setPageTitle('Регистрация успешно завершена');
        $arResult = Share::$UserProfile->getProfileDataEdit();

        $this->render($this->ViewModel->pageEditProfile, array('viData'=>$arResult, 'viErrorData'=>$res));
    }



    private function proccessRegister()
    {
        $type = Yii::app()->getRequest()->getParam('p');
        $data = array();


        // register user
        if( Yii::app()->getRequest()->isPostRequest )
        {
            $data = (new Auth())->registerUser($type);
            if( !$data['error'] ) $this->redirect(Yii::app()->createUrl(MainConfig::$PAGE_REGISTER, array('p' => $type, 's' => 2)));
        }

        if( $type == '1' )
        {
            $view = MainConfig::$VIEWS_REGISTER_APPLICANT;
            Yii::app()->getClientScript()->registerScriptFile('//vk.com/js/api/openapi.js', CClientScript::POS_END);

        } else { $view = MainConfig::$VIEWS_REGISTER_COMPANY; } // endif

        $this->render($view, array('viData' => $data), array('nobc' => '1'));
    }


    public function actionVacpub()
    {
        // no profile for guest
        Share::$UserProfile->type <> 3 && $this->redirect(MainConfig::$PAGE_INDEX);

        if( Yii::app()->getRequest()->isPostRequest && Yii::app()->getRequest()->getParam('vacancy-title') )
        {
            $res = (new Vacancy())->saveVacpubData();
            if(!$res['err']){
                $this->redirect(MainConfig::$PAGE_VACANCY . DS . $res['idvac']);
            }
        }

        $this->setBreadcrumbs($title = 'Публикация вакансии', MainConfig::$PAGE_VACPUB);
        $this->render($this->ViewModel->pageVacpub,
                array(
                    'viData' => (new Vacancy())->getVacPubFormData(),
                    'IS_PUBDATA' => 1
                ),
                array('htmlTitle' => $title)
            );
    }



    public function actionVacactivate()
    {

        Share::$UserProfile->type <> 3 && $this->redirect(MainConfig::$PAGE_INDEX);

        $res = (new Vacancy())->vacActivate();

        if( $res['error'] < 0 ) {
            $this->redirect(MainConfig::$PAGE_INDEX);
        } else {
            Yii::app()->user->setFlash('Message', array('type' => '-green', 'message' => $res['message']));
            $this->redirect(MainConfig::$PAGE_VACANCY . DS . $res['id']);
        }
    }



    public function actionVacancies()
    {
        // no profile for guest
        Share::$UserProfile->type != 3 && $this->redirect(MainConfig::$PAGE_LOGIN);
        $vac = explode("&", $_GET['vacancy']);
        $coun = count($vac);

            for($i = 0; $i < $coun; $i ++) {
                Yii::app()->db->createCommand()
                ->update('empl_vacations', array(
                'count'=> 1),
                    'id=:id', array(':id'=>$vac[$i]));
            }

        $Vacancy = new Vacancy();

        $arCount = $Vacancy->getSeсtionsVacCount()['cnt'];
        $pages = new CPagination($arCount['vac']);
        $pages->pageSize = MainConfig::$DEF_PAGE_LIMIT;
        $pages->applyLimit($Vacancy);
        $data['rate'] = Share::$UserProfile->getProfileDataView();
        $data = array_merge($data, $Vacancy->getVacancies());

        $this->setBreadcrumbs($title = 'Мои вакансии', MainConfig::$PAGE_VACANCIES);
        $this->render($this->ViewModel->pageVacancies,
                array('viData' => $data, 'arCount'=>$arCount, 'pages' => $pages),
                array('htmlTitle' => $title)
            );
    }

    public function actionVacarhive()
    {
        Share::$UserProfile->type != 3 && $this->redirect(MainConfig::$PAGE_LOGIN);

        $Vacancy = new Vacancy();
        $data = $Vacancy->getSeсtionsVacCount();
        $pages = new CPagination($data['cnt']['arc']);
        $pages->pageSize = MainConfig::$DEF_PAGE_LIMIT;
        $pages->applyLimit($Vacancy);
        $data['rate'] = Share::$UserProfile->getProfileDataView();
        $arVacs = $Vacancy->getVacanciesArh();

        $this->setBreadcrumbs($title = 'Архив вакансий', MainConfig::$PAGE_VACARHIVE);
        $this->render($this->ViewModel->pageVacancArh,
                array('viData' => $data, 'arVacs'=>$arVacs, 'pages' => $pages),
                array('htmlTitle' => $title)
            );
    }


    public function actionVacedit()
    {
        // no profile for guest
        Share::$UserProfile->type <> 3 && $this->redirect(MainConfig::$PAGE_INDEX);

        if( Yii::app()->getRequest()->isPostRequest && Yii::app()->getRequest()->getParam('save') )
        {
            $res = (new Vacancy())->saveVacpubData();
            if( $res['err'] ) {
            } else {
                $this->redirect(MainConfig::$PAGE_VACANCY . DS . $res['idvac']);
            } // endif
        } // endif

        $idvac = filter_var(Yii::app()->getRequest()->getParam('id', false), FILTER_SANITIZE_NUMBER_INT);
        $this->setBreadcrumbsEx(array('Вакансия', MainConfig::$PAGE_VACANCY . DS . $idvac), array($title = 'Редактирование вакансии', MainConfig::$PAGE_VACANCY_EDIT));

        $idvac && (Yii::app()->session['editVacId'] = $idvac);

       /* Yii::app()->getClientScript()->registerCssFile("/jslib/jquery-autocomplete/styles.css");
        Yii::app()->getClientScript()->registerScriptFile("/jslib/jquery-autocomplete/jquery.autocomplete.min.js", CClientScript::POS_END);
        Yii::app()->getClientScript()->registerScriptFile("/theme/js/dev/pages/vac_edit.js", CClientScript::POS_END);*/


        $this->render($this->ViewModel->pageVacpub,
                array('viData' => (new Vacancy())->getVacEditFormData()));
    }



    public function actionResponses()
    {
         // no profile for guest
        in_array(Share::$UserProfile->type, [2,3]) || $this->redirect(MainConfig::$PAGE_LOGIN);
        $tab = Yii::app()->getRequest()->getParam('tab');
        $activeFilterLink = $tab == 'invites' ? 1 : 0;
        $vac = explode("&", $_GET['vacancy']);
        $coun = count($vac);
        if(Share::$UserProfile->type == 3) {
            for($i = 0; $i < $coun; $i ++) {
                Yii::app()->db->createCommand()
                ->update('vacation_stat', array(
                'id_jobs'=> 1),
                    'id_vac=:id_vac', array(':id_vac'=>$vac[$i]));
            }
        } elseif(Share::$UserProfile->type == 2) {
            for($i = 0; $i < $coun; $i ++) {
                Yii::app()->db->createCommand()
                ->update('vacation_stat', array(
                'isend'=> 1),
                    'id_vac=:id_vac', array(':id_vac'=>$vac[$i]));
            }
        }
        $Responses = Share::$UserProfile->type == 2 ? new ResponsesApplic() : new ResponsesEmpl();

        // results per page
        $pages=new CPagination($Responses->getResponsesCount(['type' => $tab]));
        $pages->pageSize = MainConfig::$DEF_PAGE_LIMIT;
        $pages->applyLimit($Responses);

        Yii::app()->getClientScript()->registerCssFile("/theme/css/private/page-reviews.css");
        $this->setBreadcrumbs($title = 'Отклики на вакансии', MainConfig::$PAGE_RESPONSES);

        $this->render($this->ViewModel->pageResponses,
                array('viData' => $Responses->getResponses(['type' => $tab])
                    , 'pages' => $pages
                    , 'activeFilterLink' => $activeFilterLink),
                array('htmlTitle' => $title)
            );
    }



    public function actionIm()
    {
         // no profile for guest
        in_array(Share::$UserProfile->type, [2,3]) || $this->redirect(MainConfig::$PAGE_LOGIN);

        $id = filter_var(Yii::app()->getRequest()->getParam('id'), FILTER_SANITIZE_NUMBER_INT);
        $idto = filter_var(Yii::app()->getRequest()->getParam('new'), FILTER_SANITIZE_NUMBER_INT);
        if($idto){
            if(Share::$UserProfile->type == 2) {
                $Mess = new ImApplic();
            } else {
                $Mess = new ImEmpl();
            }
           $result = $Mess->accessMessage($idto);
           var_dump($result);
        }
        
        // chat model
        //echo memory_get_usage() ;
        $Im =  Share::$UserProfile->makeChat();

        // скрипты
        if( Share::$UserProfile->type == 2 ) $script = 'im_applic.js';
        else $script = 'im_empl.js';
        Yii::app()->getClientScript()->registerScriptFile("/theme/js/dev/pages/{$script}", CClientScript::POS_END);


        // читаем чат
         if( isset($_GET['id'])  || isset($_GET['new']) && $result['user']['ismoder'] == 1 && $idto == $result['new']['id'])
        {
            $data = $Im->getMessViewData($id, $idto);
            $view = $this->ViewModel->pageMessView;

            Yii::app()->getClientScript()->registerCssFile("/jslib/magnific-popup/magnific-popup-min.css");
            Yii::app()->getClientScript()->registerCssFile('/' . MainConfig::$PATH_CSS . '/' . Share::$cssAsset['im.css']);

            Yii::app()->getClientScript()->registerScriptFile("/jslib/nicedit/nicEdit.js", CClientScript::POS_BEGIN);
            Yii::app()->getClientScript()->registerScriptFile("/jslib/magnific-popup/jquery.magnific-popup.min.js", CClientScript::POS_END);

            // добавляем класс к контенту
            $this->ViewModel->addBodyClass('l-im-chat');

            // чужой диалог
            if( $data['error'] == 100 ) $view = $this->redirect(MainConfig::$PAGE_IM);

            if( isset($_GET['new']) ) $isNew = $_GET['new'];
        }
        elseif(isset($_GET['vac']))
        {
            // results per page
            $pages=new CPagination($Im->getChatsCounts(Share::$UserProfile->id));
            $pages->pageSize = 5;
            $pages->applyLimit($Im);

            $data = $Im->getChatsVac();

            $view = $this->ViewModel->pageMessages;
        }
        else
        {
            // results per page
            $pages=new CPagination($Im->getChatsCount(Share::$UserProfile->id));
            $pages->pageSize = 5;
            $pages->applyLimit($Im);

            $data = $Im->getChats();

            $view = $this->ViewModel->pageMessages;
        } // endif

        $this->setBreadcrumbs($title = 'Мои сообщения', MainConfig::$PAGE_IM);

        $this->render($view,
                array('viData' => $data, 'isNew' => $isNew, 'idTm' => $id, 'pages' => $pages));
    }



    /**
     * Выставление рейтинга после окончания вакансии
     */
    public function actionSetrate()
    {
        in_array(Share::$UserProfile->type, [2,3]) || $this->redirect(MainConfig::$PAGE_LOGIN);

        $title = 'Добавить Рейтинг/Отзыв';
        //$this->setBreadcrumbs($title,$this->ViewModel->pageSetRate);
        Yii::app()->getClientScript()->registerCssFile('/theme/css/private/page-setrate.css');
        Yii::app()->getClientScript()->registerScriptFile('/theme/js/private/page-setrate.js', CClientScript::POS_END);
        $Responses = Share::$UserProfile->makeResponse();

        if( Yii::app()->getRequest()->isPostRequest )
        {
            $res = $Responses->saveRateData();

        }
        else {

            if(Share::$UserProfile->type == 3){
            $viData = $Responses->setRate();
            $id = $viData['user']['pid'];
            $idUs = $viData['user']['iduspromo'];
            $data = $Responses->loadRatingPageDatas($id, $idUs);

        }
        elseif(Share::$UserProfile->type == 2){
            $viData = $Responses->setRate();
            $id = $viData['user']['eid'];
            $idUs = $viData['user']['idusempl'];
            $data = $Responses->loadRatingPageDatas($id, $idUs);

        }

        }


        $this->render($this->ViewModel->pageSetRate,
                array('viData' => $Responses->setRate(), 'data' => $data),

                array(
                    'saveResp' => $res,
                    'htmlTitle' => $title
                )
            );
    }


    /**
     *     Страница оплаты для юр. лиц
     */
    public function actionPayment()
    {
        // no profile for guest
        in_array(Share::$UserProfile->type, [2,3]) || $this->redirect(MainConfig::$PAGE_LOGIN);

        $title = 'Оплата услуг PROMMU';
        $this->setBreadcrumbsEx(
            array('Мой профиль', MainConfig::$PAGE_PROFILE),
            array($title = 'Оплата услуг', MainConfig::$PAGE_PAYMENT)
        );
        $this->ViewModel->addContentClass('page-payment');
        Yii::app()->getClientScript()->registerCssFile(Yii::app()->baseUrl.'/theme/css/page-payment.css');
        Yii::app()->getClientScript()->registerScriptFile(Yii::app()->baseUrl.'/theme/js/payment-page.js', CClientScript::POS_END);
        if(!Yii::app()->getRequest()->isPostRequest){
            throw new CHttpException(404, 'Error');
        }

        $prommuOrder = new PrommuOrder();
        if($_POST['vacsms']){

            $vac = $_POST['vacsms'];
            $user = explode(",", $_POST['user']);
            $account = $_POST['account'];
            $account.=".$vac.sms";
            $text = $_POST['text'];
            $count = count($user);
            $type = "sms";
            $date = date("Y-m-d");
            for($i = 0; $i < $count; $i ++)
            {
                $admin = $_POST['account'];
                $use = $user[$i];
                $sum = $prommuOrder->servicePrice($_POST['account'], "sms");
                $postback = 0;
                $status = 0;
                $prommuOrder->serviceOrderSms($admin,$sum, $status, $postback, $date ,$date, $vac, $type, $text, $use);
                $summa+=$sum;
            }
            for($i = 0; $i < $count; $i ++) {
                $use = $user[$i];
                $account.= ".$use";
            }
            $publi = "84661-fc398";
           $link = "https://unitpay.ru/pay/$publi?sum=$summa&account=$account&desc=$vac";
           $this->redirect($link);

        } elseif($_POST['vacemail']){

            $vac = $_POST['vacemail'];
            $user = explode(",", $_POST['users']);
            $account = $_POST['employer'];
            $account.=".$vac.email";
            $text = $_POST['vacemail'];
            $count = count($user);
            $type = "email";
            $date = date("Y-m-d");
            $sum = $prommuOrder->servicePrice($_POST['employer'], "email");
            if($sum > 0){
                for($i = 0; $i < $count; $i ++)
                {
                $admin = $_POST['employer'];
                $use = $user[$i];
                $sum = $prommuOrder->servicePrice($_POST['employer'], "email");
                $postback = 0;
                $status = 0;
                $prommuOrder->serviceOrderEmail($admin,$sum, $status, $postback, $date ,$date, $vac, $type, $text, $use);
                $summa+=$sum;
                }
                for($i = 0; $i < $count; $i ++) {
                    $use = $user[$i];
                    $account.= ".$use";
                }
                $publi = "84661-fc398";
               $link = "https://unitpay.ru/pay/$publi?sum=$summa&account=$account&desc=$vac";
                $this->redirect($link);
           } else {

               for($i = 0; $i < $count; $i ++)
                {
                 $admin = $_POST['employer'];
                $use = $user[$i];
                 $postback = 0;
                $status = 1;
                $prommuOrder->serviceOrderEmail($admin,$sum, $status, $postback, $date ,$date, $vac, $type, $text, $use);

            $sql = "SELECT  e.title
                FROM empl_vacations e
                WHERE e.id = $vac";
                $vacancy = Yii::app()->db->createCommand($sql)->queryAll();

             $sql = "SELECT  u.email, e.name, e.firstname, e.lastname
                FROM employer e
                LEFT JOIN user u ON u.id_user = e.id_user
                WHERE e.id_user = $admin";
            $empl = Yii::app()->db->createCommand($sql)->queryAll();

            $sql = "SELECT  e.id, u.email, e.firstname, e.lastname
                FROM resume e
                LEFT JOIN user u ON u.id_user = e.id_user
                WHERE e.id_user = $use";
            $resume = Yii::app()->db->createCommand($sql)->queryAll();

            $message = '<p style="font-size:16px;"Работодатель'.$admin.' '.$empl[0]['lastname'].' '.$empl[0]['firstname'].'<br/> </p>
                    <br/>

                <p style=" font-size:16px;">
                 <br/>Компания: '.$empl[0]['name'].'<br/>
              Приглашает на вакансию:  '.$name.' '.$vacancy[0]['title'].'<br/>
              Ссылка на вакансию:  <a href="https://prommu.com/vacancy/'.$name.'">'.$vacancy[0]['title'].'</a><br/>

                    <br/>';
                    if(strpos($resume[0]['email'], "@") !== false){
                        Share::sendmail($empl[0]['email'], "Prommu.com. Приглашение На Вакансию", $message);
                        Share::sendmail('denisgresk@gmail.com', "Prommu.com. Приглашение На Вакансию", $message);
                    }
                }
                Yii::app()->user->setFlash('success', array('event'=>'email'));

               $link = "https://prommu.com/services";
               $this->redirect($link);

            }

        } elseif($_POST['vacpush']){

            $vac = $_POST['vacpush'];
            $user = explode(",", $_POST['users']);
            $account = $_POST['employer'];
            $account.=".$vac.push";
            $text = $_POST['vacpush'];
            $count = count($user);
            $type = "push";
            $date = date("Y-m-d h-i-s");
            $sum = $prommuOrder->servicePrice($_POST['employer'], "push");
            if($sum > 0){
            for($i = 0; $i < $count; $i ++)
            {
                $admin = $_POST['employer'];
                $use = $user[$i];
                $sum = $prommuOrder->servicePrice($_POST['employer'], "push");
                $postback = 0;
                $status = 0;
                $prommuOrder->serviceOrderEmail($admin,$sum, "0", $postback, $date ,$date, $vac, $type, $text, $use);
                $summa+=$sum;

            }
            $publi = "84661-fc398";
               $link = "https://unitpay.ru/pay/$publi?sum=$summa&account=$account&desc=$vac";
               $this->redirect($link);
            } else {
                for($i = 0; $i < $count; $i ++)
                {
                $admin = $_POST['employer'];
                $use = $user[$i];
                $sum = $prommuOrder->servicePrice($_POST['employer'], "push");
                $postback = 0;
                $status = 0;
                $prommuOrder->serviceOrderEmail($admin,$sum, "1", $postback, $date ,$date, $vac, $type, $text, $use);
                $summa+=$sum;

                }
                 Yii::app()->user->setFlash('success', array('event'=>'push'));

               $link = "https://prommu.com/services";
               $this->redirect($link);


            }



        } elseif($_POST['vacanc']){


            $from = $_POST['from'];
            $to = $_POST['to'];
            $account = $_POST['account'];
            $name = $_POST['vacanc'];
            $postback = 0;
            $type = "vacancy";
            $count = count($name);

            for($i = 0; $i < $count; $i ++)
            {
                $date =  (strtotime ($to[$i])- strtotime ($from[$i]))/(60*60*24);
                $sums = $prommuOrder->servicePrice($_POST['employer'], "vacancy");
                $sum = $date * $sums;
                $postback = 0;
                $status = 0;
                $prommuOrder->serviceOrder($account,$sum, $status, $postback, $from[$i], $to[$i], $name[$i], $type);
                $summa+=$date;
            }
           $date = 0;
            for($i = 0; $i < $count; $i ++) {
                $vac = $name[$i];
                $account.= ".$vac";
            }

           $publi = "84661-fc398";
           $link = "https://unitpay.ru/pay/$publi?sum=$summa&account=$account&desc=$summa";
           $this->redirect($link);
        } elseif($_POST['vacrepost']){

            $vac = $_POST['vacrepost'];
            $user = explode(",", $_POST['network']);
            $account = $_POST['employer'];
            $account.=".$vac.repost";
            $text = $_POST['vacrepost'];
            $count = count($user);
            $type = "push";
            $date = date("Y-m-d h-i-s");
            for($i = 0; $i < $count; $i ++)
            {
                $admin = $_POST['employer'];
                $use = $user[$i];
                $sum = $prommuOrder->servicePrice($_POST['employer'], "repost");
                $postback = 0;
                $status = 0;
                $prommuOrder->serviceOrderEmail($admin,$sum, "0", $postback, $date ,$date, $vac, $type, $text, $use);
                $summa+=$sum;

            }

            $publi = "84661-fc398";
           $link = "https://unitpay.ru/pay/$publi?sum=$summa&account=$account&desc=$vac";
           $this->redirect($link);

        }elseif($_POST['sms']) {
            $vac = new Vacancy();
            $vac = $vac->getVacancyInfo($_POST['sms'][0]);
            $mech = $vac[0]['id_attr'];

            $result = $vac->VacMech($mech);

            $data = $_POST['sms'];
            $data['sms'] = 1;
            //$model = new PrommuOrder;
            //$data['price'] = $model->servicePrice(Share::$UserProfile->id,'sms');
            $view = MainConfig::$PAGE_PAYMENT_VIEW;
            $this->render($view, array('viData' => $data, 'User' => $result), array('nobc' => '1'));
        }
        else {      // premium
            $data = array(
                'service' => Yii::app()->getRequest()->getParam('service'),
                'vacancies' => Yii::app()->getRequest()->getParam('vacancy'),
                /*
                'from' => Yii::app()->getRequest()->getParam('from'),
                'to' => Yii::app()->getRequest()->getParam('to'),
                'period' => Yii::app()->getRequest()->getParam('period'),
                'personal' => Yii::app()->getRequest()->getParam('personal'),
                'name' => Yii::app()->getRequest()->getParam('name'),
                'inn' => Yii::app()->getRequest()->getParam('inn'),
                'kpp' => Yii::app()->getRequest()->getParam('kpp'),
                'account' => Yii::app()->getRequest()->getParam('account'),
                */
            );
            //$data = $_POST['vacancy'];
            //$data['premium'] = 1;
            $model = new PrommuOrder;
            $data['price'] = $model->servicePrice(Share::$UserProfile->id,'vacancy');
            $view = MainConfig::$PAGE_PAYMENT_VIEW;
            $this->render($view, array('viData' => $data),  array( 'htmlTitle' => $title ));
        }
    }
    /*
    *       Отзыв / рейтинг
    */
    public function actionReviews()
    {
        in_array(Share::$UserProfile->type, [2,3]) || $this->redirect(MainConfig::$PAGE_LOGIN);

        $Responses = Share::$UserProfile->type == 2 ? new ResponsesApplic() : new ResponsesEmpl();
        $pages = new CPagination($Responses->getResponsesRatingCount());
        $pages->pageSize = MainConfig::$DEF_PAGE_LIMIT;
        $pages->applyLimit($Responses);

        Yii::app()->getClientScript()->registerCssFile("/theme/css/private/page-reviews.css");

        if(Share::$UserProfile->type==2)
            $title = 'Оценка работодателей';
        else
            $title = 'Оценка персонала';

        $this->setBreadcrumbs($title, MainConfig::$PAGE_REVIEWS_VIEW);

        $this->render(MainConfig::$PAGE_REVIEWS_VIEW,
                array('viData' => $Responses->getResponsesRating(), 'pages' => $pages),
                array('htmlTitle' => $title)
            );
    }

    /*
    *   Аналитика
    */
    public function actionAnalytics()
    {
        in_array(Share::$UserProfile->type, [2,3]) || $this->redirect(MainConfig::$PAGE_LOGIN);

        $status = "0,1,2,3,4,5,6";
        $statusEnd = "7";
        $Vacancy = new Vacancy();
        $Termostat = new Termostat();
        $arDates = $Termostat->getDates();
        $count = $Vacancy->getVacanciesCount();
        $viData = $Vacancy->getVacancies();
        $countView = $Termostat->getPromoView(Share::$UserProfile->id, $arDates);
        $countResponse = $Termostat->getPromoResponse(Share::$UserProfile->id, 2, $status, $arDates);
        $countInvite = $Termostat->getPromoResponse(Share::$UserProfile->id, 1, $status, $arDates);
        $countProject = $Termostat->getPromoResponse(Share::$UserProfile->id, '1,2', $statusEnd, $arDates);
        $arResult = array(
            'viData' => $viData,
            'count' => $count,
            'countView' => $countView,
            'countResponse' => $countResponse,
            'countInvite' => $countInvite,
            'countProject'=> $countProject,
            'arDates' => $arDates
        );

        if(Yii::app()->request->isAjaxRequest){
            $this->renderPartial(MainConfig::$AJAX_ANALYTICS, $arResult, false, true);
        }
        else{
            Yii::app()->getClientScript()->registerCssFile('/theme/css/analytics.css');
            Yii::app()->getClientScript()->registerScriptFile('https://www.gstatic.com/charts/loader.js');
            Yii::app()->getClientScript()->registerScriptFile('/theme/js/analytics.js', CClientScript::POS_END);
            $title = 'Аналитика';
            $this->setBreadcrumbs($title, MainConfig::$PAGE_ANALYTICS);
            $this->render(MainConfig::$VIEWS_ANALYTICS, $arResult, array('htmlTitle' => $title));
        }
    }

    public function actionApi()
    {
        $api = $_GET['api'];
        $id = Share::$UserProfile->id;
        $name = Share::$UserProfile->fio;

          $sql = "SELECT  u.email
                FROM employer e
                LEFT JOIN user u ON u.id_user = e.id_user
                WHERE e.id_user = {$id}";
         $resultat = Yii::app()->db->createCommand($sql)->queryAll();

        $idus = 2054;
         $sql = "SELECT ca.id
                FROM  chat_theme ca
                WHERE ca.id_use = {$id} AND ca.id_usp = {$idus}";
        /** @var $res CDbCommand */
        $res = Yii::app()->db->createCommand($sql);
        $data = $res->queryAll();
        // return $data;
        // var_dump($data[0]['id']);
        if(!$data[0]['id']){
            if($api == 1 || $api == 2){
        $texts = "Здравствуйте, я PROMMU BOT. Метод https://prommu.com/api.promo_search подготовлен. Документация выгружена в файл по ссылке https://prommu.com/api-help#PROMO_SEARCH.
            При использовании API ресурсами сервиса PROMMU вы соглашаетесь с пользовательским соглашением и принимаете условия использования https://prommu.com/api-private";
            $message = $texts;
            $new = $id;

            $idTm = $theme;
            $Im = new ImApplic();
            $figaro = compact('message', 'new', 'idus','idTm', 'theme');
            $resu = $Im->sendUserMessages($figaro);

             $res = Yii::app()->db->createCommand()
            ->insert('feedback', array(
                'type' => 3,
                'name' => 'API'.$name,
                'theme' => 'API запрос',
                'text' => 'Запрос на выгрузку',
                'email' => $resultat[0]['email'],
                'crdate' => date("Y-m-d"),
                'chat' => $resu['idtm'] ));

        }

        }
        else {

             $texts = " Метод https://prommu.com/api.promo_search подготовлен. Документация выгружена в файл по ссылке https://prommu.com/api-help#PROMO_SEARCH.
            При использовании API ресурсами сервиса PROMMU вы соглашаетесь с пользовательским соглашением и принимаете условия использования https://prommu.com/api-private";
            $message = $texts;
            $new = 0;

            $idTm = $data[0]['id'];
            $Im = new ImApplic();
            $figaro = compact('message', 'new', 'idus','idTm');
            $resu = $Im->sendUserMessages($figaro);



        }
         Yii::app()->db->createCommand()
                        ->insert('service_cloud', array('id_user' => $id,
                                'name' => $id,
                                'type' => "api",
                                'bdate' => date("Y-m-d h-i-s"),
                                'edate' => date("Y-m-d h-i-s"),
                                'status' => 1,
                                'sum' => 0,
                                'text' => "Запрос на выгрузку API",
                                'user' => "api"
                            ));

            Yii::app()->user->setFlash('Message', array('mess'=>'Ваша заявка на формирование запроса команд API сформирована. Все нужные команды Вы сможете взять из сформировавшегося окна диалогов. Также в нём можно будет задать вопросы администратору по возникшим техническим вопросам'));
            $this->redirect("https://prommu.com/user/im");






    }
    /*
    *   Страница настроек
    */
    public function actionSettings()
    {
        in_array(Share::$UserProfile->type, [2,3]) || $this->redirect(MainConfig::$PAGE_LOGIN);

        $idus = Share::$UserProfile->id;

       if($_POST['save']){
        $cloudSett = json_encode($_POST);
         $result = Yii::app()->db->createCommand()
                ->update('user', array(
                    'setting' => $cloudSett
                ), 'id_user=:id', array(':id' => $idus));
        (new Termostat)->setUserDataTime($idus, $_POST, $_POST['analytic']);
        if(Share::$UserProfile->type == 2)
        $result = Yii::app()->db->createCommand()
                ->update('resume', array(
                    'isman' => $_POST['sex'],
                ), 'id_user=:id', array(':id' => $idus));
            }



        $title = 'Настройки профиля';
        $this->setBreadcrumbs($title, MainConfig::$PAGE_SETTINGS);

        $data = Yii::app()->db->createCommand()
            ->select('a.val phone, u.email, u.confirmEmail, u.confirmPhone, u.setting')
            ->from('user u')
            ->leftJoin('user_attribs a', 'a.id_us=u.id_user AND a.id_attr=1')
            ->where('u.id_user=:id_user', array(':id_user' => $idus))
            ->queryRow();
        $data['setting'] = json_decode($data['setting']);

        $this->render(MainConfig::$VIEWS_SETTINGS, array('viData' => $data), array('htmlTitle' => $title));
    }
    /*
    *   Страница настроек
    */
    public function actionSnapshottest()
    {
        in_array(Share::$UserProfile->type, [2,3]) || $this->redirect(MainConfig::$PAGE_LOGIN);

        $title = 'Снимок';
        Yii::app()->getClientScript()->registerCssFile(Yii::app()->baseUrl.'/jslib/cropperjs/cropper.min.css');
        Yii::app()->getClientScript()->registerScriptFile(Yii::app()->baseUrl.'/jslib/cropperjs/cropper.min.js', CClientScript::POS_END);
        Yii::app()->clientScript->registerMetaTag('noindex,nofollow','robots', null, array());
        $this->render('/user/page-edit-profile-applicant/page-edit-photo-tpl2', array(), array('htmlTitle' => $title));
    }
    /*
    *   Удалить вакансию
    */
    public function actionVacdelete()
    {
        Share::$UserProfile->type <> 3 && $this->redirect(MainConfig::$PAGE_INDEX);

        $page = filter_var(Yii::app()->getRequest()->getParam('page'), FILTER_SANITIZE_NUMBER_INT);
        $res = (new Vacancy())->vacDelete();
        if( $res['error'] < 0 ) {
            $this->redirect(MainConfig::$PAGE_INDEX);
        }
        else {
            Yii::app()->user->setFlash('Message', array('type' => '-green', 'message' => $res['message']));
            $url = DS . ($page ? MainConfig::$PAGE_VACANCIES : MainConfig::$PAGE_VACARHIVE);
            $this->redirect($url);
        }
    }
    /*
    *
    */
    public function actionSchedule()
    {
        $status = "0,1,2,3,4,5,6";
        $statusEnd = "7";
        $Termostat = new Termostat();
        $arDates = $Termostat->getDates();
        $countView = $Termostat->getPromoView(Share::$UserProfile->id, $arDates);
        $countResponse = $Termostat->getPromoResponse(Share::$UserProfile->id, 2, $status, $arDates);
        $countInvite = $Termostat->getPromoResponse(Share::$UserProfile->id, 1, $status, $arDates);
        $countProject = $Termostat->getPromoResponse(Share::$UserProfile->id, '1,2', $statusEnd, $arDates);
        $arResult = array(
            'count' => $count,
            'countView' => $countView,
            'countResponse' => $countResponse,
            'countInvite' => $countInvite,
            'countProject'=> $countProject,
            'arDates' => $arDates
        );

        $this->render('page-schedule-view', $arResult);
    }
    /*
    *
    */
    public function actionVacPostToSocial()
    {
        Share::$UserProfile->type <> 3 && $this->redirect(MainConfig::$PAGE_INDEX);

        $page = filter_var(Yii::app()->getRequest()->getParam('page'), FILTER_SANITIZE_NUMBER_INT);
        $id = filter_var(Yii::app()->getRequest()->getParam('id'), FILTER_SANITIZE_NUMBER_INT);
        $res = (new Vacancy())->VacPostToSocial();
        Yii::app()->user->setFlash('Message', array('type' => '-green', 'message' => $res['message']));
        $url = $page ? (DS . MainConfig::$PAGE_VACANCIES) : (MainConfig::$PAGE_VACANCY . DS . $id);
        $this->redirect($url);
    }
    /*
    *   Мои проекты
    */
    public function actionProjects()
    {
        $type = Share::$UserProfile->type;
        !in_array($type, [2,3]) && $this->redirect(MainConfig::$PAGE_INDEX);

        $id = Yii::app()->getRequest()->getParam('id');
        $view = $type==3
            ? MainConfig::$VIEW_EMP_PROJECT_LIST
            : MainConfig::$VIEW_APP_PROJECT_LIST;
            
        $model = new Project();
    
        if($id=='new') { // новый проект
            if(Yii::app()->request->isAjaxRequest) {
                $this->renderPartial(
                    MainConfig::$VIEWS_SERVICE_ANKETY_AJAX,
                    array('viData' => (new Services())->getFilteredPromos()),
                    false,
                    true
                );
            }
            else {
                $data = (new Services())->getFilteredPromos();
            }
            $view = MainConfig::$VIEW_PROJECT_NEW;
        }
        elseif($id>0) { // существующий
            if(!$model->hasAccess($id))
                $this->redirect(MainConfig::$PAGE_PROJECT_LIST);

            switch (Yii::app()->getRequest()->getParam('section')) {
                case 'staff':
                    $data = (new Services())->getFilteredPromos();
                    $view = MainConfig::$VIEW_PROJECT_ITEM_STAFF;
                    break;
                case 'index':
                    $data = $model->getProject();
                    $view = MainConfig::$VIEW_PROJECT_ITEM_INDEX;
                    break;
                case 'geo':
                    $view = MainConfig::$VIEW_PROJECT_ITEM_GEO;
                    break;
                case 'route':
                    $view = MainConfig::$VIEW_PROJECT_ITEM_ROUTE;
                    break;
                case 'tasks':
                    $view = MainConfig::$VIEW_PROJECT_ITEM_TASKS;
                    break;
                case 'report':
                    $view = MainConfig::$VIEW_PROJECT_ITEM_REPORT;
                    break;
                case 'address-edit':
                    $view = MainConfig::$VIEW_PROJECT_ITEM_ADR_CHANGE;
                    break;
                case 'users-select':
                    $view = 'projects/project-users-select';
                    break;
                default:
                    $data = $model->getProject($id);
                    $view = MainConfig::$VIEW_PROJECT_ITEM;
                    break;
            }
        }
        else{         
            $view = MainConfig::$VIEW_EMP_PROJECT_LIST;
            $data = $model->getProjectEmployer();
        }

        $this->render($view, array('viData' => $data));
    }
}
