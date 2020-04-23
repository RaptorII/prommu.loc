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
        $this->redirect(MainConfig::$PAGE_PROJECT_LIST);

      }

      public function actionOutstaffing(){
        $order = new PrommuOrder();
        $order->outstaffing($_POST);
        Yii::app()->user->setFlash('success', '1');
        $this->redirect(MainConfig::$PAGE_SERVICES);
      }
  /**
   *
   */
  public function actionPostback()
  {
    $db = Yii::app()->db;
    $method = $_GET['method'];
    $account = $_GET['params']['account'];
    $orderSum = (int)$_GET['params']['orderSum'];
    $unitpayId = $_GET['params']['unitpayId'];
    if($method == "check"){
      $data['result']['message'] = "Запрос успешно обработан";
      $data = json_encode($data);
      echo $data;
      $account = $_GET['params']['account'];
      $arr = explode(".", $account);
      $account = $arr[0];
      $count = count($arr);
      if($arr[2] == "sms"){

        $name = $arr[1];
        $user = $arr[3];
        $res = $db->createCommand()
          ->update('service_cloud', array(
            'key'=> $unitpayId,
          ), 'id_user=:id_user AND name=:name AND stack=:stack', array(':id_user' => "$account", ':name' => "$name", ':stack' => $user));

      }elseif($arr[2] == "push"){

        $name = $arr[1];
        $user = $arr[3];
        $res = $db->createCommand()
          ->update('service_cloud', array(
            'key'=> $unitpayId,
          ), 'id_user=:id_user AND name=:name AND stack=:stack', array(':id_user' => "$account", ':name' => "$name", ':stack' => $user));

      } elseif($arr[2] == "email"){

        $name = $arr[1];
        $user = $arr[3];
        $res = $db->createCommand()
          ->update('service_cloud', array(
            'key'=> $unitpayId,
          ), 'id_user=:id_user AND name=:name AND stack=:stack', array(':id_user' => "$account", ':name' => "$name", ':stack' => $user));

      }
      else
      {
        $arIdOrder = [];
        for ($i=1, $n=count($arr); $i<($n-2); $i++)
        {
          $arIdOrder[] = $arr[$i];
        }
        $db->createCommand()
          ->update(
            'service_cloud',
            ['key'=>$unitpayId],
            ['and','id_user=:id',['in','id',$arIdOrder]],
            [':id'=>$account]
          );
      }

    }
    if ($method == "pay")
    {
      $model = new PrommuOrder();
      $arParams = explode(".", $_GET['params']['account']);
      if(count($arParams)<4)
      {
        return false;
      }
      $id_user = reset($arParams); // service_cloud => id_user
      $id_vacancy = next($arParams); // service_cloud => name
      $transaction = end($arParams); // service_cloud => stack
      $serviceType = prev($arParams); // service_cloud => type

      $email = $db->createCommand()
        ->select('email')
        ->from('user')
        ->where('id_user=:id',[':id'=>$id_user])
        ->queryScalar();
      // уведомление для работодателя
      Mailing::set(20, ['email_user' => $email]);

      if (in_array($serviceType, ['email', 'sms', 'push']))
      {
        $model->autoOrder($serviceType, $transaction, $id_user, $id_vacancy);
      }
      elseif ($serviceType=='vacancy') // услуга Премиум
      {
        $arIdOrder = [];
        for ($i=1, $n=count($arParams); $i<($n-2); $i++)
        {
          $arIdOrder[] = $arParams[$i];
        }
        $arIdVacs = $db->createCommand()
          ->select('name')
          ->from('service_cloud')
          ->where(['in','id',$arIdOrder])
          ->queryColumn();

        $db->createCommand()
          ->update(
            'service_cloud',
            ['status' => 1],
            ['and', 'id_user=:id', ['in','id',$arIdOrder]],
            [':id'=>$id_user]
          );

        $date = date("Y-m-d");
        $db->createCommand()
          ->update(
            'empl_vacations',
            ['ispremium'=>1, 'crdate'=>$date, 'mdate'=>$date],
            ['in','id',$arIdVacs]
          );
      }
      elseif ($serviceType=='upvacancy') // Поднятие вакансии
      {
        $arIdOrder = [];
        for ($i=1, $n=count($arParams); $i<($n-2); $i++)
        {
          $arIdOrder[] = $arParams[$i];
        }
        // берем последний ID вакансий
        $lastId = $db->createCommand()
          ->select('id')
          ->from('empl_vacations')
          ->order('id desc')
          ->queryScalar();

        $arIdVacs = $db->createCommand()
          ->select('name')
          ->from('service_cloud')
          ->where(['in','id',$arIdOrder])
          ->queryColumn();

        $db->createCommand()
          ->update(
            'service_cloud',
            ['status' => 1],
            ['and', 'id_user=:id', ['in','id',$arIdOrder]],
            [':id'=>$id_user]
          );

        $date = date("Y-m-d");
        $db->createCommand()
          ->update(
            'empl_vacations',
            ['sort'=>$lastId, 'crdate'=>$date, 'mdate'=>$date],
            ['in','id',$arIdVacs]
          );
      }
      elseif ($serviceType=='personal-invitation') // personal-invitation
      {
          // set invite after send moneypay-service unitpay
          $arRes = Yii::app()->db->createCommand()
              ->select('id, id_user, name, user')
              ->from('service_cloud')
              ->where(
                  'id_user=:idp',
                  [
                      ':idp'=>$id_user
                  ])
              ->order('id_user DESC')
              ->queryRow();

          $props = [];
          $props['idvac'] = $arRes['name'];

          $users = explode(',', $arRes['user']);

          for($i=0; $i<=count($users); ++$i)
          {
              $props['id'] = Yii::app()->db->createCommand()
                  ->select('id')
                  ->from('resume')
                  ->where(
                      'id_user=:idp',
                      [':idp'=>$users[$i]]
                  )
                  ->queryScalar();

              if ($props['id']) {
                  (new ResponsesApplic())->invitePersonal($props);
              }
          }

          $db->createCommand()
              ->update(
                  'service_cloud',
                  ['status' => 1],
                  ['and', 'id_user=:id', ['in','id',$arIdOrder]],
                  [':id'=>$id_user]
              );

      }
      echo json_encode(['result'=>['message'=>'Запрос успешно обработан']]);
    }
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

  public function actionMessenger($cloud)
  {
    empty($cloud) && $cloud=$_GET;
    $fName = $cloud['fname'] ? $cloud['fname'] : explode(" ", $cloud['name'])[0];
    $lName = $cloud['lname'] ? $cloud['lname'] : explode(" ", $cloud['name'])[1];
    $Auth = new Auth();
    $id_user = $Auth->registerAuth([
      'email' => $cloud['email'],
      'fname' => $fName,
      'lname' => $lName,
      'name' => "$fName $lName",
      'gender' => $cloud['gender'],
      'birthday' => $cloud['birthday'],
      'messenger' => $cloud['id'],
      'type' => $cloud['type']
    ]);
    $subdomainId = Yii::app()->request->cookies['ursd'];
    if(!empty($subdomainId)) // при попытке регистрации возвращаем юзера на страницу профиля поддомена(и там авторизуем)
    {
      //Subdomain::setRedirectInRegister($subdomainId, MainConfig::$PAGE_AFTER_REGISTER, $id_user);
      Subdomain::setRedirectInRegister($subdomainId, MainConfig::$PAGE_AFTER_REGISTER, $id_user);
    }
    else // авторизуем здесь
    {
      $model = new Auth();
      $model->AuthorizeNet(['id' => $id_user]);
      UserRegister::clearRegister();
      $this->redirect(MainConfig::$PAGE_AFTER_REGISTER);
      //$this->redirect(MainConfig::$PAGE_PROFILE);
    }
  }

  public function actionMessnotemail()
  {
    $Auth = new Auth();
    $id_user = $Auth->registerAuth([
      'email' => $_GET['email'],
      'fname' => $_GET['fname'],
      'lname' => $_GET['lname'],
      'name' => $_GET['name'],
      'gender' => $_GET['gender'],
      'birthday' => $_GET['birthday'],
      'messenger' => $_GET['messenger'],
      'type' => $_GET['type']
    ]);
    $subdomainId = Yii::app()->request->cookies['ursd'];
    if($id_user) // регистрация удалась
    {
      if(!empty($subdomainId)) // при попытке регистрации возвращаем юзера на страницу профиля поддомена(и там авторизуем)
      {
        //Subdomain::setRedirectInRegister($subdomainId, MainConfig::$PAGE_AFTER_REGISTER, $id_user);
        Subdomain::setRedirectInRegister($subdomainId, MainConfig::$PAGE_PROFILE, $id_user);
      }
      else // авторизуем здесь
      {
        $model = new Auth();
        $model->AuthorizeNet(['id' => $id_user]);
        UserRegister::clearRegister();
        //$this->redirect(MainConfig::$PAGE_AFTER_REGISTER);
        $this->redirect(MainConfig::$PAGE_PROFILE);
      }
    }
    else // регистрация не удалась
    {
      if(!empty($subdomainId)) // при попытке регистрации возвращаем юзера на страницу профиля поддомена(и там авторизуем)
      {
        Subdomain::setRedirectInRegister($subdomainId, MainConfig::$PAGE_SITE_MESSAGE);
      }
      else
      {
        $this->redirect(MainConfig::$PAGE_SITE_MESSAGE);
      }
    }
  }



  public function actionLogin()
  {
    $rq = Yii::app()->request;
    $serviceName = $rq->getQuery('service');
    $type =  $rq->getQuery('type');

    if (isset($serviceName)) {

      /** @var $eauth EAuthServiceBase */
      $eauth = Yii::app()->eauth->getIdentity($serviceName);
      $eauth->redirectUrl = Yii::app()->user->returnUrl;
      $eauth->cancelUrl = $this->createAbsoluteUrl('user/login');
      // если редиректимся с поддоменов - обновляем регистрационное куки и убираем из урла urh
      $userRegisterHash =  $rq->getQuery('urh');
      $subdomainId =  $rq->getQuery('ursd');
      if(Share::isSocialNetwork() && !empty($userRegisterHash) && !empty($subdomainId))
      {
        $rq->cookies['urh'] = new CHttpCookie('urh', $userRegisterHash);
        $rq->cookies['ursd'] = new CHttpCookie('ursd', $subdomainId);
        $this->redirect( MainConfig::$PAGE_LOGIN . '?service=' . $serviceName);
      }

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
              $subdomainId = Yii::app()->request->cookies['ursd'];
              if ($cloud) // если по этому ID соцсети уже регались - простая авторизация
              {
                if(!empty($subdomainId)) // при попытке регистрации возвращаем юзера на страницу профиля поддомена(и там авторизуем)
                {
                  Subdomain::setRedirectInRegister($subdomainId, MainConfig::$PAGE_PROFILE, $cloud['id']);
                }
                else // авторизуем здесь
                {
                  $auth->AuthorizeNet(['id' => $cloud['id']]);
                  $this->redirect(MainConfig::$PAGE_PROFILE);
                }
              }
              else
              {
                $data = $eauth->getAttributes();
                if((new User())->checkLogin($data['email'])) // юзер с таким email же существует
                {
                  if(!empty($subdomainId))
                  {
                    Subdomain::setRedirectInRegister($subdomainId, MainConfig::$PAGE_SITE_MESSAGE);
                  }
                  else
                  {
                    $this->redirect(MainConfig::$PAGE_SITE_MESSAGE);
                  }
                }
                else
                {
                  if(Share::isEmployer($type))
                  {
                    $data['type'] = UserProfile::$EMPLOYER;
                    if(!empty($data['email']))
                    {
                      $this->actionMessenger($data);
                    }
                    else
                    {
                      $this->render(MainConfig::$VIEWS_REGISTER_FB, ['viData'=>$data], ['nobc'=>'1']);
                    }
                  }
                  else
                  {
                    /// $pth = $auth->loadLogo($eauth->getAttributes()['photo']);
                    $view = MainConfig::$VIEWS_REGISTER_FB;
                    ///$data['photo'] = $pth;
                    ///$data[0] = $pth;
                    $data['type'] = UserProfile::$APPLICANT;
                    
                    if(!empty($data['email'])){
                        // $name = $data['name'];
                        // $email = $data['email'];
                        // $curl = curl_init();
                        // curl_setopt_array($curl, array(
                        //   CURLOPT_URL => "https://dev.prommu.com/user/register/login",
                        //   CURLOPT_RETURNTRANSFER => true,
                        //   CURLOPT_ENCODING => "",
                        //   CURLOPT_MAXREDIRS => 10,
                        //   CURLOPT_TIMEOUT => 0,
                        //   CURLOPT_FOLLOWLOCATION => true,
                        //   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        //   CURLOPT_CUSTOMREQUEST => "POST",
                        //   CURLOPT_POSTFIELDS => 'name='.$name.'&surname='.$name.'&login='.$email.'&data={"name":"'.$name.'","surname":"'.$name.'","login":"'.$email.'","data":""}',
                        //   CURLOPT_HTTPHEADER => array(
                        //     "Content-Type: application/x-www-form-urlencoded"
                        //   ),
                        // ));
                        
                        // $response = curl_exec($curl);
                        
                        // curl_close($curl);
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
      Share::isGuest() && $this->redirect(MainConfig::$PAGE_LOGIN);

      Subdomain::profileRedirect(Share::$UserProfile->id);
      //
      // проверка реги на завершенность
      $this->directToCompleteRegistration();
      //
      // Magnific Popup
      Yii::app()->getClientScript()->registerCssFile(Yii::app()->baseUrl.'/jslib/magnific-popup/magnific-popup-min.css');
      Yii::app()->getClientScript()->registerScriptFile(Yii::app()->baseUrl.'/jslib/magnific-popup/jquery.magnific-popup.min.js', CClientScript::POS_END);
      Yii::app()->getClientScript()->registerScriptFile(Yii::app()->baseUrl.'/theme/js/jquery-ui.min.js', CClientScript::POS_END);
      Yii::app()->getClientScript()->registerScriptFile(Yii::app()->baseUrl.'/theme/js/jquery.form-validator.min.js', CClientScript::POS_END);
      Yii::app()->getClientScript()->registerScriptFile(Yii::app()->baseUrl.'/theme/js/jquery.mask.min.js', CClientScript::POS_END);
      Yii::app()->getClientScript()->registerScriptFile("/theme/js/dev/pages/prof_user.js", CClientScript::POS_END);

      $this->setBreadcrumbs($title = 'Мой профиль', MainConfig::$PAGE_PROFILE);
      $this->setPageTitle($title);

      $this->ViewModel->addContentClass('page-ankety');
      $this->render(
        $this->ViewModel->pageProfile,
        [
          'viData' => Share::$UserProfile->getProfileDataView(),
          'flagOwnProfile' => true,
          'idus' => Share::$UserProfile->id
        ]
      );
    }



    public function actionEditprofile()
    {
        Share::isGuest() && $this->redirect(MainConfig::$PAGE_LOGIN); // no profile for guest

        if( Yii::app()->getRequest()->isPostRequest) // save data
        {
          $arErrors = Share::$UserProfile->saveProfileData();
          if(count($arErrors))
          {
            if(Yii::app()->getRequest()->getParam('register_complete')=='Y')
            {
              $this->directToCompleteRegistration($arErrors);
            }
          }
          else
          {
            $this->redirect(MainConfig::$PAGE_PROFILE);
          }
        } 
        elseif( Yii::app()->getRequest()->getParam('del') ) // del photo
        {
          $arResult = Share::$UserProfile->delProfilePhoto();
          $this->ViewModel->replaceInUrl('', 'del', null);
          $this->redirect($_SERVER['HTTP_REFERER']);
        } 
        elseif( Yii::app()->getRequest()->getParam('dm') ) // сделать фото главным
        {
          $arResult = Share::$UserProfile->setPhotoAsLogo();
          $this->ViewModel->replaceInUrl('', 'dm', null);
          $this->redirect($_SERVER['HTTP_REFERER']);
        }

        $this->setBreadcrumbsEx(array('Мой профиль', MainConfig::$PAGE_PROFILE), array($title = 'Редактирование профиля', MainConfig::$PAGE_EDIT_PROFILE));

        Yii::app()->getClientScript()->registerScriptFile(Yii::app()->baseUrl.'/theme/js/jquery-ui.min.js', CClientScript::POS_END);
        Yii::app()->getClientScript()->registerScriptFile(Yii::app()->baseUrl.'/theme/js/jquery.form-validator.min.js', CClientScript::POS_END);
        Yii::app()->getClientScript()->registerScriptFile(Yii::app()->baseUrl.'/theme/js/jquery.mask.min.js', CClientScript::POS_END);

        if(Share::isApplicant() && Yii::app()->getRequest()->getParam('ep'))
        {
            Yii::app()->getClientScript()->registerCssFile("/" . MainConfig::$PATH_CSS . DS . Share::$cssAsset['prof_edit_applic.css']);
        }

        $this->render($this->ViewModel->pageEditProfile,
                array('viData' => Share::$UserProfile->getProfileDataEdit(), 'viErrorData' => $arResult),
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
      if( Share::$UserProfile->id )
      {
        if(Share::isGuest())
        {
          $this->redirect(Yii::app()->createUrl('index.php'));
        }
        else
        {
          $this->redirect(MainConfig::$PAGE_PROFILE);
        }
      }
      else
      {
        Yii::app()->user->setFlash('auErrMess', 'Неверный логин или пароль!');
        $this->redirect(MainConfig::$PAGE_LOGIN);
      }
    }



    public function actionRegister()
    {
      !Share::isGuest() && $this->redirect(MainConfig::$PAGE_PROFILE);

      $model = new UserRegister();
      $step = $model->step;
      $redirect = $model->checkEmailLink();
      if($redirect) // проверка на подтверждающаю ссылку из email
      {
        $this->redirect($redirect);
      }
      $rq = Yii::app()->getRequest();
      $urlStep = Yii::app()->getRequest()->getParam('step');

      if(empty($urlStep) || ($urlStep!=UserRegister::$URL_STEPS[$model->step])) // редиректим на урл со ступенью
      {
        $link = MainConfig::$PAGE_REGISTER . DS . UserRegister::$URL_STEPS[$model->step];
        $this->redirect($link);
      }
      else
      {
        $post = $rq->getParam('data');
        $post = json_decode($post, true, 5, JSON_BIGINT_AS_STRING);
        if($rq->isAjaxRequest && isset($_FILES['upload']) && $model->step==$model::$STEP_AVATAR) // только загрузка файлов
        {
          $data = $model->saveImage();
          echo CJSON::encode($data);
          Yii::app()->end();
        }
        elseif($rq->isPostRequest && !count($post)) // не пропускаем запрос без данных
        {
          throw new CHttpException(404, 'Error');
        }
        if(isset($post['step'])) // особые запросы
        {
          // return button
          if(
            in_array($post['step'],[
              $model::$STEP_CODE,
              $model::$STEP_PASSWORD,
              $model::$STEP_AVATAR,
              $model::$STEP_LOGIN
            ])
            &&
            $post['redirect']==='back'
          )
          {
            $model->setStep(intval($post['step']) - 1);
            $link = MainConfig::$PAGE_REGISTER . DS . UserRegister::$URL_STEPS[$model->step];
            $this->redirect($link);
          }
          // редирект на авторизацию
          elseif (in_array($post['step'],[$model::$STEP_LOGIN]) && $post['redirect']==='auth')
          {
            $model::clearRegister();
            $model->deleteData();
            $this->redirect(MainConfig::$PAGE_LOGIN);
          }
          // повтор отправки кода
          elseif (in_array($post['step'],[$model::$STEP_CODE]) && $post['send_code']==='Y')
          {
            $model->repeatSendCode();
            $link = MainConfig::$PAGE_REGISTER . DS . UserRegister::$URL_STEPS[$model->step];
            $this->redirect($link);
          }
          // редактирование аватара
          elseif ($post['step']==$model::$STEP_AVATAR && isset($post['width']) && isset($post['height']))
          {
            $data = ($post['edit']==1
              ? $model->onlyEditImage($post)
              : $model->editImage($post));
            echo CJSON::encode($data);
            Yii::app()->end();
          }
          // удаление файлов
          elseif ($post['step']==$model::$STEP_AVATAR && isset($post['delfile']))
          {
            $model->deleteImage($post['delfile']);
            Yii::app()->end();
          }
        }
        elseif(count($post)) // отправка текущей формы
        {
          $model->setDataByStep($post);
          if($step==$model::$STEP_AVATAR && !$model->step)
          {
            //$this->redirect(MainConfig::$PAGE_AFTER_REGISTER);
            $this->redirect(MainConfig::$PAGE_PROFILE);
          }
          elseif($step!=$model->step)
          {
            $link = MainConfig::$PAGE_REGISTER . DS . UserRegister::$URL_STEPS[$model->step];
            $this->redirect($link);
          }
        }
      }

      $this->renderRegister($model->view, ['model'=>$model]);
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



    public function actionVacpub()
    {
      if(Share::isApplicant())
      {
        throw new CHttpException(404, 'Error');
      }
      else if(Share::isGuest())
      {
        $this->redirect(MainConfig::$PAGE_LOGIN);
      }

      $rq = Yii::app()->getRequest();
      $model = new VacancyCreate($rq->getParam('vacancy'));

      $rq->isPostRequest && $model->setDataByStep();
      $model->getDataByStep();
      if($model->id_vacancy) // вакансия создана
      {
        $arOrderId = $model->checkPayment();
        $model->deleteData();
        if(count($arOrderId)) // страница выбора типа оплаты
        {
          $this->redirect(MainConfig::$PAGE_PAYMENT . '?receipt=' . implode(',',$arOrderId));
        }
        else
        {
          $this->redirect(MainConfig::$PAGE_VACANCY . DS . $model->id_vacancy);
        }
      }
      else // в процессе создания
      {
        $this->renderVacPub($model);
      }
    }


    public function actionVacancies()
    {
        Share::isGuest() && $this->redirect(MainConfig::$PAGE_LOGIN);
        //
        // проверка реги на завершенность
        $this->directToCompleteRegistration();
        //
        $data = array();
        $title = '';
        $model = new Vacancy();
        $view = $this->ViewModel->pageVacancies;

        if(Share::isEmployer())
        {
            $arVac = explode("&", $_GET['vacancy']); // & служебный символ GET. Изменится только первая вакансия
            if(count($arVac))
            {
                Yii::app()->db->createCommand()
                    ->update(
                    'empl_vacations', 
                    ['count'=> 1],
                    ['in','id',$arVac]);                
            }

            $data['viData'] = $model->getEmpVacanciesList('active');
            $data['viData']['user'] = Share::$UserProfile->getProfileDataView();
            //$model = new ProjectConvertVacancy(); // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
            //$data['viData']['projects'] = $model->findRelatedProjects($data['viData']['active']);
            $title = 'Мои вакансии';
            $this->setBreadcrumbs($title, MainConfig::$PAGE_VACANCIES);
        }
        elseif(Share::isApplicant())
        {
            $rq = Yii::app()->getRequest();
            $id_resume = Share::$UserProfile->exInfo->id_resume;
            $id = $rq->getParam('id');
            $isArchive = $rq->getParam('section')==='archive';
            $link = $isArchive 
                ? MainConfig::PAGE_USER_VACANCIES_LIST_ARCHIVE
                : MainConfig::PAGE_USER_VACANCIES_LIST;
            
            if($id>0)
            {
              if(!$model->hasAppAccess($id, $id_resume))
                throw new CHttpException(404, 'Error');

              $view = $this->ViewModel->pageVacancyItem;
              // сбрасываем уведомления
              UserNotifications::resetCounters(
                [
                  UserNotifications::$APP_START_VACANCY,
                  UserNotifications::$APP_START_VACANCY_TOMORROW,
                ],
                $id
              );

              $data['viData'] = $model->getAppVacancy($id, $id_resume);
              $title = $data['viData']['item']['title'];
              $this->setBreadcrumbsEx(
                ['Мои проекты', $link],
                [$title, $link . DS . $id ]
              );
            }
            else
            {
                $data['viData'] = $model->getAppVacanciesList($isArchive?'archive':'active');
                $title = 'Мои проекты';
                $this->setBreadcrumbs($title, $link);
            }
        }

        $this->render($view, $data, ['htmlTitle' => $title]);
    }

    public function actionVacarhive()
    {
        !Share::isEmployer() && $this->redirect(MainConfig::$PAGE_LOGIN);
        //
        // проверка реги на завершенность
        $this->directToCompleteRegistration();
        //
        $model = new Vacancy();
        $data = $model->getEmpVacanciesList('archive');
        $data['user'] = Share::$UserProfile->getProfileDataView();

        $this->setBreadcrumbs($title='Архив вакансий', MainConfig::$PAGE_VACARHIVE);
        $this->render(
            $this->ViewModel->pageVacancies,
            ['viData'=>$data],
            ['htmlTitle'=>$title]
        );
    }

    public function actionResponses()
    {
      // no profile for guest
      Share::isGuest() && $this->redirect(MainConfig::$PAGE_LOGIN);
        //
        // проверка реги на завершенность
        $this->directToCompleteRegistration();
        //
        $tab = Yii::app()->getRequest()->getParam('tab');
        $activeFilterLink = $tab == 'invites' ? 1 : 0;
        $vac = explode("&", $_GET['vacancy']);
        $coun = count($vac);

        if(Share::isEmployer())
        {
            for($i = 0; $i < $coun; $i ++){
                Yii::app()->db->createCommand()
                ->update('vacation_stat', array(
                'id_jobs'=> 1),
                    'id_vac=:id_vac', array(':id_vac'=>$vac[$i]));
            }
        }
        elseif(Share::isApplicant())
        {
            for($i = 0; $i < $coun; $i ++) {
                Yii::app()->db->createCommand()
                ->update('vacation_stat', array(
                'isend'=> 1),
                    'id_vac=:id_vac', array(':id_vac'=>$vac[$i]));
            }
          // очищаем счетчик
          UserNotifications::resetCounters([
            UserNotifications::$APP_INVITATIONS,
            UserNotifications::$APP_CONFIRMATIONS,
            UserNotifications::$APP_REFUSALS
          ]);
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
    /**
     * Выставление рейтинга после окончания вакансии
     */
    public function actionSetrate()
    {
        Share::isGuest() && $this->redirect(MainConfig::$PAGE_LOGIN);

        $title = 'Добавить Рейтинг/Отзыв';
        Yii::app()->getClientScript()->registerCssFile('/theme/css/private/page-setrate.css');
        Yii::app()->getClientScript()->registerScriptFile('/theme/js/private/page-setrate.js', CClientScript::POS_END);

        $Responses = Share::$UserProfile->makeResponse();
        $viData = $Responses->setRate();

        if( Yii::app()->getRequest()->isPostRequest )
        {
            $res = $Responses->saveRateData();
        }
        else
        {
          if(Share::isEmployer())
          {
            $id = $viData['user']['pid'];
            $idUs = $viData['user']['iduspromo'];
            // сбрасываем уведомление для Р
            UserNotifications::resetCounters(
              [UserNotifications::$EMP_SET_RATING],
              $viData['user']['id'],
              $idUs
            );
          }
          else
          {
            $id = $viData['user']['eid'];
            $idUs = $viData['user']['idusempl'];
            // сбрасываем уведомление для С
            UserNotifications::resetCounters(
              [UserNotifications::$APP_SET_RATING],
              $viData['user']['id']
            );
          }
          $data = $Responses->loadRatingPageDatas($id, $idUs);
        }

        $this->render(
                $this->ViewModel->pageSetRate,
                array('viData' => $viData, 'data' => $data),
                array('saveResp' => $res,'htmlTitle' => $title)
            );
    }


    /**
     *     Страница оплаты
     */
    public function actionPayment()
    {
        // no profile for guest
        Share::isGuest() && $this->redirect(MainConfig::$PAGE_LOGIN);

        $rq = Yii::app()->getRequest();
        // ветка для оплаты при создании вакансии
        if(!empty($rq->getParam('receipt')))
        {
          if($rq->isPostRequest)
          {
            $model = new PrommuOrder();
            $service = new ServiceCloud();
            $arItems = $service->getServices($rq->getParam('receipt'));
            $arId = [];
            $price = 0;
            foreach ($arItems as $v)
            {
              $arId[] = $v['id'];
              $price += $v['sum'];
            }

            if($rq->getParam('personal')==='individual') // физ лица
            {
              $link = $model->createPayLink(
                Share::$UserProfile->id . '.' . implode('.', $arId) . '.vacancy.' . time(), // пока только премиум
                '',
                $price
              );
              $link && $this->redirect($link);
            }
            if($rq->getParam('personal')==='legal') // юр лица
            {
              $model->setLegalEntityReceipt($arId);
              $this->redirect(MainConfig::$PAGE_SERVICES);
            }
            Yii::app()->end();
          }
          $arUser = reset(Share::getUsers([Share::$UserProfile->exInfo->id]));
          $arUser['inn'] = Share::$UserProfile->getUserAttribute(['key'=>'inn']);
          isset($arUser['inn'][0]['val']) && $arUser['inn']=$arUser['inn'][0]['val'];
          $data = [
            'user' => $arUser,
            'receipt' => explode(',',$rq->getParam('receipt'))
          ];
          $service = new ServiceCloud();
          $data['receipt_items'] = $service->getServices($data['receipt']);
          if(!count($data['receipt_items']) || count($data['receipt_items'])!=count($data['receipt'])) // если с урлом что-то не так - нам такой плательщик не нужен
          {
            throw new CHttpException(404, 'Error');
          }
          $this->render(MainConfig::$PAGE_PAYMENT_VIEW, ['viData' => $data]);
          Yii::app()->end();
        }
        // ветка для оплаты в обычном режиме
        if(!$rq->isPostRequest)
            throw new CHttpException(404, 'Error');

        $model = new PrommuOrder();
        $service = $rq->getParam('service');
        $vac = $rq->getParam('vacancy');
        $vacPrc = $rq->getParam('vacancy_price');
        $vacCity = $rq->getParam('vacancy_city');
        $emp = $rq->getParam('employer');
        $isArr = in_array($service,['premium-vacancy','podnyatie-vacansyi-vverh']);

        // get prise parameters if redirect from "my vacancy" page
        if (empty($vacCity) && isset($vac[0]))
        {
            $vacCity = Yii::app()->db->createCommand()
                ->select("id_city")
                ->from("empl_city")
                ->where("id_vac=:id", [":id"=>$vac[0]])
                ->queryColumn();

            if (count($vacCity)>0)
            {
                for ($i=0; $i<count($vacCity); ++$i)
                    $vac[$i] = $vac[0];
            }
        }

        $price = $model->servicePrice($vac, $service, $isArr, $vacCity);

        switch ($service)
        {
            case 'premium-vacancy':
              if($price>0)
              { // оплата услуги
                $data = $model->orderPremium($vac, $emp, $vacPrc, $vacCity);
                if($rq->getParam('personal')==='individual') // физ лица
                {
                  $link = $model->createPayLink($data['account'], $data['strVacancies'], $data['cost']);
                  $link && $this->redirect($link);
                }
                if($rq->getParam('personal')==='legal') // юр лица
                {
                  $model->setLegalEntityReceipt($data['id']);
                  $this->redirect(MainConfig::$PAGE_SERVICES);
                }
              }
              else
              { // бесплатно или без цены
                $this->redirect(MainConfig::$PAGE_SERVICES);
              }
              break;

            // upvacancy
            case 'podnyatie-vacansyi-vverh':

                if ($price > 0) { // pay service
                    $data = $model->orderUpVacancy($vac, $emp, $vacPrc, $vacCity);
                    if ($rq->getParam('personal') === 'individual') // people
                    {
                        //die('normal employer price');
                        $link = $model->createPayLink($data['account'], $data['strVacancies'], $data['cost']);
                        $link && $this->redirect($link);
                    }
                    if ($rq->getParam('personal') === 'legal') // company
                    {
                        //die('urist-employer price');
                        $model->setLegalEntityReceipt($data['id']);
                        $this->redirect(MainConfig::$PAGE_SERVICES);
                    }
                } else { // free or no price
                    $this->redirect(MainConfig::$PAGE_SERVICES);
                }
                break;

            // personal-invitation
            case 'personal-invitation':

                if ($price > 0) { // pay service

                    $price = $model->servicePrice($vac, $service);
                    $users = $rq->getParam('users');

                    $data = $model->orderPersonalInvitation($vac, $users, $emp, $price);

                    if (!$users){
                        Yii::app()->user->setFlash(
                            'prommu_flash',
                            "Выбраны пользователи, которые уже получили приглашения"
                        );
                        $this->redirect(MainConfig::$PAGE_SERVICES);
                    }

                    if ($data['cost'] > 0 ) {

                        if ($rq->getParam('personal') === 'individual') // people
                        {
                            $link = $model->createPayLink($data['account'], $vac, $data['cost']);
                            $link && $this->redirect($link);
                        }
                        if ($rq->getParam('personal') === 'legal') // company
                        {
                            $model->setLegalEntityReceipt($data['id']);
                            $this->redirect(MainConfig::$PAGE_SERVICES);
                        }
                    } elseif ($data['cost'] = -2) {
//                      invite users
//                      save data to database
//                      database = vacation_stat

                        $props = [];
                        $props['idvac'] = $vac;

                        $users = explode(',',$users);
                        for($i=0; $i<=count($users); ++$i)
                        {
                            $props['id'] = Yii::app()->db->createCommand()
                                ->select('id')
                                ->from('resume')
                                ->where(
                                    'id_user=:idp',
                                    [':idp'=>$users[$i]]
                                )
                                ->queryScalar();

                            if ($props['id']) {
                                (new ResponsesApplic())->invitePersonal($props);
                            }
                        }
                        Yii::app()->user->setFlash(
                            'prommu_flash',
                            "Приглашения успешно отправлены!"
                        );
                        $this->redirect(MainConfig::$PAGE_SERVICES);
                    }


                } else { // free or no price
                    //die('personal-invitation no price');
                    $this->redirect(MainConfig::$PAGE_SERVICES);
                }
                break;

            case 'email-invitation':
                if($price > 0)
                {
                  // оплата услуги
                  $price = $rq->getParam('price');
                  $data = $model->orderEmail($vac, $price, $emp);
                  if($rq->getParam('personal')==='individual') // физ лица
                  {
                    $link = $model->createPayLink($data['account'], $vac, $price);
                    $link && $this->redirect($link);
                  }
                  if($rq->getParam('personal')==='legal')  // юр лица
                  {
                    $model->setLegalEntityReceipt($data['id']);
                    $this->redirect(MainConfig::$PAGE_SERVICES);
                  }
                }
                else { // бесплатно или без цены
                    $this->redirect(MainConfig::$PAGE_SERVICES);
                }
                break;

            case 'push-notification':
                if($price > 0) { // оплата услуги
                    $this->redirect(MainConfig::$PAGE_SERVICES);
                }
                else { // бесплатно или без цены
                    $model->orderPush($vac, $price, $emp);
                    Yii::app()->user->setFlash('success', array('event'=>'push'));
                    $this->redirect(MainConfig::$PAGE_SERVICES);
                }
                break;

            case 'sms-informing-staff':
              if($price > 0)
              { // оплата услуги
                $smsCount = $rq->getParam('sms-count');
                $data = $model->orderSms($vac, $price, $emp, $smsCount);

                if($rq->getParam('personal')==='individual') // физ лица
                {
                  $link = $model->createPayLink($data['account'], $vac, $data['cost']);
                  $link && $this->redirect($link);
                }
                if($rq->getParam('personal')==='legal')  // юр лица
                {
                  $model->setLegalEntityReceipt($data['id']);
                  $this->redirect(MainConfig::$PAGE_SERVICES);
                }
              }
              else
              { // бесплатно или без цены
                $this->redirect(MainConfig::$PAGE_SERVICES);
              }
              break;

            case 'publication-vacancy-social-net':
                if($price > 0) { // оплата услуги
                    $this->redirect(MainConfig::$PAGE_SERVICES);
                }
                else { // бесплатно или без цены
                    $model->orderSocial($vac, $price, $emp);
                    Yii::app()->user->setFlash('success', array('event'=>'push'));
                    $this->redirect(MainConfig::$PAGE_SERVICES);
                }
                break;
        }

        $arUser = reset(Share::getUsers([Share::$UserProfile->exInfo->id]));
        $arUser['inn'] = Share::$UserProfile->getUserAttribute(['key'=>'inn']);
        isset($arUser['inn'][0]['val']) && $arUser['inn']=$arUser['inn'][0]['val'];
        $data = array(
                'service' => $service, 
                'vacancy' => $vac,
                'price' => $price,
                'employer' => $emp,
                'user' => $arUser
            );
        $this->render(MainConfig::$PAGE_PAYMENT_VIEW, ['viData' => $data]);
    }
    /*
    *       Отзыв / рейтинг
    */
    public function actionReviews()
    {
        Share::isGuest() && $this->redirect(MainConfig::$PAGE_LOGIN);
        //
        // проверка реги на завершенность
        $this->directToCompleteRegistration();
        //
        $Responses = Share::isApplicant() ? new ResponsesApplic() : new ResponsesEmpl();
        $pages = new CPagination($Responses->getResponsesRatingCount());
        $pages->pageSize = MainConfig::$DEF_PAGE_LIMIT;
        $pages->applyLimit($Responses);
        
        Yii::app()->getClientScript()->registerCssFile("/theme/css/private/page-reviews.css");
        $title = Share::isApplicant() ? 'Оценка персонала' : 'Оценка работодателей';
        $this->setBreadcrumbs($title, MainConfig::$PAGE_REVIEWS_VIEW);

        $this->render(MainConfig::$PAGE_REVIEWS_VIEW,
                array('viData' => $Responses->getResponsesRating(), 'pages' => $pages),
                array('htmlTitle' => $title)
            );
    }
    /**
     * Аналитика
     */
    public function actionAnalytics()
    {
        if( !Share::isApplicant() && !Share::isEmployer() )
            $this->redirect(MainConfig::$PAGE_LOGIN);
        //
        // проверка реги на завершенность
        $this->directToCompleteRegistration();
        //
        $model = new Termostat;
        $arDates = $model->getDates();
        if(Share::isApplicant())
        {
            $data = $model->getAppAnalytics(Share::$UserProfile->id, $arDates);
        }
        else
        {
            $data = $model->getEmpAnalytics(Share::$UserProfile->id, $arDates);
        }
        

        if(Yii::app()->request->isAjaxRequest)
        {
            $this->renderPartial(
                    MainConfig::$AJAX_ANALYTICS, 
                    ['viData' => $data], 
                    false, 
                    true
                );
        }
        else
        {
            $title = 'Аналитика';
            $this->setBreadcrumbs($title, MainConfig::$PAGE_ANALYTICS);
            $this->render(
                MainConfig::$VIEWS_ANALYTICS, 
                array('viData' => $data), 
                array('htmlTitle' => $title)
            );
        }
    }
    /**
     *  Заказ услуги API
     */
    public function actionApi()
    {
      $model = new ServiceCloud();
      $model->orderApi();
      $this->redirect(MainConfig::$PAGE_CHATS_LIST_FEEDBACK);
    }
    /*
    *   Страница настроек
    */
    public function actionSettings()
    {
        in_array(Share::$UserProfile->type, [2,3]) || $this->redirect(MainConfig::$PAGE_LOGIN);
        //
        // проверка реги на завершенность
        $this->directToCompleteRegistration();
        //
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
    *   Удалить вакансию
    */
    public function actionVacdelete()
    {
        !Share::isEmployer() && $this->redirect(MainConfig::$PAGE_INDEX);

        $page = filter_var(Yii::app()->getRequest()->getParam('page'), FILTER_SANITIZE_NUMBER_INT);
        $res = (new Vacancy())->vacDelete();
        if( $res['error'] < 0 ) {
            $this->redirect(MainConfig::$PAGE_INDEX);
        }
        else {
            Yii::app()->user->setFlash('prommu_flash', $res['message']);
            $url = DS . ($page ? MainConfig::$PAGE_VACANCIES : MainConfig::$PAGE_VACARHIVE);
            $this->redirect($url);
        }
    }
    /*
    *
    */
    public function actionVacPostToSocial()
    {
        !Share::isEmployer() && $this->redirect(MainConfig::$PAGE_INDEX);

        $page = filter_var(Yii::app()->getRequest()->getParam('page'), FILTER_SANITIZE_NUMBER_INT);
        $id = filter_var(Yii::app()->getRequest()->getParam('id'), FILTER_SANITIZE_NUMBER_INT);
        $res = (new Vacancy())->VacPostToSocial();
        Yii::app()->user->setFlash('prommu_flash', $res['message']);
        $url = $page ? (DS . MainConfig::$PAGE_VACANCIES) : (MainConfig::$PAGE_VACANCY . DS . $id);
        $this->redirect($url);
    }
    /*
    *   Мои проекты
    */
    public function actionProjects()
    {
        $idus = Share::$UserProfile->id;
        $type = Share::$UserProfile->type;
        !in_array($type, [2,3]) && $this->redirect(MainConfig::$PAGE_INDEX);

        $rq = Yii::app()->getRequest();
        $id = $rq->getParam('id');
            
        $model = new Project();
    
        if($id=='new') { // новый проект
            if(Yii::app()->request->isAjaxRequest) {
                $this->renderPartial(
                    'projects/ankety-ajax',
                    array('viData' => (new Services())->getFilteredPromos()),
                    false,
                    true
                );
                return;
            }
            else {
                $data = (new Services())->getFilteredPromos();
                $data['users-limit'] = $model->MAX_USERS_IN_PROJECT;
            }
            $view = MainConfig::$VIEW_PROJECT_NEW;
        }
        elseif($id=='all' && $type==3) {
            if(!$model->hasAccess())
                $this->redirect(MainConfig::$PAGE_PROJECT_LIST);

            $arId = $model->getAllProjectsId($idus);
            $data = $model->getIndexAllProjects($arId);
            $data['users'] = $model->getStaffAllProjects($arId);
            $data['filter']['projects'] = $arId;
            $view = MainConfig::$VIEW_PROJECT_ALL;

            if(Yii::app()->request->isAjaxRequest) {
                $this->renderPartial(
                    'projects/all-ajax',
                    array('viData' => $data, 'project' => $id),
                    false, true
                );
                return;
            }
        }
        elseif($id=='user-card' && $type==3) {

            $user_id = $rq->getParam('user_id');

            $main = $model->getUserMainInfo($user_id);
            $mech = $model->getUserMechInfo($user_id);
            if(!count($main))
            {
                throw new CHttpException(404, 'Error');
                return;
            }
            $contacts = $model->getUserContactsInfo($user_id);
            $project_info = $model->getUserProjectsInfo($user_id);
            $data = $model->getUserAllInfo($main,$mech, $contacts,$project_info);

            $view = MainConfig::$VIEW_PROJECT_USER_CARD;
        }
        elseif($id>0) { // существующий
            if(!$model->hasAccess($id))
                $this->redirect(MainConfig::$PAGE_PROJECT_LIST);

            switch ($rq->getParam('section')) {
                case 'staff':
                    if($type==2) // applicant
                    {
                        throw new CHttpException(404, 'Error');
                        return;
                    }
                    $save = $rq->getParam('save-users');
                    if(isset($save)) {
                        $model->recordStaff($_POST, $id);
                        $convert = new ProjectConvertVacancy();
                        $convert->synphronization($id,'project');
                        $this->redirect(MainConfig::$PAGE_PROJECT_LIST.'/'.$id.'/staff');
                    }
                    if(Yii::app()->request->isAjaxRequest) {
                        $gp = $rq->getParam('get-promos');
                        $data = (isset($gp)
                            ? (new Services())->getFilteredPromos()
                            : $model->getStaff($id));
                        if($gp==1) $view='projects/project-staff-add-ajax';
                        elseif($gp==2) $view='projects/ankety-ajax';
                        else $view='projects/project-staff-ajax';

                        $this->renderPartial(
                            $view,
                            array('viData' => $data, 'project' => $id),
                            false, true
                        );
                        return;
                    }
                    $model->getXLSFile();
                    $data = $model->getStaff($id);
                    $data['project'] = $model->getProjectData($id);
                    $view = MainConfig::$VIEW_PROJECT_ITEM_STAFF;
                    break;
                case 'index':
                    if($type==2) // applicant
                    {
                        throw new CHttpException(404, 'Error');
                        return;
                    }
                    $data = $model->getIndexes($id);
                    $data['project'] = $model->getProjectData($id);
                    if(Yii::app()->request->isAjaxRequest) {
                        $this->renderPartial(
                            'projects/project-index-ajax',
                            array('viData' => $data, 'project' => $id),
                            false, true
                        );
                        return;
                    }
                    $view = MainConfig::$VIEW_PROJECT_ITEM_INDEX;
                    $model->getXLSFile();
                    break;
                case 'geo':
                    if($type==2) // applicant
                    {
                        throw new CHttpException(404, 'Error');
                        return;
                    }
                    $data = $model->getProject($id);
                    $data = $model->buildGeoArray($data);
                    if($data['error'])
                        throw new CHttpException(404, 'Error');
                    if(Yii::app()->request->isAjaxRequest) {
                        $this->renderPartial(
                            'projects/project-geo-ajax',
                            array('viData' => $data, 'project' => $id),
                            false, true
                        );
                        return;
                    }
                    $view = MainConfig::$VIEW_PROJECT_ITEM_GEO;
                    break;
                case 'route':
                    if($type==2) // applicant
                    {
                        $data = $model->getProject($id);
                        $data['users'] = $model->getProjectAppPromoTemp($id);
                        $data = $model->buildTaskPageArray($data);
                        $view = 'projects/applicant/route';
                    }
                    if($type==3) // employer
                    {
                        $data = $model->getProject($id);
                        $data = $model->buildRouteArray($data);
                        $data['gps'] = $model->getСoordinates(['project'=>$id]);
                        if(Yii::app()->request->isAjaxRequest) {
                            $this->renderPartial(
                                'projects/project-route-ajax',
                                array('viData' => $data, 'project' => $id),
                                false, true
                            );
                            return;
                        }
                        $view = MainConfig::$VIEW_PROJECT_ITEM_ROUTE;                        
                    }
                    break;
                case 'tasks':
                    if($type==2) // applicant
                    {
                        $data = $model->getProject($id);
                        $data = $model->buildApplicantTaskPageArray($data);
                        if(Yii::app()->request->isAjaxRequest)
                        {
                            $this->renderPartial(
                                'projects/applicant/tasks-ajax',
                                array('viData' => $data, 'project' => $id),
                                false, true
                            );
                            return;
                        }
                        $view = 'projects/applicant/tasks';
                    }
                    if($type==3) // employer
                    {
                        $data = $model->getProject($id);
                        $data = $model->buildTaskPageArray($data);
                        if(Yii::app()->request->isAjaxRequest)
                        {
                            $this->renderPartial(
                                'projects/project-tasks-ajax',
                                array('viData' => $data, 'project' => $id),
                                false, true
                            );
                            return;
                        }
                        $view = MainConfig::$VIEW_PROJECT_ITEM_TASKS;
                    }
                    break;
                case 'tasks_test':
                    if($type==2) // applicant
                    {
                        throw new CHttpException(404, 'Error');
                        return;
                    }
                    $data = $model->getProject($id);
                    $data = $model->buildNewTaskPageArray($data);
                    if(Yii::app()->request->isAjaxRequest) {
                        $this->renderPartial(
                            'projects/project-tasks_test-ajax',
                            array('viData' => $data, 'project' => $id),
                            false, true
                        );
                        return;
                    }
                    $view = 'projects/project-tasks_test';
                    break;
                case 'report':
                    if($type==2) // applicant
                    {
                        throw new CHttpException(404, 'Error');
                        return;
                    }
                    $data = $model->getProject($id);
                    $data = $model->buildReportArrayNew($data);
                    if(Yii::app()->request->isAjaxRequest) {
                        $this->renderPartial(
                            'projects/project-report-ajax',
                            array('viData' => $data, 'project' => $id),
                            false, true
                        );
                        return;
                    }
                    $view = MainConfig::$VIEW_PROJECT_ITEM_REPORT;
                    break;
                case 'address-edit':
                    if($type==2) // applicant
                    {
                        throw new CHttpException(404, 'Error');
                        return;
                    }
                    if( $rq->isPostRequest )
                    {
                        $model->recordIndex($_POST, $id);
                        $convert = new ProjectConvertVacancy();
                        $convert->synphronization($id,'project');
                        $this->redirect(MainConfig::$PAGE_PROJECT_LIST.'/'.$id.'/index');
                    }
                    $data = $model->getIndexes($id);
                    $data['project'] = $model->getProjectData($id);
                    $view = MainConfig::$VIEW_PROJECT_ITEM_ADR_CHANGE;
                    break;
                case 'users-select':
                    if($type==2) // applicant
                    {
                        throw new CHttpException(404, 'Error');
                        return;
                    }
                    if(Yii::app()->request->isAjaxRequest) {
                        $data = $model->getStaff($id);
                        $this->renderPartial(
                            'projects/project-users-select-ajax',
                            array('viData' => $data, 'project' => $id),
                            false, true
                        );
                        return;
                    }

                    if( $rq->isPostRequest )
                    {
                        $model->setPromoToPoint($_POST);
                        $this->redirect(MainConfig::$PAGE_PROJECT_LIST.'/'.$id);
                    }
                    $point = $rq->getParam('point');
                    if(!isset($point))
                        $this->redirect(MainConfig::$PAGE_PROJECT_LIST.'/'.$id);

                    $view = MainConfig::$VIEW_PROJECT_ITEM_PROMO_CHANGE;
                    $data = $model->getStaff($id);
                    $data['project'] = $model->getProjectData($id);
                    $data['point'] = $model->getPoint($id,$point);
                    if(empty($data['point']['id_city']))
                        $this->redirect(MainConfig::$PAGE_PROJECT_LIST.'/'.$id);
                    break;
                default:
                    if($type==3) { // employer
                        $data = $model->getProject($id);
                        if(Yii::app()->request->isAjaxRequest) {
                            $this->renderPartial(
                                'projects/project-base-ajax',
                                array('viData' => $data, 'project' => $id),
                                false, true
                            );
                            return;
                        }
                        $view = MainConfig::$VIEW_PROJECT_ITEM;
                        $model->getXLSFile();
                    }
                    if($type==2) { // applicant
                        $data = $model->getProject($id);
                        $data['users'] = $model->getProjectAppPromoTemp($id);
                        $data = $model->buildTaskPageArray($data);
                        $view = 'projects/applicant/base';
                    }
                    break;
            }
        }
        else{
            if($type==3) { // employer
                $data = $model->getProjectEmployer($id=='arcive' ? 1 : 0);
                $view = MainConfig::$VIEW_EMP_PROJECT_LIST;
            }
            if($type==2) { // app
                $data = $model->changeAppStatus();
                if($data['status']>0) // по согласию переход на проект
                   $this->redirect(MainConfig::$PAGE_PROJECT_LIST.'/'.$data['project']); 
                if($data['status']<0) // по отказу обратно в список без параметров
                    $this->redirect(MainConfig::$PAGE_PROJECT_LIST); 
                $data = $model->getProjectApplicant();
                $view = MainConfig::$VIEW_APP_PROJECT_LIST;
            }
        }

        $this->render($view, array('viData' => $data, 'project' => $id));
    }
    /*
    *       загрузка xls файла
    */
    public function actionUploadProjectXLS() {
        $id = Yii::app()->getRequest()->getParam('id');
        $type = Yii::app()->getRequest()->getParam('type');
        $model = new Project();
        if(!$model->hasAccess($id))
            return;

        if($type=='index') {
            $model->exportProject($id);
        }
        if($type=='users') {
           $model->exportUsers($id);
        }
    }
    /**
     * Услуги
     */
    public function actionServices()
    {
        Subdomain::guestRedirect(Share::$UserProfile->type);
        $id = filter_var(Yii::app()->getRequest()->getParam('id'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $Services = new Services();
        $title = 'Услуги портала Prommu.com';
        $this->setBreadcrumbs($title, MainConfig::$PAGE_SERVICES);
        $data = $Services->getServiceData($id);
        $data = array_merge(['service' => $data], $Services->getServices($id));
        switch ($id){
            case 'premium-vacancy':
                !Share::isEmployer() && $this->redirect(MainConfig::$PAGE_SERVICES);

                $view = MainConfig::$VIEWS_SERVICE_PREMIUM_VIEW;
                $vac = new Vacancy();
                $data = $vac->getMVacsForPayService();
                break;

            case 'podnyatie-vacansyi-vverh':
                !Share::isEmployer() && $this->redirect(MainConfig::$PAGE_SERVICES);

                $view = MainConfig::$VIEWS_SERVICES_VACUP_VIEW;
                $vac = new Vacancy();
                $data = $vac->getMVacsForPayService();

                break;

            case 'personal-invitation':
                !Share::isEmployer() && $this->redirect(MainConfig::$PAGE_SERVICES);

                $vac = Yii::app()->getRequest()->getParam('vacancy');
                $data['price'] = (new PrommuOrder())->servicePrice($vac, $id);

                if(Yii::app()->getRequest()->getParam('users') && $data['price']>=0)
                {
                    $data['emp'] = Share::$UserProfile->getProfileDataView()['userInfo'];
                    $data['vac'] = (new Vacancy())->getVacancyInfo($vac);
                    $data['user'] = reset(Share::getUsers([Share::$UserProfile->exInfo->id]));
                    $attr = Share::$UserProfile->getUserAttribute(['key'=>'inn']);
                    isset($attr[0]['val']) && $data['user']['inn']=$attr[0]['val'];

                    if ($vac) {
                        $sql = "
                            SELECT 
                                status
                            FROM
                                service_cloud
                            WHERE 
                                name = {$vac}
                            AND
                                status = 1    
                            AND 
                                type = 'personal-invitation'  
                            GROUP BY
                                status         
                        ";
                        $sql = Yii::app()->db->createCommand($sql)->queryRow();

                        $data['is_pay'] = $sql['status'];
                    }

                    $view = MainConfig::$VIEWS_SERVICES_PERSONAL_INVITATION;
                }
                elseif(Yii::app()->request->isAjaxRequest)
                {
                    display($vac);
//                    die();
                    display($_GET);
                    display($_POST);
                    $this->renderPartial(
                        MainConfig::$VIEWS_SERVICE_ANKETY_AJAX,
                        [
                            'viData' => (new Services())->getFilteredPromos(),
                        ],
                        false,
                        true
                    );
                    return;
                }
                else{
                    $view = MainConfig::$VIEWS_SERVICES_PERSONAL_INVITATION;
                    $data2 = (Yii::app()->getRequest()->getParam('vacancy')
                        ? (new Services())->prepareFilterData()
                        : (new Vacancy())->getModerVacs());

                    $data = array_merge($data,$data2);
                }
                break;

            case 'email-invitation':
                !Share::isEmployer() && $this->redirect(MainConfig::$PAGE_SERVICES);

                $vac = Yii::app()->getRequest()->getParam('vacancy');
                $model = new PrommuOrder;
                $data['price'] = $model->servicePrice($vac, $id);

                if(Yii::app()->getRequest()->getParam('users') && $data['price']>=0){
                    $data['emp'] = Share::$UserProfile->getProfileDataView()['userInfo'];
                    $data['vac'] = (new Vacancy())->getVacancyInfo($vac);
                    $data['user'] = reset(Share::getUsers([Share::$UserProfile->exInfo->id]));
                    $attr = Share::$UserProfile->getUserAttribute(['key'=>'inn']);
                    isset($attr[0]['val']) && $data['user']['inn']=$attr[0]['val'];
                    $view = MainConfig::$VIEWS_SERVICE_EMAIL;
                }
                elseif(Yii::app()->request->isAjaxRequest){
                    $this->renderPartial(
                        MainConfig::$VIEWS_SERVICE_ANKETY_AJAX,
                        array('viData' => (new Services())->getFilteredPromos()), 
                        false, 
                        true
                    );
                    return;
                }
                else{
                    $view = MainConfig::$VIEWS_SERVICE_EMAIL;
                    $data2 = (Yii::app()->getRequest()->getParam('vacancy') 
                        ? (new Services())->prepareFilterData()
                        : (new Vacancy())->getModerVacs());
                    $data = array_merge($data,$data2);
                }
                break;

            case 'push-notification':
                !Share::isEmployer() && $this->redirect(MainConfig::$PAGE_SERVICES);

                if(Yii::app()->getRequest()->getParam('users')){
                    $view = MainConfig::$VIEWS_SERVICE_PUSH_VIEW;
                }
                elseif(Yii::app()->request->isAjaxRequest){
                    $this->renderPartial(
                        MainConfig::$VIEWS_SERVICE_ANKETY_AJAX,
                        array('viData' => (new Services())->getFilteredPromos()), 
                        false, 
                        true
                    );
                    return;
                }
                else{
                    $view = MainConfig::$VIEWS_SERVICE_PUSH_VIEW;
                    $data2 = (Yii::app()->getRequest()->getParam('vacancy') 
                        ? (new Services())->prepareFilterData()
                        : (new Vacancy())->getModerVacs());
                    $data = array_merge($data,$data2);
                }
                break;

            case 'sms-informing-staff':
                !Share::isEmployer() && $this->redirect(MainConfig::$PAGE_SERVICES);

                $vacancy = Yii::app()->getRequest()->getParam('vacancy');

                if(Yii::app()->getRequest()->getParam('workers'))
                {
                    $model = new PrommuOrder;
                    $data['price'] = $model->servicePrice($vacancy,$id);
                    $data['user'] = reset(Share::getUsers([Share::$UserProfile->exInfo->id]));
                    $attr = Share::$UserProfile->getUserAttribute(['key'=>'inn']);
                    isset($attr[0]['val']) && $data['user']['inn']=$attr[0]['val'];
                    $view = MainConfig::$VIEWS_SERVICE_SMS_VIEW;
                }
                elseif(Yii::app()->request->isAjaxRequest)
                {

                    $this->renderPartial(
                        MainConfig::$VIEWS_SERVICE_ANKETY_AJAX,
                            [
                                'viData' => (new Services())->getFilteredPromos(),
                            ],
                        false,
                        true
                    );
                    return;
                }
                else
                {
                    $view = MainConfig::$VIEWS_SERVICE_SMS_VIEW;
                    $data2 = $vacancy
                        ? (new Services())->prepareFilterData()
                        : (new Vacancy())->getModerVacs();

                    $data['user'] = reset(Share::getUsers([Share::$UserProfile->exInfo->id]));

                    if($vacancy) {
                        $positionVacancy = Yii::app()->db->createCommand("
                            SELECT
                                id_attr, d1.name
                            FROM
                                empl_attribs
                            INNER JOIN
                                user_attr_dict d
                            ON
                                d.id = 110
                            INNER JOIN
                                user_attr_dict d1
                            ON
                                empl_attribs.id_attr = d1.id AND d1.id_par = d.id
                            WHERE
                                id_vac = {$vacancy}
                        ")->queryRow();

                        $vacancyTime = Yii::app()->db->createCommand()
                            ->select('shour, sweek, smonth, svisit')
                            ->from('empl_vacations')
                            ->where('id=:id', [':id' => $vacancy])
                            ->queryRow();

                        $payForVacancy = 'по договорённости';
                        if ($vacancyTime['shour'] > 0)
                            $payForVacancy = $vacancyTime['shour'] . ' руб/час';
                        if ($vacancyTime['sweek'] > 0)
                            $payForVacancy = $vacancyTime['sweek'] . ' руб/неделю';
                        if ($vacancyTime['smonth'] > 0)
                            $payForVacancy = $vacancyTime['smonth'] . ' руб/месяц';
                        if ($vacancyTime['svisit'] > 0)
                            $payForVacancy = $vacancyTime['svisit'] . ' руб/посещение';

                        $data['current'] = $vacancy;
                        $data['pay'] = $payForVacancy;
                        $data['position'] = $positionVacancy['name'];
                        $data['name'] = $data['user']['name'];
                    }

                    $data = array_merge($data,$data2);
                }
                break;

            case 'publication-vacancy-social-net':
                !Share::isEmployer() && $this->redirect(MainConfig::$PAGE_SERVICES);
                $data = (new Vacancy())->postToSocialService();
                if(isset($data['vacs'])) {
                    $view = MainConfig::$VIEWS_SERVICE_DUPLICATION;
                }
                else{
                    $this->redirect(MainConfig::$PAGE_SERVICES);
                }
                break;

            case 'outstaffing':
                !Share::isEmployer() && $this->redirect(MainConfig::$PAGE_SERVICES);
                $vac = new Vacancy();
                $data = $vac->getVacanciesPrem();
                $view = MainConfig::$VIEWS_SERVICE_OUTSTAFFING_VIEW;
                break;

            case 'personal-manager-outsourcing':
                !Share::isEmployer() && $this->redirect(MainConfig::$PAGE_SERVICES);
                $vac = new Vacancy();
                $data = $vac->getVacanciesPrem();
                $view = MainConfig::$VIEWS_SERVICE_OUTSOURCING_VIEW;
                break;

            case 'prommu_card':
                $services = new Services();
                $data = $services->getServiceData($id);
                if( Yii::app()->getRequest()->getParam('save') ) {
                    $services->orderPrommu();
                    $this->redirect(MainConfig::$PAGE_SERVICES);
                }
                $Upluni = new Uploaduni();
                $data = array_merge($data, $Upluni->init());
                $view = MainConfig::$VIEWS_CARD_PROMMU;
                break;

            case 'medical-record':
                $view = MainConfig::$VIEWS_SERVICE_MEDICAL; 
                break;

            case 'api-key-prommu':
                !Share::isEmployer() && $this->redirect(MainConfig::$PAGE_SERVICES);
                $view = MainConfig::$VIEWS_SERVICE_API_VIEW;
                break;
               
            default:
                throw new CHttpException(404, 'Error'); 
                break;
        }
            
        if(strlen($data['service']['meta_title']) > 0){
            $title = htmlspecialchars_decode($data['service']['meta_title']);
            $this->setBreadcrumbsEx(array($title, $_SERVER['REQUEST_URI']));
        }
        if(strlen($data['service']['meta_description']) > 0){
            Yii::app()->clientScript->registerMetaTag(htmlspecialchars_decode($data['service']['meta_description']), 'description');
        }

        $this->render(
            '../site/' . $view, 
            array('viData' => $data, 'id' => $id),
            array(
                'pageTitle' => '<h1>'.$title.'</h1>', 
                'htmlTitle' => $title
            )
        );
    }
    /**
     * Чат
     */
    public function actionChats()
    {
        Share::isGuest() && $this->redirect(MainConfig::$PAGE_LOGIN);

        $rq = Yii::app()->getRequest();
        $section = filter_var(
                        $rq->getParam('section'),
                        FILTER_SANITIZE_FULL_SPECIAL_CHARS
                    );
        $id = filter_var(
                        $rq->getParam('id'),
                        FILTER_SANITIZE_NUMBER_INT
                    );
        $vacancy = filter_var(
                        $rq->getParam('vacancy'),
                        FILTER_SANITIZE_NUMBER_INT
                    );

        if(!empty($section) && !in_array($section,['vacancies','feedback']))
            throw new CHttpException(404, 'Error');

        $title = 'Сообщения';
        $view = MainConfig::$VIEW_CHATS_LIST;
        $page = MainConfig::$PAGE_CHATS_LIST;
        $this->setBreadcrumbs($title, $page);
        $model = Share::$UserProfile->makeChat();

        switch ($section)
        {
            case 'vacancies':
                $title = 'Сообщения по вакансиям';
                $view = MainConfig::$VIEW_CHATS_LIST_VACANCIES;
                $page = MainConfig::$PAGE_CHATS_LIST_VACANCIES;
                if(strlen($vacancy) && strlen($id)) // private
                {
                    if(!$model->hasAccess('vacancy',$id,$vacancy))
                        throw new CHttpException(404, 'Error');

                    $view = MainConfig::$VIEW_CHATS_ITEM_VACANCY_PERSONAL;
                    $data = $model->getVacancyPersonal($vacancy,$id);
                }
                elseif(strlen($vacancy)) // public
                {
                    $model = new VacDiscuss;
                    if(!intval($vacancy) || !$model->hasAccess($vacancy))
                        throw new CHttpException(404, 'Error');

                    $view = MainConfig::$VIEW_CHATS_ITEM_VACANCY_PUBLIC;
                    $data = $model->getDiscuss($vacancy);

                    if(Yii::app()->request->isAjaxRequest)
                    {
                        $this->renderPartial(
                            MainConfig::$VIEW_CHATS_ITEM_VACANCY_PUBLIC_AJAX,
                            array('viData' => $data), 
                            false, 
                            true
                        );
                        return;
                    }
                }
                else // list
                {
                    $view = MainConfig::$VIEW_CHATS_LIST_VACANCIES;
                    $data = $model->getVacanciesChats();
                }

                break;

            case 'feedback':
                $title = 'Сообщения Prommu';
                $view = MainConfig::$VIEW_CHATS_LIST_FEEDBACK;
                $page = MainConfig::$PAGE_CHATS_LIST_FEEDBACK;
                if(strlen($id))
                {
                    if(!$model->hasAccess('feedback',$id))
                        throw new CHttpException(404, 'Error');

                    if($rq->getParam('change_status')=='Y')
                    {
                      $model = new Feedback();
                      $model->ChangeModer($rq->getParam('feedback'), $rq->getParam('status'));
                      $model->setNew($rq->getParam('feedback'));
                      Yii::app()->user->setFlash('prommu_flash','Статус обращения изменен');
                      $this->redirect('/user/chats/feedback');
                    }

                    $view = MainConfig::$VIEW_CHATS_ITEM_FEEDBACK;
                    $data = $model->getMessViewData($id);
                }
                else
                {
                    $data = $model->getFeedbackChats();
                    $feedback = new Feedback;
                    $data['directs'] = $feedback->getDirects();
                    $data['statuses'] = $feedback->getStatus();
                }

                break;
            default:
                $data = $model->getAllChats();
                break;
        }




        if(!empty($section))
            $this->setBreadcrumbsEx([$title, $page]);

        $this->render(
                $view, 
                array(
                    'viData' => $data, 
                    'section' => $section, 
                    'id' => $id,
                    'vacancy' => $vacancy
                ),
                array('htmlTitle' => $title)
            );
    }
    /**
     *  developer`s page
     */
    public function actionTest()
    {
        // in_array(Share::$UserProfile->id, [7000,15642]) || $this->redirect(MainConfig::$PAGE_LOGIN);
        $rate = new RateEmpl();
        $data['rating'] = $rate->getDynamicRate(Share::$UserProfile->id );
        $data['rating'] = $rate->prepareProfileCommonDynamicRate($data['rating']);

        $this->render('test',
            array('viData' => $data,
                ));
      
    }
    /**
     *  самозанятый
     */
    public function actionSelf_employed()
    {
        !Share::isApplicant() && $this->redirect(MainConfig::$PAGE_LOGIN);
        $data = array();
        $data['inn'] = Share::$UserProfile->getUserAttribute(['key'=>'self_employed']);
        if(!$data['inn'])
        {
            if(Yii::app()->request->isPostRequest) // записываем данные
            {
                $inn = filter_var(Yii::app()->getRequest()->getParam('inn'), FILTER_SANITIZE_NUMBER_INT);
                Share::$UserProfile->setUserAttribute('self_employed',$inn);
                Yii::app()->user->setFlash('prommu_flash','Спасибо что подтвердили статус. Теперь Ваш профиль стал более конкурентным среди других Соискателей');
                $this->redirect(MainConfig::$PAGE_PROFILE);
            }
            $model = new PagesContent;
            $lang = Yii::app()->session['lang'];
            $data['agreement'] = $model->getPageContent('conditions', $lang);
            $this->setBreadcrumbsEx(
                ['Профиль', MainConfig::$PAGE_PROFILE],
                ['Как стать самозанятым', MainConfig::$VIEW_SELF_EMPLOYED]
            );
            $this->setPageTitle('Как стать самозанятым');
        }
        else
        {
            $this->setBreadcrumbsEx(
                ['Профиль', MainConfig::$PAGE_PROFILE],
                ['Личный счет', MainConfig::$VIEW_SELF_EMPLOYED]
            );
            $this->setPageTitle('Личный счет');
        }

        $this->render('self-employed',['viData'=>$data]);
    }
    /**
    *  Проверка самозанятого
    */
    public function actionCheck_self_employed()
    {
      !Share::isEmployer() && $this->redirect(MainConfig::$PAGE_LOGIN);

      $this->setBreadcrumbsEx(
        ['Профиль', MainConfig::$PAGE_PROFILE],
        ['Проверка налогового статуса соискателя', MainConfig::$VIEW_CHECK_SELF_EMPLOYED]
      );
      $this->setPageTitle('Проверка налогового статуса');
      $this->render('check-self-employed');
    }
    /**
     *
     */
    public function actionLegal_entity_receipt()
    {
      $id = filter_var(Yii::app()->getRequest()->getParam('id'), FILTER_SANITIZE_NUMBER_INT);
      $model = new PrommuOrder();
      $data = $model->getLegalEntityReceipt($id);
      !$data && $this->redirect(DS);

      if(Yii::app()->getRequest()->getParam('pdf')==1)
      {
        $mPDF1 = Yii::app()->ePdf->mpdf();
        $mPDF1->WriteHTML($this->renderPartial(MainConfig::$VIEW_LEGAL_ENTITY_RECEIPT,['viData'=>$data],true));
        $mPDF1->Output();
      }
      else
      {
        $this->renderPartial(MainConfig::$VIEW_LEGAL_ENTITY_RECEIPT,['viData'=>$data]);
      }
    }
    /**
     *  Страница с ГТМ после регистрации
     */
    public function actionLead()
    {
      $this->renderRegisterGTM();
    }
    /**
     *  Страница с ГТМ после ктивации профиля
     */
    public function actionActive_profile()
    {
      $this->renderRegisterGTM();
    }
}
