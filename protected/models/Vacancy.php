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
    /** @var array варианты опыта */
    const EXPERIENCE = [
            1 => 'Можно без опыта',
            2 => 'До 1 месяца', 
            3 => 'От 1 до 3 месяцев', 
            4 => 'От 3 до 6 месяцев', 
            5 => 'От 6 до 12 месяцев', 
            6 => 'от 1 до 2-х лет', 
            7 => 'Более 2-х лет'
        ];
    const WORK_TYPE = [0 => 'Временная', 1 => 'Постоянная'];
    const SELF_EMPLOYED = [0 => 'Физическое лицо', 1 => 'Самозанятый'];
    const SALARY_TYPE = [
      0 => 'руб / час',
      1 => 'руб / неделя',
      2 => 'руб / месяц',
      3 => 'руб / посещение'
    ];
    public $company_search;
    public $responses;
    // empl_vacations.status
    static public $STATUS_ACTIVE = 1;
    static public $STATUS_NO_ACTIVE = 0;
    // empl_vacations.ismoder
    static public $ISMODER_NEW = 0;
    static public $ISMODER_APPROVED = 100;
    static public $ISMODER_REJECTED = 200;
    // empl_vacations.in_archive
    static public $INARCHIVE_FALSE = 0;
    static public $INARCHIVE_TRUE = 1;
    // кол-во вакансий на главной странице
    static private $VACANCIES_IN_MAIN_PAGE = 12;
    // ID атрибута - должности
    const ID_POSTS_ATTRIBUTE = 110;
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

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function rules()
    {
        return array(
            array('id, id_user, title, remdate, crdate, city, status, ismoder, company_search, in_archive', 'required'),
            array('id, id_user', 'numerical', 'integerOnly'=>true),
            array('id, id_user, title, remdate, crdate, city, status, ismoder, company_search','safe','on'=>'search')
        );
    }

    function relations()
    {
        return array(
                'employer'=>array(self::BELONGS_TO,'Employer','id_empl')
            );
    }

    public function searchvac()
    {
        $condition = [];
        $GetVac = Yii::app()->getRequest()->getParam('Vacancy');
        
        $criteria = new CDbCriteria;
        $criteria->with = array('employer');

        $criteria->compare('employer.name',$this->company_search, true);
        $criteria->compare('t.id', $this->id, true);
        $criteria->compare('t.id_user', $this->id_user, true);
        $criteria->compare('t.crdate', $this->crdate, true);
        $criteria->compare('t.mdate', $this->mdate, true);
        $criteria->compare('t.remdate', $this->remdate, true);
        $criteria->compare('t.title', $this->title, true);
        $criteria->compare('t.status', $this->status, true);
        $criteria->compare('t.in_archive', $this->in_archive, true);
        if(strlen($GetVac['ismoder']))
        {
            $condition[] = 't.ismoder='.$GetVac['ismoder'];
        }
        Share::setDateQuery('t','crdate','b_crdate','e_crdate',$condition);
        Share::setDateQuery('t','mdate','b_mdate','e_mdate',$condition);
        Share::setDateQuery('t','remdate','b_remdate','e_remdate',$condition,false);

        if(count($condition))
        {
            $criteria->condition = implode(' and ', $condition);
        }

        return new CActiveDataProvider('Vacancy', array(
            'criteria'=>$criteria,
            'pagination' => array('pageSize' => 10),
            'sort' => [
                'defaultOrder'=>'t.mdate desc',
                'attributes'=>array(
                    'company_search'=>array(
                        'asc'=>'employer.name',
                        'desc'=>'employer.name DESC',
                    ),
                    '*',
                )
            ],
        ));
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
        $db = Yii::app()->db;
        // выключаем премиум
        $query = $db->createCommand()
                            ->select("id, edate, name id_vacancy")
                            ->from('service_cloud')
                            ->where("type='vacancy' AND status=1")
                            ->queryAll();

        if(count($query))
        {
            $arIdVac = $arIdService = array();
            foreach ($query as $v)
            {
                if((time() - strtotime($v['edate'])) > 1000 )
                {
                    $arIdVac[] = $v['id_vacancy'];
                    $arIdService[] = $v['id'];
                }
            }
            if(count($arIdVac)) // убираем статус в таблице вакансий
            {
                $db->createCommand()->update('empl_vacations',['ispremium'=>0],['in','id',$arIdVac]);
            }
            if(count($arIdService)) // и в таблице услуг
            {
                $db->createCommand()->update('service_cloud',['status'=>0],['in','id',$arIdService]);
            }
        }

        $query = $db->createCommand() // ищем подходящие по статусу вакансии
                            ->select("vs.id,
                                vs.status,
                                vs.mdate,
                                vs.id_vac,
                                ev.title,
                                ev.remdate,
                                ev.id_user id_emp,
                                r.id_user id_app")
                            ->from('vacation_stat vs')
                            ->leftjoin('empl_vacations ev','ev.id=vs.id_vac')
                            ->leftjoin('resume r','r.id=vs.id_promo')
                            ->where(
                                'vs.status=:status AND ev.remdate=:date',
                                [
                                    ':status' => Responses::$STATUS_APPLICANT_ACCEPT,
                                    ':date' => date("Y-m-d", strtotime("yesterday"))
                                ]
                            )
                            ->queryAll();

        if(count($query))
        {
            $arId = $arT = array();
            foreach ($query as $v)
            {
                !empty($v['id_emp']) && $arId[] = $v['id_emp'];
                !empty($v['id_app']) && $arId[] = $v['id_app'];
                $arT[$v['id_vac']]['items'][] = $v;
            }
            $arUsers = Share::getUsers($arId);
            $arId = array();

            foreach ($arT as $id_vac => $arV)
            {
                $arItem = reset($arV['items']);
                $arLink = $arApp = array();
                $message = "Завершение вакансии №" . $id_vac . " “" . $arItem['title'] 
                    . "” сегодня.<br>Просим оценить ваше сотрудничество с ";
                $title = 'Завершение проекта';
                $linkRate = Subdomain::site() . MainConfig::$PAGE_SETRATE . DS . $id_vac;
                foreach ($arV['items'] as $v)
                {
                    // письмо соискателю
                    Mailing::set(13,
                        [
                            'email_user' => $arUsers[$v['id_app']]['email'],
                            'id_vacancy' => $id_vac,
                            'title_vacancy' => $v['title'],
                            'id_company' => $v['id_emp'],
                            'name_company' => $arUsers[$v['id_emp']]['name']
                        ]
                    );
                    $arId[] = $v['id'];
                    $name = $arUsers[$v['id_app']]['name'];
                    empty($name) && $name = 'Пользователь';
                    $arLink[] = '<a href="'. $linkRate . DS . $v['id_app'] . '">' . $name . '</a>';
                    Im::setMessageFromBot( // сообщение в чат
                            $v['id_app'],
                            $arUsers[$v['id_app']]['status'],
                            $title,
                            $message . 'работодателем <a href="' . $linkRate . '">' . $arUsers[$v['id_emp']]['name'] . '</a>'
                        );
                    $arApp[] = $v['id_app'];
                }
                // письмо работодателю
                Mailing::set(14,
                    array(
                        'email_user' => $arUsers[$arItem['id_emp']]['email'],
                        'id_vacancy' => $id_vac,
                        'title_vacancy' => $arItem['title'],
                        'links_list' => implode('<br>',$arLink)
                    )
                );

                Im::setMessageFromBot( // сообщение в чат
                        $arItem['id_emp'],
                        $arUsers[$arItem['id_emp']]['status'],
                        $title,
                        $message . 'соискателями: <br>' . implode('<br>',$arLink)
                    );
              // добавляем уведомления с предложением выставить рейтинг для С
              if(count($arApp))
              {
                UserNotifications::setDataByVac($arApp,$id_vac,UserNotifications::$APP_SET_RATING);
              }
              // добавляем уведомления с предложением выставить рейтинг для Р
              UserNotifications::setDataByVac(
                $arItem['id_emp'],
                $id_vac,
                UserNotifications::$EMP_SET_RATING,
                $arApp
              );
            }
            if(count($arId))
            {
              // фиксируем в истории
              ResponsesHistory::setDataAfterVacEnd($query);
              // делаем заявки завершенными
              $db->createCommand()->update(
                    'vacation_stat',
                    [
                      'status' => Responses::$STATUS_BEFORE_RATING,
                      'mdate' => date('Y-m-d H:i:s')
                    ],
                    ['in','id',$arId]
                  );
            }
        }
        // делаем вакансии неактивными
        //
        // !!!
        //
        $arYandexVacs = [64,65,66,67,68,69,73,1191,1193,1196,1204,1205,1259,1324,1394,1418,1441,1515,1529,1530,1532,1621,1656,1694,1719,1723,1730,1733,1740,1741,1759,1788,1801,1804,1805,1825,1905,1912,1930,1931,1933,1935,1937,2037,2052,2055,2069,2075,2077,2086,2094,2095,2113,2121,2132,2140,2148,2149,2150,2151,2155,2157,2159,2163,2171,2174,2175,2178,2188,2189,2190,2191,2192,2202,2206,2214,2217,2219,2220,2224,2225,2240,2244,2246,2255,2257,2261,2263,2268,2274,2275,2281,2283,2290,2292,2294,2298,2299,2300,2301,2303,2305,2319,2321,2324,2327,2334,2335,2341,2342,2343,2345,2347,2348,2349,2350,2352,2353,2369,2373,2374,2375,2376,2377,2395,2389,2390,2391,2401];
        $db->createCommand()->update(
          'empl_vacations',
          ['status' => 0],
          [
            'and',
            'status=1 and remdate=:date',
            ['not in','id',$arYandexVacs]
          ],
          [':date'=>date("Y-m-d", strtotime("yesterday"))]
        );
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
            , Subdomain::getSiteName() . MainConfig::$PAGE_VACANCY . DS . $val['id']
            , $val['title']
            , Subdomain::getSiteName() . MainConfig::$PAGE_VACANCY_EDIT . DS . $val['id'] . '?bl=3'
            , Subdomain::getSiteName() . MainConfig::$PAGE_SET_SITE_RATE . DS . $val['id']
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

        $copyId = filter_var(
                        Yii::app()->getRequest()->getParam('copy_id'),
                        FILTER_SANITIZE_NUMBER_INT
                    );

        /*$arArchive = $this->getEmpVacanciesIdList(Share::$UserProfile->id)['archive'];
        if(in_array($copyId, $arArchive))
        {*/
        if($copyId>0)
        {
            $data['copy_vacacancy'] = $this->getVacancyView($copyId);
            $data['copy_vacacancy']['vac']['post'] = '';

            $user = Share::$UserProfile->exInfo;
            $user->name = trim($user->name);
            empty($user->name) && $user->name = 'пользователь';

            Yii::app()->user
                ->setFlash('prommu_flash',
                    "<div class='big-flash'>
                        <p>Уважаемый «" . $user->name . "»!</p>
                        <p>Вы только что продублировали вакансию на сервис Prommu.</p> 
                        <p>После закрытия этого информационного окна, Вам нужно  
                        <span style='color:#ff921d;'>дополнить</span> поле 'должность' 
                        и указать срок актуальности вакансии.</p> 
                        <p>Просмотреть и отредактировать данную вакансию Вы можете в любой момент 
                        времени в личном кабинете - категория 
                        <span style='color:#ff921d;'>«МОИ ВАКАНСИИ»</span>.</p> 
                        <p>Быстрого и лёгкого поиска Вам персонала.</p>
                        <i>С найлучшими пожеланиями команда Промму!</i>
                    </div>");
        }
        //}

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
            AND v.status=1 AND v.in_archive=0
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
            AND v.status = 1 AND v.in_archive=0
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
        $sql = "SELECT COUNT(*) cou FROM empl_vacations v WHERE v.id_user = {$idus} AND v.status=1 AND v.in_archive=0";
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
    /**
     * @param $inId integer - emp_vacations => id
     * @return mixed
     * Метод для сайта (не для API)
     */
    public function getVacancyInfoOrig($inId)
    {
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
                     e.self_employed,
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
      }

      return $res;
    }


    public function getVacancyInfo($inId){

        if( $inId )
        {
        $sql = "SELECT e.id, e.ispremium, e.sort, e.title, e.requirements, e.duties, e.conditions, e.istemp,
                   DATE_FORMAT(e.remdate, '%d.%m.%Y') remdate,
                   e.shour,
                   e.sweek,
                   e.smonth,
                   e.svisit,
                   e.isman,
                   e.agefrom,
                   e.ageto,
                   e.self_employed,
                   e.smart,
                   e.card,
                   e.cardPrommu,
                   e.ismed,
                   e.isavto,
                   e.iswoman,
                   e.repost,
                   DATE_FORMAT(e.crdate, '%d.%m.%Y') crdate
                   
              , c1.id_city, c2.name AS ciname, c1.citycu
              , ea.id_attr
              , d.name AS pname
              , em.id_user uid, em.name coname, ifnull(em.logo, '') logo
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
            
        foreach ($res as $key => $val)
        {
            if( !isset($data)) $data= array('city' => array(), 'posts' => array()) ;
            
            
            ///attribs
            $data['id'] = (int)$val['id'];
            $data['title'] = $val['title'];
            
             ///owner
            $data['owner']['id'] = (int)$val['uid'];
            $data['owner']['name'] = $val['coname'];
            $data['owner']['logo'] = "https://files.prommu.com/users/".$val['uid']."/".$val['logo'].".jpg";
            ///
            
            ///city
          
            $data['city'][0]['id'] = (int)$val['id_city'];
            $data['city'][0]['name'] = $val['id_city'] > 0 ? $val['ciname'] : $val['citycu'];
            ///
            
            ///posts
            $data['posts'][0]['id'] = (int)$val['id_attr'];
            $data['posts'][0]['name'] = $val['pname'];
            ///
            
            $data['created_at'] = $val['crdate'];
            $data['removed_at'] = $val['remdate'];
                
           
            $data['is_man'] = (boolean)$val['isman'];
            $data['is_woman'] = (boolean)$val['iswoman'];
            
           
            $data['age_from'] = (int)$val['agefrom'];
            $data['age_to'] = (int)$val['ageto'];
            
            $data['is_premium'] = (boolean)$val['ispremium'];
            $data['sort'] = (boolean)$val['sort'];
            $data['is_active'] = true;
            $data['is_med'] = (boolean)$val['ismed'];
            $data['is_hasavto'] = (boolean)$val['isavto'];
            $data['is_temp'] =  (boolean)$val['istemp'];
            $data['smart'] = (boolean)$val['smart'];
            $data['card'] = (boolean)$val['card'];
            $data['card_prommu'] = (boolean)$val['cardPrommu'];
            $data['self_employed'] = (boolean)$val['self_employed'];
            
            $data['repost_vk'] = false;
            $data['repost_fb'] = false;
            $data['repost_tl'] = false;
                
            if($val['repost'] == '111'){
                $data['repost_vk'] = true;
                $data['repost_fb'] = true;
                $data['repost_tl'] = true;
                
            } elseif($val['repost'] == '110'){
                $data['repost_vk'] = true;
                $data['repost_fb'] = true;
                $data['repost_tl'] = false;
            }elseif($val['repost'] == '011'){
                $data['repost_vk'] = false;
                $data['repost_fb'] = true;
                $data['repost_tl'] = true;
            }elseif($val['repost'] == '101'){
                $data['repost_vk'] = true;
                $data['repost_fb'] = false;
                $data['repost_tl'] = true;
            }
            
            
            $data['requirements'] = $val['requirements'];
            $data['conditions'] = $val['conditions'];
            $data['duties'] = $val['duties'];
            
            
            $data['salary_hour'] = (float)$val['shour'];
            $data['salary_week'] = (float)$val['sweek'];
            $data['salary_month'] = (float)$val['smonth'];
            $data['salary_visit'] = (float)$val['svisit'];
            
        
        } // end foreach

        
            return $data;
        }

        return $res;

    }
    
    public function getVacancyOwner($inId){

        if( $inId )
        {
        $sql = "SELECT e.id, e.ispremium, e.sort, e.title, e.requirements, e.duties, e.conditions, e.istemp,
                   DATE_FORMAT(e.remdate, '%d.%m.%Y') remdate,
                   e.shour,
                   e.ismoder,
                   e.sweek,
                   e.smonth,
                   e.svisit,
                   e.isman,
                   e.smart,
                   e.card,
                   e.cardPrommu,
                   e.ismed,
                   e.isavto,
                   e.iswoman,
                   e.repost,
                   e.agefrom,
                   e.ageto,
                   DATE_FORMAT(e.crdate, '%d.%m.%Y') crdate
                   
              , c1.id_city, c2.name AS ciname, c1.citycu
              , ea.id_attr
              , d.name AS pname
              , em.id_user uid, em.name coname, ifnull(em.logo, '') logo
            FROM empl_vacations e 
            LEFT JOIN empl_city c1 ON c1.id_vac = e.id 
            LEFT JOIN city c2 ON c2.id_city = c1.id_city 
            JOIN empl_attribs ea ON ea.id_vac = e.id
            JOIN user_attr_dict d ON (d.id = ea.id_attr) AND (d.id_par = 110)
            JOIN employer em ON em.id_user = e.id_user
            JOIN user u ON u.id_user = em.id_user
            WHERE e.id_user= {$inId}
            ORDER BY e.ispremium DESC, e.id DESC";
            $res = Yii::app()->db->createCommand($sql);
            $res = $res->queryAll();
            
             // AND (d.id_par = 110)
       
        foreach ($res as $key => $val)
        {
            $idvac = $val['id'];
            $sql = "SELECT 
                a.id_attr
              , d.name
              , a.val
              , a.key
            FROM empl_vacations e
            LEFT JOIN empl_attribs a ON e.id = a.id_vac
            LEFT JOIN user_attr_dict d ON a.id_attr = d.id
            WHERE e.id = {$idvac}
            ORDER BY a.id_attr";
            $attribs = Yii::app()->db->createCommand($sql)->queryAll();
            
           
            foreach ($attribs as $keys => $vals)
            {   
            
                if($vals['val'] == null){
                    $vals['val'] = $vals['name'];
                }
                unset($vals['name']);
                
                $data[$val['id']]['attribs'][] = $vals;
            } 
        
            if( !isset($data[$val['id']])) $data[$val['id']] = array('city' => array(), 'posts' => array()) ;
            
            
            ///attribs
            $locat = $this->getLocations((int)$val['id']);
            
            foreach($locat as $key_loc => $val_loc){
                 $data[$val['id']]['locations'][] = $val_loc;
            }
           
            
            $data[$val['id']]['id'] = (int)$val['id'];
            $data[$val['id']]['title'] = $val['title'];
            
             ///owner
            $data[$val['id']]['owner']['id'] = (int)$val['uid'];
            $data[$val['id']]['owner']['name'] = $val['coname'];
            $data[$val['id']]['owner']['logo'] = "https://files.prommu.com/users/".$val['uid']."/".$val['logo'].".jpg";
            ///
            
            ///city
            $data[$val['id']]['city'][0]['id'] = (int)$val['id_city'];
            $data[$val['id']]['city'][0]['name'] = $val['id_city'] > 0 ? $val['ciname'] : $val['citycu'];
            ///
            
            ///posts
            $data[$val['id']]['posts'][0]['id'] = (int)$val['id_attr'];
            $data[$val['id']]['posts'][0]['name'] = $val['pname'];
            ///
            $data[$val['id']]['age_to'] = (int)$val['ageto'];
            $data[$val['id']]['age_from'] = (int)$val['agefrom'];
           
            
            $data[$val['id']]['repost_vk'] = false;
            $data[$val['id']]['repost_fb'] = false;
            $data[$val['id']]['repost_tl'] = false;
                
            if($val['repost'] == '111'){
                $data[$val['id']]['repost_vk'] = true;
                $data[$val['id']]['repost_fb'] = true;
                $data[$val['id']]['repost_tl'] = true;
                
            } elseif($val['repost'] == '110'){
                $data[$val['id']]['repost_vk'] = true;
                $data[$val['id']]['repost_fb'] = true;
                $data[$val['id']]['repost_tl'] = false;
            }elseif($val['repost'] == '011'){
                $data[$val['id']]['repost_vk'] = false;
                $data[$val['id']]['repost_fb'] = true;
                $data[$val['id']]['repost_tl'] = true;
            }elseif($val['repost'] == '101'){
                $data[$val['id']]['repost_vk'] = true;
                $data[$val['id']]['repost_fb'] = false;
                $data[$val['id']]['repost_tl'] = true;
            }
            
            $data[$val['id']]['created_at'] = $val['crdate'];
            $data[$val['id']]['removed_at'] = $val['remdate'];
            $data[$val['id']]['is_man'] = (boolean)$val['isman'];
            $data[$val['id']]['is_woman'] = (boolean)$val['iswoman'];
            $data[$val['id']]['is_premium'] = (boolean)$val['ispremium'];
            $data[$val['id']]['sort'] = (boolean)$val['sort'];
            $data[$val['id']]['is_active'] = true;
            $data[$val['id']]['is_moder'] = (boolean)$val['ismoder'];
            $data[$val['id']]['is_med'] = (boolean)$val['ismed'];
            $data[$val['id']]['is_hasavto'] = (boolean)$val['isavto'];
            $data[$val['id']]['is_temp'] =  (boolean)$val['istemp'];
            $data[$val['id']]['smart'] = (boolean)$val['smart'];
            $data[$val['id']]['card'] = (boolean)$val['card'];
            $data[$val['id']]['card_prommu'] = (boolean)$val['cardPrommu'];
            
            $data[$val['id']]['requirements'] = $val['requirements'];
            $data[$val['id']]['conditions'] = $val['conditions'];
            $data[$val['id']]['duties'] = $val['duties'];
            
            
            $data[$val['id']]['salary_hour'] = (float)$val['shour'];
            $data[$val['id']]['salary_week'] = (float)$val['sweek'];
            $data[$val['id']]['salary_month'] = (float)$val['smonth'];
            $data[$val['id']]['salary_visit'] = (float)$val['svisit'];
            
            //
          
            // if( $val['mid'] ) $data['vacs'][$val['id']]['metroes'][$val['mid']] = $val['mname'];
            // $data['vacs'][$val['id']] = array_merge($data['vacs'][$val['id']], $val);
        
        } // end foreach

        $i = 1;
        $ret['vacs'] = array();
        foreach ($data as $key => $val) { $ret['vacs'][] = $val; $i++; }

        return $ret;
            
        }
        

    }
    

    public function getVacancyData($inIdVac)
    {
        if( $inIdVac )
        {
            // получение данных вакансии
            $sql = "SELECT e.id,e.city, e.ismoder, e.ispremium, e.title, e.requirements, e.duties, e.conditions, e.istemp,e.cardPrommu, e.card, e.repost,e.index, e.meta_h1, e.meta_title, e.meta_description,e.comment, e.in_archive,
                       DATE_FORMAT(e.bdate, '%d.%m.%Y') bdate,
                       DATE_FORMAT(e.edate, '%d.%m.%Y') edate,
                       e.shour, e.sweek, e.smart, e.self_employed, e.smonth, e.svisit, e.isman, e.iswoman, e.exp, e.id_user idus, e.iscontshow,
                       DATE_FORMAT(e.remdate, '%d.%m.%Y') remdate,
                       e.ismed, e.isavto, e.contacts, e.agefrom, e.ageto, e.status, e.vk_link, e.fb_link, e.tl_link,
                       DATE_FORMAT(e.crdate, '%d.%m.%Y') crdate
                  , c1.id ecid, c1.id_city, c2.name AS ciname, c1.citycu, c2.ismetro, c2.region, c2.id_co
                  , DATE_FORMAT(c1.bdate, '%d.%m.%Y') cbdate
                  , DATE_FORMAT(c1.edate, '%d.%m.%Y') cedate 
                  , ea.id_attr
                  , d.name AS pname, d.postself
                  , m.id mid, m.name mname
                  , em.id eid, em.name coname, em.logo, em.ismoder user_moder
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
                $data['vac'][0]['city'][$val['id_city']] = array(
                        $val['id_city'] ? $val['ciname'] : $val['citycu'], 
                        $val['cbdate'], 
                        $val['cedate'], 
                        $val['ecid'], 
                        $val['id_city'], 
                        'region'=>$val['region'],
                        'id_co'=>$val['id_co']
                    );
                if( $val['ismetro'] ) $data['vac'][0]['hasmetro'][$val['id_city']] = 1;
                $data['vac'][0]['post'][$val['id_attr']] = $val['pname'];

                $tmp = $data['vac'][0]['city'];

                $data['vac'][0] = array_merge($data['vac'][0], $val);

                $data['vac'][0]['city'] = $tmp;

                $btime = $this->getTime($val['btime']);
                $etime = $this->getTime($val['etime']);

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
            // Ищем наличие сроков оплаты
            $hasPay = false;
            foreach($data['vacAttribs'] as $key => $item) {
                in_array($key, [130,132,133,134,163,164]) && $hasPay = true;
            }
            // Вакансию нельзя публиковать, если отсутствуют обязательные поля
            if(
                empty($data['vac']['exp']) || !$hasPay 
                || 
                (empty($data['vac']['shour']) && empty($data['vac']['sweek']) 
                    && empty($data['vac']['smonth']) && empty($data['vac']['svisit']))
            )
                $data['vac']['cannot-publish'] = true;

            // получаем данные для вкладок
            $data['vacResponses'] = $this->getTabsData($inIdVac, $data['vac']['idus'], $data['response']['status']);
            // чат по вакансии
            $sql = Yii::app()->db->createCommand()
                    ->select("r.id_user, c.id chat")
                    ->from('vacation_stat vs')
                    ->leftjoin('chat_theme ct','ct.id_vac=vs.id_vac')
                    ->leftjoin('chat c','c.id_theme=ct.id')
                    ->leftjoin('resume r','r.id=vs.id_promo')
                    ->where(
                        'vs.id_vac=:id AND vs.status>4',
                        array(':id'=>$data['vac']['id'])
                    )
                    ->queryAll();

            $arUId = $arChatId = array();
            foreach($sql as $v)
            {
                $arUId[] = $v['id_user'];
                $arChatId[] = $v['chat'];
            }
            $data['users_chat'] = Share::getUsers($arUId);
            $data['users_chat_cnt'] = count($arChatId);
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
            //
            // информация по счетчикам
            //
            $data['info'] = $this->getInfo($inIdVac);
        }
        else
        {
            $data = array('idvac' => 0);
        } // endif

        return $data;
    }

    public function updateVacancy($id, $data)
    {
        if(!Yii::app()->request->isAjaxRequest)
        {
            $data['ismoder'] = intval($data['ismoder']);
            $data['status'] = intval($data['status']);
            $data['ispremium'] = intval($data['ispremium']);
            $data['isman'] = intval($data['isman']);
            $data['iswoman'] = intval($data['iswoman']);
            $data['istemp'] = intval($data['istemp']);
            $data['ismed'] = intval($data['ismed']);
            $data['isavto'] = intval($data['isavto']);
            $data['smart'] = intval($data['smart']);
            $data['card'] = intval($data['card']);
            $data['cardPrommu'] = intval($data['cardPrommu']);
            $data['index'] = intval($data['index']);
            $data['in_archive'] = intval($data['in_archive']);
        }

        Yii::app()->db->createCommand()
            ->update('empl_vacations', $data, 'id=:id', [':id'=>$id]);

        if($data['ismoder'] != "100")
            return;

        // данные о вакансии и параметры фильтр
        $arData = $this->getFilterForVacancy($id);
        // создаем параметры для фильтра
        $host = Subdomain::site();
        $url = $host . MainConfig::$PAGE_SEARCH_PROMO . '?';
        $url .= http_build_query($arData['filter']);

        // ищем 10 соискателей, и сортируем, чтобы сначала вывести с фото
        $filter = ['filter' => $arData['filter']];
        $model = new SearchPromo();
        $arAllId = $model->searchPromosCount($filter);
        // подходящим юзерам отправляем сообщение в ЛК
        UserNotifications::setNewVacanciesNotifications($arAllId,$id);
        $pages = new CPagination(count($arAllId));
        $pages->pageSize = 20;
        $pages->applyLimit($model);
        $arApplicants = $model->getPromos($arAllId, true, $filter)['promo'];

        $appList = '';
        if(count($arApplicants)>=3)
        {
            $arRes = array();
            foreach ($arApplicants as $u)
            {
                $u['src'] = Share::getPhoto($u['id_user'],2,$u['photo'],'small',$u['isman']);
                $u['link'] = $host . MainConfig::$PAGE_PROFILE_COMMON . '/' . $u['id_user'];
                $u['name'] = trim($u['firstname'] . ' ' . $u['lastname']);
                $datetime = new DateTime($u['birthday']);
                $interval = $datetime->diff(new DateTime(date("Y-m-d")));
                $u['years'] = $interval->format("%Y");
                $u['years'] = $u['years'] . ' ' . Share::endingYears($u['years']);
                $path = Subdomain::domainRoot() . DS . MainConfig::$PATH_APPLIC_LOGO . DS . $u['photo'] . '100.jpg';
                file_exists($path) ? array_unshift($arRes, $u) : array_push($arRes, $u);
            }

            $count = sizeof($arRes)>=6 ? 6 : 3;
            $anketa = '<td style="padding:0 8px 25px;vertical-align:top">
                <div style="display:block;box-shadow:0px 8px 16px 0px rgba(0, 0, 0, 0.19);">
                    <img src="#ASRC" style="width:100%;display:block;object-fit:cover;height:230px">
                    <span style="display:block;padding:13px;font-family:Arial,Helvetica,sans-serif;font-size:16px">
                        <b style="display:inline-block;padding-bottom:15px;">#ANAME</b><br>
                        <span style="line-height:20px;padding-bottom:20px;display:inline-block">#ACITY<br>#AYEARS</span>
                        <a href="#ALINK" style="background-color:#fe820b;color:#ffffff;text-decoration:none;display:block;text-align:center;padding:12px 5px">Посмотреть анкету</a>
                    </span>
                </div>
            </td>';

            $appList = '<tr>';
            for ($i=0; $i<$count; $i++)
            {
                $e = $arRes[$i];
                $appList .= preg_replace(
                    ['/#ALINK/','/#ASRC/','/#ANAME/','/#ACITY/','/#AYEARS/'], 
                    [$e['link'],$e['src'],$e['name'],join(', ',$e['city']),$e['years']], 
                    $anketa
                );
                if(($i+1)==3)
                    $appList .= '</tr><tr>';
            }
            $appList .= '</tr>';
        }
        else
        {
            $url = $host . MainConfig::$PAGE_SEARCH_PROMO;
        }
        // письмо работодателю
        Mailing::set(10,
            [
                'email_user' => $arData['vacancy']['email'],
                'id_vacancy' => $id,
                'title_vacancy' => $arData['vacancy']['title'],
                'applicants_list' => $appList,
                'link_ankety_filter' => $url
            ]
        );
        // репостим
        $this->VkRepost($id, $arData['vacancy']['repost']);
        // событие ПУШ
        $res = Yii::app()->db->createCommand()
            ->select("push")
            ->from('user_push')
            ->where('id=:id',array(':id'=>$arData['vacancy']['id_user']))
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

    public function saveVacpubDataApi($inProps)
    {
        $idus = $inProps['idus'];
        $idvac = $inProps['id'];
       
        
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

            // bl1
            $fields['title'] = $inProps['title'];
            $fields['exp'] = $inProps['experience'];
            $fields['requirements'] = $inProps['requirements'];
            $fields['duties'] = $inProps['duties'];
            $fields['conditions'] = $inProps['conditions'];
            $fields['agefrom'] = $inProps['age_from'];
            $fields['ageto'] = $inProps['age_to'];
            $fields['isman'] = $inProps['is_man'];
            $fields['iswoman'] = $inProps['is_woman'];
            $fields['ismed'] = $inProps['is_med'];
            $fields['isavto'] = $inProps['is_hasavto'];
            $fields['smart'] = $inProps['smart'];
            $fields['self_employed'] = $inProps['self_employed'];
            $fields['card'] = $inProps['card'];
            $fields['cardPrommu'] = $inProps['card_prommu'];
            $fields['ismoder'] = 100;
            $fields['status'] = 1;
            
            
            $fields['shour'] = $inProps['salary_hour'];
            $fields['sweek'] = $inProps['salary_week'];
            $fields['smonth'] = $inProps['salary_month'];
            $fields['svisit'] = $inProps['salary_visit'];
            //bl3
            $dateWorkStart = $inProps['date_start'];
            $dateWorkEnd = $inProps['date_end'];
            
            $fields = array_merge($fields, array(
               'bdate' => date('Y-m-d', strtotime($dateWorkStart)),
               'edate' => date('Y-m-d', strtotime($dateWorkEnd)),
            ));
            
            $fields['istemp'] = $inProps['is_temp'];
            
     
            $vk = $inProps['repost_vk'];
            $fb = $inProps['repost_fb'];
            $tl = $inProps['repost_tl'];
            
            
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
                'id_empl' => $inProps['id_empl'],
                'crdate' => date("Y-m-d H:i:s"),
                'mdate' => date("Y-m-d H:i:s"),
            ));
            
            $fields['remdate'] = date('Y-m-d 23:59:59', strtotime($fields['edate']));
    
            if($inProps['id']){
                $res = Yii::app()->db->createCommand()
                ->update('empl_vacations', $fields
                    ,'id = :id', array(':id' => $inProps['id']) );
                    
                $idvac = $inProps['id'];
            } else {
                $res = Yii::app()->db->createCommand()
                ->insert('empl_vacations', $fields);
                
                $idvac = Yii::app()->db->createCommand('SELECT LAST_INSERT_ID()')->queryScalar();
            }
            

            $flagNew = 1;
            
//             // сохраняем должности
            $postt = $this->saveVacPostsApi($idvac,$inProps);
//             // сохраняем атрибуты вакансии
            $this->saveVacAttribs($idvac, $inProps['attribs']);
//             // сохраняем города
            $idcity = $this->saveCitiesApi($idvac, $inProps);
//             // сохраняем локации
            $this->saveLocationsApi($idvac, $inProps['locations']);


//             if(checkdate($arRemdate[1], $arRemdate[0], $arRemdate[2]))
//             {
//                 $fields['remdate'] = "{$arRemdate[2]}-{$arRemdate[1]}-{$arRemdate[0]}";
//             }

//             // сохраняем атрибуты вакансии
//             $this->saveVacAttribs($idvac);

//             if($resVac['title'] != $fields['title']){
//                 $arrs .= 'Название|';
//             }
//              if($resVac['ageto'] != $fields['ageto']){
//                 $arrs .= 'Возраст от|';
//             }
            
//             if($resVac['shour'] != $fields['shour']){
//                 $arrs .= 'Почасовая оплата|';
//             }
           

//             if($resVac['requirements'] != $fields['requirements']){
//                 $arrs .= 'Требования|';
//             }
//             if($resVac['duties'] != $fields['duties']){
//                 $arrs .= 'Обязанности|';
//             }
//             if($resVac['conditions'] != $fields['conditions']){
//                 $arrs .= 'Условия|';
//             }
//         }
//         else
//         {



//             // сохраняем языки
// //            $this->saveUserLang($idvac);
//         } // endif


//         // редактирование
//         if( $fields && $idvac )
//         {
//             unset($fields['id_user']);
//             $fields['mdate'] = date("Y-m-d H:i:s");
//             $fields['ismoder'] = 0;
//             $res = Yii::app()->db->createCommand()
//                 ->update('empl_vacations', $fields
//                     ,'id = :id', array(':id' => $idvac) );
//         if($arrs != ''){
//           $sql = "SELECT ru.email, r.firstname, r.lastname
//             FROM vacation_stat s
//             INNER JOIN empl_vacations e ON e.id = s.id_vac
//             INNER JOIN resume r ON s.id_promo = r.id
//             INNER JOIN user ru ON ru.id_user = r.id_user
//             WHERE s.status IN(5,6) AND e.id = {$idvac}";
//         /** @var $res CDbCommand */
//         $res = Yii::app()->db->createCommand($sql);
//         $rest = $res->queryAll();

//         if($rest!= ""){
//             for($i = 0; $i < count($rest); $i ++){
//                 $content = file_get_contents(Yii::app()->basePath . "/views/mails/app/emp-changing-vac.html");
//                   $content = str_replace('#APPNAME#', $rest[$i]['firstname'] . ' ' . $rest[$i]['lastname'], $content);
//                  $content = str_replace('#EMPCOMPANY#', Share::$UserProfile->exInfo->name, $content);
//                  $content = str_replace('#VACID#', $idvac, $content);
//                  $content = str_replace('#VACNAME#', $fields['title'], $content);
//                  $content = str_replace('#VACPARAMLIST#', $arrs, $content);
//                  $content = str_replace('#VACLINK#',  Subdomain::site() . MainConfig::$PAGE_VACANCY . DS . $idvac, $content);
//               if(strpos($rest[$i]['email'], "@") !== false)
//               Share::sendmail($rest[$i]['email'], "Prommu.com Изменение вакансии №" .$idvac, $content);
//             }

        
        

        //  $content = file_get_contents(Yii::app()->basePath . "/views/mails/app/emp-changing-vac.html");
        //           $content = str_replace('#APPNAME#', "администратор", $content);
        //          $content = str_replace('#EMPCOMPANY#', Share::$UserProfile->exInfo->name, $content);
        //          $content = str_replace('#VACID#', $idvac, $content);
        //          $content = str_replace('#VACNAME#', $fields['title'], $content);
        //          $content = str_replace('#VACPARAMLIST#', $arrs, $content);
        //          $content = str_replace('#VACLINK#', Subdomain::site() . '/admin/site/VacancyEdit'. DS .$idvac, $content);
               
        //  $email[0] = "susgresk@gmail.com";
        // $email[1] = "prommu.servis@gmail.com";
        // $email[2] = "denisgresk@gmail.com";
        // for($i = 0; $i <3; $i++){
        //   Share::sendmail($email[$i], "Prommu.com Изменение вакансии №" . $idvac, $content);
       
        // }


        
        return array('idvac' => $idvac);
    }
    
    private function saveVacPostsApi($inVacId, $data)
    {
        $id = $data['idus'];

        $posts = $data['posts'];
       
        $insData = array();
        foreach ($posts as $key => $val)
        {
            // prepare posts
            $insData[] = array('id_vac' => $inVacId, 'id_attr' => $val['id'], 'key' => $val['id']);

        } // end foreach

        $sql = "DELETE empl_attribs FROM empl_attribs 
                INNER JOIN user_attr_dict d ON d.id = 110
                INNER JOIN user_attr_dict d1 ON empl_attribs.id_attr = d1.id AND d1.id_par = d.id
                WHERE id_vac = {$inVacId}";
        Yii::app()->db->createCommand($sql)->execute();
        $command = Yii::app()->db->schema->commandBuilder->createMultipleInsertCommand('empl_attribs', $insData);
        $command->execute();
        return $posts;
    }
    
    private function saveCitiesApi($inVacId, $data)
    {
        
        $bdate = date('Y-m-d', strtotime($data['date_start']));//date("Y-m-d");
        $edate = date('Y-m-d', strtotime($data['date_end']));//date("Y-m-d");

         Yii::app()->db->createCommand()->delete('empl_city', 'id_vac=:idvac', array(':idvac' => $inVacId));
         
       foreach ($data['city'] as $key => $val)
       {
             $res = Yii::app()->db->createCommand()
                        ->insert('empl_city', array(
                            'id_vac' => $inVacId,
                            'id_city' => $val['id'],
                            'bdate' => $bdate,
                            'edate' => $edate,
                        ));

            $id_city = $val['id'];
        } // endif

        return "1307";

    }
    
    /**
     * Активировать вакансию
     */
    public function vacActivate($inProps=[])
    {   
        
        $id = $inProps['idvac']? $inProps['idvac']:filter_var(Yii::app()->getRequest()->getParam('id'), FILTER_SANITIZE_NUMBER_INT);
        $isDeactivate = $inProps['deactivate']?$inProps['deactivate']:filter_var(Yii::app()->getRequest()->getParam('d', 0), FILTER_SANITIZE_NUMBER_INT);
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
                'bdate' => $isDeactivate ? '0000-00-00 00:00:00' : date('Y-m-d H:i:s')
            );

            $title = $Q1['title'];
            $res = Yii::app()->db->createCommand()
                ->update('empl_vacations', $fields, 'id = :id', array(':id' => $id));


            if(!$isDeactivate)
            {
                $message = 'Вакансия снята с публикации';
            }
            else
            {
                $user = Share::$UserProfile->exInfo;
                if( (int)$Q1['ismoder'] == 0 )
                {
                    // Письмо пользователю 
                    $user->efio = trim($user->efio);
                    empty($user->efio) && $user->efio = 'пользователь';
                    Mailing::set(4,
                                [
                                    'email_user' => $user->email,
                                    'name_user' => $user->efio,
                                    'name_vacancy' => $Q1['title'],
                                    'id_vacancy' => $id,
                                ]
                            );
                } // endif

                // Письмо админу 
                $user->name = trim($user->name);
                empty($user->name) && $user->name = 'компания';
                Mailing::set(5,
                            [
                                'id_user' => $user->id,
                                'company_user' => $user->name,
                                'name_vacancy' => $Q1['title'],
                                'id_vacancy' => $id,
                            ]
                        );

                $message = 'Ваша вакансия активирована и отправлена на модерацию – и будет опубликована в ближайшее время. Обычно это занимает до 15 минут в рабочее время.';
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
    
    private function saveLocationsApi($inVacId, $location)
    {
        Yii::app()->db->createCommand()->delete('empl_locations', 'id_vac = :idvac', array(':idvac' => $inVacId));
        
        for($i = 0; $i < count($location); $i ++){
        
        if( $location[$i] )
        {
        //     // сохраняем локацию
            $res = Yii::app()->db->createCommand()
                ->insert('empl_locations', array(
                        'id_vac' => $inVacId,
                        'id_city' => $location[$i]['id_city'],
                        'npp' => 1,
                        'name' => $location[$i]['name'],
                        'addr' => $location[$i]['addr'],
                    ));
            $idloc = Yii::app()->db->createCommand('SELECT LAST_INSERT_ID()')->queryScalar();

            $lobtime = $location[$i]['periods'];
            $j = 1;
            $insData = array();
            foreach ($location[$i]['periods'] as $key => $val)
            {
               
                $btime = $val['btime'];
                $etime = $val['etime'];

                if( $btime || $etime )
                {
                    $arr = explode(':', $btime);
                    $btime = $arr[0] * 60 + $arr[1];
                    $arr = explode(':', $etime);
                    $etime = $arr[0] * 60 + $arr[1];

                } // endif


                $insData[] = array('id_loc' => $idloc, 'npp' => $j, 'bdate' => date("Y-m-d", $val['bdate']), 'edate' => date("Y-m-d", $val['edate']), 'btime' => $btime, 'etime' => $etime);

                $j++;
            } // end foreach
            // сохранение периодов локации
            $command = Yii::app()->db->schema->commandBuilder->createMultipleInsertCommand('emplv_loc_times', $insData);
            $command->execute();
            } // endif
        }
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
    /**
     * @param $id - integer ID вакансии
     * @param $arPostsId - array - массив ID должностей
     * @return bool
     */
    private function saveVacancyPosts($id, $arPostsId)
    {
      if(!count($arPostsId))
      {
        return false;
      }

      $arAllPostsId = array_keys(self::getPostsList());
      Yii::app()->db->createCommand()
        ->delete(
          'empl_attribs',
          ['and', 'id_vac=:id', ['in','id_attr',$arAllPostsId]],
          [':id'=>$id]
        );

      $arInsert = [];
      $objAttr = self::getAllAttributes()->items;
      foreach ($arPostsId as $v1)
      {
        $key = false;
        foreach ($objAttr as $v2)
        {
          $v2['id']==$v1 && $key=$v2['key'];
        }

        if(!in_array($v1,$arAllPostsId) || !$key)
        {
          continue;
        }
        $arInsert[] = [
          'id_vac'=>$id,
          'id_attr'=>$v1,
          'key'=>$key,
          'crdate'=>date('Y-m-d H:i:s')
        ];
      }
      if(count($arInsert))
      {
        Share::multipleInsert(['empl_attribs'=>$arInsert]);
      }
    }
    /**
     * @param $id - integer ID вакансии
     * @param $arAttributes - array - ассоциативный массив атрибутов [key => [id, key, name, value]]
     * @return bool
     */
    private function saveVacancyAttributes($id, $arAttributes)
    {
      if(!count($arAttributes))
      {
        return false;
      }

      $objAttr = self::getAllAttributes();

      Yii::app()->db->createCommand()
        ->delete(
          'empl_attribs',
          ['and', 'id_vac=:id', ['in','key',array_keys($arAttributes)]],
          [':id'=>$id]
        );

      $arInsert = [];
      foreach ($arAttributes as $key => $v)
      {
        $arr = [
          'id_vac'=>$id,
          'id_attr'=>$v,
          'key'=>$key,
          'crdate'=>date('Y-m-d H:i:s')
        ];
        if(!in_array($key,array_keys($objAttr->lists))) // свойство НЕ список
        {
          $arr['id_attr'] = $objAttr->items[$key]['id'];
          $arr['val'] = $v;
        }
        $arInsert[] = $arr;
      }
      if(count($arInsert))
      {
        Share::multipleInsert(['empl_attribs'=>$arInsert]);
      }
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
         $content = str_replace('#EMPLINK#', Subdomain::site() . MainConfig::$PAGE_PROFILE_COMMON . DS . Share::$UserProfile->id, $content);
         $content = str_replace('#VACID#', $inId, $content);
         $content = str_replace('#VACNAME#', $title, $content);
         $content = str_replace('#APPCITY#', $rest['ciname'][0], $content);
         $content = str_replace('#VACLINK#',  Subdomain::site() . MainConfig::$PAGE_VACANCY . DS . $vacancy, $content);
      
        for($i = 0; $i < count($result); $i++){
           Share::sendmail($result[$i]['email'], "Prommu.com Размещение вакансии №" . $inId, $message);
       
        }

         }

        

    }

    // сохраняем атрибуты соискателя вакансии
    private function saveVacAttribs($inId, $data)
    {
        $id = $inId;

        $insData = array();

        foreach ($data as $key => $val)
        {
          
            $keys[] = "'" . $val['key'] . "'";
            $res = Yii::app()->db->createCommand()
                ->select('d.id , d.type, d.key, d.postself')
                ->from('user_attr_dict d')
                ->where('d.key = :key', array(':key' => $val['key']))
                ->queryRow();

            // свой вариант оплаты надо писать и значение
            if( $val['key'] == 'paylims' && $val == 164 )
            {
                $insData[] = array('id_vac' => $id, 'id_attr' => $val['id_attr'], 'key' => $res['key'], 'val' => trim(Yii::app()->getRequest()->getParam('paylimit')), 'crdate' => date('Y-m-d H:i:s'));

            // атрибут с id
            } elseif ($res['type'] == 3 )
            {
                $insData[] = array('id_vac' => $id, 'id_attr' => $val['id_attr'], 'key' => $res['key'], 'crdate' => date('Y-m-d H:i:s'));

            // атрибут со значением
            } else {
                $insData[] = array('id_vac' => $id, 'id_attr' => $res['id'], 'key' => $res['key'], 'val' => trim($val['val']), 'crdate' => date('Y-m-d H:i:s'));
            } // endif
        } // end foreach

           
            $sql = "DELETE empl_attribs FROM empl_attribs WHERE id_vac = {$id}";
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
        
        if( $inParams['page'] == 'searchapi' ) return $this->getVacancySearchAPI($inParams);


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
    /**
     * @return array
     * поиск вакансий по поиску
     */
    public function getVacanciesSearch()
    {
      $arRes = [];
      $search = filter_var(
        Yii::app()->getRequest()->getParam('search'),
        FILTER_SANITIZE_FULL_SPECIAL_CHARS
      );
      $arCitiesId = Yii::app()->getRequest()->getParam('cities');
      if(count($arCitiesId)) // если выбран город(а) - фильтруем по выбраным городам
      {
        $arT = [];
        foreach ($arCitiesId as $v)
        { $arT[] = intval($v); }
        $arCitiesId = $arT;
      }
      if(!count($arCitiesId)) // иначе фильтруем
      {
        $arCitiesId = Subdomain::getCacheData()->arCitiesIdes;
      }

      if(empty(trim($search)))
      {
        return $arRes;
      }

      $arPosts = Yii::app()->db->createCommand()
        ->select('id, name, comment')
        ->from('user_attr_dict')
        ->where(
          "name LIKE :search and id_par=:id_post",
          [':search'=>$search.'%',':id_post'=>self::ID_POSTS_ATTRIBUTE]
        )
        ->order('npp desc, name desc')
        ->queryAll();

      if(!count($arPosts))
      {
        return $arRes;
      }

      $arId = [];
      foreach ($arPosts as $v)
      {
        $arId[] = $v['id'];
      }

      $query = Yii::app()->db->createCommand()
        ->select('DISTINCT(ea.id_attr)')
        ->from('empl_vacations ev')
        ->join('empl_attribs ea','ea.id_vac=ev.id')
        ->join('empl_city ec','ec.id_vac=ev.id')
        ->where([
          'and',
          'ev.status=:status',
          'ev.ismoder=:ismoder',
          'ev.in_archive=:archive',
          'ev.remdate>=CURDATE()',
          ['in','ea.id_attr',$arId],
          ['in','ec.id_city',$arCitiesId]
        ],
          [
            ':status' => self::$STATUS_ACTIVE,
            ':ismoder' => self::$ISMODER_APPROVED,
            ':archive' => self::$INARCHIVE_FALSE
          ])
        ->queryColumn();

      if(count($query))
      {
        foreach ($arPosts as $v)
        {
          if(in_array($v['id'],$query))
          {
            $arRes[] = ['name'=>$v['name'], 'code'=>$v['comment'], 'id'=>$v['id']];
          }
        }
      }

      return $arRes;
    }
    
    public function getVacancySearchAPI($inParams)
    {
         $filter = $inParams['filter'];
        $limit = (int)$inParams['limit'] > 0 ? "LIMIT {$inParams['offset']}, {$inParams['limit']}" : '';
       // var_dump($limit);
        $sql = "SELECT e.id, e.ispremium, e.title, e.requirements, e.duties, e.conditions, e.istemp,
                   DATE_FORMAT(e.remdate, '%d.%m.%Y') remdate,
                   e.shour,
                   e.sweek,
                   e.smonth,
                   e.svisit,
                   e.isman,
                   e.smart,
                   e.iswoman,
                   e.ismed,
                   e.isavto,
                   e.card,
                   e.smart,
                   e.cardPrommu,
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
              {$filter['filter']}  AND e.status = 1 AND e.ismoder = 100 AND e.remdate >= now()
              ORDER BY e.ispremium DESC, e.id DESC 
              {$limit}  
            ) t1 ON t1.id = e.id
            
            LEFT JOIN empl_city c1 ON c1.id_vac = e.id 
            LEFT JOIN city c2 ON c2.id_city = c1.id_city 
            JOIN empl_attribs ea ON ea.id_vac = e.id
            JOIN user_attr_dict d ON (d.id = ea.id_attr) AND (d.id_par = 110)
            JOIN employer em ON em.id_user = e.id_user
            ORDER BY e.ispremium DESC, e.mdate DESC
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

    public function getVacancySearchemplPage($inParams)
    {
         $filter = $inParams['filter'];
        $limit = (int)$inParams['limit'] > 0 ? "LIMIT {$inParams['offset']}, {$inParams['limit']}" : '';
       // var_dump($limit);
        $sql = "SELECT e.id, e.ispremium, e.sort, e.title, e.requirements, e.duties, e.conditions, e.istemp,
                   DATE_FORMAT(e.remdate, '%d.%m.%Y') remdate,
                   e.shour,
                   e.sweek,
                   e.smonth,
                   e.svisit,
                   e.isman,
                   e.smart,
                   e.card,
                   e.cardPrommu,
                   e.ismed,
                   e.isavto,
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
              {$filter['filter']} AND e.status = 1 AND e.ismoder = 100 AND e.in_archive=0 AND e.remdate >= CURDATE()
              ORDER BY e.ispremium DESC, e.id DESC 
              {$limit}  
            ) t1 ON t1.id = e.id
            
            LEFT JOIN empl_city c1 ON c1.id_vac = e.id 
            LEFT JOIN city c2 ON c2.id_city = c1.id_city 
            JOIN empl_attribs ea ON ea.id_vac = e.id
            JOIN user_attr_dict d ON (d.id = ea.id_attr) AND (d.id_par = 110)
            JOIN employer em ON em.id_user = e.id_user
            ORDER BY 
              e.ispremium DESC, 
              e.sort DESC, 
              e.mdate DESC
            LIMIT 100";
        $res = Yii::app()->db->createCommand($sql);
        $data= $res->queryAll();

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
                AND e.is_new = 1 #AND e.crdate >= CURDATE()  
              ORDER BY e.ispremium DESC, e.id DESC 
            ) t1 ON t1.id = e.id
            
            LEFT JOIN empl_city c1 ON c1.id_vac = e.id 
            LEFT JOIN city c2 ON c2.id_city = c1.id_city 
            JOIN empl_attribs ea ON ea.id_vac = e.id
            JOIN user_attr_dict d ON (d.id = ea.id_attr) AND (d.id_par = 110)
            JOIN employer em ON em.id_user = e.id_user
            GROUP BY e.id
            ORDER BY e.ispremium DESC, e.id DESC
            LIMIT 1000";

        // 2111 strt... # AND e.is_new = 1 AND e.crdate >= CURDATE() # think abuot this on free time

        $res = Yii::app()->db->createCommand($sql);
        $data= $res->queryAll();
        return $data;
    }
    /**
     * @return array
     * Поиск вакансий для главной страницы
     */
    private function getVacanciesIndexPage()
    {
      $cacheId = Subdomain::site() . DS
        . Yii::app()->controller->id . Yii::app()->request->requestUri;
      $arCitiesIdes = Subdomain::getCacheData()->arCitiesIdes;

      $queryLight = Cache::getData($cacheId . 'index/vacancies_light'); // короткий запрос
      if($queryLight['data']===false) // обновляем кэш
      {
        $queryLight['data'] = Yii::app()->db->createCommand()
          ->select('distinct(ev.id)')
          ->from('empl_vacations ev')
          ->join('empl_city ec','ec.id_vac=ev.id')
          ->where(
            [
              'and',
              'ev.status=:status AND ev.ismoder=:ismoder 
              AND ev.in_archive=:archive AND ev.remdate>=CURDATE()',
              ['in','ec.id_city',$arCitiesIdes]
            ],
            [
              ':status' => self::$STATUS_ACTIVE,
              ':ismoder' => self::$ISMODER_APPROVED,
              ':archive' => self::$INARCHIVE_FALSE
            ]
          )
          ->limit(self::$VACANCIES_IN_MAIN_PAGE)
          ->order('ev.ispremium DESC, ev.id DESC')
          ->queryColumn();
        Cache::setData($queryLight, 300); // короткий запрос кэшируем на 5 минут(300сек.)
      }
      $arId = $queryLight['data'];
      $arRes = Cache::getData($cacheId . 'index/vacancies'); // главный запрос
      $hasChanged = false;
      if(count($arRes['data'])) // проверяем большой запрос на совпадение с маленьким запросом
      {
        if(count($arRes['data'])!=count($arId)) // по кол-ву
        {
          $hasChanged = true;
        }
        else // по ID вакансий и их порядку
        {
          foreach ($arRes['data'] as $v)
          {
            if(!in_array($v['id'],$arId))
            {
              $hasChanged = true;
              break;
            }
          }
        }
      }
      // полная выборка по вакансиям
      if($arRes['data']===false || $hasChanged)
      {
        $arRes['data'] = [];
        $arVacancies = Yii::app()->db->createCommand()
          ->select("id,
            id_user,
            ispremium, 
            istemp,
            DATE_FORMAT(remdate, '%d.%m.%Y') remdate,
            DATE_FORMAT(crdate, '%d.%m.%Y') crdate,
            shour,
            sweek,
            smonth,
            svisit,
            isman,
            iswoman")
          ->from(self::tableName())
          ->where(['in','id',$arId])
          ->queryAll();

        if(count($arVacancies))
        {
          $arIdUsers = $arAssocVacancies = $arPremiumId = [];
          foreach ($arVacancies as $k => $v)
          {
            $arIdUsers[] = $v['id_user'];
            $arAssocVacancies[$v['id']] = $v;
            $v['ispremium'] && $arPremiumId[]=$v['id'];
          }

          if(count($arPremiumId)) // перепроверяем сортировку премиум
          {
            $arPremiumIdSort = Yii::app()->db->createCommand()
              ->select("name")
              ->from('service_cloud')
              ->where([
                "and",
                "type like 'vacancy' and bdate<=CURDATE() and edate>=CURDATE()",
                ['in','name',$arPremiumId]
              ])
              ->order('bdate desc, date desc')
              ->queryColumn();

            if(count($arPremiumIdSort)==count($arPremiumId)) // исправляем сортировку, если кол-ва соответствуют
            {
              foreach ($arId as $k1 => $v1)
              {
                foreach ($arPremiumIdSort as $k2 => $v2)
                {
                  ($k1==$k2 && $v1!=$v2) && $arId[$k1] = $v2;
                }
              }
            }
          }
          $arUsers = Share::getUsers($arIdUsers);
          $arCities = Yii::app()->db->createCommand()
            ->select("ec.id_vac, c.name")
            ->from('empl_city ec')
            ->join('city c','c.id_city=ec.id_city')
            ->where(['in','id_vac',$arId])
            ->queryAll();
          $arAttribs  = Yii::app()->db->createCommand()
            ->select('ea.id_vac, uad.name')
            ->from('empl_attribs ea')
            ->join('user_attr_dict uad','uad.id=ea.id_attr')
            ->where(
              [
                'and',
                'uad.id_par=:id_posts',
                ['in','ea.id_vac',$arId],
              ],
              [':id_posts'=>self::ID_POSTS_ATTRIBUTE]
            )
            ->queryAll();

          foreach ($arId as $id)
          {
            $arV = $arAssocVacancies[$id];
            $arV['cities'] = $arV['posts'] = [];
            $arV['str_cities'] = $arV['str_cities_all'] = $arV['str_posts'] = $arV['payment'] = '';
            //
            $arV['user'] = $arUsers[$arV['id_user']];
            $arV['user']['small_src'] = Share::getPhoto($arV['id_user'], 3, $arV['user']['photo'], 'xsmall');
            //
            $arV['detail_url'] = MainConfig::$PAGE_VACANCY . DS . $id;
            if(($pay = round($arV['shour'],0)) > 0)
            {
              $arV['payment'] = $pay . ' руб/час';
            }
            elseif(($pay = round($arV['sweek'],0)) > 0)
            {
              $arV['payment'] = $pay . ' руб/нед';
            }
            elseif(($pay = round($arV['smonth'],0)) > 0)
            {
              $arV['payment'] = $pay . ' руб/мес';
            }
            elseif(($pay = round($arV['svisit'],0)) > 0)
            {
              $arV['payment'] = $pay . ' руб/пос';
            }
            $arV['period'] = ' с ' . $arV['crdate']
              . ($arV['remdate'] ? ' по ' . $arV['remdate'] : '');
            $arV['work_type'] = $arV['istemp'] ? 'Постоянная' : 'Временная';
            //
            foreach ($arCities as $v)
            {
              $v['id_vac']==$id && $arV['cities'][]=$v['name'];
            }
            if(count($arV['cities']))
            {
              $cnt = 0;
              $arT1 = $arT2 = [];
              foreach ($arV['cities'] as $v)
              {
                $cnt<2 ? $arT1[]=$v : $arT2[]=$v;
                $cnt++;
              }
              if(count($arT1))
              {
                $arV['str_cities'] = join(', ', $arT1);
              }
              if(count($arT2))
              {
                $arV['str_cities_all'] = join(', ', $arT2);
              }
            }
            //
            foreach ($arAttribs as $v)
            {
              $v['id_vac']==$id && $arV['posts'][]=$v['name'];
            }
            if(count($arV['posts']))
            {
              $arV['str_posts'] = join(', ', $arV['posts']);
            }
            //
            $arRes['data'][] = $arV;
          }
          Cache::setData($arRes,86400); // Записуем все данные в кэш
        }
        else
        {
          $arRes['data'] = false;
        }
      }
        return $arRes['data'];
    }

    public function VkRepost($id, $repost)
    {
      $result = $this->getVacancyInfoOrig($id);
      $arVac = reset($result);
      $arCity = $arPost = $arVacUpdate = $arCloudUpdate = array();
      // cities
      foreach ($result as $v)
        !in_array($v['ciname'], $arCity) && $arCity[] = $v['ciname'];
      $sCity = implode(', ', $arCity);
      // posts
      foreach ($result as $v)
        !in_array($v['pname'], $arPost) && $arPost[] = $v['pname'];
      $sPost = implode(', ', $arPost);
      // gender
      if($arVac['isman'] && !$arVac['iswoman'])
        $male = "Юноши";
      elseif($arVac['iswoman'] && !$arVac['isman'])
        $male = "Девушки";
      else
        $male = "Юноши, девушки";
      //  age
      if($arVac['ageto'] == 0) 
        $age = "От " . $arVac['agefrom']; 
      else 
        $age = "От " . $arVac['agefrom'] . " до " . $arVac['ageto'];
      // for vk | fb
      switch ($arVac['pname'])
      {
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
      // salary
      $coast = '';
      if($arVac['shour'] == "0.00")
      {
        return "error: shour = 0";
      }
      else
      {
        if($arVac['svisit']>0)
          $coast = $arVac['svisit'] . " руб/посещение";
        if($arVac['smonth']>0)
          $coast = $arVac['smonth'] . " руб/месяц";
        if($arVac['sweek']>0)
          $coast = $arVac['sweek'] . " руб/неделю";
        if($arVac['shour']>0)
          $coast = $arVac['shour'] . " руб/час";
      }
      // message
      $requirements = strip_tags($arVac['requirements']);
      $requirements = htmlspecialchars_decode($requirements);
      $requirements = str_replace("&nbsp;", PHP_EOL, $requirements);
      if(strlen($requirements)>200)
      {
        $requirements = substr($requirements, 0, 200) . PHP_EOL . '...';
      }

      $conditions = strip_tags($arVac['conditions']);
      $conditions = htmlspecialchars_decode($conditions);
      $conditions = str_replace("&nbsp;", PHP_EOL, $conditions);
      if(strlen($conditions)>200)
      {
        $conditions = substr($conditions, 0, 200) . PHP_EOL . '...';
      }

      $duties = strip_tags($arVac['duties']);
      $duties = htmlspecialchars_decode($duties);
      $duties = str_replace("&nbsp;", PHP_EOL, $duties);
      if(strlen($duties)>200)
      {
        $duties = substr($duties, 0, 200) . PHP_EOL . '...';
      }
    
      $linki = Subdomain::site() . MainConfig::$PAGE_VACANCY . DS . $arVac['id'];
      $vacType = ($arVac['istemp'] ? 'временная' : 'постоянная') . ' работа';

      $message =
             "🔥 Требуется: $sPost 🔥

              Тип: $vacType
             
              Город: $sCity
              
              Пол: $male

              Возраст: $age

              Оплата: $coast

              Сроки оплаты: после окончания проекта

              Требования: 
              • $requirements" . PHP_EOL;
      if(!empty($conditions))
      {
        $message .= PHP_EOL . "Условия:" . PHP_EOL . "• " . $requirements . PHP_EOL;
      }
      if(!empty($duties))
      {
        $message .= PHP_EOL . "Обязанности:" . PHP_EOL . "• " . $duties . PHP_EOL;
      }
      $message .= PHP_EOL . "👇ОТКЛИКНУТЬСЯ НА ВАКАНСИЮ 👇 " . PHP_EOL . "Cсылка: " . $linki;
      // VK
      if(empty($arVac['vk_link']) && substr($repost, 0,1)==1)
      {
        $token = "283f11bf157c1c9d30cc8ac2a7d0bbce526500ad79cd4df2c2b9c39c708459f848a675e669d628ef9acab";
        $group = "-8777665";
        $St = 'https://api.vk.com/method/wall.post';

        $curl = curl_init();
        curl_setopt($curl,CURLOPT_URL,$St);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // защищенный режим с помощью cUrl-a
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false); // защищенный режим с помощью cUrl-a
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, 
            array(
              'access_token'=>$token, 
              'owner_id'=>$group, 
              'attachments'=>$attachments, 
              'message'=>$message, 
              'from_group'=>1, 
              'v' => 'V'
            )
          );
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
        $stream = curl_exec($curl);
        $data = json_decode($stream, true);
        curl_close($curl);
        //
        $arVacUpdate['vk_link'] = "https://vk.com/wall-8777665_".$data['response']['post_id'];
        $arCloudUpdate[] = array(
            'id_user' => $arVac['id_user'],
            'name' => $id,
            'type' => "repost", 
            'bdate' => date("Y-m-d h-i-s"),
            'edate' => date("Y-m-d h-i-s"),
            'status' => 1,
            'sum' => 0,
            'text' => "vk",
            'user' => "vk"
          );
      }
      // TELEGRAM
      if(empty($arVac['tl_link']) && substr($repost, 2,1)==1)
      {
        $title = $arVac['title'];
        $message = "Опубликована вакансия $title\n\n🔥Требуется: $sPost\n\n 🔥Тип: $vacType\n\n 🔥Город: $sCity\n\n  👥Пол: $male\n\n 👫Возраст: $age\n\n 💰Оплата: $coast \n\n⏰Сроки оплаты: после окончания проекта\n\n👔Требования: • $requirements\n\n";

        if(!empty($conditions))
        {
          $message .= "📝Условия: • $conditions\n\n";
        }
        if(!empty($duties))
        {
          $message .= "💼Обязанности: • $duties\n\n";
        }
        $message .= "👇ОТКЛИКНУТЬСЯ НА ВАКАНСИЮ 👇\n\n👌Cсылка: $linki";

        $sendto ="https://api.telegram.org/bot525649107:AAFWUj7O8t6V-GGt3ldzP3QBEuZOzOz-ij8/sendMessage?parse_mode=HTML&chat_id=@prommucom&text=" . urlencode($message) . "&disable_web_page_preview=true";
       // file_get_contents($sendto);
        //
        $arVacUpdate['tl_link'] = "https://t.me/prommucom";
        $arCloudUpdate[] = array(
            'id_user' => $arVac['id_user'],
            'name' => $id,
            'type' => "repost", 
            'bdate' => date("Y-m-d h-i-s"),
            'edate' => date("Y-m-d h-i-s"),
            'status' => 1,
            'sum' => 0,
            'text' => "telegram",
            'user' => "telegram"
          );
      }
      // FB
      if(empty($arVac['fb_link']) && substr($repost, 1,1)==1)
      {
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
        curl_close($ch);
        //
        $arVacUpdate['fb_link'] = "https://www.facebook.com/prommucom/".$output['id'];
        $arCloudUpdate[] = array(
            'id_user' => $arVac['id_user'],
            'name' => $id,
            'type' => "repost", 
            'bdate' => date("Y-m-d h-i-s"),
            'edate' => date("Y-m-d h-i-s"),
            'status' => 1,
            'sum' => 0,
            'text' => "fb",
            'user' => "fb"
          );
      }
      // публикации выполнялись
      if(count($arVacUpdate))
      {
        $arVacUpdate['repost'] = $repost;
        Yii::app()->db->createCommand()
          ->update('empl_vacations', $arVacUpdate, 'id=:id', [':id' => $id]);
        Share::multipleInsert(['service_cloud'=>$arCloudUpdate]);
      }                  
    }
    /**
     * Удалить вакансию
     */
    public function vacDelete($id_vac=false, $id_user=false)
    {
        $id_vac = $id_vac ?: filter_var(Yii::app()->getRequest()->getParam('id'), FILTER_SANITIZE_NUMBER_INT);
        $id_user = $id_user ?: Share::$UserProfile->id;
        $arRes = array(
                'error' => true, 
                'id' => $id_vac, 
                'message' => 'Вакансия №' . $id_vac . ' не найдена'
            );

        $query = Yii::app()->db->createCommand()
            ->select("id")
            ->from('empl_vacations')
            ->where(
                'id=:id AND id_user=:idus',
                [':id' => $id_vac, ':idus' => $id_user]
            )
            ->queryColumn(); 

        if(!count($query))
            return $arRes;

        $arDel = [':id'=>$id_vac];
        $query = Yii::app()->db->createCommand()->delete('empl_vacations','id=:id',$arDel);

        if(!$query)
            return $arRes;

        Yii::app()->db->createCommand()->delete('empl_attribs','id_vac=:id',$arDel);
        Yii::app()->db->createCommand()->delete('empl_city','id_vac=:id',$arDel);
        Yii::app()->db->createCommand()->delete('vacation_stat','id_vac=:id',$arDel);

        $arLocId = Yii::app()->db->createCommand()
            ->select('id')
            ->from('empl_locations')
            ->where('id_vac=:id', $arDel)
            ->queryColumn();

        if(count($arLocId))
        {
            Yii::app()->db->createCommand()->delete('empl_locations','id_vac=:id',$arDel);
            Yii::app()->db->createCommand()->delete('emplv_loc_times',['in','id_loc',$arLocId]);
        }

        $project = new ProjectConvertVacancy();
        $project->synphronization($id_vac,'vacancy-delete');
        $arRes['message'] = 'Вакансия №' . $id_vac . ' успешно удалена';
        $arRes['error'] = false;

        return $arRes;
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
            WHERE v.id_user = {$idus} AND (v.status=1) AND (v.ismoder=100) AND v.in_archive=0
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

         $sql = "
            SELECT 
                v.id, 
                v.title, 
                v.repost
            FROM 
                empl_vacations v
            INNER JOIN ( 
                    SELECT v.id FROM empl_vacations v
                    WHERE v.id_user = {$idus} ORDER BY v.id DESC 
                    {$limit} 
                ) t1
            ON 
                t1.id = v.id
            WHERE 
                v.id_user = {$idus} 
                AND (v.status=1) 
                AND (v.ismoder=100) 
                AND v.in_archive=0
            ORDER BY v.id DESC
        ";

        $data = Yii::app()->db->createCommand($sql)->queryAll();
        $arRes = array();
        foreach ($data as $v) $arRes[$v['id']] = $v;

        return array('vacs'=>$arRes);
    }

    /**
     * Get Moderating Vacansions For Pay-Services
     * @return array
     * @throws CException
     */
    public function getMVacsForPayService()
    {
        $idus = $this->Profile->id ?: Share::$UserProfile->exInfo->id;

        // good views
        $arVacs = Yii::app()->db->createCommand()
            ->select('
                ev.id,
                ev.title,
                ev.repost,
                ev.remdate,
                ec.id_city,
                c.name,
                c.seo_url
            ')
            ->from('empl_vacations ev')
            ->join('empl_city ec','ec.id_vac=ev.id')
            ->join('city c','c.id_city=ec.id_city')
            ->where(
                'ev.id_user=:id_user AND ev.status=:status AND ev.ismoder=:ismoder AND ev.in_archive=:archive',
                [
                    ':id_user'=> $idus,
                    ':status'=>Vacancy::$STATUS_ACTIVE,
                    ':ismoder'=>Vacancy::$ISMODER_APPROVED,
                    ':archive'=>Vacancy::$INARCHIVE_FALSE
                ]
            )
            ->order('ev.id desc')
            ->queryAll();

        return array('vacs'=>$arVacs);
    }

    /**
     * @param $user - profile object
     * @param $isFull - bool all columns or ID only
     * @param $isArchive - int (0=active,1=archive,-1=all)
     * @return array [query = [], key-id = [], id = [], users = []]
     */
    public static function getVacsForChats($user,$isFull,$isArchive=-1)
    {
        if(!$user->id || !in_array($user->type,[2,3]))
            return array();

        $select = 'ev.id' 
            . ($isFull ? ', ev.title, ev.id_user' : '');

        if($user->type==2) // applicant
        {
            $filter = 'vs.id_promo=:id AND ';
            switch ($isArchive)
            {
                case  1: $filter .= 'vs.status>5'; break; // archive
                case  0: $filter .= 'vs.status=5'; break;
                default: $filter .= 'vs.status>4'; break; // -1
            }
            $params = [':id'=>$user->exInfo->id_resume];
        }
        else // employer
        {
            $filter = 'ev.id_user=:id';
            switch ($isArchive)
            {
                case  1: 
                    $filter .= ' AND (ev.status=0 OR vs.status in (6,7))'; // archive
                    break;
                case  0: $filter .= ' AND ev.status=1'; break;
            }
            $params = [':id'=>$user->id];
        }

        $query = Yii::app()->db->createCommand()
                    ->select($select)
                    ->from('empl_vacations ev')
                    ->leftjoin('vacation_stat vs','vs.id_vac=ev.id')
                    ->where($filter, $params)
                    ->order('ev.id desc')
                    ->queryAll();

        foreach ($query as $v)
        {
            $arRes['id'][] = $v['id'];
            $arRes['key-id'][$v['id']] = $v;
            if(!empty($v['id_user']) && $user->id != $v['id_user']) // собираем юзеров для единого запроса
            {
                $arRes['key-id'][$v['id']]['users'][] = $v['id_user'];
                $arRes['users'][] = $v['id_user'];
            }
        }
        $arRes['id'] = array_unique($arRes['id']);
        sort($arRes['id']);
        // ищем соискателей всех вакансий
        if($isFull && count($arRes['id']))
        {
            $query = Yii::app()->db->createCommand()
                        ->select("vs.id_vac id, r.id_user")
                        ->from('vacation_stat vs')
                        ->leftjoin('resume r', 'r.id=vs.id_promo')
                        ->where([
                            'and',
                            'vs.status>4',
                            ['in','vs.id_vac',$arRes['id']]]
                        )
                        ->queryAll();

            foreach ($query as $v)
                if($user->id != $v['id_user']) // собираем юзеров для единого запроса
                {
                    $arRes['key-id'][$v['id']]['users'][] = $v['id_user'];
                    $arRes['users'][] = $v['id_user'];
                }
        }
        if(count($arRes['users']))
        {
            $arRes['users'] = array_unique($arRes['users']);
            sort($arRes['users']);
        }    

        return ($isFull ? $arRes : $arRes['id']);
    }
    /**
     * @param $id_user integer
     * @param $arDates array [bdate,edate,db_bdate,db_edate]
     * @return array [id,title]
     */
    public function getVacsForTermostat($id_user, $arDates)
    {
        $sql = "SELECT id, title
                    FROM empl_vacations
                    WHERE id_user = {$id_user} 
                        AND bdate between '{$arDates['db_bdate']}' 
                        AND '{$arDates['db_edate']}'
                    ORDER BY id DESC";
        $query = Yii::app()->db->createCommand($sql)->queryAll();

        return $query;
    }
	/**
	 *      Собираем вакансии для яндекса
	 */
    public function getVacsForYandex()
    {
      $offset = 0;
      $limit = 100; // вакансий за 1 итерацию
      $arYandexVacs = [64,65,66,67,68,69,73,1191,1193,1196,1204,1205,1259,1324,1394,1418,1441,1515,1529,1530,1532,1621,1656,1694,1719,1723,1730,1733,1740,1741,1759,1788,1801,1804,1805,1825,1905,1912,1930,1931,1933,1935,1937,2037,2052,2055,2069,2075,2077,2086,2094,2095,2113,2121,2132,2140,2148,2149,2150,2151,2155,2157,2159,2163,2171,2174,2175,2178,2188,2189,2190,2191,2192,2202,2206,2214,2217,2219,2220,2224,2225,2240,2244,2246,2255,2257,2261,2263,2268,2274,2275,2281,2283,2290,2292,2294,2298,2299,2300,2301,2303,2305,2319,2321,2324,2327,2334,2335,2341,2342,2343,2345,2347,2348,2349,2350,2352,2353,2369,2373,2374,2375,2376,2377,2395,2389,2390,2391,2401];

      if(date('H')<12) // изменяем даты некоторых вакансий для актуальности типа работы(1 раз за сутки)
      {
        $mtime = time() - 1209600; // 2 недели назад(не более 2 месяцев)
        foreach ($arYandexVacs as $v)
        {
          $mtime = $mtime + 7200;
          $date = date('Y-m-d H:i:s', $mtime);
          $res = Yii::app()->db->createCommand()
            ->update(
              'empl_vacations', 
              ['crdate'=>$date, 'mdate'=>$date, 'status'=>1],
              'id=:id', 
              [':id' => $v]
            );
        }
      }

      $arRes = array('main'=>[],'city'=>[],'location'=>[],'employer'=>[]);

      $arId = Yii::app()->db->createCommand()
                ->select('id')
                ->from('empl_vacations')
                ->where('status=1 AND ismoder=100 AND in_archive=0'/* AND remdate>=NOW()'*/) // убираем remdate для повышения эффективности
                //->order('ispremium DESC, id DESC')
                ->queryColumn();

      $n = count($arId);
      if(!$n)
        return false;
        
      while ($offset <= $n)
      {
        $arNewId = array();
        for ($i = $offset; $i < $n; $i ++)
        {
          if(($i < ($offset + $limit)) && isset($arId[$i]))
            $arNewId[] = $arId[$i];
        }
        // main info
        $query = Yii::app()->db->createCommand()
                    ->select("ev.id,
                        ev.id_user,
                        ev.title,
                        UNIX_TIMESTAMP(ev.crdate) crdate,
                        UNIX_TIMESTAMP(ev.mdate) mdate,
                        ev.shour,
                        ev.sweek,
                        ev.smonth,
                        ev.svisit,
                        ev.requirements,
                        ev.duties,
                        ev.conditions,
                        ev.agefrom,
                        ev.ageto,
                        ev.istemp,
                        ev.isman,
                        ev.exp,
                        ev.iswoman,
                        ev.ismed,
                        ev.isavto,
                        ev.smart,
                        ev.card,
                        ev.cardPrommu,
                        ea.id_attr,
                        ea.val attr")
                    ->from('empl_vacations ev')
                    ->leftjoin('empl_attribs ea','ea.id_vac=ev.id')
                    ->where(['in','ev.id',$arNewId])
                    //->order('ev.ispremium DESC, ev.id DESC')
                    ->queryAll();
        $arRes['main'] = array_merge($arRes['main'],$query);
        // cities
        $query = Yii::app()->db->createCommand()
                    ->select("id,
                        id_vac, 
                        id_city, 
                        DATE_FORMAT(bdate,'%d.%m.%Y') bdate, 
                        DATE_FORMAT(edate,'%d.%m.%Y') edate")
                    ->from('empl_city')
                    ->where(['in','id_vac',$arNewId])
                    ->queryAll();
        $arRes['city'] = array_merge($arRes['city'],$query);
        // locations
        $query = Yii::app()->db->createCommand()
                    ->select("el.id_vac,
                        el.id_city,
                        el.id_metro,
                        el.id_metros,
                        el.name,
                        el.addr,
                        DATE_FORMAT(elt.bdate,'%d.%m.%Y') bdate,
                        DATE_FORMAT(elt.edate,'%d.%m.%Y') edate,
                        elt.btime,
                        elt.etime")
                    ->from('empl_locations el')
                    ->leftjoin('emplv_loc_times elt','elt.id_loc=el.id')
                    ->where(['in','el.id_vac',$arNewId])
                    ->queryAll();
        $arRes['location'] = array_merge($arRes['location'],$query);

        $offset += $limit;
      }
      //
      //
      // атрибуты
      $arAttrib = array();
      foreach ($arRes['main'] as $v)
      {
        !empty($v['id_attr']) && $arAttrib[] = $v['id_attr'];
      }
      $arAttrib = array_unique($arAttrib);
      $query = Yii::app()->db->createCommand()
                  ->select("id,name,id_par")
                  ->from('user_attr_dict')
                  ->where(['in','id',$arAttrib])
                  ->queryAll();
      $arAttrib = array();
      foreach ($query as $v)
        $arAttrib[$v['id']] = [$v['name'],$v['id_par']];
      //
      // города
      $arCity = array();
      foreach ($arRes['city'] as $v)
        $arCity[] = $v['id_city'];
      $arCity = array_unique($arCity);
      $query = Yii::app()->db->createCommand()
                    ->select("ci.id_city id, ci.name, co.name country")
                    ->from('city ci')
                    ->leftjoin('country co','co.id_co=ci.id_co')
                    ->where(['in','ci.id_city',$arCity])
                    ->queryAll();
      $arCity = array();
      foreach ($query as $v)
        $arCity[$v['id']] = ['city'=>$v['name'],'country'=>$v['country']];
      foreach ($arRes['city'] as &$v)
      {
        isset($arCity[$v['id_city']]['city'])
        ? $v['city'] = $arCity[$v['id_city']]['city']
        : $v['city'] = 'Москва';
        isset($arCity[$v['id_city']]['country'])
        ? $v['country'] = $arCity[$v['id_city']]['country']
        : $v['country'] = 'РФ';
      }
      unset($v);
      //
      // локации и метро
      $arMetro = array();
      foreach ($arRes['location'] as $k => $v)
      {
        $arM = array();
        if($v['id_metro']>0)
        {
          $arM[] = $v['id_metro'];
          $arMetro[] = $v['id_metro'];
        }
        if(!empty($v['id_metros']))
        {
          $arT = explode(',',$v['id_metros']);
          $arMetro = array_merge($arMetro,$arT);
          $arM = array_merge($arM,$arT);
        }
        $arRes['location'][$k]['id_metro'] = $arM;
        unset($arRes['location'][$k]['id_metros']);
        // преобразуем время
        if($v['btime'])
        {
          $h = floor($v['btime'] / 60);
          $m = $v['btime'] - $h * 60;
          $arRes['location'][$k]['btime']=sprintf('%d:%02d',$h,$m);
        }
        if($v['etime'])
        {
          $h = floor($v['etime'] / 60);
          $m = $v['etime'] - $h * 60;
          $arRes['location'][$k]['etime']=sprintf('%d:%02d',$h,$m);
        }
      }
      $arMetro = array_unique($arMetro);
      if(count($arMetro))
      {
        $query = Yii::app()->db->createCommand()
                    ->select("id, name")
                    ->from('metro')
                    ->where(['in','id',$arMetro])
                    ->queryAll();
        $arMetro = array();
        foreach ($query as $v)
          $arMetro[$v['id']] = $v['name'];
        foreach ($arRes['location'] as $k => $v)
        {
          if(count($v['id_metro']))
            foreach ($v['id_metro'] as $m)
              $arRes['location'][$k]['metro'][] = $arMetro[$m];
          unset($arRes['location'][$k]['id_metro']);
        }
      }
      //
      // работодатели
      $arUser = array();
      foreach ($arRes['main'] as $v)
        $arUser[] = $v['id_user'];
      //CONCAT(e.firstname,' ',e.lastname) name,
      $query = Yii::app()->db->createCommand()
                ->select("e.id_user,
                  e.type type,
                  e.name company,
                  e.logo photo,
                  e.aboutme,
                  ua.val site,
                  uad.name type_val")
                ->from('employer e')
                ->leftjoin('user_attribs ua','ua.id_us=e.id_user AND ua.key="site"')
                ->leftjoin('user_attr_dict uad','uad.id=e.type')
                ->where(['in','e.id_user',$arUser])
                ->queryAll();
      foreach ($query as $v)
      {
        $v['company'] = htmlspecialchars($v['company'],ENT_XML1);
        $v['aboutcompany'] = htmlspecialchars($v['aboutme'],ENT_XML1);
        /*$v['name'] = trim($v['name']);
        $v['name'] = htmlspecialchars($v['name'],ENT_XML1);*/
        $v['site'] = htmlspecialchars($v['site'],ENT_XML1);
        $v['hr-agency'] = in_array($v['type'], [104,105]) ? 'true' : 'false';
        $src = DS . MainConfig::$PATH_EMPL_LOGO . DS . $v['photo'] . '400.jpg';
        if(file_exists(Subdomain::domainRoot() . $src))
          $v['logo'] = Subdomain::domainSite() . $src;
        $arRes['employer'][$v['id_user']] = $v;
      }
      //
      // собираем все воедино
      $arT = array();
      $arExp = array(
        1 => 'Без опыта', 
        2 => 'До 1 месяца', 
        3 => 'От 1 до 3 месяцев', 
        4 => 'От 3 до 6 месяцев', 
        5 => 'От 6 до 12 месяцев', 
        6 => 'от 1 до 2-х лет', 
        7 => 'Более 2-х лет'
      );

      foreach ($arRes['main'] as $k => $v)
      {
        $id = $v['id'];
        $arT[$id]['id'] = $id;
        $arT[$id]['id_user'] = $v['id_user'];
        $arT[$id]['link'] = Subdomain::domainSite() . MainConfig::$PAGE_VACANCY . DS . $id;
        $arT[$id]['title'] = htmlspecialchars($v['title'],ENT_XML1);
        $arT[$id]['crdate'] = date('c',$v['crdate']);
        $arT[$id]['mdate'] = date('c',$v['mdate']);
        /*
        $v['svisit']>0 && $arT[$id]['salary'] = $v['svisit'];
        $v['shour']>0 && $arT[$id]['salary'] = $v['shour'];
        $v['sweek']>0 && $arT[$id]['salary'] = $v['sweek'];
        $v['smonth']>0 && $arT[$id]['salary'] = $v['smonth'];
        */
        if($v['smonth']>0)
            $arT[$id]['salary'] = $v['smonth'];
        elseif($v['sweek']>0)
            $arT[$id]['salary'] = $v['sweek'] * 4; // 4 недели в месяце
        elseif($v['shour']>0)
            $arT[$id]['salary'] = $v['shour'] * 8 * 22; // 8 часов * 22 рабочих дня в месяце
        else
            $arT[$id]['salary'] = $v['svisit'] * 22;// * 22 рабочих дня в месяце
        $arT[$id]['requirements'] = htmlspecialchars($v['requirements'],ENT_XML1);
        $arT[$id]['requirements'] = mb_convert_case($arT[$id]['requirements'], MB_CASE_LOWER, "UTF-8");
        $arT[$id]['duties'] = htmlspecialchars($v['duties'],ENT_XML1);
        $arT[$id]['duties'] = mb_convert_case($arT[$id]['duties'], MB_CASE_LOWER, "UTF-8");
        $arSalary = [];
        $v['svisit']>0 && $arSalary[] = $v['svisit'] . ' за посещение';
        $v['smonth']>0 && $arSalary[] = $v['smonth'] . ' в месяц';
        $v['sweek']>0 && $arSalary[] = $v['sweek'] . ' в неделю';
        $v['shour']>0 && $arSalary[] = $v['shour'] . ' за час';
        $arT[$id]['conditions1'] = 'Оплата: ' . implode(', ', $arSalary) . '<br>';
        if(in_array($arAttrib[$v['id_attr']][1],[130,132,133,134,163,164]))
            $arT[$id]['conditions2'] = 'Сроки оплаты: ' . $arAttrib[$v['id_attr']][0] . '<br>';
        $arT[$id]['conditions3'] = $v['conditions'];
        $age = '';
        $v['agefrom']>0 && $age .= 'от ' . $v['agefrom'] . ' ';
        $v['ageto']>0 && $age .= 'до ' . $v['ageto'];
        $arT[$id]['age'] = $age;
        $arT[$id]['istemp'] = ($v['istemp'] ? 'постоянная' : 'временная');
        $sex = ($v['isman'] ? 'мужской' : '');
        $sex .= ($v['iswoman']
          ?((($v['isman']&&$v['iswoman'])?' и ':'') . 'женский')
          :'');
        $arT[$id]['sex'] = $sex;
        $arT[$id]['experience'] = $arExp[$v['exp']];
        $arT[$id]['ismed'] = $v['ismed'];
        $arT[$id]['isavto'] = $v['isavto'];
        $arT[$id]['smart'] = $v['smart'];
        $arT[$id]['card'] = $v['card'];
        $arT[$id]['cardPrommu'] = $v['cardPrommu'];

        if($arAttrib[$v['id_attr']][1]==110) // posts
        {
          $arT[$id]['category'][] = array(
            'industry' => 'маркетинг, реклама, PR',                 // !!!!!!!!!!!!!!!!!!
            'specialization' => $arAttrib[$v['id_attr']][0]
          );
        }
      }

      foreach ($arRes['city'] as $v)
      {
        $arV = $arT[$v['id_vac']];
        $loc = $v['country'].', '.$v['city'];
        $arV['adresses'][$v['id']]['location'] = $loc;
        $arT[$v['id_vac']] = $arV;
        foreach ($arRes['location'] as &$l)
        {
          if($v['id_vac']==$l['id_vac'])
          {
            if($v['id']==$l['id_city'])
              $l['location'] = $loc;
            elseif($v['id_city']==$l['id_city'])
              $l['location'] = $loc;
            elseif(!$l['id_city'])
              $l['location'] = $loc;
          }
        }
        unset($l);
      }
      foreach ($arRes['location'] as &$v)
      {
        unset($arT[$v['id_vac']]['adresses'][$v['id_city']]);
        !empty($v['name']) && $v['location'] .= ', ' . $v['name'];
        !empty($v['addr']) && $v['location'] .= ', ' . $v['addr'];
        $arA = ['location' => htmlspecialchars($v['location'],ENT_XML1)];
        isset($v['metro']) && $arA['metro'] = $v['metro'];

        $arT[$v['id_vac']]['adresses'][] = $arA;
      }
      unset($v);

      $arRes['main'] = $arT;

      return $arRes;
    }
    /**
     * @param integer - vacancy ID
     * Собираем параметры фильтра по конкретной вакансии
     */
    public function getFilterForVacancy($id)
    {
        if(!$id)
            return false;

        $arFilter = array();
        $arVac = Yii::app()->db->createCommand()
            ->select('v.id, v.id_user, v.title, 
                v.isman, v.iswoman, v.ismed, 
                v.isavto, v.smart, v.card, 
                v.cardPrommu, v.repost, 
                v.agefrom, v.ageto, 
                e.name, u.email')
            ->from('empl_vacations v')
            ->leftJoin('employer e', 'e.id_user=v.id_user')
            ->leftJoin('user u', 'u.id_user=v.id_user')
            ->where('v.id=:id', array(':id' => $id))
            ->queryRow();

        // достаем города вакансии
        $arFilter['cities'] = Yii::app()->db->createCommand()
            ->select('ec.id_city')
            ->from('empl_city ec')
            ->leftJoin('city c', 'c.id_city=ec.id_city')
            ->where('ec.id_vac=:id', array(':id' => $id))
            ->queryColumn();

        // достаем должности вакансии
        $arFilter['posts'] = Yii::app()->db->createCommand()
            ->select('uad.id')
            ->from('empl_attribs ea')
            ->leftJoin('user_attr_dict uad', 'uad.id=ea.key')
            ->where('ea.id_vac=:id AND uad.id_par=110', array(':id' => $id))
            ->queryColumn();

        // создаем параметры для фильтра
        $arVac['isman'] && $arFilter['sm'] = $arVac['isman'];
        $arVac['iswoman'] && $arFilter['sf'] = $arVac['iswoman'];
        $arVac['ismed'] && $arFilter['mb'] = $arVac['ismed'];
        $arVac['isavto'] && $arFilter['avto'] = $arVac['isavto'];
        $arVac['smart'] && $arFilter['smart'] = $arVac['smart'];
        $arVac['card'] && $arFilter['card'] = $arVac['card'];
        $arVac['cardPrommu'] && $arFilter['cardPrommu'] = $arVac['cardPrommu'];
        $arVac['agefrom'] && $arFilter['af'] = $arVac['agefrom'];
        $arVac['ageto'] && $arFilter['at'] = $arVac['ageto'];

        return ['vacancy'=>$arVac, 'filter'=>$arFilter];
    }
    /**
    * @param $type - string('active'|'archive')
    * @param $id_user - integer
    * @return array('active' array,'archive' array,'apps' array, 'items' array)
    */
    public function getEmpVacanciesIdList($id_user)
    {
        $arRes = ['active'=>[],'archive'=>[],'apps'=>[],'items'=>[]];
        // находим ID всех вакансий юзера
        $arVac = Yii::app()->db->createCommand()
                    ->select("id,status,if(remdate>=CURRENT_DATE(),0,1) archive_date")
                    ->from('empl_vacations')
                    ->where('id_user=:id AND in_archive=0',[':id' => $id_user])
                    ->queryAll();

        if(!count($arVac))
            return $arRes;

        $arId = array();
        foreach ($arVac as $v)
            $arId[] = $v['id'];

        $arRes['apps'] = Yii::app()->db->createCommand()
                    ->select("id_vac,status,isresponse")
                    ->from('vacation_stat')
                    ->where(['in','id_vac',$arId])
                    ->queryAll();

        foreach ($arVac as $v)
        {
            if($v['archive_date'])
            {
                $bFlag = true;
                foreach($arRes['apps'] as $s)
                    if(
                        $v['id']==$s['id_vac']
                        &&
                        ($s['status']==Responses::$STATUS_BEFORE_RATING // есть уведомление об окончании
                            ||
                        $s['status']==Responses::$STATUS_APPLICANT_RATED) // соискатель выставил рейтинг
                    )
                    {
                        $bFlag = false;
                    }
                // если нет заявок или ВСЕ заявки с рейтингом от Р
                $bFlag
                    ? $arRes['archive'][] = $v['id']
                    : $arRes['active'][] = $v['id'];
            }
            else
            {
                $arRes['active'][] = $v['id'];
            }
        }
        return $arRes;
    }
    /**
    * @param $type - string('active'|'archive')
    * @param $id_user - integer
    * @return array('active' array,'archive' array,'apps' array)
    */
    public function getEmpVacanciesList($type, $id_user=false)
    {
        !$id_user && $id_user = ($this->Profile->id ?: Share::$UserProfile->id);
        $arRes = $this->getEmpVacanciesIdList($id_user);
        // пагинация
        $arRes['pages'] = new CPagination(count($arRes[$type]));
        $arRes['pages']->pageSize = $this->limit;
        $arRes['pages']->applyLimit($this);
        // ищем вакансии
        $arId = array();
        for($i=$this->offset, $n=count($arRes[$type]); $i<$n; $i++)
            if($i < ($this->offset + $this->limit))
                $arId[] = $arRes[$type][$i];

        $arRes['items'] = [];

        if(!count($arId))
          return $arRes;

        $arRes['items'] = Yii::app()->db->createCommand()
                    ->select("id,
                        title,
                        status,
                        repost,
                        ispremium,
                        sort,
                        if(ismoder=100,1,0) ismoder,
                        DATE_FORMAT(crdate,'%d.%m.%Y') crdate,
                        DATE_FORMAT(remdate,'%d.%m.%Y') remdate,
                        if(remdate>=CURRENT_DATE(),0,1) archive_date")
                    ->from('empl_vacations')
                    ->where('in_archive=0 and id IN(' . implode(',',$arId) . ')')
                    ->order('id desc')
                    ->queryAll();

        foreach ($arRes['items'] as &$v)
        {
          !isset($v[MainConfig::$VACANCY_RESPONDED]) && $v[MainConfig::$VACANCY_RESPONDED]=0;
          !isset($v[MainConfig::$VACANCY_APPROVED]) && $v[MainConfig::$VACANCY_APPROVED]=0;
          foreach ($arRes['apps'] as $s)
          {
            if ($s['id_vac'] == $v['id'])
            {
              if ($s['status'] >= Responses::$STATUS_APPLICANT_ACCEPT) // утвержденные
              {
                $v[MainConfig::$VACANCY_APPROVED]++;
              }
              if (in_array($s['status'],
                [Responses::$STATUS_BEFORE_RATING,
                  Responses::$STATUS_APPLICANT_RATED]))
              {
                $v['need_rating'] = true; // проверяем необходимость оценить С
              }
              // Откликнувшиеся это все те кто откликнулся на вакансию (и не важно утвердили или отложили его)
              $v[MainConfig::$VACANCY_RESPONDED]++;
            }
          }
            $t = strtotime($v['remdate']) - mktime(0,0,0);
            $t = $t / 86400;
            $v['left_days_cnt'] = $t;
            $v['left_days'] = "$t " . Share::endingYears($t,false);

            if($v['archive_date'])
            {
                $v['vacancy_state'] = 'завершена';
            }
            else
            {
                if($v['status'])
                {
                    $v['vacancy_state'] = $v['ismoder'] ? 'опубликована' : 'ожидает модерации';
                }
                else
                {
                   $v['vacancy_state'] = 'не опубликована'; 
                }
            }
        }
        unset($v);

        $arRes['termostat'] = Termostat::getTermostatVacanciesViews($arRes[$type]);

        return $arRes;
    }
    /**
     * @param $type - string('active'|'archive')
     * @param $id_resume int ID resume
     * Получение списка вакансий для С
     */
    public function getAppVacanciesList($type, $id_resume=false)
    {
        !$id_resume && $id_resume = Share::$UserProfile->exInfo->id_resume;
        $arRes = ['items'=>[],'pages'=>[]];

        $filter = 'vs.id_promo=:id and ';
        $params = [':id' => $id_resume];
        if($type=='archive')
        {
            $filter .= 'vs.status!=:s1 and vs.status!=:s2 and ev.remdate<CURRENT_DATE()';
            $params[':s1'] = Responses::$STATUS_BEFORE_RATING;
            $params[':s2'] = Responses::$STATUS_EMPLOYER_RATED;
        }
        else // active
        {
            $filter .= '( (ev.remdate<CURRENT_DATE() and (vs.status=:s1 or vs.status=:s2)) 
                or ev.remdate>=CURRENT_DATE())';
            $params[':s1'] = Responses::$STATUS_BEFORE_RATING;
            $params[':s2'] = Responses::$STATUS_EMPLOYER_RATED;
        }
        // id
        $arIdVacs = Yii::app()->db->createCommand()
                    ->select('ev.id')
                    ->from('empl_vacations ev')
                    ->leftjoin('vacation_stat vs','vs.id_vac=ev.id')
                    ->where($filter, $params)
                    ->queryColumn();

        if(!count($arIdVacs))
            return $arRes;
        // pagination
        $arRes['pages'] = new CPagination(count($arIdVacs));
        $arRes['pages']->pageSize = $this->limit;
        $arRes['pages']->applyLimit($this);
        // full data
        $arRes['items'] = Yii::app()->db->createCommand()
                    ->select("ev.id,
                        ev.id_user employer,
                        ev.title,
                        DATE_FORMAT(ev.remdate, '%d.%m.%Y') remdate,
                        DATE_FORMAT(ev.crdate, '%d.%m.%Y') crdate,
                        DATE_FORMAT(ev.bdate, '%d.%m.%Y') pubdate,
                        vs.id vstatus_id,
                        vs.isresponse,
                        vs.status,
                        vs.second_response sresponse")
                    ->from('empl_vacations ev')
                    ->leftjoin('vacation_stat vs','vs.id_vac=ev.id')
                    ->where($filter, $params)
                    ->order('ev.id desc')
                    ->limit($this->limit)
                    ->offset($this->offset)
                    ->queryAll();

        // ищем только недоступные вакансии
        $arIdDisVacs = $this->checkAccessToResponse($arIdVacs);

        $arIdUser = array();
        $responses = new ResponsesApplic();
        foreach ($arRes['items'] as &$v)
        {
            $arIdUser[] = $v['employer'];
            $v['pubdate']==='00.00.0000' && $v['pubdate'] = $v['crdate'];
            $v['condition'] = $responses->getStatus($v['isresponse'], $v['status']);
            $v['access_to_chat'] = $v['status']>Responses::$STATUS_EMPLOYER_ACCEPT; // доступ к чату
            $v['access_to_answer'] = ($v['isresponse']==2 && $v['status']==Responses::$STATUS_EMPLOYER_ACCEPT); // приглашение от работодателя сразу status=4
            $v['second_response'] = (
                    !in_array($v['id'],$arIdDisVacs) 
                    && 
                    $v['status']==Responses::$STATUS_REJECT 
                    && 
                    !$v['sresponse']
                );  // проверяем доступна ли вакансия
        }
        unset($v);

        $arRes['users'] = Share::getUsers($arIdUser);
        
        return $arRes;
    }
    /**
     * @param $id_vac int ID vacancy
     * @param $id_resume int ID resume
     * получаем данные по вакансии
     */
    public function getAppVacancy($id_vac, $id_resume)
    {
        $arRes = array();

        $arRes['item'] = Yii::app()->db->createCommand()
                    ->select("ev.id,
                        ev.id_user employer,
                        ev.title,
                        DATE_FORMAT(ev.crdate, '%d.%m.%Y') crdate,
                        DATE_FORMAT(ev.bdate, '%d.%m.%Y') pubdate,
                        DATE_FORMAT(ev.remdate, '%d.%m.%Y') remdate,
                        ev.shour,
                        ev.sweek,
                        ev.smonth,
                        ev.svisit,
                        ev.requirements,
                        ev.duties,
                        ev.conditions,
                        vs.id vstatus_id,
                        vs.isresponse,
                        vs.status,
                        vs.second_response sresponse")
                    ->from('empl_vacations ev')
                    ->leftjoin('vacation_stat vs','vs.id_vac=ev.id')
                    ->where(
                        'vs.id_promo=:id_promo AND ev.id=:id',
                        [':id_promo'=>$id_resume,':id'=>$id_vac]
                    )
                    ->queryRow();

        if(!$arRes['item']['id'])
            return ['item'=>[]];

        if($arRes['item']['pubdate']==='00.00.0000')
            $arRes['item']['pubdate'] = $arRes['item']['crdate'];
        $responses = new ResponsesApplic();
        $arRes['item']['condition'] = $responses->getStatus(
                $arRes['item']['isresponse'],
                $arRes['item']['status']
            );
        $arRes['item']['access_to_chat'] = $arRes['item']['status']>Responses::$STATUS_EMPLOYER_ACCEPT; // доступ к чату
        $arRes['item']['access_to_answer'] = (
                $arRes['item']['isresponse']==2 
                && 
                $arRes['item']['status']==Responses::$STATUS_EMPLOYER_ACCEPT
            ); // приглашение от работодателя сразу status=4
        $arIdDisVac = $this->checkAccessToResponse([$id_vac]);
        $arRes['item']['second_response'] = (
                !count($arIdDisVac) 
                && 
                $arRes['item']['status']==Responses::$STATUS_REJECT
                && 
                !$arRes['item']['sresponse']
            );  // проверяем доступна ли вакансия
        // атрибуты
        $arRes['attribs'] = Yii::app()->db->createCommand()
                    ->select("ea.*, uad.name pname, uad.id_par")
                    ->from('empl_attribs ea')
                    ->leftjoin(
                        'user_attr_dict uad',
                        'uad.id=ea.id_attr' //AND uad.id_par=110'
                    )
                    ->where('ea.id_vac=:id',[':id'=>$id_vac])
                    ->queryAll();

        foreach ($arRes['attribs'] as $v)
            $v['id_par']==110 && $arRes['posts'][] = $v['pname'];
        
        // города
        $arRes['city'] = Yii::app()->db->createCommand()
                    ->select("ec.id,
                        DATE_FORMAT(ec.bdate,'%d.%m.%Y') bdate, 
                        DATE_FORMAT(ec.edate,'%d.%m.%Y') edate,
                        ec.id_city,
                        c.name city")
                    ->from('empl_city ec')
                    ->leftjoin('city c','c.id_city = ec.id_city')
                    ->where('ec.id_vac=:id',[':id'=>$id_vac])
                    ->queryAll();
        // locations
        $arRes['locations'] = Yii::app()->db->createCommand()
                    ->select("el.id,
                            el.id_vac,
                            el.id_city,
                            el.id_metro,
                            el.id_metros,
                            el.name,
                            el.addr")
                    ->from('empl_locations el')
                    ->where('el.id_vac=:id',[':id'=>$id_vac])
                    ->queryAll();

        $arIdLoc = $arMetro = array();
        foreach ($arRes['locations'] as &$v)
        {
            $arIdLoc[] = $v['id'];
            $arM = array();
            if($v['id_metro']>0)
            {
                $arM[] = $v['id_metro'];
                $arMetro[] = $v['id_metro'];
            }
            if(!empty($v['id_metros']))
            {
                $arT = explode(',',$v['id_metros']);
                $arMetro = array_merge($arMetro,$arT);
                $arM = array_merge($arM,$arT);
            }
            $v['id_metro'] = $arM;
            unset($v['id_metros']);
        }
        unset($v);
        // метро
        $arMetro = array_unique($arMetro);
        if(count($arMetro))
        {
            $query = Yii::app()->db->createCommand()
                    ->select("id, name")
                    ->from('metro')
                    ->where(['in','id',$arMetro])
                    ->queryAll();
            $arMetro = array();
            foreach ($query as $v)
                $arMetro[$v['id']] = $v['name'];
            foreach ($arRes['locations'] as &$v)
            {
                if(count($v['id_metro']))
                    foreach ($v['id_metro'] as $m)
                        $v['metro'][] = $arMetro[$m];
                unset($v['id_metro']);
            }
            unset($v);
        }
        // периоды
        $arRes['periods'] = Yii::app()->db->createCommand()
                    ->select("id_loc,
                        DATE_FORMAT(bdate,'%d.%m.%Y') bdate,
                        DATE_FORMAT(edate,'%d.%m.%Y') edate,
                        btime,
                        etime")
                    ->from('emplv_loc_times')
                    ->where(['in','id_loc',$arIdLoc])
                    ->queryAll();

        foreach ($arRes['periods'] as &$v)
        {
            $v['btime'] = $this->getTime($v['btime']);
            $v['etime'] = $this->getTime($v['etime']);
        }

        $arRes['user'] = Share::getUsers([$arRes['item']['employer']]);

        return $arRes;
    }
    /**
     * @param $id_vac int ID vacancy
     * @param $id_resume int ID resume
     * проверка доступа соискателя к вакансии
     */
    public function hasAppAccess($id_vac, $id_resume)
    {
        return Yii::app()->db->createCommand()
                    ->select('ev.id')
                    ->from('empl_vacations ev')
                    ->leftjoin('vacation_stat vs','vs.id_vac=ev.id')
                    ->where(
                        'vs.id_promo=:id_promo AND ev.id=:id',
                        [':id_promo'=>$id_resume,':id'=>$id_vac]
                    )
                    ->queryScalar();
    }
    /**
     * @param $arIdVacs - array of ID vacancies
     * ищем доступные для отклика вакансии
     */
    public function checkAccessToResponse($arIdVacs)
    {
        return Yii::app()->db->createCommand()
                    ->select("DISTINCT(ev.id)")
                    ->from('empl_vacations ev')
                    ->leftjoin('vacation_stat vs','vs.id_vac=ev.id')
                    ->where(['and',
                        'ev.status=0 OR vs.status>5',
                        ['in','ev.id',$arIdVacs]
                    ])
                    ->queryColumn();
    }
    /**
     *  проверяем юзера на владельца вакансии
     */
    public static function hasAccess($id_vacancy, $id_user)
    {
        return Yii::app()->db->createCommand()
                    ->select("id")
                    ->from('empl_vacations')
                    ->where(
                        'id=:id AND id_user=:id_user',
                        [':id'=>$id_vacancy,':id_user'=>$id_user]
                    )
                    ->queryScalar();
    }
    /**
     * @param $id_vacancy - integer
     * @param $cnt_only - bool
     */
    public function getInfo($id_vacancy,$cnt_only=true)
    {
        $arRes = array();
        $responses = new ResponsesEmpl();
        $arRes['cnt'] = $responses->getVacResponsesCnt($id_vacancy);

        if($cnt_only)
            return $arRes['cnt'];

        $section = Yii::app()->getRequest()->getParam('section');
        $arRes['pages'] = new CPagination($arRes['cnt'][$section]);
        $arRes['pages']->pageSize = 20; // 20 юзеров на странице
        $arRes['pages']->applyLimit($responses);
        $arRes = array_merge($arRes, $responses->getVacResponses($id_vacancy,$section));

        return $arRes;
    }
    /**
     * экспорт вакансий в админке
     */
    public function exportVacancies()
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
                'Название компании',
                'ID компании',
                'Название вакансии',
                'Должность(и)',
                'Город(а)',
                'Домен',
                'Дата создания',
                'Время создания',
                'Дата модерации',
                'Время модерации',
                'Дата публикации',
                'Дата завершения',
                'Кол-во просмотров',
                'Кол-во откликнувшихся',
                'Время откликов',
                'Кол-во утвержденных'
              ],
            'autosize' => [0,1,2,3,6,7,8,9,10,11,12,13,14,16]
          );
        $db = Yii::app()->db;
        $conditions = $params = [];
        $rq = Yii::app()->getRequest();
        $dateType = $rq->getParam('export_date');
        $bDate = $rq->getParam('export_beg_date');
        $eDate = $rq->getParam('export_end_date');
        $status = $rq->getParam('export_status');
        $bDate = date('Y-m-d',strtotime($bDate));
        $eDate = date('Y-m-d',strtotime($eDate));

        if($bDate!='1970-01-01')
        {
            switch ($dateType)
            {
                case 'create': 
                    $conditions[] = 'ev.crdate>=:bdate';
                    $params[':bdate'] = $bDate . ' 00:00:00';
                    break;
                case 'begin': 
                    $conditions[] = 'ec.bdate>=:bdate';
                    $params[':bdate'] = $bDate . ' 00:00:00';
                    break;
                case 'end': 
                    $conditions[] = 'ev.remdate>=:bdate';
                    $params[':bdate'] = $bDate;
                    break;
            }   
        }
        if($eDate!='1970-01-01')
        {
            switch ($dateType)
            {
                case 'create': 
                    $conditions[] = 'ev.crdate<=:edate';
                    $params[':edate'] = $eDate . ' 23:59:59';
                    break;
                case 'begin': 
                    $conditions[] = 'ec.bdate<=:edate';
                    $params[':edate'] = $eDate . ' 23:59:59';
                    break;
                case 'end': 
                    $conditions[] = 'ev.remdate<=:edate';
                    $params[':edate'] = $eDate;
                    break;
            }   
        }
        if($status!='all')
        {
            $conditions[] = 'ev.status=' . ($status=='active' ? '1' : '0');
        }

        $arId = $db->createCommand()
                                ->selectDistinct("ev.id")
                                ->from('empl_vacations ev')
                                ->join('empl_city ec','ec.id_vac=ev.id')
                                ->where(implode(' and ',$conditions), $params)
                                ->order('ev.id desc')
                                ->queryColumn();

        $n = count($arId);
        if(!$n)
        {
          Yii::app()->user->setFlash('danger', 'Вакансий не найдено');
          return false;
        }

        while ($offset <= $n)
        {
          $arNewId = array();
          for ($i = $offset; $i < $n; $i ++)
          {
            if(($i < ($offset + $limit)) && isset($arId[$i]))
              $arNewId[] = $arId[$i];
          }
          //
          // main info
          $query = $db->createCommand()
                      ->select("ev.id,
                          ev.id_user,
                          ev.title,
                          ev.index,
                          UNIX_TIMESTAMP(ev.crdate) crdate,
                          UNIX_TIMESTAMP(ev.mdate) mdate,
                          DATE_FORMAT(ev.bdate,'%H:%i %d.%m.%Y') pubdate,
                          DATE_FORMAT(ev.remdate,'%d.%m.%Y') remdate,
                          uad.name post")
                      ->from('empl_vacations ev')
                      ->leftjoin('empl_attribs ea','ea.id_vac=ev.id')
                      ->leftjoin('user_attr_dict uad','uad.id=ea.id_attr')
                      ->where(
                        [
                            'and',
                            'uad.id_par=110', // только атрибут должности
                            ['in','ev.id',$arNewId]
                        ]
                      )
                      ->queryAll();
          $arRes['items'] = array_merge($arRes['items'],$query);
          //
          // cities
          $query = $db->createCommand()
                      ->select("ec.id_vac, 
                          c.name,
                          DATE_FORMAT(ec.bdate,'%d.%m.%Y') bdate")
                      ->from('empl_city ec')
                      ->join('city c','c.id_city=ec.id_city')
                      ->where(['in','ec.id_vac',$arNewId])
                      ->order('bdate asc')
                      ->queryAll();

          for ($i=0, $n=count($query); $i<$n; $i++)
            $arRes['city'][$query[$i]['id_vac']][] = $query[$i];
          //
          // responses
          $query = $db->createCommand()
                      ->select("id_vac,
                        status,
                        DATE_FORMAT(date,'%H:%i %d.%m.%Y') date")
                      ->from('vacation_stat')
                      ->where(['in','id_vac',$arNewId])
                      ->queryAll();

          for ($i=0, $n=count($query); $i<$n; $i++)
                $arRes['responses'][$query[$i]['id_vac']][] = $query[$i];

          $offset += $limit;
        }
        //
        // работодатели
        $arUser = array();
        foreach ($arRes['items'] as $v)
          $arUser[] = $v['id_user'];

        $query = $db->createCommand()
                  ->select("e.id_user, e.name, uc.id_city city, c.region")
                  ->from('employer e')
                  ->leftjoin('user_city uc','uc.id_user=e.id_user')
                  ->leftjoin('city c','c.id_city=uc.id_city')
                  ->where(['in','e.id_user',$arUser])
                  ->queryAll();

        foreach ($query as $v)
            $arRes['employers'][$v['id_user']] = $v;
        //
        // просмотры
        $arRes['views'] = Termostat::getTermostatVacanciesViews($arId);
        //
        // собираем все воедино
        $arT = array();
        foreach ($arRes['items'] as $k => $v)
        {
            $id = $v['id'];
            $employer = $arRes['employers'][$v['id_user']];
            $arT[$id]['id'] = $id;
            $arT[$id]['company'] = $employer['name'];
            $arT[$id]['id_company'] = $v['id_user'];
            $arT[$id]['title'] = $v['title'];

            if(!isset($arT[$id]['posts']))
                $arT[$id]['posts'] = $v['post'];
            else
                $arT[$id]['posts'] .= ', ' . $v['post'];

            if(!isset($arT[$id]['cities']))
            {
              $arT[$id]['cities'] = '';
              $arCity = array();
              for ($i=0, $n=count($arRes['city'][$id]); $i<$n; $i++)
                $arCity[] = $arRes['city'][$id][$i]['name'];
              $arT[$id]['cities'] = implode(', ', $arCity);
            }
            if(!isset($arT[$id]['domain']))
            {
                $arT[$id]['domain'] = '';
                // список доменов имеет смысл только если открыта для индексации
                if(!$v['index'])
                {
                    foreach (Subdomain::getCacheData()->data as $s)
                        if($employer['region']==$s['id'])
                            $arT[$id]['domain'] = $s['url'];

                    if($employer['region']==Subdomain::domain()->id)
                        $arT[$id]['domain'] = Subdomain::domainSite();
                }
            }
            $arT[$id]['create_date'] = date('d.m.Y',$v['crdate']);
            $arT[$id]['create_time'] = date('G:i',$v['crdate']);
            $arT[$id]['moder_date'] = date('d.m.Y',$v['mdate']);
            $arT[$id]['moder_time'] = date('G:i',$v['mdate']);
            $arT[$id]['pubdate'] = $v['pubdate'];
            $arT[$id]['remdate'] = $v['remdate'];
            $arT[$id]['views'] = $arRes['views'][$id]['count'];
            if(!isset($arT[$id]['responded']))
            {
                $arT[$id]['responded'] = 0;
                $arT[$id]['response_date'] = '';
                $arT[$id]['approved'] = 0;
                if(is_array($arRes['responses'][$id]))
                {
                    $arRespDates = [];
                    foreach ($arRes['responses'][$id] as $r)
                    {
                        $arT[$id]['responded']++;
                        $arRespDates[] = $r['date'];
                        if($r['status']>=Responses::$STATUS_APPLICANT_ACCEPT)
                            $arT[$id]['approved']++;
                    }
                    $arT[$id]['response_date'] = implode(',  ', $arRespDates);
                }
            }
        }
        $arRes['items'] = $arT;

        return $arRes;
    }
    /**
     * @param $id_vacancy - id_vacancy
     */
    public function getVacancyAdmin($id_vacancy)
    {
        $db = Yii::app()->db;
        $arRes = array();
        $arRes['id'] = $id_vacancy;
        $arRes['item'] = $db->createCommand()
                    ->select("ev.*,
                        UNIX_TIMESTAMP(ev.crdate) crdate,
                        UNIX_TIMESTAMP(ev.mdate) mdate,
                        UNIX_TIMESTAMP(ev.bdate) bdate,
                        UNIX_TIMESTAMP(ev.edate) edate,
                        UNIX_TIMESTAMP(ev.remdate) remdate,
                        e.name coname, logo")
                    ->from('empl_vacations ev')
                    ->join('employer e','e.id_user=ev.id_user')
                    ->where('ev.id=:id',[':id'=>$id_vacancy])
                    ->queryRow();

        if(!is_array($arRes['item']))
            return $arRes;

        // отклики
        $arRes['responses'] = [];
        $responses = new ResponsesEmpl();
        $arRes['responses']['cnt'] = $responses->getVacResponsesCnt($id_vacancy);
        $section = Yii::app()->getRequest()->getParam('section');
        empty($section) && $section=MainConfig::$VACANCY_APPROVED;
        $arRes['responses']['pages'] = new CPagination($arRes['responses']['cnt'][$section]);
        $arRes['responses']['pages']->pageSize = 20; // 20 юзеров на странице
        $arRes['responses']['pages']->applyLimit($responses);
        $arRes['responses']['section'] = Yii::app()->getRequest()->getParam('section');
        $arRes['responses']['section'] = ($arRes['responses']['section'] ?: MainConfig::$VACANCY_APPROVED);
        $arRes['responses'] = array_merge(
          $arRes['responses'],
          $responses->getVacResponses($id_vacancy, $arRes['responses']['section'])
        );
        // просмотры
        $model = new Termostat();
        $arRes['views'] = $model->getTermostatCount($id_vacancy);
        // история заявок
        $model = new ResponsesHistory();
        $arRes['responses_history'] = $model->getAllData($id_vacancy);
        if(count($arRes['responses_history']['users']))
        {
         $arRes['responses_history']['users'][$arRes['item']['id_user']] = [
           'id' => $arRes['item']['id_user'],
           'status' => UserProfile::$EMPLOYER,
           'name' => $arRes['item']['coname'],
           'src' => Share::getPhoto(
             $arRes['item']['id_user'],
             UserProfile::$EMPLOYER,
             $arRes['item']['logo']),
           'profile_admin' => '/admin/EmplEdit/' . $arRes['item']['id_user']
         ];
        }
        // атрибуты
        $query = $db->createCommand()
                    ->select("ea.*, uad.name dname, uad.id_par, uad.postself")
                    ->from('empl_attribs ea')
                    ->join('user_attr_dict uad','uad.id=ea.id_attr')
                    ->where('ea.id_vac=:id',[':id'=>$id_vacancy])
                    ->queryAll();
        // должности
        $arRes['properties'] = $arRes['item']['post'] = array();
        for($i=0,$n=count($query); $i<$n; $i++)
        {
            if($query[$i]['id_par']==110)
                $arRes['item']['post'][] =  $query[$i]['dname'];
            $arRes['properties'][$query[$i]['key']] = $query[$i];
        }
        // города
        $arRes['cities'] = $this->getCities($id_vacancy);
        reset($arRes['cities']);
        $arRes['item']['city'] = [key($arRes['cities'])=>[current($arRes['cities'])['city']]]; // для СЕО
        // locations
        $arRes['locations'] = $this->getLocations($id_vacancy);
        $arRes['dates'] = $this->getRealDates($arRes['cities'],$arRes['locations']);

        $model = new ServiceCloud();
        $model->limit = 10000;
        $arRes['services'] = $model->getVacData($id_vacancy);
        $arRes['seo'] = Seo::getMetaForVac($arRes['item']);

        return $arRes;
    }
    /**
     * @param $id_vacancy - id_vacancy
     */
    private function getCities($id_vacancy)
    {
        $arRes = array();
        if(!$id_vacancy)
            return $arRes;

        $query = Yii::app()->db->createCommand()
                    ->select("ec.id,
                        UNIX_TIMESTAMP(ec.bdate) bdate, 
                        UNIX_TIMESTAMP(ec.edate) edate,
                        ec.id_city,
                        c.name city")
                    ->from('empl_city ec')
                    ->join('city c','c.id_city = ec.id_city')
                    ->where('ec.id_vac=:id',[':id'=>$id_vacancy])
                    ->queryAll();

        for($i=0,$n=count($query); $i<$n; $i++)
        {
            $arRes[$query[$i]['id']] = $query[$i];
        }

        return $arRes;
    }
    /**
     * @param $id_vacancy - id_vacancy
     */
    private function getLocations($id_vacancy)
    {
        $arRes = array();
        if(!$id_vacancy)
            return $arRes;

        $query = Yii::app()->db->createCommand()
                    ->select("*,
                        UNIX_TIMESTAMP(elt.bdate) bdate, 
                        UNIX_TIMESTAMP(elt.edate) edate")
                    ->from('empl_locations el')
                    ->join('emplv_loc_times elt','elt.id_loc=el.id')
                    ->where('el.id_vac=:id',[':id'=>$id_vacancy])
                    ->queryAll();

        $arMetro = array();
        for($i=0,$n=count($query); $i<$n; $i++)
        {
            $arTemp = $arRes[$query[$i]['id']];
            $arTemp['id'] = $query[$i]['id'];
            $arTemp['id_city'] = $query[$i]['id_city'];
            $arM = array();
            if($query[$i]['id_metro']>0)
            {
                $arM[] = $query[$i]['id_metro'];
                $arMetro[] = $query[$i]['id_metro'];
            }
            if(!empty($query[$i]['id_metros']))
            {
                $arT = explode(',',$query[$i]['id_metros']);
                $arMetro = array_merge($arMetro, $arT);
                $arM = array_merge($arM, $arT);
            }
            $arTemp['id_metro'] = $arM;
            $arTemp['metro'] = [];
            $arTemp['name'] = $query[$i]['name'];
            $arTemp['addr'] = $query[$i]['addr'];
            $arTemp['periods'][] = [
                    'bdate' => $query[$i]['bdate'],
                    'edate' => $query[$i]['edate'],
                    'btime' => $this->getTime($query[$i]['btime']),
                    'etime' => $this->getTime($query[$i]['etime'])
                ];
            $arRes[$query[$i]['id']] = $arTemp;
        }

        if(!count($arMetro))
            return $arRes;

        $query = Yii::app()->db->createCommand()
                    ->select("id, name")
                    ->from('metro')
                    ->where(['in','id',$arMetro])
                    ->queryAll();

        foreach ($arRes as $id => $v)
        {
            if(!count($v['id_metro']))
                continue;

            for($j=0,$n=count($query); $j<$n; $j++)
                if(in_array($query[$j]['id'],$v['id_metro']))
                    $arRes[$id]['metro'][] = $query[$j]['name'];
        }

        return $arRes;
    }
    /**
     * @param $time - integer from `emplv_loc_times` table
     */
    private function getTime($time)
    {
        $result = '';
        if($time)
        {
            $h = floor($time / 60);
            $m = $time - $h * 60;
            $result = sprintf('%02d:%02d', $h, $m);
        }
        else
        {
          $result = '00:00';
        }
        return $result;
    }
    /**
     * @param $arCities array - (bdate[unix], edate[unix])
     * @param $arLocations array - (periods - array(bdate[unix],edate[unix]))
     * @return array ('bdate'[unix],'edate'[unix])
     */
    public function getRealDates($arCities, $arLocations)
    {
        $begDate = reset($arCities)['bdate']; // дата начала первого города
        $endDate = reset($arCities)['edate']; // дата окончания первого города
        foreach ($arCities as $id_city => $c)
        {
            $c['bdate'] < $begDate && $begDate = $c['bdate'];
            $c['edate'] > $endDate && $endDate = $c['edate'];

            if (isset($arLocations))
                foreach ($arLocations as $id_loc => $l)
                    if (isset($l['periods']))
                        foreach ($l['periods'] as $p)
                        {
                            $p['bdate'] < $begDate && $begDate = $p['bdate'];
                            $p['edate'] > $endDate && $endDate = $p['edate'];
                        }
        }
        return array('bdate' => $begDate, 'edate' => $endDate);
    }

    /**
     * setViewed
     * @return model
     */
    public function setViewed($id) {
        return Yii::app()->db->createCommand()->update(
            $this->tableName(),
            ['is_new' => 0],
            'id=:id',
            [':id' => $id]
        );
    }
    /**
     * @param $id_user
     * @return array
     */
    public function getVacanciesByUser($id_user)
    {
      return Yii::app()->db->createCommand()
        ->select('*')
        ->from($this->tableName())
        ->where('id_user=:id',[':id'=>$id_user])
        ->queryAll();
    }
    /**
     * @param $arId
     * @return array|CDbDataReader
     */
    public function getVacanciesById($arId)
    {
      return Yii::app()->db->createCommand()
        ->select('*')
        ->from($this->tableName())
        ->where(['in','id',$arId])
        ->queryAll();
    }
  /**
   * @param $id - integer
   * @param $arParams - array(key => value)
   * @return mixed
   */
  public function updateParam($id, $arParams)
  {
    $result = self::model()->updateByPk($id, $arParams);

    return $result;
  }
  /**
   * @return array
   */
  public static function getPostsList()
  {
    $arRes = Cache::getData('/Vacancy/associativePosts');
    if($arRes['data']===false)
    {
      $query = Yii::app()->db->createCommand()
        ->select('id, name')
        ->from('user_attr_dict')
        ->where('id_par=:id_par',[':id_par'=>self::ID_POSTS_ATTRIBUTE])
        ->order('npp, name')
        ->queryAll();

      $arRes['data'] = [];
      foreach ($query as $v)
      {
        $arRes['data'][$v['id']] = $v['name'];
      }
      Cache::setData($arRes, 604800); // кеш на неделю
    }
    return $arRes['data'];
  }
  /**
   * @return array
   */
  public static function getPostsSortList()
  {
    $arRes = Cache::getData('/Vacancy/sortPosts');
    if($arRes['data']===false)
    {
      $arRes['data'] = Yii::app()->db->createCommand()
                        ->select('id, name')
                        ->from('user_attr_dict')
                        ->where('id_par=:id_par',[':id_par'=>self::ID_POSTS_ATTRIBUTE])
                        ->order('npp, name')
                        ->queryAll();
      Cache::setData($arRes, 604800); // кеш на неделю
    }
    return $arRes['data'];
  }

  public static function getAllAttributes()
  {
    $arRes = Cache::getData('/Vacancy/attributes');
    if($arRes['data']===false)
    {
      $arRes['data'] = (object)[
        'items'=>[],
        'lists'=>[],
        'additional_lists'=>['manh','weig','hcolor','hlen','ycolor','chest','waist','thigh']
      ];
      $query = Yii::app()->db->createCommand()->from('user_attr_dict')->queryAll();

      foreach ($query as $v1)
      {
        $arRes['data']->items[$v1['key']] = $v1;
        if(!$v1['id_par'])
        {
          continue;
        }
        foreach ($query as $v2)
        {
          if($v2['id']==$v1['id_par'])
          {
            $arRes['data']->lists[$v2['key']][$v1['id']] = $v1['name'];
          }
        }
      }
      Cache::setData($arRes, 604800); // кеш на неделю
    }
    return $arRes['data'];
  }
  /**
   * @param $objData
   * @param $objUser
   * Создание вакансии
   */
  public function createVacancy($objData, $objUser)
  {
    $date = date('Y-m-d H:i:s');
    $repost = 0;
    in_array('vk',$objData->repost) && $repost|=1;
    in_array('facebook',$objData->repost) && $repost|=2;
    in_array('telegram',$objData->repost) && $repost|=4;
    $repost = decbin($repost);
    $repost = str_pad($repost, 3, "0", STR_PAD_LEFT);
    // создание вакансии
    $arInsert = [
      'id_user' => $objUser->id_user,
      'id_empl' => $objUser->id,
      'title' => $objData->title,
      'requirements' => $objData->requirements,
      'duties' => $objData->duties,
      'conditions' => $objData->conditions,
      'remdate' => Share::dateFormatToMySql($objData->edate),
      'istemp' => $objData->istemp,
      'shour' => ($objData->salary_type==0 ? $objData->salary : 0),
      'sweek' => ($objData->salary_type==1 ? $objData->salary : 0),
      'smonth' => ($objData->salary_type==2 ? $objData->salary : 0),
      'svisit' => ($objData->salary_type==3 ? $objData->salary : 0),
      'exp' => $objData->exp,
      'isman' => in_array('man',$objData->gender),
      'iswoman' => in_array('woman',$objData->gender),
      'ismed' => isset($objData->medbook),
      'isavto' => isset($objData->car),
      'agefrom' => $objData->age_from,
      'ageto' => $objData->age_to,
      'status' => self::$STATUS_NO_ACTIVE,
      'ismoder' => self::$ISMODER_NEW,
      'crdate' => $date,
      'mdate' => $date,
      'smart' => isset($objData->smartphone),
      'repost' => $repost,
      'card' => isset($objData->card),
      'cardPrommu' => isset($objData->card_prommu),
      'self_employed' => $objData->self_employed
    ];
    Yii::app()->db->createCommand()->insert(self::tableName(), $arInsert);
    $vacancy = Yii::app()->db->getLastInsertID();
    // сортировка
    Yii::app()->db->createCommand()->update(
      self::tableName(),
      ['sort'=>$vacancy],
      ['id'=>$vacancy]
    );
    // добавление городов вакансии
    $arInsert = [];
    foreach ($objData->city as $v)
    {
      $arInsert[] = [
        'id_vac' => $vacancy,
        'id_city' => $v,
        'bdate' => Share::dateFormatToMySql($objData->bdate),
        'edate' => Share::dateFormatToMySql($objData->edate)
      ];
    }
    Share::multipleInsert(['empl_city'=>$arInsert]);
    // Сохранение должностей
    self::saveVacancyPosts($vacancy, $objData->post); // должность
    // Сохранение атрибутов
    $arInsert = ['paylims' => $objData->salary_time];
    if(!empty($objData->salary_comment)) // Комментарии по оплате
    {
      $arInsert['salary-comment'] = $objData->salary_comment;
    }
    self::saveVacancyAttributes($vacancy, $arInsert);
    // email ведомление юзеру
    Mailing::set(3,
      [
        'email_user' => $objUser->email,
        'company_user' => $objUser->name,
        'id_vacancy' => $vacancy
      ]
    );
    return $vacancy;
  }
  /**
   * @param $id - integer
   * @param $id_user - integer
   * @param $module - integer
   * @param $data - object
   * Редактирование вакансии
   */
  public function setVacancy($id, $id_user, $module, $data=false)
  {
    $arUpdate = [];
    if($module==1)
    {
      $arUpdate = [
        'title' => $data->title,
        'exp' => $data->exp,
        'agefrom' => $data->agefrom,
        'ageto' => $data->ageto,
        'isman' => $data->isman,
        'iswoman' => $data->iswoman,
        'istemp' => $data->istemp
      ];
      self::saveVacancyPosts($id, $data->post);
    }
    if($module==3)
    {
      $arUpdate = [
        'exp' => $data->exp,
        'agefrom' => $data->agefrom,
        'ageto' => $data->ageto,
        'isman' => $data->isman,
        'iswoman' => $data->iswoman,
        'istemp' => $data->istemp
      ];
      self::saveVacancyPosts($id, $data->post);
      $arAttr = [];
      foreach ($data->properties as $key => $v)
      {
        if(in_array($key,self::getAllAttributes()->additional_lists))
        {
          if($key=='manh' || $key=='weig')
          {
            $arAttr[$key]=$v['value'];
          }
          else
          {
            $arAttr[$key]=$v['id'];
          }
        }
      }
      self::saveVacancyAttributes($id, $arAttr);
    }
    if($module==4)
    {
      $arUpdate = [
        'requirements' => $data->requirements,
        'duties' => $data->duties,
        'conditions' => $data->conditions
      ];
    }
    if($module==5)
    {
      $arUpdate = ['self_employed' => $data->self_employed];
    }
    if($module==6)
    {
      $arUpdate = [
        'shour' => $data->shour,
        'sweek' => $data->sweek,
        'smonth' => $data->smonth,
        'svisit' => $data->svisit
      ];
      self::saveVacancyAttributes($id, [
        'paylims' => $data->properties['paylims']['id'],
        'salary-comment' => $data->properties['salary-comment']['value']
      ]);
    }
    if($module==7)
    {
      $arUpdate = [
        'ismed' => $data->ismed,
        'isavto' => $data->isavto,
        'smart' => $data->smart,
        'cardPrommu' => $data->cardPrommu,
        'card' => $data->card
      ];
    }
    if($module==8)
    {
      $model = new City();
      $model->changeVacancyLocations($id, $id_user);
    }

    if(count($arUpdate))
    {
      Yii::app()->db->createCommand()->update(
        self::tableName(),
        $arUpdate,
        'id=:id AND id_user=:id_user',
        [':id'=>$id, ':id_user'=>$id_user]
      );
    }
  }
  /**
   * @param $id
   * @return object
   */
  public function getVacancy($id)
  {
    $id = intval($id);

    $result = (object)[
      'errors' => [],
      'data' => [],
      'employer' => [],
      'posts' => $this->getPostsSortList(),
      'attributes' => $this->getAllAttributes(),
      'is_owner' => false,
      'error_moodule' => false
    ];

    if(!$id)
    {
      $result->errors['system'] = true;
      return $result;
    }
    // Вакансия
    $query = Yii::app()->db->createCommand()
                ->from(self::tableName())
                ->where('id=:id',[':id'=>$id])
                ->limit(1)
                ->queryRow();

    if(!$query)
    {
      $result->errors['system'] = true;
      return $result;
    }

    $result->data = (object)$query;
    $result->data->crdate_unix = strtotime($query['crdate']);
    $result->data->mdate_unix = strtotime($query['mdate']);
    $result->data->remdate_unix = strtotime($query['remdate']);
    $result->data->ageto=0 && $result->data->ageto = '';
    ($query['duties']=="<br>" || $query['duties']=="&lt;br&gt;") && $result->data->duties = '';
    ($query['conditions']=="<br>" || $query['conditions']=="&lt;br&gt;") && $result->data->conditions = '';
    // Актуальная вакансия - активная и промодерированая
    $result->data->is_actual =
      ($query['status']==self::$STATUS_ACTIVE) && ($query['ismoder']==self::$ISMODER_APPROVED);
    // Атрибуты
    $query = Yii::app()->db->createCommand()
      ->from('empl_attribs')
      ->where('id_vac=:id',[':id'=>$id])
      ->queryAll();
    $result->data->post = [];
    $result->data->properties = [];
    // Собираем значения свойств
    $result->data->add_props = false;
    $result->data->additional = $result->data->ismed
      || $result->data->isavto
      || $result->data->smart
      || $result->data->card
      || $result->data->cardPrommu;
    if(count($query))
    {
      foreach ($query as $v)
      {
        $arProp = $result->attributes->items[$v['key']];
        if($arProp['id_par']==self::ID_POSTS_ATTRIBUTE) // должность
        {
          $result->data->post[] = $v['key'];
        }
        else // прочие атрибуты
        {
          $arr = ['id' => $v['id_attr'], 'key' => $v['key']];
          if(in_array($v['key'],$result->attributes->additional_lists))
          {
            $result->data->add_props = true;
          }
          if(in_array($v['key'],array_keys($result->attributes->lists)))
          {
            foreach ($result->attributes->items as $p)
            {
              if($v['id_attr']==$p['id'])
              {
                $arr['name'] = $arProp['name'];
                $arr['value'] = $p['name'];
              }
            }
          }
          else
          {
            $arr['name'] = $arProp['name'];
            $arr['value'] = $v['val'];
          }
          $result->data->properties[$v['key']] = $arr;
        }
      }
    }
    // Города, локации, периоды
    $result->data->cities = $this->getCities($id);
    $result->data->locations = $this->getLocations($id);
    $result->data->dates = $this->getRealDates(
      $result->data->cities,
      $result->data->locations
    );
    // Работодатель
    $arUser = Share::getUsers([$result->data->id_user]);
    if(!count($arUser))
    {
      $result->errors['system'] = true;
      return $result;
    }
    $result->employer = (object)$arUser[$result->data->id_user];
    if(Share::$UserProfile->id==$result->data->id_user)
    {
      $result->is_owner = true;
    }

    return $result;
  }
}