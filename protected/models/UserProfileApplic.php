<?php

/**
 * Date: 18.02.2016
 * Time: 10:12
 */

class UserProfileApplic extends UserProfile
{
    private $profileFill;
    private $profileFillMax;
    private $wDays;
    private $idCities; // ID городов из блоков интерфейса
    private $photosMax; // макс кол-во фоток у соискателя


    function __construct($inProps)
    {
        parent::__construct($inProps);
        $props = is_object($inProps) ? get_object_vars($inProps) : $inProps;

        $this->type = 2;
        if( $props['idProfile'] ) $this->exInfo = (object)array('id_resume' => $props['idProfile']);


        $this->viewTpl = MainConfig::$VIEWS_APPLICANT_PROFILE_OWN;

        $this->profileFillMax = 24;//MainConfig::$PROFILE_FILL_MAX; // считаем кол-во и прописываем
        $this->profileFill = 0;
        $this->photosMax = MainConfig::$APPLICANT_MAX_PHOTOS;

        $this->wDays = (object)[];
        $this->wDays->mon = 'Понедельник';
        $this->wDays->tue = 'Вторник';
        $this->wDays->wed = 'Среда';
        $this->wDays->thu = 'Четверг';
        $this->wDays->fri = 'Пятница';
        $this->wDays->tha = 'Суббота';
        $this->wDays->sun = 'Воскресенье';
    }



    /**
     * Получаем позитивный и негативный рейтинги
     */
    public function getRateCount($inID = 0)
    {
        $id = $inID ?: Share::$UserProfile->exInfo->id;

        $sql = "SELECT sum(m.rate) as rate, sum(m.rate_neg) as rate_neg, m.id_point, m.descr 
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
        return array($res['rate'], $res['rate_neg']);
    }



    /**
     * Получаем кол-во позитивных и негативных отзывов
     */
    public function getCommentsCount($inID = 0)
    {
        $id = $inID ?: $this->exInfo->id_resume;

        $sql = "SELECT
            (SELECT COUNT(id) FROM comments mm WHERE mm.iseorp = 1 AND mm.isneg = 0 AND mm.isactive = 1 AND mm.id_promo = {$id}) commpos,
            (SELECT COUNT(id) FROM comments mm WHERE mm.iseorp = 1 AND mm.isneg = 1 AND mm.isactive = 1 AND mm.id_promo = {$id}) commneg";
        $res = Yii::app()->db->createCommand($sql)->queryRow();
        return array($res['commpos'], $res['commneg']);
    }



    public function getProfileDataView($inID = 0, $inIDpromo = 0)
    {
        if( !$inID ) $inID = $this->id;
        if( !$inIDpromo ) $inIDpromo = $this->exInfo->id_resume;

        $data = $this->getProfileData($inID, $inIDpromo);
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

        // считываем характеристики пользователя
        $sql = "SELECT DATE_FORMAT(r.birthday,'%d.%m.%Y') as bday -- , DATE_FORMAT(r.birthday,'%d') as bd
              , r.id_user, r.isman , r.ismed , r.smart, r.ishasavto , r.aboutme , r.firstname , r.lastname , r.photo
              , a.val , a.id_attr
              , d.name , d.type , d.id_par idpar , d.key
              , u.email, u.is_online
            FROM resume r
            LEFT JOIN user u ON u.id_user = r.id_user
            LEFT JOIN user_attribs a ON r.id_user = a.id_us
            LEFT JOIN user_attr_dict d ON a.id_attr = d.id
            WHERE r.id_user = {$id}
            ORDER BY a.id_attr";
        $res = Yii::app()->db->createCommand($sql)->queryAll();
        $rest = "SELECT ci.id_city id, ci.name, co.id_co, co.name coname, ci.ismetro, uc.street, uc.addinfo
            FROM user_city uc
            LEFT JOIN city ci ON uc.id_city = ci.id_city
            LEFT JOIN country co ON co.id_co = ci.id_co
            WHERE uc.id_user = {$id}";
        $data['city'] = Yii::app()->db->createCommand($rest)->queryAll();

        foreach ($res as $key => $val)
        {
            $data['userAttribs'][$val['id_attr']] = ['val' => $val['val'], 'id_attr' => $val['id_attr'], 'name' => $val['name'], 'type' => $val['type'], 'idpar' => $val['idpar'], 'key' => $val['key'],];
        } // end foreach

        $data['applicInfo'] = [
            'bday' => $val['bday'],
            'id_user' => $val['id_user'],
            'isman' => $val['isman'],
            'ismed' => $val['ismed'],
            'smart' => $val['smart'],
            'ishasavto' => $val['ishasavto'],
            'aboutme' => $val['aboutme'],
            'firstname' => $val['firstname'],
            'lastname' => $val['lastname'],
            'photo' => $val['photo'],
            'email' => $val['email'],
        ];


        // считываем фото пользователя
        $sql = "SELECT p.id, p.photo, CASE WHEN p.photo = r.photo THEN 1 ELSE 0 END ismain
            FROM resume r
            LEFT JOIN user_photos p ON p.id_promo = r.id
            WHERE r.id_user = {$id}
            ORDER BY npp DESC";
        $data['userPhotos'] = Yii::app()->db->createCommand($sql)->queryAll();
        if( count($data['userPhotos']) == 1 && !$data['userPhotos'][0]['id'] ) $data['userPhotos'] = array();

        return $data;
    }



    public function getProfileDataEdit()
    {
        $data = $this->getProfileData();
        $res = $this->getProfileEditPageData($data['userInfo']);
        $data = array_merge($data, $res);
        return $data;
    }



    public function getPointRate($inID = 0)
    {
        $id = $inID ?: $this->exInfo->id;
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
              WHERE id_user = {$id}
              AND r.id = rd.id_point
            ) m 
            GROUP BY m.id_point";
        $res = Yii::app()->db->createCommand($sql)->queryAll();
        $data['rate'] = $res;


        // получение название характеристик рейтинга
        $sql = "SELECT id, descr FROM `point_rating` where grp = 1";

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
        foreach ($inData['rate'] as $key => $val)
        {
            if( abs($val['rate'] - abs($val['rate_neg'])) > $maxPointRate ) $maxPointRate = abs($val['rate'] - abs($val['rate_neg']));

            // сумарные рейтинги по всем атрибутам
            $rate[0] += $val['rate'];
            $rate[1] += abs($val['rate_neg']);

            // рейтинги по атрибутам
            $pointRate[$val['id_point']][0] += $val['rate'];
            $pointRate[$val['id_point']][1] += abs($val['rate_neg']);
        } // end foreach


       
        

        return array('pointRate' => $pointRate,
                'rate' => $rate,
                'full' => $full,
                'countRate' => $rate[0] - $rate[1],
                'maxPointRate' => $maxPointRate,
                'rateNames' => $inData['rateNames'],
            );
    }



    /**
     * Получаем данные профиля из таблицы resume
     */
    public function getUserProfileData($inID)
    {
        $sql = "SELECT r.status
            FROM user r
            WHERE r.id_user = {$inID}";
        return Yii::app()->db->createCommand($sql)->queryRow();
    }



    /**
     * Сохраняем лого соискателя
     */
    public function proccessLogo()
    {
        $id = Share::$UserProfile->id;
        $id_resume = Share::$UserProfile->exInfo->id_resume;


        $sql = "SELECT MAX(p.npp) npp, COUNT(*) cou FROM user_photos p WHERE p.id_promo = {$id_resume}";
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
                ->update(
                    'resume', 
                    ['photo' => $pathinfo['filename'], 'ismoder' => 0],
                    'id_user = :id_user', 
                    [':id_user' => $id]
                );

            Yii::app()->db->createCommand()
                ->insert('user_photos', array(
                    'id_promo' => $id_resume,
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
          
            Mailing::send('EMAIL_CHANGE_PROFILE_LOGO', ['id_user'=>$id], 2);
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
        $id = $props['id']; 
        $sql = "SELECT  r.id
            FROM resume r
            LEFT JOIN user u ON u.id_user = r.id_user
            LEFT JOIN user_attribs a ON r.id_user = a.id_us
            LEFT JOIN user_attr_dict d ON a.id_attr = d.id
            WHERE r.id_user = {$id}
            ORDER BY a.id_attr";
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

        $sql = "SELECT MAX(p.npp) npp, COUNT(*) cou FROM user_photos p WHERE p.id_promo = {$id_resume}";
        /** @var $res CDbCommand */
        $photosData = Yii::app()->db->createCommand($sql);
        $photosData = $photosData->queryRow();

        // если не превышено кол-во фоток - сохраняем
        if( $photosData['cou'] < $this->photosMax )
        {
            // crop logo, make thumbs

            Yii::app()->db->createCommand()
                ->update('resume', array(
                    'photo' => $props['data'],
                ), 'id = :id', array(':id' => $id_resume));


            Yii::app()->db->createCommand()
                ->insert('user_photos', array(
                    'id_promo' => $id_resume,
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

    /**
     * Удаляем фото профиля
     */
    public function delProfilePhoto()
    {
        $id = filter_var(Yii::app()->getRequest()->getParam('del', 0), FILTER_SANITIZE_NUMBER_INT);
        $id_resume = Share::$UserProfile->exInfo->id_resume;

        // считываем фото пользователя
        $sql = "SELECT p.id, p.photo, CASE WHEN p.photo = r.photo THEN 1 ELSE 0 END ismain
            FROM resume r
            LEFT JOIN user_photos p ON p.id_promo = r.id
            WHERE r.id = {$id_resume}
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
                        ->update('resume', array(
                            'photo' => $photos[1]['photo'],
                        ), 'id = :id', array(':id' => $id_resume));
                }
            } // endif
        } // endif
    }



    /**
     * Сделать фото основным
     */
    public function setPhotoAsLogo()
    {
        $id = filter_var(Yii::app()->getRequest()->getParam('dm', 0), FILTER_SANITIZE_NUMBER_INT);
        $id_resume = Share::$UserProfile->exInfo->id_resume;

        // считываем фото пользователя
        $sql = "SELECT p.id, p.photo, CASE WHEN p.photo = r.photo THEN 1 ELSE 0 END ismain, npp
            FROM resume r
            LEFT JOIN user_photos p ON p.id_promo = r.id
            WHERE r.id = {$id_resume}
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
                ->update('resume', array(
                    'photo' => $photos[$ind]['photo'],
                ), 'id = :id', array(':id' => $id_resume));

            Yii::app()->db->createCommand()
                ->update('user_photos', array(
                    'npp' => $max + 1,
                ), 'id = :id', array(':id' => $photos[$ind]['id']));
        } // endif
    }



    public function saveProfileData()
    {
        $id = $this->exInfo->id;
        $idresume = $this->exInfo->id_resume;
        $res = $this->checkFieldsProfile();
     
        if($res['err']){// неправильно заполнены поля
            return $res;
        } 
        else{
            // *** Сохраняем данные пользователя ***
            $name = filter_var(Yii::app()->getRequest()->getParam('name'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $lastname = filter_var(Yii::app()->getRequest()->getParam('lastname'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $dateBirth = Yii::app()->getRequest()->getParam('bdate');
            $dateBirth = "{$dateBirth['y']}-{$dateBirth['m']}-{$dateBirth['d']}";
            $hasmedbook = Yii::app()->getRequest()->getParam('hasmedbook');
            $hasavto = Yii::app()->getRequest()->getParam('hasavto');
            $smart = Yii::app()->getRequest()->getParam('smart');
            $card = Yii::app()->getRequest()->getParam('card');
            $cardPrommu = Yii::app()->getRequest()->getParam('promm');
            $email = filter_var(Yii::app()->getRequest()->getParam('email'), FILTER_VALIDATE_EMAIL);
            $aboutme = filter_var(Yii::app()->getRequest()->getParam('about-mself'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $city = Yii::app()->getRequest()->getParam('city');
            $sex = Yii::app()->getRequest()->getParam('sex');
            // save resume
            $dates = date('Y-m-d', strtotime($dateBirth));

             $sql = "SELECT r.birthday -- , DATE_FORMAT(r.birthday,'%d') as bd
              , r.id_user, r.isman , r.ismed , r.smart, r.ishasavto , r.aboutme , r.firstname , r.lastname , r.photo
              , a.val , a.id_attr, r.smart
              , d.name , d.type , d.id_par idpar , d.key
              , u.email
            FROM resume r
            LEFT JOIN user u ON u.id_user = r.id_user
            LEFT JOIN user_attribs a ON r.id_user = a.id_us
            LEFT JOIN user_attr_dict d ON a.id_attr = d.id
            WHERE r.id_user = {$id}
            ORDER BY a.id_attr";
            $res = Yii::app()->db->createCommand($sql)->queryAll();
             $arrs = '';
            if($res[0]['firstname'] != $name){
                $arrs.='Имя|';
            } 
            if($res[0]['lastname'] != $lastname){
                $arrs.='Фамилия|';
            }
            if($res[0]['birthday'] != $dateBirth){
                $arrs.='День рождения|';
            }
            // if($res[0]['ismed'] != $hasmedbook){
            //     $arrs.='Мед.карта|';
            // }
            // if($res[0]['ishasavto'] != $hasavto){
            //     $arrs.='Авто|';
            // }
            // if($res[0]['smart'] != $smart){
            //     $arrs.='Смартфон|';
            // }
            $oldEmail = filter_var($res[0]['email'], FILTER_VALIDATE_EMAIL); // при условии что email на email похож
            if($oldEmail!='' && $oldEmail != $email){ 
                $resе = Yii::app()->db->createCommand()
                    ->update('user', 
                        array('confirmEmail' => 0), 
                        'id_user=:id_user',
                        array(':id_user' => Share::$UserProfile->id)
                    );
            }
            if($res[0]['aboutme'] != $aboutme){
                $arrs.='Информация о себе|';
            }


            // gender
            $res = Yii::app()->db->createCommand()
                ->update('resume', array(
                'isman' => $sex ? 1 : 0,
            ), 'id_user=:id_user', array(':id_user' => $id));
            // auto
            $res = Yii::app()->db->createCommand()
                ->update('resume', array(
                'ishasavto' => isset($hasavto) ? 1 : 0,
            ), 'id_user=:id_user', array(':id_user' => $id));
            // medbook
            $res = Yii::app()->db->createCommand()
                ->update('resume', array(
                'ismed' => isset($hasmedbook) ? 1 : 0,
            ), 'id_user=:id_user', array(':id_user' => $id));
            // smartphone
            $res = Yii::app()->db->createCommand()
                ->update('resume', array(
                'smart' => isset($smart) ? 1 : 0,
            ), 'id_user=:id_user', array(':id_user' => $id));
            // card
            $res = Yii::app()->db->createCommand()
                ->update('resume', array(
                'card' => isset($card) ? 1 : 0,
            ), 'id_user=:id_user', array(':id_user' => $id));
            // prommu card
            $res = Yii::app()->db->createCommand()
                ->update('resume', array(
                'cardPrommu' => isset($cardPrommu) ? 1 : 0,
            ), 'id_user=:id_user', array(':id_user' => $id));

            $res = Yii::app()->db->createCommand()
                ->update('resume', array(
                'firstname' => $name,
                'lastname' => $lastname,
                'aboutme' => $aboutme,
                'birthday' =>  $dates,
                'mdate' => date('Y-m-d H:i:s'),
                'ismoder' => 0,
                
            ), 'id_user=:id_user', array(':id_user' => $id)); 

            if(count($city)){
                $insData = array();
                foreach ($city as $idcity){
                    $insData[] = array('id_resume'=>$idresume, 'id_user'=>$id, 'id_city'=>$idcity, 'street'=>NULL, 'addinfo'=>NULL);
                }
                if(count($insData)){
                    Yii::app()->db->createCommand()->delete('user_city', 'id_user=:id_user', array(':id_user' => $id));
                    $command = Yii::app()->db->schema->commandBuilder->createMultipleInsertCommand('user_city', $insData);
                    $res = $command->execute();
                }
            }
           
            // атрибуты пользователя
            $this->saveUserAttribs();
            //ЦЕЛЕВАЯ ВАКАНСИЯ
            $this->saveUserPosts();
            // метро
            $this->saveUserMetroes();
            // рабочие часы
            $this->saveUserTimePeriods();
            // должности на которых работал
            $this->saveUserPostsWorked();
            // сохранение языков
            $this->saveUserLang();
        } // endif

        $res = Yii::app()->db->createCommand()
            ->update('user', array(
                'email' => $email,
                'isblocked' => 0,
                'ismoder' => 0,
                'mdate' => date('Y-m-d H:i:s'),
            ), 'id_user=:id_user', array(':id_user' => $id));

        if($arrs != ''){
        $link = 'http://' . MainConfig::$SITE . '/admin/site/PromoEdit'. DS .$id;
        $message = sprintf("Пользователь <a href='%s'>%s</a> изменил данные профиля.
            <br />
            Изменены поля: $arrs
            <br />
            Перейти на модерацию соискателя <a href='%s'>по ссылке</a>.",
            'https://' . MainConfig::$SITE . MainConfig::$PAGE_PROFILE_COMMON . DS . $id,
            $name,
           $link
        );
      
        Share::sendmail("mk0630733719@gmail.com", "Prommu.com Изменение профиля юзера" . $id, $message);
        Share::sendmail("susgresk@gmail.com", "Prommu.com Изменение профиля юзера" . $id, $message);  
        } 

        Yii::app()->user->setFlash('Message', array('type' => 'succ', 'mess' => 'Изменения успешно сохранены и отправлены на модерацию'));
    }



    /**
     * Создаём модель рейтинга
     * @return Rate
     */
    public function makeRate($inProps)
    {
        return new RateApplic($inProps);
    }



    /**
     * фабрика модели отклика
     * @return Responses
     */
    public function makeResponse()
    {
        return new ResponsesApplic($this);
    }



    /**
     * фабрика модели чата
     * @return Im
     */
    public function makeChat()
    {
        return new ImApplic($this);
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
            ")
            ->from('user u')
            ->leftJoin('user_work w', 'u.id_user = w.id_user')
            ->leftJoin('resume r', 'r.id_user = u.id_user AND u.status = 2')
            ->leftJoin('employer e', 'e.id_user = u.id_user AND u.status = 3')
            ->where('u.id_user=:id_user', array(':id_user' => $inId))
            ->queryRow();

        return $res;
    }



    /**
     * Целевая вакансия
     */
    private function saveUserPosts()
    {
        $id = $this->exInfo->id;

        $posts = Yii::app()->getRequest()->getParam('post');

        $builder=Yii::app()->db->schema->commandBuilder;
        $insData = array();

        if( $posts )
        {
            foreach ($posts as $key => $val)
            {
                $insData[] = array('id_us' => $id, 'id_mech' => $key, 'isshow' => '0', 'mech' => $val['name'], 'pay' => $val['payment'], 'pay_type' => $val['hwm'], 'crdate' => date("Y-m-d H:i:s"));
            } // end foreach


            Yii::app()->db->createCommand()
                ->delete('user_mech', array('and', 'id_us=:id_user', 'isshow=0'), array(':id_user' => $id));
            $command = $builder->createMultipleInsertCommand('user_mech', $insData);
            $command->execute();
        } // endif
    }



    // сохраняем атрибуты пользователя
    private function saveUserAttribs()
    {
        $id = $this->exInfo->id;

        $attrs = Yii::app()->getRequest()->getParam('user-attribs');

        $insData = array();

        foreach ($attrs as $key => $val)
        {
            $keys[] = "'" . $key . "'";
            $res = Yii::app()->db->createCommand()
                ->select('d.id , d.type, d.key')
                ->from('user_attr_dict d')
                ->where('d.key = :key', array(':key' => $key))
                ->queryRow();

            if( $val != 'aa' )
            {
                if( $res['type'] == 3 )
                {
                    $insData[] = array('id_us' => $id, 'id_attr' => $val, 'key' => $res['key'], 'type' => '3', 'crdate' => date('Y-m-d H:i:s'));
                }
                else
                {
                    if($key == "mob" && $val!=''){ // Проверка изменения номера
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
                } // endif
            } // endif
        } // end foreach

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
        } // endif
    }



    // сохраняем вакансии на которых работал
    private function saveUserPostsWorked()
    {
        $id = $this->exInfo->id;

        $posts = Yii::app()->getRequest()->getParam('donjnost-exp');
        $expLevels = Yii::app()->getRequest()->getParam('exp');

        if( $posts )
        {
            $insData = array();
            foreach ($posts as $key => $val)
            {

                    $idattr = $expLevels[$val]['level'] ?: 0;
                    $insData[] = array('id_us' => $id, 'id_mech' => $val, 'mech' => $expLevels[$val]['name'], 'id_attr' => $idattr, 'isshow' => '1', 'crdate' => date("Y-m-d H:i:s"));

            } // end foreach

            Yii::app()->db->createCommand()
                ->delete('user_mech', array('and', 'id_us=:id_user', 'isshow=1'), array(':id_user' => $id));
            $command = Yii::app()->db->schema->commandBuilder->createMultipleInsertCommand('user_mech', $insData);
            $command->execute();
        } else {
            Yii::app()->db->createCommand()
                ->delete('user_mech', array('and', 'id_us=:id_user', 'isshow=1'), array(':id_user' => $id));
        } // endif
    }



    // сохранение языков
    private function saveUserLang()
    {
        $id = $this->exInfo->id;
        $idresume = $this->exInfo->id_resume;

        $langs = Yii::app()->getRequest()->getParam('langs');
        $langLvls = Yii::app()->getRequest()->getParam('lang-level');

        if( count($langs) )
        {
            foreach ($langs as $key => $val)
            {
                $insData[] = array('id_us' => $id, 'id_attr' => $val, 'key'=> NULL, 'type' => '2', 'val' => ''/*$langLvls[$val]*/, 'crdate' => date('Y-m-d H:i:s'));
            } // end foreach

            $sql = "DELETE user_attribs FROM user_attribs 
                    INNER JOIN user_attr_dict d ON user_attribs.id_attr = d.id AND d.id_par = 40
                    WHERE id_us = {$id}";
            Yii::app()->db->createCommand($sql)->execute();

            $command = Yii::app()->db->schema->commandBuilder->createMultipleInsertCommand('user_attribs', $insData);
            $res = $command->execute();
        } // endif
    }



    // insert user_city
    private function saveUserCities()
    {
        $id = $this->exInfo->id;
        $idresume = $this->exInfo->id_resume;

        $cities = Yii::app()->getRequest()->getParam('cities');
        $city = Yii::app()->getRequest()->getParam('city');
        $street = Yii::app()->getRequest()->getParam('street');
        $addinfo = Yii::app()->getRequest()->getParam('custom-place-work');
        $idco = Yii::app()->getRequest()->getParam('country')[0];

        $insData[] = array('id_resume' => $idresume, 'id_city' => $city);
         
       

        if( count($cities) )
        {
            foreach ($cities as $key => $val)
            {
                // search for city
                $res = Yii::app()->db->createCommand()
                    ->select('ci.id_city idcity, ci.name')
                    ->from('city ci')
                    ->where(array('and', 'ci.ishide = 0', 'ci.id_co = :idco', 'ci.name LIKE :city'),
                            array(':idco' => $idco, ':city' => $val))
        //                    ->where(array('and', 'grp = APPT', 'val = :post'), array(':post' => $cudo['name']))
                    ->queryRow();

                // city exist
                if( $res['idcity'] ) $mId = $res['idcity'];
                // ins new city
                else
                {
                    $res = Yii::app()->db->createCommand()
                        ->insert('city', array(
                            'id_co' => $idco,
                            'name' => ucfirst($val),
                        ));

                    if( $res )
                    {
                        $mId = Yii::app()->db->createCommand('SELECT LAST_INSERT_ID()')
                            ->queryScalar();
                    }
                    else { $mId = 0; } // endif
                } // endif

                if( $mId ) {
                    $insData[] = array('id_resume' => $idresume, 'id_user' => $id, 'id_city' => $mId, 'street' => $street[$key], 'addinfo' => $addinfo[$key]);

                    $this->idCities[$key] = $mId;
                }
            } // end foreach


            Yii::app()->db->createCommand()
                ->delete('user_city', 'id_user=:id_user', array(':id_user' => $id));
            $command = Yii::app()->db->schema->commandBuilder->createMultipleInsertCommand('user_city', $insData);
            $res = $command->execute();
        } // endif
    }



    // сохранение метро
    private function saveUserMetroes()
    {
        $id = $this->exInfo->id;
        $idresume = $this->exInfo->id_resume;

        $metroes = Yii::app()->getRequest()->getParam('metro');

        if( $metroes )
            foreach ($metroes as $key => $val)
            {
                $insData[] = array('id_resume' => $idresume, 'id_us' => $id, 'id_metro' => $val);
            } // end foreach


        Yii::app()->db->createCommand()
            ->delete('user_metro', 'id_us=:id_user', array(':id_user' => $id));

        if( count($metroes) )
        {
            $command = Yii::app()->db->schema->commandBuilder->createMultipleInsertCommand('user_metro', $insData);
            $res = $command->execute();
        } // endif
    }



    // сохранение времени работы
    private function saveUserWorkTimes()
    {
        $id = $this->exInfo->id;
        $idresume = $this->exInfo->id_resume;

        $cities = $this->idCities;
        $wDays = Yii::app()->getRequest()->getParam('week-day');


        foreach ($wDays as $key => $val)
        {
            $i = 1;
            foreach ($this->wDays as $key2 => $val2)
            {
                if( $val[$key2]['ch'] == 'on' )
                {
                    $btime = $val[$key2]['f'] ?: '';
                    $etime = $val[$key2]['t'] ?: '';

                    if( $btime || $etime )
                    {
                        $arr = explode(':', $btime);
                        $btime = $arr[0] * 60 + $arr[1];
                        $arr = explode(':', $etime);
                        $etime = $arr[0] * 60 + $arr[1];
                        $insData[] = array('id_city' => $cities[$key], 'id_us' => $id, 'wday' => $i, 'timeb' => $btime, 'timee' => $etime);
                    } // endif
                } // endif

                $i++;
            } // end foreach
        } // end foreach


        Yii::app()->db->createCommand()
            ->delete('user_wtime', 'id_us=:id_user', array(':id_user' => $id));
        if( count($insData) )
        {
            $command = Yii::app()->db->schema->commandBuilder->createMultipleInsertCommand('user_wtime', $insData);
            $res = $command->execute();
        } // endif
    }



    // chk for insert or update User Attribs
    private function checkUserAttribsCRUD($inKey, $inVal, $inUsId)
    {
        $Q1 = Yii::app()->db->createCommand()
            ->select('a.val, a.id_us idus, ud.id AS idattr, ud.name namec, d.id, d.name, d.id_par AS ispar, d.key, d.type')
            ->from('user_attr_dict d')
            ->leftJoin('user_attr_dict ud', 'd.id = ud.id_par')
            ->leftJoin('user_attribs a', array('and', 'd.id = a.id_attr', 'id_us = '.$inUsId))
            ->where('d.`key` = :key', array(':key' => $inKey));
        $res = $Q1->queryAll();

        $flag = 0;
        $ret = array();
        foreach ($res as $key => $val)
        {
            if( $val['idus'] )
            {
                if( $val['type'] == 3 )
                {
                    if( $val['idattr'] != $inVal ) $ret[] = array('oper' => 3, 'id' => $val['id']);
                    else $flag || $flag = 1;
                }
                else
                {
                    if( $val['type'] != 3 && $inVal == '' ) return array('oper' => 3, 'id' => $val['id']);
                    elseif( $val['type'] != 3 && $val['val'] != $inVal ) return array('oper' => 2, 'id' => $val['id']);
                    return array('oper' => 0, 'id' => $val['id']);
                } // endif
            }
            else
            {
                if( $val['type'] != 3 )
                {
                    if( $inVal != '' ) return array('oper' => 1, 'id' => $val['id']);
                    return array('oper' => 0, 'id' => $inVal);
                }
                else
                {
                } // endif
            } // endif
        } // end foreach


        if( !$flag ) $ret[] = array('oper' => 1, 'id' => $inVal);

        return $ret;
    }


    private function checkFieldsProfile()
    {
        $ret = array('err' => 0,);

        $val = Yii::app()->getRequest()->getParam('name');
        if( trim($val) == '' )
        {
            $ret = array('err' => 1,
                'item' => 'name',
                'msg' => 'Введите Имя',
                );
        } // endif


        $val = Yii::app()->getRequest()->getParam('lastname');
        if( !$ret['err'] && trim($val) == '' )
        {
            $ret = array('err' => 1,
                'item' => 'lastname',
                'msg' => 'Введите Фамилию',
                );
        } // endif


        $val = Yii::app()->getRequest()->getParam('email');
        if( !$ret['err'] )
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
                    );
        } // endif

        $val = Yii::app()->getRequest()->getParam('bdate');
        if( !$ret['err'] && ($val['y'] == 'aa' || $val['m'] == 'aa' || $val['d'] == 'aa' ) )
        {
            $ret = array('err' => 1,
                'item' => 'bdate',
                'msg' => 'Введите правильно Дату рождения',
                );
        } // endif

        return $ret;
    }



    public function getProfileData($inID = 0, $inIDpromo = 0)
    {
        $data['rating'] = $this->getPointRate($inID);
        $data['lastJobs'] = $this->getLastJobs($inIDpromo);
        $data['userInfo'] = $this->getUserInfo($inID);
        if( $data['userInfo']['userMetro'] ) {
            foreach ($data['userInfo']['userMetro'] as $val) { $metro[] = $val['name']; }
            $data['userInfo']['userMetro'] = array($data['userInfo']['userMetro'], join(', ', $metro));
        }

        $data['lastComments'] = $this->getLastComments($inIDpromo);
        $data['profileEffect'] = floor($this->profileFill / $this->profileFillMax * 100);
        $data['profileEffect'] = $data['profileEffect']>100 ? 100 : $data['profileEffect'];

        return $data;
    }



    private function getProfileEditPageData($inData)
    {
        $id = $this->exInfo->id;

        // считываем страны
        $sql = "SELECT id_co, name, phone FROM country co WHERE co.hidden = 0";
        $data['countries'] = Yii::app()->db->createCommand($sql)->queryAll();


        // считываем должности
        $sql = "SELECT m.id , m.`key` , m.name val FROM user_attr_dict m WHERE m.id_par = 110  ORDER BY npp, val";
        $data['posts'] = Yii::app()->db->createCommand($sql)->queryAll();

        foreach ($data['posts'] as $key => &$val)
        {
            $flag1 = $flag2 = 0;
            foreach ($inData['userDolj'][0] as $val2)
            {
                if( $val['id'] == $val2['idpost'] )
                {
                    if( $val2['isshow'] == '0' ) $val['isshow1'] = 1;
                    if( $val2['isshow'] == '1' ) $val['isshow2'] = 1;
                }
            }
        }

        // считываем опыт
        $sql = "SELECT d.id, d.type, d.name FROM user_attr_dict d WHERE d.id_par = 31 ORDER BY id";
        $data['expir'] = Yii::app()->db->createCommand($sql)->queryAll();

        // характиристики пользователя из словаря
        $sql = "SELECT d.id, d.id_par idpar, d.type, d.name FROM user_attr_dict d WHERE d.id_par IN(11,12,13,14,15,16,69,40) ORDER BY idpar, id";
        $data['userDictionaryAttrs'] = Yii::app()->db->createCommand($sql)->queryAll();

        // языки словаря
        $sql = "SELECT d.id, d.id_par idpar, d.type, d.name , a.`key`, a.val
                FROM user_attr_dict d 
                LEFT JOIN user_attribs a ON a.id_attr = d.id AND a.id_us = {$id}
                WHERE d.id_par = 40
                ORDER BY name";
        $data['langs'] = Yii::app()->db->createCommand($sql)->queryAll();

        foreach ($data['langs'] as $key => &$val) { if ($val['val'] > 0 ) $data['langsSeled'][$val['id']] = $val['val']; }

        $data['langsLvls'] = array(array("1", "Начальный"), array("2", "Средний"), array("3", "Высокий"), array("4", "Продвинутый"));
        
        $data['months'] = array(0=>'Январь',1=>'Февраль',2=>'Март',3=>'Апрель',4=>'Май',5=>'Июнь',6=>'Июль',7=>'Август',8=>'Сентябрь',9=>'Октябрь',10=>'Ноябрь',11=>'Декабрь');

        return $data;
    }



    private function getUserInfo($inID = 0)
    {
        $id = $inID ?: $this->exInfo->id;

        // считываем характеристики пользователя
        $sql = "SELECT DATE_FORMAT(r.birthday,'%d.%m.%Y') as bday, r.id
              , r.id_user, r.isman , r.ismed , r.smart,  r.ishasavto , r.aboutme , r.firstname , r.lastname , r.photo
              , a.val , a.id_attr, u.confirmPhone, u.confirmEmail
              , d.name , d.type , d.id_par idpar , d.key
              , u.email, card, cardPrommu, u.is_online
              , r.index, r.meta_h1, r.meta_title, r.meta_description
            FROM resume r
            LEFT JOIN user u ON u.id_user = r.id_user
            LEFT JOIN user_attribs a ON r.id_user = a.id_us
            LEFT JOIN user_attr_dict d ON a.id_attr = d.id
            WHERE r.id_user = {$id}
            ORDER BY a.id_attr";
        $res = Yii::app()->db->createCommand($sql)->queryAll();

        foreach ($res as $key => $val){
            $attr[$val['id_attr']] = $val;
        }

        foreach ($attr as $k => $attrib){
            if( 
                ($attrib['id_attr'] <> 0 // без общего 
                && $attrib['key'] <> 'icq' // без ICQ 
                && $attrib['idpar'] <> 40 // без языков
                && strpos($attrib['key'],'dmob')===false // без доп телефонов
                && !empty($attrib['val'])) // и чтобы значение было заполнено
                ||
                in_array($attrib['idpar'], [11,12,13,14,15,16,69]) // для параметров с выбором
            )
                $this->profileFill++;
        }
        // меняем данные для изменения кодов телефона
        if(isset($attr[1]['val'])){
            $pos = strpos($attr[1]['val'], '(');
            $attr[1]['phone-code'] = substr($attr[1]['val'], 1,($pos-1));
            $attr[1]['phone'] = substr($attr[1]['val'], $pos);     
        }

        $data['userAttribs'] = $attr;



        // считываем фото пользователя
        $sql = "SELECT p.id, p.photo, CASE WHEN p.photo = r.photo THEN 1 ELSE 0 END ismain
            FROM resume r
            LEFT JOIN user_photos p ON p.id_promo = r.id
            WHERE r.id_user = {$id}
            ORDER BY npp DESC";
        $data['userPhotos'] = Yii::app()->db->createCommand($sql)->queryAll();
        if( count($data['userPhotos']) == 1 && !$data['userPhotos'][0]['id'] ) $data['userPhotos'] = array();

        // read cities
        $sql = "SELECT ci.id_city id, ci.name, co.id_co, co.name coname, ci.ismetro, uc.street, uc.addinfo
                FROM user_city uc
                LEFT JOIN city ci ON uc.id_city = ci.id_city
                LEFT JOIN country co ON co.id_co = ci.id_co
                WHERE uc.id_user = {$id}";
        $res = Yii::app()->db->createCommand($sql)->queryAll();

        foreach ($res as $key => $val):
            $cityPrint[$val['id']] = $val['name'];
            $city[$val['id']] = array(
                'id' => $val['id'], 
                'name' => $val['name'], 
                'ismetro' => $val['ismetro'],
                'street' => $val['street'], 
                'addinfo' => $val['addinfo'],
                'region' => $val['region']
            );
        endforeach;

        $data['userCities'] = array($city, array('id' => $res[0]['id_co'], 'name' => $res[0]['coname']), $cityPrint);
        if( count($city) ) $this->profileFill++;

        // read metro
        $sql = "SELECT m.id, m.id_city idcity, m.name FROM user_metro um
                LEFT JOIN metro m ON um.id_metro = m.id
                WHERE um.id_us = {$id} ORDER BY name";
        $res = Yii::app()->db->createCommand($sql)->queryAll();
        foreach ($res as $key => $val):
            $metro[$val['id']] = array('idcity' => $val['idcity'], 'name' => $val['name']);
        endforeach;
        $data['userMetro'] = $metro;


        // read week times
        $dayNames = array('Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб', 'Вс');
        $sql = "SELECT t.id_city idcity, t.wday, t.timeb, t.timee FROM user_wtime t WHERE t.id_us = {$id}";
        $res = Yii::app()->db->createCommand($sql)->queryAll();
        foreach ($res as $key => $val):
            $val['wdayName'] = $dayNames[$val['wday']-1];
            $h = floor($val['timeb'] / 60);
            $m = $val['timeb'] - $h * 60;
            $val['timeb'] = sprintf('%d:%02d', $h, $m);
            $h = floor($val['timee'] / 60);
            $m = $val['timee'] - $h * 60;
            $val['timee'] = sprintf('%d:%02d', $h, $m);
            $wdays[$val['idcity']][$val['wday']] = $val;
        endforeach;
        $data['userWdays'] = $wdays;


        // должности, отработанные и желаемые
        $sql = "SELECT r.id
              , um.isshow, um.pay, um.pay_type pt, um.pay_type, um.id_attr, um.mech
              , d1.name pname
              , d.name val, d.id idpost
            FROM resume r
            INNER JOIN user_mech um ON um.id_us = r.id_user
            LEFT JOIN user_attr_dict d1 ON d1.id = um.id_attr
            INNER JOIN user_attr_dict d ON d.id = um.id_mech 
            WHERE r.id_user = {$id}
            ORDER BY um.isshow, val";
        $res = Yii::app()->db->createCommand($sql)->queryAll();

        $exp = array();
        $flagPF = 0;
        foreach ($res as $key => $val)
        {
            if( $val['pay_type'] == 1 ) $res[$key]['pay_type'] ='руб/неделю';
            elseif( $val['pay_type'] == 2 ) $res[$key]['pay_type'] ='руб/месяц';
            else $res[$key]['pay_type'] ='руб/час';

            if( $val['isshow'] ) $exp[] = $val['val'];

            if( !$val['isshow'] ) $flagPF || $flagPF = 1;
        } // end foreach
        $data['userDolj'] = array($res, join(', ', $exp));
        if( $flagPF ) $this->profileFill++;
        if( count($exp) ) $this->profileFill++;



        // просмотры
        if( $this->exInfo->id_resume )
        {
            $sql = "SELECT COUNT(*) FROM resume_stat rs WHERE rs.id_resume = {$this->exInfo->id_resume}";
            $res = Yii::app()->db->createCommand($sql)->queryScalar();
            $data['viewCount'] = $res;
        } // endif


        return $data;
    }



    /**
     * Получаем последние вакансии пользователя
     * @param int $inIDresume
     * @return array
     */
    private function getLastJobs($inIDresume = 0)
    {
        $id = $inIDresume ?: $this->exInfo->id_resume;

        $sql = "SELECT v.id, v.title, DATE_FORMAT(v.crdate, '%d.%m.%Y') crdate, DATE_FORMAT(v.remdate, '%d.%m.%Y') remdate
              , e.id_user idus, e.name, e.logo
            FROM empl_vacations v
            INNER JOIN vacation_stat vs ON vs.id_vac = v.id 
            INNER JOIN employer e ON e.id_user = v.id_user
            WHERE vs.id_promo = {$id}
              AND " . Vacancy::getScopesCustom(Vacancy::$SCOPE_APPLIC_WORKING, 'vs') . "
            ORDER BY v.id DESC
            LIMIT 9";
        $res = Yii::app()->db->createCommand($sql)->queryAll();
        $data['jobs'] = $res;

        $sql = "SELECT COUNT(vs.id) cou
                FROM empl_vacations v
                INNER JOIN vacation_stat vs ON vs.id_vac = v.id 
                WHERE vs.id_promo = {$id} 
                  AND " . Vacancy::getScopesCustom(Vacancy::$SCOPE_APPLIC_WORKING, 'vs');
        $res = Yii::app()->db->createCommand($sql)->queryScalar();
        $data['count'] = $res;

        return $data;
    }



    private function getLastComments($inID = 0)
    {
        $id = $inID ?: $this->exInfo->id_resume;
        // получаем 6 последних комментов
        $sql = "SELECT co.id, co.message, co.isneg, DATE_FORMAT(co.crdate,'%d.%m.%y') as `crdate`
              , e.name fio, e.logo, e.id_user
            FROM `comments` co
            INNER JOIN employer e ON co.id_empl = e.id
            WHERE co.id_promo = {$id}
                AND co.iseorp = 1
                AND co.isactive = 1
            ORDER BY id DESC
            LIMIT 6";
        $res = Yii::app()->db->createCommand($sql)->queryAll();
        $data['comments'] = $res;

        // кол-во
        $res = $this->getCommentsCount($id);
        $data['count'] = array($res[0], $res[1]);

        return $data;
    }
    /*
    *   сохранение времени работы
    */
    private function saveUserTimePeriods()
    {
        $id = $this->exInfo->id;

        $arDays = Yii::app()->getRequest()->getParam('days');
        $arTime = Yii::app()->getRequest()->getParam('time');
        $arCity = Yii::app()->getRequest()->getParam('city');

        foreach($arCity as $idCity){
            for($day=1; $day<=7; $day++){
                if(isset($arTime[$idCity][$day])){
                    $arr = explode('-', $arTime[$idCity][$day]);
                    $btime = $arr[0] * 60;
                    $etime = $arr[1] * 60;
                    $insData[] = array('id_city' => $idCity, 'id_us' => $id, 'wday' => $day, 'timeb' => $btime, 'timee' => $etime);
                }
            }
        }

        if( count($insData) ){
            Yii::app()->db->createCommand()->delete('user_wtime', 'id_us=:id_user', array(':id_user' => $id));
            $command = Yii::app()->db->schema->commandBuilder->createMultipleInsertCommand('user_wtime', $insData);
            $res = $command->execute();
        }
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
        $arResult = array('error'=>0,'mess'=>'','type'=>'');

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
            ->select('r.firstname, r.lastname, u.email, a.val phone, uc.id_city city, r.aboutme, um.id_mech pos')
            ->from('resume r')
            ->join('user u','u.id_user = r.id_user')
            ->leftJoin('user_mech um','u.id_user = um.id_us AND um.isshow=1')
            ->leftJoin('user_attribs a', 'u.id_user = a.id_us AND a.id_attr=1')
            ->leftJoin('user_city uc', 'u.id_user = uc.id_user')
            ->where('u.id_user=:id', array(':id' => $idus))
            ->queryAll();

        $arResult = array_merge($arResult[0], ['fields'=>[]]);

        if(empty($arResult['firstname']))
            $arResult['fields'][] = '"Имя"';
        if(empty($arResult['lastname']))
            $arResult['fields'][] = '"Фамилия"';
        if(empty($arResult['city']))
            $arResult['fields'][] = '"Город"';
        if(empty($arResult['email']))
            $arResult['fields'][] = '"Email"';
        if(empty($arResult['phone']))
            $arResult['fields'][] = '"Телефон"';
        if(empty($arResult['pos']))
            $arResult['fields'][] = '"Целевые вакансии"';
        if(empty($arResult['aboutme']))
            $arResult['fields'][] = '"О себе"';

        if(sizeof($arResult['fields'])>0)
            $arResult['mess'] = 'Необходимо заполнить поля: ' . implode(', ', $arResult['fields']);

        return $arResult;
    }
}