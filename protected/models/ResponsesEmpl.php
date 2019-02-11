<?php
/**
 * Date: 15.04.2016
 *
 * Модель откликов
 */

class ResponsesEmpl extends Responses
{

    /**
     * устанавливаем рейтинг соискателю
     */
    public function setRate()
    {
        return $this->loadRatingPageData();
    }



    /**
     * устанавливаем статус отклика
     */
    public function setResponseStatus($props=[])
    {
        if( Yii::app()->getRequest()->requestType != 'POST' ) $ret = array('error' => 1);
        $idres = $props['idres'] ?: filter_var(Yii::app()->getRequest()->getParam('idres'), FILTER_SANITIZE_NUMBER_INT);
        $status = $props['status'] ?: filter_var(Yii::app()->getRequest()->getParam('s'), FILTER_SANITIZE_NUMBER_INT);
        $idus = $props['idus'] ?: Share::$UserProfile->exInfo->id;


        // проверяем, что вакансия пренадлежит этому пользователю
        $sql = "SELECT e.id, e.title, e.id_user, s.id_promo promo, r.firstname, r.lastname
               , u.email
               , s.id sid, s.status
            FROM empl_vacations e
            INNER JOIN vacation_stat s ON e.id = s.id_vac AND s.id = {$idres}
            INNER JOIN resume r ON r.id = s.id_promo
            INNER JOIN user u ON u.id_user = r.id_user
            WHERE e.id_user = {$idus}";
        /** @var $res CDbCommand */
        $vacData = Yii::app()->db->createCommand($sql);
        $vacData = $vacData->queryRow();

        // if response exists
        if( $vacData['id'] )
        {
            $res = Yii::app()->db->createCommand()
                ->update('vacation_stat', array( 'status' => $status, 'mdate' => date('Y-m-d H:i:s')
                ), 'id = :id', array(':id' => $idres));
            $ret = array('error' => 0, 'res' => $res);
            $ret = array_merge($ret, $this->getVacancyResponsesCounts($vacData['id']));

            if(!($vacData['status'] == 3) && $status == 3) {
                $content = file_get_contents(Yii::app()->basePath . "/views/mails/app/emp-failure.html");
                $content = str_replace('#APPNAME#', $vacData['firstname'].' '.$vacData['lastname'], $content);
                $content = str_replace('#EMPCOMPANY#',  Share::$UserProfile->exInfo->name, $content);
                $content = str_replace('#EMPLINK#',  Subdomain::$HOST . MainConfig::$PAGE_PROFILE_COMMON . DS . Share::$UserProfile->exInfo->id, $content);
                $content = str_replace('#VACSEARCH#', Subdomain::$HOST . MainConfig::$PAGE_SEARCH_VAC, $content);
                $content = str_replace('#VACNAME#', $vacData['title'], $content);
                $content = str_replace('#VACLINK#', Subdomain::$HOST . MainConfig::$PAGE_VACANCY . DS . $vacData['id'], $content);
                $content = str_replace('#RESPLINK#',Subdomain::$HOST . MainConfig::$PAGE_RESPONSES, $content);
                        
                Share::sendmail($vacData['email'], "Prommu: Отклонение заявки", $content);
            }
        
           
            if(
                ( !($vacData['status'] == 4) && $status == 4 )
                ||
                ( !($vacData['status'] == 5) && $status == 5 )
            ) 
            {
                $this->approveVacAfterAction($vacData);

            $idPromo = $vacData['promo'];
            $sql = "SELECT r.id_user
                FROM resume r
                WHERE r.id = {$idPromo}";
                $res = Yii::app()->db->createCommand($sql);
                $id = $res->queryScalar();

            $ids = $id;
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
        }
        else
        {
            $ret = array('error' => 1);
        } // endif

        return $ret;
    }


    /**
     * получаем кол-во откликов пользователя
     */
    public function getResponsesCount($props = [])
    {
        $idus = $props['id'] ?: Share::$UserProfile->exInfo->id;


        $sql = "SELECT COUNT(*) cou
            FROM empl_vacations e
            INNER JOIN vacation_stat s ON e.id = s.id_vac AND s.isresponse IN(1,2)
            INNER JOIN resume r ON s.id_promo = r.id
            WHERE e.id_user = {$idus}";
        /** @var $res CDbCommand */
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
        $idus = $props['id'] ?: Share::$UserProfile->exInfo->id;

        // данные работодателя
        $sql = "SELECT DISTINCT e.id, e.title, e.status vstatus
              , DATE_FORMAT(e.crdate, '%d.%m.%Y') bdate
              , r.id_user idusr, r.firstname, r.lastname, r.photo, r.isman
              , s.id sid, s.status, s.isresponse, DATE_FORMAT(s.date, '%d.%m.%Y') rdate
              , rd.id_vac
            FROM empl_vacations e
            INNER JOIN vacation_stat s ON e.id = s.id_vac AND s.isresponse IN(1,2) 
            INNER JOIN resume r ON s.id_promo = r.id
            LEFT JOIN rating_details rd ON rd.id_user = r.id_user AND rd.id_vac = e.id
            WHERE e.id_user = {$idus}
            ORDER BY s.id DESC
            LIMIT {$offset}, {$limit}";
        /** @var $res CDbCommand */
        $res = Yii::app()->db->createCommand($sql);
        $res = $res->queryAll();

        // NEW 
       /*if(sizeof($res)){
          $arRes = array();
          foreach ($res as $key => $val){
            if($val['status'] == 3) continue;
            $arRes[$val['id']] = array(
              'title' => $val['title'],
              'status' => $val['vstatus'],
              'bdate' => $val['bdate']
            );
            $arRes[$val['id']]['resps'][$val['idusr']] = array(
              'name' => $val['firstname'] . ' ' . $val['lastname'],
              'photo' => $val['photo'],
              'sid' => $val['sid'],
              'status' => $val['status'],
              'isresponse' => $val['isresponse'],
              'rdate' => $val['rdate'],
              'id_vac' => $val['id_vac'],
              'sex' => $val['isman']
            );
          }
          $res = $arRes;
        }*/
        $data['resps'] = $res;

        return $data;

    }


       public function getResponsesRating($props = [])
    {
        $limit = $this->limit;
        $offset = $this->offset;
        $idus = $props['id'] ?: Share::$UserProfile->exInfo->id;

        // данные работодателя
        $sql = "SELECT DISTINCT e.id, e.title, e.status vstatus
              , DATE_FORMAT(e.crdate, '%d.%m.%Y') bdate
              , r.id_user idusr, r.firstname, r.lastname, r.photo, r.isman
              , s.id sid, s.status, s.isresponse, DATE_FORMAT(s.date, '%d.%m.%Y') rdate
              , rd.id_vac
            FROM empl_vacations e
            INNER JOIN vacation_stat s ON e.id = s.id_vac AND s.status = 6 
            INNER JOIN resume r ON s.id_promo = r.id
            LEFT JOIN rating_details rd ON rd.id_user = r.id_user AND rd.id_vac = e.id
            WHERE e.id_user = {$idus}
            ORDER BY s.id DESC
            LIMIT {$offset}, 10";
        /** @var $res CDbCommand */
        $res = Yii::app()->db->createCommand($sql);
        $res = $res->queryAll();

        // NEW 
        if(sizeof($res)){
            $arRes = array();
            foreach ($res as $key => $val){
                $arRes[$val['id']]['title'] = $val['title'];
                $arRes[$val['id']]['status'] = $val['vstatus'];
                $arRes[$val['id']]['bdate'] = $val['bdate'];

                $arRes[$val['id']]['resps'][$val['idusr']] = array(
                  'name' => $val['firstname'] . ' ' . $val['lastname'],
                  'photo' => $val['photo'],
                  'sid' => $val['sid'],
                  'status' => $val['status'],
                  'isresponse' => $val['isresponse'],
                  'rdate' => $val['rdate'],
                  'id_vac' => $val['id_vac'],
                  'sex' => $val['isman']
                );
            }
            $res = $arRes;
        }

        return $res;

    }

    public function getResponsesRatingCount($props = [])
    {
        $idus = $props['id'] ?: Share::$UserProfile->exInfo->id;

        $sql = "SELECT COUNT(*) cou
            FROM empl_vacations e
            INNER JOIN vacation_stat s ON e.id = s.id_vac AND s.isresponse IN(6) 
            INNER JOIN resume r ON s.id_promo = r.id
            LEFT JOIN rating_details rd ON rd.id_user = r.id_user AND rd.id_vac = e.id
            WHERE e.id_user = {$idus}";

        $res = Yii::app()->db->createCommand($sql);
        $res = $res->queryRow();

        return $res['cou'];
    }

    public function getResponsess($props = [])
    {
        $limit = $this->limit;
        $offset = $this->offset;
        $idus = $props['id'] ?: Share::$UserProfile->exInfo->id;

        // данные работодателя
        $sql = "SELECT DISTINCT e.id, e.title, e.status vstatus
              , DATE_FORMAT(e.crdate, '%d.%m.%Y') bdate
              , r.id_user idusr, r.firstname, r.lastname, r.photo
              , s.id sid, s.status, s.isresponse, DATE_FORMAT(s.date, '%d.%m.%Y') rdate
              , rd.id_vac
            FROM empl_vacations e
            INNER JOIN vacation_stat s ON e.id = s.id_vac AND s.isresponse IN(1,2)
            INNER JOIN resume r ON s.id_promo = r.id
            LEFT JOIN rating_details rd ON rd.id_user = r.id_user AND rd.id_vac = e.id
            WHERE e.id_user = {$idus}
            ORDER BY s.id DESC";
        /** @var $res CDbCommand */
        $res = Yii::app()->db->createCommand($sql);
        $res = $res->queryAll();

        //$data = $res;
        $data['resps'] = $res;
        return $data;
    }




    /**
     * Получаем кол-во новых заявок на вакансии
     */
    public function getNewResponses()
    {
        $idus = $this->Profile->id;

        // данные работодателя
        $sql = "SELECT COUNT(*) cou
            FROM empl_vacations e
            INNER JOIN vacation_stat s ON e.id = s.id_vac AND s.isresponse = 1 AND s.status = 0
            WHERE e.id_user = {$idus}";
        /** @var $res CDbCommand */
        $res = Yii::app()->db->createCommand($sql);
        $res = $res->queryRow();

        return $res['cou'];
    }



    /**
     * Получение откликов при просмотре вакансии
     */
    public function getVacancyResponses($inIdVac, $inStatus)
    {
        if( $inIdVac )
        {
            // получение данных вакансии
            $sql = "SELECT DISTINCT e.id, e.title, e.status vstatus
                  , DATE_FORMAT(e.crdate, '%d.%m.%Y') bdate
                  , r.id_user idusr, r.firstname, r.lastname, r.photo
                  , s.id sid, s.status, s.isresponse, DATE_FORMAT(s.date, '%d.%m.%Y') rdate
                FROM empl_vacations e
                INNER JOIN vacation_stat s ON e.id = s.id_vac AND s.isresponse IN(1,2) AND s.status IN (" . join(',', $inStatus) . ")
                INNER JOIN resume r ON s.id_promo = r.id
                WHERE e.id = {$inIdVac}
                ORDER BY s.id DESC 
                LIMIT {$this->offset}, {$this->limit} ";
            /** @var $res CDbCommand */
            $res = Yii::app()->db->createCommand($sql);
            $res = $res->queryAll();

            $data = array_fill(0, 4, array());
            $dataCounts = array_fill(0, 4, 0);
            foreach ($res as $key => $val)
            {
                if( $val['status'] == 1 ) { 
                    $data[1][$val['idusr']] = $val; 
                    $data[1][$val['idusr']]['logo'] = (new ViewModel)->getHtmlLogo($val['photo'], ViewModel::$LOGO_TYPE_APPLIC);
                }
                elseif( $val['status'] == 3 && $val['isresponse'] == 2 ) { 
                    $data[5][$val['idusr']] = $val; 
                    $data[5][$val['idusr']]['logo'] = (new ViewModel)->getHtmlLogo($val['photo'], ViewModel::$LOGO_TYPE_APPLIC);
                }
                elseif( $val['status'] == 3 ) { 
                    $data[3][$val['idusr']] = $val; 
                    $data[3][$val['idusr']]['logo'] = (new ViewModel)->getHtmlLogo($val['photo'], ViewModel::$LOGO_TYPE_APPLIC);
                }
                elseif( $val['status'] == 5 ) { 
                    $data[8][$val['idusr']] = $val; 
                    $data[8][$val['idusr']]['logo'] = (new ViewModel)->getHtmlLogo($val['photo'], ViewModel::$LOGO_TYPE_APPLIC);
                }
                elseif( in_array($val['status'], [0,2,4,6,7]) ) { 
                    $data[4][$val['idusr']] = $val;
                    $data[4][$val['idusr']]['logo'] = (new ViewModel)->getHtmlLogo($val['photo'], ViewModel::$LOGO_TYPE_APPLIC);
                }
                
            } // end foreach

            return array('responses' => $data);
        }
        else
        {
            return array('responses' => array());
        } // endif
    }

        public function getVR($inIdVac, $inStatus)
    {
        if( $inIdVac )
        {
            // получение данных вакансии
            $sql = "SELECT DISTINCT e.id, e.title, e.status vstatus
                  , DATE_FORMAT(e.crdate, '%d.%m.%Y') bdate
                  , r.id_user idusr, r.firstname, r.lastname
                  , s.id sid, s.status, s.isresponse, DATE_FORMAT(s.date, '%d.%m.%Y') rdate
                FROM empl_vacations e
                INNER JOIN vacation_stat s ON e.id = s.id_vac AND s.isresponse IN(1,2) AND s.status IN (" . join(',', $inStatus) . ")
                INNER JOIN resume r ON s.id_promo = r.id
                WHERE e.id = {$inIdVac}
                ORDER BY s.id DESC 
                LIMIT {$this->offset}, {$this->limit} ";
            /** @var $res CDbCommand */
            $res = Yii::app()->db->createCommand($sql);
            $res = $res->queryAll();

            $data = array_fill(0, 4, array());
            $dataCounts = array_fill(0, 4, 0);
            foreach ($res as $key => $val)
            {
                if( in_array($val['status'], [5]) ) { $data[0] = $val; }
            } // end foreach

            return  $data;
        }
        else
        {
            return array('responses' => array());
        } // endif
    }

    /**
     * Получаем кол-ва откликов по статусам
     */
    public function getVacancyResponsesCounts($inIdVac)
    {
        if( $inIdVac )
        {
            // получение кол-ва откликов
            $sql = "SELECT s.status, s.isresponse, COUNT(e.id) cou
                FROM empl_vacations e
                INNER JOIN vacation_stat s ON e.id = s.id_vac AND s.isresponse IN(1,2) 
                INNER JOIN resume r ON s.id_promo = r.id
                WHERE e.id = {$inIdVac}
                GROUP BY s.status
                ORDER BY s.status";
            /** @var $res CDbCommand */
            $res = Yii::app()->db->createCommand($sql);
            $res = $res->queryAll();

            $data = array_fill(0, 6, 0);
            foreach ($res as $key => $val)
            {
                if( $val['status']==1 ) $data[1] += $val['cou'];
                elseif( $val['status']==3 && $val['isresponse']==2 ) $data[5] += $val['cou'];
                elseif( $val['status']==3 ) $data[3] += $val['cou'];
                elseif( $val['status']==5 ) $data[8] += $val['cou'];
                elseif( in_array($val['status'], [0,4,6,7]) ) $data[4] += $val['cou'];
            } // end foreach

            return array('counts' => $data);
        }
        else
        {
            return array('counts' => array());
        } // endif
    }

    public function saveRateDatas($props=[])
    {
        $message = $props['message'];
        $type = $props['type'];
        $rates = $props['rate'];
        $id = $props['idvac'];
        $idUs = $props['idusp'];
        $idus = $props['idus'];
        $figaro = compact('idUs', 'id');
        $vacData = $this->getVacStat($figaro);
        if( !$vacData['id_user'] )
        {
            // save rating
            foreach ($rates as $key => $val)
            {
                $fields['id_user'] = $vacData['iduspromo'];
                $fields['id_vac'] = $id;
                $fields['id_userf'] = $idus ?: Share::$UserProfile->exInfo->id;
                $fields['id_point'] = $key;
                $fields['point'] = $val;
                $fields['crdate'] = date("Y-m-d H:i:s");
//                !YII_DEBUG &&
                    $res = Yii::app()->db->createCommand()
                        ->insert('rating_details', $fields);
            } // end foreach


            // Отправляем письмо уведомление о новом рейтинге
            $emailMessage = sprintf("Вы получили новый рейтинг на сервисе &laquo;Prommu.com&raquo;.
                    <br>
                    Нажмите на <a href='%s'>ссылку</a>, чтобы перейти на страницу рейтинга.
                    "
                , Subdomain::site() . DS . MainConfig::$PAGE_RATE
            );
            Share::sendmail($vacData['emailpromo'], "Prommu.com. Новый рейтинг", $emailMessage);


            // *** Обновляем общий рейтинг соискателя в таблице resume ***
//            $rate = (new UserProfileApplic())->getRateCount($vacData['iduspromo']);
            $rate = (new ProfileFactory())->makeProfile(array('id' => $vacData['iduspromo'], 'type' => 2))->getRateCount($vacData['iduspromo']);

//            !YII_DEBUG &&
                $res = Yii::app()->db->createCommand()
                    ->update('resume', array(
                        'rate' => $rate[0],
                        'rate_neg' => $rate[1],
                    ), 'id_user = :iduser', array(':iduser' => $vacData['iduspromo']));



            // оставляем отзыв
            if( $message )
            {
                $fields = array('id_promo' => $vacData['pid']);
                $fields['id_empl'] = $idus ?: Share::$UserProfile->exInfo->eid;
                $fields['message'] = $message;
                $fields['isneg'] = $type == 1 ? 0 : 1;
                $fields['iseorp'] = 1;
                $fields['crdate'] = date("Y-m-d H:i:s");
//                !YII_DEBUG &&
                    $res = Yii::app()->db->createCommand()
                        ->insert('comments', $fields);

                // Отправляем письмо уведомление о новом отзыве
                $emailMessage = sprintf("Вы получили новый отзыв на сервисе &laquo;Prommu.com&raquo;.
                        <br>
                        Нажмите на <a href='%s'>ссылку</a>, чтобы перейти на страницу отзывов.
                        "
                    , Subdomain::site() . DS . MainConfig::$PAGE_COMMENTS
                );
                Share::sendmail($vacData['emailpromo'], "Prommu.com. Новый отзыв", $emailMessage);
            } // endif

            if( $message ) $s1 = "Ваш отзыв оптравлен на модерацию, после проверки админстратором он будет отображаться у соискателя";
            $ids = $vacData['iduspromo'];
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
            $message = 'Вы уже оставили отзыв данному соискателю по этой вакансии';
//            if( $vacData['status'] == 7 )
//            else $message = 'Вы не можете оставить отзыв по данной вакансии';

            return array('error' => 1, 'message' => $message);
        }
    }



    public function saveRateData()
    {
        list(,,,$id, $idUs) = explode('/', filter_var(Yii::app()->getRequest()->getRequestUri(), FILTER_SANITIZE_FULL_SPECIAL_CHARS));
        $message = filter_var(Yii::app()->getRequest()->getParam('comment'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $type = filter_var(Yii::app()->getRequest()->getParam('type'), FILTER_SANITIZE_NUMBER_INT);
        $rates = Yii::app()->getRequest()->getParam('rate');


        $vacData = $this->getVacStatus($id, $idUs);

        if( !$vacData['id_user'] )
        {
            // save rating
            foreach ($rates as $key => $val)
            {
                $fields['id_user'] = $vacData['iduspromo'];
                $fields['id_vac'] = $id;
                $fields['id_userf'] = Share::$UserProfile->exInfo->id;
                $fields['id_point'] = $key;
                $fields['point'] = $val[0];
                $fields['crdate'] = date("Y-m-d H:i:s");
//                !YII_DEBUG &&
                    $res = Yii::app()->db->createCommand()
                        ->insert('rating_details', $fields);
            } // end foreach

            $content = file_get_contents(Yii::app()->basePath . "/views/mails/app/new-review.html");
        $content = str_replace('#APPNAME#', $vacData['username'], $content);
        $content = str_replace('#EMPCOMPANY#',  Share::$UserProfile->exInfo->name, $content);
        $content = str_replace('#EMPLINK#',  Subdomain::site() . MainConfig::$PAGE_PROFILE_COMMON . DS . Share::$UserProfile->exInfo->id, $content);
        $content = str_replace('#VACID#', $id, $content);
        $content = str_replace('#VACNAME#', $vacData['title'], $content);
        $content = str_replace('#VACLINK#', Subdomain::site() . MainConfig::$PAGE_VACANCY . DS . $id, $content);
        $content = str_replace('#RATELINK#',Subdomain::site() . MainConfig::$PAGE_RATE, $content);
                
        Share::sendmail($vacData['emailpromo'], "Prommu: Новый отзыв", $content);


          


            // *** Обновляем общий рейтинг соискателя в таблице resume ***
//            $rate = (new UserProfileApplic())->getRateCount($vacData['iduspromo']);
            $rate = (new ProfileFactory())->makeProfile(array('id' => $vacData['iduspromo'], 'type' => 2))->getRateCount($vacData['iduspromo']);

//            !YII_DEBUG &&
                $res = Yii::app()->db->createCommand()
                    ->update('resume', array(
                        'rate' => $rate[0],
                        'rate_neg' => $rate[1],
                    ), 'id_user = :iduser', array(':iduser' => $vacData['iduspromo']));



            // оставляем отзыв
            if( $message )
            {
                $fields = array('id_promo' => $vacData['pid']);
                $fields['id_empl'] = Share::$UserProfile->exInfo->eid;
                $fields['message'] = $message;
                $fields['isneg'] = $type == 1 ? 0 : 1;
                $fields['iseorp'] = 1;
                $fields['crdate'] = date("Y-m-d H:i:s");
//                !YII_DEBUG &&
                    $res = Yii::app()->db->createCommand()
                        ->insert('comments', $fields);

                // Отправляем письмо уведомление о новом отзыве
                // $emailMessage = sprintf("Вы получили новый отзыв на сервисе &laquo;Prommu.com&raquo;.
                //         <br>
                //         Нажмите на <a href='%s'>ссылку</a>, чтобы перейти на страницу отзывов.
                //         "
                //     , Subdomain::site() . DS . MainConfig::$PAGE_COMMENTS
                // );
                // Share::sendmail($vacData['emailpromo'], "Prommu.com. Новый отзыв", $emailMessage);
            } // endif

            if( $message ) $s1 = "Ваш отзыв оптравлен на модерацию, после проверки админстратором он будет отображаться у соискателя";
            $ids = $vacData['iduspromo'];
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
            $message = 'Вы уже оставили отзыв данному соискателю по этой вакансии';
//            if( $vacData['status'] == 7 )
//            else $message = 'Вы не можете оставить отзыв по данной вакансии';

            return array('error' => 1, 'message' => $message);
        }
    }



    private function approveVacAfterAction($inData)
    {

        $content = file_get_contents(Yii::app()->basePath . "/views/mails/app/approval-for-vac.html");
        $content = str_replace('#APPNAME#', $inData['firstname'].' '.$inData['lastname'], $content);
        $content = str_replace('#EMPCOMPANY#',  Share::$UserProfile->exInfo->name, $content);
        $content = str_replace('#EMPLINK#',  Subdomain::site() . MainConfig::$PAGE_PROFILE_COMMON . DS . Share::$UserProfile->exInfo->id, $content);
        $content = str_replace('#VACID#', $inData['id'], $content);
        $content = str_replace('#VACNAME#', $inData['title'], $content);
        $content = str_replace('#VACLINK#', Subdomain::site() . MainConfig::$PAGE_VACANCY . DS . $inData['id'], $content);
        $content = str_replace('#RESPLINK#',Subdomain::site() . MainConfig::$PAGE_RESPONSES, $content);
                
        Share::sendmail($inData['email'], "Prommu: Утверждение на вакансию", $content);

    }
    private function getVacStat($props=[])
    {
        $idus = $props['idus'];
        $inIdVac = $props['id'];
        $inIdUs  = $props['idUs'];
        $sql = "SELECT DISTINCT 
                s.id sid, s.status, s.mdate
                , e.id, e.title, e.edate
                , ru.email emailpromo, ru.id_user iduspromo
                , r.id pid, CONCAT(r.firstname, ' ', r.lastname) username, r.photo
                , rd.id_user
            FROM vacation_stat s
            INNER JOIN empl_vacations e ON e.id = s.id_vac
            INNER JOIN resume r ON s.id_promo = r.id
            INNER JOIN user ru ON ru.id_user = r.id_user
            LEFT JOIN rating_details rd ON rd.id_user = ru.id_user AND rd.id_vac = e.id
            WHERE s.isresponse IN(1,2)
              AND e.id = {$inIdVac}
              AND ru.id_user = {$inIdUs}";
        /** @var $res CDbCommand */
        $res = Yii::app()->db->createCommand($sql);
        $res = $res->queryRow();

        return $res;
    }



    private function getVacStatus($inIdVac, $inIdUs)
    {
        $idus = Share::$UserProfile->exInfo->id;

        $sql = "SELECT DISTINCT 
                s.id sid, s.status, s.mdate
                , e.id, e.title, e.edate
                , ru.email emailpromo, ru.id_user iduspromo
                , r.id pid, CONCAT(r.firstname, ' ', r.lastname) username, r.photo logo
                , rd.id_user
            FROM vacation_stat s
            INNER JOIN empl_vacations e ON e.id = s.id_vac
            INNER JOIN resume r ON s.id_promo = r.id
            INNER JOIN user ru ON ru.id_user = r.id_user
            LEFT JOIN rating_details rd ON rd.id_user = ru.id_user AND rd.id_vac = e.id
            WHERE s.isresponse IN(1,2)
              AND e.id = {$inIdVac}
              AND ru.id_user = {$inIdUs}";
        /** @var $res CDbCommand */
        $res = Yii::app()->db->createCommand($sql);
        $res = $res->queryRow();

        return $res;
    }

    public function loadRatingPageDatas($id, $idUs)
    {
        
        $res = $this->getVacStatus($id, $idUs);

        if( !$res['id_user'] )
        {
            $UserProfile = new UserProfileApplic(array('id' => $res['id_user']));
            $data = $UserProfile->getProfileDataView($idUs, $id);
            // $data = $UserProfile->prepareProfileCommonRate($data);
            $data['user'] = $res;
        }
        else
        {
            $message = 'Вы уже выставили отзыв по данной вакансии';
//            if( $res['status'] == 7 )
//            else $message = 'Вы не можете оставить отзыв по данной вакансии';

            return array('error' => 1, 'message' => $message);
        } // endif

        return $data;
    }

    private function loadRatingPageData()
    {
        list(,,,$id, $idUs) = explode('/', filter_var(Yii::app()->getRequest()->getRequestUri(), FILTER_SANITIZE_FULL_SPECIAL_CHARS));

        $res = $this->getVacStatus($id, $idUs);

        if( !$res['id_user'] )
        {
            $UserProfile = new UserProfileApplic(array('id' => $res['id_user']));
            $data = $UserProfile->getPointRate($idUs);
            $data = $UserProfile->prepareProfileCommonRate($data);
            $data['user'] = $res;
        }
        else
        {
            $message = 'Вы уже выставили отзыв по данной вакансии';
//            if( $res['status'] == 7 )
//            else $message = 'Вы не можете оставить отзыв по данной вакансии';

            return array('error' => 1, 'message' => $message);
        } // endif

        return $data;
    }
}