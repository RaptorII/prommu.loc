<?php
/**
 * Date: 22.04.2016
 *
 * Модель переписки
 */

abstract class Im extends Model
{
    public static $ADMIN_APPLICANT = 2054;
    public static $ADMIN_EMPLOYER = 1766;
    /** @var  UserProfile */
    protected $Profile;
    /**
     * Im constructor.
     * @param $Profile UserProfile
     */
    function __construct($Profile = null)
    {
        parent::__construct();

        $this->Profile = $Profile ?: Share::$UserProfile;
        $this->isApp = get_class($this)=='ImApplic';
        $this->adminId = ($this->isApp ? 1766 : 2054); // id Админа (для каждого свое)
        $this->limit = 10; // вывод 10 элементов по пагинации
    }
    /**
     * @param $arVacs - array() ID of vacancies
     * @return array()
     */
    public function getChatsByVacs($arVacs)
    {
        $filter = ($this->isApp ? 'ct.id_usp' : 'ct.id_use') 
                    . '=' . $this->Profile->id;

        return Yii::app()->db->createCommand()
                ->select("DISTINCT(c.id),
                    c.id_theme, 
                    c.id_use, 
                    c.id_usp, 
                    c.is_resp, 
                    c.is_read,
                    ct.id_vac")
                ->from('chat c')
                ->leftjoin('chat_theme ct','c.id_theme=ct.id')
                ->where(['and',$filter,['in','ct.id_vac',$arVacs]])
                ->queryAll();  
    }
    /**
     * @param $isMain - boolean - 1="/user/chats"
     * @return array()
     */
    public function getChatsByFeedback($isMain)
    {
        $user = $this->Profile;
        $select = "DISTINCT(c.id), c.is_resp, c.is_read, c.id_theme";
        !$isMain && $select .= ", ct.id_use, ct.id_usp, ct.title";

        $filter = 'ct.id_vac=0 AND ';
        $filter .= $this->isApp
            ? 'c.id_usp=:id AND c.id_use=:adm'
            : 'c.id_use=:id AND c.id_usp=:adm';
        $params = array(':id'=>$user->id,':adm'=>$this->adminId);
        $arRes = Yii::app()->db->createCommand()
                    ->select($select)
                    ->from('chat c')
                    ->leftjoin('chat_theme ct', 'ct.id=c.id_theme')
                    ->where($filter,$params)
                    ->order('c.id desc')
                    ->queryAll();

        if(!$isMain && count($arRes))
        {
            $arC = array();
            foreach ($arRes as $v) $arC[] = $v['id_theme'];
            $arC = array_unique($arC);
            sort($arC);

            if(count($arC))
            {
                $query = Yii::app()->db->createCommand()
                            ->select('chat, theme, direct, status')
                            ->from('feedback')
                            ->where(['in','chat',$arC])
                            ->queryAll();

                foreach ($arRes as &$v1)
                    foreach ($query as $v2)
                        if($v1['id_theme']==$v2['chat'])
                        {
                            $v1['theme'] = $v2['theme'];
                            $v1['direct'] = $v2['direct'];
                        }
                unset($v1);
            }
        }

        return $arRes;
    }
    /**
    *      страница всех чатов
    */
    public function getAllChats()
    {
        $user = $this->Profile;

        $arRes = array(
                    'cnt'=> 0,
                    'feedback' => [
                            'cnt-mess'=>0,
                            'cnt-noread'=>0
                        ],
                    'vacancies' => [
                            'cnt-mess'=>0,
                            'cnt-users'=>0,
                            'cnt-noread'=>0
                        ]  
                );

        // чат фидбека
        $arFeedback = $this->getChatsByFeedback(true);

        if(count($arFeedback))
        {
            $arC = array();
            foreach ($arFeedback as $v)
            {
                $arC[] = $v['id_theme'];
                $arRes['feedback']['cnt-mess']++;
                if(!$v['is_read'])
                {
                    if($v['is_resp'] == $this->isApp) // разный результат для разного типа юзера
                        $arRes['feedback']['cnt-noread']++;
                }
            }
            $arC = array_unique($arC);
            $arRes['cnt'] = $arC;
        }

        // чат вакансий
        $arVacs = Vacancy::getVacsForChats($user,true);
        // если вакансий нет - финал
        if(!count($arVacs['id']))
            return $arRes;
        // формируем массив вакансии
        $arV = $arVacs['key-id'];
        $arRes['vacancies']['cnt-users'] = count($arVacs['users']);

        // Находим сообщения общих чатов
        $arPublic = VacDiscuss::getChatsByVacs($arVacs['id'],$user);
        // переносим в вакансии общие чаты
        foreach ($arPublic as $v)
        {
            $id = $v['id_vac'];
            // этот счетчик инкрементируем один раз для вакансии
            !isset($arV[$id]['public-mess']) && $arRes['cnt']++;
            // id общих чатов уникальны в $arPublic
            $arV[$id]['public-mess'][] = $v['id'];
            $arRes['vacancies']['cnt-mess']++;
            if(!$v['readed']) // подсчет непрочитанных
            {
                $arV[$id]['public-mess-readed'][] = $v['id'];
                $arRes['vacancies']['cnt-noread']++;
            }
        }

        // Находим сообщения личных чатов
        $arPrivate = $this->getChatsByVacs($arVacs['id']);
        // переносим в вакансии личные чаты

        foreach ($arPrivate as $v)
        {
            $id = $v['id_vac'];
            if(!in_array($v['id_theme'], $arV[$id]['personal-chat']))
            {
                $arV[$id]['personal-chat'][] = $v['id_theme'];
                $arRes['cnt']++;
            }
            // цикл по массиву уникальных сообщений
            $arRes['vacancies']['cnt-mess']++;
             // подсчет непрочитанных
            if(!$v['is_read'])
            {
                if($v['is_resp'] == $this->isApp) // разный результат для разного типа юзера
                    $arRes['vacancies']['cnt-noread']++;
            }
        }

        return $arRes;
    }
    /**
     *      список чатов с админом
     */
    public function getFeedbackChats()
    {
        $arRes = $arId = $arIdus = $arI = array();
        $arFeedback = $this->getChatsByFeedback(false);
        // без чатов дальше работать нет смысла
        if(!count($arFeedback))
            return $arRes;

        foreach ($arFeedback as $v)
        {
            $id = $v['id_theme']; 
            // разные значения для разных типов пользователей
            $idUser = $this->isApp ? $v['id_use'] : $v['id_usp'];

            $arI[$id]['id'] = $id;
            $arI[$id]['direct'] = $v['direct'] ?: 1;
            $arI[$id]['user'] = $idUser;
            // Устанавливаем заголовок полюбому
            $title = 'Без названия';
            !empty($v['title']) && $title = $v['title'];
            !empty($v['theme']) && $title = $v['theme'];
            $arI[$id]['title'] = $title;
            // начальные значения счетсиков
            !isset($arI[$id]['cnt-mess']) && $arI[$id]['cnt-mess'] = 0;
            !isset($arI[$id]['cnt-noread']) && $arI[$id]['cnt-noread'] = 0;
            // разные значения для разных типов пользователей
            if(!$v['is_read'] && ($v['is_resp']==$this->isApp))
            {
                $arI[$id]['cnt-noread']++;
                $arRes['cnt-mess-noread']++;
            }
            $arI[$id]['cnt-mess']++;
            $arRes['cnt-mess']++;
            $arIdus[] = $idUser; // собираем юзеров для общего запроса
            !in_array($id, $arId) && $arId[] = $id; // собираем ID чатов для пагинации
        }
        // счечик чатов
        $arRes['cnt-chat'] = count($arId);
        // пагинация и все дела
        $arRes['pages'] = new CPagination($arRes['cnt-chat']);
        $arRes['pages']->pageSize = $this->limit;
        $arRes['pages']->applyLimit($this);

        for($i=$this->offset; $i<$arRes['cnt-chat']; $i++)
            if($i<($this->offset+$this->limit))
                $arRes['items'][$arId[$i]] = $arI[$arId[$i]];

        $arRes['users'] = Share::getUsers($arIdus);

        return $arRes;
    }
    /**
     *      список чатов по вакансиям
     */
    public function getVacanciesChats()
    {
        $user = $this->Profile;

        $arRes = array(
                'items' => array(),
                'cnt-chat' => 0,
                'cnt-mess' => 0,
                'cnt-mess-noread' => 0
            );
        
        $bArchive = Yii::app()->getRequest()->getParam('s')=='archive';

        // Находим все вакансии с соискателем
        $arVacs = Vacancy::getVacsForChats($user,true,$bArchive);
        // если вакансий нет - финал
        if(!count($arVacs['id']))
            return $arRes;
        // формируем массив вакансии
        $arV = $arVacs['key-id'];

        // Находим сообщения общих чатов
        $arPublic = VacDiscuss::getChatsByVacs($arVacs['id'],$user);
        // переносим в вакансии общие чаты
        foreach ($arPublic as $v)
        {
            $arV[$v['id_vac']]['public-mess'][] = $v['id']; //общий чат вакансии
            $arV[$v['id_vac']]['cnt-mess']++; // счетчик общих чатов вакансии
            $arRes['cnt-mess']++; // общий счетчик общих чатов
            if(!$v['readed']) // подсчет непрочитанных
            {
                $arV[$v['id_vac']]['cnt-public-noread']++;
                $arV[$v['id_vac']]['cnt-mess-noread']++;
                $arRes['cnt-mess-noread']++;
            }
        }

        // Находим сообщения личных чатов
        $arPrivate = $this->getChatsByVacs($arVacs['id']);
        // переносим в вакансии личные чаты

        foreach ($arPrivate as $v)
        {
            $id = $v['id_vac'];
            $idus = $this->isApp ? $v['id_use'] : $v['id_usp'];

            $arV[$id]['personal-id'][$v['id_theme']] = $v['id_theme'];

            $arC = $arV[$id]['personal-chat'][$idus];
            $arC['id'][] = $v['id'];
            $arC['user'] = $idus;
            !isset($arC['noread']) && $arC['noread'] = 0;
            if(!$v['is_read'])
            {
                if($v['is_resp'] == $this->isApp) // разный результат для разного типа юзера
                {
                    $arV[$id]['cnt-mess-noread']++;
                    $arRes['cnt-mess-noread']++;
                    $arC['noread']++;
                }
            }
            $arV[$id]['personal-chat'][$idus] = $arC;
            $arV[$id]['cnt-mess']++; //
            $arRes['cnt-mess']++; // общий счетчик чатов
        }

        // дорабатываем массив вакансий
        foreach ($arV as $id => $v)
        {
            count($v['public-mess']) && $arRes['cnt-chat']++; // общий чат считаем один раз
            $arRes['cnt-chat'] += count($v['personal-chat']);            
        }
        
        $nV = count($arVacs['id']);
        $arRes['pages'] = new CPagination($nV); // цепляем пагинацию
        $arRes['pages']->pageSize = $this->limit;
        $arRes['pages']->applyLimit($this);
        // ограничиваем
        for($i=$this->offset; $i<$nV; $i++)
            if($i<($this->offset+$this->limit))
            {
                $arRes['items'][$arVacs['id'][$i]] = $arV[$arVacs['id'][$i]];
            }

        $arVacs['users'][] = $user->id; // добавляем в поиск себя

        // находим данные по всем юзерам
        $arRes['users'] = Share::getUsers($arVacs['users']);

        return $arRes;
    }
    /**
     *      проверка доступа к чату
     */
    public function hasAccess($chat, $id, $vacancy=0)
    {
        $user = $this->Profile;

        if(!in_array($user->type,[2,3]) || !intval($id))
            return false;

        if($chat==='feedback')
        {
            // просто проверяем наличие чата
            $filter = 'id=:id AND ' . ($this->isApp ? 'id_usp' : 'id_use') . '=:idus';
            $params = array(':id'=>$id, ':idus'=>$user->id);
            $sql = Yii::app()->db->createCommand()
                    ->select('id')
                    ->from('chat_theme')
                    ->where($filter,$params)
                    ->queryRow();

            return isset($sql['id']);
        }
        if($chat==='vacancy')
        {
            if(!intval($vacancy))
                return false;
            // проверка подтвержденного юзера
            $filter = 'vs.id_vac=:id AND r.id_user=:idus AND vs.status>4';
            $params = array(
                        ':id'=>$vacancy,
                        ':idus'=>($this->isApp ? $user->id : $id)
                    );
            $sql = Yii::app()->db->createCommand()
                    ->select('vs.id')
                    ->from('vacation_stat vs')
                    ->leftjoin('resume r', 'r.id=vs.id_promo')
                    ->where($filter, $params)
                    ->queryRow();

            if(!isset($sql['id']))
                return false;

            // проверка владельца вакансии
            $filter = 'id=:id AND id_user=:id_emp';
            $params = array(
                        ':id'=>$vacancy,
                        ':id_emp'=>($this->isApp ? $id : $user->id)
                    );
            $sql = Yii::app()->db->createCommand()
                    ->select('id, title')
                    ->from('empl_vacations')
                    ->where($filter, $params)
                    ->queryRow();

            if(!isset($sql['id']))
                return false;

        }
        return true;
    }
    /**
     *      Формирование правильного массива
     */
    public function buildMessArray($arr)
    {
        $arUId = array();
        foreach ($arr as $k => $v)
        {
            $arUId[] = $v['idusp'];
            $arUId[] = $v['iduse'];
            $arr[$k]['date'] = Share::getPrettyDate($v['crdate'],$v['crtime']);
        }

        $arUsers = Share::getUsers($arUId);

        foreach ($arr as $k => $v)
        {
            $to = $this->isApp ? $v['idusp'] : $v['iduse'];
            $from = $this->isApp ? $v['iduse'] : $v['idusp'];

            $arr[$k]['nameto'] = $arUsers[$to]['name'];
            $arr[$k]['phototo'] = $arUsers[$to]['src'];
            $arr[$k]['namefrom'] = $arUsers[$from]['name'];
            $arr[$k]['photofrom'] = $arUsers[$from]['src'];
        }

        return $arr;
    }
    /**
     * Подготавливаем файлы сообщения
     */
    protected function prepareFiles($data)
    {
        $data = array_map(
                function ($v){
                    if($v['files'])
                    {
                        $v['files'] = get_object_vars(json_decode($v['files']));
                        foreach ($v['files'] as $key => &$val) {
                            $val->files = array_map(function ($v2) {
                                return str_replace('/protected', '', $v2);
                            }, get_object_vars($val->files));
                        }
                        unset($val);
                    }
                    return $v;
                }, 
                $data
            );
        return $data;
    }
    /**
     * Перемещаем прикрепленные файлы
     */
    protected function moveUploadedFiles($inFiles)
    {
        foreach ($inFiles as $key => $val)
        {
            $movedFiles[$key] = $val;
            foreach ($movedFiles[$key]['files'] as $key2 => &$val2)
            {
                // задаём новые значения путей
                $val2 = str_replace('tmp/', '', $val2);
                // перемещаем файлы в рабочую директорию
                if( file_exists(MainConfig::$DOC_ROOT . $inFiles[$key]['files'][$key2]) )
                {
                    copy(MainConfig::$DOC_ROOT . $inFiles[$key]['files'][$key2], MainConfig::$DOC_ROOT . $val2);
                    unlink(MainConfig::$DOC_ROOT . $inFiles[$key]['files'][$key2]);
                } // endif
            } // end foreach
            unset($val2);
        } // end foreach
        return $movedFiles;
    }
    /**
     *      Ответ админа в чате
     */
    public function recordAdminMessage($id, $message, $idus)
    {
        Yii::app()->db->createCommand()
            ->insert('chat', array(
                'id_theme' => $id,
                'id_usp' => $this->isApp ? $idus : $this->adminId,
                'id_use' => $this->isApp ? $this->adminId : $idus,
                'message' => $message,
                'is_resp' => $this->isApp,
                'is_read' => 0,
                'crdate' => date("Y-m-d H:i:s"),
            ));
    }
    /**
     * @param $id_user int id_user
     * @param $type int user type (2,3)
     * @param $title string
     * @param $message string
     */
    public static function setMessageFromBot($id_user, $type, $title, $message)
    {
        $db = Yii::app()->db;
        $isApp = Share::isApplicant($type);
        $id_usp = $isApp ? $id_user : self::$ADMIN_APPLICANT;
        $id_use = $isApp ? self::$ADMIN_EMPLOYER : $id_user;
        $filter = $isApp 
            ? 'id_vac=0 AND id_usp=:id AND id_use=' . self::$ADMIN_EMPLOYER 
            : 'id_vac=0 AND id_use=:id AND id_usp=' . self::$ADMIN_APPLICANT;
        $chatId = $db->createCommand()
                    ->select("id")
                    ->from('chat_theme')
                    ->where($filter,[':id'=>$id_user])
                    ->order('id desc')
                    ->queryScalar();

        if($chatId>0) // чат уже создавался
        {
            $db->createCommand()->update(
                    'chat_theme',
                    ['title' => $title],
                    'id=:id',
                    [':id' => $chatId]
                );
            $db->createCommand()->update(
                    'feedback',
                    ['theme' => $title],
                    'chat=:id',
                    [':id' => $chatId]
                );
        }
        else // создаем новый чат
        {
            $db->createCommand()->insert(
                    'chat_theme',
                    ['id_usp'=>$id_usp,'id_use'=>$id_use,'title'=>$title]
                );

            $chatId = $db->createCommand()
                        ->select("id")
                        ->from('chat_theme')
                        ->order('id desc')
                        ->queryScalar();
        }

        $db->createCommand()->insert('chat', 
                [
                    'id_theme' => $chatId,
                    'id_usp' => $id_usp,
                    'id_use' => $id_use,
                    'message' => $message,
                    'is_resp' => $isApp,
                    'is_read' => 0,
                    'crdate' => date("Y-m-d H:i:s"),
                ]
            );
    }
    /**
     * 
     */  
    public static function sendEmailNotifications()
    {
        //$endDate = mktime(0,0,0);
        $endDate = date('Y-m-d H:i:s'); // сейчас
        $begDate = strtotime($endDate) - (60*60*24); // вчера
        $begDate = date('Y-m-d H:i:s',$begDate);
        // ищем юзеров с непрочитанными сообщениями личных чатов
        $query = Yii::app()->db->createCommand()
                    ->select('id_use, id_usp, is_resp')
                    ->from('chat')
                    ->where(
                        'crdate BETWEEN :bdate AND :edate AND is_read=0',
                        [':bdate'=>$begDate,':edate'=>$endDate]
                    )
                    ->queryAll();

        $arUserId = array();
        foreach ($query as $v) // если ответ Р - то письмо С и наоборот
            $arUserId[] = $v['is_resp'] ? $v['id_usp'] : $v['id_use']; 
           
        // ищем сообщения по общим чатам
        $query = Yii::app()->db->createCommand()
                    ->select('ed.id_vac, edr.id_user')
                    ->from('emplv_discuss_readed edr')
                    ->leftjoin('emplv_discuss ed','edr.id_message=ed.id')
                    ->where(
                        'crdate BETWEEN :bdate AND :edate',
                        [':bdate'=>$begDate,':edate'=>$endDate]
                    )
                    ->queryAll();        

        $arVacId = array();
        $arUserReaded = array();
        foreach ($query as $v)
        {
            $arVacId[] = $v['id_vac'];
            $arUserReaded[$v['id_vac']][] = $v['id_user'];
        }
        $arVacId = array_unique($arVacId);
 
        if(count($arVacId)) // если есть смысл искать по общим чатам юзеров
        {
            // ищем инфу по вакансиям и утвержденному персоналу
            $query = Yii::app()->db->createCommand()
                        ->select("vs.id_vac, r.id_user")
                        ->from('vacation_stat vs')
                        ->leftjoin('resume r', 'r.id=vs.id_promo')
                        ->where([
                            'and',
                            'vs.status>4',
                            ['in','vs.id_vac',$arVacId]]
                        )
                        ->queryAll();

            foreach ($query as $v) // собираем только тех, кто не читал
                if(!in_array($v['id_user'], $arUserReaded[$v['id_vac']]))
                    $arUserId[] = $v['id_user'];
            // ищем владельцев вакансий
            $query = Yii::app()->db->createCommand()
                        ->select("id id_vac, id_user")
                        ->from('empl_vacations')
                        ->where(['in','id',$arVacId])
                        ->queryAll();

            foreach ($query as $v) // собираем только тех, кто не читал
                if(!in_array($v['id_user'], $arUserReaded[$v['id_vac']]))
                    $arUserId[] = $v['id_user'];        
        }
        if(count($arUserId))
        {
            $arUserId = array_unique($arUserId);
            $query = Yii::app()->db->createCommand()
                        ->select("u.email,
                            CONCAT(r.firstname,' ',r.lastname) app_name,
                            CONCAT(e.firstname,' ',e.lastname) emp_name,
                            u.status")
                        ->from('user u')
                        ->leftjoin('resume r','r.id_user=u.id_user')
                        ->leftjoin('employer e','e.id_user=u.id_user')
                        ->where(array('in','u.id_user',$arUserId))
                        ->queryAll();

            $arParams = array();
            foreach ($query as $v)
            {
                // set email
                $email = trim($v['email']);
                if(filter_var($email,FILTER_VALIDATE_EMAIL))
                    $arParams['email_user'] = $email;
                if(!isset($arParams['email_user']))
                    continue;
                // set name
                if($v['status']==2) // applicant
                    $arParams['name_user'] = trim($v['app_name']);
                else // employer
                    $arParams['name_user'] = trim($v['emp_name']);  
                if(empty($arParams['name_user']))
                    $arParams['name_user'] = 'пользователь';

                Mailing::set(2, $arParams);
            }
        }
    }
}