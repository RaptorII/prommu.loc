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
      $db = Yii::app()->db;
      $rq = Yii::app()->getRequest();
        if( $rq->requestType != 'POST' ) $ret = array('error' => 1);
        $idres = $props['idres'] ?: filter_var($rq->getParam('idres'), FILTER_SANITIZE_NUMBER_INT);
        $status = $props['status'] ?: filter_var($rq->getParam('s'), FILTER_SANITIZE_NUMBER_INT);
        $idus = $props['idus'] ?: Share::$UserProfile->exInfo->id;


        // проверяем, что вакансия пренадлежит этому пользователю
        $sql = "SELECT e.id, e.title, e.id_user, s.id_promo promo, r.firstname, r.lastname, r.id_user app_user
               , u.email
               , s.id sid, s.status
            FROM empl_vacations e
            INNER JOIN vacation_stat s ON e.id = s.id_vac AND s.id = {$idres}
            INNER JOIN resume r ON r.id = s.id_promo
            INNER JOIN user u ON u.id_user = r.id_user
            WHERE e.id_user = {$idus}";
        /** @var $res CDbCommand */
        $vacData = $db->createCommand($sql)->queryRow();

        // if response exists
        if( $vacData['id'] )
        {
            $res = $db->createCommand()
                ->update(
                  'vacation_stat',
                  ['status'=>$status, 'mdate'=>date('Y-m-d H:i:s')],
                  'id=:id',
                  [':id'=>$idres]
                );
            // фиксируем в истории
            ResponsesHistory::setData($idres, $idus, $vacData['status'], $status);
            // отправляем уведомление для С
            if($status==Responses::$STATUS_REJECT)
            {
              UserNotifications::setDataByVac(
                $vacData['app_user'],
                $vacData['id'],
                UserNotifications::$APP_REFUSALS
              );
            }
            if($status==Responses::$STATUS_APPLICANT_ACCEPT)
            {
              UserNotifications::setDataByVac(
                $vacData['app_user'],
                $vacData['id'],
                UserNotifications::$APP_CONFIRMATIONS
              );
            }
            $ret = array('error' => 0, 'res' => $res);
            $ret = array_merge($ret, $this->getVacancyResponsesCounts($vacData['id']));

            if(!($vacData['status'] == Responses::$STATUS_REJECT) && $status == Responses::$STATUS_REJECT)
            {
                $content = file_get_contents(Yii::app()->basePath . "/views/mails/app/emp-failure.html");
                $content = str_replace('#APPNAME#', $vacData['firstname'].' '.$vacData['lastname'], $content);
                $content = str_replace('#EMPCOMPANY#',  Share::$UserProfile->exInfo->name, $content);
                $content = str_replace('#EMPLINK#',  Subdomain::site() . MainConfig::$PAGE_PROFILE_COMMON . DS . Share::$UserProfile->exInfo->id, $content);
                $content = str_replace('#VACSEARCH#', Subdomain::site() . MainConfig::$PAGE_SEARCH_VAC, $content);
                $content = str_replace('#VACNAME#', $vacData['title'], $content);
                $content = str_replace('#VACLINK#', Subdomain::site() . MainConfig::$PAGE_VACANCY . DS . $vacData['id'], $content);
                $content = str_replace('#RESPLINK#',Subdomain::site() . MainConfig::$PAGE_RESPONSES, $content);
                        
                Share::sendmail($vacData['email'], "Prommu: Отклонение заявки", $content);
            }
        
           
        if(
          ( $vacData['status']!=self::$STATUS_EMPLOYER_ACCEPT && $status==self::$STATUS_EMPLOYER_ACCEPT )
          ||
          ( $vacData['status']!=self::$STATUS_APPLICANT_ACCEPT && $status==self::$STATUS_APPLICANT_ACCEPT )
        )
        {
          $this->approveVacAfterAction($vacData);
          $id_user = $db->createCommand()
                      ->select('id_user')
                      ->from('resume')
                      ->where('id=:id',[':id'=>$vacData['promo']])
                      ->queryScalar();
          // push
          PushChecker::setPushMess($id_user, 'respond');
        }
      }
      else
      {
        $ret = ['error' => 1];
      }

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
    /**
     * получаем заявки по вакансиям
     */
    public function getResponsesRating($props = [])
    {
        $id_user = $props['id'] ?: Share::$UserProfile->exInfo->id;
        $arRes = ['items' => []];

        $query = Yii::app()->db->createCommand()
            ->select("vs.id,
                vs.status,
                vs.isresponse,
                DATE_FORMAT(vs.date, '%d.%m.%Y') rdate,
                DATE_FORMAT(ev.crdate, '%d.%m.%Y') bdate,
                ev.id id_vacancy,
                ev.title,
                ev.status vstatus,
                r.id_user")
            ->from('vacation_stat vs')
            ->leftjoin('empl_vacations ev','ev.id=vs.id_vac')
            ->leftjoin('resume r','r.id=vs.id_promo')
            ->where(
                'ev.id_user=:id AND (vs.status=:s1 OR vs.status=:s2)',
                [
                    ':id'=>$id_user,
                    ':s1'=>self::$STATUS_BEFORE_RATING,
                    ':s2'=>self::$STATUS_APPLICANT_RATED
                ]
            )
            ->order('vs.id desc')
            ->limit($this->limit)
            ->offset($this->offset)
            ->queryAll();

        if(!count($query))
            return $arRes;

        $arId = array();
        foreach ($query as $v)
        {
            $arRes['items'][$v['id_vacancy']][] = $v;
            $arId[] = $v['id_user'];
        }
        $arRes['users'] = Share::getUsers($arId);

        return $arRes;
    }
    /**
     * получаем заявки по вакансиям (счетчик)
     */
    public function getResponsesRatingCount($props = [])
    {
        $id_user = $props['id'] ?: Share::$UserProfile->exInfo->id;

        return Yii::app()->db->createCommand()
            ->select('count(vs.id)')
            ->from('vacation_stat vs')
            ->leftjoin('empl_vacations ev','ev.id=vs.id_vac')
            ->where(
                'ev.id_user=:id AND (vs.status=:s1 OR vs.status=:s2)',
                [
                    ':id'=>$id_user,
                    ':s1'=>self::$STATUS_BEFORE_RATING,
                    ':s2'=>self::$STATUS_APPLICANT_RATED
                ]
            )
            ->queryScalar();
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
                elseif( in_array($val['status'], [0,4,6,7,8,9]) ) $data[4] += $val['cou'];
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
    /**
     * выставление рейтинга и отзыва на странице setrate
     */
    public function saveRateData()
    {
        $db = Yii::app()->db;
        $rq = Yii::app()->getRequest();

        list(,,,$id, $idUs) = explode('/', filter_var($rq->getRequestUri(), FILTER_SANITIZE_FULL_SPECIAL_CHARS));
        $message = filter_var($rq->getParam('comment'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $type = filter_var($rq->getParam('type'), FILTER_SANITIZE_NUMBER_INT);
        $rates = $rq->getParam('rate');
        $about_us = filter_var($rq->getParam('about_us'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $recommend = filter_var($rq->getParam('recommend'), FILTER_SANITIZE_NUMBER_INT);      

        $vacData = $this->getVacStatus($id, $idUs);

        if(!$vacData['id_user'])
        { // отзыв для этого соискателя по данной вакансии еще не выставлялся
            $arInsert = array();
            foreach ($rates as $key => $val)
            {
                $arInsert[] = array(
                        'id_user' => $vacData['iduspromo'],
                        'id_vac' => $id,
                        'id_userf' => Share::$UserProfile->exInfo->id,
                        'id_point' => $key,
                        'point' => $val[0],
                        'crdate' => date("Y-m-d H:i:s")
                    );
            }
            if(count($arInsert))
            {
                Share::multipleInsert(['rating_details'=>$arInsert]);
            }
            // письмо соискателю о новом отзыве
            Mailing::set(15,
              [
                'email_user' => $vacData['emailpromo'],
                'name_user' => $vacData['username'],
                'name_company' => Share::$UserProfile->exInfo->name,
                'id_company' => Share::$UserProfile->exInfo->id,
                'id_vacancy' => $id,
                'title_vacancy' => $vacData['title']
              ]
            );
            // Обновляем общий рейтинг соискателя в таблице resume
            $rate = (new ProfileFactory())->makeProfile(['id'=>$vacData['iduspromo'], 'type'=>2])->getRateCount($vacData['iduspromo']);

            $db->createCommand()
                ->update(
                    'resume', 
                    ['rate'=>$rate[0], 'rate_neg'=>$rate[1]],
                    'id_user=:iduser',
                    [':iduser'=>$vacData['iduspromo']]
                );
            // оставляем отзыв
            if( $message )
            {
                $db->createCommand()->insert(
                        'comments',
                        [
                            'id_promo' => $vacData['pid'],
                            'id_empl' => Share::$UserProfile->exInfo->eid,
                            'message' => $message,
                            'isneg' => ($type==1 ? 0 : 1),
                            'crdate' => date("Y-m-d H:i:s")
                        ]
                    );
            }
            // отзыв о сайте
            if(!empty($recommend))
            {
                $db->createCommand()->insert(
                        'comments_about_us',
                        [
                            'id_user' => Share::$UserProfile->exInfo->id,
                            'message' => $about_us,
                            'is_negative' => ($recommend==1 ? 0 : 1),
                            'cdate' => time()
                        ]
                    );
            }
            // устанавливаем статус
            $status = ($vacData['status']==self::$STATUS_BEFORE_RATING 
                ? self::$STATUS_EMPLOYER_RATED
                : self::$STATUS_FULL_RATING); // полный статус только после того как оценит работодатель
            $db->createCommand()->update(
                'vacation_stat', 
                ['status'=>$status, 'mdate'=>date('Y-m-d H:i:s')],
                'id = :id',
                [':id' => $vacData['sid']]
            );

            // фиксируем в истории
            ResponsesHistory::setData(
              $vacData['sid'],
              Share::$UserProfile->exInfo->id,
              $vacData['status'],
              $status
            );
            // пуш уведомление для соискателя
            PushChecker::setPushMess($vacData['iduspromo'],'rate');

            // добавляем уведомление для Р
            UserNotifications::setDataByVac($vacData['iduspromo'],$id,UserNotifications::$APP_NEW_RATING);

            $message = ($message
                ? " Ваш отзыв отправлен на модерацию, после проверки администратором он будет отображаться у соискателя"
                : "");
            return array('saved' => 1, 'message' => "Спасибо за ваш ответ.$message");
        }
        else
        {
            $message = 'Вы уже оставили отзыв данному соискателю по этой вакансии';
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
                , r.id pid, CONCAT(r.firstname, ' ', r.lastname) username, r.photo logo, r.isman
                , rd.id_user, r.rate app_rate, r.rate_neg app_rate_neg
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

            return array('error' => 1, 'message' => $message);
        } // endif

        return $data;
    }
    /**
     * @param $isResponse int isresponse from vacancy_stat
     * @param $status int status from vacancy_stat
     * получаем человекопонятный статус
     */
    public function getStatus($isResponse, $status)
    {
        $result = '';
        if($isResponse==1) // отклик
        {
            switch($status)
            {
                case self::$STATUS_NEW: 
                    $result = 'Новая'; 
                    break;
                case self::$STATUS_VIEW: 
                    $result = 'Отложенная'; 
                    break;
                case self::$STATUS_REJECT: 
                    $result = 'Отклонена'; 
                    break;
                case self::$STATUS_APPLICANT_ACCEPT: 
                    $result = 'Заявка подтверждена'; 
                    break;
                case self::$STATUS_BEFORE_RATING: 
                    $result = 'Необходима оценка'; 
                    break;
                case self::$STATUS_APPLICANT_RATED: 
                    $result = 'Соискатель Вас оценил'; 
                    break;
                case self::$STATUS_EMPLOYER_RATED:
                case self::$STATUS_FULL_RATING: 
                    $result = 'Проект завершен'; 
                    break;
            }
        }
        elseif($isResponse==2) // приглашение
        {
            switch($status)
            {
                case self::$STATUS_REJECT: 
                    $result = 'Отказавшийся'; 
                    break;
                case self::$STATUS_EMPLOYER_ACCEPT: 
                    $result = 'Приглашение отправлено'; 
                    break;
                case self::$STATUS_APPLICANT_ACCEPT: 
                    $result = 'Приглашение принято'; 
                    break;
                case self::$STATUS_BEFORE_RATING: 
                    $result = 'Необходима оценка'; 
                    break;
                case self::$STATUS_APPLICANT_RATED: 
                    $result = 'Соискатель Вас оценил'; 
                    break;
                case self::$STATUS_EMPLOYER_RATED:
                case self::$STATUS_FULL_RATING: 
                    $result = 'Проект завершен'; 
                    break;
            }
        }

        return $result;
    }
    /**
     * 
     */
    public function getVacResponsesCnt($id_vacancy)
    {
        $arRes = [
                MainConfig::$VACANCY_APPROVED => 0, // Утвержденные
                MainConfig::$VACANCY_INVITED => 0, // Приглашенные
                MainConfig::$VACANCY_RESPONDED => 0, // Откликнувшиеся
                MainConfig::$VACANCY_DEFERRED => 0, // Отложенные
                MainConfig::$VACANCY_REJECTED => 0, // Отклоненные
                MainConfig::$VACANCY_REFUSED => 0, // Отказавшиеся
                'cnt' => 0
            ];
        // общий чат
        $model = new VacDiscuss();
        $arRes['discuss'] = $model->getDiscussCount($id_vacancy);
        // приглашения (включая приглашения по услугам)
        $model = new ServiceCloud();
        $arRes[MainConfig::$VACANCY_INVITED] = $model->getVacDataCnt($id_vacancy);
        //
        $query = Yii::app()->db->createCommand()
                    ->select("status, isresponse")
                    ->from('vacation_stat')
                    ->where('id_vac=:id',[':id'=>$id_vacancy])
                    ->queryAll();

        if(!count($query))
            return $arRes;

        foreach ($query as $v)
        {
            // новые(отложенные)
            if($v['status']==self::$STATUS_VIEW)
                $arRes[MainConfig::$VACANCY_DEFERRED]++;
            // Отклонение, отказ
            if($v['status']==self::$STATUS_REJECT)
            {
                $v['isresponse']==Responses::$STATE_INVITE && $arRes[MainConfig::$VACANCY_REJECTED]++;
                $v['isresponse']==Responses::$STATE_RESPONSE && $arRes[MainConfig::$VACANCY_REFUSED]++;
            }
            // утвержденные
            if($v['status'] > self::$STATUS_EMPLOYER_ACCEPT)
                $arRes[MainConfig::$VACANCY_APPROVED]++;
            // откликнувшиеся
            $v['isresponse']==Responses::$STATE_RESPONSE && $arRes[MainConfig::$VACANCY_RESPONDED]++;
            // приглашения
            $v['isresponse']==Responses::$STATE_INVITE && $arRes[MainConfig::$VACANCY_INVITED]++;
        }
        $arRes['cnt'] = count($query);

        return $arRes;
    }
    /**
     * 
     */
    public function getVacResponses($id_vacancy, $section)
    {
        $arRes = array();
        $db = Yii::app()->db;
        $condition = 'vs.id_vac=:id AND ';
        $filter = [':id'=>$id_vacancy];

        $arRes['vacancy'] = $db->createCommand()
                            ->select("id,
                                title,
                                status,
                                DATE_FORMAT(crdate, '%d.%m.%Y') bdate")
                            ->from('empl_vacations')
                            ->where('id=:id', $filter)
                            ->queryRow();

        if($section==MainConfig::$VACANCY_DEFERRED) // Новые(отложенные)
        {
            $condition .= 'vs.status=:status';
            $filter[':status'] = self::$STATUS_VIEW;
        }
        if($section==MainConfig::$VACANCY_REJECTED) // Отклонение
        {
            $condition .= 'vs.isresponse=:response AND vs.status=:status';
            $filter[':response'] = Responses::$STATE_RESPONSE;
            $filter[':status'] = self::$STATUS_REJECT;
        }
        if($section==MainConfig::$VACANCY_REFUSED) // Отказ
        {
            $condition .= 'vs.isresponse=:response AND vs.status=:status';
            $filter[':response'] = Responses::$STATE_INVITE;
            $filter[':status'] = self::$STATUS_REJECT;
        }
        if($section==MainConfig::$VACANCY_APPROVED) // Утвержденные
        {
            $condition .= 'vs.status>:status';
            $filter[':status'] = self::$STATUS_EMPLOYER_ACCEPT;
        }
        if($section==MainConfig::$VACANCY_RESPONDED) // Откликнувшиеся
        {
            $condition .= 'vs.isresponse=:response';
            $filter[':response'] = Responses::$STATE_RESPONSE;
            /*$condition .= '(vs.status=:status1 OR vs.status=:status2)';
            $filter[':status1'] = self::$STATUS_NEW;
            $filter[':status2'] = self::$STATUS_EMPLOYER_ACCEPT;*/
        }
        if($section!=MainConfig::$VACANCY_INVITED)
        {
            $arRes['items'] = $db->createCommand()
                                ->select("r.id_user user,
                                    vs.id sid,
                                    vs.status, 
                                    vs.isresponse,
                                    DATE_FORMAT(vs.date, '%d.%m.%Y') rdate")
                                ->from('vacation_stat vs')
                                ->join('resume r','r.id=vs.id_promo')
                                ->where($condition, $filter)
                                ->order('vs.id desc')
                                ->limit($this->limit)
                                ->offset($this->offset)
                                ->queryAll();
        }
        else // Приглашения
        {
            $query = $db->createCommand()
                                ->select("sc.id sc_id,
                                    sc.type,
                                    sc.name vacancy,
                                    sc.status sc_status,
                                    sc.user sc_user,
                                    DATE_FORMAT(sc.date,'%H:%i %d.%m.%Y') sc_date,
                                    UNIX_TIMESTAMP(sc.date) usc_date,
                                    r.id_user vs_user,
                                    vs.id vs_id,
                                    vs.status vs_status, 
                                    vs.isresponse,
                                    UNIX_TIMESTAMP(vs.date) uvs_date,
                                    DATE_FORMAT(vs.date, '%H:%i %d.%m.%Y') vs_date")
                                ->from('empl_vacations ev')
                                ->leftjoin('service_cloud sc','sc.name=ev.id')
                                ->leftjoin('vacation_stat vs','vs.id_vac=ev.id')
                                ->leftjoin('resume r','r.id=vs.id_promo')
                                ->where(
                                    "ev.id=:id AND (sc.type IN('email','push','sms') or vs.isresponse=2)",
                                    [':id'=>$id_vacancy]
                                )
                                ->order('vs.date asc, sc.date asc')
                                ->limit($this->limit)
                                ->offset($this->offset)
                                ->queryAll();

            if (count($query))
            {
                $arSC = $arVS = array();
                foreach ($query as $v)
                {
                    $arT = array();
                    if(
                        !in_array($v['sc_id'], $arSC) 
                        && 
                        in_array($v['type'],['email','push','sms'])
                    )
                    {
                        $arUsers = explode(',',$v['sc_user']);
                        foreach ($arUsers as $id_user)
                        {
                            $arT[] = array(
                                'user' => $id_user,
                                'status' => ($v['sc_status'] ? 'Отправлено' : 'Ожидание'),
                                'type' => $v['type'],
                                'date' => $v['sc_date']
                            );
                        }
                        $arSC[] = $v['sc_id'];
                    }
                    //
                    if(!in_array($v['vs_id'], $arVS)  &&  $v['isresponse']==Responses::$STATE_INVITE)
                    {
                        $arT[] = array(
                            'user' => $v['vs_user'],
                            'status' => $this->getStatus($v['isresponse'], $v['vs_status']),
                            'type' => 'site',
                            'date' => $v['vs_date']
                        );
                        $arVS[] = $v['vs_id'];
                    }
                    //
                    count($arT)==1 && $arRes['items'][] = reset($arT);
                    //
                    if(count($arT)>1)
                    {
                        if($v['usc_date'] > $v['uvs_date'])
                        {
                            for($i=0,$n=count($arT); $i<$n; $i++)
                                $arRes['items'][] = $arT[$i];
                        }
                        else
                        {
                            for($i=count($arT)-1; $i>=0; $i--)
                                $arRes['items'][] = $arT[$i];                   
                        }
                    }
                }
            }
        }

        if(count($arRes['items']))
        {
            $arId = array();
            foreach ($arRes['items'] as $v)
                $arId[] = $v['user'];
            $arRes['users'] = Share::getUsers($arId);
        }

        return $arRes;
    }
}