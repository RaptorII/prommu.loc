<?php
/**
 * Created by Vlasakh
 * Date: 26.08.16
 */

class Employer extends ARModel
{
    public static $SCOPE_HAS_LOGO = 1;



	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'employer';
	}


    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('id, name, firstname, city, lastname, logo, type, crdate, ismoder, isblocked', 'required'),
            array('id, status, isblocked', 'numerical', 'integerOnly'=>true),
            array('name, firstname,lastname, city, admin', 'length', 'max'=>64),
            // array('email','email'),
            array('id, name, firstname, city, lastname, logo, type, crdate, ismoder, isblocked, admin', 'safe', 'on'=>'search'),
        );

    }

    function get_string_between($string, $start, $end){
    $string = " ".$string;
     $ini = strpos($string,$start);
     if ($ini == 0) return "";
     $ini += strlen($start);     
     $len = strpos($string,$end,$ini) - $ini;
     return substr($string,$ini,$len);
    }

    public function getNotifications(){
        
         $id = Share::$UserProfile->id;
        $Vacancy = new Vacancy();
        $ResponsesEmpl = new ResponsesEmpl();
        $vacancy = $Vacancy->getVacancies();
        $countInvite = 0;
        $countResponse = 0;
        $countPlus = 0;
        $countMinus = 0;
        $dateEnds = 0;
        $dateStarts = 0;
        $result['cnt'] = 0;
         $sql = "SELECT e.id, e.title, s.status, s.isresponse
                    FROM empl_vacations e
                    INNER JOIN vacation_stat s ON e.id = s.id_vac 
                        AND s.isresponse IN(1,2) 
                    WHERE e.id_user = {$id} AND e.in_archive=0
                    ORDER BY s.id DESC";
        /** @var $res CDbCommand */
        $res = Yii::app()->db->createCommand($sql);
        $res = $res->queryAll();

        $countVacStat = count($res);
        
        for($i = 0; $i < $countVacStat; $i++)
        {
            if($res[$i]['isresponse'] == 1 && $res[$i]['status'] == 0)
            {
                $result['vacancyInvite'][$res[$i]['id']]['name'] = $res[$i]['title'];
                if(!isset($result['vacancyInvite'][$res[$i]['id']]['cnt']))
                    $result['vacancyInvite'][$res[$i]['id']]['cnt']==0;
                $result['vacancyInvite'][$res[$i]['id']]['cnt']++;
                $result['vacancyInvite'][$res[$i]['id']]['link'] = 
                    MainConfig::$PAGE_VACANCY . DS . $res[$i]['id'] . DS . MainConfig::$VACANCY_RESPONDED;
                $countInvite++;
                $result['cnt']++;
            }
            // в main не используется
            /*if($res[$i]['isresponse'] == 2 && $res[$i]['status'] == 0)
            {
                $result['vacancyResponse'] = $res[$i]['id']."&";
                $countResponse++;
            }*/
            if( $res[$i]['status'] == 5)
            {
                $result['vacancyPlus'][$res[$i]['id']]['name'] = $res[$i]['title'];
                if(!isset($result['vacancyPlus'][$res[$i]['id']]['cnt']))
                    $result['vacancyPlus'][$res[$i]['id']]['cnt']==0;
                $result['vacancyPlus'][$res[$i]['id']]['cnt']++;
                $result['vacancyPlus'][$res[$i]['id']]['link'] = 
                    MainConfig::$PAGE_VACANCY . DS . $res[$i]['id'] . DS . MainConfig::$VACANCY_APPROVED;
                $countPlus++;
                $result['cnt']++;
            }
            if( $res[$i]['status'] == 3)
            {
                $result['vacancyMinus'][$res[$i]['id']]['name'] = $res[$i]['title'];
                if(!isset($result['vacancyMinus'][$res[$i]['id']]['cnt']))
                    $result['vacancyMinus'][$res[$i]['id']]['cnt']==0;
                $result['vacancyMinus'][$res[$i]['id']]['cnt']++;
                $result['vacancyMinus'][$res[$i]['id']]['link'] = 
                    MainConfig::$PAGE_VACANCY . DS . $res[$i]['id'] . DS . MainConfig::$VACANCY_REFUSED;
                $countMinus++;
                $result['cnt']++;
            }
        }

        $date = new DateTime('-50 days');
        $dateStart = $date->format('Y-m-d');
        $dateEnd =  date('Y-m-d');
        $date = new DateTime('+1 day');
        $dateTomor = $date->format('Y-m-d');
       $sql = "SELECT e.id,  e.title,
                   DATE_FORMAT(e.remdate, '%d.%m.%Y') remdate, et.bdate, et.edate, u.email, em.name, em.firstname
            FROM empl_vacations e 
            LEFT JOIN empl_city c1 ON c1.id_vac = e.id 
            LEFT JOIN city c2 ON c2.id_city = c1.id_city 
            LEFT JOIN empl_locations el ON el.id_vac = e.id
            LEFT JOIN empl_city et ON et.id_vac = e.id
            JOIN empl_attribs ea ON ea.id_vac = e.id
            JOIN user_attr_dict d ON (d.id = ea.id_attr) AND (d.id_par = 110)
            JOIN employer em ON em.id_user = e.id_user 
            JOIN user u ON em.id_user = u.id_user 
            WHERE em.id_user ={$id} AND e.status=1 AND e.in_archive=0 AND DATE(et.bdate) BETWEEN '{$dateStart}' AND '{$dateTomor}' AND e.count = 0
            GROUP BY  e.id DESC";
            $rest = Yii::app()->db->createCommand($sql);
            $rest = $rest->queryAll();;

        $name = 'пользователь';
        for($i = 0; $i < count($rest); $i++)
        {
            if(explode(" ", $rest[$i]['bdate'])[0] ==  $dateTomor ||explode(" ", $rest[$i]['bdate'])[0] == $dateEnd )
            {
                $result['vacancyStart'][$res[$i]['id']]['name'] = $res[$i]['title'];
                if(!isset($result['vacancyStart'][$res[$i]['id']]['cnt']))
                    $result['vacancyStart'][$res[$i]['id']]['cnt']==0;
                $result['vacancyStart'][$res[$i]['id']]['cnt']++;
                $result['vacancyStart'][$res[$i]['id']]['link'] = 
                    MainConfig::$PAGE_VACANCY . DS . $res[$i]['id'];
                $dateStarts++;
                $result['cnt']++;
            }
            if(explode(" ", $rest[$i]['bdate'])[0] ==  $dateEnd)
            {
                $result['vacancyStart'][$res[$i]['id']]['name'] = $res[$i]['title'];
                if(!isset($result['vacancyStart'][$res[$i]['id']]['cnt']))
                    $result['vacancyStart'][$res[$i]['id']]['cnt']==0;
                $result['vacancyStart'][$res[$i]['id']]['cnt']++;
                $result['vacancyStart'][$res[$i]['id']]['link'] = 
                    MainConfig::$PAGE_VACANCY . DS . $res[$i]['id'];
                $dateStarts++;
                $result['cnt']++;
            }
            if(explode(" ", $rest[$i]['edate'])[0] ==  $dateEnd)
            {
                $result['vacancyEnd'][$res[$i]['id']]['name'] = $res[$i]['title'];
                if(!isset($result['vacancyEnd'][$res[$i]['id']]['cnt']))
                    $result['vacancyEnd'][$res[$i]['id']]['cnt']==0;
                $result['vacancyEnd'][$res[$i]['id']]['cnt']++;
                $result['vacancyEnd'][$res[$i]['id']]['link'] = 
                    MainConfig::$PAGE_VACANCY . DS . $res[$i]['id'];
                $dateEnds++;
                $result['cnt']++;
            }
            !empty($rest[$i]['firstname']) && $name = $rest[$i]['firstname'];
        }
 
        $result['countInvite'] = $countInvite;
        $result['countResponse'] = $countResponse;
        $result['countPlus'] = $countPlus;
        $cookieView = Yii::app()->request->cookies['cookie_personal_data']->value;
        if($countPlus>0 && $cookieView!=1)
        {
            $message = "<p class='big-flash'>Уважаемый " . $name . "<br>Вам открыты контактные данные соискателя, теперь Вы можете связаться для уточнения деталей проекта или сотрудничества через предоставленные контактные данные. Важно!!! Для обеспечения гарантий оплаты и безопасности сотрудничества мы рекомендуем держать связь по вакансии и проекту на нашем сервисе Prommu. Все договоренности, не зафиксированные в Вашем личном кабинете, не обеспечивают защиту со стороны сервиса Prommu</p>";
            Yii::app()->user->setFlash('prommu_flash', $message);
            Yii::app()->request->cookies['cookie_personal_data'] = new CHttpCookie('cookie_personal_data', 1);
        }
        $result['countMinus'] = $countMinus;
        $result['dateEnd'] = $dateEnds;
        $result['dateStart'] = $dateStarts;

        return $result;
    }

    public function employers()
    {

           $searchWord = filter_var(Yii::app()->getRequest()->getParam('search'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
           $sql = "SELECT r.id id_ra, r.id_user idus, r.name 
            FROM (
                  SELECT r.id, r.id_user, r.name FROM employer r, user u 
                  WHERE r.id_user = u.id_user 
                    AND u.ismoder = 1 AND u.isblocked = 0
                    AND r.name LIKE '%{$searchWord}%' 
                  ORDER BY r.id DESC LIMIT 10
                ) r
                ORDER BY name
            ";
        $res = Yii::app()->db->createCommand($sql)->queryAll();

         $obj = (object)[];
            foreach ($res as $key => &$val)
            {       
                // $test = $this->get_string_between($val['name'],"[","]");
                
                $obj->name =$val['name'];
                $obj->code = $val['idus'];
                $val = clone $obj;
            }

        return $res;
    }

        public function searchempl()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria=new CDbCriteria;
        $criteria->compare('id',$this->id, true);
        $criteria->compare('firstname',$this->firstname, true);
        $criteria->compare('lastname',$this->lastname, true); 
        $criteria->compare('name',$this->name, true);
        $criteria->compare('city',$this->city, true);
        $criteria->compare('logo',$this->logo, true);
        $criteria->compare('type',$this->type, true);
        $criteria->compare('crdate',$this->crdate, true);
        $criteria->compare('ismoder',$this->ismoder, true);
        $criteria->compare('isblocked',$this->isblocked, true);
        $criteria->compare('mdate',$this->mdate, true);
        $criteria->compare('admin',$this->admin, true);
        return new CActiveDataProvider('Employer', array(
            'criteria'=>$criteria,
            'pagination' => array('pageSize' => 100,),
            'sort' => ['defaultOrder'=>'crdate desc'],
        ));
    }

    public function deleteEmployer($cloud){
        foreach ($cloud as $key => $value) {
            // Yii::app()->db->createCommand()->delete('user', 'id_user = :id_user', array(':id_user' => $value));

            // Yii::app()->db->createCommand()->delete('employer', 'id_user = :id_user', array(':id_user' => $value));

            // Yii::app()->db->createCommand()->delete('empl_vacations', 'id_user = :id_user', array(':id_user' => $value));
        }
    }

    public function blocked($id, $st)
    {
        //$md = User::model()->findByPk($id);
        //$md->isblocked = $md->isblocked ^ 1;
        Yii::app()->db->createCommand()
            ->update('employer', array(
            'isblocked'=>$st),
                'id_user=:id', array(':id'=>$id));

            Yii::app()->db->createCommand()
            ->update('user', array(
            'isblocked'=>$st),
                'id_user=:id', array(':id'=>$id));


            if($st == 1){
                $mail = new MailCloud();
                $mail->mailerBlock($id, 3);
            }
        //echo $md->isblocked; die;
        //$md->save();
        //return $md->isblocked;

    }   
    
    public function ChangeModer($id, $st)
    {
        Yii::app()->db->createCommand()
            ->update('user', array(
                'ismoder' => $st,
            ), 'id_user=:id_user', array(':id_user' => $id));

            Yii::app()->db->createCommand()
            ->update('employer', array(
                'ismoder' => $st,
            ), 'id_user=:id_user', array(':id_user' => $id));
    }
    /**
     * Готовые условия для ручных запросов по работодателям
     * @param string $inName
     * @param string $alias
     * @return string
     */
    static public function getScopesCustom($inName, $alias = 'e')
    {
        // Если удаляем условия убивать и $SCOPE_XXXXXXX чтобы сразу выявить использование условия
        $aliasPlh = '{{alias}}';
        switch ( (int)$inName )
        {
           case self::$SCOPE_HAS_LOGO : $condition = "logo <> ''"; break;
           default : $condition = "";
        }

        return $condition ? str_replace($aliasPlh, $alias . '.', $aliasPlh . $condition) : '';
    }



    /**
     * Получаем работодателей
     * @return CDbCommand
     */
    public function getEmployersQueries($inParams)
    {
        /**
         * Список необходимых данных:
         * Название компании, Имя, Фамилия, Email, Телефон, Город
         *
         * Выделяем все выборки работодателей в один класс, для того, чтобы если изменится список необходимых данных можно было быстро везде поменять
         *
         * ВАЖНО ДАЖЕ ЕСЛИ БЛОКИРУЕМ РАБОТОДАТЕЛЯ, ВАКАНСИИ ЕЩЕ ОТОБРАЖАЮТСЯ
         */

        // Получаем ??? для карты сайта
//        if( $inParams['page'] == 'sitemap' ) return $this->getApplicantsSitemap();


        // Получаем работодателей для поиска
        if( $inParams['page'] == 'searchempl' ) return $this->getEmployerSearchempl($inParams);


        // Получаем работодателей для главной
        if( $inParams['page'] == 'index' ) return $this->getEmployersIndexPage();
    }



    private function getEmployerSearchempl($inParams)
    {
        $sql = "SELECT e.id,
                   e.id_user,
                   e.type,
                   e.name,
                   u.is_online,
                   e.rate,
                   e.rate_neg,
                   (SELECT COUNT(id) FROM comments mm WHERE mm.iseorp = 0 AND mm.isneg = 0 AND mm.isactive = 1 AND mm.id_empl = e.id) commpos,
                   (SELECT COUNT(id) FROM comments mm WHERE mm.iseorp = 0 AND mm.isneg = 1 AND mm.isactive = 1 AND mm.id_empl = e.id) commneg
                   ,(SELECT COUNT(*) cou FROM empl_vacations v WHERE v.id_user = e.id_user AND v.status = 1 AND v.ismoder = 100 AND v.in_archive=0) vaccount
                   ,e.logo,
                   DATE_FORMAT(e.crdate, '%d.%m.%Y') crdate,
                   DATE_FORMAT(e.mdate, '%d.%m.%Y') mdate
              , ci.id_city, ci.name ciname
              , d.id idattr, d.type dtype, d.name tname
            FROM employer e
            INNER JOIN (
              SELECT DISTINCT e.id
              FROM employer e
              INNER JOIN user_city uc ON e.id_user = uc.id_user
              INNER JOIN user u ON u.id_user = e.id_user AND u.ismoder = 1              
            --   INNER JOIN empl_attribs ea ON ea.id_vac = e.id
             
              {$inParams['filter']}
              ORDER BY e.mdate DESC, e.id DESC 
              LIMIT {$inParams['offset']}, {$inParams['limit']}
            ) t1 ON t1.id = e.id
            
            LEFT JOIN user_city uc ON e.id_user = uc.id_user
            LEFT JOIN city ci ON ci.id_city = uc.id_city
            LEFT JOIN user_metro um ON um.id_us = e.id_user
            INNER JOIN user u ON u.id_user = e.id_user AND u.ismoder = 1  
            LEFT JOIN user_attr_dict d ON d.id = e.type
            ORDER BY e.mdate DESC, e.id DESC";
        $res = Yii::app()->db->createCommand($sql);

        try
        {
            return $res->queryAll();
        }
        catch (Exception $e) {
            throw new Exception($e->getMessage());
//            return array('error' => $e->getMessage(), 'sql' => $sql);
        } // endtry
    }

    public function getEmplAdmin()
    {
        $sql = "
            SELECT
              r.id,
              r.id_user idus,
              name,
              r.logo,
              r.rate,
              r.rate_neg,
              cast(r.rate AS SIGNED) - ABS(cast(r.rate_neg AS SIGNED)) avg_rate,
              (SELECT COUNT(id)
               FROM comments mm
               WHERE mm.iseorp = 0 AND mm.id_promo = r.id) comment_count
            
            FROM employer r
              INNER JOIN user u ON u.id_user = r.id_user
            WHERE r.is_new = 1 AND u.crdate >= CURDATE()
            ORDER BY r.id DESC
            LIMIT 1000
        ";
        $result = Yii::app()->db->createCommand($sql)
        ->queryAll();

        return $result;
    }

    private function getEmployersIndexPage()
    {
        $strCities = Subdomain::getCacheData()->strCitiesIdes;
        // достаем работодателей
        $filter = Employer::getScopesCustom(Employer::$SCOPE_HAS_LOGO, 'r');
        $sql = "SELECT r.id, r.id_user idus, #u.is_online, 
                    name, r.firstname, r.lastname,
                    r.logo, r.rate, r.rate_neg, 
                    cast(r.rate AS SIGNED) - ABS(cast(r.rate_neg as signed)) avg_rate,
                    (SELECT COUNT(id) 
                    FROM comments mm 
                    WHERE mm.iseorp = 0 AND mm.id_empl = r.id) comment_count,
                    (SELECT COUNT(id) FROM comments mm
                    WHERE mm.isneg = 1 AND mm.id_empl = r.id) comment_neg     
                FROM employer r
                INNER JOIN user u ON u.id_user = r.id_user 
                    AND u.ismoder = 1 AND u.isblocked = 0
                INNER JOIN user_city ci ON r.id_user = ci.id_user 
                    AND ci.id_city IN({$strCities})
                WHERE {$filter} 
                ORDER BY avg_rate DESC
                LIMIT 6";
        $arEmp = Yii::app()->db->createCommand($sql)->queryAll();
        //
        $nEmp = sizeof($arEmp);
        if(!$nEmp)
            return false;
        // достаем должности соискателей
        $arIdies = array();
        for ($i=0; $i < $nEmp; $i++)
            $arIdies[] = $arEmp[$i]['idus'];

        $sql = "SELECT ci.id_city id, ci.name name, uc.id_user idus
                    FROM user_city uc
                    LEFT JOIN city ci ON uc.id_city = ci.id_city
                    WHERE uc.id_user IN(".implode(",", $arIdies).")";
        $arCities = Yii::app()->db->createCommand($sql)->queryAll();
        // формируем массив
        foreach($arEmp as &$i){
            foreach($arCities as $j => $city)
                if($city['idus']==$i['idus'])
                    $i['cities'] .= (isset($i['cities']) 
                        ? ', '.$city['name'] 
                        : $city['name']);

            $i['datail-url'] = MainConfig::$PAGE_PROFILE_COMMON . DS . $i['idus'];
            $i['logo'] = Share::getPhoto(3, $i['logo']);
            $i['fullname'] = $i['name'] . ' (№' . $i['idus'] . ')';
            $i['comment-url'] = MainConfig::$PAGE_COMMENTS . DS . $i['idus'];
            $i['rate_count'] = $i['rate'] + $i['rate_neg'];
        }
        unset($i, $arCities, $arIdies);

        return $arEmp;
    }

    public static function getUserAttrib($id_user) {
    // Read attributes
    $list = Yii::app()->db->createCommand()
        ->select('key, val')
        ->from('user_attribs')
        //->order('id_us')
        ->where('id_us=:id_user', array(':id_user'=>$id_user))
        ->queryAll();
    $lst = [];
    foreach($list as $r) {
        $lst[$r['key']] = $r['val'];
    }
    return $lst;
}

    public function exportEmplCSV()
    {
        $csv_file = ''; // создаем переменную, в которую записываем строки
        $result = Yii::app()->db->createCommand()
            ->select("e.id_user, e.name, e.type, e.firstname, e.lastname")
            ->from('employer e')
            //->where('`id_user`=:id_user', array(':id_user' => $id_user))
            //->limit(1)
            ->order('e.id_user desc')
            ->queryAll();
        //$result = $user[0];

        // Get directory of color of hair
        $cmp_type = Share::getDirectory('cotype');


        // Get attributes
        foreach($result as &$res) {
            $res['attr'] = self::getUserAttrib($res['id_user']);


            //print_r($result); die;

            // Blocks - City
            $list = Yii::app()->db->createCommand()
                ->select('uc.id_city, c.name as city_name, c.id_co, uc.street, uc.addinfo')
                ->from('user_city uc')
                ->join('city c', 'c.id_city=uc.id_city')
                //->order('uc.id')
                ->where('uc.id_user=:id_user', array(':id_user' => $res['id_user']))
                ->queryAll();
            $res['blocks_city'] = $list;
            //print_r($list); die;
            $res['blocks_cnt'] = count($list);

            // Country
            if(!empty($result['blocks_city'])) {
                $list = Yii::app()->db->createCommand()
                    ->select('id_co, name')
                    ->from('country')
                    ->where('id_co=:id_country', array(':id_country' => $res['blocks_city'][0]['id_co']))
                    ->limit(1)
                    ->queryAll();
                $res['id_co'] = $list[0]['id_co'];
                $res['country_name'] = $list[0]['name'];
            } else {
                $res['country_name'] = '';
            }
        }


        $data = [];
        $i = 0;
        foreach ($result as $res) {
            $attr = Yii::app()->db->createCommand()
                ->select("e.*")
                ->from('empl_attribs e, empl_vacations v')
                ->where('v.id_user=:id and e.id_vac=v.id', array(':id' => $res['id_user']))
                ->queryAll();
            $arr_at = [];

            foreach ($attr as $at) {
                if (!empty($at['key'])) {
                    $arr_at[$at['key']] = $at['val'];
                }
            }
            $data[$i] = $res;
            $data[$i]['attr'] = $arr_at;
            $i++;
        }

        $csv_file = '<table border="1">
            <tr><td style="color:red; background:#E0E0E0">'.'ID'.
            '</td><td style="color:red; background:#E0E0E0">'.htmlentities(iconv("utf-8", "windows-1251", 'Название компании'),ENT_QUOTES, "cp1251").
            '</td><td style="color:red; background:#E0E0E0">'.htmlentities(iconv("utf-8", "windows-1251", 'Тип компании'),ENT_QUOTES, "cp1251").
            '</td><td style="color:red; background:#E0E0E0">'.htmlentities(iconv("utf-8", "windows-1251", 'Страна'),ENT_QUOTES, "cp1251").
            '</td><td style="color:red; background:#E0E0E0">'.htmlentities(iconv("utf-8", "windows-1251", 'Город'),ENT_QUOTES, "cp1251").
            '</td><td style="color:red; background:#E0E0E0">'.htmlentities(iconv("utf-8", "windows-1251", 'Улица'),ENT_QUOTES, "cp1251").
            '</td><td style="color:red; background:#E0E0E0">'.htmlentities(iconv("utf-8", "windows-1251", 'Другое'),ENT_QUOTES, "cp1251").
            '</td><td style="color:red; background:#E0E0E0">'.htmlentities(iconv("utf-8", "windows-1251", 'Web сайт'),ENT_QUOTES, "cp1251").
            '</td><td style="color:red; background:#E0E0E0">'.htmlentities(iconv("utf-8", "windows-1251", 'Логотип (фото url)'),ENT_QUOTES, "cp1251").
            '</td><td style="color:red; background:#E0E0E0">'.htmlentities(iconv("utf-8", "windows-1251", 'Имя'),ENT_QUOTES, "cp1251").
            '</td><td style="color:red; background:#E0E0E0">'.htmlentities(iconv("utf-8", "windows-1251", 'Фамилия'),ENT_QUOTES, "cp1251").
            '</td><td style="color:red; background:#E0E0E0">'.htmlentities(iconv("utf-8", "windows-1251", 'Skype'),ENT_QUOTES, "cp1251").
            '</td><td style="color:red; background:#E0E0E0">'.htmlentities(iconv("utf-8", "windows-1251", 'Моб. Телефон)'),ENT_QUOTES, "cp1251").
            '</td><td style="color:red; background:#E0E0E0">'.htmlentities(iconv("utf-8", "windows-1251", 'Должность'),ENT_QUOTES, "cp1251").
            '</td></tr>';

        $block_status = ["полностью активен", "заблокирован", "ожидает активации", "активирован", "остановлен к показу"];

        //print_r($data); die;
        foreach ($data as $row) {


            $csv_file .= '<tr>';
            $b = "";
            $b_end = "";
            if ($row["ismoder"]==0) {
                $b = '<b>';
                $b_end = '</b>';
            }
            $ismed = $row["ismed"] ? 'X' : '';
            $ishasavto = $row["ishasavto"] ? 'X' : '';
            $isman = $row["isman"] ? 'М' : 'Ж';
            $csv_file .= '<td>'.$b.$row["id_user"].$b_end.
                '</td><td>'.$b.htmlentities(iconv("utf-8", "windows-1251", $row["name"]),ENT_QUOTES, "cp1251").$b_end.
                '</td><td>'.$b.htmlentities(iconv("utf-8", "windows-1251", $cmp_type[$row["type"]]),ENT_QUOTES, "cp1251").$b_end.
                '</td><td>'.$b.htmlentities(iconv("utf-8", "windows-1251", $row["country_name"]),ENT_QUOTES, "cp1251").$b_end.
                '</td><td>'.$b.htmlentities(iconv("utf-8", "windows-1251", $row["blocks_city"][0]["city_name"]),ENT_QUOTES, "cp1251").$b_end.
                '</td><td>'.$b.htmlentities(iconv("utf-8", "windows-1251", $row["blocks_city"][0]["street"]),ENT_QUOTES, "cp1251").$b_end.
                '</td><td>'.$b.htmlentities(iconv("utf-8", "windows-1251", $row["blocks_city"][0]["addinfo"]),ENT_QUOTES, "cp1251").$b_end.
                '</td><td>'.$b.$row['attr']['web'].$b_end.
                '</td><td>'.$b.$row['attr']['logo'].$b_end.
                '</td><td>'.$b.$b.htmlentities(iconv("utf-8", "windows-1251", $row['firstname']),ENT_QUOTES, "cp1251").$b_end.
                '</td><td>'.$b.$b.htmlentities(iconv("utf-8", "windows-1251", $row['lastname']),ENT_QUOTES, "cp1251").$b_end.
                '</td><td>'.$b.htmlentities(iconv("utf-8", "windows-1251", $row['attr']["phone_mb"]),ENT_QUOTES, "cp1251").$b_end.
                '</td><td>'.$b.htmlentities(iconv("utf-8", "windows-1251", $row['attr']['position']),ENT_QUOTES, "cp1251").$b_end.
                '</td></tr>';
        }

        $csv_file .='</table>';
        $file_name = $_SERVER['DOCUMENT_ROOT'].'/content/empl_exp.xls'; // название файла
        $file = fopen($file_name,"w"); // открываем файл для записи, если его нет, то создаем его в текущей папке, где расположен скрипт


        fwrite($file,trim($csv_file)); // записываем в файл строки
        fclose($file); // закрываем файл

        // задаем заголовки. то есть задаем всплывающее окошко, которое позволяет нам сохранить файл.
        //header('Content-type: application/csv'); // указываем, что это csv документ
        //header("Content-Disposition: inline; filename=".$file_name); // указываем файл, с которым будем работать
        header('Pragma: no-cache');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Description: File Transfer');
        //header('Content-Type: text/csv');
        //header('Content-Disposition: attachment; filename=export.csv;');
        header('Content-Disposition: attachment; filename=empl_exp.xls');
        header('Content-transfer-encoding: binary');
        //header("content-type:application/csv;charset=ANSI");
        header('Content-Type: text/html; charset=windows-1251');
        header('Content-Type: application/x-unknown');
        header('P3P: CP="NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM"');
        //print "\xEF\xBB\xBF"; // UTF-8 BOM
        readfile($file_name); // считываем файл
        //unlink($file_name); // удаляем файл. то есть когда вы сохраните файл на локальном компе, то после он удалится с сервера
    }

    /**
     * setViewed
     * @return model
     */
    public function setViewed($id) {
        return Yii::app()->db->createCommand()->update(
            $this->tableName(),
            ['is_new' => 0],
            'id_user=:id',
            [':id' => $id]
        );
    }
}