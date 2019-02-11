<?php
/**
 * Date: 15.04.2016
 *
 * Модель откликов
 */

class ResponsesApplic extends Responses
{

    /**
     * устанавливаем рейтинг работодателю
     */
    public function setRate()
    {
        return $this->loadRatingPageData();
    }



    /**
     * устанавливаем статус отклика
     */
    public function setResponseStatus($props = [])
    {
        // if( Yii::app()->getRequest()->requestType != 'POST' ) $ret = array('error' => 1);
        $idres = $props['idres'] ?: filter_var(Yii::app()->getRequest()->getParam('idres'), FILTER_SANITIZE_NUMBER_INT);
        $status = $props['status'] ?: filter_var(Yii::app()->getRequest()->getParam('s'), FILTER_SANITIZE_NUMBER_INT);
        $idus = $props['idus'] ?: Share::$UserProfile->exInfo->id;

        // проверяем, что заявка пренадлежит этому пользователю пользователю
        $sql = "SELECT s.id sid, s.status, s.id_vac
            , u.email, e.id_user
            , e.id, e.title
            , r.isman, em.name
            FROM vacation_stat s
            INNER JOIN resume r ON r.id = s.id_promo
            INNER JOIN empl_vacations e ON e.id = s.id_vac
            INNER JOIN employer em ON e.id_user = em.id_user
            INNER JOIN user u ON u.id_user = e.id_user
            WHERE r.id_user = {$idus} AND s.id = {$idres}";
        /** @var $res CDbCommand */
        $vacData = Yii::app()->db->createCommand($sql);
        $vacData = $vacData->queryRow();

        // if response exists
        if( $vacData['sid'] )
        {
            $res = Yii::app()->db->createCommand()
                ->update('vacation_stat', array( 'status' => $status,
                    'mdate' => date('Y-m-d H:i:s'),
                ), 'id = :id', array(':id' => $idres));
            $ret = array('error' => 0, 'res' => $res);
            if($status == 3){
                 $content = file_get_contents(Yii::app()->basePath . "/views/mails/emp/app-refused-part.html");
                  $content = str_replace('#APPNAME#', Share::$UserProfile->exInfo->firstname . ' ' . Share::$UserProfile->exInfo->lastname, $content);
                  $content = str_replace('#EMPNAME#', $vacData['name'], $content);
                 $content = str_replace('#APPLINK#',  Subdomain::site() . MainConfig::$PAGE_PROFILE_COMMON . DS . Share::$UserProfile->exInfo->id, $content);
                 $content = str_replace('#VACID#', $vacData['id_vac'], $content);
                 $content = str_replace('#VACNAME#', $vacData['title'], $content);
                 $content = str_replace('#RESPLINK#',Subdomain::site() . MainConfig::$PAGE_RESPONSES, $content);
                 $content = str_replace('#VACLINK#',  Subdomain::site() . MainConfig::$PAGE_VACANCY . DS . $vacData['id_vac'], $content);
      
              Share::sendmail($vacData['email'], "Prommu: заявка на вакансию отклонена", $content);

                return array ('error' => 0, 'message' => 'Вы отклонили приглашение на вакансию');
            }

            if($status == 5){
                 $content = file_get_contents(Yii::app()->basePath . "/views/mails/emp/app-confirmed-part.html");
                 $content = str_replace('#APPNAME#', Share::$UserProfile->exInfo->firstname . ' ' . Share::$UserProfile->exInfo->lastname, $content);
                 $content = str_replace('#APPLINK#',  Subdomain::site() . MainConfig::$PAGE_PROFILE_COMMON . DS . Share::$UserProfile->exInfo->id, $content);
                 $content = str_replace('#VACID#', $vacData['id_vac'], $content);
                 $content = str_replace('#VACNAME#', $vacData['title'], $content);
                 $content = str_replace('#RESPLINK#',Subdomain::site() . MainConfig::$PAGE_RESPONSES, $content);
                 $content = str_replace('#VACLINK#',  Subdomain::site() . MainConfig::$PAGE_VACANCY . DS . $vacData['id_vac'], $content);
      
           Share::sendmail($vacData['email'], "Prommu: заявка на вакансию подтверждена", $content);

            
            }


            if( $status == 5)
            {
                // $message = "<div>Работодатель и Вы утвердили заявку на вакансию. <br>Ожидайте дальнейших инструкций от работодателя</div>";
                // $this->approveVacAfterAction(array('fio' => Share::$UserProfile->exInfo->firstname . ' ' . Share::$UserProfile->exInfo->lastname,
                //     'email' => $vacData['email'],
                //     'title' => $vacData['title'],
                //     'id' => $vacData['id'],
                //     'isman' => $vacData['isman'],
                //     'idus' => $idus,
                // ));

                $vacId = $vacData['id_vac'];
            $sql = "SELECT e.id_user
            FROM empl_vacations e 
            WHERE e.id= {$vacId}";
        /** @var $res CDbCommand */
        $vacRes = Yii::app()->db->createCommand($sql);
        $employer = $vacRes->queryScalar();

             $ids = $employer;
        $sql = "SELECT r.new_respond
            FROM push_config r
            WHERE r.id = {$ids}";
            $res = Yii::app()->db->createCommand($sql)->queryScalar(); 
            if($res == 2) {
            $sql = "SELECT r.push
            FROM user_push r
            WHERE r.id = {$ids}";
            $res = Yii::app()->db->createCommand($sql)->queryRow(); 

            if($res) {
                $type = "respond";
                $api = new Api();
                $api->getPush($res['push'], $type);
            
                    }
                }
    
            }
   
            return array('error' => 0, 'message' => $message);
        
        } // endif

        else
        {
            $ret = array('error' => 1);
        } // endif

        return $ret;
    }



    /**
     * Новые одобренные заявки
     */
    public function getNewResponses()
    {
        $idus = $this->Profile->id;

        // данные работодателя
        $sql = "SELECT COUNT(*) cou
            FROM resume r
            INNER JOIN vacation_stat s ON s.id_promo = r.id AND s.isresponse IN(1,2) AND s.status = 4
            WHERE r.id_user = {$idus}";
        /** @var $res CDbCommand */
        $res = Yii::app()->db->createCommand($sql);
        $res = $res->queryRow();

        return $res['cou'];
    }



    public function getResponsesCount($props = [])
    {
        $idus = $props['id'] ?: Share::$UserProfile->exInfo->id;
        $isresponse = 1;

        if( $props && $props['type'] == 'invites' ) $isresponse = 2;

        // данные работодателя
        $sql = "SELECT COUNT(*)
            FROM vacation_stat s 
            WHERE s.id_promo = {$idus}
              AND s.isresponse = {$isresponse}";
        /** @var $res CDbCommand */
        $res = Yii::app()->db->createCommand($sql);
        $res = $res->queryRow();

        return $res['cou'];
    }

    public function getResponsesRating($props = [])
    {
        $limit = $this->limit;
        $offset = $this->offset;
        $id = $props['id'] ?: Share::$UserProfile->exInfo->id_resume ;
        $isresponse = 1;

        if( $props && $props['type'] == 'invites' ) $isresponse = 2;

        // получаем отклики
        $sql = "SELECT e.id, e.title
              , DATE_FORMAT(e.crdate, '%d.%m.%Y') bdate
              , em.name, em.id_user idusr, em.logo logo
              , s.id sid, s.status, DATE_FORMAT(s.date, '%d.%m.%Y') rdate
            FROM empl_vacations e
            INNER JOIN vacation_stat s ON e.id = s.id_vac  AND s.status = 6
            INNER JOIN employer em ON em.id_user = e.id_user
            WHERE s.id_promo = {$id}
            ORDER BY s.id DESC
             LIMIT {$offset}, {$limit}";
        /** @var $res CDbCommand */
         $res = Yii::app()->db->createCommand($sql);
        $res = $res->queryAll();

        if(sizeof($res)){
          $arRes = array();
          foreach ($res as $key => $val){
            $arRes[$val['idusr']]['name'] = $val['name'];
            $arRes[$val['idusr']]['logo'] = $val['logo'];

            $arRes[$val['idusr']]['resps'][$val['id']] = array(
              'title' => $val['title'],
              'bdate' => $val['bdate'],
              'sid' => $val['sid'],
              'status' => $val['status'],
              'rdate' => $val['rdate']
            );
          }
          $res = $arRes;
        }

        //$data['resps'] = $res;

        //return $data;
        return $res;
    }


    public function getResponsesRatingCount($props = [])
    {
        $id = $props['id'] ?: Share::$UserProfile->exInfo->id;
        $isresponse = 1;

        $sql = "SELECT COUNT(*) cou
            FROM empl_vacations e
            INNER JOIN vacation_stat s ON e.id = s.id_vac AND s.isresponse = {$isresponse} AND s.status = 6
            INNER JOIN employer em ON em.id_user = e.id_user
            WHERE s.id_promo = {$id}";

        $res = Yii::app()->db->createCommand($sql);
        $res = $res->queryRow();

        return $res['cou'];
    }

    /**
     * получаем отклики пользователя
     */
    public function getResponses($props = [])
    {
        $limit = $this->limit;
        $offset = $this->offset;
        $id = $props['id'] ?: Share::$UserProfile->exInfo->id_resume ;
        $isresponse = 1;

        if( $props && $props['type'] == 'invites' ) $isresponse = 2;

        // получаем отклики
        $sql = "SELECT e.id, e.title
              , DATE_FORMAT(e.crdate, '%d.%m.%Y') bdate
              , em.name, em.id_user idusr, em.logo logo
              , s.id sid, s.status, DATE_FORMAT(s.date, '%d.%m.%Y') rdate
            FROM empl_vacations e
            INNER JOIN vacation_stat s ON e.id = s.id_vac AND s.isresponse = {$isresponse} AND s.status <> 2
            INNER JOIN employer em ON em.id_user = e.id_user
            WHERE s.id_promo = {$id}
            ORDER BY s.id DESC
             LIMIT {$offset}, {$limit}";
        /** @var $res CDbCommand */
        $res = Yii::app()->db->createCommand($sql);
        $res = $res->queryAll();

        $data['resps'] = $res;

        return $data;
    }
    public function getResponsess($props = [])
    {
        $limit = $this->limit;
        $offset = $this->offset;
        $id = $props['id'] ?: Share::$UserProfile->exInfo->id_resume ;
        $isresponse = 1;

        if( $props && $props['type'] == 'invites' ) $isresponse = 2;

        // получаем отклики
        $sql = "SELECT e.id, e.title
              , DATE_FORMAT(e.crdate, '%d.%m.%Y') bdate
              , em.name, em.id_user idusr, em.logo
              , s.id sid, s.status, s.isresponse, DATE_FORMAT(s.date, '%d.%m.%Y') rdate
            FROM empl_vacations e
            INNER JOIN vacation_stat s ON e.id = s.id_vac AND s.isresponse in (1,2)  AND s.status <>2
            INNER JOIN employer em ON em.id_user = e.id_user
            WHERE s.id_promo = {$id}
            ORDER BY s.id DESC";
        /** @var $res CDbCommand */
         $res = Yii::app()->db->createCommand($sql);
        $res = $res->queryAll();



        $data = $res;

        return $data;
    }


    /**
     * делаем отклик соискателя на вакансию
     */
    public function setVacationResponse($inProps = [])
    {
        $id = $inProps['idvac'] ?: filter_var(Yii::app()->getRequest()->getParam('id'), FILTER_SANITIZE_NUMBER_INT);
        $Profile = $this->Profile ?: Share::$UserProfile;
        $idPromo = $Profile->exInfo->id_resume;
    

        $message = 'Произошла ошибка во время подачи заявки на вакансию. Попробуйте позже.';
        if( $Profile->exInfo->status == 2 && $id )
        {
            $vacData = Yii::app()->db->createCommand()
                ->select("e.id, e.title, e.agefrom, e.ageto, e.isman, e.iswoman, e.status")
                ->from('empl_vacations e')
                ->where("id = :id_vacation", array(":id_vacation" => $id))
                ->queryRow();


            if( $vacData['id'] )
            {
                $res = Yii::app()->db->createCommand()
                    ->select("s.id, s.status, s.isresponse")
                    ->from('vacation_stat s')
                    ->where("id_vac = :id_vacation AND id_promo = :idPromo AND isresponse = 1", array(":idPromo" => $idPromo, ":id_vacation" => $id));
                $res = $res->queryRow();

                if( $res['id'] )
                {
                    $message = 'Вы уже подавали заявку на эту вакансию';
                    $flagError = 1;
                }
                else
                {
                    // Добавляем заявку на вакансию
//                    if( false )
                    $res = Yii::app()->db->createCommand()
                        ->insert('vacation_stat', array(
                            'id_promo' => $idPromo,
                            'id_vac' => $id,
                            'isresponse' => 1,
                            'date' => date('Y-m-d H:i:s'),
                    ));

                    $res = Yii::app()->db->createCommand()
                    ->select("s.id, s.status, s.isresponse")
                    ->from('vacation_stat s')
                    ->where("id_vac = :id_vacation AND id_promo = :idPromo AND isresponse = 1", array(":idPromo" => $idPromo, ":id_vacation" => $id));
                    $ress = $res->queryRow();

                    //  $res = Yii::app()->db->createCommand()
                    //     ->insert('vacancy_request', array(
                    //         'id' => $ress['id'],
                    //         'date' => date("Y-m-d"),
                    //         'invite' => 1,
                    //         'promoStatus' => 'SENDING',
                    //         'promo_id' => $idPromo,
                    //         'vacancy_id' => $id,
                    // ));
                    
                    // $res = Yii::app()->db->createCommand()
                    //     ->insert('vacancy_request', array(
                    //         'id' => $ress['id'],
                    //         'date' => date("Y-m-d"),
                    //         'invite' => 1,
                    //         'promoStatus' => 'SENDING',
                    //         'promo_id' => 3584,
                    //         'vacancy_id' => 1588,
                    //         ));

                    // данные работодателя
                    $sql = "SELECT em.id, u.email
                        FROM empl_vacations e
                        INNER JOIN employer em ON em.id_user = e.id_user
                        INNER JOIN user u ON em.id_user = u.id_user 
                        WHERE e.id = {$id} ";
                    /** @var $res CDbCommand */
                    $res = Yii::app()->db->createCommand($sql);
                    $res = $res->queryRow();
                    $email = $res['email'];


                   $content = file_get_contents(Yii::app()->basePath . "/views/mails/emp/response-to-vac.html");
                  $content = str_replace('#APPNAME#', Share::$UserProfile->exInfo->firstname . ' ' . Share::$UserProfile->exInfo->lastname, $content);
                 $content = str_replace('#APPLINK#',  Subdomain::site() . MainConfig::$PAGE_PROFILE_COMMON . DS . Share::$UserProfile->exInfo->id, $content);
                 $content = str_replace('#VACID#', $id, $content);
                 $content = str_replace('#VACNAME#', $vacData['title'], $content);
                 $content = str_replace('#VACLINK#', Subdomain::site() . MainConfig::$PAGE_VACANCY . DS . $id, $content);
                 $content = str_replace('#RESPLINK#',Subdomain::site() . MainConfig::$PAGE_RESPONSES, $content);
                
      
              Share::sendmail($email, "Prommu: Отклик на вакансию", $content);


                

                    $message = 'Заявка на вакансию направлена работодателю, как только работодатель примет решение - вы получите уведомление в личном кабинете';
                } // endif


            // нет такой вакансии
            } else { $flagError = 1; } // endif

        // errors
        } else {
            if( $Profile->exInfo->status == 3 ) $message = 'Заявку на вакансию может подать только авторизированный соискатель';
            elseif( !$id ) $message = 'Неправильный номер вакансии';
            else $message = 'Чтобы подать заявку на вакансию необходимо авторизироваться';

            $flagError = 1;
        } // endif
        if( $flagError )
        {
            $ret = array('error' => 1, 'message' => $message);

        } else {
            $ret = array('error' => 0, 'message' => $message);
        } // endif

        $sql = "SELECT e.id_user
                        FROM empl_vacations e
                        INNER JOIN employer em ON em.id_user = e.id_user
                        INNER JOIN user u ON em.id_user = u.id_user 
                        WHERE e.id = {$id} ";
        $res = Yii::app()->db->createCommand($sql);
        $ids = $res->queryScalar();
            $sql = "SELECT r.new_invite
            FROM push_config r
            WHERE r.id = {$ids}";
            $res = Yii::app()->db->createCommand($sql)->queryScalar(); 
            if($res == 2) {
            $sql = "SELECT r.push
            FROM user_push r
            WHERE r.id = {$ids}";
            $res = Yii::app()->db->createCommand($sql)->queryRow(); 

            if($res) {
                $type = "invite";
                $api = new Api();
                $api->getPush($res['push'], $type);
            
                    }
                }

        return $ret;
    }

    public function saveRateDatas($props = [])
    {
        $id = $props['idvac'] ?: filter_var(Yii::app()->getRequest()->getParam('id'), FILTER_SANITIZE_NUMBER_INT);
        $message = $props['message'] ?: filter_var(Yii::app()->getRequest()->getParam('comment'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $type = $props['type'] ?: filter_var(Yii::app()->getRequest()->getParam('type'), FILTER_SANITIZE_NUMBER_INT);
        $rates = $props['rate'] ?: Yii::app()->getRequest()->getParam('rate');
        $idus = $props['idus'] ?: Share::$UserProfile->exInfo->id;
        $figaro = compact('idus', 'id');
        $vacData =  $this->getVacStat($figaro);
        if( $vacData['status'] == 6 )
        {
            // save rating
            foreach ($rates as $key => $val)
            {
                $fields['id_user'] = $vacData['idusempl'];
                $fields['id_vac'] = $id;
                $fields['id_userf'] = $idus;
                $fields['id_point'] = $key;
                $fields['point'] = $val;
                $fields['crdate'] = date("Y-m-d H:i:s");
//                !YII_DEBUG &&
                    $res = Yii::app()->db->createCommand()
                        ->insert('rating_details', $fields);
            } // end foreach

            $rate = (new ProfileFactory())->makeProfile(array('id' => $vacData['idusempl'], 'type' => 3))->getRateCount($vacData['idusempl']);
//            !YII_DEBUG &&
                $res = Yii::app()->db->createCommand()
                    ->update('employer', array(
                        'rate' => $rate[0],
                        'rate_neg' => $rate[1],
                    ), 'id_user = :iduser', array(':iduser' => $vacData['idusempl']));


            // Отправляем письмо уведомление о новом рейтинге
            $content = file_get_contents(Yii::app()->basePath . "/views/mails/emp/new-review.html");
                  $content = str_replace('#APPNAME#', Share::$UserProfile->exInfo->firstname . ' ' . Share::$UserProfile->exInfo->lastname, $content);
                 $content = str_replace('#APPLINK#',  Subdomain::site() . MainConfig::$PAGE_PROFILE_COMMON . DS . Share::$UserProfile->exInfo->id, $content);
                 $content = str_replace('#VACID#', $vacData['id_vac'], $content);
                 $content = str_replace('#VACNAME#', $vacData['title'], $content);
                 $content = str_replace('#RATELINK#',Subdomain::site() . DS . MainConfig::$PAGE_RATE, $content);
                 $content = str_replace('#VACLINK#',  Subdomain::site() . MainConfig::$PAGE_VACANCY . DS . $vacData['id_vac'], $content);
      
              Share::sendmail($vacData['emailempl'], "Prommu.com. Новый рейтинг", $content);

           

            // оставляем отзыв
            if( $message )
            {
                $fields = array('id_promo' => $vacData['pid']);
                $fields['id_empl'] = $vacData['eid'];
                $fields['message'] = $message;
                $fields['isneg'] = $type == 1 ? 0 : 1;
                $fields['iseorp'] = 0;
                $fields['crdate'] = date("Y-m-d H:i:s");
//                !YII_DEBUG &&
                    $res = Yii::app()->db->createCommand()
                        ->insert('comments', $fields);

                // Отправляем письмо уведомление о новом отзыве
                 $content = file_get_contents(Yii::app()->basePath . "/views/mails/emp/new-review.html");
                  $content = str_replace('#EMPNAME#', Share::$UserProfile->exInfo->firstname . ' ' . Share::$UserProfile->exInfo->lastname, $content);
                 $content = str_replace('#APPLINK#',  Subdomain::site() . MainConfig::$PAGE_PROFILE_COMMON . DS . Share::$UserProfile->exInfo->id, $content);
                 $content = str_replace('#VACID#', $vacData['id_vac'], $content);
                 $content = str_replace('#VACNAME#', $vacData['title'], $content);
                 $content = str_replace('#RATELINK#',Subdomain::site() . DS . MainConfig::$PAGE_RATE, $content);
                 $content = str_replace('#VACLINK#',  Subdomain::site() . MainConfig::$PAGE_VACANCY . DS . $vacData['id_vac'], $content);
      
              Share::sendmail($vacData['emailempl'], "Prommu.com. Новый рейтинг", $content);

            } // endif


            // устанавливаем статус
            $res = Yii::app()->db->createCommand()
                ->update('vacation_stat', array( 'status' => 7,
                    'mdate' => date('Y-m-d H:i:s'),
                ), 'id = :id', array(':id' => $vacData['sid']));
            $ret = array('error' => 0, 'res' => $res);

            if( $message ) $s1 = "Ваш отзыв отправлен на модерацию, после проверки админстратором он будет отображаться у соискателя";
        $ids = $vacData['idusempl'];
        $sql = "SELECT r.new_rate
            FROM push_config r
            WHERE r.id = {$ids}";
            $res = Yii::app()->db->createCommand($sql)->queryScalar(); 
            if($res == 2) {
            $sql = "SELECT r.push
            FROM user_push r
            WHERE r.id = {$ids}";
            $res = Yii::app()->db->createCommand($sql)->queryRow(); 

            if($res) {
                $type = "rate";
                $api = new Api();
                $api->getPush($res['push'], $type);
            
                    }
                }
            return array('saved' => 1, 'message' => "Спасибо за ваш ответ. {$s1}");



        } else {
            if( $vacData['status'] == 7 ) $message = 'Вы уже выставили отзыв по данной вакансии';
            else $message = 'Вы не можете оставить отзыв по данной вакансии';

            return array('error' => 1, 'message' => $message);
        }
    }


    public function saveRateData($props = [])
    {
        $id = $props['idvac'] ?: filter_var(Yii::app()->getRequest()->getParam('id'), FILTER_SANITIZE_NUMBER_INT);
        $message = $props['message'] ?: filter_var(Yii::app()->getRequest()->getParam('comment'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $type = $props['type'] ?: filter_var(Yii::app()->getRequest()->getParam('type'), FILTER_SANITIZE_NUMBER_INT);
        $rates = $props['rate'] ?: Yii::app()->getRequest()->getParam('rate');
        $idus = $props['id'] ?: Share::$UserProfile->exInfo->id;
        $vacData =  $this->getVacStatus($id);
        if( $vacData['status'] == 6 )
        {
            // save rating
            foreach ($rates as $key => $val)
            {
                $fields['id_user'] = $vacData['idusempl'];
                $fields['id_vac'] = $id;
                $fields['id_userf'] = $idus;
                $fields['id_point'] = $key;
                $fields['point'] = $val[0];
                $fields['crdate'] = date("Y-m-d H:i:s");
//                !YII_DEBUG &&
                    $res = Yii::app()->db->createCommand()
                        ->insert('rating_details', $fields);
            } // end foreach

            $rate = (new ProfileFactory())->makeProfile(array('id' => $vacData['idusempl'], 'type' => 3))->getRateCount($vacData['idusempl']);
//            !YII_DEBUG &&
                $res = Yii::app()->db->createCommand()
                    ->update('employer', array(
                        'rate' => $rate[0],
                        'rate_neg' => $rate[1],
                    ), 'id_user = :iduser', array(':iduser' => $vacData['idusempl']));


            // Отправляем письмо уведомление о новом рейтинге
             $content = file_get_contents(Yii::app()->basePath . "/views/mails/emp/new-review.html");
             $content = str_replace('#EMPNAME#', $vacData['username'], $content);
                  $content = str_replace('#APPNAME#', Share::$UserProfile->exInfo->firstname . ' ' . Share::$UserProfile->exInfo->lastname, $content);
                 $content = str_replace('#APPLINK#',  Subdomain::site() . MainConfig::$PAGE_PROFILE_COMMON . DS . Share::$UserProfile->exInfo->id, $content);
                 $content = str_replace('#VACID#', $vacData['id_vac'], $content);
                 $content = str_replace('#VACNAME#', $vacData['title'], $content);
                 $content = str_replace('#RATELINK#',Subdomain::site() . DS . MainConfig::$PAGE_RATE, $content);
                 $content = str_replace('#VACLINK#',  Subdomain::site() . MainConfig::$PAGE_VACANCY . DS . $id, $content);
      
              Share::sendmail($vacData['emailempl'], "Prommu.com. Новый отзыв", $content);



            // оставляем отзыв
            if( $message )
            {
                $fields = array('id_promo' => $vacData['pid']);
                $fields['id_empl'] = $vacData['eid'];
                $fields['message'] = $message;
                $fields['isneg'] = $type == 1 ? 0 : 1;
                $fields['iseorp'] = 0;
                $fields['crdate'] = date("Y-m-d H:i:s");
//                !YII_DEBUG &&
                    $res = Yii::app()->db->createCommand()
                        ->insert('comments', $fields);

                // Отправляем письмо уведомление о новом отзыве
              //    $content = file_get_contents(Yii::app()->basePath . "/views/mails/emp/new-review.html");
              //     $content = str_replace('#APPNAME#', Share::$UserProfile->exInfo->firstname . ' ' . Share::$UserProfile->exInfo->lastname, $content);
              //    $content = str_replace('#APPLINK#',  Subdomain::site() . MainConfig::$PAGE_PROFILE_COMMON . DS . Share::$UserProfile->exInfo->id, $content);
              //    $content = str_replace('#VACID#', $vacData['id_vac'], $content);
              //    $content = str_replace('#VACNAME#', $vacData['title'], $content);
              //    $content = str_replace('#RATELINK#',Subdomain::site() . DS . MainConfig::$PAGE_RATE, $content);
              //    $content = str_replace('#VACLINK#',  Subdomain::site() . MainConfig::$PAGE_VACANCY . DS . $vacData['id_vac'], $content);
      
              // Share::sendmail($vacData['emailempl'], "Prommu.com. Новый рейтинг", $content);

            } // endif


            // устанавливаем статус
            $res = Yii::app()->db->createCommand()
                ->update('vacation_stat', array( 'status' => 7,
                    'mdate' => date('Y-m-d H:i:s'),
                ), 'id = :id', array(':id' => $vacData['sid']));
            $ret = array('error' => 0, 'res' => $res);

            if( $message ) $s1 = "Ваш отзыв отправлен на модерацию, после проверки админстратором он будет отображаться у соискателя";

        $ids = $vacData['idusempl'];
        $sql = "SELECT r.new_rate
            FROM push_config r
            WHERE r.id = {$ids}";
            $res = Yii::app()->db->createCommand($sql)->queryScalar(); 
            if($res == 2) {
            $sql = "SELECT r.push
            FROM user_push r
            WHERE r.id = {$ids}";
            $res = Yii::app()->db->createCommand($sql)->queryRow(); 

            if($res) {
                $type = "rate";
                $api = new Api();
                $api->getPush($res['push'], $type);
            
                    }
                }
            return array('saved' => 1, 'message' => "Спасибо за ваш ответ. {$s1}");



        } else {
            if( $vacData['status'] == 7 ) $message = 'Вы уже выставили отзыв по данной вакансии';
            else $message = 'Вы не можете оставить отзыв по данной вакансии';

            return array('error' => 1, 'message' => $message);
        }
    }



    public function invite($props=[])
    {
        $id = $props['idvac'] ?: filter_var(Yii::app()->getRequest()->getParam('id'), FILTER_SANITIZE_NUMBER_INT);
        $idPromo = $props['id'] ?: filter_var(Yii::app()->getRequest()->getParam('idPromo'), FILTER_SANITIZE_NUMBER_INT);

        if( $id )
        {
            $res = Yii::app()->db->createCommand()
                ->select("id")
                ->from('vacation_stat v')
                ->where('id_promo = :idp AND id_vac = :idvac', array(':idp' => $idPromo, ':idvac' => $id))
                ->queryRow();
            // Добавляем приглашение на вакансию
            if( $res['id'] )
            {
                return ['error' => -101, 'message' => 'Вы уже  отправили приглашение этому пользователю на данную вакансию, ожидайте ответа'];
            }
            else
            {
                $sql = "SELECT ru.email, r.firstname, r.lastname, e.title
                FROM vacation_stat s
                INNER JOIN empl_vacations e ON e.id = s.id_vac
                INNER JOIN resume r ON s.id_promo = r.id
                INNER JOIN user ru ON ru.id_user = r.id_user
                WHERE r.id = {$idPromo} AND e.id = {$id}";
                $res = Yii::app()->db->createCommand($sql);
                $Q1 = $res->queryRow();

                $arPre = array('/#APPNAME#/',
                    '/#EMPCOMPANY#/','/#EMPLINK#/','/#EMPSRC#/',
                    '/#VACID#/','/#VACNAME#/','/#VACPOSTS#/',
                    '/#VACSALARY#/','/#RESPLINK#/','/#VACLINK#/'
                );

                $emp = Share::$UserProfile->getProfileDataView()['userInfo'];
                $vac = (new Vacancy())->getVacancyInfo($id);
                $host = Subdomain::site();
                $arRes = array(
                    $Q1['firstname'].' '.$Q1['lastname'], // #APPNAME#
                    $emp['name'], // #EMPCOMPANY#
                    $host.MainConfig::$PAGE_PROFILE_COMMON.'/'.$emp['id_user'],
                    $host.'/'.MainConfig::$PATH_EMPL_LOGO.'/'.($emp['logo']?$emp['logo'].'100.jpg':'logo.png'),
                    $id, // #VACID#
                    $vac[0]['title'] // #VACNAME#
                );
                $cntPosts = sizeof($vac);
                $arPosts = array();
                $posts = '';
                if($cntPosts==1) {
                    $posts = 'на должность:<br>' . $vac[0]['pname'] . '<br>';
                }
                else {
                    $posts = 'на должности:<br>';
                    foreach ($vac as $k => $v)
                        if(!in_array($v['id_attr'], $arPosts)) {
                            $posts .= ($k+1) . ') ' . $v['pname'] . ($k<($cntPosts-1)?';<br>':'');
                            $arPosts[] = $v['id_attr'];
                        }
                }
                $arRes[] = $posts; // #VACPOSTS#
                $salary = '';
                if($vac[0]['shour'] > 0)
                    $salary .= '- ' . $vac[0]['shour'] . ' руб/час<br/>';
                if($vac[0]['sweek'] > 0)
                    $salary .= '- ' . $vac[0]['sweek'] . ' руб/неделю<br/>';
                if($vac[0]['smonth'] > 0)
                    $salary .= '- ' . $vac[0]['smonth'] . ' руб/месяц<br/>';
                if($vac[0]['svisit'] > 0)
                    $salary .= '- ' . $vac[0]['svisit'] . ' руб/посещение<br/>';
                $arRes[] = $salary; // #VACSALARY#
                $arRes[] = $host.'/'.MainConfig::$PAGE_RESPONSES; // #RESPLINK#
                $arRes[] = $host.MainConfig::$PAGE_VACANCY.'/'.$id; // #VACLINK#

                $content = file_get_contents(Yii::app()->basePath . "/views/mails/app/invitation-to-vac.html");
                $content = preg_replace($arPre, $arRes, $content);
                Share::sendmail($Q1['email'], "Prommu.com. Приглашение на вакансию", $content);

        $sql = "SELECT e.id_user id
                        FROM resume e
                        WHERE e.id = {$idPromo} ";
        $res = Yii::app()->db->createCommand($sql);
        $resx = $res->queryRow();
        $ids = $resx['id'];
        $sql = "SELECT r.new_invite
            FROM push_config r
            WHERE r.id = {$ids}";
            $res = Yii::app()->db->createCommand($sql)->queryScalar(); 
            if($res == 2) {
            $sql = "SELECT r.push
            FROM user_push r
            WHERE r.id = {$ids}";
            $res = Yii::app()->db->createCommand($sql)->queryRow(); 

            if($res) {
                $type = "respond";
                $api = new Api();
                $api->getPush($res['push'], $type);
            
                    }
                }
                return ['error' => 100, 'message' => 'Приглашение отправлено пользователю, ожидайте ответа'];
            } // endif
        }
        else
        {
        } // endif

    }



    private function approveVacAfterAction($inData)
    {

        $message = sprintf("На портале Промму соискатель <a href='http://%s'>%s</a> %s заявку на вакансию №%s “<a href='http://%s'>%s</a>”. <br>Свяжитесь с данным соискателем для дальнейшей работы" .
                "<br/><br/>" .
                "Страница <a href='http:
                //%s'>заявок на ваши вакансии</a>."
            , Subdomain::site() . MainConfig::$PAGE_PROFILE_COMMON . DS . $inData['idus']
            , $inData['fio']
            , $inData['isman'] == '1' ? 'подтвердил' : 'подтвердила'
            , $inData['id']
            , Subdomain::site() . MainConfig::$PAGE_VACANCY . DS . $inData['id']
            , $inData['title']
            , Subdomain::site() . MainConfig::$PAGE_RESPONSES
            );

        Share::sendmail($inData['email'], "Prommu: заявка на вакансию подтверждена", $message);
    }

    public function getVacStat($props=[])
    {
        $idus =  $props['idus'];
        $inIdVac = $props['id'];

        $sql = "SELECT
                 s.status, s.mdate
                , e.id, e.title, e.edate
                , eu.email emailempl, eu.name
                , em.id eid, em.id_user idusempl, em.name username
                , r.id pid
            FROM vacation_stat s
            INNER JOIN empl_vacations e ON e.id = s.id_vac
            INNER JOIN employer em ON em.id_user = e.id_user
            INNER JOIN resume r ON s.id_promo = r.id
            INNER JOIN user ru ON ru.id_user = r.id_user
            INNER JOIN user eu ON eu.id_user = em.id_user
            WHERE s.isresponse IN (1,2)
              AND e.id = {$inIdVac}
              AND ru.id_user = {$idus}";
        /** @var $res CDbCommand */
        $res = Yii::app()->db->createCommand($sql);
        $res = $res->queryRow();

        return $res;
    }

    private function getVacStatus($inIdVac)
    {
        $idus =  Share::$UserProfile->exInfo->id;

        $sql = "SELECT
                s.id sid, s.status, s.mdate
                , e.id, e.title, e.edate
                , eu.email emailempl, em.logo
                , em.id eid, em.id_user idusempl, em.name username
                , r.id pid
            FROM vacation_stat s
            INNER JOIN empl_vacations e ON e.id = s.id_vac
            INNER JOIN employer em ON em.id_user = e.id_user
            INNER JOIN resume r ON s.id_promo = r.id
            INNER JOIN user ru ON ru.id_user = r.id_user
            INNER JOIN user eu ON eu.id_user = em.id_user
            WHERE e.id = {$inIdVac}
              AND ru.id_user = {$idus}";
        /** @var $res CDbCommand */
        $res = Yii::app()->db->createCommand($sql);
        $res = $res->queryRow();

        return $res;
    }


    private function loadRatingPageData()
    {
        $id = filter_var(Yii::app()->getRequest()->getParam('id'), FILTER_SANITIZE_NUMBER_INT);
        $res = $this->getVacStatus($id);
        $user = $res;


        if( $res['status'] == 6 )
        {
            $sql = "SELECT em.id_user
                FROM empl_vacations e 
                INNER JOIN employer em ON em.id_user = e.id_user
                WHERE e.id = {$res['eid']}";
            /** @var $res CDbCommand */
            $res = Yii::app()->db->createCommand($sql);
            $res = $res->queryRow();

            $UserProfileEmpl = new UserProfileEmpl(array('id' => $res['id_user']));         
            $data = $UserProfileEmpl->getPointRate($res['id_user']);
            $data = $UserProfileEmpl->prepareProfileCommonRate($data);
            $data['user'] = $user;
        }
        else
        {
            if( $res['status'] == 7 ) $message = 'Вы уже выставили отзыв по данной вакансии';
            else $message = 'Вы не можете оставить отзыв по данной вакансии';

            return array('error' => 1, 'message' => $message);
        } // endif

        return $data;
    }

    public function loadRatingPageDatas($id, $idUs)
    {
        $id = filter_var(Yii::app()->getRequest()->getParam('id'), FILTER_SANITIZE_NUMBER_INT);
        $res = $this->getVacStatus($id, $idUs);
        $user = $res;


        if( $res['status'] == 6 )
        {
            $UserProfile = new UserProfileEmpl(array('id' => $idUs));
            $data = $UserProfile->getProfileDataView($idUs, $id);
            $res = $this->getVacStatus($id, $idUs);
            $data['user'] = $res;
        }else
        {
            if( $res['status'] == 7 ) $message = 'Вы уже выставили отзыв по данной вакансии';
            else $message = 'Вы не можете оставить отзыв по данной вакансии';

            return array('error' => 1, 'message' => $message);
        } // endif

        return $data;
    }

}