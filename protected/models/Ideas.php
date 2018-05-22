<?php

class Ideas extends ARModel
{
    public $IDEAS_IN_PAGE = 20;
    public $COMMENTS_IN_PAGE = 20;

    public function tableName()
    {
        return 'ideas';
    }

    public function search()
    {
        $criteria=new CDbCriteria;

        $criteria->compare('id',$this->id,true);
        $criteria->compare('name',$this->name, true);
        $criteria->compare('type',$this->type, true);
        $criteria->compare('status',$this->status,true);
        $criteria->compare('crdate',$this->crdate,true);
        $criteria->compare('mdate',$this->mdate,true);
        $criteria->compare('ismoder',$this->ismoder,true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'pagination' => array('pageSize' => 50,),
            'sort' => ['defaultOrder'=>'crdate desc'],
        ));
    }
    /*
    *       Вспомогательный массив типов и статусов
    */
    public function getParams()
    {
        return array(
            'types' => array(
                1 => array('class' => 'idea',       'idea' => 'Идея',   'sort' => 'Идеи'),
                2 => array('class' => 'error',      'idea' => 'Ошибка', 'sort' => 'Ошибки'),
                3 => array('class' => 'question',   'idea' => 'Вопрос', 'sort' => 'Вопросы')
            ),
            'statuses' => array(
                1 => array('class' => 'start',  'idea' => 'На рассмотрении','sort' => 'На рассмотрении'),
                2 => array('class' => 'work',   'idea' => 'В работе',       'sort' => 'В работе'),
                3 => array('class' => 'end',    'idea' => 'Завершено',      'sort' => 'Завершенные'),
                4 => array('class' => 'decl',   'idea' => 'Отклонено',      'sort' => 'Отклоненные'),
            )
        );
    }
    /*
    *       Считаем все идеи
    */
    private function getIdeasCnt($filter){
        $sql = "SELECT COUNT(DISTINCT i.id) FROM ideas i WHERE i.ismoder = 1 {$filter}";
        $res = Yii::app()->db->createCommand($sql);
        return $res->queryScalar();
    }
    /*
    *       Получить все идеи (с фильтром)
    */
    public function getIdeas()
    {
        $name = Yii::app()->getRequest()->getParam('q');
        $status = Yii::app()->getRequest()->getParam('status');
        $type = Yii::app()->getRequest()->getParam('type');
        $sort = Yii::app()->getRequest()->getParam('sort');
        $filter = "";

        if(!empty($name)) {
            $filter.= " AND i.name LIKE '%{$name}%'";
        }
        if(!empty($status) && $status>=0) {
            $filter.= " AND i.status = $status";
        }

        if(!empty($type)) {
            $filter.= " AND i.type = $type";
        }
        switch ($sort) {
            case 1: $order = 'posrating DESC'; break; // по рейтингу
            case 2: $order = 'negrating DESC'; break; // по антирейтингу
            case 4: $order = 'i.crdate ASC'; break; // по самой ранней дате
            case 5: $order = 'i.crdate DESC'; break; // просмотры !!!!!!!!!!!!!
            case 6: $order = 'comments DESC'; break; // комментарии
            case 3:
            default: $order = 'i.crdate DESC'; break; // по последнейдате
        }

        $res['ideas_cnt'] = $this->getIdeasCnt($filter);
        $res['pages'] = new CPagination($res['ideas_cnt']);
        $res['pages']->pageSize = $this->IDEAS_IN_PAGE;
        $res['pages']->applyLimit($this);

        $sql = "SELECT 
            (SELECT COUNT(id) 
                FROM ideas_attrib ai 
                WHERE ai.comment IS NOT NULL AND ai.id_idea = i.id AND ai.hidden=0) comments,
           (SELECT COUNT(id) FROM ideas_attrib ai WHERE ai.rating = 1 AND ai.id_idea = i.id) posrating,
           (SELECT COUNT(id) FROM ideas_attrib ai WHERE ai.rating = 2 AND ai.id_idea = i.id) negrating,
           i.id, i.name, i.text, i.type, DATE_FORMAT(i.crdate, '%d.%m.%Y') crdate, 
           DATE_FORMAT(i.mdate, '%d.%m.%Y') mdate, i.status, i.id_user
                FROM ideas i
                WHERE i.ismoder = 1 $filter
                ORDER BY {$order}
                LIMIT {$this->offset}, {$this->limit}";
        /** @var $res CDbCommand */
        $res['ideas'] = Yii::app()->db->createCommand($sql)->queryAll();

        $arUserIdies = array();
        $arIdeasIdies = array();
        foreach ($res['ideas'] as &$item){
            $arUserIdies[] = $item['id_user'];
            $arIdeasIdies[] = $item['id'];
            $item['link'] = MainConfig::$PAGE_IDEAS_LIST . DS . $item['id'];
        } 
        unset($item);

        $res['users'] = $this->getUsers($arUserIdies);
        $res['is_guest'] = !in_array(Share::$UserProfile->type, [2,3]);
        $res = array_merge($res, $this->getParams());

        return $res;
    }
    /*
    *       Получение отдельной идеи с комментариями (с фильтром)
    */
    public function getIdea($id)
    {
        $type = Yii::app()->getRequest()->getParam('type');

        $sql = "SELECT 
            (SELECT COUNT(id) 
                FROM ideas_attrib ai 
                WHERE ai.comment IS NOT NULL AND ai.id_idea = i.id AND ai.hidden=0) comments_cnt,
            (SELECT COUNT(id) FROM ideas_attrib ai WHERE ai.rating = 1 AND ai.id_idea = i.id) posrating,
            (SELECT COUNT(id) FROM ideas_attrib ai WHERE ai.rating = 2 AND ai.id_idea = i.id) negrating, 
            i.id, i.name, i.text, i.type, DATE_FORMAT(i.crdate, '%d.%m.%Y') crdate, 
           DATE_FORMAT(i.mdate, '%d.%m.%Y') mdate, i.status, i.id_user
                FROM ideas i
                WHERE i.id = $id";
        /** @var $res CDbCommand */
        $res = Yii::app()->db->createCommand($sql);
        $res = $res->queryRow();

        $res['pages'] = new CPagination($res['comments_cnt']);
        $res['pages']->pageSize = $this->COMMENTS_IN_PAGE;
        $res['pages']->applyLimit($this);
        $order = ($type==2 ? 'ASC' : 'DESC');

        $sql = "SELECT ai.id_user, ai.rating, DATE_FORMAT(ai.date_rating, '%d.%m.%Y') date_rating, 
                    ai.comment, DATE_FORMAT(ai.date_comment, '%d.%m.%Y %T') date_comment, ai.isread,
                    ai.email, ai.notification, ai.hidden
                FROM ideas_attrib ai
                WHERE ai.id_idea = $id AND ai.comment IS NOT NULL AND ai.hidden = 0
                ORDER BY ai.date_comment {$order}
                LIMIT {$this->offset}, {$this->limit}";
        /** @var $res CDbCommand */
        $rest = Yii::app()->db->createCommand($sql);
        $res['attrib'] = $rest->queryAll();

        $arUserIdies = array();
        $arUserIdies[] = $res['id_user'];
        $res['comments'] = array();
        foreach($res['attrib'] as $key => $attr){
            if(!empty($attr['comment']) && $attr['hidden']==0){
                $res['comments'][] = $attr;
                $arUserIdies[] = $attr['id_user'];
            }
        }

        $res['users'] = $this->getUsers($arUserIdies);
        $res['is_guest'] = !in_array(Share::$UserProfile->type, [2,3]);
        $res = array_merge($res, $this->getParams());

        return $res;
    }
    /*
    *       Забираем идею для админки
    */
    public function getIdeaForAdmin($id)
    {
        $sql = "SELECT 
            (SELECT COUNT(id) 
                FROM ideas_attrib ai 
                WHERE ai.comment IS NOT NULL AND ai.id_idea = i.id AND ai.hidden=0) comments_cnt,
            (SELECT COUNT(id) FROM ideas_attrib ai WHERE ai.rating = 1 AND ai.id_idea = i.id) posrating,
            (SELECT COUNT(id) FROM ideas_attrib ai WHERE ai.rating = 2 AND ai.id_idea = i.id) negrating, 
            i.id, i.name, i.text, i.type, DATE_FORMAT(i.crdate, '%d.%m.%Y') crdate, 
            DATE_FORMAT(i.mdate, '%d.%m.%Y') mdate, i.status, i.id_user, i.ismoder
            FROM ideas i
            WHERE i.id = $id";

        $res = Yii::app()->db->createCommand($sql)->queryRow();

        $sql = "SELECT ai.id, ai.id_user, ai.comment, ai.hidden,
                    DATE_FORMAT(ai.date_comment, '%d.%m.%Y %T') date_comment 
                FROM ideas_attrib ai
                WHERE ai.comment IS NOT NULL AND ai.id_idea = $id
                ORDER BY ai.date_comment DESC";

        $res['comments'] = Yii::app()->db->createCommand($sql)->queryAll();

        $arUserIdies = array();
        $arUserIdies[] = $res['id_user'];
        foreach($res['comments'] as $key => $attr){
            $arUserIdies[] = $attr['id_user'];
        }

        $res['users'] = $this->getUsers($arUserIdies);
        $res = array_merge($res, $this->getParams());

        return $res;
    }
    /*
    *       Создание идеи
    */
    public function setIdeas()
    {
        $name = Yii::app()->getRequest()->getParam('name');
        $text = Yii::app()->getRequest()->getParam('text');
        $type = Yii::app()->getRequest()->getParam('type');
        $id = Share::$UserProfile->id;

        $res = Yii::app()->db->createCommand()
                    ->insert('ideas', array(
                        'name' => $name,
                        'type' => $type,
                        'text' => $text,
                        'id_user' => $id,
                        'crdate' => date("Y-m-d H-i-s"),
                        'ismoder' => 0,
                        'status' => 1
                    ));

    }
    /*
    *       Изменение идеи
    */
    public function changeIdea($id)
    {
        Yii::app()->db->createCommand()
                ->update(
                    'ideas', 
                    array(
                        'name' => $_POST['name'],
                        'text' => $_POST['text'],
                        'type' => $_POST['type'],
                        'status' => $_POST['status'],
                        'mdate' => date("Y-m-d H-i-s"),
                        'ismoder' => $_POST['ismoder']
                    ),
                    'id=:id', 
                    array(':id'=>$id)
            );
    }
    /*
    *       Удаление идеи
    */
    public function deleteIdea($id) 
    {
        $attrib = Yii::app()->db->createCommand()
            ->delete('ideas_attrib','id_idea=:id', array(':id'=>$id));
        $idea = Yii::app()->db->createCommand()
            ->delete('ideas','id=:id', array(':id'=>$id));
    }
    /*
    *       Добавление комментария к идее
    */
    public function setComment($isAdmin=0)
    {
        $comment = Yii::app()->getRequest()->getParam('comment');
        $idea = Yii::app()->getRequest()->getParam('id');
        $id = $isAdmin ? 0 : Share::$UserProfile->id;

        $res = Yii::app()->db->createCommand()
                    ->insert('ideas_attrib', array(
                        'id_idea' => $idea,
                        'id_user' => $id,
                        'comment' => $comment,
                        'date_comment' => date("Y-m-d H-i-s"),
                        'hidden' => $id ? 1 : 0
                    ));

        return $res;
    }
    /*
    *       Изменить видимость комментария
    */
    public function changeVisComment($id) 
    {
        $res = Yii::app()->db->createCommand()
                ->select('ia.hidden')
                ->from('ideas_attrib ia')
                ->where('ia.id=:id', array(':id' => $id))
                ->queryRow();    

        return Yii::app()->db->createCommand()
            ->update(
                'ideas_attrib', 
                array('hidden' => !$res['hidden']),
                'id=:id', 
                array(':id'=>$id)
            );
    }
    /*
    *       Удаление комментария
    */
    public function deleteComment($id) 
    {
        return Yii::app()->db->createCommand()
            ->delete('ideas_attrib','id=:id', array(':id'=>$id));
    }
    /*
    *       Добавление голоса за идею
    */
    public function setRating()
    {
        $rating = Yii::app()->getRequest()->getParam('rating');
        $idea = Yii::app()->getRequest()->getParam('id');
        $id = Share::$UserProfile->id;

        $sql = "SELECT ai.id, ai.rating
            FROM ideas_attrib ai
            WHERE ai.id_user = $id AND ai.id_idea = $idea AND ai.rating <> 0";
        $res = Yii::app()->db->createCommand($sql)->queryRow();

        if(isset($res['id'])) {
            Yii::app()->db->createCommand()
                ->delete('ideas_attrib','id=:id', array(':id'=>$res['id']));
            $arResult = array(
                    'type' => $res['rating']==1 ? 'rempos' : 'remneg',
                    'mess' => $res['rating']==1
                        ? 'Ваш положительный голос был удален'
                        : 'Ваш отрицательный голос был удален'
                );
        } else {
            $arResult = array(
                    'type' => 'create',
                    'mess' => 'Спасибо, что приняли участие в голосовании'
                );
            $res = Yii::app()->db->createCommand()
                    ->insert('ideas_attrib', array(
                        'id_idea' => $idea,
                        'id_user' => $id,
                        'rating' => $rating,
                        'date_rating' => date("Y-m-d H-i-s"),
                        'hidden' => 0,
                    ));  
        }
        return $arResult;
    }
    /*
    *       Получение инфы о пользователях
    */
    private function getUsers($arIdies)
    {
        $arResult = array();

        if(sizeof($arIdies)){
            $arIdies = array_unique($arIdies);
            $strId = implode(',', $arIdies);
            $sql = "SELECT u.id_user id, u.status type, r.photo, 
                r.firstname, r.lastname, u.is_online, r.isman
                FROM resume r
                INNER JOIN user u ON r.id_user = u.id_user
                WHERE r.id_user IN({$strId})";
                $arApps = Yii::app()->db->createCommand($sql)->queryAll();

            foreach($arApps as $user) {
                $arResult[$user['id']] = $this->drawUpUser($user);
            }

            $sql = "SELECT u.id_user id, u.status type, r.logo, 
                r.name, u.is_online
                FROM employer r
                INNER JOIN user u ON r.id_user = u.id_user
                WHERE r.id_user IN({$strId})";
                $arEmps = Yii::app()->db->createCommand($sql)->queryAll();

            foreach($arEmps as $user) {
                $arResult[$user['id']] = $this->drawUpUser($user);
            }
            $arResult[0] = array( // admin
                    'id' => 0,
                    'type' => 1,
                    'name' => 'Администрация',
                    'src' => '/images/prommu.jpg',
                    'profile' => 'javascript:void(0)',
                    'is_online' => 0
                );       
        }

        return $arResult;
    }
    /*
    *       Формирование массива пользователей
    */
    private function drawUpUser($arr)
    {
        $arRes = array();
        if($arr['type']==2){
            $arRes['id'] = $arr['id'];
            $arRes['type'] = $arr['type'];
            $arRes['name'] = $arr['firstname'] . ' ' . $arr['lastname'];
            $arRes['src'] = DS . MainConfig::$PATH_APPLIC_LOGO . DS 
                . ($arr['photo'] ?: ($arr['isman'] ? MainConfig::$DEF_LOGO : MainConfig::$DEF_LOGO_F))
                . '100.jpg';
            $arRes['profile'] = MainConfig::$PAGE_PROFILE_COMMON . DS . $arr['id'];
            $arRes['is_online'] = $arr['is_online'];
        }
        if($arr['type']==3){
            $arRes['id'] = $arr['id'];
            $arRes['type'] = $arr['type'];
            $arRes['name'] = $arr['name'];
            $arRes['src'] = DS . MainConfig::$PATH_EMPL_LOGO 
                . DS . (!$arr['logo'] ? 'logo.png' : $arr['logo'] 
                . '100.jpg');
            $arRes['profile'] = MainConfig::$PAGE_PROFILE_COMMON . DS . $arr['id'];
            $arRes['is_online'] = $arr['is_online'];
        }
        return $arRes;
    }
}
?>