<?php
/**
 * Created by Prommu
 * Date: 17.02.16
 * Time: 22:22
 */

class Promo extends ARModel
{
    static public $SCOPE_ACTIVE = 1;
    static public $SCOPE_HAS_PHOTO = 2;
    static public $SCOPE_BLOCKED = 0;


    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'resume';
    }

    public function getPromo($id_user)
    {
        $user = Yii::app()->db->createCommand()
            ->select("e.fio, c.name as city_name, DATE_FORMAT(e.birthday,'%d.%m.%Y') as birthday,
                e.phone, e.email, e.photo, e.isman, u.name as education, e.education_type, usr.login")
            ->from('resume e')
		    ->join('city c', 'c.id_city=e.id_city')
            ->join('university u', 'u.id=e.id_universiti')
		    ->join('user usr', 'usr.id_user=e.id_user')
		    ->where('e.id_user=:id_user', array(':id_user' => $id_user))
            ->queryAll();

        return $user[0];
    }

    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('id, firstname, city, lastname, date_public,birthday, photo, status, ismoder, isblocked, is_new', 'required'),
            array('id, status, isblocked', 'numerical', 'integerOnly'=>true),
            array('name, firstname,lastname, city, admin', 'length', 'max'=>64),
            // array('email','email'),
            array('id, firstname, city, lastname, date_public, birthday, photo, status, isblocked', 'safe', 'on'=>'search'),
        );

    }

    public function getNotifications(){
        $name = 'пользователь';
        $id = Share::$UserProfile->id;
        $countInvite = 0;
        $countResponse = 0;
        $countPlus = 0;
        $countMinus = 0;
        $dateEnds = 0;
        $dateStarts = 0;
        $dateTomorrow = 0;
        $result['cnt'] = 0;
         $sql = "SELECT DISTINCT e.id, e.title, e.status vstatus, e.remdate, e.crdate
              , DATE_FORMAT(e.crdate, '%d.%m.%Y') bdate
              , r.id_user idusr, r.firstname, r.lastname, r.photo, r.isman
              , s.id sid, s.status, s.isresponse, DATE_FORMAT(s.date, '%d.%m.%Y') rdate
            FROM empl_vacations e
            INNER JOIN vacation_stat s ON e.id = s.id_vac AND s.isresponse IN(1,2) 
            INNER JOIN resume r ON s.id_promo = r.id
            WHERE r.id_user = {$id} AND s.isend = 0
            ORDER BY s.id DESC";
        /** @var $res CDbCommand */
        $res = Yii::app()->db->createCommand($sql);
        $res = $res->queryAll();
        $countVacStat = count($res);
        
        for($i = 0; $i < $countVacStat; $i++){

            if($res[$i]['isresponse'] == 2 && $res[$i]['status'] == 4){
                $result['vacancyResponse'] = $res[$i]['id']."&";
                $countResponse++;
                $result['cnt']++;
            }
            if( $res[$i]['status'] == 5 || $res[$i]['status'] == 6 ){
                $result['vacancyPlus'] = $res[$i]['id']."&";
                $countPlus++;
                $result['cnt']++;
            }
            if( $res[$i]['status'] == 3){
                $result['vacancyMinus'] = $res[$i]['id']."&";
                $countMinus++;
                $result['cnt']++;
            }
            if($res[$i]['crdate'] == date("m.d.Y")){
                $result['vacancyStart'] = $res[$i]['id']."&";
                $dateStarts++;
                $result['cnt']++;
            }
            !empty($res[$i]['firstname']) && $name = $res[$i]['firstname'];
        }

         $date = new DateTime('-50 days');
        $dateStart = $date->format('Y-m-d');
        $dateEnd =  date('Y-m-d');
        $date = new DateTime('+1 day');
        $dateTomor = $date->format('Y-m-d');
       $sql = "SELECT e.id,  e.title,
                   DATE_FORMAT(e.remdate, '%d.%m.%Y') remdate, et.bdate, et.edate, u.email, em.name
            FROM empl_vacations e 
            LEFT JOIN empl_city c1 ON c1.id_vac = e.id 
            LEFT JOIN city c2 ON c2.id_city = c1.id_city 
            LEFT JOIN empl_locations el ON el.id_vac = e.id
            LEFT JOIN empl_city et ON et.id_vac = e.id
            JOIN empl_attribs ea ON ea.id_vac = e.id
            JOIN user_attr_dict d ON (d.id = ea.id_attr) AND (d.id_par = 110)
            JOIN employer em ON em.id_user = e.id_user 
            JOIN user u ON em.id_user = u.id_user 

            WHERE e.status=1 AND e.in_archive=0 AND DATE(et.bdate) BETWEEN '{$dateStart}' AND '{$dateTomor}'
            GROUP BY  e.id DESC";
            $rest = Yii::app()->db->createCommand($sql);
            $rest = $rest->queryAll();;

        for($i = 0; $i < count($rest); $i++){
            $idvac = $rest[$i]['id'];
            $sql = "SELECT ru.email, r.firstname, r.lastname
            FROM vacation_stat s
            INNER JOIN empl_vacations e ON e.id = s.id_vac
            INNER JOIN resume r ON s.id_promo = r.id
            INNER JOIN user ru ON ru.id_user = r.id_user
            WHERE r.id = {$id} AND s.status IN(5,6) AND e.id = {$idvac} AND s.isend = 0";
            $res = Yii::app()->db->createCommand($sql);
            $ress = $res->queryAll();
            if($ress['email']) {
                if(explode(" ", $rest[$i]['bdate'])[0] ==  $dateTomor){
                    $result['vacancyTomorrow'] = $rest[$i]['id']."&";
                    $dateTomorrow++;
                    $result['cnt']++;
                }
                 if(explode(" ", $rest[$i]['bdate'])[0] ==  $dateEnd){
                    $result['vacancyStart'] = $res[$i]['id']."&";
                    $dateStarts++;
                    $result['cnt']++;
                }
                 if(explode(" ", $rest[$i]['edate'])[0] ==  $dateEnd){
                     $result['vacancyEnd'] = $res[$i]['id']."&";
                     $dateEnds++;
                }
            }
       
        }
         
        $result['countInvite'] = $countInvite;
        $result['countResponse'] = $countResponse;
        $result['countPlus'] = $countPlus;
        $cookieView = Yii::app()->request->cookies['cookie_personal_data']->value;
        if($countPlus>0 && $cookieView!=1)
        {
            $message = "<p class='big-flash'>Уважаемый " . $name . "<br>Вам открыты контактные данные работодателя, теперь Вы можете связаться для уточнения деталей проекта или сотрудничества через предоставленные контактные данные. Важно!!! Для обеспечения гарантий оплаты и безопасности сотрудничества мы рекомендуем держать связь по вакансии и проекту на нашем сервисе Prommu. Все договоренности, не зафиксированные в Вашем личном кабинете, не обеспечивают защиту со стороны сервиса Prommu</p>";
            Yii::app()->user->setFlash('prommu_flash', $message);
            Yii::app()->request->cookies['cookie_personal_data'] = new CHttpCookie('cookie_personal_data', 1);
        }

        $result['countMinus'] = $countMinus;
        $result['dateEnd'] = $dateEnds;
        $result['dateStart'] = $dateStarts;
        $result['tommorowStart'] = $dateTomorrow;

        return $result;
    }

    public function blockedPromo($id, $st)
    {
        Yii::app()->db->createCommand()
            ->update('resume', array(
            'isblocked'=>$st),
                'id_user=:id', array(':id'=>$id));

            Yii::app()->db->createCommand()
            ->update('user', array(
            'isblocked'=>$st),
                'id_user=:id', array(':id'=>$id));

            if($st == 1){
                $mail = new MailCloud();
                $mail->mailerBlock($id, 2);
            }     
    }  

    public function ChangeModer($id, $st)
    {
        Yii::app()->db->createCommand()
            ->update('user', array(
                'ismoder' => $st,
            ), 'id_user=:id_user', array(':id_user' => $id));

            Yii::app()->db->createCommand()
            ->update('resume', array(
                'ismoder' => $st,
                'status' => $st,
            ), 'id_user=:id_user', array(':id_user' => $id));
    }

    public function deletePromo($cloud){
        foreach ($cloud as $key => $value) {
            // Yii::app()->db->createCommand()->delete('user', 'id_user = :id_user', array(':id_user' => $value));

            // Yii::app()->db->createCommand()->delete('resume', 'id_user = :id_user', array(':id_user' => $value));

            // Yii::app()->db->createCommand()->delete('user_photos', 'id_user = :id_user', array(':id_user' => $value));

            // Yii::app()->db->createCommand()->delete('user_wtime', 'id_us = :id_user', array(':id_user' => $value));
        }
    }

    public function searchpr()
    {
        $criteria = new CDbCriteria;

        $get = Yii::app()->getRequest()->getParam('Promo');
        if(is_array($get))
        {
          $this->attributes = $get;
        }
        // недорегистрированных не выводим
        $criteria->condition = 't.isblocked <> ' . User::$ISBLOCKED_NOT_FULL_ACTIVE;
        //
        $criteria->compare('id',$this->id, true);
        $criteria->compare('id_user',$this->id_user, true);
        $criteria->compare('isman',$this->isman, true);
        $criteria->compare('firstname',$this->firstname, true);
        $criteria->compare('lastname',$this->lastname, true); 
        $criteria->compare('city',$this->city, true);
        $criteria->compare('photo',$this->photo, true);
        $criteria->compare('date_public',$this->date_public, true);
        $criteria->compare('birthday',$this->birthday, true);
        $criteria->compare('mdate',$this->mdate, true);
        $criteria->compare('aboutme',$this->aboutme, true);
        $criteria->compare('status',$this->status, true);
        $criteria->compare('ismoder',$this->ismoder, true);
        $criteria->compare('isblocked',$this->isblocked, true);
        $criteria->compare('admin',$this->admin, true);
        $criteria->compare('is_new',$this->is_new, true);

        return new CActiveDataProvider('Promo', array(
            'criteria'=>$criteria,
            'pagination' => array('pageSize' => 100,),
            'sort' => ['defaultOrder'=>'mdate desc'],
        ));
    }
  

    /**
     * Full resume, Update
     *
     * @param $id_user
     * @param $params
     */
    public function setPromoFull($id_user, $params) {
        $valid = array(
            'is_auto','height','is_med','is_man','photo','phone_mb','phone_ex','email','skype','vk_link','viber',
            'icq','other','city','city_pt','street','other_pt','weight','pay',
            'practice', 'clr_hair', 'ln_hair', 'clr_eye', 'size_bst', 'hips', 'edu', 'waist'
        );

        $id_city = Yii::app()->db->createCommand()
            ->select('id_city')
            ->from('city')
            ->where('name like :name', array(':name'=>$params['city_name']))
            ->limit(1)
            ->queryScalar();

        // Update resume
        Yii::app()->db->createCommand()
            ->update('resume', array(
                'user_name' => $params['user_name'],
                'user_surname' => $params['user_surname'],
                'id_city' => $id_city,
                'aboutme' => $params['aboutme'],
                'birthday' => Share::dateFormatToMySql($params['birthday']),
            ), 'id_user=:id_user', array(':id_user' => $id_user));

        foreach($params as $key => $value) {
            if (in_array ($key, $valid)) {
                Yii::app()->db->createCommand()
                    ->update('user_attribs', array(
                        "val" => $value,
                    ), '`id_usr`=:id_user and `key`=:key', array(':id_user' => $id_user, ':key'=>$key));
            }
        }
        // Time
        for($i=1;$i<=7;$i++) {
            if(isset($params['day_'.$i])){
                Yii::app()->db->createCommand()
                    ->update('user_wtime', array(
                        "timeb" => Share::convTimetoMin($params['timeb_'.$i]),
                        "timee" => Share::convTimetoMin($params['timee_'.$i]),
                    ), '`id_user`=:id_user and `nday`=:key', array(':id_user' => $id_user, ':key'=>$i));
            } else {
                Yii::app()->db->createCommand()
                    ->update('user_wtime', array(
                        "timeb" => 0,
                        "timee" => 0,
                    ), '`id_user`=:id_user and `nday`=:key', array(':id_user' => $id_user, ':key'=>$i));
            }
        }
        // Languages
        Yii::app()->db->createCommand()->delete('user_langs', array('in', 'id_user', array($id_user)));

        foreach($params['lang'] as $value) {
            Yii::app()->db->createCommand()
                ->insert('user_langs', array(
                    "id_user" => $id_user,
                    "id_lang" => $value,
                ));
        }

        // Position
        $lst_pos = Share::getDirectory('position');
        Yii::app()->db->createCommand()->delete('user_position', array('in', 'id_user', array($id_user)));
        foreach($lst_pos as $key => $value) {
            $id = $key;
            $v = 0;
            if($this->checkPos($params['position1'], $key)) {
                $v = 1;
            }
            if($this->checkPos($params['position2'], $key)) {
                if($v==1) {
                    $v = 3;
                } else {
                    $v = 2;
                }
            }

            if($v > 0) {
                Yii::app()->db->createCommand()
                    ->insert('user_position', array(
                        "id_user" => $id_user,
                        "id_attr" => $id,
                        "val" => $v,
                    ));
            }

        }
    }

    private function checkPos($arr, $val) {
        foreach($arr as $value) {
            if($value == $val) {
                return true;
            }
        }
        return false;
    }



// BMS: ==  ==========================================

    /**
     * Готовые условия для ручных запросов по соискателям
     * @param string $inName
     * @param string $alias
     * @return string
     */
    static public function getScopesCustom($inName, $alias = 'r')
    {
        // Если удаляем условия убивать и $SCOPE_XXXXXXX чтобы сразу выявить использование условия
        $aliasPlh = '{{alias}}';
        switch ( (int)$inName )
        {
           case self::$SCOPE_ACTIVE : $condition = 'status = 1'; break;
           case self::$SCOPE_BLOCKED : $condition = 'isblocked = 0'; break;
           case self::$SCOPE_HAS_PHOTO : $condition = "photo <> ''"; break;
           default : $condition = "";
        }

        return $condition ? str_replace($aliasPlh, $alias . '.', $aliasPlh . $condition) : '';
    }



    /**
     * Получаем объединение именованых условий
     * @params mixed ['scope' => 'name', 'alias' => 'r']
     */
    static public function mergeScopes()
    {
        $scopes = func_get_args();

        foreach ($scopes as $key => $val)
        {
            $filter[] = Promo::getScopesCustom($val['scope'], $val['alias']);
        } // end foreach

        return join(' and ', $filter);
    }



    /**
     * Получаем анкеты
     * @return CDbCommand
     */
    public function getApplicantsQueries($inParams)
    {
        /**
         * Список необходимых данных:
         * Имя, Фамилия, Дата рождения, Email, Должность, Город
         *
         * Выделяем все выборки соискателей в один класс, для того, чтобы если изменится список необходимых данных можно было быстро везде поменять
         */

        // Получаем анкеты для карты сайта
        if( $inParams['page'] == 'sitemap' ) return $this->getApplicantsSitemap();


        // Получаем анкеты для поиска
        if( $inParams['page'] == 'searchpromo' ) return $this->getApplicantsSearchpromo($inParams);


        // Получаем соискателей для главной
        if( $inParams['page'] == 'index' ) return $this->getApplicantsIndexPage();
    }


    private function getApplicantsSitemap()
    {
        $sql = "SELECT DISTINCT r.id_user id, CONCAT(r.firstname, ' ', r.lastname) fio, r.mdate 
            FROM resume r
            INNER JOIN user_mech m ON r.id_user = m.id_us
            INNER JOIN user_city ci ON r.id_user = ci.id_user
            INNER JOIN user u ON r.id_user = u.id_user AND u.ismoder = 1 AND u.isblocked = 0
            WHERE r.birthday IS NOT NULL
            ORDER BY id DESC";
        /** @var $res CDbCommand */
        $res = Yii::app()->db->createCommand($sql)->queryAll();
        $count = count($res);
        for($i = 0; $i < $count; $i++){
            $profileFillMax = 24;
            $profileFill = $this->getUserInfo($res[$i]['id']); // id_user пользователя

            $profileEffect = floor($profileFill / $profileFillMax * 100);
            if($profileEffect<40){
               unset($res[$i]);
            }
        }
        return $res;
    }

    public function getUserInfo($id)
    {
    $profileFill = 0;
    // считываем характеристики пользователя
    $sql = "SELECT DATE_FORMAT(r.birthday,'%d.%m.%Y') as bday, r.id
          , r.id_user, r.isman , r.ismed , r.smart,  r.ishasavto , r.aboutme , r.firstname , r.lastname , r.photo
          , a.val , a.id_attr
          , d.name , d.type , d.id_par idpar , d.key
          , u.email, card, cardPrommu, u.mdate
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
            $profileFill++;
    }

    // read cities
    $sql = "SELECT ci.id_city id, ci.name, co.id_co, co.name coname, ci.ismetro, uc.street, uc.addinfo
            FROM user_city uc
            LEFT JOIN city ci ON uc.id_city = ci.id_city
            LEFT JOIN country co ON co.id_co = ci.id_co
            WHERE uc.id_user = {$id}";
    $res = Yii::app()->db->createCommand($sql)->queryAll();

    foreach ($res as $key => $val):
        $cityPrint[$val['id']] = $val['name'];
        $city[$val['id']] = array('id' => $val['id'], 'name' => $val['name'], 'ismetro' => $val['ismetro'], 'street' => $val['street'], 'addinfo' => $val['addinfo'], );
    endforeach;

    if( count($city) ) $profileFill++;

    // должности, отработанные и желаемые
    $sql = "SELECT r.id
          , um.isshow, um.pay, um.id_attr, um.mech
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
        if( $val['isshow'] ) $exp[] = $val['val'];
        if( !$val['isshow'] ) $flagPF || $flagPF = 1;
    }
    $data['userDolj'] = array($res, join(', ', $exp));
    if( $flagPF ) $profileFill++;
    if( count($exp) ) $profileFill++;

    return $profileFill;
}


    public function getApplicantsSearchpromo($inParams)
    {  
        $sql = Yii::app()->db->createCommand()
                ->select("
                    r.id,
                    r.id_user,
                    r.id_rating,
                    r.firstname,
                    r.lastname,
                    r.photo,
                    r.rate,
                    r.isman,
                    r.smart,
                    r.card,
                    r.cardPrommu,
                    r.rate_neg,
                    r.ismed,
                    r.ishasavto,
                    DATE_FORMAT(r.date_public, '%d.%m.%Y') date_public,
                    DATE_FORMAT(r.birthday, '%d.%m.%Y') birthday,
                    DATE_FORMAT(r.mdate, '%d.%m.%Y') mdates,
                    u.is_online, u.mdate")
                ->from('resume r')
                ->leftjoin('user u', 'u.id_user=r.id_user')
                ->where(array('in', 'r.id_user', $inParams['arId']))
                ->order('r.mdate desc')
                ->queryAll();

        $nP = sizeof($sql);
        if(!$nP)
            return $sql;

        $arP = array();
        $arId = array();
        $arIdPromo = array();
        for ( $i=0; $i<$nP; $i++ ) {
            $arP[$sql[$i]['id_user']] = $sql[$i];
            $arId[] = $sql[$i]['id_user'];
            $arIdPromo[] = $sql[$i]['id'];
        }

        $arAttr = Yii::app()->db->createCommand()
          ->select("id_us, key, val")
          ->from('user_attribs')
          ->where(['in', 'id_us', $arId])
          ->queryAll();

        foreach ($arAttr as $v)
        {
          !empty($v['key']) && $arP[$v['id_us']]['attribs'][$v['key']]=$v['val'];
        }

        $sqlP = Yii::app()->db->createCommand()
                    ->select("
                        um.id_us id_user, 
                        um.pay_type, 
                        um.id_mech id, 
                        um.pay, 
                        uad.name")
                    ->from('user_mech um')
                    ->leftjoin('user_attr_dict uad', 'uad.id=um.id_mech')
                    ->where('um.isshow=0 AND um.id_us IN('.implode(',',$arId).')')
                    ->queryAll();

        for ( $i=0, $n=sizeof($sqlP); $i<$n; $i++ )
            $arP[$sqlP[$i]['id_user']]['post'][] = array(
                    "id"=> (int)$sqlP[$i]['id'],
                    "name" => $sqlP[$i]['name'],
                    "pay" => (float)$sqlP[$i]['pay'],
                    "pay_type" => (int)$sqlP[$i]['pay_type']
                );

        $sqlC = Yii::app()->db->createCommand()
                    ->select("uc.id_user, c.id_city id, c.name")
                    ->from('user_city uc')
                    ->leftjoin('city c', 'c.id_city=uc.id_city')
                    ->where(array('in', 'uc.id_user', $arId))
                    ->queryAll();

        for ( $i=0, $n=sizeof($sqlC); $i<$n; $i++ ){
            !empty($sqlC[$i]['name'])
            &&
            $arP[$sqlC[$i]['id_user']]['city']['name'] = $sqlC[$i]['name'];
            $arP[$sqlC[$i]['id_user']]['city']['id'] = $sqlC[$i]['id'];
        }

        $sqlM = Yii::app()->db->createCommand()
                    ->select("um.id_us id_user, m.id, m.name")
                    ->from('user_metro um')
                    ->leftjoin('metro m', 'm.id=um.id_metro')
                    ->where(array('in', 'um.id_us', $arId))
                    ->queryAll();

        for ( $i=0, $n=sizeof($sqlM); $i<$n; $i++ ) {
            if(!empty($sqlM[$i]['name'])){
                $arP[$sqlM[$i]['id_user']]['metroes']['id'] = $sqlM[$i]['id'];
                $arP[$sqlM[$i]['id_user']]['metroes']['name'] = $sqlM[$i]['name'];   
            }
            
        }

        $where = 'vs.id_promo IN(' . implode(',', $arIdPromo) . ') AND ' 
            . Vacancy::getScopesCustom(Vacancy::$SCOPE_APPLIC_WORKING, 'vs');
        $sqlV = Yii::app()->db->createCommand()
                    ->select("vs.id_promo id")
                    ->from('empl_vacations v')
                    ->join('vacation_stat vs', 'vs.id_vac = v.id')
                    ->where($where)
                    ->queryAll();

        $arV = array();
        for ( $i=0, $n=sizeof($sqlV); $i<$n; $i++ )
            $arV[$sqlV[$i]['id']] += 1;

        $where = 'id_promo IN('.implode(',',$arIdPromo).') AND iseorp=1 AND isactive=1';
        $sqlK = Yii::app()->db->createCommand()
                    ->select("id_promo id, isneg")
                    ->from('comments')
                    ->where($where)
                    ->queryAll();

        $arK = array();
        for ( $i=0, $n=sizeof($sqlK); $i<$n; $i++ ) {
            !isset($arK[$sqlV[$i]['id']]['comm']) && $arK[$sqlV[$i]['id']]['comm'] = 0;
            $sqlK[$i]['isneg']==0 && $arK[$sqlV[$i]['id']]['comm'] += 1;
            !isset($arK[$sqlV[$i]['id']]['commneg']) && $arK[$sqlV[$i]['id']]['commneg'] = 0;
            $sqlK[$i]['isneg']==1 && $arK[$sqlV[$i]['id']]['commneg'] += 1;
        }

        foreach ($arP as $idus => $p) {
            $arP[$idus]['projects'] 
                = !empty($arV[$p['id']]) ? $arV[$p['id']] : 0;
            $arP[$idus]['comm']
                = !empty($arK[$p['id']]['comm']) ? $arK[$p['id']]['comm'] : 0;
            $arP[$idus]['commneg']
                = !empty($arK[$p['id']]['commneg']) ? $arK[$p['id']]['commneg'] : 0;

            $d1 = new DateTime();
            $d2 = new DateTime($p['birthday']);
            $diff = $d2->diff($d1);
            $arP[$idus]['age'] = $diff->y;
            $arP[$idus]['sex'] = $p['isman'];
        }

        return $arP;
    }








     public function getApplicAdmin()
    {
       return Yii::app()->db->createCommand()
        ->select('r.id, u.id_user idus, r.firstname, r.lastname')
        ->from('resume r')
        ->leftJoin('user u','u.id_user=r.id_user')
        ->where(
          'r.is_new=1 AND u.isblocked<>:isblocked',
          [':isblocked'=>User::$ISBLOCKED_NOT_FULL_ACTIVE]
        )
        ->order('r.id desc')
        ->limit('1000')
        ->queryAll();

        $result = Yii::app()->db->createCommand($sql)->queryAll();

        return $result;
    }

    private function getApplicantsIndexPage()
    {
        $strCities = Subdomain::getCacheData()->strCitiesIdes;
        // достаем соискателей
        $filter = Promo::mergeScopes([
                'scope' => Promo::$SCOPE_HAS_PHOTO, 
                'alias' => 'r'
            ]);
        $sql = "SELECT DISTINCT r.id, u.is_online, 
                    r.id_user idus, 
                    r.photo, r.firstname, r.lastname, 
                    r.isman, DATE_FORMAT(r.birthday,'%d.%m.%Y') as birthday,
                    cast(r.rate AS SIGNED) - ABS(cast(r.rate_neg as signed)) avg_rate,
                    r.rate, r.rate_neg, photo, 
                    (SELECT COUNT(id) FROM comments mm 
                    WHERE mm.iseorp = 1 AND mm.id_promo = r.id) comment_count,
                    (SELECT COUNT(id) FROM comments mm
                    WHERE mm.isneg = 1 AND mm.id_promo = r.id) comment_neg
                FROM resume r
                INNER JOIN user_mech m ON r.id_user = m.id_us
                INNER JOIN user_city ci ON r.id_user = ci.id_user 
                    AND ci.id_city IN({$strCities})
                INNER JOIN user u ON r.id_user = u.id_user 
                    AND u.ismoder = 1 AND (u.isblocked = 0)
                WHERE r.birthday IS NOT NULL AND {$filter}
                ORDER BY avg_rate DESC, id DESC
                LIMIT 6";
        $arApp = Yii::app()->db->createCommand($sql)->queryAll();
        //
        $nApp = sizeof($arApp);
        if(!$nApp)
            return false;
        // достаем должности соискателей
        $arIdies = array();
        for ($i=0; $i < $nApp; $i++)
            $arIdies[] = $arApp[$i]['idus'];

        $sql = "SELECT r.id_user idus, d.name name
                    FROM resume r
                    INNER JOIN user_mech um 
                        ON um.id_us = r.id_user AND um.isshow = 0
                    INNER JOIN user_attr_dict d ON d.id = um.id_mech 
                    WHERE r.id_user IN(".implode(",", $arIdies).")";
        $arPosts = Yii::app()->db->createCommand($sql)->queryAll();
        // формируем массив
        foreach ($arApp as &$i) {
            foreach($arPosts as $j => $pos)
                if($pos['idus']==$i['idus'])
                    $i['positions'] .= (isset($i['positions']) 
                        ? ', ' . $pos['name'] 
                        : $pos['name']);

            $i['rate_count'] = $i['rate'] + $i['rate_neg'];
            $i['comment-url'] = MainConfig::$PAGE_COMMENTS . DS . $i['idus'];
            $i['datail-url'] = MainConfig::$PAGE_PROFILE_COMMON . DS . $i['idus'];
            $i['logo'] = Share::getPhoto($i['idus'], 2, $i['photo'], 'small', $i['isman']);
            $i['birthday'] = date('Y') - date('Y', strtotime($i['birthday']));
            $i['birthday'] = $i['birthday'] . ' ' 
                . Share::endingYears($i['birthday']);
            $i['fullname'] = trim($i['firstname']) . ' ' 
                .  trim($i['lastname']) . ', ' . $i['birthday'];
        }
        unset($arIdies, $i, $arPosts);

        return $arApp;      
    }
    
            /**
     * экспорт соискателей в админке
     */
    public function exportPromos()
    {
        $offset = 0;
        $limit = 100; // вакансий за 1 итерацию
        $arRes = array(
            'items'=>[],
            'city'=>[],
            'responses'=>[],
            'employers'=>[],
            'views'=>[],
            'head' => [
                'ID',
                'ФИО',
                'Дата Рождения',
                'Фото Ава',
                'Доп Фото (количество)',
                'Страна',
                'Город',
                'Область',
                'Тлф',
                'Е-Меил',
                'Skype',
                'WhatsApp',
                'Viber',
                'Telegram',
                'Messeger',
                'Дата и Время регистрации',
                'Дата и время Модерации',
                'Дата и время изменений',
                'Дата и время Модерации изменений',
                'Дата и время последнего посещения сайта',
                'Сколько на сайте всего',
                'Сколько на сайте Онлайн',
                'Количество просмотренных В.',
                'Количество откликов на В.',
                'Количество утверждений на В.',
                'Количетсво отклонений на В.',
                'Количество отработанных проектов',
                'Свои Оценки',
                'Свои Отзывы',
                'Оставленные оценки по Р.',
                'Оставленные отзывы по Р.',
                'Наличие Мед книги',
                'Наличие банк карты Промму',
                'Наличие банк карты',
                'Наличие смартфона',
                'Наличие Авто',
              ],
            'autosize' => [0,1,2,3,6,7,8,9,10,11,12,13,14,16]
          );
        $db = Yii::app()->db;
        $conditions = $params = [];
        $rq = Yii::app()->getRequest();
        
        $dateType = $rq->getParam('export_date');
        $bDate = $rq->getParam('export_beg_date');
        $eDate = $rq->getParam('export_end_date');
        
        $birthbDate = $rq->getParam('birthday_beg_date');
        $birtheDate = $rq->getParam('birthday_end_date');
        
        $status = $rq->getParam('export_status');
        $phones = $rq->getParam('export_phone');
        
        $bDate = date('Y-m-d',strtotime($bDate));
        $eDate = date('Y-m-d',strtotime($eDate));

        if($bDate!='1970-01-01')
        {
            switch ($dateType)
            {
                case 'create': 
                    $conditions[] = 'e.date_public>=:bdate';
                    $params[':bdate'] = $bDate . ' 00:00:00';
                    break;
            }   
        }
        if($eDate!='1970-01-01')
        {
            switch ($dateType)
            {
                case 'create': 
                    $conditions[] = 'e.date_public<=:edate';
                    $params[':edate'] = $eDate . ' 23:59:59';
                    break;
            }   
        }
        
        if($birthbDate!='1970-01-01')
        {
            switch ($dateType)
            {
                case 'create': 
                    $conditions[] = 'e.birthday>=:bsdate';
                    $params[':bsdate'] = $bDate . ' 00:00:00';
                    break;
            }   
        }
        if($birtheDate!='1970-01-01')
        {
            switch ($dateType)
            {
                case 'create': 
                    $conditions[] = 'e.birthday<=:esdate';
                    $params[':esdate'] = $eDate . ' 23:59:59';
                    break;
            }   
        }
        
        
        if($status!='all')
        {
            $conditions[] = 'u.ismoder =' . ($status=='active' ? '1' : '0');
        }
        
        
        $arId = $db->createCommand()
                                ->select("e.id, e.id_user, e.firstname,e.birthday, e.lastname, e.date_public, e.mdate, e.photo, e.card, e.cardPrommu,
                                          e.ismed, e.ishasavto, e.smart")
                                ->from('resume e')
                                ->join('user u', 'u.id_user=e.id_user')
                                ->where(implode(' and ',$conditions), $params)
                                ->order('e.id desc')
                                ->queryAll();

        $n = count($arId);
        if(!$n)
        {
          Yii::app()->user->setFlash('danger', 'Соискателей не найдено');
          return false;
        }
        
         
    
        foreach ($arId as $k => $v)
        {
            
            $data = $this->getUserExcelInfo($v['id_user']);
            $time = $this->getOnlineTime($v['id']);
            //!empty($data['userAttribs']['mob']['val']) && 
            if($time['time'] != 0){
                
                
                
                $now = time(); 
                $your_date = strtotime($v['date_public']); 
                $datediff = $now - $your_date; 
                
                $days = floor($datediff / (60 * 60 * 24)); 
                $id = $v['id'];
                $id_user = $v['id_user'];
                $arT[$id]['id'] = $v['id'];
                $arT[$id]['fio'] = $v['firstname'].' '.$v['lastname'];
                $arT[$id]['birthday'] = $v['birthday'];
                if($v['photo']){
                    $arT[$id]['photo'] = "https://files.prommu.com/users/".$v['id'].'/'.$v['photo'].'.jpg';
                } else $arT[$id]['photo'] = "";
    
                $arT[$id]['photocount'] = 1;
                
                ///city
                $city = $this->getCityUserExcel($v['id_user']);
                
                $arT[$id]['country'] = $city['coname'];
                $arT[$id]['city'] = $city['name'];
                $arT[$id]['region'] = $city['region'];
                
                ///contact
                $arT[$id]['phone'] = $data['userAttribs']['mob']['val'];
                $arT[$id]['email'] = $data[0]['email'];
                $arT[$id]['skype'] = $data['userAttribs']['skype']['val'];
                $arT[$id]['whatsapp'] = $data['userAttribs']['whatsapp']['val'];
                $arT[$id]['viber'] = $data['userAttribs']['viber']['val'];
                $arT[$id]['telegram'] = $data['userAttribs']['telegram']['val'];
                $arT[$id]['messenger'] = $data['userAttribs']['google']['val'];
                
                ///дата создания
                $arT[$id]['crdate'] = $v['date_public'];
                $arT[$id]['mdate'] = $v['mdate'];
                $arT[$id]['edate'] = $v['mdate'];
                $arT[$id]['dedate'] = $v['mdate'];
                $arT[$id]['online'] = $v['mdate'];
                $arT[$id]['daysfromsite'] = $days;
                $arT[$id]['daysonline'] = $time['time'];
                
                ///вакансия
                
                $sql = "SELECT COUNT(id)
								FROM termostat_analytic
								WHERE user = {$id_user} 
									AND type = 'vacancy'";
			    $countvac = Yii::app()->db->createCommand($sql)->queryScalar();
			    
			    $sql = "SELECT COUNT(id)
								FROM vacation_stat
								WHERE id_promo = {$id} 
									AND isresponse = 1";
			    $countactivevac = Yii::app()->db->createCommand($sql)->queryScalar();
			    
			    $sql = "SELECT COUNT(id)
								FROM vacation_stat
								WHERE id_promo = {$id} 
									AND status IN (5,6,7)";
			    $countarchivevac = Yii::app()->db->createCommand($sql)->queryScalar();
			    
			    $sql = "SELECT COUNT(id)
								FROM vacation_stat
								WHERE id_promo = {$id} 
									AND status IN (3)";
			    $countinvitevac = Yii::app()->db->createCommand($sql)->queryScalar();
			    
			    
			    $sql = "SELECT COUNT(id)
								FROM vacation_stat
								WHERE id_promo = {$id} 
									AND status IN (7)";
			    $countresponsevac = Yii::app()->db->createCommand($sql)->queryScalar();
			
                $arT[$id]['countvac'] = $countvac;
                $arT[$id]['countactivevac'] = $countactivevac;
                $arT[$id]['countarchivevac'] = $countarchivevac;
                $arT[$id]['countinvitevac'] = $countinvitevac;
                $arT[$id]['countresponsevac'] = $countresponsevac;
                $arT[$id]['countrefusedvac'] = "";
                
                
                ///рейтинг
                $arT[$id]['countrating'] = "";
                $arT[$id]['feedback'] = "";
                $arT[$id]['countratingpromo'] = "";
                
                
                ///наличие атрибутов
                $arT[$id]['ismed'] = $v['ismed'];
                $arT[$id]['cardPrommu'] = $v['cardPrommu'];
                $arT[$id]['card'] = $v['card'];
                $arT[$id]['smart'] = $v['smart'];
                $arT[$id]['ishasavto'] = $v['ishasavto'];
            }
        }
        
         $arRes['items'] = $arT;
         
        return $arRes;
        
    }
    
    
    public function getOnlineTime($idus){
        
        $res = [];
        
        $result = Yii::app()->db->createCommand()
                                ->select("uw.date_login")
                                ->from('user_work uw')
                                ->where('uw.id_user=:id_user', array(':id_user' => $idus))
                                ->order('uw.id desc')
                                ->queryAll();
        
        $res['time'] = count($result)*3;
        $res['date_login'] = $result[count($result)-1];
        
        return $res;
    }
    
    public function getCityUserExcel($idus){
        
        $sql = "SELECT ci.id_city id, ci.name, co.id_co, co.name coname, ci.ismetro, uc.street, uc.addinfo, ci.region
            FROM user_city uc
            LEFT JOIN city ci ON uc.id_city = ci.id_city
            LEFT JOIN country co ON co.id_co = ci.id_co
            WHERE uc.id_user = {$idus}";
        $res = Yii::app()->db->createCommand($sql)->queryRow();
        
        
        $region = Yii::app()->db->createCommand()
            ->select('name')
            ->from('city')
            ->where('id_city like :id_city', array(':id_city'=>$res['region']))
            ->limit(1)
            ->queryRow();
            
        $res['region'] = $region['name'];
        return $res;
    }
    
    
    public function getUserExcelInfo($idus){
        
        $sql = "SELECT DATE_FORMAT(r.birthday,'%d.%m.%Y') as bday -- , DATE_FORMAT(r.birthday,'%d') as bd
              , r.id_user, r.isman , r.ismed , r.smart, r.ishasavto , r.aboutme , r.firstname , r.lastname , r.photo, r.card, r.cardPrommu
              , a.val , a.id_attr
              , d.name , d.type , d.id_par idpar , d.key
              , u.email, u.is_online, u.mdate
            FROM resume r
            LEFT JOIN user u ON u.id_user = r.id_user
            LEFT JOIN user_attribs a ON r.id_user = a.id_us
            LEFT JOIN user_attr_dict d ON a.id_attr = d.id
            WHERE r.id_user = {$idus}
            ORDER BY a.id_attr";
        $res = Yii::app()->db->createCommand($sql)->queryAll();
        
        
        foreach ($res as $key => $val)
        {
            if($val['idpar'] == 0){
                $res['userAttribs'][$val['key']] = ['val' => $val['val'], 'id_attr' => $val['id_attr'], 'name' => $val['name'], 'type' => $val['type'], 'idpar' => $val['idpar'], 'key' => $val['key'],];
            } else {
                $userdict = Yii::app()->db->createCommand()
                        ->select('d.id , d.type, d.key, d.name')
                        ->from('user_attr_dict d')
                        ->where('d.id = :id', array(':id' => $val['idpar']))
                        ->queryRow();
                
                $res['userAttribs'][$userdict['key']] = ['val' => $val['val'], 'id_attr' => $val['id_attr'], 'name' => $val['name'], 'type' => $val['type'], 'idpar' => $val['idpar'], 'key' => $val['key'],];
                
            }
        } // end foreach


        return $res;
    }
    
    
    /**
     * setViewed
     * @return model
     */
    public function setViewed($id)
    {
        return Yii::app()->db->createCommand()->update(
            $this->tableName(),
            ['is_new' => 0],
            'id_user=:id',
            [':id' => $id]
        );
    }
  /**
   * @param $arInsert - array(field => value)
   * @return bool
   */
  public function registerUser($arInsert)
  {
    $result = Yii::app()->db->createCommand()
      ->insert(self::tableName(),$arInsert);

    return ($result ? Yii::app()->db->getLastInsertID() : $result);
  }
}