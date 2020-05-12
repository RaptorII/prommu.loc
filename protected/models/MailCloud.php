<?php


class MailCloud extends Model
{

	public function mailer($cloud)
    {


    	foreach ($cloud as $key => $value) {

    	$res = Yii::app()->db->createCommand("SELECT u.id_user, u.email, u.status  FROM user u WHERE u.id_user = {$value}")->queryRow();

    	$usData = Yii::app()->db->createCommand()
                ->select("u.id_user, u.status, a.token, a.data")
                ->from('user_activate a')
                ->join('user u', 'u.id_user = a.id_user')
                ->where('a.id_user = :u', array(':u' => $value))
                ->queryRow();
        $cloudPrommu =  json_decode($usData['data'], true);
        $pass = $cloudPrommu['pass'];
        $email = $cloudPrommu['email'];

        $analytData = Yii::app()->db->createCommand()
                ->select("a.id_us, a.referer, a.canal, a.campaign,
          			 a.content, a.keywords, a.point, a.last_referer, a.ip, a.client")
                ->from('analytic a')
               ->join('user u', 'u.id_user = a.id_us')
                ->where('a.id_us = :u', array(':u' => $value))
                ->queryRow();



    	if( $res['status'] == 2 )
        {

            $link  = 'https://prommu.com' . MainConfig::$PAGE_ACTIVATE . '/?t=' . $usData['token'] . "&uid=" . $value."&referer=".$analytData['referer']."&transition=".$analytData['transition']."&canal=".$analytData['canal']."&campaign=".$analytData['campaign']."&content=".$analytData['content']."&keywords=".$analytData['keywords']."&point=".$analytData['point']."&last_referer=".$analytData['last_referer']."&admin=".$analytData['admin']."&ip=".$analytData['ip']."&client=".$analytData['client'];

             $message = '<p style="font-size:16px;">Вы зарегистрировались у нас на сервисе: https://prommu.com/  но еще не активировали свой аккаунт</p>
                    <br/>

        		<p style=" font-size:16px;">
                    <br/>
        		Вам достаточно <a href ='.$link.'><ins>подтвердить регистрацию</ins></a> на нашем портале и воспользоваться логином/паролем для входа на портал - и вы сможете сразу откликаться на понравившиеся вакансии  </p>
                    <br/>
        		<p style="text-align: center; font-size:16px;">Подтверждая регистрацию, вы начинаете пользоваться всеми преимуществами сервиса Prommu.com </p>

      
        		<div class="hh" style="position: relative;  text-align: center;"><a href='.$link.'><span style="display: inline-block; position: relative; margin-bottom: -22px; width: 240px; height: 60px; background: #fff url(https://prommu.com/theme/pic/button.jpg) center center no-repeat"></span></a>
        		</div>
                    <br/> <div style="margin: 20px;" class="text-block">
                    <p style="text-align: center">Мы не требуем никаких оплат, отправки смс, не рассылаем спам </p>
                    <br/>
                    <br/>
                  <p style="font-size:16px;"> Ваш логин для входа на портал:'.$email.
                    '<br/>
                    Ваш пароль для входа на портал:'.$pass.'</p></div>';
        	Share::sendmail($res['email'], "Prommu.com. Подтверждение регистрации на портале поиска временной работы!", $message);

        

        }
        else
        {
            $link  = 'https://prommu.com' . MainConfig::$PAGE_ACTIVATE . '/?t=' . $usData['token'] . "&uid=" . $value."&referer=".$analytData['referer']."&transition=".$analytData['transition']."&canal=".$analytData['canal']."&campaign=".$analytData['campaign']."&content=".$analytData['content']."&keywords=".$analytData['keywords']."&point=".$analytData['point']."&last_referer=".$analytData['last_referer']."&admin=".$analytData['admin']."&ip=".$analytData['ip']."&client=".$analytData['client'];
            $message = '<p style="font-size:16px;">Вы зарегистрировались у нас на сервисе: https://prommu.com/  но еще не активировали свой аккаунт</p>
                    <br/>
                      
        <p style="font-size:16px;"> Вам достаточно <a href ='.$link.'><ins>подтвердить регистрацию</ins></a> на нашем портале и воспользоваться логином/паролем для входа на портал - и вы сможете сразу размешать вакансии и просматрировать анкеты соискателей. </p>
                    <br/>
        <p style="text-align: center;font-size:16px;>Подтверждая регистрацию, вы начинаете пользоваться всеми преимуществами сервиса Prommu.com </p>
       <div class="hh" style="position: relative;  text-align: center;"><a href='.$link.'><span style="display: inline-block; position: relative; margin-bottom: -22px; width: 240px; height: 60px; background: #fff url(https://prommu.com/theme/pic/button.jpg) center center no-repeat"></span></a>
        </div>
                    <br/> <div style="margin: 20px;" class="text-block">

                    <p style="text-align: center">Мы не требуем никаких оплат, отправки смс, не рассылаем спам </p>
                    <br/>
                    <br/>
                    <p style="font-size:16px;">Ваш логин для входа на портал:'.$email.
                    '<br/>
                    Ваш пароль для входа на портал:'.$pass.'</p></div>';
        Share::sendmail($res['email'], " Prommu.com. Подтверждение регистрации на портале поиска персонала!", $message);
        } // endif

        $res = Yii::app()->db->createCommand()
                ->update('user', array(
                    'mdate' => date('Y-m-d H:i:s'),
                ), 'id_user=:id_user', array(':id_user' => $value));
    	
    }

    	

  	}

     public function mailerMail($cloud)
    {
        $adminMess = $cloud['chat'] ? $cloud['chat'] : $cloud['message'];
        if(filter_var($cloud['email'],FILTER_SANITIZE_EMAIL))
        {
            $message = '<p style="font-size:16px;text-align: center">Здравствуйте'.$cloud['name'].'</p>
                <br/>
                <p style="font-size:16px;">Ответ на ваше обращение:</p>
                <div style="font-size:16px"> '.$adminMess.'.</div>
                <br/>';
            Share::sendmail($cloud['email'], "Prommu.com. Ответ на вопрос", $message);    
        }

        if(!$cloud['chat'])
        {
            $message = '<br/>
                <p style=" font-size:16px;"><br/>Номер обращения в системе Prommu Admin: '.$cloud['id'].'.</p><br/>
                <p style=" font-size:16px;"><br/>Пользователь: '.$cloud['idusp'].'.</p><br/>
                <p style=" font-size:16px;"><br/>Текст обращения: '.$cloud['text'].'.</p><br/>
                <p style=" font-size:16px;"><br/>Ответ администратора Prommu: '.$adminMess.'.</p><br/>';
            Share::sendmail('projekt.sergey@gmail.com', "Prommu.com. Ответ на вопрос", $message);
        }
        else
        {
            $message = '<br/>
                <p style=" font-size:16px;"><br/>Номер обращения в системе Prommu Admin: '.$cloud['id'].'.</p><br/>
                <p style=" font-size:16px;"><br/>Пользователь: '.$cloud['name'].'.</p><br/>
                <p style=" font-size:16px;"><br/>Текст обращения: '.$cloud['text'].'.</p><br/>
                <p style=" font-size:16px;"><br/>Ответ администратора Prommu: '.$adminMess.'.</p><br/>';
            Share::sendmail('projekt.sergey@gmail.com', "Prommu.com. Ответ на вопрос", $message);
        }

        $text = "Администратор ответил на feedback https://prommu.com/admin/feedback";
        $sendto ="https://api.telegram.org/bot525649107:AAFWUj7O8t6V-GGt3ldzP3QBEuZOzOz-ij8/sendMessage?chat_id=@prommubag&text=".$text;
        file_get_contents($sendto);
    }

 	
       public function mailerVac($cloud)
    {


        foreach ($cloud as $key => $value) {

        $sql = "SELECT eu.email emailempl, eu.id_user idusempl
                , e.id, e.title, e.remdate, em.name coname
                , em.name
            FROM empl_vacations e 
            INNER JOIN employer em ON em.id_user = e.id_user
            INNER JOIN user eu ON em.id_user = eu.id_user
            WHERE e.id = {$value}
              ";
            }
        $res = Yii::app()->db->createCommand($sql)->queryAll();


        $name = $res[0]['name'];

        $link = 'https://prommu.com/vacancy/'.$value;


             $message = '<p style="font-size:16px;text-align: center">Здравствуйте,'.$res[0]['name'].'</p>
                    <br/>

                <p style=" font-size:16px;">
                    <br/>
                Мы напоминаем Вам, что в Личном кабинете есть неопубликованные вакансии '.$value.'. Чтобы Ваша вакансия появилась сервисе Промму и ее увидели потенциальные соискатели, необходимо всего лишь нажать в личном кабинете "Опубликовать вакансию". После прохождения модерации она будет видна многочисленным соискателям в Вашем городе.</p>
                    <br/>
                <p style="text-align: center; font-size:16px;">Перейти к вакансии в личном кабинете можно по ссылке <a href='.$link.'>Опубликовать</a></p>';
            Share::sendmail($res[0]['emailempl'], "Prommu.com. Есть не активные вакансии!", $message);

        


        $res = Yii::app()->db->createCommand()
                ->update('empl_vacations', array(
                    'mdate' => date('Y-m-d H:i:s'),
                ), 'id=:id', array(':id' => $value));
        
    }

        

    
       
    
    public function mailerBlock($id, $type)
    {

        if($type == 2){ 

        $user = Yii::app()->db->createCommand()
            ->select("e.firstname,
                 usr.email, e.photo, e.isman")
            ->from('resume e')
            ->join('user usr', 'usr.id_user=e.id_user')
            ->where('e.id_user=:id_user', array(':id_user' => $id))
            ->queryAll();

        
        $link = 'https://prommu.com/services/conditions';

             $message = '<p style="font-size:16px;text-align: center">Здравствуйте,'.$user[0]['firstname'].'</p>
                    <br/>

                <p style=" font-size:16px;">
                    <br/>
               К сожалению, мы вынуждены приостановить показы Вашей анкеты по причине несоответствия с нашим регламентом размещения соискателя</p>
                    <br/>
                <p style="text-align: center; font-size:16px;">Cсылка на   <a href='.$link.'>Правила пользования сайтом</a></p>';
            Share::sendmail($user[0]['email'], "Prommu.com. Ваша анкета заблокирована!", $message);
        }
        else {
            $user = Yii::app()->db->createCommand()
            ->select("e.name,
                 usr.email")
            ->from('employer e')
            ->join('user usr', 'usr.id_user=e.id_user')
            ->where('e.id_user=:id_user', array(':id_user' => $id))
            ->queryAll();

        
        $link = 'https://prommu.com/services/conditions';

             $message = '<p style="font-size:16px;text-align: center">Здравствуйте,'.$user[0]['name'].'</p>
                    <br/>

                <p style=" font-size:16px;">
                    <br/>
               К сожалению, мы вынуждены приостановить показы Вашей анкеты по причине несоответствия с нашим регламентом размещения соискателя</p>
                    <br/>
                <p style="text-align: center; font-size:16px;">Cсылка на   <a href='.$link.'>Правила пользования сайтом</a></p>';
            Share::sendmail($user[0]['email'], "Prommu.com. Ваша анкета заблокирована!", $message);
        }
        

    }



    public function mailerMess($id,$idTm, $type)
    {

        if($type == 2){ 
            $user = Yii::app()->db->createCommand()
            ->select("e.name,
                 usr.email")
            ->from('employer e')
            ->join('user usr', 'usr.id_user=e.id_user')
            ->where('e.id_user=:id_user', array(':id_user' => $id))
            ->queryAll();

         $content = file_get_contents(Yii::app()->basePath . "/views/mails/emp/app-answer-to-mess.html");
                  $content = str_replace('#APPNAME#', Share::$UserProfile->exInfo->firstname. ' ' . Share::$UserProfile->exInfo->lastname, $content);
                 $content = str_replace('#EMPNAME#', $user[0]['name'], $content);
                 $content = str_replace('#CHATLINK#',  "https://prommu.com/user/im/$idTm", $content);
               if(strpos($user[0]['email'], "@") !== false)
               Share::sendmail($user[0]['email'], "Prommu.com Новое сообщение", $content);



      
        }
        else {
             $user = Yii::app()->db->createCommand()
            ->select("e.firstname,usr.email")
            ->from('resume e')
            ->join('user usr', 'usr.id_user=e.id_user')
            ->where('e.id_user=:id_user', array(':id_user' => $id))
            ->queryAll();


        
                $content = file_get_contents(Yii::app()->basePath . "/views/mails/app/emp-answer-to-mess.html");
                  $content = str_replace('#APPNAME#', $user[0]['firstname']. ' ' . $user[0]['lastname'], $content);
                 $content = str_replace('#EMPCOMPANY#', Share::$UserProfile->exInfo->name, $content);
                 $content = str_replace('#CHATLINK#',  "https://prommu.com/user/im/$idTm", $content);
               if(strpos($user[0]['email'], "@") !== false)
               Share::sendmail($user[0]['email'], "Prommu.com Новое сообщение", $content);

        }
        

    }





    public function mailerModer($id, $type)
    {

        if($type == 2){ 

        $user = Yii::app()->db->createCommand()
            ->select("e.firstname,
                 usr.email, e.photo, e.isman")
            ->from('resume e')
            ->join('user usr', 'usr.id_user=e.id_user')
            ->where('e.id_user=:id_user', array(':id_user' => $id))
            ->queryAll();

        
        $link = 'https://prommu.com/services/conditions';

             $message = '<p style="font-size:16px;text-align: center">Здравствуйте,'.$user[0]['firstname'].'</p>
                    <br/>

                <p style=" font-size:16px;">
                    <br/>
               Ваша анкета успешно прошла модерацию</p>
                    <br/>
                <p style="text-align: center; font-size:16px;">Cсылка на   <a href='.$link.'>Правила пользования сайтом</a></p>';
            Share::sendmail($user[0]['email'], "Prommu.com. Ваша анкета прошла модерацию!", $message);
        }
        else {
            $user = Yii::app()->db->createCommand()
            ->select("e.name,
                 usr.email")
            ->from('employer e')
            ->join('user usr', 'usr.id_user=e.id_user')
            ->where('e.id_user=:id_user', array(':id_user' => $id))
            ->queryAll();

        
        $link = 'https://prommu.com/services/conditions';

             $message = '<p style="font-size:16px;text-align: center">Здравствуйте,'.$user[0]['name'].'</p>
                    <br/>

                <p style=" font-size:16px;">
                    <br/>
               Ваша анкета успешно прошла модерацию</p>
                    <br/>
                <p style="text-align: center; font-size:16px;">Cсылка на   <a href='.$link.'>Правила пользования сайтом</a></p>';
            Share::sendmail($user[0]['email'], "Prommu.com.  Ваша анкета прошла модерацию!", $message);
        }
        

    }


    // public function mailSetting($inProps = []){
        
    //     if($inProps['type'] == 2) {

    //         switch ($message) {
    //             case "Создание вакансии (подходящей под параметры город и должность)":

    //                  $message = sprintf("На портале Промму работодатель <a href='http://%3$01s'>%s</a> опубликовал вакансию “<a href='http://%4$01s'>%s</a>”, которая соответствует Вашей желаемой должности и Вашему городу" .
    //                         "<br/><br/>"
    //                     , $inProps['name']
    //                     , $inProps['title']
    //                     , Subdomain::getSiteName() . MainConfig::$PAGE_PROFILE_COMMON . DS . $Profile->exInfo->id
    //                     , Subdomain::getSiteName() . MainConfig::$PAGE_VACANCY . DS . $inProps['id']
    //                     );

    //                 Share::sendmail($inProps['email'], "Prommu: Опубликована вакансия", $message);
    //                 break;

    //              case "Приглашение на вакансию от Работодателя":
                
    //                  $message = sprintf("На портале Промму работодатель <a href='http://%3$01s'>%s</a> пригласил Вас на свою вакансию “<a href='http://%4$01s'>%s</a>”, которая соответствует вашей желаемой должности и вашему городу" .
    //                         "<br/><br/>"
    //                     , $inProps['name']
    //                     , $inProps['title']
    //                     , Subdomain::getSiteName() . MainConfig::$PAGE_PROFILE_COMMON . DS . $Profile->exInfo->id
    //                     , Subdomain::getSiteName() . MainConfig::$PAGE_VACANCY . DS . $inProps['id']
    //                     );

    //                 Share::sendmail($inProps['email'], "Prommu: Опубликована вакансия", $message);
    //                 break;

    //             case "Утверждение на вакансии":
                
    //                  $message = sprintf("На портале Промму работодатель <a href='http://%4$01s'>%s %s</a> подтвердил ваше участие на вакансии “<a href='http://%5$01s'>%s</a>”. " .
    //                         "<br/><br/>" .
    //                         "Страница <a href='http://%6$01s'>заявок на ваши вакансии</a>."
    //                     , $Profile->exInfo->firstname
    //                     , $Profile->exInfo->lastname
    //                     , $vacData['title']
    //                     , Subdomain::getSiteName() . MainConfig::$PAGE_PROFILE_COMMON . DS . $Profile->exInfo->id
    //                     , Subdomain::getSiteName() . MainConfig::$PAGE_VACANCY . DS . $id
    //                     , Subdomain::getSiteName() . MainConfig::$PAGE_RESPONSES
    //                     );

    //                 Share::sendmail($email, "Prommu: заявка на вакансию", $message);
    //                 break;

    //             case "Отклонение Работодателем из вакансии":
                
    //                  $message = sprintf("На портале Промму пользователь <a href='http://%4$01s'>%s %s</a> оставил заявку на вашу вакансию “<a href='http://%5$01s'>%s</a>”. " .
    //                         "<br/><br/>" .
    //                         "Страница <a href='http://%6$01s'>заявок на ваши вакансии</a>."
    //                     , $Profile->exInfo->firstname
    //                     , $Profile->exInfo->lastname
    //                     , $vacData['title']
    //                     , Subdomain::getSiteName() . MainConfig::$PAGE_PROFILE_COMMON . DS . $Profile->exInfo->id
    //                     , Subdomain::getSiteName() . MainConfig::$PAGE_VACANCY . DS . $id
    //                     , Subdomain::getSiteName() . MainConfig::$PAGE_RESPONSES
    //                     );

    //                 Share::sendmail($email, "Prommu: заявка на вакансию", $message);
    //                 break;

    //             case "Ответ работодателя по вакансии сообщением":
                
    //                  $message = sprintf("На портале Промму пользователь <a href='http://%4$01s'>%s %s</a> оставил заявку на вашу вакансию “<a href='http://%5$01s'>%s</a>”. " .
    //                         "<br/><br/>" .
    //                         "Страница <a href='http://%6$01s'>заявок на ваши вакансии</a>."
    //                     , $Profile->exInfo->firstname
    //                     , $Profile->exInfo->lastname
    //                     , $vacData['title']
    //                     , Subdomain::getSiteName() . MainConfig::$PAGE_PROFILE_COMMON . DS . $Profile->exInfo->id
    //                     , Subdomain::getSiteName() . MainConfig::$PAGE_VACANCY . DS . $id
    //                     , Subdomain::getSiteName() . MainConfig::$PAGE_RESPONSES
    //                     );

    //                 Share::sendmail($email, "Prommu: заявка на вакансию", $message);
    //                 break;

    //             case "Ответ работодателя по вакансии из чата":
                
    //                  $message = sprintf("На портале Промму пользователь <a href='http://%4$01s'>%s %s</a> оставил заявку на вашу вакансию “<a href='http://%5$01s'>%s</a>”. " .
    //                         "<br/><br/>" .
    //                         "Страница <a href='http://%6$01s'>заявок на ваши вакансии</a>."
    //                     , $Profile->exInfo->firstname
    //                     , $Profile->exInfo->lastname
    //                     , $vacData['title']
    //                     , Subdomain::getSiteName() . MainConfig::$PAGE_PROFILE_COMMON . DS . $Profile->exInfo->id
    //                     , Subdomain::getSiteName() . MainConfig::$PAGE_VACANCY . DS . $id
    //                     , Subdomain::getSiteName() . MainConfig::$PAGE_RESPONSES
    //                     );

    //                 Share::sendmail($email, "Prommu: заявка на вакансию", $message);
    //                 break;

    //             case "Изменение вакансии работодателем":
                
    //                  $message = sprintf("На портале Промму пользователь <a href='http://%4$01s'>%s %s</a> оставил заявку на вашу вакансию “<a href='http://%5$01s'>%s</a>”. " .
    //                         "<br/><br/>" .
    //                         "Страница <a href='http://%6$01s'>заявок на ваши вакансии</a>."
    //                     , $Profile->exInfo->firstname
    //                     , $Profile->exInfo->lastname
    //                     , $vacData['title']
    //                     , Subdomain::getSiteName() . MainConfig::$PAGE_PROFILE_COMMON . DS . $Profile->exInfo->id
    //                     , Subdomain::getSiteName() . MainConfig::$PAGE_VACANCY . DS . $id
    //                     , Subdomain::getSiteName() . MainConfig::$PAGE_RESPONSES
    //                     );

    //                 Share::sendmail($email, "Prommu: заявка на вакансию", $message);
    //                 break;

    //             case "Начало работы по проекту завтра в такое то время":
                
    //                  $message = sprintf("На портале Промму пользователь <a href='http://%4$01s'>%s %s</a> оставил заявку на вашу вакансию “<a href='http://%5$01s'>%s</a>”. " .
    //                         "<br/><br/>" .
    //                         "Страница <a href='http://%6$01s'>заявок на ваши вакансии</a>."
    //                     , $Profile->exInfo->firstname
    //                     , $Profile->exInfo->lastname
    //                     , $vacData['title']
    //                     , Subdomain::getSiteName() . MainConfig::$PAGE_PROFILE_COMMON . DS . $Profile->exInfo->id
    //                     , Subdomain::getSiteName() . MainConfig::$PAGE_VACANCY . DS . $id
    //                     , Subdomain::getSiteName() . MainConfig::$PAGE_RESPONSES
    //                     );

    //                 Share::sendmail($email, "Prommu: заявка на вакансию", $message);
    //                 break;

    //             case "Получение нового отзыва":
                
    //                  $message = sprintf("На портале Промму пользователь <a href='http://%4$01s'>%s %s</a> оставил заявку на вашу вакансию “<a href='http://%5$01s'>%s</a>”. " .
    //                         "<br/><br/>" .
    //                         "Страница <a href='http://%6$01s'>заявок на ваши вакансии</a>."
    //                     , $Profile->exInfo->firstname
    //                     , $Profile->exInfo->lastname
    //                     , $vacData['title']
    //                     , Subdomain::getSiteName() . MainConfig::$PAGE_PROFILE_COMMON . DS . $Profile->exInfo->id
    //                     , Subdomain::getSiteName() . MainConfig::$PAGE_VACANCY . DS . $id
    //                     , Subdomain::getSiteName() . MainConfig::$PAGE_RESPONSES
    //                     );

    //                 Share::sendmail($email, "Prommu: заявка на вакансию", $message);
    //                 break;

    //             case "Уведомление по Услугам (по действиям)":
                
    //                  $message = sprintf("На портале Промму пользователь <a href='http://%4$01s'>%s %s</a> оставил заявку на вашу вакансию “<a href='http://%5$01s'>%s</a>”. " .
    //                         "<br/><br/>" .
    //                         "Страница <a href='http://%6$01s'>заявок на ваши вакансии</a>."
    //                     , $Profile->exInfo->firstname
    //                     , $Profile->exInfo->lastname
    //                     , $vacData['title']
    //                     , Subdomain::getSiteName() . MainConfig::$PAGE_PROFILE_COMMON . DS . $Profile->exInfo->id
    //                     , Subdomain::getSiteName() . MainConfig::$PAGE_VACANCY . DS . $id
    //                     , Subdomain::getSiteName() . MainConfig::$PAGE_RESPONSES
    //                     );

    //                 Share::sendmail($email, "Prommu: заявка на вакансию", $message);
    //                 break;
                    


    //             default:
    //                 # code...
    //                 break;
    //         }


    //     }
    //     elseif($inProps['type']) {

    //         switch ($message) {
    //             case "Отклик Соискателя на вакансию":

    //                  $message = sprintf("На портале Промму пользователь <a href='http://%4$01s'>%s %s</a> оставил заявку на вашу вакансию “<a href='http://%5$01s'>%s</a>”. " .
    //                         "<br/><br/>" .
    //                         "Страница <a href='http://%6$01s'>заявок на ваши вакансии</a>."
    //                     , $Profile->exInfo->firstname
    //                     , $Profile->exInfo->lastname
    //                     , $vacData['title']
    //                     , Subdomain::getSiteName() . MainConfig::$PAGE_PROFILE_COMMON . DS . $Profile->exInfo->id
    //                     , Subdomain::getSiteName() . MainConfig::$PAGE_VACANCY . DS . $id
    //                     , Subdomain::getSiteName() . MainConfig::$PAGE_RESPONSES
    //                     );

    //                 Share::sendmail($email, "Prommu: заявка на вакансию", $message);
    //                 break;

    //              case "Соискатель подтвердил участие на предложенной вакансии":
                
    //                  $message = sprintf("На портале Промму пользователь <a href='http://%4$01s'>%s %s</a> оставил заявку на вашу вакансию “<a href='http://%5$01s'>%s</a>”. " .
    //                         "<br/><br/>" .
    //                         "Страница <a href='http://%6$01s'>заявок на ваши вакансии</a>."
    //                     , $Profile->exInfo->firstname
    //                     , $Profile->exInfo->lastname
    //                     , $vacData['title']
    //                     , Subdomain::getSiteName() . MainConfig::$PAGE_PROFILE_COMMON . DS . $Profile->exInfo->id
    //                     , Subdomain::getSiteName() . MainConfig::$PAGE_VACANCY . DS . $id
    //                     , Subdomain::getSiteName() . MainConfig::$PAGE_RESPONSES
    //                     );

    //                 Share::sendmail($email, "Prommu: заявка на вакансию", $message);
    //                 break;

    //             case "Соискатель отказался от участия в вакансии":
                
    //                  $message = sprintf("На портале Промму пользователь <a href='http://%4$01s'>%s %s</a> оставил заявку на вашу вакансию “<a href='http://%5$01s'>%s</a>”. " .
    //                         "<br/><br/>" .
    //                         "Страница <a href='http://%6$01s'>заявок на ваши вакансии</a>."
    //                     , $Profile->exInfo->firstname
    //                     , $Profile->exInfo->lastname
    //                     , $vacData['title']
    //                     , Subdomain::getSiteName() . MainConfig::$PAGE_PROFILE_COMMON . DS . $Profile->exInfo->id
    //                     , Subdomain::getSiteName() . MainConfig::$PAGE_VACANCY . DS . $id
    //                     , Subdomain::getSiteName() . MainConfig::$PAGE_RESPONSES
    //                     );

    //                 Share::sendmail($email, "Prommu: заявка на вакансию", $message);
    //                 break;

    //             case "Ответ соискателя по вакансии сообщением":
                
    //                  $message = sprintf("На портале Промму пользователь <a href='http://%4$01s'>%s %s</a> оставил заявку на вашу вакансию “<a href='http://%5$01s'>%s</a>”. " .
    //                         "<br/><br/>" .
    //                         "Страница <a href='http://%6$01s'>заявок на ваши вакансии</a>."
    //                     , $Profile->exInfo->firstname
    //                     , $Profile->exInfo->lastname
    //                     , $vacData['title']
    //                     , Subdomain::getSiteName() . MainConfig::$PAGE_PROFILE_COMMON . DS . $Profile->exInfo->id
    //                     , Subdomain::getSiteName() . MainConfig::$PAGE_VACANCY . DS . $id
    //                     , Subdomain::getSiteName() . MainConfig::$PAGE_RESPONSES
    //                     );

    //                 Share::sendmail($email, "Prommu: заявка на вакансию", $message);
    //                 break;

    //             case "Старт проекта завтра":
                
    //                  $message = sprintf("На портале Промму пользователь <a href='http://%4$01s'>%s %s</a> оставил заявку на вашу вакансию “<a href='http://%5$01s'>%s</a>”. " .
    //                         "<br/><br/>" .
    //                         "Страница <a href='http://%6$01s'>заявок на ваши вакансии</a>."
    //                     , $Profile->exInfo->firstname
    //                     , $Profile->exInfo->lastname
    //                     , $vacData['title']
    //                     , Subdomain::getSiteName() . MainConfig::$PAGE_PROFILE_COMMON . DS . $Profile->exInfo->id
    //                     , Subdomain::getSiteName() . MainConfig::$PAGE_VACANCY . DS . $id
    //                     , Subdomain::getSiteName() . MainConfig::$PAGE_RESPONSES
    //                     );

    //                 Share::sendmail($email, "Prommu: заявка на вакансию", $message);
    //                 break;

    //             case "Старт проекта сегодня (оповещение за час до начала)":
                
    //                  $message = sprintf("На портале Промму пользователь <a href='http://%4$01s'>%s %s</a> оставил заявку на вашу вакансию “<a href='http://%5$01s'>%s</a>”. " .
    //                         "<br/><br/>" .
    //                         "Страница <a href='http://%6$01s'>заявок на ваши вакансии</a>."
    //                     , $Profile->exInfo->firstname
    //                     , $Profile->exInfo->lastname
    //                     , $vacData['title']
    //                     , Subdomain::getSiteName() . MainConfig::$PAGE_PROFILE_COMMON . DS . $Profile->exInfo->id
    //                     , Subdomain::getSiteName() . MainConfig::$PAGE_VACANCY . DS . $id
    //                     , Subdomain::getSiteName() . MainConfig::$PAGE_RESPONSES
    //                     );

    //                 Share::sendmail($email, "Prommu: заявка на вакансию", $message);
    //                 break;

    //             case "Проект завершается (оповещение за час до завершения)":
                
    //                  $message = sprintf("На портале Промму пользователь <a href='http://%4$01s'>%s %s</a> оставил заявку на вашу вакансию “<a href='http://%5$01s'>%s</a>”. " .
    //                         "<br/><br/>" .
    //                         "Страница <a href='http://%6$01s'>заявок на ваши вакансии</a>."
    //                     , $Profile->exInfo->firstname
    //                     , $Profile->exInfo->lastname
    //                     , $vacData['title']
    //                     , Subdomain::getSiteName() . MainConfig::$PAGE_PROFILE_COMMON . DS . $Profile->exInfo->id
    //                     , Subdomain::getSiteName() . MainConfig::$PAGE_VACANCY . DS . $id
    //                     , Subdomain::getSiteName() . MainConfig::$PAGE_RESPONSES
    //                     );

    //                 Share::sendmail($email, "Prommu: заявка на вакансию", $message);
    //                 break;

    //             case "Получение нового отзыва":
                
    //                  $message = sprintf("На портале Промму пользователь <a href='http://%4$01s'>%s %s</a> оставил заявку на вашу вакансию “<a href='http://%5$01s'>%s</a>”. " .
    //                         "<br/><br/>" .
    //                         "Страница <a href='http://%6$01s'>заявок на ваши вакансии</a>."
    //                     , $Profile->exInfo->firstname
    //                     , $Profile->exInfo->lastname
    //                     , $vacData['title']
    //                     , Subdomain::getSiteName() . MainConfig::$PAGE_PROFILE_COMMON . DS . $Profile->exInfo->id
    //                     , Subdomain::getSiteName() . MainConfig::$PAGE_VACANCY . DS . $id
    //                     , Subdomain::getSiteName() . MainConfig::$PAGE_RESPONSES
    //                     );

    //                 Share::sendmail($email, "Prommu: заявка на вакансию", $message);
    //                 break;

    //             case "Уведомление по Услугам (по действиям)":
                
    //                  $message = sprintf("На портале Промму пользователь <a href='http://%4$01s'>%s %s</a> оставил заявку на вашу вакансию “<a href='http://%5$01s'>%s</a>”. " .
    //                         "<br/><br/>" .
    //                         "Страница <a href='http://%6$01s'>заявок на ваши вакансии</a>."
    //                     , $Profile->exInfo->firstname
    //                     , $Profile->exInfo->lastname
    //                     , $vacData['title']
    //                     , Subdomain::getSiteName() . MainConfig::$PAGE_PROFILE_COMMON . DS . $Profile->exInfo->id
    //                     , Subdomain::getSiteName() . MainConfig::$PAGE_VACANCY . DS . $id
    //                     , Subdomain::getSiteName() . MainConfig::$PAGE_RESPONSES
    //                     );

    //                 Share::sendmail($email, "Prommu: заявка на вакансию", $message);
    //                 break;



    //             default:
    //                 # code...
    //                 break;
    //         }



    //     }


    // }


}


?>