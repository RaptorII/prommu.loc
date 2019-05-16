<?php

/**
 * Date: 11.02.16
 * Time: 22:23
 */
class Auth
{
    /**
     * Активация пользователя
     */
    public function activateUser()
    {
        $idUs = Yii::app()->getRequest()->getParam('uid');
        $network = Yii::app()->getRequest()->getParam('network');

        ///ANALITYCS DATA
        $referer = filter_var(Yii::app()->getRequest()->getParam('referer'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $transition = filter_var(Yii::app()->getRequest()->getParam('transition'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $admin  = filter_var(Yii::app()->getRequest()->getParam('admin'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $canal = filter_var(Yii::app()->getRequest()->getParam('canal'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $sex = filter_var(Yii::app()->getRequest()->getParam('sex'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $smart = filter_var(Yii::app()->getRequest()->getParam('smart'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $campaign = filter_var(Yii::app()->getRequest()->getParam('campaign'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $content = filter_var(Yii::app()->getRequest()->getParam('content'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $keywords = Yii::app()->getRequest()->getParam('keywords');
        $point = filter_var(Yii::app()->getRequest()->getParam('point'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $last_referer = filter_var(Yii::app()->getRequest()->getParam('last_referer'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        ///ANALITYCS DATA 
        $type = filter_var(Yii::app()->getRequest()->getParam('type'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $birthday = filter_var(Yii::app()->getRequest()->getParam('birthday'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $photos = filter_var(Yii::app()->getRequest()->getParam('photos'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $ip =  Yii::app()->getRequest()->getParam('ip');
        $client = Yii::app()->request->cookies['_ga'];
        $client = substr($client, 6, 100);
        $client = Yii::app()->session['client'];
         $pm = Yii::app()->getRequest()->getParam('pm');
        if($pm == '') $pm = 'none';
        
        $ips  = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote  = @$_SERVER['REMOTE_ADDR'];
        
        if(filter_var($ips, FILTER_VALIDATE_IP)) $ip = $ips;
        elseif(filter_var($forward, FILTER_VALIDATE_IP)) $ip = $forward;
        else $ip = $remote;
    
        ///DOUBLE NULL
        $transition = explode(",", $transition);
        $transition = $transition[0];

        $content = explode(",", $content);
        $content = $content[0];

        $campaign = explode(",", $campaign);
        $campaign = $campaign[0];

        $canal = explode(",", $canal);
        $canal = $canal[0];
        
        $usu = Yii::app()->db->createCommand()
                ->select("a.keywords, a.point, a.last_referer")
                ->from('analytic a')
                ->where('id_us = :t', array(':t' => $idUs))
                ->queryRow();
        
        $keywords = $usu['keywords'];
        $keywords = $this->encoderSys($keywords);

         $point = $usu['point'];
         $last_referer = $usu['last_referer'];

         


        
        if ( $token = Yii::app()->getRequest()->getParam('t') ) 
        {
            $usData = Yii::app()->db->createCommand()
                ->select("u.id_user, u.status, a.token, a.data")
                ->from('user_activate a')
                ->join('user u', 'u.id_user = a.id_user')
                ->where('a.token = :t', array(':t' => $token))
                ->queryRow();


            $id = $usData['id_user'];
            $idUs = $usData['id_user'];

            if($usData['status'] == 3){
                $usDataTest = Yii::app()->db->createCommand()
                ->select("u.id_user")
                ->from('employer u')
                ->where('u.id_user = :t', array(':t' => $idUs))
                ->queryRow();
            } elseif($usData['status'] == 2){
                $usDataTest = Yii::app()->db->createCommand()
                ->select("u.id_user")
                ->from('resume u')
                ->where('u.id_user = :t', array(':t' => $idUs))
                ->queryRow();
            }

            if ($id > 0) {
              
                if ($usData['status'] == 3 && empty($usDataTest['id_user']))
                {   

                    $data = json_decode($usData['data']);
                    $res = Yii::app()->db->createCommand()
                        ->insert('employer', array('id_user' => $usData['id_user'],
                                'name' => ucfirst($data->name),
                                'firstname' => $data->fname, 
                                'lastname' => $data->lname,
                                'logo' => $data->photos,
                                'crdate' => date('Y-m-d H:i:s'),
                                'admin' => $admin,
                                'type' => 102 // устанавливаем по умолчанию "Прямой работодатель"
                            ));

                    $res = Yii::app()->db->createCommand()
                        ->insert('user_city', array('id_user' => $usData['id_user'],
                                'id_city' => 1307,
                            ));

                    $analytData = array('id_us' => $usData['id_user'],
                        'name' => ucfirst($data->lname),
                        'type' => 3,
                        'date' =>  date('Y-m-d H:i:s'),
                        'referer' => $referer,
                        'canal' => $canal,
                        'campaign' => $campaign,
                        'content' => $content, 
                        'keywords' => $keywords,
                        'point' => $point, 
                        'transition' => $transition,
                        'last_referer' => $last_referer,
                        'admin' => $admin,
                        'subdomen' => 0,
                        'client' => $client, 
                        'ip' => $ip, 
                        'source' => $pm, 
                    );

                     $res = Yii::app()->db->createCommand()
                        ->insert('analytic', $analytData);

                   
                        $pid = Yii::app()->db->createCommand("SELECT u.id  FROM  employer u WHERE u.id = (SELECT MAX(u.id)  FROM employer u)")->queryScalar();
                    $pid+1;

                     if($photos)
                    {
                        $res = Yii::app()->db->createCommand()
                            ->insert('user_photos', array('id_promo' => '0',
                                'id_user' => $usData['id_user'],
                                'photo' => $photos,
                                'npp' => 1,
                                'id_empl' => $pid,
                            ));
                    }
                    else
                    {
                    } // endif


                // соискатель
                } elseif ($usData['status'] == 2 && empty($usDataTest['id_user']))
                {   
                    
                    
                    $data = json_decode($usData['data']);
                    

                    $pid = Yii::app()->db->createCommand("SELECT u.id  FROM  resume u WHERE u.id = (SELECT MAX(u.id)  FROM resume u)")->queryScalar();
                    $pid+1;


                  //  $sex = $this->SexOnder(ucfirst($data->fname ? $data->fname : $data->name ));
                    $insData = array('id_user' => $usData['id_user'],
                        'firstname' => ucfirst($data->fname ? $data->fname : $data->name ),
                        'lastname' =>  ucfirst($data->lname),
                        'isman' => $sex,
                        'smart' => $smart,
                        'date_public' => date('Y-m-d H:i:s'),
                        'mdate' => date('Y-m-d H:i:s'),
                        'admin' => $admin,
                        'birthday' => date("Y-m-d", strtotime($data->birthday)),
                    );

                    
                    if($sex = 1) {
                        $male = 'MALE';
                    }
                    else $male = 'FEMALE';
                                     

                    if($data->photos) {
                         $insData = array('id_user' => $usData['id_user'],
                        'firstname' => ucfirst($data->fname ? $data->fname : $data->name ),
                        'lastname' =>  ucfirst($data->lname),
                        'isman' => $sex,
                        'smart' => $smart,
                        'date_public' => date('Y-m-d H:i:s'),
                        'mdate' => date('Y-m-d H:i:s'),
                        'admin' => $admin,
                        'birthday' => date("Y-m-d", strtotime($data->birthday)),
                        'photo' => $data->photos
                    );
                    }

                    $res = Yii::app()->db->createCommand()
                        ->insert('user_mech', array('id_us' => $usData['id_user'],
                                'crdate' => date('Y-m-d H:i:s'),
                                'id_mech' => 111,
                                'isshow' => 0, 
                                ));

                    $analytData = array('id_us' => $usData['id_user'],
                        'name' => ucfirst($data->name),
                        'date' =>  date('Y-m-d H:i:s'),
                        'type' => 2,
                        'referer' => $referer,
                        'canal' => $canal,
                        'campaign' => $campaign,
                        'content' => $content, 
                        'keywords' => $keywords,
                        'point' => $point, 
                        'transition' => $transition,
                        'last_referer' => $last_referer,
                        'admin' => $admin,
                        'subdomen' => 0,
                        'client' => $client,
                        'ip' => $ip, 
                        'source' => $pm
                    );

                    $res = Yii::app()->db->createCommand()
                        ->insert('user_city', array('id_user' => $usData['id_user'],
                            'id_resume' => $pid,
                                'id_city' => 1307,
                            ));

                     $res = Yii::app()->db->createCommand()
                        ->insert('analytic', $analytData);

                     
                    // дата рождения
                    if( $data->bdate ) {
                        $data->bdate = date("Y-m-d", strtotime($data->bdate));
                        $insData['birthday'] = $data->bdate;
                    }
                    else
                    {
                        $insData['birthday'] = '1970-01-01'; // 18 years 
                    }

                    // загрузка фото ВК
                    if( $data->photo_200 && ($fid = $this->loadVKLogo($data)) )
                    {
                        $insData['photo'] = $fid;

                    // загрузка фото ВК
                    } elseif( $data->fb && ($fid = $this->loadFBLogo($data)) )
                    {
                        $insData['photo'] = $fid;
                    } // endif

                    // $insData['isman'] = $data->sex;

                    $res = Yii::app()->db->createCommand()
                        ->insert('resume', $insData);


                    // если есть фото
                    if( $fid )
                    {
                        $pid = Yii::app()->db->createCommand('SELECT LAST_INSERT_ID()')->queryScalar();
                        $res = Yii::app()->db->createCommand()
                            ->insert('user_photos', array('id_promo' => $pid,
                                'id_user' => $usData['id_user'],
                                'photo' => $fid,
                            ));
                    }
                    else
                    {
                    } // endif


                }

                $this->userUpdate(array('isblocked' => 3), 'id_user='.$usData['id_user']);

                

                $this->AuthorizeNet(['id' => $usData['id_user']]);

                $link = Subdomain::site() . "/user/activate/?type=$type&uid=".$usData['id_user']."&sex=".$sex."&smart=".$smart."&birthday=".$birthday."&photos=".$photos."&referer=".$referer."&keywords=".$keywords."&transition=".$transition."&canal=".$canal."&campaign=".$campaign."&content=".$content."&point=".$point."&last_referer=".$last_referer;
                $usData = Yii::app()->db->createCommand()
                ->select("u.email, u.status")
                ->from('user u')
                ->where('u.id_user = :id', array(':id' => $usData['id_user']))
                ->queryRow();

                $emails = $usData['email'];
                $emails = filter_var($emails,FILTER_VALIDATE_EMAIL);

                if( $usData['status'] == 2 && empty($usDataTest['id_user']))
                {   
                    $types = "Соискатель";
                    $message = "<tr><td>Ваш пользователь успешно активирован. Нажмите на кнопку ниже, чтобы перейти к форме заполнения данных. После того, как вы заполните все необходимые данные о себе - ваш профиль будет виден в общем списке соискателей и поиске на сайте, а также вы сможете откликаться на понравившиеся вакансии.";
                    $nam = $data->name ? $data->name : $data->fname;
                    $names = "$nam ".$data->lname;

                    if(!empty($emails)) // отправка сообщения клиенту
                        Share::sendmail($emails, "Prommu: активация прошла успешно", $message);

                    $messages = sprintf("На сайте <a href='https://%s'>https://%1$01s</a> зарегистрирован новый пользователь (почтовый ящик подтвержден)
                        <br/>
                        <br/>
                        Пользователь: <b>%s</b>, 
                        Тип:<b>%s</b>,
                        Имя:<b>%s</b>, Email: <b>%s</b>,
                        <br/>
                        ----------------------------------------------------------
                        <br/>
                        Тип трафика:<b>%s</b> 
                        <br/>
                        Источник: <b>%s</b> 
                        <br/>
                        Канал: <b>%s</b>  
                        <br/>
                        Кампания: <b>%s</b>  
                        <br/>
                        Контент: <b>%s</b>  
                        <br/>
                        Ключевые слова: <b>%s</b> 
                        <br/>
                        Точка входа: <b>%s</b> 
                        <br/>
                        Реферер: <b>%s</b>
                        <br/>
                        IP: <b>%s</b>
                         <br/>
                        GA: <b>%s</b>
                         <br/>
                        Площадка: <b>%s</b>",
                        Subdomain::getSiteName(), $idUs,$types,$names, $emails, $referer, $transition, $canal, $campaign, $content, $keywords, $point, $last_referer, $ip, $client, $pm);
                    $email[0] = "denisgresk@gmail.com";
                    $email[1] = "admin.prommu@prommu.ru";
                    /*
                    $email[1] = "man.market2@gmail.com";
                    $email[2] = "susgresk@gmail.com";
                    $email[3] = "e.market.easss@gmail.com"; 
                    $email[4] = "code@code.code";
                    $email[5] = "manag_reports@euro-asian.ru";
                    $email[6] = "e.marketing@euro-asian.ru";
                    */
                    for($i = 0; $i <2; $i++)
                    {
                        Share::sendmail($email[$i], "Prommu: зарегистрирован новый пользователь", trim($messages));
                    }
                    /*
                    $message = sprintf("На сайте <a href='https://%s'>https://%1$01s</a> зарегистрирован новый пользователь (почтовый ящик подтвержден) Требуется модерация администратора сайта!
                        <br/>
                        <br/>
                        Пользователь: <b>%s</b>,
                        Тип:<b>%s</b>,
                        Имя:<b>%s</b>,
                        <br/>
                        ----------------------------------------------------------
                        <br/>
                        Тип трафика:<b>%s</b> 
                        <br/>
                        Источник: <b>%s</b> 
                        <br/>
                        Канал: <b>%s</b>  
                        <br/>
                        Кампания: <b>%s</b>  
                        <br/>
                        Контент: <b>%s</b>  
                        <br/>
                        Ключевые слова: <b>%s</b> 
                        <br/>
                        Точка входа: <b>%s</b> 
                        <br/>
                        Реферер: <b>%s</b> 
                        <br/>
                        IP: <b>%s</b>
                        <br/>
                        GA: <b>%s</b>
                        <br/>
                        Площадка: <b>%s</b>",
                        Subdomain::getSiteName(), $usData['id_user'],$types,$names, $referer, $transition, $canal, $campaign, $content, $keywords, $point, $last_referer, $ip, $client, $pm);
                    Share::sendmail("prommu.servis@gmail.com", "Prommu: зарегистрирован новый пользователь", trim($messages));
                    */
                }
                elseif($usData['status'] == 3 && empty($usDataTest['id_user']))
                {   
                    $types ="Работодатель";
                    $message = "<tr><td>Ваш пользователь успешно активирован. Нажмите на кнопку ниже, чтобы перейти к форме заполнения данных. После того, как вы заполните все необходимые данные о себе - ваш профиль будет виден в общем списке работодателей и поиске на сайте, а также вы сможете размещать вакансии.";
                    $names = $data->name;

                    if(!empty($emails)) // отправка сообщения клиенту
                        Share::sendmail($emails, "Prommu: активация прошла успешно", $message);

                    $messages = sprintf("На сайте <a href='https://%s'>https://%1$01s</a> зарегистрирован новый пользователь (почтовый ящик подтвержден)
                        <br/>
                        <br/>
                        Пользователь: <b>%s</b>, 
                        Тип:<b>%s</b>,
                        Имя:<b>%s</b>, Email: <b>%s</b>,
                        <br/>
                        ----------------------------------------------------------
                        <br/>
                        Тип трафика:<b>%s</b> 
                        <br/>
                        Источник: <b>%s</b> 
                        <br/>
                        Канал: <b>%s</b>  
                        <br/>
                        Кампания: <b>%s</b>  
                        <br/>
                        Контент: <b>%s</b>  
                        <br/>
                        Ключевые слова: <b>%s</b> 
                        <br/>
                        Точка входа: <b>%s</b> 
                        <br/>
                        Реферер: <b>%s</b>
                        <br/>
                        IP: <b>%s</b>
                        <br/>
                        GA: <b>%s</b>
                        <br/>
                        Площадка: <b>%s</b>",
                        Subdomain::getSiteName(), $idUs,$types,$names, $emails, $referer, $transition, $canal, $campaign, $content, $keywords, $point, $last_referer, $ip, $client, $pm);

                    $email[0] = "denisgresk@gmail.com";
                    $email[1] = "admin.prommu@prommu.ru";
                    /*
                    $email[1] = "man.market2@gmail.com";
                    $email[2] = "susgresk@gmail.com";
                    $email[3] = "e.market.easss@gmail.com"; 
                    $email[4] = "code@code.code";
                    $email[5] = "manag_reports@euro-asian.ru";
                    $email[6] = "e.marketing@euro-asian.ru";
                    */
                    for($i = 0; $i <2; $i++)
                    {
                        Share::sendmail($email[$i], "Prommu: зарегистрирован новый пользователь", trim($messages));
                    }
                    /*
                    $message = sprintf("На сайте <a href='https://%s'>https://%1$01s</a> зарегистрирован новый пользователь (почтовый ящик подтвержден) Требуется модерация администратора сайта!
                        <br/>
                        <br/>
                        Пользователь: <b>%s</b>,
                        Тип:<b>%s</b>,
                        Имя:<b>%s</b>,
                        <br/>
                        ----------------------------------------------------------
                        <br/>
                        Тип трафика:<b>%s</b> 
                        <br/>
                        Источник: <b>%s</b> 
                        <br/>
                        Канал: <b>%s</b>  
                        <br/>
                        Кампания: <b>%s</b>  
                        <br/>
                        Контент: <b>%s</b>  
                        <br/>
                        Ключевые слова: <b>%s</b> 
                        <br/>
                        Точка входа: <b>%s</b> 
                        <br/>
                        Реферер: <b>%s</b>
                        <br/>
                        IP: <b>%s</b>
                        <br/>
                        GA: <b>%s</b>
                        <br/>
                        Площадка: <b>%s</b>",
                        Subdomain::getSiteName(), $usData['id_user'],$types,$names, $referer, $transition, $canal, $campaign, $content, $keywords, $point, $last_referer, $ip, $client,$pm);
                    Share::sendmail("prommu.servis@gmail.com", "Prommu: зарегистрирован новый пользователь", trim($messages));
                    */
                } // endif

                 
                return $link;

            } else {
                $flagError = 2;
                return $link;
            }


        } else {
            $flagError = 1;
            return $link;
        }


        if( $flagError > 0 )
        {
            return $link;
        }
        else
        {
            return $link;
        } // endif
    }

    public function SexOnder($lastname){
       
        $sql = "SELECT  r.firstname
                    FROM resume r
                    WHERE r.isman = 1";
                $result = Yii::app()->db->createCommand($sql)->queryAll();
                $sex= 0;
                    $count = count($result);
                   for($i = 0; $i < $count; $i++){
                        
                        if(strpos($result[$i]['firstname'], $lastname) !== false) {
                            $sex++;
               
                        }
                        
                    }
                if($sex > 0){
                    $sex = 1;
                } else $sex = 0;
                        return $sex;
    }

    public function encoderSys($eco){
        
        $eco = urldecode($eco);
        $eco = $this->json_fix_cyr($eco);
        return $eco;

    }

    function json_fix_cyr($json_str) {
         $cyr_chars = array (
        '\u0430' => 'а', '\u0410' => 'А',
        '\u0431' => 'б', '\u0411' => 'Б',
        '\u0432' => 'в', '\u0412' => 'В',
        '\u0433' => 'г', '\u0413' => 'Г',
        '\u0434' => 'д', '\u0414' => 'Д',
        '\u0435' => 'е', '\u0415' => 'Е',
        '\u0451' => 'ё', '\u0401' => 'Ё',
        '\u0436' => 'ж', '\u0416' => 'Ж',
        '\u0437' => 'з', '\u0417' => 'З',
        '\u0438' => 'и', '\u0418' => 'И',
        '\u0439' => 'й', '\u0419' => 'Й',
        '\u043a' => 'к', '\u041a' => 'К',
        '\u043b' => 'л', '\u041b' => 'Л',
        '\u043c' => 'м', '\u041c' => 'М',
        '\u043d' => 'н', '\u041d' => 'Н',
        '\u043e' => 'о', '\u041e' => 'О',
        '\u043f' => 'п', '\u041f' => 'П',
        '\u0440' => 'р', '\u0420' => 'Р',
        '\u0441' => 'с', '\u0421' => 'С',
        '\u0442' => 'т', '\u0422' => 'Т',
        '\u0443' => 'у', '\u0423' => 'У',
        '\u0444' => 'ф', '\u0424' => 'Ф',
        '\u0445' => 'х', '\u0425' => 'Х',
        '\u0446' => 'ц', '\u0426' => 'Ц',
        '\u0447' => 'ч', '\u0427' => 'Ч',
        '\u0448' => 'ш', '\u0428' => 'Ш',
        '\u0449' => 'щ', '\u0429' => 'Щ',
        '\u044a' => 'ъ', '\u042a' => 'Ъ',
        '\u044b' => 'ы', '\u042b' => 'Ы',
        '\u044c' => 'ь', '\u042c' => 'Ь',
        '\u044d' => 'э', '\u042d' => 'Э',
        '\u044e' => 'ю', '\u042e' => 'Ю',
        '\u044f' => 'я', '\u042f' => 'Я',
 
        '\r' => '',
        '\n' => '<br />',
        '\t' => ''
    );
 
        foreach ($cyr_chars as $cyr_char_key => $cyr_char) {
            $json_str = str_replace($cyr_char_key, $cyr_char, $json_str);
        }
        return $json_str;
    }

    public function registerUser($inParam)
    {
        
        
        // регистрация соискателя
        if( in_array($inParam, ['1', 'vk', 'fb']) || $inParam['type'] == 2 )
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
        // *** Авторизация через форму ***
        $rType = (new CHttpRequest())->requestType;
        if( $rType == 'POST'
                && Yii::app()->getRequest()->getParam('login')
                && Yii::app()->getRequest()->getParam('passw')
        )
        {
            $res = $this->doLogin(['login' => $inParam->login, 'passw' => $inParam->passw, 'remember' => $inParam->remember]);
//echo "POST:";print_r($res);

        // *** Авторизация через cookie ***
        } else {
            $res = $this->doAuthenicate();
        } // endif

        switch ($res['error'])
        {
            // Создаём профиль
            case 100 : Share::$UserProfile = $Profile = (new ProfileFactory())->makeProfile(array('id' => Yii::app()->session['au_us_data']->id, 'type' => Yii::app()->session['au_us_type']));
                $Profile instanceof UserProfile && $Profile->setUserData();
                break;

            // guest
            default: Share::$UserProfile = $Profile = (new ProfileFactory())->makeProfile(array('id' => 0));
        }


        if( $res['error'] < 0 ) return array('auth' => 0, 'message' => $res['message'], 'error' => 1);
        else return array('auth' => 1,);
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
                $data = $this->doLogin(['login' => $login, 'passmd5' => $pass, 'passw' => $pass, 'remember' => 1]);
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

        if( $error < 0 ) return array('error' => abs($error), 'message' => $message);
        else return array('access_token' => $data['data']['token'], 'id' => $data['data']['idus'], 'type' => $data['data']['type'],'status' => $data['data']['status'], 'exp_date' => strtotime('+1 day'));
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
                // elseif( (int)$user->isblocked === 2 ) throw new Exception('', -105);
//                elseif( (int)$user->isblocked === 3 ) throw new Exception('', -106);
                // elseif( !in_array((int)$user->isblocked, [0,3]) ) throw new Exception('', -103);
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
            "status" => $usData->isblocked,
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
                elseif( (int)$user->isblocked === 2 ) throw new Exception('', -105);
//                elseif( (int)$user->isblocked === 3 ) throw new Exception('', -106);
                elseif( !in_array((int)$user->isblocked, [0,3]) ) throw new Exception('', -103);
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
               case -105: $message = "Ваш пользователь ожидает активации через почту, перейдите по ссылке в письме на почтовом ящике, который вы указали при регистрации.<br>Если письмо долго не приходит - проверьте папку спам, так как почтовый сервер может быть через чур бдительным."; break;
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
        $term = 'email';
        $inputData[$key] = Yii::app()->getRequest()->getParam($key);
        
        if(empty($inputData[$key])){
            $key = 'phone';
            $term = 'login';
            $inputData[$key] = Yii::app()->getRequest()->getParam($key);
        }
        
        if( !$flag_error && !filter_var($inputData['email'], FILTER_VALIDATE_EMAIL) && empty($inputData['phone']))
        {
            $message = "Ошибки заполнения формы";
            $hint = 'введите правильный электронный адрес или номер телефона';
            $flag_error = 1;
            $element = $key;

        // проверяем на дубликат
        } elseif(!$flag_error && empty($inputData['phone']) && empty($inputData['email'])) {
            
            $message = "Ошибки заполнения формы";
            $hint = 'введите правильный номер телефона';
            $flag_error = 1;
            $element = 'phone';
            
        } else {
            
            if( (new User())->find("$term = '{$inputData[$key]}'") )
            {
                $message = "Такой пользователь уже зарегистрирован в системе";
                $hint = 'введите другие данные';
                $flag_error = 1;
                $element = $key;
            } 
        } 
       
        
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

        // CAPTCHA
        $model = new Settings;
        $use_recaptcha = $model->getData()->register_captcha;
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
        if( $inData['isblocked'] ) $data['isblocked'] = $inData['isblocked'];
        $data['access_time'] = $inData['access_time'] ? $inData['access_time'] : date('Y-m-d H:i:s');
        $data['crdate'] = date('Y-m-d H:i:s');
        $data['mdate'] = date('Y-m-d H:i:s');
        $data['ismoder'] = '0';
        !empty($inData['agreement']) && $data['agreement'] = $inData['agreement'];
        

        $res = Yii::app()->db->createCommand()
            ->insert('user', $data);

        if( $isRetId )
            return Yii::app()->db->createCommand('SELECT LAST_INSERT_ID()')
                ->queryScalar();
    }



    public function userUpdate($inData, $inWhere)
    {
        if( $inData['login'] ) $data['login'] = $inData['login'];
        if( $inData['passw'] ) $data['passw'] = md5($inData['passw']);
        if( $inData['email'] ) $data['email'] = ($inData['email']);
        if( $inData['status'] ) $data['status'] = ($inData['status']);
        if( $inData['isblocked'] ) $data['isblocked'] = $inData['isblocked'];
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
        $ip = Yii::app()->getRequest()->getParam('ip');
        $robot = Yii::app()->getRequest()->getParam('lastname');
        $pm = Yii::app()->getRequest()->getParam('pm_source');
        if($pm == '') $pm = 'none';
        $client = Yii::app()->getRequest()->getParam('client');
        $client = substr($client, 6, 100);

        $agreement = filter_var(Yii::app()->getRequest()->getParam('agreement'), FILTER_SANITIZE_NUMBER_INT);

        if( $idUs && $res['isblocked'] != 2 )
        {
            return array('error' => 1, 'message' => 'Пользователь с таким email адресом уже есть', 'inputData' => $inData['inputData']);
        } // endif

        // пользователь уже есть
        if($idUs)
        {
            $this->userUpdate(array('email' => $inData['inputData']['email'],
                'passw' => $inData['inputData']['pass'],
                'isblocked' => 2,
                'ismoder' => 0,
                'status' => $inData['type'],
             
            ), 'id_user = ' . $res['id_user']);
            $idUser = $idUs;



        } else {
             if(!empty($inData['inputData']['phone'])) $inData['inputData']['email'] = $inData['inputData']['phone'];
            $idUs = $this->userInsert(array('email' => $inData['inputData']['email'],
                'passw' => $inData['inputData']['pass'],
                'login' => $inData['inputData']['email'],
                'isblocked' => 2,
                'ismoder' => 1,
                'status' => $inData['type'],
                'agreement' => $agreement
            ), 1);

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
        // if($smart) {
        //  // $sex = $this->SexOnder($inData['inputData']['name']); 
        // }


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
                        'client' => $client ? $client : " ",
                        'ip' => $ip ? $ip : " ", 
                        'source' => $pm ? $pm : " ", 
                    );

        $res = Yii::app()->db->createCommand()
                        ->insert('analytic', $analytData);
                        
        if($inData['type'] == 2) {
            
            $res = Yii::app()->db->createCommand()
                        ->insert('resume', array('id_user' => $idUs,
                                'firstname' => $inData['inputData']['name'],
                                'lastname' => $inData['inputData']['lname'],
                                'date_public' => date('Y-m-d H:i:s'),
                                'mdate' => date('Y-m-d H:i:s'),
                            ));
                            
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
        } elseif($inData['type'] == 3 && empty($robot)){
            
            $res = Yii::app()->db->createCommand()
                        ->insert('employer', array('id_user' => $idUs,
                                'name' => $inData['inputData']['name'],
                                'crdate' => date('Y-m-d H:i:s'),
                                'type' => 102 // устанавливаем по умолчанию "Прямой работодатель"
                            ));


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
        $term = 'email';
        $inputData[$key] = Yii::app()->getRequest()->getParam($key);
        
        if(empty($inputData[$key])){
            $key = 'phone';
            $term = 'login';
            $inputData[$key] = Yii::app()->getRequest()->getParam($key);
        }
        
        
        if( !$flag_error && !filter_var($inputData['email'], FILTER_VALIDATE_EMAIL) && empty($inputData['phone']))
        {
            $message = "Ошибки заполнения формы";
            $hint = 'введите правильный электронный адрес или номер телефона';
            $flag_error = 1;
            $element = $key;

        // проверяем на дубликат
        } elseif(!$flag_error && empty($inputData['phone']) && empty($inputData['email'])) {
            
            $message = "Ошибки заполнения формы";
            $hint = 'введите правильный номер телефона';
            $flag_error = 1;
            $element = 'phone';
            
        } else {
            // нет есть в системе и статус = регистрация 1 шаг
            if( (new User())->find("$term = '{$inputData[$key]}'") )
            {
                $message = "Такой пользователь уже зарегистрирован в системе";
                $hint = 'введите другие данные';
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
        $use_recaptcha = $model->getData()->register_captcha;
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


     public function registerAuth($data){

        ///ANALITYCS DATA
        $admin = filter_var(Yii::app()->getRequest()->getParam('admin'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $referer = $data['referer'];
        $transition = $data['transition'];
        $canal = $data['canal'];
        $campaign = $data['campaign'];
        $content = $data['content'];
        $keywords = urldecode($data['keywords']);
        $point = $data['point'];
        $last_referer = $data['last_referer'];

        ///ANALITYCS DATA

        $email = $data['email']; 
        $pass = "DdUu19221922SuSaNnAa";
         if(empty($data['birthday'])) {
            $birthday = 'none';
        }
        else $birthday = 'type';

        if( (new User())->find("email = '{$email}'") )
            {
                $result = Yii::app()->db->createCommand()
                ->select('id_user')
                ->from('user')
                ->where('email=:email', array(':email'=>$email))
                ->queryAll();
            $id = $result[0]['id_user'];



             $link  = 'http://' . $_SERVER['HTTP_HOST'] . MainConfig::$PAGE_ACTIVATE . "/?&uid=" .$id."&birthday=".$birthday."&smart=1&referer=".$referer."&keywords=".$keywords."&transition=".$transition."&canal=".$canal."&campaign=".$campaign."&content=".$content."&point=".$point."&last_referer=".$last_referer;
                return $link;
            } else {// endif

        if($data['type'] == 3) {

        $idUs = $this->userInsert(array('email' => $email,
                'passw' => $pass,
                'isblocked' => 2,
                'ismoder' => 0,
                'status' => 3,
                'messenger' => $data['messenger'],
            ), 1);

        $idUser = 0;
        $token = md5($email . date("d.m.Y H:i:s") . md5($pass));
        $uid = md5($idUs);
        $password = $pass;
        $this->userActivateInsertUpdate(array('id_user' => $idUs,
            'token' => $token,
            'data' => json_encode($data),
            'dt_create' => date('Y-m-d H:i:s'),
        ));

        $analytData = array('id_us' => $idUs,
                        'name' => $data['fname']." ".$data['lname'],
                        'date' =>  date('Y-m-d H:i:s'),
                        'type' => 3,
                        'referer' => $data['referer'],
                        'canal' => $data['canal'],
                        'campaign' => $data['campaign'],
                        'content' => $data['content'], 
                        'keywords' => urldecode($data['keywords']),
                        'point' => $data['point'], 
                        'transition' => $data['transition'],
                        'last_referer' => $data['last_referer'],
                        'active' => 0,
                        'admin' => 0,
                        'subdomen' => 0
                    );


                     $res = Yii::app()->db->createCommand()
                        ->insert('analytic', $analytData);

        $link  = 'http://' . $_SERVER['HTTP_HOST'] . MainConfig::$PAGE_ACTIVATE . '/?type=3&t=' . $token . "&uid=" . $idUs."&referer=".$referer."&keywords=".$keywords."&transition=".$transition."&canal=".$canal."&campaign=".$campaign."&content=".$content."&point=".$point."&last_referer=".$last_referer;
            
        } else {

        $idUs = $this->userInsert(array('email' => $email,
                'passw' => $pass,
                'isblocked' => 2,
                'ismoder' => 0,
                'status' => 2,
                'messenger' => $data['messenger'],
            ), 1);

        $idUser = 0;
        $token = md5($email . date("d.m.Y H:i:s") . md5($pass));
        $uid = md5($idUs);
        $password = $pass;
        $this->userActivateInsertUpdate(array('id_user' => $idUs,
            'token' => $token,
            'data' => json_encode($data),
            'dt_create' => date('Y-m-d H:i:s'),
        ));
        
         $analytData = array('id_us' => $idUs,
                        'name' => $data['fname']." ".$data['lname'],
                        'date' =>  date('Y-m-d H:i:s'),
                        'type' => 2,
                        'referer' => $data['referer'],
                        'canal' => $data['canal'],
                        'campaign' => $data['campaign'],
                        'content' => $data['content'], 
                        'keywords' => urldecode($data['keywords']),
                        'point' => $data['point'], 
                        'transition' => $data['transition'],
                        'last_referer' => $data['last_referer'],
                        'active' => 0,
                        'admin' => 0,
                        'subdomen' => 0
                    );


                     $res = Yii::app()->db->createCommand()
                        ->insert('analytic', $analytData);


         if($data['gender'] == 'male' || $data['gender'] == 1 || $data['gender'] == 'MALE'){
            $sex = 1;
        }
        else $sex = 0; $smart = 1;


        $link  = 'http://' . $_SERVER['HTTP_HOST'] . MainConfig::$PAGE_ACTIVATE . '/?type=2&t=' . $token . "&uid=" . $idUs."&sex=".$sex."&birthday=".$birthday."&smart=1&referer=".$referer."&keywords=".$keywords."&transition=".$transition."&canal=".$canal."&campaign=".$campaign."&content=".$content."&point=".$point."&last_referer=".$last_referer;



        }


        return $link;
        }
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
