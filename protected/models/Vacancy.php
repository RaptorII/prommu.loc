<?php
/**Degres
 * Date: 18.03.2016
 * Time: 15:41
 *
 * Модель вакансии
 */

class Vacancy extends ARModel
{
    /** @var int вакансии на которых работали(ют) оба */
    static public $SCOPE_APPLIC_WORKING = 1;
    /** @var int активны и промодерированы */
    static public $SCOPE_ACTIVE_N_MODER = 2;
    /** @var int актуальные */
    static public $SCOPE_ACTUAL = 3;


    /** @var UserProfile */
    private $Profile;



    /**
     * @param UserProfile $Profile
     */
    function __construct($Profile = null)
    {
        parent::__construct();

        $this->Profile = $Profile;
    }

    public function tableName()
    {
        return 'empl_vacations';
    }

        public function searchvac()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria=new CDbCriteria;
        $criteria->compare('id',$this->id, true);
        $criteria->compare('id_empl',$this->id_empl, true);
        $criteria->compare('city',$this->city, true);
        $criteria->compare('crdate',$this->crdate, true);
        $criteria->compare('mdate',$this->mdate, true);
        $criteria->compare('remdate',$this->remdate, true);
        $criteria->compare('title',$this->title, true);
        $criteria->compare('status',$this->status, true);
        $criteria->compare('meta_h1',$this->meta_h1, true);
        $criteria->compare('meta_title',$this->meta_title, true);
        $criteria->compare('meta_description',$this->meta_description, true);
        $criteria->compare('index',$this->index, true);
        return new CActiveDataProvider('Vacancy', array(
            'criteria'=>$criteria,
            'pagination' => array('pageSize' => 20,),
            'sort' => ['defaultOrder'=>'mdate desc'],
        ));
    }

public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('id, id_empl, title, remdate,crdate, city, status', 'required'),
           array('id', 'numerical', 'integerOnly'=>true),
            // array('name, firstname,lastname, city', 'length', 'max'=>64),
            // array('email','email'),
            array('id, id_empl, title, remdate,crdate, city, status, index, meta_title, meta_h1, meta_description', 'safe', 'on'=>'search'),
        );

    }

    public function setAnalytic($id){
        $res = Yii::app()->db->createCommand()
                 ->update('vacancy_count', array(
                        'count' => 'count' + 1,
                    ), 'id = :id', array(':id' => $id));
    }

    public function deleteVacancy($id){
        Yii::app()->db->createCommand()
            ->delete('empl_vacations', 'id=:id', array(':id' => $id));
    }

    /**
     * Готовые условия для ручных запросов по пользователям
     * @param string $inName
     * @param string $inAlias
     * @return string
     */
    static public function getScopesCustom($inName, $inAlias = 'v')
    {
        // Если удаляем условия убивать и $SCOPE_XXXXXXX чтобы сразу выявить использование условия
        $alias = '{{alias}}';
        switch ( (int)$inName )
        {
           case self::$SCOPE_APPLIC_WORKING : $condition = 'status IN(5,6,7)'; break;
           case self::$SCOPE_ACTIVE_N_MODER : $condition = "status = 1 AND {$alias}ismoder = 100"; break;
           case self::$SCOPE_ACTUAL : $condition = "remdate >= now()"; break;
           default : $condition = "";
        }

        return $condition ? str_replace($alias, $inAlias . '.', $alias . $condition) : '';
    }


    public function VacMech($mech){
        $sql = "SELECT DISTINCT r.id, r.id_user idus, r.photo, r.firstname, r.lastname, r.isman, DATE_FORMAT(r.birthday,'%d.%m.%Y') as birthday,
                cast(r.rate AS SIGNED) - ABS(cast(r.rate_neg as signed)) avg_rate, r.rate, r.rate_neg, photo,
                (SELECT COUNT(id) FROM comments mm WHERE mm.iseorp = 1 AND mm.id_promo = r.id) comment_count
            FROM resume r
            INNER JOIN user_mech m ON r.id_user = m.id_us
            INNER JOIN user_city ci ON r.id_user = ci.id_user
            INNER JOIN user u ON r.id_user = u.id_user AND u.ismoder = 1 AND (u.isblocked = 0)
            WHERE m.id_mech = {$mech}
            LIMIT 10000";
        $result = Yii::app()->db->createCommand($sql)->queryAll();

        return $result;
    }
    /**
     * проверяем наличие оконченных вакансий
     */
    public function chkVacsEnds()
    {
        $sql = "SELECT s.edate, s.name
            FROM service_cloud s
            WHERE s.type = 'vacancy' AND s.status = 1";
        $ress = Yii::app()->db->createCommand($sql)->queryAll();
        $count = count($ress);
        for($i = 0; $i < $count; $i ++){
            
            if((time() - strtotime($ress[$i]['edate'])) > 1000 ){
                 $res = Yii::app()->db->createCommand()
                ->update('empl_vacations', array(
                    'ispremium'=> 0,
                ), 'id=:id', array(':id' => $ress[$i]['name']));

                $res = Yii::app()->db->createCommand()
                ->update('service_cloud', array(
                    'status'=> 0,
                ), 'edate=:edate', array(':edate' => $ress[$i]['edate']));
            }
        }


        // читаем вакансии
        $sql = "SELECT
                s.id sid, s.status, s.mdate
                , eu.email emailempl, eu.id_user idusempl
                , ru.email emailpromo, ru.id_user iduspromo
                , e.id, e.title, e.remdate
                , em.name
                , r.firstname, r.lastname
            FROM vacation_stat s
            INNER JOIN empl_vacations e ON e.id = s.id_vac
            INNER JOIN employer em ON em.id_user = e.id_user
            INNER JOIN user eu ON em.id_user = eu.id_user
            INNER JOIN resume r ON s.id_promo = r.id
            INNER JOIN user ru ON ru.id_user = r.id_user
            WHERE s.isresponse = 1
              AND s.status = 5";
        $res = Yii::app()->db->createCommand($sql)->queryAll();
        foreach ($res as $key => $val)
        {
            $flag = 0;
            // проверка на 7 дней после начала работы соискателя
            if( ($diff = (time() - strtotime($val['mdate'])) / 86400) > 1) $flag = 1;

            // закончилась раньше 7 дней
            if( !$flag && (time() > strtotime($val['remdate'])) ) $flag = 1;

         

            if(date("Y-m-d") == $val['remdate']){
                $message = sprintf("Завершение вакансии №%s “<a href='https://%s'>%s</a>” сегодня. <br>Просим оценить ваше сотрудничество с компанией “<a href='https://%s'>%s</a>”, для этого перейдите на страницу <a href='http://%s'>http://%s</a>"
                    , $val['id']
                    , MainConfig::$SITE . MainConfig::$PAGE_VACANCY . DS . $val['id']
                    , $val['title']
                    , MainConfig::$SITE . MainConfig::$PAGE_PROFILE_COMMON . DS . $val['idusempl']
                    , $val['name']
                    , MainConfig::$SITE . MainConfig::$PAGE_SETRATE . DS . $val['id']
                    , MainConfig::$SITE . MainConfig::$PAGE_SETRATE . DS . $val['id']
//                    , MainConfig::$SITE
//                    , MainConfig::$SITE
                );
                 Share::sendmail($val['emailpromo'], "Prommu.com. Завершение проекта сегодня", $message);
                 Share::sendmail($val['emailempl'], "Prommu.com. Завершение проекта сегодня", $message);
                  $res = Yii::app()->db->createCommand()
                    ->update('vacation_stat', array( 'status' => 6,
                        'mdate' => date('Y-m-d H:i:s'),
                    ), 'id = :id', array(':id' => $val['sid']));
                $ret = array('error' => 0, 'res' => $res);
            }

//             if($flag)
//             {
//                 $message = sprintf("Вы были приняты на вакансию №%s “<a href='https://%s'>%s</a>”. <br>Просим оценить ваше сотрудничество с компанией “<a href='https://%s'>%s</a>”, для этого перейдите на страницу <a href='http://%s'>http://%s</a>"
//                     , $val['id']
//                     , MainConfig::$SITE . MainConfig::$PAGE_VACANCY . DS . $val['id']
//                     , $val['title']
//                     , MainConfig::$SITE . MainConfig::$PAGE_PROFILE_COMMON . DS . $val['idusempl']
//                     , $val['name']
//                     , MainConfig::$SITE . MainConfig::$PAGE_SETRATE . DS . $val['id']
//                     , MainConfig::$SITE . MainConfig::$PAGE_SETRATE . DS . $val['id']
// //                    , MainConfig::$SITE
// //                    , MainConfig::$SITE
//                 );

//                 // Share::sendmail($val['emailpromo'], "Prommu.com. рейтинг", $message);

//                 $message = sprintf("Соискатель “<a href='https://%s'>%s</a>” был принят вами на вакансию №%s “<a href='https://%s'>%s</a>”. <br>Просим оценить ваше сотрудничество, для этого перейдите на страницу <a href='https://%s'>http://%s</a>"
//                 , MainConfig::$SITE . MainConfig::$PAGE_PROFILE_COMMON . DS . $val['iduspromo']
//                 , $val['firstname'] . ' ' . $val['lastname']
//                 , $val['id']
//                 , MainConfig::$SITE . MainConfig::$PAGE_VACANCY . DS . $val['id']
//                 , $val['title']
//                 , MainConfig::$SITE . MainConfig::$PAGE_SETRATE . DS . $val['id'] . '/' . $val['iduspromo']
//                 , MainConfig::$SITE . MainConfig::$PAGE_SETRATE . DS . $val['id'] . '/' . $val['iduspromo']
//                 );

//                 // Share::sendmail($val['emailempl'], "Prommu.com. рейтинг", $message);

//                 $res = Yii::app()->db->createCommand()
//                     ->update('vacation_stat', array( 'status' => 6,
//                         'mdate' => date('Y-m-d H:i:s'),
//                     ), 'id = :id', array(':id' => $val['sid']));
//                 $ret = array('error' => 0, 'res' => $res);

//             }
            else
            {
            } // endif
        } // end foreach


        // проверка на то, что вакансия подходит к концу
        $this->chkVacsAlmostEnds();

        return 1;
    }



    /**
     * Проверка на вакансию, кт. подходит к завершению
     */
    private function chkVacsAlmostEnds()
    {
        $id = Share::$UserProfile->id;

        // читаем вакансии
        $sql = "SELECT eu.email emailempl, eu.id_user idusempl
                , e.id, e.title, e.remdate
                , em.name
            FROM empl_vacations e 
            INNER JOIN employer em ON em.id_user = e.id_user
            INNER JOIN user eu ON em.id_user = eu.id_user
            WHERE e.id_user = {$id}
              AND DATEDIFF(e.remdate, NOW()) = 2";
        $res = Yii::app()->db->createCommand($sql)->queryAll();
        foreach ($res as $key => $val)
        {
        // TODO: ПРОВЕРИТЬ СОСТАВЛЕНИЕ ПИСЬМА
            $message = sprintf("Вакансия “<a href='http://%s'>%s</a>” завершается, если Вы не набрали нужного персонала, можно <a href='http://%s'>продлить вакансию</a>. Также, пожалуйста, <a href='http://%s'>оставьте обратную связь по работе сервиса</a>"
            , MainConfig::$SITE . MainConfig::$PAGE_VACANCY . DS . $val['id']
            , $val['title']
            , MainConfig::$SITE . MainConfig::$PAGE_VACANCY_EDIT . DS . $val['id'] . '?bl=3'
            , MainConfig::$SITE . MainConfig::$PAGE_SET_SITE_RATE . DS . $val['id']
            );

            // Share::sendmail($val['emailempl'], "Prommu.com. окончание вакансии", $message);

//            $res = Yii::app()->db->createCommand()
//                ->update('vacation_stat', array( 'status' => 6,
//                    'mdate' => date('Y-m-d H:i:s'),
//                ), 'id = :id', array(':id' => $val['sid']));
//            $ret = array('error' => 0, 'res' => $res);
        } // end foreach

        return 1;
    }



    /**
     * данные для формы публикации вакансии
     */
    public function getVacPubFormData()
    {
        $data = $this->getVacancyData(0);
        $data = array_merge($data, $this->getVacancyEditData($data));

        return $data;
    }



    /**
     * данные для формы редактирования вакансии
     */
    public function getVacEditFormData()
    {
        $idvac = filter_var(Yii::app()->getRequest()->getParam('id'), FILTER_SANITIZE_NUMBER_INT);
        $block = Yii::app()->getRequest()->getParam('bl');
        if( $idvac < 1 ) return array('error' => 1, 'message' => 'Вакансия с таким номером не найдена');

        // проверка наличия вакансии
        $id = Share::$UserProfile->exInfo->id;
        $sql = "SELECT id FROM empl_vacations e WHERE e.id_user = {$id} AND e.id = {$idvac}";
        /** @var $res CDbCommand */
        $res = Yii::app()->db->createCommand($sql);
        $res = $res->queryRow();

        if( !$res['id'] ) return array('error' => 1, 'message' => 'Вакансия с таким номером не найдена');


        $data = $this->getVacancyData($idvac);
        $data = array_merge($data, $this->getVacancyEditData(array_merge($data, array('idvac' => $idvac))), array('idvac' => $idvac)
                , array('block' => $block));

        return $data;
    }


    public function getVacanciesArh()
    {
        $idus = $this->Profile->id ?: Share::$UserProfile->exInfo->id;

        $limit = $this->limit > 0 ? "LIMIT {$this->offset}, {$this->limit}" : '';


        // читаем вакансии
        $sql = "SELECT v.id, v.title, v.status, v.count
                  , DATE_FORMAT(v.crdate, '%d.%m.%Y') crdate, DATE_FORMAT(v.remdate, '%d.%m.%Y') remdate
                  , vs.id_promo
                  , vs.isresponse + 1 isresp
            FROM empl_vacations v
            INNER JOIN ( SELECT v.id FROM empl_vacations v WHERE v.id_user = {$idus} ORDER BY v.id DESC 
                {$limit} ) t1 ON t1.id = v.id 
            LEFT JOIN vacation_stat vs ON vs.id_vac = v.id 
            LEFT JOIN employer e ON e.id_user = v.id_user
            WHERE v.id_user = {$idus}
            AND (v.status = 0 OR vs.status in (6,7))
            ORDER BY v.id DESC
            ";
        
        $res = Yii::app()->db->createCommand($sql)->queryAll();
        foreach ($res as $key => $val)
        {
            if( !isset($data['vacs'][$val['id']]) ) $data['vacs'][$val['id']] = array_merge($val, array('isresp' => array($val['count'],0)));
            if( $val['isresp'] ) $data['vacs'][$val['id']]['isresp'][$val['isresp']-1]++;
        } // end foreach

        return $data;
    }
    /**
     * получаем данные для "Мом вакансии" и приглашение на вакансии
     */
    public function getVacancies ()
    {
        $idus = $this->Profile->id ?: Share::$UserProfile->exInfo->id;

        $limit = $this->limit > 0 ? "LIMIT {$this->offset}, {$this->limit}" : '';


        // читаем вакансии
        $sql = "SELECT v.id, v.title, v.status, v.count, v.vk_link, v.fb_link, v.tl_link
                  , DATE_FORMAT(v.crdate, '%d.%m.%Y') crdate, DATE_FORMAT(v.remdate, '%d.%m.%Y') remdate, t.bdate, t.edate
                  , vs.id_promo, v.ispremium, v.repost, v.ismoder
                  , vs.isresponse + 1 isresp
            FROM empl_vacations v
            INNER JOIN ( SELECT v.id FROM empl_vacations v WHERE v.id_user = {$idus} ORDER BY v.id DESC 
                {$limit} ) t1 ON t1.id = v.id 
            LEFT JOIN vacation_stat vs ON vs.id_vac = v.id 
            LEFT JOIN empl_locations l ON v.id = l.id_vac 
            LEFT JOIN emplv_loc_times t ON l.id = t.id_loc
            LEFT JOIN employer e ON e.id_user = v.id_user
            WHERE v.id_user = {$idus}
            AND (v.status = 1)
            ORDER BY v.id DESC
            ";
        
        $res = Yii::app()->db->createCommand($sql)->queryAll();
        foreach ($res as $key => $val)
        {
            $Termostat = new Termostat();
            $data['analytic'][$val['id']] = $Termostat->getTermostatCount($val['id']);
            $idvac = $val['id'];

            $sql = "SELECT COUNT(*)
                FROM empl_vacations v 
                LEFT JOIN vacation_stat vs ON vs.id_vac = v.id
                WHERE vs.isresponse = 2 AND v.id = {$idvac}";
            $des = Yii::app()->db->createCommand($sql)->queryScalar();

            $data['responses'][$val['id']] = $des;

            if( !isset($data['vacs'][$val['id']]) ) $data['vacs'][$val['id']] = array_merge($val, array('isresp' => array($val['count'],0)));
            if( $val['isresp'] ) $data['vacs'][$val['id']]['isresp'][$val['isresp']-1]++;
        } // end foreach

        return $data;
    }

    public function getVacanciesPrem()
    {
        $idus = $this->Profile->id ?: Share::$UserProfile->exInfo->id;

        $limit = $this->limit > 0 ? "LIMIT {$this->offset}, {$this->limit}" : '';


        // читаем вакансии
        $sql = "SELECT v.id, v.title, v.status, v.count
                  , DATE_FORMAT(v.crdate, '%d.%m.%Y') crdate, DATE_FORMAT(v.remdate, '%d.%m.%Y') remdate
                  , vs.id_promo
                  , vs.isresponse + 1 isresp
            FROM empl_vacations v
            INNER JOIN ( SELECT v.id FROM empl_vacations v WHERE v.id_user = {$idus} ORDER BY v.id DESC 
                {$limit} ) t1 ON t1.id = v.id 
            LEFT JOIN vacation_stat vs ON vs.id_vac = v.id 
            LEFT JOIN employer e ON e.id_user = v.id_user
            WHERE v.id_user = {$idus}
            AND (v.ispremium = 0)
            AND v.status = 1
            ORDER BY v.id DESC
            ";
        
        $res = Yii::app()->db->createCommand($sql)->queryAll();
        foreach ($res as $key => $val)
        {
            if( !isset($data['vacs'][$val['id']]) ) $data['vacs'][$val['id']] = array_merge($val, array('isresp' => array($val['count'],0)));
            if( $val['isresp'] ) $data['vacs'][$val['id']]['isresp'][$val['isresp']-1]++;
        } // end foreach

        return $data;
    }

    /**
     * Колво вакансий для пагинатора
     * @return count
     */
    public function getVacanciesCount()
    {
        $idus = $this->Profile->id ?: Share::$UserProfile->exInfo->id;

        // читаем вакансии
        $sql = "SELECT COUNT(*) cou FROM empl_vacations v WHERE v.id_user = {$idus} AND v.status = 1";
        return Yii::app()->db->createCommand($sql)->queryScalar();
    }



    /**
     * получаем данные вакансии
     */
    public function getVacancyView($inIdVac = false)
    {
        $idvac = $inIdVac ?: filter_var(Yii::app()->getRequest()->getParam('id'), FILTER_SANITIZE_NUMBER_INT);
        if( $idvac < 1 ) return array('error' => 1, 'message' => 'Вакансия с таким номером не найдена');

        $result = $this->getVacancyData($idvac);
        $result['posts'] = $this->getPost();
        
        $sql = "SELECT d.id, d.id_par idpar, d.type, d.postself, d.name FROM user_attr_dict d WHERE d.id_par IN(11,12,13,14,15,16, 131) ORDER BY idpar, id"; //,69,40
        $result['userDictionaryAttrs'] = Yii::app()->db->createCommand($sql)->queryAll();
        $sql = "SELECT COUNT(*) FROM termostat_analytic t WHERE t.id = {$idvac} AND t.type='vacancy'";
        $result['views'] = Yii::app()->db->createCommand($sql)->queryScalar() + 1;

        return $result;
    }

    public function getVacancyViews($inIdVac = false, $idus)
    {
        $idvac = $inIdVac ?: filter_var(Yii::app()->getRequest()->getParam('id'), FILTER_SANITIZE_NUMBER_INT);
        if( $idvac < 1 ) return array('error' => 1, 'message' => 'Вакансия с таким номером не найдена');

        return $this->getVacancyDatasss($idvac, $idus);
    }


    public function getVacancyInfo($inId){

        if( $inId )
        {
        $sql = "SELECT e.id, e.ispremium, e.status, e.ismoder, e.title, e.requirements, e.duties, e.conditions, e.istemp,
                   DATE_FORMAT(e.remdate, '%d.%m.%Y') remdate,e.agefrom, e.ageto, u.email,
                   e.shour,
                   e.sweek,
                   e.smonth,
                   e.svisit,
                   e.isman,
                   e.smart,
                   e.iswoman,
                   e.repost,
                   e.vk_link,
                   e.fb_link,
                   e.tl_link,
                   DATE_FORMAT(e.crdate, '%d.%m.%Y') crdate
              , c1.id_city, c2.name AS ciname, c1.citycu
              , ea.id_attr, em.id_user, em.name
              , d.name AS pname, d.postself
              , ifnull(em.logo, '') logo
            FROM empl_vacations e 
            LEFT JOIN empl_city c1 ON c1.id_vac = e.id 
            LEFT JOIN city c2 ON c2.id_city = c1.id_city 
            JOIN empl_attribs ea ON ea.id_vac = e.id
            JOIN user_attr_dict d ON (d.id = ea.id_attr) AND (d.id_par = 110)
            JOIN employer em ON em.id_user = e.id_user
            JOIN user u ON u.id_user = em.id_user
            WHERE e.id= {$inId}
            ORDER BY e.ispremium DESC, e.id DESC";
            $res = Yii::app()->db->createCommand($sql);
            $res = $res->queryAll();
        //      foreach ($res as $key => $val)
        // {
        //     if( !isset($data['vac_data'][$val['id']]) ) $data['vac_data'][$val['id']] = array_merge($val, array('id' => $val['id']));
        // } // end foreach

        // }
        // else {
        //    $data=  array('error' => '1', 'message' => 'Error data');    
        }

        return $res;

    }

    public function getVacancyData($inIdVac)
    {
        if( $inIdVac )
        {
            // получение данных вакансии
            $sql = "SELECT e.id,e.city, e.ismoder, e.ispremium, e.title, e.requirements, e.duties, e.conditions, e.istemp,e.cardPrommu, e.card, e.repost,e.index, e.meta_h1, e.meta_title, e.meta_description,e.comment,
                       DATE_FORMAT(e.bdate, '%d.%m.%Y') bdate,
                       DATE_FORMAT(e.edate, '%d.%m.%Y') edate,
                       e.shour, e.sweek, e.smart, e.smonth, e.svisit, e.isman, e.iswoman, e.exp, e.id_user idus, e.iscontshow,
                       DATE_FORMAT(e.remdate, '%d.%m.%Y') remdate,
                       e.ismed, e.isavto, e.contacts, e.agefrom, e.ageto, e.status, e.vk_link, e.fb_link, e.tl_link,
                       DATE_FORMAT(e.crdate, '%d.%m.%Y') crdate
                  , c1.id ecid, c1.id_city, c2.name AS ciname, c1.citycu, c2.ismetro, c2.region
                  , DATE_FORMAT(c1.bdate, '%d.%m.%Y') cbdate
                  , DATE_FORMAT(c1.edate, '%d.%m.%Y') cedate 
                  , ea.id_attr
                  , d.name AS pname, d.postself
                  , m.id mid, m.name mname
                  , em.id eid, em.name coname, em.logo
                  , l.id lid, l.npp lnpp, l.name lname, l.addr, l.id_city lidcity, l.id_metros
                  , DATE_FORMAT(t.bdate, '%d.%m.%Y') tbdate
                  , DATE_FORMAT(t.edate, '%d.%m.%Y') tedate
                  , CONCAT(t.bdate, t.edate, t.btime, t.etime) perHash
                  , t.btime, t.etime
                FROM empl_vacations e 
                LEFT JOIN empl_city c1 ON c1.id_vac = e.id 
                LEFT JOIN city c2 ON c2.id_city = c1.id_city 
                LEFT JOIN employer em ON em.id_user = e.id_user
                JOIN empl_attribs ea ON ea.id_vac = e.id
                JOIN user_attr_dict d ON (d.id = ea.id_attr) AND (d.id_par = 110)
                LEFT JOIN empl_locations l ON l.id_vac = e.id
                LEFT JOIN emplv_loc_times t ON t.id_loc = l.id
                LEFT JOIN metro m ON m.id = l.id_metro
                WHERE e.id = {$inIdVac}
                -- AND e.status = 1
                -- AND e.ismoder = 100
                ORDER BY e.ispremium DESC, e.id DESC, c2.name, l.npp, t.npp";
            /** @var $res CDbCommand */
            $res = Yii::app()->db->createCommand($sql);
            $res = $res->queryAll();

            // no data found
            if( count($res) < 1 ) return array('error' => 1, 'message' => 'Вакансия с таким номером не найдена');

            $data['vac'] = array();
            foreach ($res as $key => $val)
            {
                if( !isset($data['vac'][0])) $data['vac'][0] = array('city' => array(), 'post' => array(), 'metroes' => array(), 'hasmetro' => array(), 'location' => array());
                $data['vac'][0]['city'][$val['id_city']] = array($val['id_city'] ? $val['ciname'] : $val['citycu'], $val['cbdate'], $val['cedate'], $val['ecid'], $val['id_city'], 'region'=>$val['region']);
                if( $val['ismetro'] ) $data['vac'][0]['hasmetro'][$val['id_city']] = 1;
                $data['vac'][0]['post'][$val['id_attr']] = $val['pname'];

                $tmp = $data['vac'][0]['city'];

                $data['vac'][0] = array_merge($data['vac'][0], $val);

                $data['vac'][0]['city'] = $tmp;

                $btime = $etime = '';
                if( $val['btime'] )
                {
                    $h = floor($val['btime'] / 60);
                    $m = $val['btime'] - $h * 60;
                    $btime = sprintf('%d:%02d', $h, $m);
                }

                if( $val['etime'] )
                {
                    $h = floor($val['etime'] / 60);
                    $m = $val['etime'] - $h * 60;
                    $etime = sprintf('%d:%02d', $h, $m);
                } // endif

                // периоды локаций
                if( $val['tbdate'] ) $data['vac'][0]['loctime'][$val['lid']][md5($val['perHash'])] = array($val['tbdate'], $val['tedate'], $btime, $etime);
                // локации
                if($val['lname']){
                    $arResult = array();
                    if($val['id_metros']){
                        $sql = "SELECT m.id, m.name FROM metro m WHERE m.id IN({$val['id_metros']})";
                        $arMetros = Yii::app()->db->createCommand($sql)->queryAll();
                        foreach ($arMetros as $m)
                            $arResult[$m['id']] = $m['name'];
                    }
                    $val['mid'] ? $arResult[$val['mid']] = $val['mname'] : $arResult = null;

                    $data['vac'][0]['location'][$val['lidcity']][$val['lid']] = array(
                        'id' => $val['lid'], 
                        'name' => $val['lname'], 
                        'addr' => $val['addr'], 
                        'metro' => $arResult, 
                        'idcity' => $val['id_city'],
                    );
                }
            } // end foreach
            $data['vac'] = $data['vac'][0];

            $data['vac']['comment'] =$res[0]['comment']; 
            // если соикатель - проверяем есть ли отклик и подходит ли по параметрам
            $res = $this->chkResponse(['idvac' => $inIdVac, 'vacdata' => $data['vac']]);
            $data['response'] = $res;
            $data['vacAttribs'] = $data['response']['vacAttribs'];
            unset($data['response']['vacAttribs']);

            // получаем данные для вкладок
            $data['vacResponses'] = $this->getTabsData($inIdVac, $data['vac']['idus'], $data['response']['status']);
            //
            //      META
            //
            $arSeo = Seo::getMetaForVac($data['vac']);

            // устанавливаем title
            if(empty($data['vac']['meta_title']))
                $data['vac']['meta_title'] = $arSeo['meta_title'];

            // устанавливаем h1
            if(empty($data['vac']['meta_h1']))
                $data['vac']['meta_h1'] = $arSeo['meta_h1'];

            // устанавливаем description
            if(empty($data['vac']['meta_description']))
                $data['vac']['meta_description'] = $arSeo['meta_description'];
        }
        else
        {
            $data = array('idvac' => 0);
        } // endif

        return $data;
    }

    public function updateVacancy($id, $data)
    {
        if(isset($data['cur_status'])) {
            $data['ismoder'] = $data['cur_status']==100 ? $data['cur_status'] : 0;
            unset($data['cur_status']);            
        }
        else {
            // сохранение вакансии
            $data['index'] = (isset($data['index']) ? : 0);
            $data['ismoder'] = (empty($data['ismoder']) ? 0 : $data['ismoder']);
        }
        Yii::app()->db->createCommand()
            ->update('empl_vacations', $data, 'id=:id', array(':id'=>$id));

        if($data['ismoder'] != "100" )
            return;

        // достаем данные вакансии
        $arVac = Yii::app()->db->createCommand()
            ->select('v.id, v.id_user, v.title, 
                v.isman, v.iswoman, v.ismed, 
                v.isavto, v.smart, v.card, 
                v.cardPrommu, v.repost, 
                e.name, u.email')
            ->from('empl_vacations v')
            ->leftJoin('employer e', 'e.id_user=v.id_user')
            ->leftJoin('user u', 'u.id_user=v.id_user')
            ->where('v.id=:id', array(':id' => $id))
            ->queryRow();
        // достаем города вакансии
        $arVac['cities'] = Yii::app()->db->createCommand()
            ->select('ec.id_city, c.id_co, c.name')
            ->from('empl_city ec')
            ->leftJoin('city c', 'c.id_city=ec.id_city')
            ->where('ec.id_vac=:id', array(':id' => $id))
            ->queryAll();
        // достаем должности вакансии
        $arVac['posts'] = Yii::app()->db->createCommand()
            ->select('uad.id, uad.name')
            ->from('empl_attribs ea')
            ->leftJoin('user_attr_dict uad', 'uad.key=ea.key')
            ->where('ea.id_vac=:id AND uad.id_par=110', array(':id' => $id))
            ->queryAll();
        // создаем параметры для фильтра
        $host = Subdomain::$HOST;
        $url = $host . MainConfig::$PAGE_SEARCH_PROMO . '?';
        foreach ($arVac['cities'] as $c) {
            $_POST['cities'][] = $c['id_city'];
            $url .= 'cities[]=' . $c['id_city'] . '&';
        }

        foreach ($arVac['posts'] as $p) {
            $_POST['posts'][] = $p['id'];
            $url .= 'posts[]=' . $p['id'] . '&';
        }
        $_POST['sm'] = $arVac['isman'];
        $_POST['sf'] = $arVac['iswoman'];
        $_POST['mb'] = $arVac['ismed'];
        $_POST['avto'] = $arVac['isavto'];
        $_POST['smart'] = $arVac['smart'];
        $_POST['card'] = $arVac['card'];
        $_POST['cardPrommu'] = $arVac['cardPrommu'];

        $url .= 'sm=' . $arVac['isman'] . '&'
            . 'sf=' . $arVac['iswoman'] . '&'
            . 'mb=' . $arVac['ismed'] . '&'
            . 'avto=' . $arVac['isavto'] . '&'
            . 'smart=' . $arVac['smart'] . '&'
            . 'card=' . $arVac['card'] . '&'
            . 'cardPrommu=' . $arVacInfo['cardPrommu'];
        // ищем 10 соискателей, и сортируем, чтобы сначала вывести с фото
        $sPromo = new SearchPromo();
        $cnt = $sPromo->searchPromosCount();
        $pages = new CPagination($cnt);
        $pages->pageSize = 10;
        $pages->applyLimit($sPromo);
        $arr = $sPromo->getPromos(true)['promo'];

        $arRes = array();
        foreach ($arr as $u) {
            $u['src'] = $host . '/' . MainConfig::$PATH_APPLIC_LOGO . '/' . 
                ($u['photo'] 
                    ? ($u['photo'] . '100.jpg')
                    : ($u['isman'] ? MainConfig::$DEF_LOGO : MainConfig::$DEF_LOGO_F)
                );
            $u['link'] = $host . MainConfig::$PAGE_PROFILE_COMMON . '/' . $u['id_user'];
            $u['name'] = trim($u['firstname'] . ' ' . $u['lastname']);
            $datetime = new DateTime($u['birthday']);
            $interval = $datetime->diff(new DateTime(date("Y-m-d")));
            $u['years'] = $interval->format("%Y");
            $u['years'] = $u['years'] . ' ' . Share::endingYears($u['years']);
            empty($u['photo']) ? array_push($arRes, $u) : array_unshift($arRes, $u);
        }
        // формируем html письма
        $file = file_get_contents(Yii::app()->basePath . '/views/mails/after-moder-vac.php');
        preg_match_all('/#LPLACE(.*?)#LPLACE|#CYCLE(.*?)#CYCLE/', $file, $matches);

        $listPlace = '';
        if(sizeof($arRes)>=5) {
            $list = '';
            for ($i=0; $i<5; $i++) {
                $e = $arRes[$i];
                $list .= preg_replace(
                    array('/#ALINK/','/#ASRC/','/#ANAME/','/#ACITY/','/#AYEARS/'), 
                    array($e['link'],$e['src'],$e['name'],join(', ',$e['city']),$e['years']), 
                    $matches[2][1]
                );
            }
            $listPlace = preg_replace(
                    array('/#LCONTENT/', '/#ANKETY/'), 
                    array($list, $url), 
                    $matches[1][0]
                );
        }
        $arNeed = array('/#EMP/','/#VNAME/','/#VLINK/','/#CONTACT/');
        $arRpls = array(
                $arVac['name'],
                $arVac['title'],
                $host . MainConfig::$PAGE_VACANCY . "/" . $id,
                $host . MainConfig::$PAGE_FEEDBACK
            );
        $file = preg_replace($arNeed, $arRpls, $file);
        $file = str_replace($matches[0][0], $listPlace, $file);
        $message = str_replace($matches[0][1], '', $file);
        // письмо работодателю
        Share::sendmail($arVac['email'], "Prommu.com. Вакансия прошла модерацию", $message);
        // репостим
        $this->VkRepost($id, $arVac['repost']);
        // событие ПУШ
        $res = Yii::app()->db->createCommand()
            ->select("push")
            ->from('user_push')
            ->where('id=:id',array(':id'=>$arVac['id_user']))
            ->queryRow();

        if($res) {
            $type = "vacmoder";
            $api = new Api();
            $api->getPush($res['push'], $type);
        } 
    }

    public function ChangeModer($id, $st)
    {
        Yii::app()->db->createCommand()
            ->update('empl_vacations', array(
                'status' => $st,
            ), 'id=:id', array(':id' => $id));
    }

    private function getVacancyEditData($inVacData = array())
    {
        $idus = Share::$UserProfile->exInfo->id;

        // читаем должности
        $data['posts'] = $this->getPost();


        // читаем города
        $sql = "SELECT co.id_co idco,
                  co.name
                  , uc.id_city idci
                FROM country co
                LEFT JOIN city ci ON co.id_co = ci.id_co
                LEFT JOIN empl_city uc ON ci.id_city = uc.id_city AND uc.id_vac = {$inVacData['idvac']}
                GROUP BY co.id_co, co.name
                ORDER BY name";
        $data['countries'][] = Yii::app()->db->createCommand($sql)->queryAll();
        foreach ($data['countries'][0] as $key => $val)
        {
            if( $val['idci'] ) { $data['countries'][1] = $val['idco']; break; }
        } // end foreach



        // языки словаря
        $sql = "SELECT d.id, d.id_par idpar, d.postself, d.type, d.name , a.`key`, a.val
                FROM user_attr_dict d 
                LEFT JOIN empl_attribs a ON a.id_attr = d.id AND a.id_vac = {$inVacData['idvac']}
                WHERE d.id_par = 40
                ORDER BY name";
        $data['langs'] = Yii::app()->db->createCommand($sql)->queryAll();

        foreach ($data['langs'] as $key => &$val) { if ($val['val'] > 0 ) $data['langsSeled'][$val['id']] = $val['val']; }

        $data['langsLvls'] = array(array("1", "Начальный"), array("2", "Средний"), array("3", "Высокий"), array("4", "Продвинутый"));


        // получаем метро
        if( isset($inVacData['vac']['hasmetro']) && count($inVacData['vac']['hasmetro']) )
        {
            $sql = "SELECT m.id, m.id_city idcity, m.name FROM metro m WHERE m.id_city IN (".join(',', array_keys($inVacData['vac']['hasmetro'])).") ORDER BY m.name";
            /** @var $res CDbCommand */
            $res = Yii::app()->db->createCommand($sql);
            $res = $res->queryAll();

            if( $res )
            {
                $data['metro'] = array();
                foreach ($res as $key => $val)
                {
                    $data['metro'][$val['idcity']][$val['id']] = $val;
                }
            } 
        } 


        // характиристики вакансии из словаря
        $sql = "SELECT d.id, d.id_par idpar, d.type, d.name, d.postself FROM user_attr_dict d WHERE d.id_par IN(11,12,13,14,15,16, 131) ORDER BY idpar, id"; //,69,40
        $data['userDictionaryAttrs'] = Yii::app()->db->createCommand($sql)->queryAll();

        // получаем город Работодатедя
        $sql = "SELECT uc.id_city id, c.name, c.id_co 
            FROM user_city uc 
            LEFT JOIN city c ON c.id_city=uc.id_city
            WHERE uc.id_user=".$idus;
        $data['usercity'] = Yii::app()->db->createCommand($sql)->queryRow();

        return $data;
    }

    public function saveVacpubData($inProps = [])
    {
        $idus = Share::$UserProfile->exInfo->id;
        $idvac = filter_var(Yii::app()->getRequest()->getParam('id'), FILTER_SANITIZE_NUMBER_INT);
        $blockpub =  Yii::app()->getRequest()->getParam('blockpub');
        $block = Yii::app()->getRequest()->getParam('block');
        $arrs = '';
        if($idvac) {
        $sql = "SELECT e.id, e.ispremium, e.status, e.title, e.requirements, e.duties, e.conditions, e.istemp,
                   DATE_FORMAT(e.remdate, '%d.%m.%Y') remdate,e.agefrom, e.ageto,
                   e.shour,
                   e.sweek,
                   e.smonth,
                   e.isman,
                   e.smart,
                   e.iswoman,
                   e.repost,
                   DATE_FORMAT(e.crdate, '%d.%m.%Y') crdate
              , c1.id_city, c2.name AS ciname, c1.citycu
              , ea.id_attr
              , d.name AS pname, d.postself
              , ifnull(em.logo, '') logo
            FROM empl_vacations e 
            LEFT JOIN empl_city c1 ON c1.id_vac = e.id 
            LEFT JOIN city c2 ON c2.id_city = c1.id_city 
            JOIN empl_attribs ea ON ea.id_vac = e.id
            JOIN user_attr_dict d ON (d.id = ea.id_attr) AND (d.id_par = 110)
            JOIN employer em ON em.id_user = e.id_user
            WHERE e.id= {$idvac}
            ORDER BY e.ispremium DESC, e.id DESC";
            $res = Yii::app()->db->createCommand($sql);
            $resVac = $res->queryAll();
        }

        // публикация вакансии
        if( $blockpub == 'pub' )
        {
            // bl1
            $fields['title'] = $inProps['title'] ?: filter_var(Yii::app()->getRequest()->getParam('vacancy-title'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $fields['exp'] = Yii::app()->getRequest()->getParam('expirience');
            $fields['requirements'] = filter_var(Yii::app()->getRequest()->getParam('requirements'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $fields['duties'] = filter_var(Yii::app()->getRequest()->getParam('duties'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $fields['conditions'] = filter_var(Yii::app()->getRequest()->getParam('conditions'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $fields['agefrom'] = $inProps['agefrom'] ?: filter_var(Yii::app()->getRequest()->getParam('age-from'), FILTER_SANITIZE_NUMBER_INT);
            $fields['ageto'] = $inProps['ageto'] ?: filter_var(Yii::app()->getRequest()->getParam('age-to'), FILTER_SANITIZE_NUMBER_INT);
            $fields['isman'] = $inProps['isman'] ?: filter_var(Yii::app()->getRequest()->getParam('mans'), FILTER_SANITIZE_NUMBER_INT);
            $fields['iswoman'] = $inProps['iswoman'] ?: filter_var(Yii::app()->getRequest()->getParam('wonem'), FILTER_SANITIZE_NUMBER_INT);
            $fields['ismed'] = $inProps['ismed'] ?: filter_var(Yii::app()->getRequest()->getParam('ismed'), FILTER_SANITIZE_NUMBER_INT);
            $fields['isavto'] = $inProps['isavto'] ?: filter_var(Yii::app()->getRequest()->getParam('isavto'), FILTER_SANITIZE_NUMBER_INT);
            $fields['smart'] = $inProps['smart'] ?: filter_var(Yii::app()->getRequest()->getParam('smart'), FILTER_SANITIZE_NUMBER_INT);
            $fields['card'] = $inProps['card'] ?: filter_var(Yii::app()->getRequest()->getParam('bank-card'), FILTER_SANITIZE_NUMBER_INT);
            $fields['cardPrommu'] = $inProps['cardPrommu'] ?: filter_var(Yii::app()->getRequest()->getParam('card-prommu'), FILTER_SANITIZE_NUMBER_INT);
            // bl2
            $filter = function($val) { return preg_match("/([0-9]+)[.,]?([0-9]{0,2})/", $val, $res) ? $res[1].'.'.$res[2] : 0; };
            $fields['shour'] = $inProps['shour'] ?: filter_var(Yii::app()->getRequest()->getParam('salary-rub-hour'), FILTER_CALLBACK, array('options' => $filter));
            $fields['sweek'] = filter_var(Yii::app()->getRequest()->getParam('salary-rub-week'), FILTER_CALLBACK, array('options' => $filter));
            $fields['smonth'] = filter_var(Yii::app()->getRequest()->getParam('salary-rub-month'), FILTER_CALLBACK, array('options' => $filter));
            $fields['svisit'] = filter_var(Yii::app()->getRequest()->getParam('salary-rub-visit'), FILTER_CALLBACK, array('options' => $filter));
            //bl3
            $dateWorkStart = filter_var(Yii::app()->getRequest()->getParam('date-work-start'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $dateWorkEnd = filter_var(Yii::app()->getRequest()->getParam('date-work-end'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $fields = array_merge($fields, array(
               'bdate' => date('Y-m-d', strtotime($dateWorkStart)),
               'edate' => date('Y-m-d', strtotime($dateWorkEnd)),
            ));
            $fields['istemp'] = $inProps['istemp'] ?: filter_var(Yii::app()->getRequest()->getParam('busyType'), FILTER_SANITIZE_NUMBER_INT);
            // bl4
            $vk = $inProps['vk'] ?: filter_var(Yii::app()->getRequest()->getParam('vk'), FILTER_SANITIZE_NUMBER_INT);
            $fb = $inProps['fb'] ?: filter_var(Yii::app()->getRequest()->getParam('fb'), FILTER_SANITIZE_NUMBER_INT);
            $tl = $inProps['tl'] ?: filter_var(Yii::app()->getRequest()->getParam('tl'), FILTER_SANITIZE_NUMBER_INT);
            ///Posting
            if($fb){
                $fields['repost'] = '010';
            }
            if($vk){
                $fields['repost'] = '100';
            }
            if($tl){
                $fields['repost'] = '001';
            }
            if($vk && $fb && $tl) $fields['repost'] = '111';
            if($vk && $fb && !$tl) $fields['repost'] = '110';
            if(!$vk && $fb && $tl) $fields['repost'] = '011';
            if($vk && !$fb && $tl) $fields['repost'] = '101';

            //
            $fields['status'] = 0;
            $fields = array_merge($fields, array( 'id_user' => $idus,
                'id_empl' => Share::$UserProfile->exInfo->eid,
                'crdate' => date("Y-m-d H:i:s"),
                'mdate' => date("Y-m-d H:i:s"),
            ));
            $fields['remdate'] = date('Y-m-d 23:59:59', strtotime($inProps['remdate'] ?: Yii::app()->getRequest()->getParam('date-autounpublish')));

            $res = Yii::app()->db->createCommand()
                ->insert('empl_vacations', $fields);

            $flagNew = 1;
            $idvac = Yii::app()->db->createCommand('SELECT LAST_INSERT_ID()')->queryScalar();
            // сохраняем должности
            $postt = $this->saveVacPosts($idvac);
            // сохраняем атрибуты вакансии
            $this->saveVacAttribs($idvac);
            // сохраняем города
            $idcity = $this->saveCities($idvac);
            // сохраняем локации
            $this->saveLocations($idvac, $idcity);

            ///API
            if($fields['isman'] == 1)
                $male = 'MALE';
            else
                $male = 'FEMALE';

            $Q1 = Yii::app()->db->createCommand()
                ->select("v.id")
                ->from('user_api v')
                ->where('v.id = :id ', array(':id' => Share::$UserProfile->id))
                ->queryRow();
        }
        elseif( $block == 1 )
        {
            $fields['title'] = filter_var(Yii::app()->getRequest()->getParam('vacancy-title'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            // сохраняем должности
            $this->saveVacPosts($idvac);
            // есть ли опыт
            $fields['exp'] = Yii::app()->getRequest()->getParam('expirience');
            $fields['agefrom'] = filter_var(Yii::app()->getRequest()->getParam('age-from'), FILTER_SANITIZE_NUMBER_INT);
            $fields['ageto'] = filter_var(Yii::app()->getRequest()->getParam('age-to'), FILTER_SANITIZE_NUMBER_INT);
            $fields['isman'] = filter_var(Yii::app()->getRequest()->getParam('mans'), FILTER_SANITIZE_NUMBER_INT);
            $fields['iswoman'] = filter_var(Yii::app()->getRequest()->getParam('wonem'), FILTER_SANITIZE_NUMBER_INT);
            $fields['ismed'] = filter_var(Yii::app()->getRequest()->getParam('ismed'), FILTER_SANITIZE_NUMBER_INT);
            $fields['isavto'] = filter_var(Yii::app()->getRequest()->getParam('isavto'), FILTER_SANITIZE_NUMBER_INT);
            $fields['smart'] = $inProps['smart'] ?: filter_var(Yii::app()->getRequest()->getParam('smart'), FILTER_SANITIZE_NUMBER_INT);
            // сохраняем атрибуты вакансии
            $this->saveVacAttribs($idvac);

            if($resVac['title'] != $fields['title']){
                $arrs .= 'Название|';
            }
             if($resVac['ageto'] != $fields['ageto']){
                $arrs .= 'Возраст от|';
            }
           
        }
        elseif( $block == 2 )
        {
            $filter = function($val) { return preg_match("/([0-9]+)[.,]?([0-9]{0,2})/", $val, $res) ? $res[1].'.'.$res[2] : 0; };
            $fields['shour'] = filter_var(Yii::app()->getRequest()->getParam('salary-rub-hour'), FILTER_CALLBACK, array('options' => $filter));
            $fields['sweek'] = filter_var(Yii::app()->getRequest()->getParam('salary-rub-week'), FILTER_CALLBACK, array('options' => $filter));
            $fields['smonth'] = filter_var(Yii::app()->getRequest()->getParam('salary-rub-month'), FILTER_CALLBACK, array('options' => $filter));
            $fields['svisit'] = filter_var(Yii::app()->getRequest()->getParam('salary-rub-visit'), FILTER_CALLBACK, array('options' => $filter));
            $fields['cardPrommu'] = filter_var(Yii::app()->getRequest()->getParam('card-prommu'), FILTER_SANITIZE_NUMBER_INT);
            $fields['card'] = filter_var(Yii::app()->getRequest()->getParam('bank-card'), FILTER_SANITIZE_NUMBER_INT);
            // сохраняем атрибуты вакансии
            $this->saveVacAttribs($idvac);

            if($resVac['shour'] != $fields['shour']){
                $arrs .= 'Почасовая оплата|';
            }
           

        }
        elseif( $block == 4 )
        {
            $fields['istemp'] = $inProps['istemp'] ?: filter_var(Yii::app()->getRequest()->getParam('busyType'), FILTER_SANITIZE_NUMBER_INT);
            $fields['requirements'] = filter_var(Yii::app()->getRequest()->getParam('requirements'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $fields['duties'] = filter_var(Yii::app()->getRequest()->getParam('duties'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $fields['conditions'] = filter_var(Yii::app()->getRequest()->getParam('conditions'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            if($resVac['requirements'] != $fields['requirements']){
                $arrs .= 'Требования|';
            }
            if($resVac['duties'] != $fields['duties']){
                $arrs .= 'Обязанности|';
            }
            if($resVac['conditions'] != $fields['conditions']){
                $arrs .= 'Условия|';
            }

        }
//        elseif( $block == 5 )
//        {
//        }
        elseif( $block == 6 )
        {
            $fields['contacts'] = filter_var(Yii::app()->getRequest()->getParam('ContactInfo'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $fields['iscontshow'] = Yii::app()->getRequest()->getParam('isShowContacts') ? 1 : 0;
        }
        elseif( $block == 'vacpage' ){
            $fields['title'] = filter_var(Yii::app()->getRequest()->getParam('vacancy-title'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            // сохраняем должности
            $this->saveVacPosts($idvac);
            // есть ли опыт
            $fields['exp'] = Yii::app()->getRequest()->getParam('expirience');
            $fields['agefrom'] = filter_var(Yii::app()->getRequest()->getParam('age-from'), FILTER_SANITIZE_NUMBER_INT);
            $fields['ageto'] = filter_var(Yii::app()->getRequest()->getParam('age-to'), FILTER_SANITIZE_NUMBER_INT);
            $fields['isman'] = filter_var(Yii::app()->getRequest()->getParam('mans'), FILTER_SANITIZE_NUMBER_INT);
            $fields['iswoman'] = filter_var(Yii::app()->getRequest()->getParam('wonem'), FILTER_SANITIZE_NUMBER_INT);
            $fields['ismed'] = filter_var(Yii::app()->getRequest()->getParam('ismed'), FILTER_SANITIZE_NUMBER_INT);
            $fields['isavto'] = filter_var(Yii::app()->getRequest()->getParam('isavto'), FILTER_SANITIZE_NUMBER_INT);
            $fields['smart'] = $inProps['smart'] ?: filter_var(Yii::app()->getRequest()->getParam('smart'), FILTER_SANITIZE_NUMBER_INT);
            $fields['requirements'] = filter_var(Yii::app()->getRequest()->getParam('requirements'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $fields['duties'] = filter_var(Yii::app()->getRequest()->getParam('duties'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $fields['conditions'] = filter_var(Yii::app()->getRequest()->getParam('conditions'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            $filter = function($val) { return preg_match("/([0-9]+)[.,]?([0-9]{0,2})/", $val, $res) ? $res[1].'.'.$res[2] : 0; };
            $fields['shour'] = filter_var(Yii::app()->getRequest()->getParam('salary-rub-hour'), FILTER_CALLBACK, array('options' => $filter));
            $fields['sweek'] = filter_var(Yii::app()->getRequest()->getParam('salary-rub-week'), FILTER_CALLBACK, array('options' => $filter));
            $fields['smonth'] = filter_var(Yii::app()->getRequest()->getParam('salary-rub-month'), FILTER_CALLBACK, array('options' => $filter));
            $fields['svisit'] = filter_var(Yii::app()->getRequest()->getParam('salary-rub-visit'), FILTER_CALLBACK, array('options' => $filter));
            $fields['cardPrommu'] = filter_var(Yii::app()->getRequest()->getParam('card-prommu'), FILTER_SANITIZE_NUMBER_INT);
            $fields['card'] = filter_var(Yii::app()->getRequest()->getParam('bank-card'), FILTER_SANITIZE_NUMBER_INT);
            $fields['istemp'] = $inProps['istemp'] ?: filter_var(Yii::app()->getRequest()->getParam('busyType'), FILTER_SANITIZE_NUMBER_INT);
            // сохраняем атрибуты вакансии
            $this->saveVacAttribs($idvac);

            if($resVac['title'] != $fields['title']){
                $arrs .= 'Название|';
            }
             if($resVac['ageto'] != $fields['ageto']){
                $arrs .= 'Возраст от|';
            }
            
            if($resVac['shour'] != $fields['shour']){
                $arrs .= 'Почасовая оплата|';
            }
           

            if($resVac['requirements'] != $fields['requirements']){
                $arrs .= 'Требования|';
            }
            if($resVac['duties'] != $fields['duties']){
                $arrs .= 'Обязанности|';
            }
            if($resVac['conditions'] != $fields['conditions']){
                $arrs .= 'Условия|';
            }
        }
        else
        {



            // сохраняем языки
//            $this->saveUserLang($idvac);
        } // endif


        // редактирование
        if( $fields && $idvac )
        {
            unset($fields['id_user']);
            $fields['mdate'] = date("Y-m-d H:i:s");
            $fields['ismoder'] = 0;
            $res = Yii::app()->db->createCommand()
                ->update('empl_vacations', $fields
                    ,'id = :id', array(':id' => $idvac) );
        if($arrs != ''){
           $sql = "SELECT ru.email, r.firstname, r.lastname
            FROM vacation_stat s
            INNER JOIN empl_vacations e ON e.id = s.id_vac
            INNER JOIN resume r ON s.id_promo = r.id
            INNER JOIN user ru ON ru.id_user = r.id_user
            WHERE s.status IN(5,6) AND e.id = {$idvac}";
        /** @var $res CDbCommand */
        $res = Yii::app()->db->createCommand($sql);
        $rest = $res->queryAll();

        if($rest!= ""){
            for($i = 0; $i < count($rest); $i ++){
                $content = file_get_contents(Yii::app()->basePath . "/views/mails/app/emp-changing-vac.html");
                  $content = str_replace('#APPNAME#', $rest[$i]['firstname'] . ' ' . $rest[$i]['lastname'], $content);
                 $content = str_replace('#EMPCOMPANY#', Share::$UserProfile->exInfo->name, $content);
                 $content = str_replace('#VACID#', $idvac, $content);
                 $content = str_replace('#VACNAME#', $fields['title'], $content);
                 $content = str_replace('#VACPARAMLIST#',$arrs, $content);
                 $content = str_replace('#VACLINK#',  MainConfig::$SITE . MainConfig::$PAGE_VACANCY . DS . $idvac, $content);
               if(strpos($rest[$i]['email'], "@") !== false)
               Share::sendmail($rest[$i]['email'], "Prommu.com Изменение вакансии №" .$idvac, $content);
            }

        
        

         $content = file_get_contents(Yii::app()->basePath . "/views/mails/app/emp-changing-vac.html");
                  $content = str_replace('#APPNAME#', "администратор", $content);
                 $content = str_replace('#EMPCOMPANY#', Share::$UserProfile->exInfo->name, $content);
                 $content = str_replace('#VACID#', $idvac, $content);
                 $content = str_replace('#VACNAME#', $fields['title'], $content);
                 $content = str_replace('#VACPARAMLIST#',$arrs, $content);
                 $content = str_replace('#VACLINK#','http://' . MainConfig::$SITE . '/admin/site/VacancyEdit'. DS .$idvac, $content);
               
         $email[0] = "susgresk@gmail.com";
        $email[1] = "mk0630733719@gmail.com";
        $email[2] = "denisgresk@gmail.com";
        for($i = 0; $i <3; $i++){
           Share::sendmail($email[$i], "Prommu.com Изменение вакансии №" . $idvac, $content);
       
        }
       }
    }
        }
        $showFlash = false;
        if($flagNew){
            $message = 'Ваша вакансия сохранена, но не отображается.<br/>Для того, чтобы вакансия отображалась, Вам необходимо при помощи редактирования и существующих подсказок, добавить всю необходимую информацию к публикуемой вакансии.<br/>После этого нажмите кнопку "Опубликовать вакансию"';
            $showFlash = true;
        }
        else{
            $res = Yii::app()->db->createCommand()
                ->select('status')
                ->from('empl_vacations v')
                ->where('v.id = :id AND v.id_user = :idus', array(':id' => $idvac, ':idus' => Share::$UserProfile->id))
                ->queryRow();
            $showFlash = $res['status'];          
            $message = 'Данные успешно сохранены и направлены на модерацию. Обычно это занимает от 2 - 4 часов.';
        }
        if($showFlash){
          Yii::app()->user->setFlash('Message', array('type' => '-green', 'message' => $message));  
        }
        
        return array('idvac' => $idvac);
    }



    /**
     * Активировать вакансию
     */
    public function vacActivate($inProps=[])
    {
        $id = $inProps['idvac']?:filter_var(Yii::app()->getRequest()->getParam('id'), FILTER_SANITIZE_NUMBER_INT);
        $isDeactivate = $inProps['deactive']?:filter_var(Yii::app()->getRequest()->getParam('d', 0), FILTER_SANITIZE_NUMBER_INT);
        $idus = $inProps['idus'];

        $error = -100;
        try
        {
            $Q1 = Yii::app()->db->createCommand()
                ->select("id, title, ismoder")
                ->from('empl_vacations v')
                ->where('v.id = :id AND v.id_user = :idus', array(':id' => $id, ':idus' => $idus ?: Share::$UserProfile->id))
                ->queryRow();

            if( !$Q1['id'] ) throw new Exception('', -101);

            $fields = array(
                'status' => $isDeactivate ? 0 : 1,
                'ismoder' => 0, // деактивировать модерацию
            );

        $title = $Q1['title'];
        // $text = "Опубликована вакансия $title";
        //   $sendto ="https://api.telegram.org/bot525649107:AAFWUj7O8t6V-GGt3ldzP3QBEuZOzOz-ij8/sendMessage?chat_id=@prommubag&text=$text";
        //                file_get_contents($sendto);
           // $this->getDigest($Q1['id'],  $Q1['title']);
           
            $res = Yii::app()->db->createCommand()
                ->update('empl_vacations', $fields, 'id = :id', array(':id' => $id));

            
        

            if( $isDeactivate ) $message = 'Вакансия снята с публикации';
            else {
                if( (int)$Q1['ismoder'] == 0 )
                {
                    $message = sprintf("Ув. %s. 
                            <br />
                            <br />
                            Ваша вакансия «%s» отправлена на модерацию – и будет опубликована в ближайшее время. Обычно это занимает до 2 часов в рабочее время.
                            <br />
                            <br />
                            Сообщаем Вам что информацию по данным вакансии можете корректировать исходя из возникших ситуаций и задач.
                            <br />
                            Ссылка на Вашу вакансию: <a href='https://%3$01s'>%s</a>
                            <br />
                            <br />
                            Преимущества размещения вакансии на сервисе ПРОММУ:
                            <ol>
                              <li>Большая база проверенного персонала</li>
                              <li>Система отзывов и рейтинга персонала</li>
                              <li>Размещение вакансии происходит с подсказками и с возможностью учесть все мелочи по вакансии (избежание дополнительных утомительных вопросов)</li>
                              <li>Push оповещения персонала персонала подходящего под указанные параметры в вакансии</li>
                              <li>Обсуждение вакансии на сервисе онлайн со всеми отобранными соискателями (всех кого отобрали позже – смогут прочесть все ранее написанные сообщения)</li>
                              <li>Отбирать и Отклонять кандидатов на вакансию в 1 клик</li>
                              <li>И много других преимуществ, которые Вы ощутите работая на нашем сервисе</li>
                              <li>Легких, прибыльных и удачных Вам проектов.</li>
                            </ol>
                            Если у Вас возникли сложности или есть дополнительные вопросы обращайтесь сюда <a href='mailto:%4$01s'>%s</a> мы максимально быстро дадим ответ",
                        Share::$UserProfile->exInfo->efio,
                        $Q1['title'],
                        MainConfig::$SITE . MainConfig::$PAGE_VACANCY . DS . $id,
                        "https://prommu.com/feedback"
                    );

                    Share::sendmail(Share::$UserProfile->exInfo->email, "Prommu.com. Публикация вакансии", $message);
                } // endif

                $link = 'http://' . MainConfig::$SITE . '/admin/site/VacancyEdit'. DS .$id;
                // Письмо админу
                $message = sprintf("Пользователь <a href='%s'>%s</a> отправил вакансию №%s <a href='%s'>%s</a> на модерацию.
                    <br />
                    <br />
                    Перейти на модерацию вакансий <a href='%s'>по ссылке</a>.",
                    'https://' . MainConfig::$SITE . MainConfig::$PAGE_PROFILE_COMMON . DS . Share::$UserProfile->id,
                    Share::$UserProfile->exInfo->name,
                    $Q1['id'],
                    'https://' . MainConfig::$SITE . MainConfig::$PAGE_VACANCY . DS . $id,
                    $Q1['title'],

                   $link
                );
        $email[0] = "denisgresk@gmail.com";
        $email[1] = "mk0630733719@gmail.com";
        //$email[2] = "code@code.com";
        for($i = 0; $i <2; $i++){
           Share::sendmail($email[$i], "Prommu.com Размещение вакансии №" . $idvac, $message);
       
        }
            

                $message = 'Ваша вакансия отправлена на модерацию – и будет опубликована в ближайшее время. Обычно это занимает до 2 часов в рабочее время.';
            }

            $error = 0;
        }
        catch (Exception $e) {
            $message = $e->getMessage();
            $error = $e->getCode();
        } // endtry

        //return array('error' => $error, 'id' => $id);
        return array('error' => $error, 'message' => $message, 'id' => $id);
    }



    /**
     * @deprecated
     */
    private function saveMetroes($inVacId)
    {
        $metro = Yii::app()->getRequest()->getParam('metro');

        if( $metro )
        {
            $insData = array();
            foreach ($metro as $key => $val)
            {
                foreach ($val as $key2 => $val2)
                {
                    $insData[] = array('id_vac' => $inVacId, 'id_city' => $key, 'id_metro' => $val2);
                } // end foreach
            } // end foreach

            Yii::app()->db->createCommand()->delete('empl_metro', 'id_vac = :idvac', array(':idvac' => $inVacId));
            $command = Yii::app()->db->schema->commandBuilder->createMultipleInsertCommand('empl_metro', $insData);
            $command->execute();
        } // endif
    }



    private function saveLocations($inVacId, $inIdCity)
    {
        $name = filter_var(Yii::app()->getRequest()->getParam('loname'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $addr = filter_var(Yii::app()->getRequest()->getParam('loaddr'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $lobdate = Yii::app()->getRequest()->getParam('lobdate');
        $loedate = Yii::app()->getRequest()->getParam('loedate');
        $lobtime = Yii::app()->getRequest()->getParam('lobtime');
        $loetime = Yii::app()->getRequest()->getParam('loetime');

        if( $lobdate )
        {
            // сохраняем локацию
            $res = Yii::app()->db->createCommand()
                ->insert('empl_locations', array('id_vac' => $inVacId,
                        'id_city' => $inIdCity,
                        'npp' => 1,
                        'name' => $name,
                        'addr' => $addr,
                    ));
            $idloc = Yii::app()->db->createCommand('SELECT LAST_INSERT_ID()')->queryScalar();


            $i = 1;
            $insData = array();
            foreach ($lobtime as $key => $val)
            {
                $btime = $val;
                $etime = $loetime[$key];

                if( $btime || $etime )
                {
                    $arr = explode(':', $btime);
                    $btime = $arr[0] * 60 + $arr[1];
                    $arr = explode(':', $etime);
                    $etime = $arr[0] * 60 + $arr[1];

                } // endif


                $insData[] = array('id_loc' => $idloc, 'npp' => $i, 'bdate' => date("Y-m-d", strtotime($lobdate[$key])), 'edate' => date("Y-m-d", strtotime($loedate[$key])), 'btime' => $btime, 'etime' => $etime);

                $i++;
            } // end foreach
            // сохранение периодов локации
            $command = Yii::app()->db->schema->commandBuilder->createMultipleInsertCommand('emplv_loc_times', $insData);
            $command->execute();
        } // endif
    }



    /**
     * @deprecated
     */
    private function saveLocaPreparedData($inVacId, $inData)
    {

        Yii::app()->db->createCommand()->delete('empl_locations', 'id_vac = :idvac', array(':idvac' => $inVacId));
//        Yii::app()->db->createCommand()->delete('empl_wtime', 'id_vac = :idvac', array(':idvac' => $inVacId));


        if( $inData )
        {
            foreach ($inData as $key => $val)
            {
                $res = Yii::app()->db->createCommand()
                    ->insert('empl_locations', $val);
            } // end foreach
        } // endif
    }



    private function saveUserLang($inVacId)
    {
        $id = $inVacId;

        $langs = Yii::app()->getRequest()->getParam('langs');
        $langLvls = Yii::app()->getRequest()->getParam('lang-level');

        if( count($langs) )
        {
            foreach ($langs as $key => $val)
            {
                $insData[] = array('id_attr' => $val, 'id_vac' => $id, 'type' => '2', 'val' => $langLvls[$val], 'crdate' => date('Y-m-d H:i:s'));
            } // end foreach

            $sql = "DELETE empl_attribs FROM empl_attribs 
                INNER JOIN user_attr_dict d ON d.id = 40
                INNER JOIN user_attr_dict d1 ON empl_attribs.id_attr = d1.id AND d1.id_par = d.id
                WHERE id_vac = {$inVacId}";
            Yii::app()->db->createCommand($sql)->execute();

            $command = Yii::app()->db->schema->commandBuilder->createMultipleInsertCommand('empl_attribs', $insData);
            $res = $command->execute();
        } // endif
    }

    public function createVac(){
        $vac=0;

         $sql = "SELECT *
            FROM empl_vacations e 
            ORDER BY id DESC LIMIT 1";
        $res = Yii::app()->db->createCommand($sql)->queryScalar();
        $vac=$res+1;


         $res = Yii::app()->db->createCommand()
                ->insert('empl_vacations', array('id' => $vac, 
                    'id_empl'=>342,
                    'title'=>'Новая вакансия',
                    'ispremium'=>0,
                    'requirements'=>'Требования',
                    'duties'=>'Обязанности',
                    'conditions'=>'Условия',
                    'remdate'=>date("Y-m-d H:i:s"),
                    'istemp'=>'1',
                    'shour'=>'100',
                    'isman'=>'1',
                    'iswoman'=>'1',
                    'ismed'=>'1',
                    'isavto'=>'1',
                    'agefrom'=>'18',
                    'ageto'=>'22',
                    'status'=>'0',
                    'ismoder'=>'200',
                    'crdate'=> date("Y-m-d H:i:s"),
                    'city'=>'Москва',

                    ));
                $vac++;
    }

 
    private function saveCities($inVacId)
    {
        $idcity = $_POST['idcity'];
        unset($_POST['id_city'][0]);
        $bdate = date('Y-m-d', strtotime(Yii::app()->getRequest()->getParam('cibdate')));//date("Y-m-d");
        $edate = date('Y-m-d', strtotime(Yii::app()->getRequest()->getParam('ciedate')));//date("Y-m-d");

         Yii::app()->db->createCommand()->delete('empl_city', 'id_vac=:idvac', array(':idvac' => $inVacId));
       foreach ($idcity as $key => $val)
       {
             $res = Yii::app()->db->createCommand()
                        ->insert('empl_city', array(
                            'id_vac' => $inVacId,
                            'id_city' => $val,
                            'bdate' => $bdate,
                            'edate' => $edate,
                        ));

            $id_city = $val;
        } // endif

        return "1307";

    }


    private function saveVacPosts($inVacId)
    {
        $id = Share::$UserProfile->exInfo->id;

        $posts = Yii::app()->getRequest()->getParam('posts');
        $postSelf = trim(filter_var(Yii::app()->getRequest()->getParam('post-self'), FILTER_SANITIZE_FULL_SPECIAL_CHARS));

        $insData = array();
        foreach ($posts as $key => $val)
        {
            // prepare posts
            if( $val != 'aa' )
            {
                $insData[] = array('id_vac' => $inVacId, 'id_attr' => $val, 'key' => $val);


            // prepare custom post
            } 

        } // end foreach

        if($postSelf){
                // search for same post
                $res = Yii::app()->db->createCommand()
                    ->select('MAX(m.id) max, d.id , d.name')
                    ->from('user_attr_dict m')
                    ->leftJoin('user_attr_dict d', "d.name LIKE :name", array(':name' => $postSelf));
//                    ->where(array('and', 'grp = APPT', 'val = :post'), array(':post' => $cudo['name']))
                $res = $res->queryRow();


                if( $res['id'] ) $mId = $res['id'];
                // ins new post
                else
                {
                    //запись своего варианта должности
                    $res = Yii::app()->db->createCommand()
                        ->insert('user_attr_dict', array(
                            'id_par' => '110',
                            'key' => $res['max'] + 1,
                            'ptype' => 3,
                            'name' => ucfirst($postSelf),
                            'postself' => 1,
                        ));

                    if( $res )
                    {
                        $mId = Yii::app()->db->createCommand('SELECT LAST_INSERT_ID()')->queryScalar();
                    }
                    else { $mId = 0; } // endif
                } // endif

                if( $mId ) $insData[] = array('id_vac' => $inVacId, 'id_attr' => $mId, 'key' => $mId);
            } // endif


        $sql = "DELETE empl_attribs FROM empl_attribs 
INNER JOIN user_attr_dict d ON d.id = 110
INNER JOIN user_attr_dict d1 ON empl_attribs.id_attr = d1.id AND d1.id_par = d.id
WHERE id_vac = {$inVacId}";
        Yii::app()->db->createCommand($sql)->execute();
        $command = Yii::app()->db->schema->commandBuilder->createMultipleInsertCommand('empl_attribs', $insData);
        $command->execute();
        return $posts;
    }



    /**
     * ПРоверка на возможность отклика
     * @param $inData
     * @return array
     */
    private function chkResponse($inData)
    {
        $idvac = $inData['idvac'];
        $inData = $inData['vacdata'];
        $idus = $inData['idus'];

        // считываем характеристики соискателя вакансии
        $sql = "SELECT a.id_attr
              , a.val
              , d.name
              , d.type
              , d.id_par idpar
              , d.key
            FROM empl_vacations e
            LEFT JOIN empl_attribs a ON e.id = a.id_vac
            LEFT JOIN user_attr_dict d ON a.id_attr = d.id
            WHERE e.id = {$idvac}
            ORDER BY a.id_attr";
        $res = Yii::app()->db->createCommand($sql)->queryAll();

        foreach ($res as $key => $val)
        {
            $attr[$val['id_attr']] = $val;
        } // end foreach
//        $data['vacAttribs'] = $attr;


    if(!empty($idus)){

        $sql = "SELECT r.status
            FROM user r
            WHERE r.id_user = {$idus}";
        $retRa= Yii::app()->db->createCommand($sql)->queryScalar();
        if($retRa == 2){
        
        $data = array_merge(['vacations' => $data, 'pageCount' => $pages->pageCount]);
           $sql = "SELECT r.birthday, r.id
              , r.id_user, r.isman , r.ismed , r.ishasavto , r.aboutme , r.firstname , r.lastname , r.photo
              , a.val , a.id_attr
              , d.name , d.type , d.id_par idpar , d.key, d.postself
              , u.email
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
            'bday' => $val['birthday'],
            'id' => $val['id'],
            'id_user' => $val['id_user'],
            'isman' => $val['isman'],
            'ismed' => $val['ismed'],
            'ishasavto' => $val['ishasavto'],
            'aboutme' => $val['aboutme'],
            'firstname' => $val['firstname'],
            'lastname' => $val['lastname'],
            'photo' => $val['photo'],
            'email' => $val['email'],
        ];
        } 
    }

//        $Profile = $inData['profile'] ?: Share::$UserProfile;
        $Profile = $this->Profile ?: Share::$UserProfile;
        if( $retRa == 2 ?: $Profile->exInfo->status == 2 )
        {
            $user = Yii::app()->db->createCommand()
            ->select("e.isman")
            ->from('resume e')
            ->join('user usr', 'usr.id_user=e.id_user')
            ->where('e.id_user=:id_user', array(':id_user' => Share::$UserProfile->id))
            ->queryAll();



            $flagAppr = 1;
            $birthday = $dat['bday'] ?: $Profile->exInfo->birthday;
            $isman = $user[0]['isman'];
            $idPromo = $dat['id']?: $Profile->exInfo->id_resume;

            // проверка на уже поданную заявку
            $res = Yii::app()->db->createCommand()
                ->select("s.id, s.status, s.isresponse")
                ->from('vacation_stat s')
                ->where("id_vac = :id_vacation AND id_promo = :idPromo AND isresponse = 1", array(":idPromo" => $idPromo, ":id_vacation" => $inData['id']));
            $res = $res->queryRow();
            $status = $res['status'];

            if( $res['id'] )
            {
                $flagAppr = 0;
                if( $res['status'] == 0 || $res['status'] == 1 || $res['status'] == 4 ) $message = "Вы уже подали заявку на данную вакансию";
            } // endif

            // закрытая вакансия
            if( $inData['status'] < 1 )
            {
                $flagAppr = 0;
            } // endif

            $fisman  = $inData['isman'];
            $fiswoman = $inData['iswoman'];
            if( $inData['isman'] != $inData['iswoman'] )
            {
                if(($inData['isman'] == 0 && $isman == 1 && $inData['iswoman'] == 1) ) { $flagAppr = 0; $message = "Вы не подходите на даную ваканию по параметру “Пол соискателя женский”"; }
                elseif(($inData['isman'] == 1 && $isman == 0 && $inData['iswoman'] == 0) ) { $flagAppr = 0; $message = "Вы не подходите на даную ваканию по параметру “Пол соискателя  мужской”"; }
            } // endif

            $diff = (time() - strtotime($birthday)) / 365 / 86400;
            if( $inData['agefrom'] > 0 && $inData['agefrom'] > $diff ) { $flagAppr = 0; $message = "Вы не подходите на даную ваканию по параметру “Возраст соискателя”"; }
            elseif( $inData['ageto'] > 0 && $inData['ageto'] < $diff ) { $flagAppr = 0; $message = "Вы не подходите на даную ваканию по параметру “Возраст соискателя”"; }
            // $flagAppr = 1; $message = "Ведутся технические работы на сервере";
        }

        // если работодатель
        elseif( $retRa == 3 ?: $Profile->exInfo->status == 3 )
        {
            $flagAppr = 0;
        } // endif

        return array('response' => $flagAppr, 'message' => $message, 'status' => $status, 'vacAttribs' => $attr );
    }



    private function preDefinedData()
    {
        $preDefCities = [1307, 1838, 2463, 1973, 1449];

        // получаем города
        $sql = "SELECT ci.id_city idcity, name FROM city ci WHERE ci.id_city IN (".join(',', $preDefCities).")";
        /** @var $res CDbCommand */
        $res = Yii::app()->db->createCommand($sql);
        $res = $res->queryAll();
        foreach ($res as $key => $val)
        {
            $data['vac']['city'][$val['idcity']] = $val['name'];
        } // end foreach

        // получаем метро
        $sql = "SELECT m.id, m.id_city idcity, m.name FROM metro m WHERE m.id_city IN (".join(',', $preDefCities).") ORDER BY m.name";
        /** @var $res CDbCommand */
        $res = Yii::app()->db->createCommand($sql);
        $res = $res->queryAll();

        if( $res )
        {
            $data['metro'] = array();
            foreach ($res as $key => $val)
            {
                $data['metro'][$val['idcity']][1][$val['id']] = $val;
                if( !$data['metro'][$val['idcity']][0] ) $data['metro'][$val['idcity']][0] = $data['vac']['city'][$val['idcity']];
//                if( $inVacData['vac']['metroes'][$val['id']] ) $data['metro'][$val['idcity']][1][$val['id']]['selected'] = 1;
            } // end foreach
        } // endif
//$data['metro'][1307][1][35]['id']
        return $data;
    }

    private function getDigest($inId, $title){

        $vacancy = $inId;
       
        $sql = "SELECT e.id, e.ispremium, e.status, e.title, e.requirements, e.duties, e.conditions, e.istemp,
                   DATE_FORMAT(e.remdate, '%d.%m.%Y') remdate,e.agefrom, e.ageto,
                   e.shour,
                   e.sweek,
                   e.smonth,
                   e.isman,
                   e.smart,
                   e.iswoman,
                   e.repost,
                   DATE_FORMAT(e.crdate, '%d.%m.%Y') crdate
              , c1.id_city, c2.name AS ciname, c1.citycu
              , ea.id_attr
              , d.name AS pname, d.postself
              , ifnull(em.logo, '') logo
            FROM empl_vacations e 
            LEFT JOIN empl_city c1 ON c1.id_vac = e.id 
            LEFT JOIN city c2 ON c2.id_city = c1.id_city 
            JOIN empl_attribs ea ON ea.id_vac = e.id
            JOIN user_attr_dict d ON (d.id = ea.id_attr) AND (d.id_par = 110)
            JOIN employer em ON em.id_user = e.id_user 
            JOIN user u ON em.id_user = u.id_user AND u.ismoder = 1
            WHERE e.id= {$inId}
            ORDER BY e.ispremium DESC, e.id DESC";
            $rest = Yii::app()->db->createCommand($sql);
            $rest = $rest->queryAll();


        $id_attrs = "";
        for($i = 0; $i < count($rest); $i ++)
        {
              file_put_contents('/home/prommudev/log.txt', date('d.m.Y H:i')."\t".$rest[$i]['id_attr']."\t\n", FILE_APPEND | LOCK_EX);
            $res = Yii::app()->db->createCommand()
                ->select('d.id , d.type, d.key, d.id_par')
                ->from('user_attr_dict d')
                ->where('d.id = :id', array(':id' => $rest[$i]['id_attr']))
                ->queryRow();
            

             if($res['id_par'] == 110) {
                 $keys[] = "'" . $rest[$i]['id_attr'] . "'";
             } 
       
        }

        $id_city = " ";
         for($i = 0; $i < count($rest); $i ++)
        {
             $keysCity[] = "'" . $rest[$i]['id_city'] . "'";
            

       } // endif
         $keys = join(',', $keys);
         $keysCity = join(',', $keysCity);
         $sql = "SELECT u.email, r.firstname, r.lastname, c.name
            FROM resume r
            INNER JOIN user_mech m ON r.id_user = m.id_us 
            INNER JOIN user_city ci ON r.id_user = ci.id_user AND ci.id_city IN({$keysCity})
            INNER JOIN city c ON c.id_city = ci.id_city
            INNER JOIN user u ON r.id_user = u.id_user 
            WHERE m.isshow = 0 AND m.id_mech IN ({$keys})
            LIMIT 1000";
        $result = Yii::app()->db->createCommand($sql)->queryAll();

        if(!empty($result)) {
         $content = file_get_contents(Yii::app()->basePath . "/views/mails/app/create-vac.html");
         $content = str_replace('#APPNAME#', $result[$i]['firstname']." ".$result[$i]['lastname'], $content);
         $content = str_replace('#EMPCOMPANY#', Share::$UserProfile->exInfo->name, $content);
         $content = str_replace('#EMPLINK#', MainConfig::$SITE . MainConfig::$PAGE_PROFILE_COMMON . DS . Share::$UserProfile->id, $content);
         $content = str_replace('#VACID#', $inId, $content);
         $content = str_replace('#VACNAME#', $title, $content);
         $content = str_replace('#APPCITY#', $rest['ciname'][0], $content);
         $content = str_replace('#VACLINK#',  'https://' . MainConfig::$SITE . MainConfig::$PAGE_VACANCY . DS . $vacancy, $content);
      
        for($i = 0; $i < count($result); $i++){
           Share::sendmail($result[$i]['email'], "Prommu.com Размещение вакансии №" . $inId, $message);
       
        }

         }

        

    }

    // сохраняем атрибуты соискателя вакансии
    private function saveVacAttribs($inId)
    {
        $id = $inId;

        $attrs = Yii::app()->getRequest()->getParam('user-attribs');

        $insData = array();

        foreach ($attrs as $key => $val)
        {
            $keys[] = "'" . $key . "'";
            $res = Yii::app()->db->createCommand()
                ->select('d.id , d.type, d.key, d.postself')
                ->from('user_attr_dict d')
                ->where('d.key = :key', array(':key' => $key))
                ->queryRow();

            // свой вариант оплаты надо писать и значение
            if( $key == 'paylims' && $val == 164 )
            {
                $insData[] = array('id_vac' => $id, 'id_attr' => $val, 'key' => $res['key'], 'val' => trim(Yii::app()->getRequest()->getParam('paylimit')), 'crdate' => date('Y-m-d H:i:s'));

            // атрибут с id
            } elseif ($res['type'] == 3 )
            {
                $insData[] = array('id_vac' => $id, 'id_attr' => $val, 'key' => $res['key'], 'crdate' => date('Y-m-d H:i:s'));

            // атрибут со значением
            } else {
                $insData[] = array('id_vac' => $id, 'id_attr' => $res['id'], 'key' => $res['key'], 'val' => trim($val), 'crdate' => date('Y-m-d H:i:s'));
            } // endif
        } // end foreach

            $keys = join(',', $keys);
            $sql = "DELETE empl_attribs FROM empl_attribs 
                INNER JOIN user_attr_dict d ON empl_attribs.id_attr = d.id AND d.key IN({$keys})
                WHERE id_vac = {$id}";
            Yii::app()->db->createCommand($sql)->execute();
            $sql = "DELETE empl_attribs FROM empl_attribs 
                INNER JOIN user_attr_dict d ON d.key IN({$keys})
                INNER JOIN user_attr_dict d1 ON empl_attribs.id_attr = d1.id AND d1.id_par = d.id
                WHERE id_vac = {$id}";
            Yii::app()->db->createCommand($sql)->execute();

        if( count($insData) )
        {
            $command = Yii::app()->db->schema->commandBuilder->createMultipleInsertCommand('empl_attribs', $insData);
            $command->execute();
        } // endif
    }



    /**
     * @param $inIdVac - id вакансии
     * @param $idus - пользватель создавший вакансию
     * @param $inStatus - статус отклика
     */
    private function getTabsData($inIdVac, $idus, $inStatus)
    {
        $isAllowedPromo = Share::$UserProfile->exInfo->status == 2 && in_array($inStatus, [4, 5, 6, 7]);
        if( $inIdVac && $idus == Share::$UserProfile->id || $isAllowedPromo)
        {
            // получаем кол-во откликнувшихся для закладок
            $ResponsesEmpl = new ResponsesEmpl();
            $counts = $ResponsesEmpl->getVacancyResponsesCounts($inIdVac);
            $data['counts'] = $counts['counts'];

            // получаем кол-во сообщений
            $VacDiscuss = new VacDiscuss();
            $counts = $VacDiscuss->getDiscussCount($inIdVac);
            $data['countsDiscuss'] = $counts;

            if( $isAllowedPromo ) $tab = 'dialog';
            else $tab = Yii::app()->getRequest()->getParam('info', 'resp');

            // Получаем диалог
            if( $tab == 'dialog' )
            {
                $pages=new CPagination($data['countsDiscuss']);
                $pages->pageSize = MainConfig::$DEF_PAGE_LIMIT;
                $pages->applyLimit($VacDiscuss);
                $data['pages'] = $pages;

                return array_merge(array('discuss' => $VacDiscuss->getDiscuss($inIdVac)), $data);

            // получаем отклики
            } else {
                switch ($tab) {
                   case 'refuse':
                   case 'reject':   $status = array(3);
                                    $index = 3;
                                break;
                   case 'aside':    $status = array(1);
                                    $index = 1;
                                break;
                   case 'approv':
                   case 'resp':     $status = [0,2,4,5,6,7];
                                    $index = 4;
                                break;
                }

                $pages=new CPagination($data['counts'][$index]);
                $pages->pageSize = MainConfig::$DEF_PAGE_LIMIT;
                $pages->applyLimit($ResponsesEmpl);
                $data['pages'] = $pages;

                return array_merge($ResponsesEmpl->getVacancyResponses($inIdVac, $status), $data);
            } // endif
        }
        else
        {
            return array();
        } // endif
    }


    /**
     * Получаем вакансии
     * @return CDbCommand
     */
    public function getVacanciesQueries($inParams)
    {
        /**
         * Выделяем все выборки вакансий в один класс, для того, чтобы если изменится условия выборки - можно было быстро везде поменять
         */


        // Получаем работодателей для поиска
        if( $inParams['page'] == 'searchvac' ) return $this->getVacancySearchemplPage($inParams);


        if( $inParams['page'] == 'invite' )
        {
            $params = (['filter' => ['filter' => 'WHERE e.remdate >= now() AND e.status = 1 '], 'offset' => '0', 'limit' => '0']);
            return $this->getVacancySearchemplPage($params);
        }


        // Получаем работодателей для главной
        if( $inParams['page'] == 'index' ) return $this->getVacanciesIndexPage();
    }



    /**
     * Получаем должности системы
     * @return array
     */
    public function getPost()
    {
        $sql = "SELECT d.id, d.type, d.comment, d.name, d.postself FROM user_attr_dict d WHERE d.id_par = 110 ORDER BY npp, name";
        $res = Yii::app()->db->createCommand($sql)->queryAll();

        return $res;
    }

     public function getPosts()
    {   
        $searchWord = filter_var(Yii::app()->getRequest()->getParam('search'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $sql = "SELECT d.id, d.type, d.comment, d.name, d.postself
            FROM (
                  SELECT d.id, d.type, d.comment, d.name, d.postself FROM user_attr_dict d
                    WHERE d.id_par = 110
                    AND d.name LIKE '{$searchWord}%' 
                  ORDER BY npp, name
                ) d
                ORDER BY name
            ";
        $res = Yii::app()->db->createCommand($sql)->queryAll();
        $obj = (object)[];
            foreach ($res as $key => &$val)
            {
                $obj->name = $val['name'];
                $obj->code = $val['comment'];
                $val = clone $obj;
            } // end foreach
       
        return $res;


    }


    public function getVacancySearchemplPage($inParams)
    {
        $filter = $inParams['filter'];
        $limit = (int)$inParams['limit'] > 0 ? "LIMIT {$inParams['offset']}, {$inParams['limit']}" : '';
        $sql = "SELECT e.id, e.ispremium, e.title, e.requirements, e.duties, e.conditions, e.istemp,
                   DATE_FORMAT(e.remdate, '%d.%m.%Y') remdate,
                   e.shour,
                   e.sweek,
                   e.smonth,
                   e.svisit,
                   e.isman,
                   e.smart,
                   e.iswoman,
                   DATE_FORMAT(e.crdate, '%d.%m.%Y') crdate
                   
              , c1.id_city, c2.name AS ciname, c1.citycu
              , ea.id_attr
              , d.name AS pname
              , em.id_user uid, em.name coname, ifnull(em.logo, '') logo
            FROM empl_vacations e 
            INNER JOIN (
              SELECT DISTINCT e.id
              FROM empl_vacations e
              INNER JOIN empl_city c ON c.id_vac = e.id 
              INNER JOIN user u ON e.id_user = u.id_user 
              INNER JOIN empl_attribs ea ON ea.id_vac = e.id
              {$filter['table']}
              {$filter['filter']}  AND e.status = 1 AND e.ismoder = 100 
              ORDER BY e.ispremium DESC, e.id DESC 
              {$limit}  
            ) t1 ON t1.id = e.id
            
            LEFT JOIN empl_city c1 ON c1.id_vac = e.id 
            LEFT JOIN city c2 ON c2.id_city = c1.id_city 
            JOIN empl_attribs ea ON ea.id_vac = e.id
            JOIN user_attr_dict d ON (d.id = ea.id_attr) AND (d.id_par = 110)
            JOIN employer em ON em.id_user = e.id_user
            -- WHERE e.status = 1
            -- AND e.ismoder = 100
            ORDER BY e.ispremium DESC, e.id DESC
            LIMIT 100";
        $res = Yii::app()->db->createCommand($sql);
        $data= $res->queryAll();
        foreach ($data as $key => $value) {
            if(time() > strtotime($value['remdate'])){

                $res = Yii::app()->db->createCommand()
                    ->update('empl_vacations', array( 'status' => 0,
                        'ismoder' => 0, 
                    ), 'id = :id', array(':id' => $value['id']));
            }
        }
        

        return $data;
    }

     public function getVacAdmin()
    {
        $sql = "SELECT e.id, e.ispremium, e.istemp,
              DATE_FORMAT(e.remdate, '%d.%m.%Y') remdate,
              e.shour,
              e.title,
              e.sweek,
              e.smonth,
              e.isman,
              e.iswoman,
              DATE_FORMAT(e.crdate, '%d.%m.%Y') crdate,
              c1.id_city, c2.name AS ciname, c1.citycu,
              ea.id_attr,
              d.name AS pname,
              em.name coname,
              ifnull(em.logo, '') logo
            FROM empl_vacations e 
            INNER JOIN (
              SELECT DISTINCT e.id
              FROM empl_vacations e
              INNER JOIN empl_city c ON c.id_vac = e.id  
              INNER JOIN empl_attribs ea ON ea.id_vac = e.id 
              INNER JOIN user u ON e.id_user = u.id_user
              WHERE e.status = 1
                AND e.ismoder = 0 AND e.crdate >= CURDATE() 
              ORDER BY e.ispremium DESC, e.id DESC 
            ) t1 ON t1.id = e.id
            
            LEFT JOIN empl_city c1 ON c1.id_vac = e.id 
            LEFT JOIN city c2 ON c2.id_city = c1.id_city 
            JOIN empl_attribs ea ON ea.id_vac = e.id
            JOIN user_attr_dict d ON (d.id = ea.id_attr) AND (d.id_par = 110)
            JOIN employer em ON em.id_user = e.id_user
            ORDER BY e.ispremium DESC, e.id DESC
            LIMIT 1000";
        $res = Yii::app()->db->createCommand($sql);
        $data= $res->queryAll();
        return $data;
    }

    private function getVacanciesIndexPage()
    {
        $strCities = Subdomain::getCitiesIdies();
        $sql = "SELECT e.id, e.ispremium, e.istemp,
              DATE_FORMAT(e.remdate, '%d.%m.%Y') remdate,
              e.shour,
              e.sweek,
              e.smonth,
              e.isman,
              e.iswoman,
              DATE_FORMAT(e.crdate, '%d.%m.%Y') crdate,
              c1.id_city, c2.name AS ciname, c1.citycu,
              ea.id_attr,
              d.name AS pname,
              em.name coname,
              ifnull(em.logo, '') logo
            FROM empl_vacations e 
            INNER JOIN (
              SELECT DISTINCT e.id
              FROM empl_vacations e
              INNER JOIN empl_city c ON c.id_vac = e.id
                AND c.id_city IN({$strCities})
              INNER JOIN empl_attribs ea ON ea.id_vac = e.id
              INNER JOIN user u ON e.id_user = u.id_user
              WHERE e.status = 1
                AND e.ismoder = 100
              ORDER BY e.ispremium DESC, e.id DESC 
              LIMIT 12
            ) t1 ON t1.id = e.id
            
            LEFT JOIN empl_city c1 ON c1.id_vac = e.id 
            LEFT JOIN city c2 ON c2.id_city = c1.id_city
            JOIN empl_attribs ea ON ea.id_vac = e.id
            JOIN user_attr_dict d ON (d.id = ea.id_attr) AND (d.id_par = 110)
            JOIN employer em ON em.id_user = e.id_user
            ORDER BY e.ispremium DESC, e.id DESC";
        $res = Yii::app()->db->createCommand($sql);
        $data= $res->queryAll();

        foreach ($data as $key => &$vac) {
            if(time() > strtotime($vac['remdate'])){
                unset($data[$key]);
            }
            $vac['detail_url'] = MainConfig::$PAGE_VACANCY . DS . $vac['id'];
            if($vac['shour']>0 || $vac['sweek']>0 || $vac['smonth']>0){
              if($vac['shour'] > 0){
                $vac['payment'] = round($vac['shour'],0) . ' руб/час';
              }
              elseif($vac['sweek'] > 0){
                $vac['payment'] = round($vac['sweek'],0) . ' руб/нед';
              }
              elseif($vac['smonth'] > 0){
                $vac['payment'] = round($vac['smonth'],0) . ' руб/мес';
              }
            }
            else{
              $vac['payment'] = '';
            }
            $vac['logo_src'] = DS . MainConfig::$PATH_EMPL_LOGO . DS . (!$vac['logo'] ?  'logo-min.png' : ($vac['logo']) . '100.jpg');
            $vac['period'] = ' с ' . $vac['crdate'] . ($vac['remdate']?' по '.$vac['remdate']:'');
            $vac['work_type'] = ($vac['istemp'] ? 'Постоянная' : 'Временная');
        }
        unset($vac);


        return $data;
    }

    public function VkRepost($id, $repost){

        $result = $this->getVacancyInfo($id);
        $id_user = $result[0]['id_user'];
        if($result[0]['isman'] && !$result[0]['iswoman']) {
                $male = "Юноши";
            }elseif($result[0]['iswoman'] && !$result[0]['isman']){
                $male = "Девушки";
            }
            else 
                $male = "Юноши, девушки";

        if($result[0]['ageto'] == 0) {

           $age = "От ".$result[0]['agefrom']; 

        } else 
            $age = "От ".$result[0]['agefrom']." до ".$result[0]['ageto'];

        switch ($result[0]['pname']) {
            case 'Промоутер':
                $attachments = "photo-151205900_456239032";
                $photo_fb = "https://www.facebook.com/prommucom/photos/a.911915242298934.1073741828.911896865634105/911926268964498/?type=3&theater";
                break;
            case 'Консультант':
               $attachments = "photo-151205900_456239028";
               $photo_fb = "https://www.facebook.com/prommucom/photos/a.911915242298934.1073741828.911896865634105/911925525631239/?type=3&theater";
                break;
            case 'Модель':
               $attachments = "photo-151205900_456239029";
               $photo_fb = "https://www.facebook.com/prommucom/photos/a.911915242298934.1073741828.911896865634105/911925772297881/?type=3&theater";
                break;
            case 'Супервайзер':
                $attachments = "photo-151205900_456239030";
                $photo_fb = "https://www.facebook.com/prommucom/photos/a.911915242298934.1073741828.911896865634105/911925975631194/?type=3&theater";
                break;
            case 'Тайный покупатель':
               $attachments = "photo-151205900_456239027";
               $photo_fb = "https://www.facebook.com/prommucom/photos/a.911915242298934.1073741828.911896865634105/911926132297845/?type=3&theater";
                break;
            case 'Мерчендайзер':
                $attachments = "photo-151205900_456239056";
                $photo_fb = "";
                break;
            case 'Хостес':
               $attachments = "photo-151205900_456239034";
               $photo_fb = "https://www.facebook.com/prommucom/photos/a.911915242298934.1073741828.911896865634105/911926582297800/?type=3&theater";
                break;
            case 'Ростовая кукла':
              $attachments = "photo-151205900_456239033";
              $photo_fb = "https://www.facebook.com/prommucom/photos/a.911915242298934.1073741828.911896865634105/911926372297821/?type=3&theater";
                break;
            case 'Интервьюер':
              $attachments = "photo-151205900_456239055";
              $photo_fb = "";
                break;
            case 'Аниматор':
              $attachments = "photo-151205900_456239057";
              $photo_fb = "";
                break;


            default:
               $attachments = "";
                break;
        }
        if($result[0]['shour'] == "0.00") {
            return "error: shour = 0";
        }
        else  $coast = $result[0]['shour']." руб/час";;
        // elseif($result[0]['sweek'] != 0) {
        //     $coast = $result[0]['shour']." руб/неделю";
        // }
        // elseif($result[0]['smonth'] != 0) {
        //     $coast = $result[0]['smonth']." руб/месяц";
        // }

        $title = $result[0]['title'];

        $id_attr = $result[0]['id_attr'];
        $dia = $result[0]['pname'];
        $conditions = $result[0]['conditions'];
        $requirements = $result[0]['requirements'];
        $duties = $result[0]['duties'];
        $idvac = $result[0]['id'];
        $linki = "https://prommu.com/vacancy/$idvac";
        $post = $result[0]['pname'];
        $city = $result[0]['ciname'];

        $message =
               "🔥 Требуется: $post 🔥
               
                Город: $city
                
                Пол: $male

                Возраст: $age

                Оплата: $coast

                Сроки оплаты: после окончания проекта

                Требования: 
                • $requirements

                Условия: 
                • $conditions

                Обязанности: 
                • $duties

                👇ОТКЛИКНУТЬСЯ НА ВАКАНСИЮ 👇 
                Cсылка: $linki";

            $token = "283f11bf157c1c9d30cc8ac2a7d0bbce526500ad79cd4df2c2b9c39c708459f848a675e669d628ef9acab";
            $group = "-8777665";
            $St = 'https://api.vk.com/method/wall.post';

        if(substr($repost, 0,1)=='1' && empty($result[0]['vk_link'])) {
            $curl = curl_init();
            curl_setopt($curl,CURLOPT_URL,$St);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // защищенный режим с помощью cUrl-a
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false); // защищенный режим с помощью cUrl-a
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, array('access_token'=>$token, 'owner_id'=>$group, 'attachments'=>$attachments, 'message'=>$message, 'from_group'=>1, 'v' => 'V'));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
            $stream = curl_exec($curl);
            $data = json_decode($stream, true);
            curl_close($curl);

            $res = Yii::app()->db->createCommand()
                            ->update('empl_vacations', array(
                                 'vk_link' => "https://vk.com/wall-8777665_".$data['response']['post_id']
                            ), 'id = :id', array(':id' => $id));
                            
            Yii::app()->db->createCommand()
                        ->insert('service_cloud', array('id_user' => $id_user,
                                'name' => $id,
                                'type' => "repost", 
                                'bdate' => date("Y-m-d h-i-s"),
                                'edate' => date("Y-m-d h-i-s"),
                                'status' => 1,
                                'sum' => 0,
                                'text' => "vk",
                                'user' => "vk"
                            ));
        } 

        if(substr($repost, 2,1)=='1' && empty($result[0]['tl_link'])) {
            $message =
               "Опубликована вакансия $title\n\n🔥Требуется: $post\n\n 🔥Город: $city\n\n  👥Пол: $male\n\n 👫Возраст: $age\n\n 💰Оплата: $coast \n\n⏰Сроки оплаты: после окончания проекта\n\n👔Требования: • $requirements\n\n📝Условия: • $conditions\n\n💼Обязанности: • $duties\n\n👇ОТКЛИКНУТЬСЯ НА ВАКАНСИЮ 👇\n\n👌Cсылка: $linki";

            $sendto ="https://api.telegram.org/bot525649107:AAFWUj7O8t6V-GGt3ldzP3QBEuZOzOz-ij8/sendMessage?parse_mode=HTML&chat_id=@prommucom&text=".urlencode($message)."&disable_web_page_preview=true";
            file_get_contents($sendto);
            $res = Yii::app()->db->createCommand()
                            ->update('empl_vacations', array(
                                 'tl_link' => "https://t.me/prommucom",
                            ), 'id = :id', array(':id' => $id));
                 Yii::app()->db->createCommand()
                        ->insert('service_cloud', array('id_user' => $id_user,
                                'name' => $id,
                                'type' => "repost", 
                                'bdate' => date("Y-m-d h-i-s"),
                                'edate' => date("Y-m-d h-i-s"),
                                'status' => 1,
                                'sum' => 0,
                                'text' => "telegram",
                                'user' => "telegram"
                            ));

        } 

        if(substr($repost, 1,1)=='1' && empty($result[0]['fb_link'])){
            $graph_url= "https://graph.facebook.com/911896865634105/feed/";
              $postData = "&message=$message&link=$photo_fb&access_token=EAACEdEose0cBAMuyoA7ZBo2nV8Wb16bSh7V3QAKhNFCSXTLcIH4YOU8xY2SZBOMeTPG495G2VuzYhQMnvqq7eMpK5sAyGyX5V9P2cV8CwPs7rittgbGnZAZC4GVA9gGvF6trbS2AaZCGeZBbQCsvuzgLMxEGTuNfnpY4g2wd6W3NPOskCJLZA1ZAGjwkHFlqc92EXGyeqi7PGwZDZD";

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $graph_url);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

            $output = curl_exec($ch);
            $output = json_decode($output, true);
             $res = Yii::app()->db->createCommand()
                                ->update('empl_vacations', array(
                                     'fb_link' => "https://www.facebook.com/prommucom/".$output['id'],
                                ), 'id = :id', array(':id' => $id));
            
            Yii::app()->db->createCommand()
                        ->insert('service_cloud', array('id_user' => $id_user,
                                'name' => $id,
                                'type' => "repost", 
                                'bdate' => date("Y-m-d h-i-s"),
                                'edate' => date("Y-m-d h-i-s"),
                                'status' => 1,
                                'sum' => 0,
                                'text' => "telegram",
                                'user' => "fb"
                            ));
                            
            
            curl_close($ch);
        }
        // записываем результат репоста
        $res = Yii::app()->db->createCommand()
            ->update('empl_vacations', array(
                'repost' => $repost,
            ), 'id = :id', array(':id' => $id));
            
              
                            
    }
    public function getSeсtionsVacCount()
    {
        $idus = $this->Profile->id ?: Share::$UserProfile->exInfo->id;

        $sql = "SELECT COUNT(*) cou 
            FROM empl_vacations v 
            WHERE v.id_user = {$idus} AND (v.status = 1)";
        $vacCnt = Yii::app()->db->createCommand($sql)->queryScalar();

        $sql = "SELECT COUNT(*) cou 
            FROM empl_vacations v 
            LEFT JOIN vacation_stat vs ON vs.id_vac = v.id
            WHERE v.id_user = {$idus} AND (v.status = 0 OR vs.status in (6,7))";
        $arcCnt = Yii::app()->db->createCommand($sql)->queryScalar();

        $data['cnt'] = array('vac'=>$vacCnt, 'arc'=>$arcCnt);

        return $data;
    }

    /**
     * Удалить вакансию
     */
    public function vacDelete($inProps=[])
    {
        $id = filter_var(Yii::app()->getRequest()->getParam('id'), FILTER_SANITIZE_NUMBER_INT);
        $message = 'Вакансию №' . $id . ' не удалось удалить';
        $error = -100;
        try
        {
            $Q1 = Yii::app()->db->createCommand()
                ->select("id, title, ismoder")
                ->from('empl_vacations v')
                ->where('v.id = :id AND v.id_user = :idus', array(':id' => $id, ':idus' => Share::$UserProfile->id))
                ->queryRow();

            if( !$Q1['id'] ) throw new Exception('', -101);

            Yii::app()->db->createCommand()->delete('empl_vacations', 'id=:id', array(':id' => $id));
            Yii::app()->db->createCommand()->delete('empl_attribs', 'id_vac=:id', array(':id' => $id));
            Yii::app()->db->createCommand()->delete('empl_city', 'id_vac=:id', array(':id' => $id));
            Yii::app()->db->createCommand()->delete('vacation_stat', 'id_vac=:id', array(':id' => $id));

            $Q1 = Yii::app()->db->createCommand()
                ->select('l.id')
                ->from('empl_locations l')
                ->where('l.id_vac = :idvac', array(':idvac'=>$id));
            $arLoc = $Q1->queryAll();

            if(!empty($arLoc)){
                foreach($arLoc as $v) 
                    $arLocId[] = $v['id'];

                Yii::app()->db->createCommand()->delete('empl_locations', 'id_vac=:id', array(':id' => $id));
                Yii::app()->db->createCommand()->delete('emplv_loc_times', array('in', 'id_loc', $arLocId));
            }

            $message = 'Вакансия №' . $id . ' успешно удалена';
            $error = 0;
        }
        catch (Exception $e) {
            $message = $e->getMessage();
            $error = $e->getCode();
        } // endtry

        return array('error' => $error, 'id' => $id, 'message' => $message);
    }

    /*
    *   Публикация в соцсети со страниц "Мои вакансии" и страницы редактирования вакансии(промодерированой)
    */
    public function vacPostToSocial(){
        $soc = filter_var(Yii::app()->getRequest()->getParam('soc'), FILTER_SANITIZE_NUMBER_INT);// код соцсети
        $id = filter_var(Yii::app()->getRequest()->getParam('id'), FILTER_SANITIZE_NUMBER_INT); // id вакансии
        $arRes = array('error' => 1, 'message' => 'Вакансия не найдена');

        $query = Yii::app()->db->createCommand()
            ->select("id, ismoder, repost")
            ->from('empl_vacations v')
            ->where('v.id = :id AND v.id_user = :idus', array(':id' => $id, ':idus' => Share::$UserProfile->id))
            ->queryRow();

        if($query['id']){
            if($query['ismoder']==100){
                if(($soc==1 && substr($query['repost'], 0,1)=='1') || ($soc==2 && substr($query['repost'], 1,1)=='1') || ($soc==3 && substr($query['repost'], 2,1)=='1')) // vk || fb || tl
                {
                    $arRes['message'] = 'Вакансия отправлена на публикацию и в скором времени появится в соцсетях';
                }
                else{
                    $query['repost'] = $soc==1 ? substr_replace($query['repost'], '1', 0, 1) : $query['repost']; // VK
                    $query['repost'] = $soc==2 ? substr_replace($query['repost'], '1', 1, 1) : $query['repost']; // FB
                    $query['repost'] = $soc==3 ? substr_replace($query['repost'], '1', 2, 1) : $query['repost']; // NTKTGRAM
                    $this->VkRepost($id, $query['repost']); 
                    $arRes = array('error' => 0, 'message' => 'Вакансия успешно опубликована в соцсетях');                    
                }
            }
            else{
                $arRes['message'] = 'Вакансия сейчас проходит модерацию. Попробуйте позже';
            }
        }

        return $arRes;
    }
    /*
    *   Публикация в соцсети со страницы услуги "Группы социальных сетей PROMMU"
    */
    public function postToSocialService(){
        $idus = $this->Profile->id ?: Share::$UserProfile->exInfo->id;

        $limit = $this->limit > 0 ? "LIMIT {$this->offset}, {$this->limit}" : '';

        $sql = "SELECT v.id, v.title, v.repost
            FROM empl_vacations v
            INNER JOIN ( SELECT v.id FROM empl_vacations v WHERE v.id_user = {$idus} ORDER BY v.id DESC 
                {$limit} ) t1 ON t1.id = v.id 
            WHERE v.id_user = {$idus} AND (v.status=1) AND (v.ismoder=100)
            ORDER BY v.id DESC
            ";
        $data = Yii::app()->db->createCommand($sql)->queryAll();
        $arRes = array();
        foreach ($data as $v) $arRes[$v['id']] = $v;

        if(Yii::app()->getRequest()->isPostRequest){
            $arSoc = Yii::app()->getRequest()->getParam('soc');
            $order = new PrommuOrder;
            $arId = array();
            foreach($arSoc as $idvac => $soc)
                $arId[] = $idvac;

            $price = $order->servicePrice($arId,'publication-vacancy-social-net');
            if($price>0)
                return array('price' => $price, 'arReposts' => $arSoc);

            foreach($arSoc as $idvac => $soc){

                if(isset($arRes[$idvac])){
					$arRes[$idvac]['repost'] = isset($soc['vk']) ? substr_replace($arRes[$idvac]['repost'], '1', 0, 1) : $arRes[$idvac]['repost'];	
					$arRes[$idvac]['repost'] = isset($soc['fb']) ? substr_replace($arRes[$idvac]['repost'], '1', 1, 1) : $arRes[$idvac]['repost'];
					$arRes[$idvac]['repost'] = isset($soc['tl']) ? substr_replace($arRes[$idvac]['repost'], '1', 2, 1) : $arRes[$idvac]['repost'];

					$this->VkRepost($idvac, $arRes[$idvac]['repost']);
                }
                else{
                    return array('error'=>1, 'mess'=>'Вакансия не найдена. Попробуйте еще раз');
                }
            }

            Yii::app()->user->setFlash('success', array('event'=>'social'));
            return array('error'=>0);
        }
        else{
            return array('vacs'=>$arRes);
        }
    }
    /*
    *   Получить список промодерированных вакансий
    */
    public function getModerVacs(){
        $idus = $this->Profile->id ?: Share::$UserProfile->exInfo->id;

        $limit = $this->limit > 0 ? "LIMIT {$this->offset}, {$this->limit}" : '';

        $sql = "SELECT v.id, v.title, v.repost
            FROM empl_vacations v
            INNER JOIN ( SELECT v.id FROM empl_vacations v WHERE v.id_user = {$idus} ORDER BY v.id DESC 
                {$limit} ) t1 ON t1.id = v.id 
            WHERE v.id_user = {$idus} AND (v.status=1) AND (v.ismoder=100)
            ORDER BY v.id DESC
            ";
        $data = Yii::app()->db->createCommand($sql)->queryAll();
        $arRes = array();
        foreach ($data as $v) $arRes[$v['id']] = $v;

        return array('vacs'=>$arRes);
    }
}