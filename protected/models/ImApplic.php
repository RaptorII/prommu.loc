<?php

/**
 * Date: 22.04.2016
 *
 * Модель переписки
 */
class ImApplic extends Im
{
    public $adminId = 1766; // id Админа

    public function getChats()
    {
        $limit = 5;
        $offset = $this->offset;
        $id = $this->Profile->id;

        $sql = "SELECT ct.id, ct.id_vac, ct.title, e.title etitle
            , DATE_FORMAT(ca.crdate, '%d.%m.%Y') crfdate
            , DATE_FORMAT(ca2.crdate, '%d.%m.%Y') crldate
            , ca2.crdate crldatetime
            , cou.count
            , coun.countn
            , em.name, em.logo, em.firstname nnn, em.lastname fff
                        FROM chat_theme ct
                        LEFT JOIN empl_vacations e ON e.id = ct.id_vac
                        LEFT JOIN chat ca ON ca.id_theme = ct.id AND ca.id = 
                            (SELECT MIN(id) FROM chat c1 WHERE c1.id_theme = ct.id)
                        LEFT JOIN chat ca2 ON ca2.id_theme = ct.id AND ca2.id = 
                            (SELECT MAX(id) FROM chat c2 WHERE c2.id_theme = ct.id)
                        INNER JOIN (
                            SELECT ca.id_theme, COUNT(*) count FROM chat ca GROUP BY ca.id_theme
                        ) cou ON cou.id_theme = ct.id
                        LEFT JOIN (
                            SELECT ca.id_theme, COUNT(*) countn FROM chat ca WHERE ca.is_read = 0 AND ca.is_resp = 1 GROUP BY ca.id_theme 
                        ) coun ON coun.id_theme = ct.id
                        INNER JOIN employer em ON em.id_user = ct.id_use
            WHERE ct.id_usp = {$id}
            ORDER BY crldatetime DESC
            LIMIT {$offset}, {$limit}";
        /** @var $res CDbCommand */
        $res = Yii::app()->db->createCommand($sql);
        $data['chats'] = $res->queryAll();

        $data['count'] = $this->getChatsCount($id);

        return $data;
    }

    public function accessMessage($idto)
    {

        $result['user'] = Yii::app()->db->createCommand()
            ->select("u.ismoder,u.isblocked")
            ->from('user u')
            ->where('u.id_user=:st', array(':st' => Share::$UserProfile->id))
            ->queryRow();

        if ($idto) {
            $result['new'] = Yii::app()->db->createCommand()
                ->select("e.id")
                ->from('employer e')
                ->where('e.id=:st', array(':st' => $idto))
                ->queryRow();
        }

        return $result;
    }

    public function getMessCount($inIdTm, $inUsId)
    {
        $sql = "SELECT COUNT(*) cou
                FROM chat ca
                LEFT JOIN employer e ON e.id_user = ca.id_use
                LEFT JOIN resume r ON r.id_user = ca.id_usp
                WHERE ca.id_theme = {$inIdTm}
                AND ca.id_usp = {$inUsId}";
        /** @var $res CDbCommand */
        $res = Yii::app()->db->createCommand($sql);
        return $res->queryScalar();
    }

    /**
     * Получаем колво чатов пользователя
     */
    public function getChatsCount($inUsId)
    {
        $sql = "SELECT COUNT(*) count FROM chat_theme WHERE id_usp = {$inUsId}";
        /** @var $res CDbCommand */
        $res = Yii::app()->db->createCommand($sql);

        return $res->queryScalar();
    }


    /**
     * Получаем данные для страницы переписки
     */
    public function getMessViewData($inChatId = 0, $inIdTo = 0)
    {
        if ($inChatId || $inIdTo) {
            // вакансии по которым есть отклики для новой темы
            if ($inIdTo) {
                $idpromo = Share::$UserProfile->exInfo->id_resume;
                $sql = "SELECT e.id, e.title
                    FROM empl_vacations e
                    INNER JOIN vacation_stat v ON v.id_vac = e.id AND v.isresponse = 1 AND v.id_promo = {$idpromo}
                    INNER JOIN employer em ON e.id_user = em.id_user AND em.id_user = {$inIdTo}";
                /** @var $res CDbCommand */
                $res = Yii::app()->db->createCommand($sql);
                $res = $res->queryAll();

                return array('themes' => $res);
            } // endif


            // заголовок чата
            if ($inChatId) {
                $sql = "SELECT DISTINCT  ct.id, ct.title, e.title vactitle, ca.id_usp idusp, ca.id_use iduse
                        FROM chat_theme ct
                        LEFT JOIN empl_vacations e ON e.id = ct.id_vac
                        LEFT JOIN chat ca ON ca.id_theme = ct.id 
                        WHERE ct.id = {$inChatId}";
                /** @var $res CDbCommand */
                $res = Yii::app()->db->createCommand($sql);
                $res = $res->queryRow();

                if ($res['idusp'] == Share::$UserProfile->exInfo->id) {
                    $data['title'] = $res;
                } else return array('error' => 100); // чужой диалог
            } else {
            } // endif


            // получаем файлы диалога
            $Upluni = new Uploaduni();
            $Upluni->setCustomOptions(array(
                    'type' => array('images' => 'image/jpeg,image/png', 'files' => 'word,excel,spreadsheetml'),
                    'scope' => 'im',
                    'maxFS' => 5242880, // max filesize
                    'maxImgDim' => array(2500, 2500),
                    'removeProtected' => true,
                    'sizes' => array('isorig' => true, 'dims' => [], 'thumb' => 150),
                    'tmpDir' => "/" . MainConfig::$PATH_CONTENT_PROTECTED . "/im/tmp",
                )
            );
            $files = $Upluni->init();

            // берем только файлы этого диалога
            $themeFiles = array();
            foreach ($files['files'] ?: array() as $key => $val) {
                if ($val['extmeta']->idTheme == $inChatId) {
                    $val['files'] = array_map(function ($v) {
                        return str_replace('/protected', '', $v);
                    }, $val['files']);
                    $themeFiles[$key] = $val;
                }
            }
            $data = array_merge($data, array('files' => $themeFiles));

            // set chat data
            Yii::app()->session['imdata'] = array('chatId' => $inChatId);

            return $data;
        } else {
            return array('error' => 1, 'message' => 'Диалог не найден');
        } // endif
    }

    /**
     * Отправляем сообщение пользователя
     */
    public function sendUserMessages($inProps = [])
    {
        //сообщение
        $message = $inProps['message'] ?: filter_var(Yii::app()->getRequest()->getParam('m', 0), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        //id чата
        $idTm = $inProps['idTm'] ?: filter_var(Yii::app()->getRequest()->getParam('tm', 0), FILTER_SANITIZE_NUMBER_INT);
        //id последнего сообщения
        $lastMessId = $inProps['lastMessId'] ?: filter_var(Yii::app()->getRequest()->getParam('l', 0), FILTER_SANITIZE_NUMBER_INT);
        //новый чат
        $new = $inProps['new'] ?: filter_var(Yii::app()->getRequest()->getParam('new', 0), FILTER_SANITIZE_NUMBER_INT);
        //id вакансии
        $vid = $inProps['vid'] ?: filter_var(Yii::app()->getRequest()->getParam('vid', 0), FILTER_SANITIZE_NUMBER_INT);
        //nt
        $theme = $inProps['theme'] ?: filter_var(Yii::app()->getRequest()->getParam('t'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $Profile = $inProps['profile'] ?: $this->Profile ?: Share::$UserProfile;
        $id = $inProps['idus'] ?: $Profile->id;
        // добавляем новый чат
        if ($new > 0) {
            $iduse = $new;


            $props = array(
                'id_usp' => $id,
                'id_use' => $iduse,
            );
            if ($theme) $props['title'] = $theme;
            elseif ($vid) $props['id_vac'] = $vid;

            $res = Yii::app()->db->createCommand()
                ->insert('chat_theme', $props);

            $idTm = Yii::app()->db->createCommand('SELECT LAST_INSERT_ID()')->queryScalar();

            $lastMessId = 0;

            // получаем тему из вакансии
            if ($vid) {
                $sql = "SELECT e.title FROM empl_vacations e WHERE e.id = {$vid}";
                /** @var $res CDbCommand */
                $res = Yii::app()->db->createCommand($sql);
                $theme = $res->queryScalar();
            }
        } else {
        } // endif

        // проверяем диалог на владельца
        if ($new || $ids = $this->isMyChat($idTm)) {
            $message = trim(preg_replace('/&lt;([\/]?(?:div|b|i|br|u))&gt;/i', "<$1>", $message));
            if (true) {
                $uploaduni = new Uploaduni();
                $files = $uploaduni->getUploadedFiles(array('scope' => 'im'));

                // берем только файлы этого диалога
                if ($files) {
                    foreach ($files as $key => $val) {
                        if ($val['extmeta']->idTheme == $idTm) $themeFiles[$key] = $val;
                    } // end foreach

                    // перемещаем файлы
                    $themeFiles = $themeFiles ? json_encode($this->moveUploadedFiles($themeFiles)) : '';
                    $themeFiles && $uploaduni->removeUnExistedFiles(array('scope' => 'im'));
                } // endif


                $res = Yii::app()->db->createCommand()
                    ->insert('chat', array(
                        'id_theme' => $idTm,
                        'id_usp' => $id,
                        'id_use' => $ids['iduse'] ?: $iduse,
                        'message' => $message,
                        'is_resp' => 0,
                        'is_read' => 0,
                        'files' => $themeFiles,
                        'crdate' => date("Y-m-d H:i:s"),
                    ));

                $mailCloud = new MailCloud();
                $mailCloud->mailerMess($ids['iduse'], $idTm, 2);

            }
            // endif
            $ids = $ids['iduse'] ?: $iduse;
            file_get_contents("https://prommu.com/api.mailer/?id=$ids&type=3&method=mess");

            $sql = "SELECT r.new_mess
            FROM push_config r
            WHERE r.id = {$ids}";
            $res = Yii::app()->db->createCommand($sql)->queryScalar();
            if ($res == 2) {
                $sql = "SELECT r.push
            FROM user_push r
            WHERE r.id = {$ids}";
                $res = Yii::app()->db->createCommand($sql)->queryRow();


                if ($res) {
                    $type = "mess";
                    $api = new Api();
                    $api->getPush($res['push'], $type);

                }
            }
            // оповещение по Телеграм
            if ($id == 2054 || $ids == 1766) { // 2054,1766 - ID администратора
                $mess = '"' . (isset($inProps['original']) ? $inProps['original'] : strip_tags($message)) . '"';
                $text = "Зарегистрированный пользователь обратился по обратной связи: $mess. Необходима модерация https://prommu.com/admin/site/update/$idTm";
                $sendto = "https://api.telegram.org/bot525649107:AAFWUj7O8t6V-GGt3ldzP3QBEuZOzOz-ij8/sendMessage?chat_id=@prommubag&text=$text";
                file_get_contents($sendto);
            }

            return array_merge($this->getNewMessData($idTm, $lastMessId), array('theme' => $theme, 'idtm' => $idTm));
        } else {
            // не мой
            return array('error' => 100);
        } // endif

    }


    /**
     * Получаем кол-во сообщений пользователя
     */
    public function getMessagesCount($inIdTm)
    {
        $sql = "SELECT COUNT(*) cou
                FROM chat ca
                LEFT JOIN employer e ON e.id_user = ca.id_use
                LEFT JOIN resume r ON r.id_user = ca.id_usp
                WHERE ca.id_theme = {$inIdTm}";
        /** @var $res CDbCommand */
        $res = Yii::app()->db->createCommand($sql);
        return array_reverse($res->queryRow());
    }


    /**
     * Получаем сообщения пользователя
     */
    public function getUserMessages()
    {
        $firstId = filter_var(Yii::app()->getRequest()->getParam('fid', 0), FILTER_SANITIZE_NUMBER_INT);
        $idTm = filter_var(Yii::app()->getRequest()->getParam('tm', 0), FILTER_SANITIZE_NUMBER_INT);

        if ($idTm) {
            // последние сообщения
            if (!$firstId) {
                $limit = 15;
                $filter = '';

                // предыдущие сообщения
            } else {
                $limit = 100;
                $filter = ' AND ca.id < ' . $firstId;
            } // endif


            // получаем сообщения
            $sql = "SELECT ca.id, ca.message, ca.is_resp isresp, ca.is_read isread, ca.id_usp idusp, ca.id_use iduse, ca.files
                  , e.name namefrom
                  , CONCAT(r.firstname, ' ', r.lastname) nameto
                  , DATE_FORMAT(ca.crdate, '%d.%m.%Y') crdate, DATE_FORMAT(ca.crdate, '%H:%i:%s') crtime
                FROM chat ca 
                LEFT JOIN employer e ON e.id_user = ca.id_use
                LEFT JOIN resume r ON r.id_user = ca.id_usp
                WHERE ca.id_theme = {$idTm}
                  {$filter}
                ORDER BY id DESC
                LIMIT {$limit}";
            /** @var $res CDbCommand */
            $res = Yii::app()->db->createCommand($sql);
            $data = ($res->queryAll());


            // обработать файлы
            $data = $this->prepareFiles($data);


            // есть ли еще сообщения
            $sql = "SELECT COUNT(*) cou
                    FROM chat ca
                    WHERE id_theme = {$idTm} AND ca.id < " . $data[count($data) - 1]['id'];
            /** @var $res CDbCommand */
            $res = Yii::app()->db->createCommand($sql);
            $res = $res->queryRow();


            if ($data[0]['idusp'] == Share::$UserProfile->exInfo->id) {
                return array('data' => $data, 'count' => $res['cou']);
            } else {
                return array('error' => 100);
            } // endif

        } // endif
    }


    // получение новых сообщений
    public function getNewMessages()
    {
        $idTm = filter_var(Yii::app()->getRequest()->getParam('tm', 0), FILTER_SANITIZE_NUMBER_INT);
        $lastMessId = filter_var(Yii::app()->getRequest()->getParam('l', 0), FILTER_SANITIZE_NUMBER_INT);

        return $this->getNewMessData($idTm, $lastMessId);
    }


    private function getNewMessData($inIdTm, $lastId)
    {
        // выбираем новые
        $sql = "SELECT ca.id, ca.message, ca.is_resp isresp, ca.is_read isread, ca.id_usp idusp, ca.id_use iduse, ca.files
                  , e.name namefrom
                  , CONCAT(r.firstname, ' ', r.lastname) nameto
                  , DATE_FORMAT(ca.crdate, '%d.%m.%Y') crdate, DATE_FORMAT(ca.crdate, '%H:%i:%s') crtime
                FROM chat ca
                LEFT JOIN employer e ON e.id_user = ca.id_use
                LEFT JOIN resume r ON r.id_user = ca.id_usp
                WHERE ca.id_theme = {$inIdTm}
                  AND ca.id > {$lastId}";
        /** @var $res CDbCommand */
        $res = Yii::app()->db->createCommand($sql);
        $data['messages'] = $res->queryAll();


        // обработать файлы
        $data['messages'] = $this->prepareFiles($data['messages']);


        // помечаем прочитанными
        $res = Yii::app()->db->createCommand()
            ->update('chat', array(
                'is_read' => 1,
            ), 'id_theme = :id_theme AND is_resp = 1 AND is_read = 0', array(':id_theme' => $inIdTm));


        return $data;
    }


    private function isMyChat($inIdTm)
    {
        $sql = "SELECT ca.id_usp idusp, ca.id_use iduse
                FROM chat ca 
                LEFT JOIN employer e ON e.id_user = ca.id_use
                LEFT JOIN resume r ON r.id_user = ca.id_usp
                WHERE ca.id_theme = {$inIdTm}
                LIMIT 1";
        /** @var $res CDbCommand */
        $res = Yii::app()->db->createCommand($sql)->queryRow();

        return Share::$UserProfile->id == $res['idusp'] ? $res : $res;
    }


    /**
     * Перемещаем прикрепленные файлы
     */
    private function moveUploadedFiles($inFiles)
    {
        foreach ($inFiles as $key => $val) {
            $movedFiles[$key] = $val;
            foreach ($movedFiles[$key]['files'] as $key2 => &$val2) {
                // задаём новые значения путей
                $val2 = str_replace('tmp/', '', $val2);
                // перемещаем файлы в рабочую директорию
                if (file_exists(MainConfig::$DOC_ROOT . $inFiles[$key]['files'][$key2])) {
                    copy(MainConfig::$DOC_ROOT . $inFiles[$key]['files'][$key2], MainConfig::$DOC_ROOT . $val2);
                    unlink(MainConfig::$DOC_ROOT . $inFiles[$key]['files'][$key2]);
                } // endif
            } // end foreach
        } // end foreach

        return $movedFiles;
    }


    /**
     * Подготавливаем файлы сообщения
     */
    private function prepareFiles($data)
    {
        $data = array_map(function ($v) {
            if ($v['files']) {
                $v['files'] = get_object_vars(json_decode($v['files']));
                foreach ($v['files'] as $key => &$val) {
                    $val->files = array_map(function ($v2) {
                        return str_replace('/protected', '', $v2);
                    }, get_object_vars($val->files));
                } // end foreach
            }
            return $v;
        }, $data);

        return $data;
    }
    /**
     *      Ответ админа в чате
     */
    public function recordAdminMessage($id, $arr)
    {
        $res = Yii::app()->db->createCommand()
                    ->insert('chat', array(
                        'id_theme' => $id,
                        'id_usp' => $this->$adminId,
                        'id_use' => $arr['user-id'],
                        'message' => $arr['message'],
                        'is_resp' => 0,
                        'is_read' => 0,
                        'crdate' => date("Y-m-d H:i:s"),
                    )); 
    }
    /**
     *      страница всех чатов
     */
    public function getAllChats()
    {
        $idus = Share::$UserProfile->id;
        $id_resume = Share::$UserProfile->exInfo->id_resume;

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
        $sql = Yii::app()->db->createCommand()
                ->select("ct.id")
                ->from('chat_theme ct')
                ->where(
                    'ct.id_usp=:id AND ct.id_vac=0 AND ct.id_use=:adm',
                    array(':id'=>$idus,':adm'=>$this->adminId)
                )
                ->order('ct.id desc')
                ->queryAll();

        if(count($sql))
        {
            $arRes['cnt'] = count($sql);
            $arId = array();
            foreach ($sql as $v)
                $arId[] = $v['id'];

            $sql = Yii::app()->db->createCommand()
                            ->select("*")
                            ->from('chat')
                            ->where(array('in','id_theme',$arId))
                            ->queryAll(); 

            foreach ($sql as $v)
            {
                $arRes['feedback']['cnt-mess']++;
                if(!isset($arRes['feedback']['cnt-noread']))
                    $arRes['feedback']['cnt-noread'] = 0;

                if(!$v['is_resp'] && !$v['is_read'])
                    $arRes['feedback']['cnt-noread']++;
            }
        }
        //
        //
        // чат вакансий
        $arV = $arVId = $arPid = array();
        // находим все по вакансиям
        $sql = Yii::app()->db->createCommand()
                ->select("ev.id, ed.id id_public, ct.id id_personal, vs.id_promo")
                ->from('empl_vacations ev')
                ->leftjoin('emplv_discuss ed','ed.id_vac=ev.id')
                ->leftjoin('chat_theme ct','ct.id_vac=ev.id')
                ->leftjoin(
                    'vacation_stat vs',
                    'vs.id_vac=ev.id AND vs.status>4'
                )
                ->where('vs.id_promo=:id',array(':id'=>$id_resume))
                ->queryAll();

        if(!count($sql))
            return $arRes;

        foreach ($sql as $v) {
            $id = $v['id'];
            $arV[$id]['id'] = $id;
            if(!empty($v['id_promo']) && !in_array($v['id_promo'], $arPid))
                $arPid[] = $v['id_promo'];
            !in_array($v['id'], $arVId) && array_push($arVId, $v['id']);

            if(!empty($v['id_public']))
            {
                $arV[$id]['public-mess'][] = $v['id_public'];
                $arRes['vacancies']['cnt-mess']++;
            }
                
            if(!empty($v['id_personal']) && !in_array($v['id_personal'], $arV[$id]['personal-chat']))
                $arV[$id]['personal-chat'][] = $v['id_personal'];
        }

        foreach ($arV as $v) {
            if(count($v['public-mess']))
                $arRes['cnt']++;

            $arRes['cnt'] += count($v['personal-chat']);
        }

        // поиск личных чатов
        $sql = Yii::app()->db->createCommand()
                ->select("ct.id_vac, c.*")
                ->from('chat c')
                ->leftjoin('chat_theme ct', 'c.id_theme=ct.id')
                ->where(array('in','ct.id_vac',$arVId))
                ->queryAll();

        foreach ($sql as $v) {
            $arRes['vacancies']['cnt-mess']++;

          if(!in_array($v['id_usp'], $arPid))
              $arPid[] = $v['id_usp'];

          if(!$v['is_resp'] && !$v['is_read'])
              $arRes['vacancies']['cnt-noread']++;
        }
        $arRes['vacancies']['cnt-users'] = count($arPid);

        return $arRes;
    }
    /**
     *      список чатов с админом
     */
    public function getFeedbackChats()
    {
        $arRes = array();
        $this->limit = 30;
        $idus = Share::$UserProfile->id;
        $arT = Yii::app()->db->createCommand()
                ->select("
                    ct.id, 
                    ct.id_use, 
                    ct.title, 
                    f.theme, 
                    f.direct")
                ->from('chat_theme ct')
                ->leftjoin('feedback f', 'f.chat=ct.id')
                ->where(
                    'ct.id_usp=:id AND ct.id_vac=0 AND ct.id_use=:adm',
                    array(':id'=>$idus,':adm'=>$this->adminId)
                )
                ->order('ct.id desc')
                ->queryAll();

        $nT = sizeof($arT);
        if(!$nT)
            return $arRes;

        $arRes['cnt'] = $nT;
        $arRes['pages'] = new CPagination($nT);
        $arRes['pages']->pageSize = $this->limit;
        $arRes['pages']->applyLimit($this);

        $arId = $arIdus = array();
        for($i=$this->offset; $i<$nT; $i++)
            if($i<($this->offset+$this->limit))
            {
                $arId[] = $arT[$i]['id'];
                $arRes['items'][$arT[$i]['id']] = [
                        'id' => $arT[$i]['id'],
                        'direct' => ($arT[$i]['direct'] ?: 1),
                        'user' => $arT[$i]['id_use']
                    ];
                $arIdus[] = $arT[$i]['id_use'];

                $title = 'Без названия';
                if(!empty($arT[$i]['title']))
                    $title = $arT[$i]['title'];
                if(!empty($arT[$i]['theme']))
                    $title = $arT[$i]['theme'];

                $arRes['items'][$arT[$i]['id']]['title'] = $title;
            }

        $sql = Yii::app()->db->createCommand()
                        ->select("*")
                        ->from('chat')
                        ->where(array('in','id_theme',$arId))
                        ->queryAll(); 

        foreach ($sql as $m)
        {
            $id = $m['id_theme'];
            $arRes['items'][$id]['cnt-mess']++;
            !isset($arRes['items'][$id]['cnt-noread']) && $arRes['items'][$id]['cnt-noread']=0;
            if($m['is_resp'] && !$m['is_read']) // ответ Р и не прочитано
                $arRes['items'][$id]['cnt-noread']++;

            if(!empty($m['title']))
                $arRes['items'][$id]['title'] = $m['title'];
            if(!empty($m['theme']))
                $arRes['items'][$id]['title'] = $m['theme'];
        }

        $arRes['users'] = Share::getUsers($arIdus);

        return $arRes;
    }
    /**
     *      список чатов по вакансиям
     */
    public function getVacanciesChats()
    {
        $id_resume = Share::$UserProfile->exInfo->id_resume;
        $arRes = $arV = $arVId = $arUId = $arVIdSelect = array();
        $this->limit = 10; // вывод 10 вакансий на странице
        // поиск вакансий

        // находим все по вакансиям
        $sql = Yii::app()->db->createCommand()
                ->select("
                    ev.id,
                    ev.title, 
                    ev.remdate,
                    ev.id_user employer,
                    ed.id id_public, 
                    ct.id id_personal")
                ->from('empl_vacations ev')
                ->leftjoin('emplv_discuss ed','ed.id_vac=ev.id')
                ->leftjoin('chat_theme ct','ct.id_vac=ev.id')
                ->leftjoin(
                    'vacation_stat vs',
                    'vs.id_vac=ev.id AND vs.status>4'
                )
                ->where('vs.id_promo=:id',array(':id'=>$id_resume))
                ->order('ev.remdate desc, ev.id desc')
                ->queryAll();

        if(!count($sql))
            return $arRes;

        $arRes['cnt'] = 0;
        foreach ($sql as $v) {
            $id = $v['id'];
            $arV[$id]['id'] = $id;
            $arV[$id]['title'] = $v['title'];
            $arV[$id]['remdate'] = date('d.m.Y',strtotime($v['remdate']));
            $arV[$id]['isactive'] = strtotime(date('Y-m-d')) < strtotime($v['remdate']);
            if(!empty($v['employer']) && !in_array($v['employer'], $arV[$id]['users']))
                $arV[$id]['users'][] = $v['employer'];
            !in_array($id, $arVId) && array_push($arVId, $id);
            if(!empty($v['id_public']))
                $arV[$id]['public-mess'][] = $v['id_public'];
            if(!empty($v['id_personal']) && !in_array($v['id_personal'], $arV[$id]['personal-id']))
                $arV[$id]['personal-id'][] = $v['id_personal'];
        }
        $nV = count($arVId);
        foreach ($arV as $v) {
            if(count($v['public-mess']))
                $arRes['cnt']++;

            $arRes['cnt'] += count($v['personal-id']);
        }

        $arRes['pages'] = new CPagination($nV);
        $arRes['pages']->pageSize = $this->limit;
        $arRes['pages']->applyLimit($this);

        for($i=$this->offset; $i<$nV; $i++)
            if($i<($this->offset+$this->limit))
            {
                $arVIdSelect[] = $arVId[$i];
                if(count($arV[$arVId[$i]]['users']))
                    $arUId = array_merge($arUId, $arV[$arVId[$i]]['users']);

                $arRes['items'][$arVId[$i]] = $arV[$arVId[$i]];
            }

        // поиск личных чатов
        $sql = Yii::app()->db->createCommand()
                ->select("ct.id_vac, c.*")
                ->from('chat c')
                ->leftjoin('chat_theme ct', 'c.id_theme=ct.id')
                ->where(array('in','ct.id_vac',$arVIdSelect))
                ->queryAll();

        
        foreach ($sql as $v) {
            $arC = $arRes['items'][$v['id_vac']]['personal-chat'][$v['id_theme']];
            $arC['id'] = $v['id'];
            $arC['user'] = $v['id_usp'];
            !isset($arC['noread']) && $arC['noread'] = 0;
            if(!$v['is_resp'] && !$v['is_read'])
                $arC['noread']++;
            $arRes['items'][$v['id_vac']]['personal-chat'][$v['id_theme']] = $arC;
        }

        $arRes['users'] = Share::getUsers($arUId);

        return $arRes;
    }
}