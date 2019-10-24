<?php

/**
 * Date: 18.02.2016
 * Time: 10:12
 */

class UserProfileEmpl extends UserProfile
{
    function __construct($inProps)
    {
        parent::__construct($inProps);
        $props = is_object($inProps) ? get_object_vars($inProps) : $inProps;

        $this->type = 3;
        if( $props['idProfile'] ) $this->exInfo = (object)array('eid' => $props['idProfile']);

        $this->photosMax = MainConfig::$EMPLOYER_MAX_PHOTOS;

        $this->viewTpl = MainConfig::$VIEWS_COMPANY_PROFILE_OWN;
        // YiiUpload
        $this->arYiiUpload['imgDimensions'] = ['30'=>30,'100'=>220,'169'=>169,'400'=>400];
        $this->arYiiUpload['objSave'] = $this;
    }


    /**
     * Сохраняем лого работодателя
     */

    public function proccessLogo()
    {
        $id = Share::$UserProfile->id;
        $eid = Share::$UserProfile->exInfo->eid;

        $sql = "SELECT MAX(p.npp) npp, COUNT(*) cou FROM user_photos p WHERE p.id_empl = {$eid}";
        /** @var $res CDbCommand */
        $photosData = Yii::app()->db->createCommand($sql);
        $photosData = $photosData->queryRow();

        // если не превышено кол-во фоток - сохраняем
        if( $photosData['cou'] < $this->photosMax )
        {
            // crop logo, make thumbs
            $cropRes = (new UploadLogo())->processCropLogo();

            // save main logo to db
            $pathinfo = pathinfo(Yii::app()->session['uplLogo']['file']);

            $this->updateForPhoto($eid, $pathinfo['filename']);

            Yii::app()->db->createCommand()
                ->insert('user_photos', array(
                    'id_empl' => $eid,
                    'id_user' => $id,
                    'npp' => $photosData['npp'] + 1,
                    'photo' => $pathinfo['filename'],
                ));

            Yii::app()->db->createCommand()
                ->update('user', array(
                    'ismoder' => 0,
                ), 'id_user=:id_user', array(':id_user' => $id));


            $pathinfo = pathinfo($cropRes['file']);
            $cropRes['idfile'] = $pathinfo['filename'];

            Mailing::set(1, ['id_user'=>$id], self::$EMPLOYER);
        }
        else
        {
            $s1 = "Максимальное кол-во фото для пользователя: {$this->photosMax} шт";
            Yii::app()->user->setFlash('Message', array('type' => 'error', 'message' => $s1));
            $cropRes = array();
        } // endif

        return $cropRes;
    }

    public function sendLogo($props=[])
    {
        // $id = $props['id'];
        $id = Share::$UserProfile->id;
        $eid = Share::$UserProfile->exInfo->eid;
        $sql = "SELECT  r.id
            FROM employer r
            LEFT JOIN user u ON u.id_user = r.id_user
            WHERE r.id_user = {$id}";
        $res = Yii::app()->db->createCommand($sql)->queryAll();

        foreach ($res as $key => $val)
        {
//            $val['val'] ?: $val['val'] = $val['name'];
        } // end foreach

        $dat = [
            'id' => $val['id'],
            'id_user' => $val['id_user'],
        ];


        $id = $dat['id_user'];
        $id_resume = $dat['id'];

        $sql = "SELECT MAX(p.npp) npp, COUNT(*) cou FROM user_photos p WHERE p.id_empl = {$eid}";
        /** @var $res CDbCommand */
        $photosData = Yii::app()->db->createCommand($sql);
        $photosData = $photosData->queryRow();

        // если не превышено кол-во фоток - сохраняем
        if( $photosData['cou'] < $this->photosMax )
        {
            // crop logo, make thumbs
            $this->updateForPhoto($eid, $props['data']);

            Yii::app()->db->createCommand()
                ->insert('user_photos', array(
                    'id_empl' => $eid,
                    'id_user' => $id,
                    'npp' => $photosData['npp'] + 1,
                    'photo' => $props['data'],
                ));
        }
        else
        {
            $s1 = "Максимальное кол-во фото для пользователя: {$this->photosMax} шт";
            Yii::app()->user->setFlash('Message', array('type' => 'error', 'message' => $s1));
            $cropRes = array();
        } // endif

        return $message = "Good Sent";
    }


    public function setPhotoAsLogo()
    {
       $id = filter_var(Yii::app()->getRequest()->getParam('dm', 0), FILTER_SANITIZE_NUMBER_INT);
        $eid = Share::$UserProfile->exInfo->eid;
        // считываем фото пользователя
        $sql = "SELECT p.id, p.photo, CASE WHEN p.photo = r.logo THEN 1 ELSE 0 END ismain, npp
            FROM employer r
            LEFT JOIN user_photos p ON p.id_empl = r.id
            WHERE r.id = {$eid}
            ORDER BY npp DESC";
        $photos = (Yii::app()->db->createCommand($sql)->queryAll());

        // если пренадлежит пользователю - делаем главным
        if( count($photos) > 1 && ($ind = Share::arraySearch($photos, 'id', $id)) !== false )
        {
            $max = 0;
            foreach ($photos as $key => $val)
            {
                if( $max < $val['npp'] ) $max = $val['npp'];
            } // end foreach

            $this->updateForPhoto($eid, $photos[$ind]['photo']);

            Yii::app()->db->createCommand()
                ->update('user_photos', array(
                    'npp' => $max + 1,
                ), 'id = :id', array(':id' => $photos[$ind]['id']));
        } // endif
    }

    public function delProfilePhoto()
    {
        $id = filter_var(Yii::app()->getRequest()->getParam('del', 0), FILTER_SANITIZE_NUMBER_INT);
        $eid = Share::$UserProfile->exInfo->eid;

        // считываем фото пользователя
        $sql = "SELECT p.id, p.photo, CASE WHEN p.photo = r.logo THEN 1 ELSE 0 END ismain
            FROM employer r
            LEFT JOIN user_photos p ON p.id_empl = r.id
            WHERE r.id = {$eid}
            ORDER BY npp DESC";
        $photos = (Yii::app()->db->createCommand($sql)->queryAll());

        // если пренадлежит пользователю - удаляем
        if( count($photos) > 1 && ($ind = Share::arraySearch($photos, 'id', $id)) !== false )
        {
            Yii::app()->db->createCommand()->delete('user_photos', '`id`=:id', array(':id' => $id));

            $res = (new UploadLogo())->delPhoto($this->filesRoot . DS . $photos[$ind]['photo']);

            // делаем предыдущую фотку главной
            if( $photos[$ind]['ismain'] && count($photos)>1)
            {
                $this->updateForPhoto($eid, $photos[1]['photo']);
            }
        }
    }
    /**
     * @param $id integer id employer
     * @param $photo string logo employer
     */
    private function updateForPhoto($id,$photo)
    {
        $arRating = Share::$UserProfile->getRateCount();

        Yii::app()->db->createCommand()
            ->update('employer',
                array(
                    'logo' => $photo,
                    'mdate' => date('Y-m-d H:i:s'),
                    'rate' => $arRating[0],
                    'rate_neg' => $arRating[1],
                    'ismoder' => 0,
                    'is_new' => 1
                ),
                'id=:id',
                [':id'=>$id]
            );
    }
    /**
     * Получаем рейтинг работодателя
     */
    public function getRate()
    {
        $idempl = filter_var(Yii::app()->getRequest()->getParam('id'), FILTER_SANITIZE_NUMBER_INT);;
        $data = $this->getPointRate($idempl);
        $data['rate'] = $this->prepareProfileCommonRate($data);

        return $data;
    }



    /**
     * Получаем контакты работодателя
     */
    public function getContacts()
    {
        $idempl = filter_var(Yii::app()->getRequest()->getParam('id'), FILTER_SANITIZE_NUMBER_INT);;
        $idvac = filter_var(Yii::app()->getRequest()->getParam('idvac'), FILTER_SANITIZE_NUMBER_INT);;

        if( $idvac && $idempl )
        {
            $sql = "SELECT a.id_attr idattr, a.val, u.email FROM empl_vacations v
                INNER JOIN employer e ON e.id_user = v.id_user AND e.id = {$idempl}
                LEFT JOIN user_attribs a ON a.id_us = e.id_user AND a.id_attr IN (1,2)
                LEFT JOIN user u ON u.id_user = e.id_user
                WHERE v.id = {$idvac} AND v.iscontshow = 1";
            $res = Yii::app()->db->createCommand($sql)->queryAll();

            $data = array();
            foreach ($res as $key => $val)
            {
                if( $val['idattr'] == 1 ) $data['mob'] = $val['val'];
                elseif( $val['idattr'] == 2 ) $data['addmob'] = $val['val'];
            } // end foreach

            $data['email'] = $val['email'];
        }

        return $data;
    }
    /**
     * @param $inID int id_user
     * @return array(int - positive, int - negative, int - sum)
     * получаем значение рейтинга
     */
    public function getRateCount($inID = 0)
    {
        $arRes = array(0,0,0); // положительные, отрицательные, сумма
        $id_user = $inID ?: Share::$UserProfile->id;
        // считаем баллы рейтинга
        $query = Yii::app()->db->createCommand()
                    ->select("rd.point, pr.value")
                    ->from('rating_details rd')
                    ->leftjoin('point_rating pr','pr.id=rd.id_point')
                    ->where('rd.id_user=:id',[':id'=>$id_user])
                    ->queryAll();

        if(count($query))
            foreach ($query as $v)
            {
                $v['point']>0 && $arRes[0] += ($v['point'] * $v['value']);
                $v['point']<0 && $arRes[1] += ($v['point'] * $v['value']);
            }
        // считаем баллы комментариев
        $query = Yii::app()->db->createCommand()
                    ->select("count(c.id) - sum(c.isneg) positive, sum(c.isneg) negative")
                    ->from('comments c')
                    ->leftjoin('employer e','e.id=c.id_empl')
                    ->where(
                        'e.id_user=:id and c.iseorp=0',
                        [':id'=>$id_user]
                    )
                    ->queryRow();

        $query['positive']>0 && $arRes[0] += ($query['positive'] * 20); // по ТЗ
        $query['negative']>0 && $arRes[1] += ($query['negative'] * -20); // по ТЗ
        // считаем года
        $query = Yii::app()->db->createCommand()
                    ->select("TIMESTAMPDIFF(YEAR,crdate,curdate())")
                    ->from('user')
                    ->where('id_user=:id',[':id'=>$id_user])
                    ->queryScalar();
        $arRes[0] += $query * 5; // по ТЗ 5 балов за год
        // подсчет отработанных вакансий
        $query = Yii::app()->db->createCommand()
                    ->select("count(*)")
                    ->from('empl_vacations')
                    ->where(
                        'id_user=:id and status=0 and ismoder=100 and in_archive=0 and remdate>=now()',
                        [':id'=>$id_user]
                    )
                    ->queryScalar();
        $arRes[0] += $query;
        // подсчет личных данных
        $query = Yii::app()->db->createCommand()
                    ->select("e.logo,
                        u.confirmEmail,
                        u.confirmPhone,
                        ua.key,
                        ua.val")
                    ->from('user u')
                    ->join('employer e','e.id_user=u.id_user')
                    ->join('user_attribs ua','ua.id_us=u.id_user')
                    ->where('u.id_user=:id',[':id'=>$id_user])
                    ->queryAll();

        $info = reset($query);
        $flag = false;
        foreach ($query as $v)
        {
            $v['key']=='site' && !empty($v['val']) && $arRes[0]++; // по ТЗ
            $v['key']=='inn' && !empty($v['val']) && $arRes[0]+=2; // по ТЗ
            $v['key']=='legalindex' && !empty($v['val']) && $arRes[0]+=2; // по ТЗ
            $v['key']=='stationaryphone' && !empty($v['val']) && $arRes[0]++; // по ТЗ
            if(!$flag && in_array($v['key'],['viber','whatsapp','telegram','googleallo']))
            {
                $flag = true;
                $arRes[0]++; // по ТЗ
            }
        }
        !empty($info['logo']) && $arRes[0] += 2;// по ТЗ наличие фото
        $info['confirmEmail'] && $arRes[0] += 2; // по ТЗ
        $info['confirmPhone'] && $arRes[0] += 2; // по ТЗ
        //
        return $arRes;
    }



    /**
     * Получаем кол-во позитивных и негативных отзывов
     */
    public function getCommentsCount($inID = 0)
    {
        $idempl = $inID ?: $this->exInfo->eid;

        $sql = "SELECT
            (SELECT COUNT(*) FROM comments co WHERE co.id_empl = {$idempl} AND co.iseorp = 0 AND co.isactive = 1 
                AND co.isneg = 0 ) commpos,
            (SELECT COUNT(*) commpos FROM comments co WHERE co.id_empl = {$idempl} AND co.iseorp = 0 AND co.isactive = 1
                AND co.isneg = 1) commneg";
        $res = Yii::app()->db->createCommand($sql)->queryRow();
        return array($res['commpos'], $res['commneg']);
    }



    /**
     * Получаем данные профиля для вывода
     * @param int $inID - id user
     * @param int $inEID - id empl
     * @return mixed
     */
    public function getProfileDataView($inID = 0, $inEID = 0)
    {
        if( !$inID ) $inID = $this->id;
        if( !$inEID ) $inEID = $this->exInfo->eid;

        $data = $this->getProfileData($inID, $inEID);
        $data['userAllInfo'] = $this->getProfileMainData($data['userInfo']['id_user']);
        $data['rating'] = $this->prepareProfileCommonRate($data['rating']);


        return $data;
    }



    /**
     * Получаем данные профиля для API
     * @param array $props :
     *      id - id user
     * @return mixed
     */
    public function getProfileDataAPI($props)
    {
        $id = $props['id'];

        // читаем данные из профиля
        $sql = "SELECT u.email
              , e.id,
                e.id_user idus,
                e.type,
                e.name, 
                e.web,
                e.position,
                e.firstname,
                e.lastname,
                e.logo,
                u.email,
                u.crdate,
                u.is_online,
                u.mdate,
                e.contact
            FROM employer e
            INNER JOIN user u ON u.id_user = e.id_user
            WHERE e.id_user = {$id}";
        $res = Yii::app()->db->createCommand($sql)->queryRow();
        if(!empty($res['logo'])) $res['logo'] = "https://files.prommu.com/users/".$res['idus']."/".$res['logo'].".jpg";
        $data['emplInfo'] = $res;
        $rest = "SELECT ci.id_city id, ci.name, co.id_co, co.name coname
            FROM user_city uc
            LEFT JOIN city ci ON uc.id_city = ci.id_city
            LEFT JOIN country co ON co.id_co = ci.id_co
            WHERE uc.id_user = {$id}";
        $data['city'] = Yii::app()->db->createCommand($rest)->queryAll();


        // считываем характеристики пользователя
        $sql = "SELECT e.id_user idus
              , a.val
              , a.id_attr
              , d.name
              , d.type
              , d.id_par idpar
              , d.key
            FROM employer e
            LEFT JOIN user u ON u.id_user = e.id_user
            LEFT JOIN user_attribs a ON e.id_user = a.id_us
            LEFT JOIN user_attr_dict d ON a.id_attr = d.id
            WHERE e.id_user = {$id}
            ORDER BY a.id_attr";
        $res = Yii::app()->db->createCommand($sql)->queryAll();


        foreach ($res as $key => $val)
        {
//            $val['val'] != '' ?: $val['val'] = $val['name'];
            $attr[$val['key']] = $val;
        } // end foreach
        $data['userAttribs'] = $attr;




        // считываем тип работодателя
        $sql = "SELECT d.id, d.type, d.name FROM user_attr_dict d WHERE d.id_par = 101 ORDER BY id";
        $data['cotype'] = Yii::app()->db->createCommand($sql)->queryAll();
//        foreach ($data['cotype'] as $key => &$val)
//        {
//            if( $data['emplInfo']['type'] == $val['id'] ) $val['selected'] = 1;
//        } // end foreach

        return $data;
    }



    public function getProfileDataEdit()
    {
        return $this->getProfileEditPageData();
    }



    public function getPointRate($inIdUsr = 0)
    {
        $id = $inIdUsr ?: Share::$UserProfile->exInfo->id;

        // получаем рейтинг и уровень характеристик
        $sql = "SELECT sum(m.rate) as rate, sum(m.rate_neg) as rate_neg, m.id_point, m.descr 
            FROM (
              SELECT
                CASE WHEN rd.point >= 0 THEN rd.point ELSE 0 END AS rate,
                CASE WHEN rd.point < 0 THEN rd.point ELSE 0 END AS rate_neg,
                rd.id_point,
                r.descr
              FROM rating_details rd,
                   point_rating r
              WHERE id_user = {$id} AND grp = 2
              AND r.id = rd.id_point
            ) m 
            GROUP BY m.id_point";
        $res = Yii::app()->db->createCommand($sql)->queryAll();
        $data['rate'] = $res;



        // получение название характеристик рейтинга
       $sql = "SELECT id, descr FROM `point_rating` where grp = 2";

        $res = Yii::app()->db->createCommand($sql)->query();
        $data['rateNames'] = array();
        while( ($row = $res->read()) !== false ) { $data['rateNames'][$row['id']] = $row['descr']; }

        return $data;
    }



    public function prepareProfileCommonRate($inData)
    {
        foreach ($inData['rateNames'] as $key => $val)
        {
            $pointRate[$key] = array(0, 0);
        } // end foreach


        // sum all pos and neg rate
        $rate = array(0, 0);
        $maxPointRate = 0;
        $full = 0;
        foreach ($inData['rate'] as $key => $val)
        {
            // масимальный рейтинг
            if( $val['rate'] - abs($val['rate_neg']) > $maxPointRate) $maxPointRate = $val['rate'] - abs($val['rate_neg']);

            // сумарные рейтинги по всем атрибутам
            $rate[0] += $val['rate'];
            $rate[1] += abs($val['rate_neg']);

            // рейтинги по атрибутам
            $pointRate[$val['id_point']][0] += $val['rate'];
            $pointRate[$val['id_point']][1] += abs($val['rate_neg']);
        } // end foreach

       $neg = $rate[1];
        if($neg != 0) {
             $full = ($rate[0] - $rate[1])/5;

        }
        else {
            $full = ($rate[0] - $rate[1]);
        }
        if($full > 5) $full = 5;
        if($full < 0) $full = 1;


        return array('pointRate' => $pointRate,
                'rate' => $rate,
                'countRate' => $full,
                'full' => $full,
                'maxPointRate' => $maxPointRate,
                'rateNames' => $inData['rateNames'],
            );
    }



    public function saveProfileData()
    {
        $id = $this->exInfo->id;
        $res = $this->checkFieldsProfile();


        if($res['err'])// неправильно заполнены поля
        {
            return $res;
        }
        else // *** Сохраняем данные пользователя ***
        {
            $rq = Yii::app()->getRequest();

            $name = filter_var($rq->getParam('name'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $fname = filter_var($rq->getParam('fname'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $lname = filter_var($rq->getParam('lname'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $contact = filter_var($rq->getParam('contact'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $emplcontact = $rq->getParam('employerContact');
            $email = filter_var($rq->getParam('email'), FILTER_VALIDATE_EMAIL);
            $companyType = filter_var($rq->getParam('companyType'), FILTER_SANITIZE_NUMBER_INT);
            $cityManual = filter_var($rq->getParam('cityManualMulti'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $logo = filter_var($rq->getParam('logo'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $aboutme = filter_var($rq->getParam('aboutme'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $arFields = [];

            $sql = "SELECT u.email, u.confirmEmail
              , e.id,
                e.id_user idus,
                e.type,
                e.name,
                e.firstname,
                e.lastname,
                e.contact,
                e.photo,
                e.logo,
                e.crdate,
                e.aboutme,
                e.employer_contact
            FROM employer e
            INNER JOIN user u ON u.id_user = e.id_user
            WHERE e.id_user = {$id}";
            $res = Yii::app()->db->createCommand($sql)->queryRow();
            $data = $res;

            $oldEmail = filter_var($data['email'], FILTER_VALIDATE_EMAIL); // при условии что email на email похож
            if($oldEmail!='' && $oldEmail != $email){
                $res = Yii::app()->db->createCommand()
                ->update('user', array(
                    'confirmEmail' => 0,
                ), 'id_user=:id_user', array(':id_user' => $id));
            }
            $data['firstname']!=$fname && $arFields[] = 'Имя';
            $data['lastname']!=$lname && $arFields[] = 'Фамилия';
            $data['type']!=$companyType && $arFields[] = 'Тип компании';
            $data['name']!=$name && $arFields[] = 'Название компании';
            $data['contact']!=$contact && $arFields[] = 'Контактное лицо';
            $data['aboutme']!=$aboutme && $arFields[] = 'О компании';
            $data['employer_contact']!=$emplcontact && $arFields[] = 'Отображение контактных данных';

            ///API
            //             $fieldsApi = array(
            //                 'firstName' => $fname,
            //                 'lastName' => $lname,
            //                 'email' => $email,
            //             );
            //            if( $logo ) $fields['photo'] = $logo;
            //             $res = Yii::app()->db->createCommand()
            //                 ->update('user_api', $fieldsApi, 'id=:id', array(':id' => $id));
            ///API

            // сохраняем атрибуты пользователя
            $this->saveUserAttribs();

            // сохраняем города
            $this->saveEmplCities();

            $res = Yii::app()->db->createCommand()
                ->update('user', array(
                    'email' => $email,
                    'isblocked' => 0,
                    'mdate' => date('Y-m-d H:i:s'),
                ), 'id_user=:id_user', array(':id_user' => $id));

            if(count($arFields))
            {
                $arRating = Share::$UserProfile->getRateCount();

                Yii::app()->db->createCommand()
                  ->update(
                    'employer',
                    [
                      'name' => $name,
                      'firstname' => $fname,
                      'lastname' => $lname,
                      'contact' => $contact,
                      'employer_contact' => $emplcontact,
                      'type' => $companyType,
                      'aboutme' => $aboutme,
                      'ismoder' => 0,
                      'mdate' => date('Y-m-d H:i:s'),
                      'rate' => $arRating[0],
                      'rate_neg' => $arRating[1],
                      'is_new' => 1
                    ],
                    'id_user=:id_user',
                    [':id_user' => $id]
                  );
                // save user
                $res = Yii::app()->db->createCommand()
                    ->update('user', array(
                        'email' => $email,
                        'isblocked' => 0,
                        'ismoder' => 0,
                        'mdate' => date('Y-m-d H:i:s'),
                    ), 'id_user=:id_user', array(':id_user' => $id));

                $name = $data['firstname'] . ' ' . $data['lastname'];
                empty(trim($name)) && $name = "Пользователь";
                // Письмо админу о изменении профиля пользователем
                Mailing::set(17,
                  [
                    'name_user' => $name,
                    'id_user' => $id,
                    'fields_user' => implode(', ',$arFields)
                  ],
                  self::$EMPLOYER
                );
                $message = '<p>Анкета отправлена на модерацию.<br>Модерация занимает до 15 минут в рабочее время. О результатах проверки - Вам прийдет уведомление на эл. почту</p>';
                Yii::app()->user->setFlash('prommu_flash', $message);
            }
        }
    }



    /**
     * Получаем данные профиля из таблицы employer
     */
    public function getUserProfileData($inID)
    {
        $sql = "SELECT e.id_user idus
              , e.firstname
              , e.lastname
              , e.logo
            FROM employer e
            WHERE e.id_user = {$inID}";
        return Yii::app()->db->createCommand($sql)->queryRow();
    }



    /**
     * Создаём модель рейтинга
     * @return Rate
     */
    public function makeRate($inProps)
    {
        return new RateEmpl($inProps);
    }



    /**
     * фабрика модели отклика
     * @return Responses
     */
    public function makeResponse()
    {
        return new ResponsesEmpl($this);
    }



    /**
     * фабрика модели чата
     * @return Im
     */
    public function makeChat()
    {
        return new ImEmpl($this);
    }



    /**
     * Получаем даные профиля
     */
    protected function getUserData($inId)
    {
        $res = Yii::app()->db->createCommand()
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
                , u.is_online
                , u.mdate
            ")
            ->from('user u')
            ->leftJoin('user_work w', 'u.id_user = w.id_user')
            ->leftJoin('resume r', 'r.id_user = u.id_user AND u.status = 2')
            ->leftJoin('employer e', 'e.id_user = u.id_user AND u.status = 3')
            ->where('u.id_user=:id_user', array(':id_user' => $inId))
            ->queryRow();

        return $res;
    }



    private function checkFieldsProfile()
    {
        $ret = array('err' => 0,);

        $val = Yii::app()->getRequest()->getParam('name');
        if( trim($val) == '' )
        {
            $ret = array('err' => 1,
                'item' => 'name',
                'msg' => 'Введите Название компании',
                );
         } //endif

        $val = Yii::app()->getRequest()->getParam('type');
        if( trim($val) == 'не выбран' )
        {
            $ret = array('err' => 1,
                'item' => 'fname',
                'msg' => 'Введите Имя',
                );
        } // endif

        $val = Yii::app()->getRequest()->getParam('email');
        if( !$ret['err'] && trim($val) == '' )
        {
            $res = Yii::app()->db->createCommand()
                ->select("email")
                ->from('user')
                ->where('email = :t AND id_user <> :id', array(':t' => $val, ':id' => $this->exInfo->id))
                ->queryRow();

            if( $res['email'] )
                $ret = array('err' => 1,
                    'item' => 'email',
                    'msg' => 'Указанный e-mail адрес уже используется в системе',
                    );

            if( trim($val) == '' )
                $ret = array('err' => 1,
                    'item' => 'email',
                    'msg' => 'Введите Email адрес',
                    );        } // endif


        $val = Yii::app()->getRequest()->getParam('cities');
        if( !$ret['err'] && !count($val) )
        {
            $ret = array('err' => 1,
                'item' => 'cities',
                'msg' => 'Выберите Город',
                );
        } // endif

        return $ret;
    }



    /**
     * Копируем новый файл лого из временного каталога
     */
    private function copyLogoFiles($inId)
    {
        $path = MainConfig::$DOC_ROOT . DS . MainConfig::$PATH_EMPL_LOGO;
        $file = $inId;

        file_exists("{$path}/tmp/{$file}" . '100.jpg') && copy("{$path}/tmp/{$file}" . '100.jpg', "{$path}/{$file}" . '100.jpg');
        file_exists("{$path}/tmp/{$file}" . '400.jpg') &&  copy("{$path}/tmp/{$file}" . '400.jpg', "{$path}/{$file}" . '400.jpg');
        file_exists("{$path}/tmp/{$file}" . '000.jpg') &&  copy("{$path}/tmp/{$file}" . '000.jpg', "{$path}/{$file}" . '000.jpg');
    }



    private function getProfileData($inID = 0, $inEID = 0)
    {
        // получаем общий рейтинг
        $data['userInfo'] = $this->getUserInfo($inID);
        $data['rating'] = $this->getPointRate($inID);
        $data['lastJobs'] = $this->getLastJobs($inID);
        $data['lastResp'] = $this->getLastResponses($inID);
        $data['lastComments'] = $this->getLastComments($inEID);
        $data['userPhotos'] = $this->getUserPhotos($inEID);

        return $data;
    }



    private function getProfileEditPageData()
    {
        $arRes = array();
        $id = Share::$UserProfile->id;
        $type = Share::$UserProfile->type;

        if(!$id || $type!=3)
            return $arRes;

        if(Yii::app()->getRequest()->getParam('ep')==1) // для страницы Мои фото этого достаточно
        {
            $arRes['userPhotos'] = Yii::app()->db->createCommand()
                            ->select('up.id, up.photo, up.signature, e.logo')
                            ->from('user_photos up')
                            ->rightjoin('employer e', 'e.id=up.id_empl')
                            ->where('e.id_user=:id',[':id'=>$id])
                            ->order('npp desc')
                            ->limit(MainConfig::$APPLICANT_MAX_PHOTOS)
                            ->queryAll();

            foreach ($arRes['userPhotos'] as &$v)
            {
                $v['ismain'] = $v['logo']==$v['photo'];
                $v['src_small'] = Share::getPhoto($id, $type,$v['photo'],'medium');
                $v['src_big'] = Share::getPhoto($id, $type,$v['photo'],'big');
            }
            unset($v);

            return $arRes;
        }

        // читаем данные из профиля
        $arRes['info'] = Yii::app()->db->createCommand()
                        ->select("e.id,
                                e.id_user idus,
                                e.type,
                                e.name,
                                e.firstname,
                                e.lastname,
                                e.photo,
                                e.logo,
                                e.crdate,
                                e.contact,
                                e.aboutme,
                                e.employer_contact,
                                u.confirmEmail,
                                u.confirmPhone,
                                u.email")
                        ->from('employer e')
                        ->join('user u', 'u.id_user=e.id_user')
                        ->where('e.id_user=:idus', [':idus'=>$id])
                        ->queryRow();

        $arRes['userPhotosCnt'] = Yii::app()->db->createCommand()
                            ->select('count(up.id)')
                            ->from('user_photos up')
                            ->rightjoin('employer e', 'e.id=up.id_empl')
                            ->where('e.id_user=:id',[':id'=>$id])
                            ->queryScalar();

        // вычисляем настоящий урл для фото
        $arRes['info']['src'] = Share::getPhoto(
                                        $arRes['info']['idus'],
                                        $type,
                                        $arRes['info']['logo'],
                                        'xmedium');
        // отсекаем телефон в логине
        $arRes['info']['email'] = filter_var(
                                        $arRes['info']['email'],
                                        FILTER_VALIDATE_EMAIL
                                    );

        // считываем характеристики пользователя
        $sql = Yii::app()->db->createCommand()
                        ->select("ua.id_us idus,
                                ua.val, 
                                ua.id_attr, 
                                uad.name, 
                                uad.key")
                        ->from('user_attribs ua')
                        ->leftjoin('user_attr_dict uad', 'uad.id=ua.id_attr')
                        ->where('ua.id_us=:idus', [':idus' => $id])
                        ->order('ua.id_attr asc')
                        ->queryAll();

        $arMess = array();
        foreach ($sql as $v)
        {
            if($v['key']=='mob') // преобразуем телефон
            {
                $v['phone'] = str_replace('+', '', $v['val']);
                $pos = strpos($v['phone'], '(');
                $v['phone-code'] = substr($v['phone'], 0, $pos);
                if(empty($v['phone-code']))
                    $v['phone-code'] = 7; // по умолчанию Рашка
                $v['phone'] = substr($v['phone'], $pos);
                $arRes['phone'] = $v['phone'];
                $arRes['phone-code'] = $v['phone-code'];
            }
            $v['key']=='viber' && $arMess[]='Viber';
            $v['key']=='whatsapp' && $arMess[]='WhatsApp';
            $v['key']=='telegram' && $arMess[]='Telegram';
            $v['key']=='googleallo' && $arMess[]='Google Allo';

            $arRes['attribs'][$v['key']] = $v;

        }
        $arRes['messengers'] = implode(',',$arMess);


        // считываем типы пользователя
        $arRes['cotype'] = Yii::app()->db->createCommand()
                        ->select('id, type, name')
                        ->from('user_attr_dict')
                        ->where('id_par=101')
                        ->order('id asc')
                        ->queryAll();

        foreach ($arRes['cotype'] as &$v)
            $v['selected'] = $arRes['info']['type']==$v['id'];
        unset($v);

        // считываем города пользователя
        $arRes['userCities'] = Yii::app()->db->createCommand()
                        ->select('ci.id_city, ci.name, ci.id_co id_country')
                        ->from('user_city uc')
                        ->leftjoin('city ci', 'ci.id_city=uc.id_city')
                        ->where('uc.id_user=:idus', [':idus' => $id])
                        ->queryRow();

        // считываем страны
        $arRes['countries'] = Yii::app()->db->createCommand()
                        ->select('id_co id, name, phone')
                        ->from('country')
                        ->where('hidden=0')
                        ->queryAll();

        // если попап закрыли
        $uid = filter_var(
                    Yii::app()->getRequest()->getParam('uid'),
                    FILTER_SANITIZE_NUMBER_INT
                );
        $cityId = filter_var(
                    Yii::app()->getRequest()->getParam('city'),
                    FILTER_SANITIZE_FULL_SPECIAL_CHARS
                );
        $phoneCode = filter_var(
                    Yii::app()->getRequest()->getParam('__phone_prefix'),
                    FILTER_SANITIZE_NUMBER_INT
                );

        if(empty($uid)) // эти проверки только для страницы редактирования профиля
        {
            if(!$cityId)
            {
                $cityId = Subdomain::getCacheData()->id;
            }

            Yii::app()->db->createCommand()
                    ->update('user_city',
                        ['id_city' => $cityId],
                        'id_user=:id',
                        [':id' => $id]
                    );
            $arRes['userCities']['id_city'] = $cityId;
            $arRes['userCities']['name'] = Yii::app()->db->createCommand()
                    ->select("name")
                    ->from('city')
                    ->where('id_city=:id',[':id'=>$cityId])
                    ->queryScalar();
        }
        else // подгон данных для попапа
        {
            $arGeo = (new Geo())->getUserGeo();
            if($arGeo['country']==1) // если РФ
            {
                $query = Yii::app()->db->createCommand()
                            ->select("*")
                            ->from('city')
                            ->where(
                                'name=:name AND id_co=1', // только РФ
                                [':name'=>$arGeo['city']]
                            )
                            ->queryRow();
            }
            else // иначе город субдомена
            {
                $query = Yii::app()->db->createCommand()
                            ->select("*")
                            ->from('city')
                            ->where(
                                'id_city=:id',
                                [':id'=>Subdomain::getCacheData()->id]
                            )
                            ->queryRow();
            }

            $arRes['userCities'] = array(
                    'id_city' => $query['id_city'],
                    'name' => $query['name'],
                    'id_country' => $query['id_co']
                );

            // Телефон
            foreach($arRes['countries'] as $v)
            {
                if(!empty($arRes['phone']) && $v['phone']==$arRes['phone-code'])
                { // регистрация через телефон
                    $arRes['userCities']['id_country'] = $v['id'];
                    break;
                }
                else if($arRes['info']['email'] && $v['id']==$arRes['userCities']['id_country'])
                { // регистрация через почту
                    $arRes['phone-code'] = $v['phone'];
                    $arRes['userCities']['id_country'] = $v['id'];
                }
            }
        }

        if(!$phoneCode && !$arRes['phone-code'])
        {
            $arGeo = (new Geo())->getUserGeo();
            foreach($arRes['countries'] as $v)
                $arGeo['country']==$v['id'] && $arRes['phone-code'] = $v['phone'];
        }

        return $arRes;
    }



    /**
     * ПОлучаем последнии 6 комментов
     * @param int $inID
     * @return mixed
     */
    private function getLastComments($inID = 0)
    {
        $id = $inID ?: $this->$this->exInfo->eid;
//        $id = $inID ?: Share::$UserProfile->exInfo->eid;

        // получаем рейтинг и уровень характеристик
        $sql = "SELECT co.id, co.message, co.isneg, DATE_FORMAT(co.crdate,'%d.%m.%y') as crdate
              , r.firstname fio, r.id_user
            FROM comments co
            INNER JOIN resume r ON co.id_promo = r.id
            WHERE co.id_empl = {$id}
                AND co.iseorp = 0
                AND co.isactive = 1
            ORDER BY id DESC
            LIMIT 6";
        $res = Yii::app()->db->createCommand($sql)->queryAll();
        $data['comments'] = $res;

        $sql = "SELECT COUNT(*) pos,
                        (SELECT COUNT(*)
                    FROM comments co
                    WHERE co.id_empl = {$id}
                        AND co.iseorp = 0
                        AND co.isactive = 1
                        AND isneg > 0) neg
            FROM `comments` co
            WHERE co.id_empl = {$id}
                AND co.iseorp = 0
                AND co.isactive = 1
                AND isneg = 0 ;";
        $res = Yii::app()->db->createCommand($sql)->queryRow();
        $data['count'] = array($res['pos'], $res['neg']);

        return $data;
    }



    private function getLastResponses($inID = 0)
    {
        $id = $inID ?: Share::$UserProfile->exInfo->id;
        // получаем рейтинг и уровень характеристик
        $sql = "SELECT j.id_jobs id, j.name_act name
                    , COUNT(v.id) cou
                FROM `jobs` j
                INNER JOIN vacation_stat v ON j.id_jobs = v.id_jobs AND isresponse = 1
                WHERE j.id_empl = {$id}
                GROUP BY j.id_jobs
                ORDER BY j.id_jobs DESC
                LIMIT 9";
//        $res = Yii::app()->db->createCommand($sql)->queryAll();
//        $data['jobs'] = $res;
        $data['jobs'] = array();

        $sql = "SELECT COUNT(j.id_jobs) FROM `jobs` j INNER JOIN vacation_stat v ON j.id_jobs = v.id_jobs AND isresponse = 1 WHERE j.id_empl = {$id}";
//        $res = Yii::app()->db->createCommand($sql)->queryScalar();
//        $data['count'] = $res;
        $data['count'] = array();

        return $data;
    }


    private function getLastJobs($inID = 0)
    {
        $id = $inID ?: Share::$UserProfile->exInfo->id;
        // получаем рейтинг и уровень характеристик
        $sql = "SELECT v.id, v.title, DATE_FORMAT(v.crdate, '%d.%m.%Y') crdate, DATE_FORMAT(v.remdate, '%d.%m.%Y') remdate,
            (SELECT COUNT(id) FROM emplv_discuss ed WHERE ed.id_vac=v.id) discuss_cnt
            FROM empl_vacations v
            WHERE v.id_user = {$id} AND v.status = 1 AND v.ismoder = 100 AND v.in_archive=0
            ORDER BY v.id DESC
            LIMIT 9";
        $res = Yii::app()->db->createCommand($sql)->queryAll();
        $data['jobs'] = $res;

        $sql = "SELECT COUNT(*) FROM empl_vacations v WHERE v.id_user = {$id} AND v.status = 1 AND v.ismoder = 100 AND v.in_archive=0";
        $res = Yii::app()->db->createCommand($sql)->queryScalar();
        $data['count'] = $res;

        return $data;
    }



    private function saveEmplCities()
    {
        $id = $this->exInfo->id;

        $cities = Yii::app()->getRequest()->getParam('cities');
        $idco = Yii::app()->getRequest()->getParam('country')[0];

        $insData = array();
        foreach ($cities as $key => $val)
        {
            // prepare cities
            if( intval($val) > 0 )
            {
                $insData[] = array('id_user' => $id, 'id_city' => $val);


            // prepare custom city
            } else {
                $cuci = $val; //Yii::app()->getRequest()->getParam('cityManualMulti');

                // search for same city
                $res = Yii::app()->db->createCommand()
                    ->select('ci.id_city id')
                    ->from('city ci')
                    ->where(array('and', "ci.id_co = :idco", "ci.name LIKE :city"), array(':idco' => $idco, ':city' => $cuci))
                    ->queryRow();

                // post exist
                if( $res['id'] ) $mId = $res['id'];
                // ins new city
                else
                {
                    $res = Yii::app()->db->createCommand()
                        ->insert('city', array(
                            'id_co' => $idco,
                            'name' => ucfirst($cuci),
                        ));

                    if( $res )
                    {
                        $mId = Yii::app()->db->createCommand('SELECT LAST_INSERT_ID()')
                            ->queryScalar();
                    }
                    else { $mId = 0; } // endif
                } // endif

                if( $mId ) $insData[] = array('id_user' => $id, 'id_city' => $mId);
            } // endif

        } // end foreach

        Yii::app()->db->createCommand()
            ->delete('user_city', 'id_user=:id_user', array(':id_user' => $id));
        $command = Yii::app()->db->schema->commandBuilder->createMultipleInsertCommand('user_city', $insData);
        $command->execute();
    }

    public function updateEmployer($data, $id) {
        if(empty($id) || $id<=0) return null;

        // Update table user_attribs
        if(isset($data['email'])) {
        $res = Yii::app()->db->createCommand()
            ->update('user', array(
                'email' => $data['email'],
                'ismoder' => 1,
//              'date_login' => date('Y-m-d H:i:s'),
            ), 'id_user=:id_user', array(':id_user' => $id));
        }

            Yii::app()->db->createCommand()
            ->update('user_city', array(
                'id_city' => $data['city'],
                ), 'id_user=:id_user', array(':id_user' => $id));

            Yii::app()->db->createCommand()
            ->update('employer', array(
                'name' => $data['name'],
                'firstname' => $data['firstname'],
                'lastname' => $data['lastname'],
                'position' => $data['post'],
                'phone' => $data['mob'],
                'type' => $data['type'],
                ), 'id_user=:id_user', array(':id_user' => $id));

        $attr = array();

        foreach($attr['userAttribs'] as $key=>$val) {
            Yii::app()->db->createCommand()
                ->update('user_attribs', array(
                    'val' => $val['val'],
                ), "id_us=:id_user and `key`=:key", array(':id_user' => $id, ':key' => $val['key']));
        }

        return array('error' => 0, 'message'=>'Saved successfully');
    }


    // сохраняем атрибуты пользователя
    private function saveUserAttribs($props=[])
    {
        $id =  $this->exInfo->id;

        $attrs =  Yii::app()->getRequest()->getParam('user-attribs');


        $insData = array();
        !isset($attrs['isnews']) && $attrs['isnews']=0;
        foreach ($attrs as $key => $val)
        {
            $keys[] = "'" . $key . "'";
            $res = Yii::app()->db->createCommand()
                ->select('d.id , d.type, d.key')
                ->from('user_attr_dict d')
                ->where('d.key = :key', array(':key' => $key))
                ->queryRow();

            if( $res['type'] == 3 )
            {

                $insData[] = array('id_us' => $id, 'id_attr' => $val, 'key' => $res['key'], 'type' => '3', 'crdate' => date('Y-m-d H:i:s'));
            }
            else
            {
                if($key == "mob" && $val!==''){ // Проверка изменения номера
                    $val = '+' . Yii::app()->getRequest()->getParam('__phone_prefix') . $val;
                    $mob = Yii::app()->db->createCommand()
                        ->select('val')
                        ->from('user_attribs')
                        ->where('id_attr=1 AND id_us=:idus', array(':idus' => Share::$UserProfile->id))
                        ->queryRow();

                    if(!empty($mob['val']) && $mob['val']!=$val){
                        $confMob = Yii::app()->db->createCommand()
                            ->update('user', array(
                            'confirmPhone' => 0,
                            ), 'id_user=:id_user', array(':id_user' => Share::$UserProfile->id));
                    }
                }
                $insData[] = array('id_us' => $id, 'id_attr' => $res['id'], 'key' => $res['key'], 'type' => $res['type'], 'val' => $val, 'crdate' => date('Y-m-d H:i:s'));
            }
        }

        $keys = join(',', $keys);
        $sql = "DELETE user_attribs FROM user_attribs 
                INNER JOIN user_attr_dict d ON user_attribs.id_attr = d.id AND d.key IN({$keys})
                WHERE id_us = {$id}";
        Yii::app()->db->createCommand($sql)->execute();

        $sql = "DELETE user_attribs FROM user_attribs 
                INNER JOIN user_attr_dict d ON d.key IN({$keys})
                INNER JOIN user_attr_dict d1 ON user_attribs.id_attr = d1.id AND d1.id_par = d.id
                WHERE id_us = {$id}";
        Yii::app()->db->createCommand($sql)->execute();

        if( count($insData) )
        {
            $command = Yii::app()->db->schema->commandBuilder->createMultipleInsertCommand('user_attribs', $insData);
            $command->execute();
        }
    }



    private function getUserInfo($inID = 0)
    {
        $id = $inID ?: $this->exInfo->id;

        // считываем характеристики пользователя
        $sql = "SELECT e.id,
                e.id_user,
                e.name,
                e.logo,
                e.rate,
                e.rate_neg,
                u.mdate,
                u.is_online,
                DATE_FORMAT(e.crdate, '%d.%m.%Y') date_public
            FROM employer e
            LEFT JOIN user u ON u.id_user = e.id_user
            WHERE e.id_user = {$id}";
        $res = Yii::app()->db->createCommand($sql)->queryRow();

        // время на сайте
        $d2 = new DateTime($res['date_public']);
        $diff = $d2->diff($d1);
        $months = ($diff->y * 12) + $diff->m;
        if($months < 6)
        {
          $res['time_on_site'] = 'Новичок';
        }
        else if($months < 12)
        {
          $res['time_on_site'] = 'Скоро 1 год';
        }
        else
        {
          $res['time_on_site'] = "Уже {$diff->y} год";
        }

        return $res;
    }
    /*
    *       Получаем фото пользователя
    */
    private function getUserPhotos($inID = 0)
    {
        $id = $inID ?: Share::$UserProfile->exInfo->eid;

        // считываем фото пользователя
        $sql = "SELECT p.id, p.photo
            FROM employer r
            LEFT JOIN user_photos p ON p.id_empl = r.id
            WHERE r.id = {$id} AND p.photo <> r.logo
            ORDER BY npp DESC";
        $data = (Yii::app()->db->createCommand($sql)->queryAll());

        return $data;
    }
    /*
    *       Получаем данные для нередактируемой страницы профиля
    */
    private function getProfileMainData($id){
        // читаем данные из профиля
        $sql = "SELECT u.email, e.id, e.id_user idus, e.type, e.name, e.firstname, e.lastname, e.contact, u.confirmEmail, u.confirmPhone, e.aboutme
            FROM employer e
            INNER JOIN user u ON u.id_user = e.id_user
            WHERE e.id_user = {$id}";
        $res = Yii::app()->db->createCommand($sql)->queryRow();
        $data['emplInfo'] = $res;

        // считываем характеристики пользователя
        $sql = "SELECT a.val, a.id_attr, d.name, d.type, d.key
            FROM employer e
            LEFT JOIN user u ON u.id_user = e.id_user
            LEFT JOIN user_attribs a ON e.id_user = a.id_us
            LEFT JOIN user_attr_dict d ON a.id_attr = d.id
            WHERE e.id_user = {$id}
            ORDER BY a.id_attr";
        $res = Yii::app()->db->createCommand($sql)->queryAll();

        foreach ($res as $key => $val)
            $attr[$val['id_attr']] = $val;
        $data['userAttribs'] = $attr;

        // считываем тип работодателя
        $sql = "SELECT d.id, d.type, d.name FROM user_attr_dict d WHERE d.id_par = 101 ORDER BY id";
        $data['cotype'] = Yii::app()->db->createCommand($sql)->queryAll();
        foreach ($data['cotype'] as $key => &$val)
            if( $data['emplInfo']['type'] == $val['id'] )
                $val['selected'] = 1;

        // read cities
        $sql = "
            SELECT 
                ci.id_city id, 
                ci.name, 
                co.id_co, 
                co.name coname, 
                ci.ismetro
            FROM 
                user_city uc
            LEFT JOIN 
                city ci 
                ON 
                uc.id_city = ci.id_city
            LEFT JOIN 
                country co 
                ON 
                co.id_co = ci.id_co
            WHERE 
                uc.id_user = {$id}
        ";
        $data['userCities'] = Yii::app()->db->createCommand($sql)->queryAll();

        if (isset($data['userCities']) && $data['userCities'] !== '') {
            $sql = "
                SELECT
                    ci.id_city,
                    ci.name,
                    uc.id_user
                FROM
                    user_city uc
                LEFT JOIN
                    city ci
                ON
                    uc.id_city = ci.id_city
                WHERE
                    uc.id_user = {$id}
            ";
        }
        $data['userCities'] = Yii::app()->db->createCommand($sql)->queryAll();

       // echo $sql;
       // die();

        return $data;
    }
    /*
    *   Проверка уникальности почты. Вызывается в ajaxController
    */
    public function emailVerification(){
        $oldEmail = Yii::app()->getRequest()->getParam('oemail');
        $newEmail = Yii::app()->getRequest()->getParam('nemail');
        $result = false;

        $res = Yii::app()->db->createCommand()
            ->select("email")
            ->from('user')
            ->where('email = :n AND email <> :o', array(':n' => $newEmail, ':o' => $oldEmail))
            ->queryRow();

        if($res['email']) $result = true;

        return $result;
    }
    /*
    *   Сохранение настроек
    */
    public function saveSettings($idus)
    {
        $email = filter_var(Yii::app()->getRequest()->getParam('email'), FILTER_VALIDATE_EMAIL);
        $phone = Yii::app()->getRequest()->getParam('phone');
        $oldPsw = Yii::app()->getRequest()->getParam('oldpsw');
        $newPsw = Yii::app()->getRequest()->getParam('newpsw');
        $arResult = array('error'=>0,'mess'=>'');

        if(strlen($email)>0){   // почта
            $arResult['type'] = 'email';
            $res = Yii::app()->db->createCommand()
                ->update('user', array(
                        'email' => $email,
                        'mdate' => date('Y-m-d H:i:s'),
                    ),
                    'id_user=:id',
                    array(':id' => $idus)
                );
            if(!$res)
                $arResult = ['error'=>1,'mess'=>'Ошибка сохранения почты','type'=>'email'];
        }
        elseif(strlen($oldPsw)>0 && strlen($newPsw)>0){ // пароль
            $arResult['type'] = 'psw';
            $oldPsw = md5($oldPsw);
            $newPsw = md5($newPsw);

            $user = Yii::app()->db->createCommand()
                ->select('u.passw, u.email')
                ->from('user u')
                ->where('u.id_user=:id', array(':id' => $idus))
                ->queryRow();

            if($user['passw']==$oldPsw){
                $res = Yii::app()->db->createCommand()
                    ->update('user', array(
                            'passw' => $newPsw,
                            'mdate' => date('Y-m-d H:i:s'),
                        ),
                        'id_user=:id',
                        array(':id' => $idus)
                    );

                if(!$res)
                {
                  $arResult = ['error'=>1, 'mess'=>'Ошибка сохранения пароля', 'type'=>'psw'];
                }
                else
                {
                  Mailing::set(18,['email_user'=>$user['email'], 'id_user'=>$idus]);
                }
            }
            else{
                $arResult = ['error'=>1, 'mess'=>'Старый пароль не подходит', 'type'=>'psw'];
            }
        }
        elseif(strlen($phone)>0){
            $arResult['type'] = 'phone';
            $res = Yii::app()->db->createCommand()

                ->update('user_attribs',
                    array('val' => $phone),
                    'id_us=:id AND id_attr=1',
                    array(':id' => $idus)
                );
            if(!$res)
                $arResult = ['error'=>1,'mess'=>'Ошибка сохранения телефона', 'type'=>'phone'];
        }
        return $arResult;
    }
    /*
    *      Проверка обязательных полей
    */
    public function checkRequiredFields(){
        $idus = $this->exInfo->id;
        $arResult = Yii::app()->db->createCommand()
            ->select('u.email, e.id_user, e.name, a.val phone, uc.id_city city')
            ->from('employer e')
            ->join('user u','u.id_user = e.id_user')
            ->leftJoin('user_attribs a', 'u.id_user = a.id_us AND a.id_attr=1')
            ->leftJoin('user_city uc', 'u.id_user = uc.id_user')
            ->where('u.id_user=:id', array(':id' => $idus))
            ->queryAll();

        $arResult = array_merge($arResult[0], ['fields'=>[]]);

        if(empty($arResult['name']))
            $arResult['fields'][] = '"Название компании"';
        if(empty($arResult['city']))
            $arResult['fields'][] = '"Город"';
        if(empty($arResult['email']))
            $arResult['fields'][] = '"Email"';
        if(empty($arResult['phone']))
            $arResult['fields'][] = '"Телефон"';

        if(sizeof($arResult['fields'])>0)
            $arResult['mess'] = 'Необходимо заполнить поля: ' . implode(', ', $arResult['fields']);

        return $arResult;
    }
    /**
     *  сохранение данных с попапа по ajax
     */
    public function savePopupData($data)
    {
        $id = $this->exInfo->id;
        $db = Yii::app()->db;
        // phone
        if(isset($data['phone']))
        {
            $phone = $db->createCommand()
                        ->select('val')
                        ->from('user_attribs')
                        ->where('id_attr=1')
                        ->queryScalar();

            if($phone)
            {
                $db->createCommand()->delete(
                        'user_attribs',
                        'id_attr=1 AND id_us=:id_user',
                        [':id_user'=>$id]
                    );
            }

            $db->createCommand()
                ->insert(
                    'user_attribs',
                    [
                        'id_us' => $id,
                        'id_attr' => 1,
                        'key' => 'mob',
                        'val' => '+'.$data['phone'],
                        'crdate' => date('Y-m-d H:i:s')
                    ]
                );
        }
        // city
        if(isset($data['city']))
        {
            $db->createCommand()
                 ->update('user_city',
                    ['id_city'=>$data['city']],
                    'id_user=:id_user',
                    [':id_user'=>$id]
                );
        }
        // contact
        if(isset($data['contact']))
        {
            $db->createCommand()
                ->update('employer',
                    [
                        'contact' => $data['contact'],
                        'firstname' => $data['contact'],
                        'lastname' => $data['contact']
                    ],
                    'id_user=:id_user',
                    [':id_user'=>$id]
                );
        }
        // company type
        if(isset($data['companyType']))
        {
            $arCompany = $db->createCommand()
                        ->select('id')
                        ->from('user_attr_dict')
                        ->where('id_par=:id',[':id'=>101])
                        ->queryColumn();

            if(in_array($data['companyType'], $arCompany))
            {
                $db->createCommand()
                    ->update('employer',
                        ['type' => $data['companyType']],
                        'id_user=:id_user',
                        [':id_user'=>$id]
                    );
            }
        }
    }
    /**
     *  сохранение данных с помощью виджета
     * @param $arData - array ['files'=>[0=>['name','oldname','path','linkTag','isImg','imgTag','signature']...]]
     */
    public function savePhoto($arData)
    {
        $query = Yii::app()->db->createCommand()
                    ->select('MAX(npp) npp, COUNT(*) cnt')
                    ->from('user_photos')
                    ->where('id_empl=:id',[':id'=>$this->exInfo->eid])
                    ->queryRow();

        // проверяем на допустимое кол-во фото
        if($query['cnt']>=$this->photosMax || !count($arData['files']))
        {
            return false;
        }

        $arInsert = array();
        $n=count($arData['files']);
        $npp = $query['npp'] + $n;
        for ($i=0; $i<$n; $i++)
        {
            // загружаем только допустимое кол-во
            if(($i + 1 + $query['cnt'])>$this->photosMax)
                continue;

            $file = pathinfo($arData['files'][$i]['name'], PATHINFO_FILENAME);
            // первое фото ставим главным
            $i==0 && $this->updateForPhoto($this->exInfo->eid, $file);

            $arInsert[] = [
                    'id_empl' => $this->exInfo->eid,
                    'id_user' => $this->id,
                    'npp' => $npp--,
                    'photo' => $file,
                    'signature' => filter_var(
                        $arData['files'][$i]['signature'],
                        FILTER_SANITIZE_FULL_SPECIAL_CHARS
                    )
                ];
        }
        // записываем в user_photos одним запросом
        Share::multipleInsert(['user_photos'=>$arInsert]);
        // устанавливаем что нужна модерация
        Yii::app()->db->createCommand()
            ->update('user', ['ismoder'=>0], 'id_user=:id', [':id'=>$this->id]);
        // уведомляем админа по почте
        Mailing::set(1, ['id_user'=>$this->id], self::$EMPLOYER);
    }
    /**
     *  сохранение одного фото при регистрации
     * @param $name - string
     */
    public function saveRegisterPhoto($name)
    {
      Yii::app()->db->createCommand()
        ->delete('user_photos','id_user=:id',[':id' => $this->id]);

      Yii::app()->db->createCommand()
        ->insert('user_photos',[
          'id_promo' => $this->exInfo->id_resume,
          'id_user' => $this->id,
          'npp' => 1,
          'photo' => $name,
          'signature' => ''
        ]);

      $this->updateForPhoto($this->exInfo->id_resume, $name);
    }
    /**
     *  сохранение данных с помощью виджета
     * @param $arData - array ['files'=>[0=>['name','oldname','path','linkTag','isImg','imgTag','signature']...]]
     */
    public function editPhoto($arData)
    {
        $query = Yii::app()->db->createCommand()
                    ->select('MAX(npp) npp, COUNT(*) cnt')
                    ->from('user_photos')
                    ->where('id_promo=:id',[':id'=>$this->exInfo->eid])
                    ->queryRow();

        // проверяем на допустимое кол-во фото
        if($query['cnt']>=$this->photosMax)
        {
            return false;
        }

        $arFile = reset($arData['files']);
        $oldPhoto = pathinfo($arFile['oldname'], PATHINFO_FILENAME);

        Yii::app()->db->createCommand()
            ->update(
                'user_photos',
                [
                    'photo' => pathinfo($arFile['name'], PATHINFO_FILENAME),
                    'signature'=>filter_var(
                        $arFile['signature'],
                        FILTER_SANITIZE_FULL_SPECIAL_CHARS
                    )
                ],
                "id_user=:id AND photo='{$oldPhoto}'",
                [':id'=>$this->id]
            );
        // если это был лого то надо поправить и employer
        $isLogo = Yii::app()->db->createCommand()
                    ->select('id_user')
                    ->from('employer')
                    ->where("logo='{$oldPhoto}'")
                    ->queryScalar();

        if($isLogo)
        {
            Yii::app()->db->createCommand()
                ->update(
                    'employer',
                    ['logo' => pathinfo($arFile['name'], PATHINFO_FILENAME)],
                    'id_user=:id',
                    [':id' => $this->id]
                );
        }
        // устанавливаем что нужна модерация
        Yii::app()->db->createCommand()
            ->update('user', ['ismoder'=>0], 'id_user=:id', [':id'=>$this->id]);
        // уведомляем админа по почте
        Mailing::set(1, ['id_user'=>$this->id], self::$EMPLOYER);
    }
}
