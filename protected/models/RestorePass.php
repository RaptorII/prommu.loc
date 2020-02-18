<?php
/**
 * Модель для смены пароля пользователя
 * Date: 01.11.16
 */


class RestorePass
{
    /**
     * Запрос на восстановление пароля
     */
    public function passRestoreRequest($props = [])
    {
      $arRes = ['error' => false, 'message' => ''];
      $login = $props['email']
        ? $props['email']
        : filter_var(
          Yii::app()->getRequest()->getParam('email'),
          FILTER_SANITIZE_FULL_SPECIAL_CHARS
        );

      if(!Yii::app()->getRequest()->isPostRequest)
      {
        return $arRes;
      }

      try
      {
        $model = new User();
        $isPhone = false;
        $isEmail = false;
        if(filter_var($login,FILTER_VALIDATE_EMAIL)) // Email
        {
          $isEmail = true;
        }
        else // Телефон
        {
          $login = preg_replace("/[^0-9]/", '', $login);
          if(strlen($login)==10) // RF
          {
            $login = '7' . $login;
            $isPhone = true;
          }
          elseif (strlen($login)==11 && in_array(substr($login,0,1), ['7','8'])) // RF
          {
            $isPhone = true;
          }
          elseif(
            strlen($login)==13
            &&
            in_array(substr($login,0,3),['380','375'])
          ) // Ukraine | Belarus
          {
            $isPhone = true;
          }
        }
        //
        if(!$isEmail && !$isPhone)
        {
          throw new Exception('', -102); // некорректный ввод
        }
        //
        $user = $model->checkLogin($login,$isPhone);
        if(!$user)
        {
          throw new Exception('', -103); // не найден юзер
        }

        if($user['isblocked']==User::$ISBLOCKED_BLOCKED) // юзер заблокирован
        {
          throw new Exception('', -104);
        }

        // получаем hash для ссылки восстановления
        $token = md5($user['id_user'] . rand(100000, 1000000) . $user['passw'] . time());
        Yii::app()->db->createCommand()
            ->insert(
              'user_activate',
              [
                'id_user' => $user['id_user'],
                'token' => $token,
                'type' => 1,
                'dt_create' => date("Y-m-d H:i:s"),
              ]
            );

        $link = Subdomain::site() . DS . MainConfig::$PAGE_NEW_PASS . '/?t=' . $token;
        if($isPhone)
        {
          Share::sendSMSCode($login, $link);
        }
        else
        {
          $link .= "&uid=" . $user['id_user'];
          // письмо юзеру для воссановления пароля
          Mailing::set(
            6,
            ['email_user'=>$login, 'link_restore_pass'=>$link]
          );
        }

        Yii::app()->user->setFlash(
          'success',
           $isPhone
            ? "На указанный телефон отправлено письмо со ссылкой для восстановления пароля"
            : "На указанный email адрес отправлено письмо со ссылкой для восстановления пароля"
        );
      }
      catch (Exception $e)
      {
        $error = abs($e->getCode());
        switch( $e->getCode() )
        {
          case -102 : $message = 'Необходимо ввести корректный E-mail или Мобильный номер телефона'; break;
          case -103 : $message = 'Таких учетных данных не обнаружено среди зарегистрированных пользователей'; break;
          case -104 : $message = 'Пользователь заблокирован'; break;
          default: $error = 101; $message = 'Ошибка восстановления пароля '.$e->getMessage();
        }

        $arRes = ['error' => $error, 'message' => $message];
      }

      return $arRes;
    }


    public function passRestoreRequestAPI($props = [])
    {
        $error = '-101';
        try
        {
            $email = $props['email'] ?: Yii::app()->getRequest()->getParam('email');
            // if( !$email ) throw new Exception('', -102);

            $sql = "SELECT e.id_user
            FROM user e
            WHERE e.login = '{$email}' AND e.isblocked = ".User::$ISBLOCKED_BLOCKED;
             $res = Yii::app()->db->createCommand($sql)->queryRow();
            if( $res ) throw new Exception('', -104);

            if(strpos($email, "@") === false && !empty($email)){
                
                $sql = "SELECT id_user
                    FROM user
                    WHERE login LIKE '%{$email}%' 
                    ";
                $res = Yii::app()->db->createCommand($sql)->queryRow();
                
                $User->id_user = $res['id_user'];

                $message = "На указанный вами телефон отправлено письмо со ссылкой для восстановления пароля";
            }
            else{
                $User = User::model()->find('login=:email', [':email' => $email]);
                if( !$User ) throw new Exception('', -103); 
                $message = "На указанный вами email адрес отправлено письмо со ссылкой для восстановления пароля";
            }
           
            // получаем hash для ссылки восстановления
            $token = md5($User->id_user . rand(100000, 1000000) . $User->passw . time());

            $res = Yii::app()->db->createCommand()
                ->insert('user_activate', array(
                    'id_user' => $User->id_user,
                    'token' => $token,
                    'type' => 1,
                    'dt_create' => date("Y-m-d H:i:s"),
                ));
           
            $link = Subdomain::site() . DS . MainConfig::$PAGE_NEW_PASS . '/?t=' . $token;
            if(strripos($email, "@"))
            {
                $link .= "&uid=" . $User->id_user;
                Mailing::set(
                            6,
                            array(
                                'email_user' => $email,
                                'link_restore_pass' => $link
                            )
                        );
            }
            else
            {
                file_get_contents("https://prommu.com/api.teles/?phone=$email&code=$link");
            }
            
            Yii::app()->user->setFlash('Result', ['error' => 1, 'message' => $message]);
            $data = ['error' => 1, 'message' => ""];


        } catch (Exception $e)
        {
            $error = abs($e->getCode());
            switch( $e->getCode() )
            {
                case -102 : $message = 'Введите корректный email адрес'; break; // invalid email
                case -103 : $message = 'Таких учетных данных не обнаружено среди зарегистрированных пользователей'; break; // token expired
                case -104 : $message = 'Пользователь не найден - либо вы не подтвердили свой email'; break; // token expired
                default: $error = 101; $message = 'Ошибка восстановления пароля '.$e->getMessage();
            }

            $data = ['error' => $error, 'message' => $message];
        } // endtry

        return $data;
    }



    /**
     * Поверяем токен смены пароля
     */
    public function newPassTokenCheck()
    {
        $error = '-101';
        try
        {
            $token = filter_var(Yii::app()->getRequest()->getParam('t', ''), FILTER_SANITIZE_STRING);
            $sql = "SELECT e.id_user
            FROM user_activate e
            WHERE e.token = '{$token}'";
            $res = Yii::app()->db->createCommand($sql)->queryRow();

            $idus = $res['id_user'];
            if( empty($token) || $idus == 0 ) throw new Exception('', -102);

            // ищем пользователя в БД
            $UAct = UserActivate::model()->getById($idus)->lastByDate()->findAll();
            if( !$UAct || $UAct[0]->token != $token ) throw new Exception('', -103);


//            Yii::app()->user->setFlash('Result', ['error' => 1, 'message' => "На указанный вами email адрес отправлено письмо со ссылкой для восстановления пароля"]);
            $data = ['error' => 1, 'token' => $token, 'idus' => $idus];


        } catch (Exception $e)
        {
            $error = abs($e->getCode());
            switch( $e->getCode() )
            {
                case -102 : $message = 'Некорректные параметры'; break; // Некорректные параметры
                case -103 : $message = 'Неправильная ссылка восстановления пароля'; break; // token expired
                default: $error = 101; $message = 'Ошибка восстановления пароля';
            }

            $data = ['error' => $error, 'message' => $message, 'noform' => 1];
        } // endtry

        return $data;
    }



    /**
     * Меняем пароль
     */
    public function changePass()
    {
        $error = '-101';
        try
        {
            $token = filter_var(Yii::app()->getRequest()->getParam('t', ''), FILTER_SANITIZE_STRING);
            $sql = "SELECT e.id_user
            FROM user_activate e
            WHERE e.token = '{$token}'";
            $res = Yii::app()->db->createCommand($sql)->queryRow();

            $idus = $res['id_user'];
            $pass = filter_var(Yii::app()->getRequest()->getParam('pass', ''), FILTER_SANITIZE_STRING);
            $passrep = filter_var(Yii::app()->getRequest()->getParam('passrep', ''), FILTER_SANITIZE_STRING);
            if( empty($token) || $idus == 0 || empty($pass) ) throw new Exception('', -102);

            // ищем пользователя в БД
            $UAct = UserActivate::model()->getById($idus)->lastByDate()->findAll();
            if( !$UAct || $UAct[0]->token != $token ) throw new Exception('', -103);

            if( $pass == $passrep )
            {
                $User = User::model()->findByPk($idus);
                if( !$User ) throw new Exception('', -105);
                if( $User->passw == md5($pass) ) throw new Exception('', -106);

                $res = User::model()->updateByPk($idus, ['passw' => md5($pass)]);

                 // $salt = '$2a$10$'.substr(str_replace('+', '.', base64_encode(pack('N4', mt_rand(), mt_rand(), mt_rand(),mt_rand()))), 0, 22) . '$';
                 // $token = crypt( $pass, $salt);

                 

                // $res = Yii::app()->db->createCommand()
                // ->update('user_api', array(
                //     'password' => $token,
                
                // ), 'id=:id', array(':id' => $idus));



                if( $res )
                {
//                    UserActivate::model()->updateAll(['token' => rand(100000, 1000000) . time()], 'id_user = :idus AND token = :token',
//                            [':idus' => $idus, ':token' => $token]);
                    $res = Yii::app()->db->createCommand()->delete('user_activate', 'id_user = :idus AND token = :token',
                            [':idus' => $idus, ':token' => $token]);



                    $data = ['error' => 1];
                    Yii::app()->user->setFlash(
                      'success',
                      'Пароль успешно изменён теперь можете авторизироваться используя новый пароль, перейти на страницу <a href="' . MainConfig::$PAGE_LOGIN . '">авторизации</a>'
                    );
                }
                else
                {
                    throw new Exception('', -105);
                } // endif
            }
            else
            {
                throw new Exception('', -104);
            } // endif




        } catch (Exception $e)
        {
            $error = abs($e->getCode());
            switch( $e->getCode() )
            {
                case -102 : $message = 'Некорректные параметры'; break; // Некорректные параметры
                case -103 : $message = 'Ошибка определения пользователя'; break; // token expired
                case -104 : $message = 'Пароль и его повтор не совпадают'; break; // token expired
                case -105 : $message = 'Ошибка смены пароля'; break; // token expired
                case -106 : $message = 'Такой пароль уже был задан ранее, укажите другой пароль'; break; // token expired
                default: $error = 101; $message = 'Ошибка восстановления пароля';
            }

            $data = ['error' => $error, 'message' => $message, 'token' => $token, 'idus' => $idus];
        } // endtry

        return $data;
    }
}