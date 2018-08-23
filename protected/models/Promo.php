<?php
/**
 * Created by wind
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
            array('id, firstname, city, lastname, date_public,birthday, photo, status, isblocked', 'required'),
            array('id, status, isblocked', 'numerical', 'integerOnly'=>true),
            array('name, firstname,lastname, city, admin', 'length', 'max'=>64),
            // array('email','email'),
            array('id, firstname, city, lastname, date_public, birthday, photo, status, isblocked', 'safe', 'on'=>'search'),
        );

    }

    public function getNotifications(){
        $id = Share::$UserProfile->id;
        $countInvite = 0;
        $countResponse = 0;
        $countPlus = 0;
        $countMinus = 0;
        $dateEnds = 0;
        $dateStarts = 0;
        $dateTomorrow = 0;
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
            }
            if( $res[$i]['status'] == 5 || $res[$i]['status'] == 6 ){
                $result['vacancyPlus'] = $res[$i]['id']."&";
                $countPlus++;
            }
            if( $res[$i]['status'] == 3){
                $result['vacancyMinus'] = $res[$i]['id']."&";
                $countMinus++;
            }
            if($res[$i]['crdate'] == date("m.d.Y")){
                $result['vacancyStart'] = $res[$i]['id']."&";
                $dateStart++;
            }
           
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

            WHERE e.status = 1 AND  DATE(et.bdate) BETWEEN '{$dateStart}' AND '{$dateTomor}'
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
                }
                 if(explode(" ", $rest[$i]['bdate'])[0] ==  $dateEnd){
                    $result['vacancyStart'] = $res[$i]['id']."&";
                    $dateStarts++;
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
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria=new CDbCriteria;
        $criteria->compare('id',$this->id, true);
        $criteria->compare('firstname',$this->firstname, true);
        $criteria->compare('lastname',$this->lastname, true); 
        $criteria->compare('city',$this->city, true);
        $criteria->compare('photo',$this->photo, true);
        $criteria->compare('date_public',$this->date_public, true);
        $criteria->compare('birthday',$this->birthday, true);
        $criteria->compare('mdate',$this->mdate, true);
        $criteria->compare('aboutme',$this->aboutme, true);
        $criteria->compare('status',$this->status, true);
        $criteria->compare('isblocked',$this->isblocked, true);
        $criteria->compare('admin',$this->admin, true);
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
          , u.email, card, cardPrommu
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
        $sql = "SELECT r.id,
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
               u.is_online,
               r.ishasavto,
               DATE_FORMAT(r.date_public, '%d.%m.%Y') date_public,
               DATE_FORMAT(r.birthday, '%d.%m.%Y') birthday,
               DATE_FORMAT(r.mdate, '%d.%m.%Y') mdate
              , ci.id_city, ci.name ciname
              , m.id mid, m.name mname 
--               , d.id idattr, d.type dtype, d.name tname
              , me.pay , me.pay_type pt 
            FROM resume r
            INNER JOIN (
                SELECT DISTINCT r.id
                FROM resume r
                INNER JOIN user u ON u.id_user = r.id_user 
                    AND u.ismoder = 1 AND u.isblocked = 0
                INNER JOIN user_city uc ON r.id_user = uc.id_user 
                {$inParams['table']}
                INNER JOIN user_mech a ON a.id_us = r.id_user
                {$inParams['filter']}
                ORDER BY r.mdate DESC 
                LIMIT {$inParams['offset']}, {$inParams['limit']}
            ) t1 ON t1.id = r.id
            
            LEFT JOIN user_city uc ON r.id_user = uc.id_user
            LEFT JOIN city ci ON ci.id_city = uc.id_city
            LEFT JOIN user_metro um ON um.id_us = r.id_user
            LEFT JOIN metro m ON m.id = um.id_metro
            LEFT JOIN user_mech me ON me.isshow = 0 AND me.id_us = r.id_user
            INNER JOIN user u ON u.id_user = r.id_user AND u.ismoder = 1 AND u.isblocked = 0
            ORDER BY r.mdate DESC, ciname, mname
            LIMIT 100";

            return Yii::app()->db->createCommand($sql)->queryAll();
    }




     public function getApplicAdmin()
    {

        $sql = "SELECT DISTINCT r.id, r.id_user idus, r.photo, r.firstname, r.lastname, r.isman, DATE_FORMAT(r.birthday,'%d.%m.%Y') as birthday,
                cast(r.rate AS SIGNED) - ABS(cast(r.rate_neg as signed)) avg_rate, r.rate, r.rate_neg, photo,
                (SELECT COUNT(id) FROM comments mm WHERE mm.iseorp = 1 AND mm.id_promo = r.id) comment_count
            FROM resume r
            INNER JOIN user u ON r.id_user = u.id_user AND u.ismoder = 0  AND u.crdate >= CURDATE()
            ORDER BY id DESC, id DESC";
        $result = Yii::app()->db->createCommand($sql)->queryAll();

        return $result;
    }

    private function getApplicantsIndexPage()
    {
        $strCities = Subdomain::getCitiesIdies();
        $filter = Promo::mergeScopes(['scope' => Promo::$SCOPE_HAS_PHOTO, 'alias' => 'r']);
        $sql = "SELECT DISTINCT r.id, u.is_online, r.id_user idus, r.photo, r.firstname, r.lastname, r.isman, DATE_FORMAT(r.birthday,'%d.%m.%Y') as birthday,
                cast(r.rate AS SIGNED) - ABS(cast(r.rate_neg as signed)) avg_rate, r.rate, r.rate_neg, photo,
                (SELECT COUNT(id) FROM comments mm WHERE mm.iseorp = 1 AND mm.id_promo = r.id) comment_count
            FROM resume r
            INNER JOIN user_mech m ON r.id_user = m.id_us
            INNER JOIN user_city ci ON r.id_user = ci.id_user 
                AND ci.id_city IN({$strCities})
            INNER JOIN user u ON r.id_user = u.id_user 
                AND u.ismoder = 1 AND (u.isblocked = 0)
            WHERE r.birthday IS NOT NULL AND {$filter}
            ORDER BY avg_rate DESC, id DESC
            LIMIT 6";
        $result = Yii::app()->db->createCommand($sql)->queryAll();

        if(!sizeof($result))
            return false;

        $arIdies = array();
        foreach ($result as $i => $item){
            $arIdies[] = $item['idus'];
        }
        $sql = "SELECT r.id_user idus, d.name name
            FROM resume r
            INNER JOIN user_mech um ON um.id_us = r.id_user AND um.isshow = 0
            LEFT JOIN user_attr_dict d1 ON d1.id = um.id_attr
            INNER JOIN user_attr_dict d ON d.id = um.id_mech 
            WHERE r.id_user IN(".implode(",", $arIdies).")";
        $arPositions = Yii::app()->db->createCommand($sql)->queryAll();

        foreach ($result as $i => &$item){
            $id = $item['idus'];
            foreach($arPositions as $j => $pos){
                if($pos['idus']==$id){
                    $item['positions'] .= (isset($item['positions']) ? ', '.$pos['name'] : $pos['name']);
                }
            }
            $item['rate_count'] = $item['rate'] + $item['rate_neg'];
            $item['comment-url'] = MainConfig::$PAGE_COMMENTS . DS . $id;
            $item['datail-url'] = MainConfig::$PAGE_PROFILE_COMMON . DS . $id;
            $item['logo'] = DS . MainConfig::$PATH_APPLIC_LOGO . DS . (!$item['photo'] ?  'logo.png' : $item['photo'] . '100.jpg');
            $item['birthday'] = date('Y') - date('Y', strtotime($item['birthday']));
            $num = $item['birthday'];
            $strYear = ' лет'; 
            if ($num < 21 && $num > 4) $strYear = ' лет';
            $num = $num%10;
            if ($num == 1) $strYear = ' год';
            if ($num > 1 && $num < 5) $strYear = ' года';
            $item['birthday'] = $item['birthday'] . $strYear;
            $item['fullname'] = trim($item['firstname']) . ' ' .  trim($item['lastname']) . ', ' . $item['birthday'];
        }
        unset($arIdies, $item, $arPositions);

        return $result;      
    }
}