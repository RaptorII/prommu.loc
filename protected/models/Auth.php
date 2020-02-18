<?php

/**
 * Date: 11.02.16
 * Time: 22:23
 */
class Auth
{
    public function registerUser($inParam)
    {
        // регистрация соискателя
        if( in_array($inParam, ['1', 'vk', 'fb']) )
        {
            // проверка полей
            $res = $this->checkFieldsApplicant();

            if( $res['error'] )
            {
                return $res;


            } else {               
                if(empty($res['inputData']['sex'])){
                    $res['inputData']['sex'] = Yii::app()->getRequest()->getParam('sex');
                }          
                if(empty($res['inputData']['smart'])){
                    $res['inputData']['smart'] = Yii::app()->getRequest()->getParam('smart');
                }
                $res['type'] = 2;
                $res = $this->registerUserFirsStep($res);

                return $res;
            } // endif



        // *** Регистрация работодателя ***
        } else {
            // проверка полей
            $res = $this->checkFieldsEmpl();


            if( $res['error'] )
            {
                return $res;


            } else {
                $res['type'] = 3;
                $res = $this->registerUserFirsStep($res);

                return $res;
            } // endif
        } // endif
    }

    public function authChekin($messenger){

        $usRes = Yii::app()->db->createCommand()
            ->select("u.id_user id,, u.email, u.status, u.isblocked, u.passw passw
            ")
            ->from('user u')
            ->where('u.messenger=:messenger', array(':messenger' => $messenger))
            ->queryRow();

        return $usRes;
    }

    public function doAuth($inParam)
    {
      $rq = Yii::app()->getRequest();
      // *** Авторизация через форму ***
      if( $rq->isPostRequest && $rq->getParam('login') && $rq->getParam('passw') )
      {
        $result = $this->doLogin([
          'login' => $inParam->login,
          'passw' => $inParam->passw,
          'remember' => $inParam->remember
        ]);
      }
      else // *** Авторизация через cookie ***
      {
        $result = $this->doAuthenicate();
      }

      switch ($result['error'])
      {
        case 100 : // Создаём профиль
          Share::$UserProfile = (new ProfileFactory())->makeProfile([
            'id' => Yii::app()->session['au_us_data']->id,
            'type' => Yii::app()->session['au_us_type']
          ]);
          Share::$UserProfile instanceof UserProfile && Share::$UserProfile->setUserData();
          break;

        default: // guest
          Share::$UserProfile = (new ProfileFactory())->makeProfile(['id' => 0]);
      }

      return ($result['error'] < 0
        ? ['auth'=>0, 'message'=>$result['message'], 'error'=>1]
        : ['auth'=>1]);
    }




    /**
     * Авторизация через API
     * @params  login - email пользователя при регистрации
     *          pass - md5 пароля
     * @return array
     */
    public function doAPIAuth()
    {
        $error = -101;
        try
        {
            $login = filter_var(Yii::app()->getRequest()->getParam('login'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $pass = filter_var(Yii::app()->getRequest()->getParam('pass'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            // *** ПРоверка параметров ***
            if(  !empty($login)
                    && !empty($pass)
            )
            {
                $data = $this->doLogin(['login' => $login, 'passmd5' => $pass, 'remember' => 1]);
                $error = $data['error'];

            // *** Не корректны параметры запроса ***
            } else {
                throw new Exception('', -101);
            } // endif

            // если была ошибка при авторизации
            if( $data['error'] < 0 )
            {
                $message = $data['message'];
                throw new Exception('', -102);
            }


//            switch ($data['error'])
//            {
//                // Создаём профиль
//                case 100 : Share::$UserProfile = $Profile = (new ProfileFactory())->makeProfile(array('id' => Yii::app()->session['au_us_data']->id, 'type' => Yii::app()->session['au_us_type']));
//                    $Profile instanceof UserProfile && $Profile->setUserData();
//                    break;
//
//                // guest
//                default: Share::$UserProfile = $Profile = (new ProfileFactory())->makeProfile(array('id' => 0));
//            }



        }
        catch (Exception $e) {
            $error = $e->getCode();
            switch ($e->getCode()) {
               case -101: $error = -106; $message = "Некорректные параметры запроса авторизации"; break;
               case -102: $error = $data['error']; break;
            }
        } // endtry

        if( $error < 0 ) return array('error_code' => abs($error), 'message' => $message);
        else return array('access_token' => $data['data']['token'], 'id' => $data['data']['idus'], 'type' => $data['data']['type'], 'exp_date' => strtotime('+1 day'));
    }



    /**
     * Авторизируем пользователя, c логином и паролем. Также можно просто авторизировать пользователя передав его ID
     * @param $login
     * @param $passw
     * @param $remember - запомнить на долго
     * @param int $usId
     * @return array
     * @throws Exception
     */
    public function Authorize($inParams)
    {
        $login = $inParams['login'];
        $passw = $inParams['passw'];
        $remember = $inParams['remember'];
        $usId = $inParams['id'] ?: 0;

        $login = !empty($login) ? stripslashes(trim($login)) : '';
        $passw = !empty($passw) ? stripslashes(trim($passw)) : '';

        $passMd5 = $inParams['passmd5'] ?: md5($passw);

        $user = 0;
        if($usId>0) {
            $user = User::model()->find(array(
                'select' => 'id_user, status, passw, isblocked',
                'condition' => "id_user = :idus",
                'params'=>array(':idus' => $usId),
            ));
        }
        else {
            $user = User::model()->find(array(
                'select' => 'id_user, status, passw, isblocked',
                'condition' => "email = :email OR login = :email",
                'params'=>array(':email' => $login),
            ));            
        }

        /*
            $user = User::model()->find(array(
                'select' => 'id_user, status, passw, isblocked',
                'condition' => "email = :email OR id_user = :idus OR login = :email",
                'params'=>array(':email' => $login, ':idus' => $usId),
            ));
        */

         //'condition' => "email = :email OR id_user = :idus OR login = :login",
                //'params'=>array(':email' => $login, ':idus' => $usId, ':login' => $login),
//        $sql = "select id_user, status, email, passw from user where email = '$login' and passw = md5('$passw') OR id_user = {$usId} limit 1;";
//        $res = Yii::app()->db->createCommand($sql)->queryAll();

        // проверяем пароль и блокировку
        if( $user && $user->id_user )
        {
            if( $usId )
            {
                $login = $user->email;
            }
            else
            {
                if( $user->passw != $passMd5 ) throw new Exception('', -102);
                elseif( (int)$user->isblocked === 1 ) throw new Exception('', -104);
                //elseif( (int)$user->isblocked === 2 ) throw new Exception('', -105);
//                elseif( (int)$user->isblocked === 3 ) throw new Exception('', -106);
                elseif( !in_array((int)$user->isblocked, [0,2,3,5]) ) throw new Exception('', -103);
            } // endif
        }
        else
        {
            throw new Exception('', -101);
        } // endif

//        $status = $user->status;

        // BM: Generate TOKEN
        $token = md5($login . date("d.m.Y") . $passMd5 . rand(100000,1000000));
        $uid = md5($user->id_user);
        $usRes = Yii::app()->db->createCommand()
            ->select("u.id_user id, u.login, u.email, u.status, u.isblocked, u.statuses
                , w.id wid
                , r.id id_resume
                , r.lastname
                , r.firstname
                , DATE_FORMAT(r.birthday, '%d.%m.%Y') birthday
                , CONCAT(r.firstname,\" \",r.lastname) fio
                , r.photo
                , e.id eid
                , CONCAT(e.firstname,\" \",e.lastname) efio
                , e.name
                , e.logo
            ")
            ->from('user u')
            ->leftJoin('user_work w', 'u.id_user = w.id_user')
            ->leftJoin('resume r', 'r.id_user = u.id_user AND u.status = 2')
            ->leftJoin('employer e', 'e.id_user = u.id_user AND u.status = 3')
            ->where('u.id_user=:id_user', array(':id_user' => $user->id_user))
            ->queryRow();

//        if ($usRes['wid'] > 0)
//        {
//            $res = Yii::app()->db->createCommand()
//                ->update('user_work', array(
//                    'token' => $token,
//                    'date_login' => date('Y-m-d H:i:s'),
//                ), 'id_user=:id_user', array(':id_user' => $usRes['id']));
//
//            $res = Yii::app()->db->createCommand()
//                ->update('user', array(
//                    'mdate' => date('Y-m-d H:i:s'),
//                    'is_online' => 1,
//                ), 'id_user=:id_user', array(':id_user' => $usRes['id']));
//
//        } else {
            $res = Yii::app()->db->createCommand()
                ->insert('user_work', array(
                    'token' => $token,
                    'uid' => $uid,
                    'id_user' => $usRes['id'],
                    'date_login' => date('Y-m-d H:i:s'),
                ));
            
            $res = Yii::app()->db->createCommand()
                ->update('user', array(
                    'mdate' => date('Y-m-d H:i:s'),
                    'is_online' => 1,
                ), 'id_user=:id_user', array(':id_user' => $usRes['id']));
//        }


        $usData = (object)$usRes;
        $this->saveAuthData(array('uid' => $uid,
                    'token' => $token,
                    'type' => $usData->status,
                    'remember' => $remember,
                    'userData' => $usData,
                ));

        // тут есть лишние
        return array(
            "token" => $token, // not used
            "uid" => $uid, // not used
            "idus" => $usData->id, // not used
            "type" => $usData->status,
//            "login" => $login,
//            "rating" => 0,
//            "count_resp" => 0,
//            "user_data" => $usData,
        );
    }

    public function AuthorizeNet($inParams)
    {
        $login = $inParams['login'];
        $passw = $inParams['passw'];
        $remember = $inParams['remember'];
        $usId = $inParams['id'] ?: 0;

        $login = !empty($login) ? stripslashes(trim($login)) : '';
        $passw = !empty($passw) ? stripslashes(trim($passw)) : '';

        $passMd5 = $inParams['passmd5'] ?: md5($passw);

        $user = User::model()->find(array(
                'select' => 'id_user, status, passw, isblocked',
                'condition' => "email = :email OR id_user = :idus",
                'params'=>array(':email' => $login, ':idus' => $usId),
            ));
         //'condition' => "email = :email OR id_user = :idus OR login = :login",
                //'params'=>array(':email' => $login, ':idus' => $usId, ':login' => $login),
//        $sql = "select id_user, status, email, passw from user where email = '$login' and passw = md5('$passw') OR id_user = {$usId} limit 1;";
//        $res = Yii::app()->db->createCommand($sql)->queryAll();

        // проверяем пароль и блокировку
        if( $user && $user->id_user )
        {
            if( $usId )
            {
                $login = $user->email;
            }
            else
            {
                if( $user->passw != $passMd5 ) throw new Exception('', -102);
                elseif( (int)$user->isblocked === 1 ) throw new Exception('', -104);
                //elseif( (int)$user->isblocked === 2 ) throw new Exception('', -105);
//                elseif( (int)$user->isblocked === 3 ) throw new Exception('', -106);
                elseif( !in_array((int)$user->isblocked, [0,2,3,5]) ) throw new Exception('', -103);
            } // endif
        }
        else
        {
            throw new Exception('', -101);
        } // endif

//        $status = $user->status;

        // BM: Generate TOKEN
        $token = md5($login . date("d.m.Y") . $passMd5 . rand(100000,1000000));
        $uid = md5($user->id_user);

        $usRes = Yii::app()->db->createCommand()
            ->select("u.id_user id, u.login, u.email, u.status, u.isblocked, u.statuses
                , w.id wid
                , r.id id_resume
                , r.lastname
                , r.firstname
                , DATE_FORMAT(r.birthday, '%d.%m.%Y') birthday
                , CONCAT(r.firstname,\" \",r.lastname) fio
                , r.photo
                , e.id eid
                , CONCAT(e.firstname,\" \",e.lastname) efio
                , e.name
                , e.logo
            ")
            ->from('user u')
            ->leftJoin('user_work w', 'u.id_user = w.id_user')
            ->leftJoin('resume r', 'r.id_user = u.id_user AND u.status = 2')
            ->leftJoin('employer e', 'e.id_user = u.id_user AND u.status = 3')
            ->where('u.id_user=:id_user', array(':id_user' => $user->id_user))
            ->queryRow();


        if ($usRes['wid'] > 0)
        {
            $res = Yii::app()->db->createCommand()
                ->update('user_work', array(
                    'token' => $token,
                    'date_login' => date('Y-m-d H:i:s'),
                ), 'id_user=:id_user', array(':id_user' => $usRes['id']));
            
            $res = Yii::app()->db->createCommand()
                ->update('user', array(
                    'mdate' => date('Y-m-d H:i:s'),
                    'is_online' => 1,
                ), 'id_user=:id_user', array(':id_user' => $usRes['id']));
                
        } else {
            $res = Yii::app()->db->createCommand()
                ->insert('user_work', array(
                    'token' => $token,
                    'uid' => $uid,
                    'id_user' => $usRes['id'],
                    'date_login' => date('Y-m-d H:i:s'),
                ));
            
            $res = Yii::app()->db->createCommand()
                ->update('user', array(
                    'mdate' => date('Y-m-d H:i:s'),
                    'is_online' => 1,
                ), 'id_user=:id_user', array(':id_user' => $usRes['id']));
        }


        $usData = (object)$usRes;

        $this->saveAuthData(array('uid' => $uid,
                    'token' => $token,
                    'type' => $usData->status,
                    'remember' => $remember,
                    'userData' => $usData,
                ));

        // тут есть лишние
        return array(
            "token" => $token, // not used
            "uid" => $uid, // not used
            "idus" => $usData->id, // not used
            "type" => $usData->status,
//            "login" => $login,
//            "rating" => 0,
//            "count_resp" => 0,
//            "user_data" => $usData,
        );
    }

    private function checkAuth($inData)
    {
        $res = Yii::app()->db->createCommand()
            ->select('id_user id, date_login')
            ->from('user_work')
            ->where(array('and', 'uid=:uid', 'token=:token'), array(':uid' => $inData->uid, ':token' => $inData->token))
            ->queryRow();

//        $res = Yii::app()->db->createCommand($sql = "select id, date_login from user_work where uid='{$inData->uid}' and token='{$inData->token}'")->queryRow();

//print  "<pre> \$idus, $datel :"
//      . print_r('checkAuth', 1)."\n"
//      . print_r($sql, 1)."\n"
//      . var_export($res, 1)."\n"
//      . var_export((new DateTime($res['date_login']))->getTimestamp(), 1)."\n"
//      ."</pre>";//exit;
        if ($res['id'] > 0)
        {
            $usRes = Yii::app()->db->createCommand()
                ->select("u.id_user id, u.login, u.email, u.status, u.isblocked
                    , r.id id_resume
                    , r.lastname
                    , r.firstname
                    , DATE_FORMAT(r.birthday, '%d.%m.%Y') birthday
                    , CONCAT(r.firstname,' ',r.lastname) fio
                    , r.photo
                    , r.isman
                    , e.id eid
                    , e.name
                    , e.logo
                ")
                ->from('user u')
                ->leftJoin('resume r', 'r.id_user = u.id_user AND u.status = 2')
                ->leftJoin('employer e', 'e.id_user = u.id_user AND u.status = 3')
                ->where('u.id_user=:id_user', array(':id_user' => $res['id']))
                ->queryRow();

            $usData = (object)$usRes;
//            if( $usData->status == 3 ) {
//                $usData->lastname = $usData->fff;
//                $usData->firstname = $usData->iii;
//            }

//            Share::$userType = $usData->status;

            $data = json_decode(base64_decode(Yii::app()->request->cookies['prommu']));
            $this->saveAuthData(array('uid' => $inData->uid,
                        'token' => $inData->token,
                        'type' => $usData->status,
                        'remember' => $data->remember,
                        'userData' => $usData,
                    ));
            return 1;
//            $record = User::model()->findByAttributes(array('id_user'=>$res['id']));
            // Аутентифицируем пользователя по имени и паролю
//            $identity=new UserIdentityCustom('','');
//            if($identity->authenticate())
//                Yii::app()->user->login($identity);
//            else
//                echo $identity->errorMessage;


//                $res = Yii::app()->db->createCommand()->delete('user_work', 'id_user=:id', array(':id'=>$res['id']));
        } else {
            return 0;
        }
    }


    /**
     * @param authData - uid, token, type
     * @param remember - ckeckbox from form
     * @param userDara - from table
     */
  private function saveAuthData($inData)
    {
        if( is_array($inData) ) $inData = (object)$inData;
        $exptime = $inData->remember == '1' ? MainConfig::$AUTH_EXPIRE_TIME_LONG : MainConfig::$AUTH_EXPIRE_TIME;
        $date = (new DateTime())->add(new DateInterval('PT'.$exptime.'S'));
        $exptime = $date->getTimestamp();
        $data = json_encode(array('uid'=>$inData->uid, 'token'=>$inData->token, 'exptime' => $exptime, 'remember' => $inData->remember));
        //Yii::app()->request->cookies['prommu'] = new CHttpCookie('prommu', base64_encode($data));
        $cookie = new CHttpCookie('prommu', base64_encode($data));
        $cookie->expire = time() + MainConfig::$AUTH_EXPIRE_TIME_LONG;
        Yii::app()->request->cookies['prommu'] = $cookie;


        $session = Yii::app()->session;
        $session['au_uid'] = $inData->uid;
        $session['au_token'] = $inData->token;
        $session['au_exptime'] = $exptime;
        $session['au_us_type'] = $inData->type;
        $session['au_us_data'] = $inData->userData;
    }




    /**
     * Аутентификация через логин и пароль
     * @param $login
     * @param $passw
     * @param $remember
     * @param passmd5 - для авторизации через hash пароля
     * @return array
     */
    private function doLogin($inParams )
//    private function doLogin( $login, $passw, $remember, $usId = 0 )
    {
        $login = $inParams['login'];
        $passw = $inParams['passw'];
        $remember = $inParams['remember'];

        $error = 100;
        try
        {
            $data = $this->Authorize(['login' => $login, 'passw' => $passw, 'remember' => $remember, 'passmd5' => $inParams['passmd5']]);
        }
        catch (Exception $e)
        {
            $error = $e->getCode();
            switch ($e->getCode()) {
               case -101:
               case -102: $message = "Таких учетных данных не обнаружено среди зарегистрированных пользователей"; break;
               case -104:
               case -103: $message = "Пользователь с таким логином заблокирован"; break;
               case -105: $message = "Ваш пользователь ожидает активации через почту, перейдите по ссылке в письме на почтовом ящике, который вы указали при регистрации.<br>Если письмо долго не приходит - проверьте папку спам, так как почтовый сервер может быть чересчур бдительным."; break;
               // case -106: Ошибка параметров запроса, ЗАРЕЗЕРВИРОВАННО !!!!!!!!!!!!!!!!!!!!!!!
            }
        }

        return ['error' => $error, 'message' => $message, 'data' => $data];
    }



    /**
     * Аутентификация через сессию
     * @return array
     */
    private function doAuthenicate()
    {
        $error = 100;
        try
        {
            // авторизирован в этой сессии
            $session = Yii::app()->session;
/*
            if( $session['au_uid'] )
            {
                // проверяем exptime
                if( $session['au_exptime'] > time() )
                {
                    $error = 100;
                    $user = User::model()->findByPk($session['au_us_data']->id);

                    if( !$user ) $error = -105; // фигня с записью пользователя
                    elseif( !in_array((int)$user->isblocked, [0,3]) ) $error = -104; // блокирован
                    if( $error < 0 ) $this->resetAuthData();

                    throw new Exception('', $error);
                }
                else
                {
                    $this->resetAuthData();
                    throw new Exception('', -101);
                } // endifr


            // не авторизирован в этой сессии
            } else {
*/
                if( Yii::app()->request->cookies['prommu'] && $data = Yii::app()->request->cookies['prommu']->value )
                {
                    $data = json_decode(base64_decode($data));

                    // проверяем exptime
                    if( $data->exptime > time() )
                    {
                        $res = $this->checkAuth($data);
                        throw new Exception('', $res ? 100 : -103);
                    }
                    else
                    {
                        throw new Exception('', -101);
                    } // endif
                }
                else
                {
                    throw new Exception('', -102);
                } // endif
            //} // endif


        }
        catch (Exception $e)
        {
            $error = $e->getCode();
            switch ($e->getCode()) {
               case -101:
               case -102:
               case -103: $message = "Пользователь не авторизирован"; break;
               case -104: $message = "Пользователь был заблокирован, по вопросам блокировки обращайтесь в администрацию системы через форму обратной связи"; break;
               case -105: $message = "Требуется повторная авторизация"; break;
            }
            if( in_array($e->getCode(), [-104, -105]) ) Yii::app()->user->setFlash('auErrMess', $message);
        }
        return ['error' => $error, 'message' => $message];
    }

    private function checkFieldsApplicant()
    {
        $inputData = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $key = 'name';
        if( empty($inputData[$key]) )
        {
            $message = "Ошибки заполнения формы";
            $hint = 'введите имя';
            $flag_error = 1;
            $element = $key;
        } // endif


        $key = 'lname';
        if( !$flag_error && empty($inputData[$key]) )
        {
            $message = "Ошибки заполнения формы";
            $hint = 'введите фамилию';
            $flag_error = 1;
            $element = $key;
        } // endif


        $key = 'email';
        if( !$flag_error && !filter_var($inputData[$key], FILTER_VALIDATE_EMAIL) )
        {
            $message = "Ошибки заполнения формы";
            $hint = 'введите правильный электронный адрес';
            $flag_error = 1;
            $element = $key;
         } else {
            // нет есть в системе и статус = регистрация 1 шаг
            if( (new User())->find("email = '{$inputData[$key]}'") )
            {
                $message = "Такой email уже зарегистрирован в системе";
                $hint = 'введите другой email адрес';
                $flag_error = 1;
                $element = $key;
            } // endif
        } // endif
// endif


        $key = 'pass';
        $data = Yii::app()->getRequest()->getParam('pass');
        if( !$flag_error )
        {
            $element = $key;
            if( empty($data) )
            {
                $message = "Ошибки заполнения формы";
                $hint = 'введите пароль';
                $flag_error = 1;
            }
            elseif( $data != Yii::app()->getRequest()->getParam('passrep') )
            {
                $message = "Ошибки заполнения формы";
                $hint = 'пароль и его повтор не совпадают';
                $flag_error = 1;
            } // endif
        } // endif


        if( $flag_error )
        {
            unset($inputData['pass']);
            unset($inputData['passrep']);
        } // endif


        // CAPTCHA
        $model = new Settings;
        $use_recaptcha = boolval($model->getDataByCode('register_captcha'));
        if($use_recaptcha==true)
        {
            $recaptcha = Yii::app()->getRequest()->getParam('g-recaptcha-response');
            if(!empty($recaptcha))
            {
                $google_url="https://www.google.com/recaptcha/api/siteverify";
                $secret='6Lf2oE0UAAAAAPkKWuPxJl0cuH7tOM2OoVW5k6yH';
                $ip=$_SERVER['REMOTE_ADDR'];
                $url=$google_url."?secret=".$secret."&response=".$recaptcha."&remoteip=".$ip;
                //
                $curl = curl_init();
                curl_setopt($curl, CURLOPT_URL, $url);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($curl, CURLOPT_TIMEOUT, 10);
                curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16");
                $res = curl_exec($curl);
                curl_close($curl);
                //
                $res = json_decode($res, true);//reCaptcha введена
                if(!$res['success']) // wrong captcha
                {
                    $message = "Ошибки заполнения формы";
                    $hint = 'Вы допустили ошибку при прохождении проверки "Я не робот"';
                    $flag_error = 1;
                    $element = 'recaptcha';
                }
            }
            else
            {
                $message = "Ошибки заполнения формы";
                $hint = 'Необходимо пройти проверку "Я не робот"';
                $flag_error = 1;
                $element = 'recaptcha';
            }
        }

        return array(
                'message' => $message,
                'hint' => $hint,
                'error' => $flag_error,
                'element' => $flag_error ? $element : '',
                'inputData' => $inputData,
                'use_recaptcha' => $use_recaptcha
            );
    }



    private function userSelect($inWhere)
    {
        $res = Yii::app()->db->createCommand()
            ->select('u.*')
            ->from('user u')
            ->where($inWhere)
            ->queryRow();

        return $res;
    }



    private function userInsert($inData, $isRetId = 0)
    {
        if( $inData['login'] ) $data['login'] = $inData['login'];
        if( $inData['passw'] ) $data['passw'] = md5($inData['passw']);
        if( $inData['email'] ) $data['email'] = ($inData['email']);
        if( $inData['messenger'] ) $data['messenger'] = ($inData['messenger']);
        if( $inData['status'] ) $data['status'] = ($inData['status']);
        if( $inData['isblocked'] ) $data['isblocked'] = 3;
        $data['access_time'] = $inData['access_time'] ? $inData['access_time'] : date('Y-m-d H:i:s');
        $data['crdate'] = date('Y-m-d H:i:s');
        $data['mdate'] = date('Y-m-d H:i:s');
        $data['ismoder'] = '0';

        $res = Yii::app()->db->createCommand()
            ->insert('user', $data);

        if( $isRetId )
            return Yii::app()->db->createCommand('SELECT LAST_INSERT_ID()')
                ->queryScalar();
    }



    private function userUpdate($inData, $inWhere)
    {
        if( $inData['login'] ) $data['login'] = $inData['login'];
        if( $inData['passw'] ) $data['passw'] = md5($inData['passw']);
        if( $inData['email'] ) $data['email'] = ($inData['email']);
        if( $inData['status'] ) $data['status'] = ($inData['status']);
        if( $inData['isblocked'] ) $data['isblocked'] = 3;
        $data['access_time'] = $inData['access_time'] ? $inData['access_time'] : date('Y-m-d H:i:s');

        $res = Yii::app()->db->createCommand()
            ->update('user', $data, $inWhere);


    }



    public function userActivateInsertUpdate($inData)
    {
        $res = Yii::app()->db->createCommand()
            ->select("id_user")
            ->from('user_activate')
            ->where('id_user = :uid', array(':uid' => $inData['id_user']))
            ->queryScalar();

        if( $res )
        {
            $res = Yii::app()->db->createCommand()
                ->update('user_activate', $inData, 'id_user = ' . $res);
        }
        else
        {
            $res = Yii::app()->db->createCommand()
                ->insert('user_activate', $inData);
        } // endif
    }


    private function registerUserFirsStep($inData)
    {
        $res = $this->userSelect("email = '{$inData['inputData']['email']}'");
        $idUs = $res['id_user'];
        $admin = filter_var(Yii::app()->getRequest()->getParam('admin'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $referer = filter_var(Yii::app()->getRequest()->getParam('referer'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $transition = filter_var(Yii::app()->getRequest()->getParam('transition'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $canal = filter_var(Yii::app()->getRequest()->getParam('canal'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $campaign = filter_var(Yii::app()->getRequest()->getParam('campaign'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $content = filter_var(Yii::app()->getRequest()->getParam('content'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $keywords = filter_var(Yii::app()->getRequest()->getParam('keywords'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $point = filter_var(Yii::app()->getRequest()->getParam('point'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $last_referer = filter_var(Yii::app()->getRequest()->getParam('last_referer'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $robot = Yii::app()->getRequest()->getParam('lastname');
        $ip = Yii::app()->getRequest()->getParam('ip');
        $pm = Yii::app()->getRequest()->getParam('pm_source');
        if($pm == '') $pm = 'none';
        $ips  = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote  = @$_SERVER['REMOTE_ADDR'];
        
        if(filter_var($ips, FILTER_VALIDATE_IP)) $ip = $ips;
        elseif(filter_var($forward, FILTER_VALIDATE_IP)) $ip = $forward;
        else $ip = $remote;
                
        $clients = Yii::app()->db->createCommand()
                ->select("*")
                ->from('user_client a')
                ->where('a.ip = :ip', array(':ip' => $ip))
                ->queryRow();
        $client = $clients['client'];

        if( $idUs && $res['isblocked'] != 2 )
        {
            return array('error' => 1, 'message' => 'Пользователь с таким email адресом уже есть', 'inputData' => $inData['inputData']);
        } // endif

        // пользователь уже есть
        if($idUs)
        {
            $this->userUpdate(array('email' => $inData['inputData']['email'],
                'passw' => $inData['inputData']['pass'],
                'isblocked' => 3,
                'ismoder' => 0,
                'status' => $inData['type'],
             
            ), 'id_user = ' . $res['id_user']);
            $idUser = $idUs;



        } else {
            $idUs = $this->userInsert(array('email' => $inData['inputData']['email'],
                'passw' => $inData['inputData']['pass'],
                'isblocked' => 3,
                'ismoder' => 0,
                'status' => $inData['type']
            ), 1);
            
            ///create mailing event
            $Mailing = new Mailing();
            $template = new MailingTemplate;
            $template = $template->getActiveTemplate();
        
            $receiver = $inData['inputData']['email'];
            $title = 'Активация аккаунта';
            for($i = 10; $i < 15; $i = $i + 5){
                $rdate = date('Y-m-d H:i', strtotime(" +{$i} minutes"));
                $body = str_replace(
                        MailingTemplate::$CONTENT,
                        'Активируйте профиль',
                        $template->body
                    );
                $set = $Mailing->setToMailingNotActive($receiver,$title,$body,$isUrgent=false, $rdate);
            }
             ///create mailing event


            $idUser = 0;
               $token = md5($inData['inputData']['email'] . date("d.m.Y H:i:s") . md5($inData['inputData']['pass']));
        $uid = md5($idUs);
        $password = $inData['inputData']['pass'];

        } // endif
        // Generate TOKEN

        $token = md5($inData['inputData']['email'] . date("d.m.Y H:i:s") . md5($inData['inputData']['pass']));
        $uid = md5($idUs);

        $this->userActivateInsertUpdate(array('id_user' => $idUs,
            'token' => $token,
            'data' => json_encode($inData['inputData']),
            'dt_create' => date('Y-m-d H:i:s'),
        ));

        $sex = $inData['inputData']['sex'];
        $smart = $inData['inputData']['smart'];

        $analytData = array('id_us' => $idUs,
                        'name' => 'NO ACTIVE',
                        'date' =>  date('Y-m-d H:i:s'),
                        'type' => $inData['type'],
                        'referer' => $referer,
                        'canal' => $canal,
                        'campaign' => $campaign,
                        'content' => $content, 
                        'keywords' => $keywords,
                        'transition' => $transition,
                        'point' => $point, 
                        'last_referer' => $last_referer,
                        'active' => 0,
                        'subdomen' => 0,
                        'client' => $client,
                        'ip' => $ip, 
                        'source' => $pm
                    );

        $res = Yii::app()->db->createCommand()
                        ->insert('analytic', $analytData);
                        
        if( $inData['type'] == 2 )
        {
            $link  = 'http://' . $_SERVER['HTTP_HOST'] . MainConfig::$PAGE_ACTIVATE . '/?type=2&t=' . $token . "&uid=" . $idUs."&referer=".$referer."&transition=".$transition."&canal=".$canal."&campaign=".$campaign."&content=".$content."&keywords=".$keywords."&point=".$point."&last_referer=".$last_referer."&admin=".$admin."&sex=".$sex."&smart=".$smart."&ip=".$ip."&client=".$client."&pm=".$pm;
            $message = '<p style="font-size:16px">Наш портал <b>Prommu.com</b> позволяет найти работу в России и странах СНГ совершенно бесплатно.</p>'
            .'<br/>'
            .'<p style=" margin-bottom: 0px;font-size:16px">Мы предлагаем временную работу и интересную подработку по следующим вакансиям:</p>'
            .'<br/>'
            .'<div class="hh" style="position: relative;  text-align: center">'
                .'<ul class="reg__vacancies-list">'
                    .'<li class="reg__vacancies-list-item"><i></i><span>Промоутер</span></li>'
                    .'<li class="reg__vacancies-list-item"><i></i><span>Мерчендайзер</span></li>'
                    .'<li class="reg__vacancies-list-item"><i></i><span>Ростовая кукла</span></li>'
                    .'<li class="reg__vacancies-list-item"><i></i><span>Супервайзер</span></li>'
                    .'<li class="reg__vacancies-list-item"><i></i><span>Тайный покупатель</span></li>'
                .'</ul>'
                .'<ul class="reg__vacancies-list">'
                    .'<li class="reg__vacancies-list-item"><i></i><span>Аниматор</span></li>'
                    .'<li class="reg__vacancies-list-item"><i></i><span>Интервьюер</span></li>'
                    .'<li class="reg__vacancies-list-item"><i></i><span>Консультант</span></li>'
                    .'<li class="reg__vacancies-list-item"><i></i><span>Модель</span></li>'
                    .'<li class="reg__vacancies-list-item"><i></i><span>Хостес</span></li>'
                .'</ul>'
            .'</div>'
            .'<br/>'
            .'<p style=" font-size:16px;">Почему мы именно вам предлагаем воспользоваться нашим сервисом? У нас вы можете найти работу, которая будет вам действительно интересна.<br/>Вам достаточно <a href ='.$link.'><ins>подтвердить регистрацию</ins></a> на нашем портале и воспользоваться логином/паролем для входа на портал - и вы сможете сразу откликаться на понравившиеся вакансии.</p>'
            .'<br/>'
            .'<p style=" font-size:16px;">Вы также можете подтвердить регистрацию, скопировав следующую ссылку в адресную строку браузера:<br/>'.$link.'</p>'
            .'<br/>'
            .'<p style="text-align: center; font-size:16px;">Подтверждая регистрацию, вы начинаете пользоваться всеми преимуществами сервиса Prommu.com</p>'
            .'<br/>'
            .'<div class="hh" style="position: relative;  text-align: center;">'
                .'<a href='.$link.' class="reg__registration-btn">ПОДТВЕРДИТЬ РЕГИСТРАЦИЮ</a>'
            .'</div>'
            .'<div style="margin: 20px;" class="text-block">'
                .'<p style="text-align: center">Мы не требуем никаких оплат, отправки смс, не рассылаем спам</p>'
                .'<br/>'
                .'<br/>'
                .'<p style="font-size:16px;"> Ваш логин для входа на портал:'.$inData['inputData']['email']
                .'<br/>Ваш пароль для входа на портал:'.$inData['inputData']['pass'].'</p>'
            .'</div>';
            Share::sendmail($inData['inputData']['email'], "Prommu.com. Подтверждение регистрации на портале поиска временной работы!", $message);
        } elseif( $inData['type'] == 3 && empty($robot)) {
            $link = 'http://' . $_SERVER['HTTP_HOST'] . MainConfig::$PAGE_ACTIVATE . '/?type=3&t=' . $token . "&uid=" . $idUs."&referer=".$referer."&transition=".$transition."&canal=".$canal."&campaign=".$campaign."&content=".$content."&keywords=".$keywords."&point=".$point."&last_referer=".$last_referer."&admin=".$admin."&ip=".$ip."&client=".$client."&pm=".$pm;
            $message = '<p style="font-size:16px;">Наш портал <b>Prommu.com</b> позволяет найти квалифицированный персонал в России и странах СНГ совершенно бесплатно.</p>'
            .'<br/>'
            .'<p style=" margin-bottom: 0px;font-size:16px;">На нашем портале вы сможете разместить вакансии и найти сотрудников по следующим направлениям:</p>'
            .'<br/>'
            .'<div class="hh" style="position: relative;  text-align: center;">'
                .'<ul class="reg__vacancies-list">'
                    .'<li class="reg__vacancies-list-item"><i></i><span>Промоутер</span></li>'
                    .'<li class="reg__vacancies-list-item"><i></i><span>Мерчендайзер</span></li>'
                    .'<li class="reg__vacancies-list-item"><i></i><span>Ростовая кукла</span></li>'
                    .'<li class="reg__vacancies-list-item"><i></i><span>Супервайзер</span></li>'
                    .'<li class="reg__vacancies-list-item"><i></i><span>Тайный покупатель</span></li>'
                .'</ul>'
                .'<ul class="reg__vacancies-list">'
                    .'<li class="reg__vacancies-list-item"><i></i><span>Аниматор</span></li>'
                    .'<li class="reg__vacancies-list-item"><i></i><span>Интервьюер</span></li>'
                    .'<li class="reg__vacancies-list-item"><i></i><span>Консультант</span></li>'
                    .'<li class="reg__vacancies-list-item"><i></i><span>Модель</span></li>'
                    .'<li class="reg__vacancies-list-item"><i></i><span>Хостес</span></li>'
                .'</ul>'
            .'</div>'
            .'<br/>'
            .'<p style="font-size:16px;">Почему мы именно вам предлагаем воспользоваться нашим сервисом? У нас вы сможете подобрать персонал, который полностью отвечает вашим требованиям.<br/>'
            .'<p style="font-size:16px;"> Вам достаточно <a href ='.$link.'><ins>подтвердить регистрацию</ins></a> на нашем портале и воспользоваться логином/паролем для входа на портал - и вы сможете сразу размешать вакансии и просматрировать анкеты соискателей.</p>'
            .'<br/>'
            .'<p style=" font-size:16px;">Вы также можете подтвердить регистрацию, скопировав следующую ссылку в адресную строку браузера:<br/>'.$link.'</p>'        
            .'<br/>'
            .'<p style="text-align: center;font-size:16px;>Подтверждая регистрацию, вы начинаете пользоваться всеми преимуществами сервиса Prommu.com </p>'
            .'<div class="hh" style="position: relative;  text-align: center;">'
                .'<a href='.$link.' class="reg__registration-btn">ПОДТВЕРДИТЬ РЕГИСТРАЦИЮ</a>'
            .'</div>'
            .'<div style="margin: 20px;" class="text-block">'
                .'<p style="text-align: center">Мы не требуем никаких оплат, отправки смс, не рассылаем спам </p>'
                .'<br/>'
                .'<br/>'
                .'<p style="font-size:16px;">Ваш логин для входа на портал:'.$inData['inputData']['email']
                .'<br/>Ваш пароль для входа на портал:'.$inData['inputData']['pass'].'</p>'
            .'</div>';
            Share::sendmail($inData['inputData']['email'], " Prommu.com. Подтверждение регистрации на портале поиска персонала!", $message);
        } // endif
        

        return array('error' => 0);
    }



    private function checkFieldsEmpl()
    {
        $key = 'name';
        $inputData[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if( empty($inputData[$key]) )
        {
            $message = "Ошибки заполнения формы";
            $hint = 'введите название компании';
            $flag_error = 1;
            $element = $key;


        // проверяем на дубликат
        } else {
            // if( (new Employer())->find("name = '{$inputData[$key]}'") )
            // {
            //     $message = "Компания с таким названием уже зарегистрирована в системе";
            //     $hint = 'выберите другое название компании';
            //     $flag_error = 1;
            //     $element = $key;
            // } // endif
        } // endif


        $key = 'email';
        $inputData[$key] = Yii::app()->getRequest()->getParam($key);
        if( !$flag_error && !filter_var($inputData[$key], FILTER_VALIDATE_EMAIL) )
        {
            $message = "Ошибки заполнения формы";
            $hint = 'введите правильный электронный адрес';
            $flag_error = 1;
            $element = $key;

        // проверяем на дубликат
        } else {
            // нет есть в системе и статус = регистрация 1 шаг
            if( (new User())->find("email = '{$inputData[$key]}'") )
            {
                $message = "Такой email уже зарегистрирован в системе";
                $hint = 'введите другой email адрес';
                $flag_error = 1;
                $element = $key;
            } // endif
        } // endif


        $key = 'pass';
        $inputData[$key] = Yii::app()->getRequest()->getParam($key);
        $key2 = 'passrep';
        $inputData[$key2] = Yii::app()->getRequest()->getParam($key2);
        if( !$flag_error )
        {
            $element = $key;
            if( empty($inputData[$key]) )
            {
                $message = "Ошибки заполнения формы";
                $hint = 'введите пароль';
                $flag_error = 1;
            }
            elseif( $inputData[$key] != Yii::app()->getRequest()->getParam($key2) )
            {
                $message = "Ошибки заполнения формы";
                $hint = 'пароль и его повтор не совпадают';
                $flag_error = 1;
            } // endif
        } // endif

        // CAPTCHA
        $model = new Settings;
        $use_recaptcha = boolval($model->getDataByCode('register_captcha'));
        if($use_recaptcha==true)
        {
            $recaptcha = Yii::app()->getRequest()->getParam('g-recaptcha-response');
            if(!empty($recaptcha))
            {
                $google_url="https://www.google.com/recaptcha/api/siteverify";
                $secret='6Lf2oE0UAAAAAPkKWuPxJl0cuH7tOM2OoVW5k6yH';
                $ip=$_SERVER['REMOTE_ADDR'];
                $url=$google_url."?secret=".$secret."&response=".$recaptcha."&remoteip=".$ip;
                //
                $curl = curl_init();
                curl_setopt($curl, CURLOPT_URL, $url);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($curl, CURLOPT_TIMEOUT, 10);
                curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16");
                $res = curl_exec($curl);
                curl_close($curl);
                //
                $res = json_decode($res, true);//reCaptcha введена
                if(!$res['success']) // wrong captcha
                {
                    $message = "Ошибки заполнения формы";
                    $hint = 'Вы допустили ошибку при прохождении проверки "Я не робот"';
                    $flag_error = 1;
                    $element = 'recaptcha';
                }
            }
            else
            {
                $message = "Ошибки заполнения формы";
                $hint = 'Необходимо пройти проверку "Я не робот"';
                $flag_error = 1;
                $element = 'recaptcha';
            }
        }

        return array(
                'message' => $message,
                'hint' => $hint,
                'error' => $flag_error,
                'element' => $flag_error ? $element : '',
                'inputData' => $inputData,
                'use_recaptcha' => $use_recaptcha
            );
    }


  public function registerAuth($data)
  {
    if((new User())->checkLogin($data['email'])) // юзер с таким email же существует
    {
      return false;
    }
    // создание нового юзера
    $model = new UserRegister();
    $arRegister = $model->saveNewUserFromSocialNetwork([
      'login' => $data['email'],
      'name' => $data['fname'],
      'surname' => $data['lname'],
      'password' => "DdUu19221922SuSaNnAa", // загадочная константа пароля для всех юзеров из соцсетей)
      'messenger' => $data['messenger'],
      'gender' => $data['gender'],
      'birthday' => $data['birthday']
    ]);
    return $arRegister['id_user'];
  }

    public function loadLogo($photo)
    {
    
        if($photo)
        {
            $fn = date('YmdHis').rand(100,1000);
            $flag = true;

            $pathinfo = pathinfo($photo);
             $we = "jpg";
            if( !copy($photo, MainConfig::$DOC_ROOT . DS . MainConfig::$PATH_APPLIC_LOGO . DS . $fn . '000.' . $we) ) {
                $flag = false;
                
            }


            if( $flag )
            {   
                $pathinfo['extension'] = "jpg";
                $UploadLogo = new UploadLogo();
                $UploadLogo->imgResizeToRect(MainConfig::$DOC_ROOT . DS . MainConfig::$PATH_APPLIC_LOGO . DS . $fn . '000.jpg', MainConfig::$DOC_ROOT . DS . MainConfig::$PATH_APPLIC_LOGO . DS . $fn . '400.' . $pathinfo['extension'], "image/jpeg", 400 );
                $UploadLogo->imgResizeToRect(MainConfig::$DOC_ROOT . DS . MainConfig::$PATH_APPLIC_LOGO . DS . $fn . '000.' . $pathinfo['extension'], MainConfig::$DOC_ROOT . DS . MainConfig::$PATH_APPLIC_LOGO . DS . $fn . '100.' . $pathinfo['extension'], "image/jpeg", 100 );

            }
            else
            {
            } // endif
        } // endif

        return !$flag ?: $fn;
    }

    public function loadLogoEmpl($photo)
    {
    
        if($photo)
        {
            $fn = date('YmdHis').rand(100,1000);
            $flag = true;

            $pathinfo = pathinfo($photo);
             $we = "jpg";
            if( !copy($photo, MainConfig::$DOC_ROOT . DS . MainConfig::$PATH_EMPL_LOGO . DS . $fn . '000.' . $we) ) {
                $flag = false;
                LogError::write(__CLASS__ . ':' . __METHOD__ . ": copy fail \$photo ({$photo})");
            }


            if( $flag )
            {   
                $pathinfo['extension'] = "jpg";
                $UploadLogo = new UploadLogo();
                $UploadLogo->imgResizeToRect(MainConfig::$DOC_ROOT . DS . MainConfig::$PATH_EMPL_LOGO . DS . $fn . '000.jpg', MainConfig::$DOC_ROOT . DS . MainConfig::$PATH_EMPL_LOGO . DS . $fn . '400.' . $pathinfo['extension'], "image/jpeg", 400 );
                $UploadLogo->imgResizeToRect(MainConfig::$DOC_ROOT . DS . MainConfig::$PATH_EMPL_LOGO . DS . $fn . '000.' . $pathinfo['extension'], MainConfig::$DOC_ROOT . DS . MainConfig::$PATH_EMPL_LOGO . DS . $fn . '100.' . $pathinfo['extension'], "image/jpeg", 100 );

            }
            else
            {
            } // endif
        } // endif

        return !$flag ?: $fn;
    }



    /**
     * Сбрасываем авторизацию
     */
    private function resetAuthData()
    {
        unset(Yii::app()->session['au_us_type']);
        unset(Yii::app()->session['au_uid']);
        unset(Yii::app()->session['au_token']);
        unset(Yii::app()->session['au_exptime']);
        unset(Yii::app()->session['au_us_data']);
    }
}
