<?php
/**
 * Date: 22.04.2016
 *
 * Модель переписки
 */

class ImApplic extends Im
{

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
        if( $inChatId || $inIdTo )
        {
            // вакансии по которым есть отклики для новой темы
            if( $inIdTo )
            {
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
            if( $inChatId )
            {
                $sql = "SELECT DISTINCT  ct.id, ct.title, e.title vactitle, ca.id_usp idusp, ca.id_use iduse
                        FROM chat_theme ct
                        LEFT JOIN empl_vacations e ON e.id = ct.id_vac
                        LEFT JOIN chat ca ON ca.id_theme = ct.id 
                        WHERE ct.id = {$inChatId}";
                /** @var $res CDbCommand */
                $res = Yii::app()->db->createCommand($sql);
                $res = $res->queryRow();

                if( $res['idusp'] == Share::$UserProfile->exInfo->id )
                {
                    $data['title'] = $res;
                }
                else return array('error' => 100); // чужой диалог
            }
            else
            {
            } // endif


            // получаем файлы диалога
            $Upluni = new Uploaduni();
            $Upluni->setCustomOptions(array(
                    'type' => array('images' => 'image/jpeg,image/png', 'files' => 'word,excel,spreadsheetml'),
                    'scope' => 'im',
                    'maxFS' => 5242880, // max filesize
                    'maxImgDim' => array(2500, 2500),
                    'removeProtected' => true,
                    'sizes' => array('isorig' => true, 'dims' => [], 'thumb' => 150 ),
                    'tmpDir' => "/" . MainConfig::$PATH_CONTENT_PROTECTED . "/im/tmp",
                )
            );
            $files = $Upluni->init();

            // берем только файлы этого диалога
            $themeFiles = array();
            foreach ($files['files'] ?: array() as $key => $val)
            {
                if( $val['extmeta']->idTheme == $inChatId ) {
                    $val['files'] = array_map(function($v){ return str_replace('/protected', '', $v); }, $val['files']);
                    $themeFiles[$key] = $val;
                }
            }
            $data = array_merge($data, array('files' => $themeFiles));

            // set chat data
            Yii::app()->session['imdata'] = array('chatId' => $inChatId);

            return $data;
        }
        else
        {
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
        if( $new > 0 )
        {
            $iduse = $new;


            $props = array(
                'id_usp' => $id,
                'id_use' => $iduse,
            );
            if( $theme ) $props['title'] = $theme;
            elseif( $vid ) $props['id_vac'] = $vid;

            $res = Yii::app()->db->createCommand()
                ->insert('chat_theme', $props);

            $idTm = Yii::app()->db->createCommand('SELECT LAST_INSERT_ID()')->queryScalar();

            $lastMessId = 0;

            // получаем тему из вакансии
            if( $vid )
            {
                $sql = "SELECT e.title FROM empl_vacations e WHERE e.id = {$vid}";
                /** @var $res CDbCommand */
                $res = Yii::app()->db->createCommand($sql);
                $theme = $res->queryScalar();
            }
        }
        else
        {
        } // endif
        
        // проверяем диалог на владельца
        if( $new || $ids = $this->isMyChat($idTm))
        {
            $message = trim(preg_replace('/&lt;([\/]?(?:div|b|i|br|u))&gt;/i', "<$1>", $message));
            if( true )
            {
                $uploaduni = new Uploaduni();
                $files = $uploaduni->getUploadedFiles(array('scope' => 'im'));

                // берем только файлы этого диалога
                if( $files )
                {
                    foreach ($files as $key => $val)
                    {
                        if( $val['extmeta']->idTheme == $idTm ) $themeFiles[$key] = $val;
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
                    $mailCloud->mailerMess($ids['iduse'],$idTm, 2);
        
            }
             // endif
            $ids = $ids['iduse'] ?: $iduse;
            file_get_contents("https://prommu.com/api.mailer/?id=$ids&type=3&method=mess");

            $sql = "SELECT r.new_mess
            FROM push_config r
            WHERE r.id = {$ids}";
            $res = Yii::app()->db->createCommand($sql)->queryScalar(); 
            if($res == 2) {
            $sql = "SELECT r.push
            FROM user_push r
            WHERE r.id = {$ids}";
            $res = Yii::app()->db->createCommand($sql)->queryRow(); 



            if($res) {
                $type = "mess";
                $api = new Api();
                $api->getPush($res['push'], $type);
            
                    }
                }
            return array_merge($this->getNewMessData($idTm, $lastMessId), array('theme' => $theme, 'idtm' => $idTm));
        }
        else
        {
            // не мой
            return array('error' => 100) ;
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

        if( $idTm )
        {
            // последние сообщения
            if( !$firstId )
            {
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


            if( $data[0]['idusp'] == Share::$UserProfile->exInfo->id )
            {
                return array('data' => $data, 'count' => $res['cou']);
            }
            else
            {
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
        $res = Yii::app()->db->createCommand($sql);
        $res = $res->queryRow();

        return Share::$UserProfile->id == $res['idusp'] ? $res : $res;
    }


    /**
     * Перемещаем прикрепленные файлы
     */
    private function moveUploadedFiles($inFiles)
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
        } // end foreach

        return $movedFiles;
    }



    /**
     * Подготавливаем файлы сообщения
     */
    private function prepareFiles($data)
    {
        $data = array_map(function($v) {
            if($v['files'])
            {
                $v['files'] = get_object_vars(json_decode($v['files']));
                foreach ($v['files'] as $key => &$val)
                {
                    $val->files = array_map(function($v2){ return str_replace('/protected', '', $v2); }, get_object_vars($val->files));
                } // end foreach
            }
            return $v;
        }, $data);

        return $data;
    }
}