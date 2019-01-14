<?php
/**
 * Date: 23.06.2016
 * Модель обсуждения вакансии
 */

class VacDiscuss extends Model
{
    /**
     * Получаем сообщения обсуждений
     */
    public function getDiscuss($id)
    {
        $this->limit = 50;
        $conditions = 'id_vac=:idvac';
        $arParams[':idvac'] = $id;
        $idus = Share::$UserProfile->id;

        $id_message = filter_var(
                Yii::app()->getRequest()->getParam('id_message'), 
                FILTER_SANITIZE_NUMBER_INT
            );
        $offset = filter_var(
                Yii::app()->getRequest()->getParam('offset'), 
                FILTER_SANITIZE_NUMBER_INT
            );

        if(intval($id_message)>0)
        {
            $conditions .= ' AND id>:idmes';
            $arParams[':idmes'] = intval($id_message);
        }
        if(intval($offset)>0)
        {
            $this->offset = $offset * $this->limit;
        }

        $arRes['vacancy'] = Yii::app()->db->createCommand()
                ->select('id, id_user, title')
                ->from('empl_vacations')
                ->where('id=:id',array(':id'=>$id))
                ->queryRow();

        $arRes['items'] = Yii::app()->db->createCommand()
                ->select("id, id_user, mess, files, crdate")
                ->from('emplv_discuss')
                ->where($conditions,$arParams)
                ->order('crdate desc')
                ->offset($this->offset)
                ->limit($this->limit)
                ->queryAll();

        if(!count($arRes['items']))
            return $arRes;

        $arRes['title'] = reset($arRes['items'])['title'];
        $arIdus = $arIdMess = $arId = array();
        foreach ($arRes['items'] as &$item)
        {
            $arIdMess[] = $item['id'];
            $arIdus[] = $item['id_user'];
            $item['users_readed'] = array();
            $item['date'] = Share::getPrettyDate($item['crdate']);
            strlen($item['files']) && $item['files'] = unserialize($v['files']);
        }
        unset($item);

        $sql = Yii::app()->db->createCommand()
                ->select("id_message id, id_user")
                ->from('emplv_discuss_readed')
                ->where(['in','id_message',$arIdMess])
                ->queryAll();

        foreach ($arRes['items'] as &$item)
            foreach ($sql as $v)
                if($item['id']==$v['id'])
                {
                    if($v['id_user']!=$idus)
                    {
                        if(
                            !in_array($v['id_user'],$item['users_readed']) 
                            && 
                            $v['id_user']!=$item['id_user']
                        )
                            $item['users_readed'][] = $v['id_user'];
                    }
                    else
                    {
                        $item['readed'] = 1;
                        $key = array_search($v['id'], $arIdMess);
                        unset($arIdMess[$key]);
                    }
                }
        unset($item);

        if(count($arIdMess))
        { // устанавливаем статус прочитанности
        	$arInsert = array();
        	$date = time();
        	foreach ($arIdMess as $v)
        	{
        		$arInsert[] = array(
        				'id_message' => $v,
        				'id_user' => $idus,
        				'cdate' => $date
        			);
        	}
        	Share::multipleInsert(['emplv_discuss_readed'=>$arInsert]);
        }

        $arRes['users'] = Share::getUsers($arIdus);

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
        $sessionId = 'v'.$id;
        $arRes['files'] = array();
        foreach ($files['files'] ?: array() as $key => $val)
        {
            if ($val['extmeta']->idTheme == $sessionId)
            {
                $val['files'] = array_map(
                    function ($v){ return str_replace('/protected', '', $v);}, 
                    $val['files']
                );
                $arRes['files'][$key] = $val;
            }
        }
        // set vacancy chat data in session
        $imData = Yii::app()->session['imdata'];
        if(!is_array($imData['vacancy']))
            $imData['vacancy'] = [$sessionId];
        elseif(!in_array($sessionId, $imData['vacancy']))
            $imData['vacancy'][] = $sessionId;

        Yii::app()->session['imdata'] = $imData;

        return $arRes;
    }



    /**
     * Получаем колво сообщений в обсуждении
     */
    public function getDiscussCount($inIdVac)
    {
        $sql = "SELECT COUNT(*) cou FROM emplv_discuss d WHERE id_vac = {$inIdVac}";
        /** @var $res CDbCommand */
        $res = Yii::app()->db->createCommand($sql);
        return $res->queryScalar();
    }



    public function postMessage()
    {
        $id = filter_var(Yii::app()->getRequest()->getParam('id', 0), FILTER_SANITIZE_NUMBER_INT);
        $mess = filter_var(Yii::app()->getRequest()->getParam('mess'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $idPromo = Share::$UserProfile->exInfo->id_resume;
        $idus = Share::$UserProfile->id;

        // проверка на возможность писать сообщение в эту вакансию
        if( !$idPromo )
        {
            $sql = "SELECT e.id
                FROM empl_vacations e
                INNER JOIN employer em ON e.id_user = em.id_user AND em.id_user = {$idus}
                WHERE e.id = {$id}";
        }
        else
        {
            $sql = "SELECT e.id
                FROM empl_vacations e
                INNER JOIN vacation_stat s ON e.id = s.id_vac AND s.status IN (4,5,6,7) AND s.id_promo = {$idPromo}
                WHERE e.id = {$id}";
        } // endif
        /** @var $res CDbCommand */
        $res = Yii::app()->db->createCommand($sql);
        $res = $res->queryScalar();


        // сохраняем сообщение
        if( $res )
        {
            Yii::app()->db->createCommand()
                ->insert('emplv_discuss', array(
                        'id_vac' => $id,
                        'id_user' => $idus,
                        'mess' => $mess,
                        'files' => $arFiles,
                        'crdate' => date("Y-m-d H:i:s"),
                    ));

            $error = 0;
            $message = "Сообщение добавлено";
        }
        else
        {
            $error = 1;
            $message = "Ошибка добавления сообщения";
        }

        return array('error' => $error);
    }
    /**
    * @param $id number - vacancy ID
    */
    public function hasAccess($id)
    {
        $idus = Share::$UserProfile->id;
        $type = Share::$UserProfile->type;
        $id_promo = Share::$UserProfile->exInfo->id_resume;

        if($type==2)
        {
            $sql = Yii::app()->db->createCommand()
                    ->select('id')
                    ->from('vacation_stat vs')
                    ->where(
                        'vs.id_vac=:id AND vs.id_promo=:idus AND vs.status>4',
                        array(':id'=>$id,':idus'=>$id_promo)
                    )
                    ->queryRow();

            return isset($sql['id']);
        }
        if($type==3)
        {
            $sql = Yii::app()->db->createCommand()
                    ->select('ev.id')
                    ->from('empl_vacations ev')
                    ->leftjoin('vacation_stat vs','vs.id_vac=ev.id')
                    ->where(
                        'ev.id=:id AND ev.id_user=:idus AND vs.status>4',
                        array(':idus'=>$idus,':id'=>$id)
                    )
                    ->queryRow();

            return isset($sql['id']);
        }

        return false;
    }
    /**
     * 
     */
    public function recordMessage($data)
    {
        $vacancy = filter_var($data['vacancy'], FILTER_SANITIZE_NUMBER_INT);
        $message = filter_var($data['message'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        
        if(!intval($vacancy) || !strlen($message) || !$this->hasAccess($vacancy))
            return array('error' => true);

        $uploaduni = new Uploaduni();
        $files = $uploaduni->getUploadedFiles(['scope' => 'im']);
        $arFiles = array();

        if ($files)
        {
            $sessionId = 'v'.$vacancy;
            foreach ($files as $key => $val)
                $val['extmeta']->idTheme==$sessionId && $arFiles[$key] = $val;

          $arFiles = $arFiles ? serialize($this->moveUploadedFiles($arFiles)) : '';
          $arFiles && $uploaduni->removeUnExistedFiles(['scope' => 'im']);
        }

        Yii::app()->db->createCommand()
            ->insert(
                'emplv_discuss', 
                array(
                    'id_vac' => $vacancy,
                    'id_user' => Share::$UserProfile->id,
                    'mess' => $message,
                    'files' => $arFiles,
                    'crdate' => date("Y-m-d H:i:s")
                )
            );

        return array('error' => false);   
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
     *      счетчик непрочитаных для юзера в шапку
     */
    public static function publicVacChatCnt()
    {
        $profile = Share::$UserProfile;

        if(!$profile->id || !in_array($profile->type,[2,3]))
            return false;

        if($profile->type==2) // applicant
        {
            $arVacs = Vacancy::getVacsForChats($profile, false)['id'];
            if(!count($arVacs)) return 0;
print_r($arVacs);
            return self::getChatsByVacs($arVacs, $profile,false);
        }
        if($profile->type==3) // employer
        {
            return Yii::app()->db->createCommand()
                    ->select('COUNT(DISTINCT(ed.id)) cnt')
                    ->from('empl_vacations ev')
                    ->leftjoin('emplv_discuss ed','ed.id_vac=ev.id')
                    ->leftjoin(
                        'emplv_discuss_readed edr',
                        'edr.id_message=ed.id AND edr.id_user=ev.id_user'
                    )
                    ->leftjoin(
                        'vacation_stat vs',
                        'vs.id_vac=ev.id AND vs.status>4'
                    )
                    ->where(
                        'ev.id_user=:id AND edr.cdate IS NULL',
                        array(':id'=>$idus)
                    )
                    ->queryScalar();
        }
    }
    /**
     * @param $arVacs - array() ID of vacancies
     * @param $user - profile object
     * @param $isFull - bool all columns or ID only
     * @return array(query = array())
     */
    public static function getChatsByVacs($arVacs,$user,$isFull=true)
    {
        if(!$isFull)
        {
            return Yii::app()->db->createCommand()
                        ->select("edr.cdate")
                        ->from('emplv_discuss ed')
                        ->leftjoin(
                            'emplv_discuss_readed edr',
                            'edr.id_message=ed.id AND edr.id_user='.$user->id
                        )
                        ->where(['in','ed.id_vac',$arVacs])
                        ->queryScalar();            
        }

        $arRes['query'] = Yii::app()->db->createCommand()
                            ->select("ed.id, 
                                ed.id_vac, 
                                ed.id_user,
                                IF(edr.cdate IS NULL,0,1) AS readed")
                            ->from('emplv_discuss ed')
                            ->leftjoin(
                                'emplv_discuss_readed edr',
                                'edr.id_message=ed.id AND edr.id_user='.$user->id
                            )
                            ->where(['in','ed.id_vac',$arVacs])
                            ->queryAll();

        return $arRes;
    }
}