<?php

/**
 * Date: 18.02.2016
 * Time: 10:12
 */

class UserProfileEmpl extends UserProfile
{
    private $photosMax; // макс кол-во фоток у работодателя

    function __construct($inProps)
    {
        parent::__construct($inProps);
        $props = is_object($inProps) ? get_object_vars($inProps) : $inProps;

        $this->type = 3;

        if( $props['idProfile'] ) $this->exInfo = (object)array('eid' => $props['idProfile']);

        $this->photosMax = MainConfig::$EMPLOYER_MAX_PHOTOS;

        $this->viewTpl = MainConfig::$VIEWS_COMPANY_PROFILE_OWN;
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

            Yii::app()->db->createCommand()
                ->update('employer', array(
                    'logo' => $pathinfo['filename'],
                ), 'id = :id', array(':id' => $eid));


            Yii::app()->db->createCommand()
                ->insert('user_photos', array(
                    'id_empl' => $eid,
                    'id_user' => $id,
                    'npp' => $photosData['npp'] + 1,
                    'photo' => $pathinfo['filename'],
                ));


            $pathinfo = pathinfo($cropRes['file']);

            $cropRes['idfile'] = $pathinfo['filename'];

             $link = 'http://' . MainConfig::$SITE . '/admin/site/EmplEdit'. DS .$id;
        $message = sprintf("Пользователь <a href='%s'>%s</a> изменил данные профиля.
            <br />
            Изменены поля: Логотип компании|
            <br />
            Перейти на модерацию соискателя <a href='%s'>по ссылке</a>.",
            'https://' . MainConfig::$SITE . MainConfig::$PAGE_PROFILE_COMMON . DS . $id,
            $id,
           $link
        );
      
        Share::sendmail("mk0630733719@gmail.com", "Prommu.com Изменение профиля юзера" . $id, $message);
        Share::sendmail("susgresk@gmail.com", "Prommu.com Изменение профиля юзера" . $id, $message);  

         $res = Yii::app()->db->createCommand()
                ->update('user', array(
                    'ismoder' => 0,
                ), 'id_user=:id_user', array(':id_user' => $id));

        $res = Yii::app()->db->createCommand()
                ->update('employer', array(
                    'ismoder' => 0,
                ), 'id_user=:id_user', array(':id_user' => $id));

      
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

            Yii::app()->db->createCommand()
                ->update('employer', array(
                    'logo' => $props['data'],
                ), 'id = :id', array(':id' => $eid));


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

            Yii::app()->db->createCommand()
                ->update('employer', array(
                    'logo' => $photos[$ind]['photo'],
                ), 'id = :id', array(':id' => $eid));



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

            $res = (new UploadLogo())->delPhoto($photos[$ind]['photo']);

            // делаем предыдущую фотку главной
            if( $photos[$ind]['ismain'] )
            {
                if( count($photos) > 1 )
                {
                    Yii::app()->db->createCommand()
                        ->update('employer', array(
                            'logo' => $photos[1]['photo'],
                        ), 'id = :id', array(':id' => $eid));
                }
            } // endif
        } // endif
    }

//     public function proccessLogo()
//     {
//         $id = Share::$UserProfile->id;
//         $eid = Share::$UserProfile->exInfo->eid;

//         // crop logo, make thumbs
//         $UploadLogo = (new UploadLogo());
// //        $UploadLogo->delPhoto(Share::$UserProfile->exInfo->logo);
//         $cropRes = $UploadLogo->processCropLogo();

//         // save main logo to db
//         $pathinfo = pathinfo(Yii::app()->session['uplLogo']['file']);

// //        Yii::app()->db->createCommand()
// //            ->update('employer', array(
// //                'logo' => $pathinfo['filename'],
// //            ), 'id = :id', array(':id' => $eid));


// //        $pathinfo = pathinfo($cropRes['file']);
// //        $cropRes['idfile'] = $pathinfo['filename'];

//         return $cropRes;
//     }



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
     * Получаем позитивный и негативный рейтинги
     */
    public function getRateCount($inID = 0)
    {
        $id = $inID ?: Share::$UserProfile->id;

//        $sql = "SELECT SUM(p.rating) rpos, SUM(p.rating_neg) rneg FROM projects p WHERE p.id_empl = {$idempl}";
        $sql = "SELECT sum(m.rate) as rpos, sum(m.rate_neg) as rneg, m.id_point, m.descr 
            FROM (
              SELECT
                CASE WHEN rd.point >= 0 THEN rd.point ELSE 0 END AS rate,
                CASE WHEN rd.point < 0 THEN rd.point ELSE 0 END AS rate_neg,
                rd.id_point,
                r.descr
              FROM rating_details rd,
                   point_rating r
              WHERE id_user = {$id}
              AND r.id = rd.id_point
            ) m ";
        $res = Yii::app()->db->createCommand($sql)->queryRow();
        return array($res['rpos'], $res['rneg']);
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
                e.firstname,
                e.lastname,
                e.logo,
                u.email,
                u.crdate,
                u.is_online
            FROM employer e
            INNER JOIN user u ON u.id_user = e.id_user
            WHERE e.id_user = {$id}";
        $res = Yii::app()->db->createCommand($sql)->queryRow();
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
            $attr[$val['id_attr']] = $val;
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
//        $data = $this->getProfileData();
        $data['rating'] = $this->getPointRate();

        $res = $this->getProfileEditPageData($data = array());
        $data = array_merge($data, $res);

        return $data;
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
        else    // *** Сохраняем данные пользователя ***
        {
            $name = filter_var(Yii::app()->getRequest()->getParam('name'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $fname = filter_var(Yii::app()->getRequest()->getParam('fname'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $lname = filter_var(Yii::app()->getRequest()->getParam('lname'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $email = filter_var(Yii::app()->getRequest()->getParam('email'), FILTER_VALIDATE_EMAIL);
            $companyType = filter_var(Yii::app()->getRequest()->getParam('companyType'), FILTER_SANITIZE_NUMBER_INT);
            $cityManual = filter_var(Yii::app()->getRequest()->getParam('cityManualMulti'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $logo = filter_var(Yii::app()->getRequest()->getParam('logo'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $arrs = '';
            $bFlashFlag = false;

            $sql = "SELECT u.email, u.confirmEmail
              , e.id,
                e.id_user idus,
                e.type,
                e.name,
                e.firstname,
                e.lastname,
                e.photo,
                e.logo,
                e.crdate
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

            if($data['firstname'] != $fname){
                $arrs.='Имя|';
                $bFlashFlag = true;
            }
            if($data['lastname'] != $lname){
                $arrs.='Фамилия|';
                $bFlashFlag = true;
            }
           
            if($data['type'] != $companyType){
                $arrs.='Тип компании|';
                $bFlashFlag = true;
            }

            if($data['name'] != $name){
                $arrs.='Название|';
                $bFlashFlag = true;
            }

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

            // save resume
            
            // сохраняем лого
            if($logo)
            {
                $fields['logo'] = $logo;
                //$this->copyLogoFiles($logo);
                //$UploadLogo = (new UploadLogo());
                //$UploadLogo->delPhoto(Share::$UserProfile->exInfo->logo);
            }

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

            $link = 'http://' . MainConfig::$SITE . '/admin/site/EmplEdit'. DS .$id;

            if($arrs != '')
            {
                $fields = array(
                    'name' => $name,
                    'firstname' => $fname,
                    'lastname' => $lname,
                    'type' => $companyType,
                    'ismoder' => 0,
                    'mdate' => date('Y-m-d H:i:s'),
                );
            
                $res = Yii::app()->db->createCommand()
                    ->update('employer', $fields, 'id_user=:id_user', array(':id_user' => $id));
                // save user
                $res = Yii::app()->db->createCommand()
                    ->update('user', array(
                        'email' => $email,
                        'isblocked' => 0,
                        'ismoder' => 0,
                        'mdate' => date('Y-m-d H:i:s'),
                    ), 'id_user=:id_user', array(':id_user' => $id));

                $message = sprintf("Пользователь <a href='%s'>%s</a> изменил данные профиля.
                    <br />
                     Изменены поля: $arrs
                    <br />
                    Перейти на модерацию работодателя <a href='%s'>по ссылке</a>.",
                    'https://' . MainConfig::$SITE . MainConfig::$PAGE_PROFILE_COMMON . DS . $id,
                    Share::$UserProfile->exInfo->name,
                   $link
                );
                Share::sendmail("mk0630733719@gmail.com", "Prommu.com Изменение профиля юзера" . $id, $message);
                Share::sendmail("susgresk@gmail.com", "Prommu.com Изменение профиля юзера" . $id, $message);     
            }
            if($bFlashFlag)
            {
                Yii::app()->user->setFlash('Message', array('type' => 'succ', 'mess' => 'Изменения успешно сохранены и отправлены на модерацию'));
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



    private function getProfileEditPageData($inData)
    {
        $id = Share::$UserProfile->exInfo->id;

        // читаем данные из профиля
        $sql = "SELECT u.email
              , e.id,
                e.id_user idus,
                e.type,
                e.name,
                e.firstname,
                e.lastname,
                e.photo,
                e.logo,
                u.confirmEmail,
                u.confirmPhone,
                e.crdate
            FROM employer e
            INNER JOIN user u ON u.id_user = e.id_user
            WHERE e.id_user = {$id}";
        $res = Yii::app()->db->createCommand($sql)->queryRow();
        $data['emplInfo'] = $res;


        // считываем характеристики пользователя
        $sql = "SELECT e.id_user idus
              , a.val
              , a.id_attr
              , d.name
              , d.type
              , d.id_par idpar
              , d.key
              , u.confirmEmail
              , u.confirmPhone
              , u.email
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
            $attr[$val['id_attr']] = $val;
        } // end foreach
        // меняем данные для изменения кодов телефона
        if(isset($attr[1]['val'])){
            $pos = strpos($attr[1]['val'], '(');
            $attr[1]['phone-code'] = substr($attr[1]['val'], 1,($pos-1));
            $attr[1]['phone'] = substr($attr[1]['val'], $pos);     
        }     
        $data['userAttribs'] = $attr;


        // считываем тип работодателя
        $sql = "SELECT d.id, d.type, d.name FROM user_attr_dict d WHERE d.id_par = 101 ORDER BY id";
        $data['cotype'] = Yii::app()->db->createCommand($sql)->queryAll();
        foreach ($data['cotype'] as $key => &$val)
        {
            if( $data['emplInfo']['type'] == $val['id'] ) $val['selected'] = 1;
        } // end foreach


        // read cities
        $sql = "SELECT ci.id_city id, ci.name, co.id_co, co.name coname, ci.ismetro
FROM user_city uc
LEFT JOIN city ci ON uc.id_city = ci.id_city
LEFT JOIN country co ON co.id_co = ci.id_co
WHERE uc.id_user = {$id}";
        $data['userCities'] = Yii::app()->db->createCommand($sql)->queryAll();


        // считываем страны
        $sql = "SELECT id_co, name, phone FROM country co WHERE co.hidden = 0";
        $data['countries'] = Yii::app()->db->createCommand($sql)->queryAll();

        $sql = "SELECT p.id, p.photo, CASE WHEN p.photo = r.logo THEN 1 ELSE 0 END ismain
            FROM employer r
            LEFT JOIN user_photos p ON p.id_empl = r.id
            WHERE r.id_user = {$id}
            ORDER BY npp DESC";
        $res = Yii::app()->db->createCommand($sql)->queryAll();
        $data['userPhotos'] = $res;
        // if( count($data['userPhotos']) == 1 && !$data['userPhotos'][0]['id'] ) $data['userPhotos'] = array();

        return $data;
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
        $sql = "SELECT v.id, v.title, DATE_FORMAT(v.crdate, '%d.%m.%Y') crdate, DATE_FORMAT(v.remdate, '%d.%m.%Y') remdate
            FROM empl_vacations v
            WHERE v.id_user = {$id} AND v.status = 1 AND v.ismoder = 100
            ORDER BY v.id DESC
            LIMIT 9";
        $res = Yii::app()->db->createCommand($sql)->queryAll();
        $data['jobs'] = $res;

        $sql = "SELECT COUNT(*) FROM empl_vacations v WHERE v.id_user = {$id} AND v.status = 1 AND v.ismoder = 100";
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
        $attr['post'] = $data['post'];
        $attr['mob'] = $data['mob'];
        $attr['isnews'] = $data['isnews'];
        foreach($attr as $key=>$val) {
            Yii::app()->db->createCommand()
                ->update('user_attribs', array(
                    'val' => $val,
                ), "id_us=:id_user and `key`=:key", array(':id_user' => $id, ':key' => $key));
        }
    
    

        return array('error' => 0, 'message'=>'Saved successfully');
    }


    // сохраняем атрибуты пользователя
    private function saveUserAttribs($props=[])
    {
        $id =  $this->exInfo->id;

        $attrs =  Yii::app()->getRequest()->getParam('user-attribs');
    

        $insData = array();

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
                e.logo
            FROM employer e
            WHERE e.id_user = {$id}";
        $res = Yii::app()->db->createCommand($sql)->queryRow();

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
        $sql = "SELECT u.email, e.id, e.id_user idus, e.type, e.name, e.firstname, e.lastname, u.confirmEmail, u.confirmPhone
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
        $sql = "SELECT ci.id_city id, ci.name, co.id_co, co.name coname, ci.ismetro
            FROM user_city uc
            LEFT JOIN city ci ON uc.id_city = ci.id_city
            LEFT JOIN country co ON co.id_co = ci.id_co
            WHERE uc.id_user = {$id}";
        $data['userCities'] = Yii::app()->db->createCommand($sql)->queryAll();

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
    public function saveSettings($idus){
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
                $arResult = array('error'=>1,'mess'=>'Ошибка сохранения почты','type'=>'email');
        }
        elseif(strlen($oldPsw)>0 && strlen($newPsw)>0){ // пароль
            $arResult['type'] = 'psw';
            $oldPsw = md5($oldPsw);
            $newPsw = md5($newPsw);

            $user = Yii::app()->db->createCommand()
                ->select('u.passw')
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
                    $arResult = array('error'=>1,'mess'=>'Ошибка сохранения пароля','type'=>'psw');
            }
            else{
                $arResult = array('error'=>1,'mess'=>'Старый пароль не подходит','type'=>'psw');
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
                $arResult = array('error'=>1,'mess'=>'Ошибка сохранения телефона','type'=>'phone');
        }
        return $arResult;
    }
    /*
    *      Проверка обязательных полей
    */
    function checkRequiredFields(){
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
}
