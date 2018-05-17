<?php

class Ideas
{
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

        $count = $this->getIdeasCnt($filter);
        $pages = new CPagination($count);
        $pages->pageSize = 3; // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
        $pages->applyLimit($this);

        $sql = "SELECT (SELECT COUNT(id) FROM ideas_attrib ai WHERE ai.comment IS NOT NULL AND ai.id = i.id) comments,
           (SELECT COUNT(id) FROM ideas_attrib ai WHERE ai.rating = 1 AND ai.id = i.id) posrating,
           (SELECT COUNT(id) FROM ideas_attrib ai WHERE ai.rating = 2 AND ai.id = i.id) negrating,
           i.id, i.name, i.text, i.type, DATE_FORMAT(i.crdate, '%d.%m.%Y') crdate, 
           DATE_FORMAT(i.mdate, '%d.%m.%Y') mdate, i.status, i.id_user, i.usertype
                FROM ideas i
                WHERE i.ismoder = 1 $filter
                ORDER BY {$order}
                LIMIT {$this->offset}, {$this->limit}";
        /** @var $res CDbCommand */
        $res = Yii::app()->db->createCommand($sql);
        $res = $res->queryAll();



        for($i = 0; $i < count($res); $i ++){
            $id = $res[$i]['id'];
            $idus = $res[$i]['id_user'];
            $res[$i]['link'] = MainConfig::$PAGE_IDEAS_LIST . DS . $res[$i]['id'];
            if($res[$i]['usertype'] == 2){
                $sql = "SELECT u.id_user id, u.status type, r.photo, 
                    r.firstname, r.lastname, u.is_online, r.isman
                    FROM resume r
                    INNER JOIN user u ON r.id_user = u.id_user
                    WHERE r.id_user = $idus";
                $arTemp = Yii::app()->db->createCommand($sql)->queryRow();
                $res[$i]['author'] = $this->drawUpUser($arTemp);

            } elseif($res[$i]['usertype'] == 3){
                $sql = "SELECT u.id_user id, u.status type, 
                    r.logo, r.name, u.is_online
                    FROM employer r
                    INNER JOIN user u ON r.id_user = u.id_user
                    WHERE r.id_user = $idus";
                $arTemp = Yii::app()->db->createCommand($sql)->queryRow();
                $res[$i]['author'] = $this->drawUpUser($arTemp);
            }
            
            $sql = "SELECT  ai.id_user, ai.rating, DATE_FORMAT(ai.date_rating, '%d.%m.%Y') date_rating, 
                    ai.comment, DATE_FORMAT(ai.date_comment, '%d.%m.%Y') date_comment, ai.isread,
                    ai.email, ai.notification
                FROM ideas_attrib ai
                WHERE ai.id = $id
                ORDER BY ai.id";
            /** @var $res CDbCommand */
            $rest = Yii::app()->db->createCommand($sql);
            $res[$i]['attrib'] = $rest->queryAll();
        }
        $arResult = array(
                'ideas' => $res,
                'types' => $this->GetTypes(),
                'statuses' => $this->getStatuses(),
                'is_guest' => !in_array(Share::$UserProfile->type, [2,3]),
                'pages' => $pages,
                'ideas_cnt' => $count
            );
       

        return $arResult;

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
                        'usertype' => Share::$UserProfile->type,
                        'crdate' => date("Y-m-d H-i-s"),
                        'ismoder' => 0,
                        'status' => 1
                    ));

    }
    /*
    *       Добавление голоса за идею
    */
    public function setRating()
    {
        $rating = Yii::app()->getRequest()->getParam('rating');
        $idea = Yii::app()->getRequest()->getParam('id');
        $id = Share::$UserProfile->id;

        $sql = "SELECT  ai.id_user, ai.rating, ai.date_rating, ai.comment, ai.date_comment, ai.isread,
                           ai.email, ai.notification
                    FROM ideas_attrib ai
                    WHERE ai.id_user = $id AND ai.id = $idea AND ai.rating <> 0
                    ORDER BY ai.id";
            /** @var $res CDbCommand */
        $rest = Yii::app()->db->createCommand($sql);
        $rest = $rest->queryAll();
        if(!empty($rest)){
            return "error";
        } else {
            $res = Yii::app()->db->createCommand()
                    ->insert('ideas_attrib', array(
                        'id' => $idea,
                        'id_user' => $id,
                        'usertype' => Share::$UserProfile->type,
                        'rating' => $rating,
                        'date_rating' => date("Y-m-d H-i-s")
                    ));
            return $res;
        }

    }
    /*
    *       Добавление комментария к идее
    */
    public function setComment()
    {
        $comment = Yii::app()->getRequest()->getParam('comment');
        $idea = Yii::app()->getRequest()->getParam('id');
        $id = Share::$UserProfile->id;

        $res = Yii::app()->db->createCommand()
                    ->insert('ideas_attrib', array(
                        'id' => $idea,
                        'id_user' => $id,
                        'usertype' => Share::$UserProfile->type,
                        'comment' => $comment,
                        'date_comment' => date("Y-m-d H-i-s")
                    ));

        return $res;
    }
    /*
    *       Получение отдельной идеи с комментариями (с фильтром)
    */
    public function getIdea($id)
    {
        $type = Yii::app()->getRequest()->getParam('type');

        $sql = "SELECT (SELECT COUNT(id) FROM ideas_attrib ai WHERE ai.comment IS NOT NULL AND ai.id = i.id) comments,
        (SELECT COUNT(id) FROM ideas_attrib ai WHERE ai.rating = 1 AND ai.id = i.id) posrating,
        (SELECT COUNT(id) FROM ideas_attrib ai WHERE ai.rating = 2 AND ai.id = i.id) negrating, 
            i.id, i.name, i.text, i.type, DATE_FORMAT(i.crdate, '%d.%m.%Y') crdate, 
           DATE_FORMAT(i.mdate, '%d.%m.%Y') mdate, i.status, i.id_user, i.usertype
                FROM ideas i
                WHERE i.id = $id";
        /** @var $res CDbCommand */
        $res = Yii::app()->db->createCommand($sql);
        $res = $res->queryRow();

        $idus = $res['id_user'];
        if($res['usertype'] == 2) {
            $sql = "SELECT u.id_user id, u.status type, r.photo, 
                r.firstname, r.lastname, u.is_online, r.isman
                FROM resume r
                INNER JOIN user u ON r.id_user = u.id_user
                WHERE r.id_user = $idus";
            $arTemp = Yii::app()->db->createCommand($sql)->queryRow();
            $res['author'] = $this->drawUpUser($arTemp);
        } 
        elseif($res['usertype'] == 3) {
            $sql = "SELECT u.id_user id, u.status type, 
                r.logo, r.name, r.lastname, u.is_online
                FROM employer r
                INNER JOIN user u ON r.id_user = u.id_user
                WHERE r.id_user = $idus";
            $arTemp = Yii::app()->db->createCommand($sql)->queryRow();
            $res['author'] = $this->drawUpUser($arTemp);
        }

        $count = $this->getIdeaCommentsCnt($id);
        $pages = new CPagination($count);
        $pages->pageSize = 3; // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
        $pages->applyLimit($this);
        $order = ($type==2 ? 'ASC' : 'DESC');

        $sql = "SELECT ai.id_user, ai.rating, DATE_FORMAT(ai.date_rating, '%d.%m.%Y') date_rating, 
                    ai.comment, DATE_FORMAT(ai.date_comment, '%d.%m.%Y %T') date_comment, ai.isread,
                    ai.email, ai.notification
                FROM ideas_attrib ai
                WHERE ai.id = $id
                ORDER BY ai.date_comment {$order}
                LIMIT {$this->offset}, {$this->limit}";
        /** @var $res CDbCommand */
        $rest = Yii::app()->db->createCommand($sql);
        $res['attrib'] = $rest->queryAll();

        $arId = array();
        $res['arr_comments'] = array();
        foreach($res['attrib'] as $key => $attr){
            if(!empty($attr['comment'])){
                $res['arr_comments'][] = $attr;
                $arId[] = $attr['id_user'];
            }
        }

        $res['users'] = array();
        if(sizeof($arId)){
            $arId = implode(',', $arId);
            $sql = "SELECT u.id_user id, u.status type, r.photo, 
                r.firstname, r.lastname, u.is_online, r.isman
                FROM resume r
                INNER JOIN user u ON r.id_user = u.id_user
                WHERE r.id_user IN({$arId})";
                $arTemp = Yii::app()->db->createCommand($sql)->queryAll();

            foreach($arTemp as $user) {
                $res['users'][$user['id']] = $this->drawUpUser($user);
            }

            $sql = "SELECT u.id_user id, u.status type, r.logo, 
                r.name, u.is_online
                FROM employer r
                INNER JOIN user u ON r.id_user = u.id_user
                WHERE r.id_user IN({$arId})";
                $arTemp = Yii::app()->db->createCommand($sql)->queryAll();

            foreach($arTemp as $user) {
                $res['users'][$user['id']] = $this->drawUpUser($user);
            }

        }

        return array_merge(
            $res, 
            array(
                'comments_cnt' => $count,
                'pages' => $pages,
                'types' => $this->GetTypes(),
                'statuses' => $this->getStatuses(),
                'is_guest' => !in_array(Share::$UserProfile->type, [2,3])
            )
        );

    }
    /*
    *   формирование массива пользователя
    */
    private function drawUpUser($arr){
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
    /*
    *   считаем все идеи
    */
    private function getIdeasCnt($filter){
        $sql = "SELECT COUNT(DISTINCT i.id) FROM ideas i WHERE i.ismoder = 1 {$filter}";
        $res = Yii::app()->db->createCommand($sql);
        return $res->queryScalar();
    }
    /*
    *   считаем все комменты идеи
    */
    private function getIdeaCommentsCnt($id){
        $sql = "SELECT COUNT(ai.id) FROM ideas_attrib ai WHERE ai.id = $id";
        $res = Yii::app()->db->createCommand($sql);
        return $res->queryScalar();
    }
    /*
    *   Вспомогательный массив типов
    */
    public function getTypes()
    {
        return array(
            1 => array('class' => 'idea',       'idea' => 'Идея',   'sort' => 'Идеи'),
            2 => array('class' => 'error',      'idea' => 'Ошибка', 'sort' => 'Ошибки'),
            3 => array('class' => 'question',   'idea' => 'Вопрос', 'sort' => 'Вопросы'),
        );
    }
    /*
    *   Вспомогательный массив статусов
    */
    public function getStatuses()
    {
        return array(
            1 => array('class' => 'start',  'idea' => 'На рассмотрении','sort' => 'На рассмотрении'),
            2 => array('class' => 'work',   'idea' => 'В работе',       'sort' => 'В работе'),
            3 => array('class' => 'end',    'idea' => 'Завершено',      'sort' => 'Завершенные'),
            4 => array('class' => 'decl',   'idea' => 'Отклонено',      'sort' => 'Отклоненные'),
        );
    }
}




?>