<?php

class Ideas
{
    /**
     * Активация пользователя
     */
    public function getIdeas()
    {
    	$status = Yii::app()->getRequest()->getParam('status');
    	$type = Yii::app()->getRequest()->getParam('type');
    	$limit = Yii::app()->getRequest()->getParam('limit');
    	$offset = Yii::app()->getRequest()->getParam('offset');
    	$filter = "";

    	if($offset == 0 && $limit == 0) {
    		$offset = 0;
    		$limit = 10;
    	}

    	if(!empty($status)) {
    		$filter.= " AND i.status = $status";
    	}

    	if(!empty($type)) {
    		$filter.= " AND i.type = $type";
    	}

    	$sql = "SELECT (SELECT COUNT(id) FROM ideas_attrib ai WHERE ai.comment IS NOT NULL AND ai.id = i.id) comments,
    	(SELECT COUNT(id) FROM ideas_attrib ai WHERE ai.rating = 1 AND ai.id = i.id) posrating,
    	(SELECT COUNT(id) FROM ideas_attrib ai WHERE ai.rating = 0 AND ai.id = i.id) negrating,
    	 i.id, i.name, i.text, i.type, i.crdate, i.mdate, i.status, i.id_user, i.usertype
                FROM ideas i
                WHERE i.ismoder = 1 $filter
                LIMIT {$offset}, {$limit}";
        /** @var $res CDbCommand */
      	$res = Yii::app()->db->createCommand($sql);
        $res = $res->queryAll();



        for($i = 0; $i < count($res); $i ++){
        	$id = $res[$i]['id'];
        	$idus = $res[$i]['id_user'];
        	if($res[$i]['usertype'] == 2){
        		$sql = "SELECT r.photo, r.firstname, r.lastname, u.is_online, r.isman
	            FROM resume r
	            INNER JOIN user u ON r.id_user = u.id_user
	            WHERE r.id_user = $idus";
        		$res[$i]['author'] = Yii::app()->db->createCommand($sql)->queryAll();
        	} elseif($res[$i]['usertype'] == 3){
        		$sql = "SELECT r.logo, r.name, r.lastname, u.is_online
	            FROM employer r
	            INNER JOIN user u ON r.id_user = u.id_user
	            WHERE r.id_user = $idus";
        		$res[$i]['author'] = Yii::app()->db->createCommand($sql)->queryAll();

        	}
        	
	        $sql = "SELECT  ai.id_user, ai.rating, ai.date_rating, ai.comment, ai.date_comment, ai.isread,
	    				   ai.email, ai.notification
	                FROM ideas_attrib ai
	                WHERE ai.id = $id
	                ORDER BY ai.id";
	        /** @var $res CDbCommand */
	      	$rest = Yii::app()->db->createCommand($sql);
	        $res[$i]['attrib'] = $rest->queryAll();
    	}

        return $res;

    }

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
                        'crdate' => date("Y-m-d h-i-s"),
                        'ismoder' => 0,
                    ));

    }

    public function setRating()
    {
    	$rating = Yii::app()->getRequest()->getParam('rating');
    	$idea = Yii::app()->getRequest()->getParam('id');
    	$id = Share::$UserProfile->id;

    	$sql = "SELECT  ai.id_user, ai.rating, ai.date_rating, ai.comment, ai.date_comment, ai.isread,
	    				   ai.email, ai.notification
	                FROM ideas_attrib ai
	                WHERE ai.id_user = $id
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
                       	'date_rating' => date("Y-m-d h-i-s")
                    ));
        }

    }

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
                       	'comment_rating' => date("Y-m-d h-i-s")
                    ));

    }

    public function getIdea($id)
    {
    	$sql = "SELECT (SELECT COUNT(id) FROM ideas_attrib ai WHERE ai.comment IS NOT NULL AND ai.id = i.id) comments,
    	(SELECT COUNT(id) FROM ideas_attrib ai WHERE ai.rating = 1 AND ai.id = i.id) posrating,
    	(SELECT COUNT(id) FROM ideas_attrib ai WHERE ai.rating = 0 AND ai.id = i.id) negrating, i.name, i.text, i.type, i.crdate, i.mdate, i.status, i.id_user, i.usertype
                FROM ideas i
                WHERE i.id = $id";
        /** @var $res CDbCommand */
      	$res = Yii::app()->db->createCommand($sql);
        $res = $res->queryRow();

        $idus = $res['id_user'];
        if($res['usertype'] == 2){
        		$sql = "SELECT r.photo, r.firstname, r.lastname, u.is_online, r.isman
	            FROM resume r
	            INNER JOIN user u ON r.id_user = u.id_user
	            WHERE r.id_user = $idus";
        		$res['author'] = Yii::app()->db->createCommand($sql)->queryAll();
        	} elseif($res['usertype'] == 3){
        		$sql = "SELECT r.logo, r.name, r.lastname, u.is_online
	            FROM employer r
	            INNER JOIN user u ON r.id_user = u.id_user
	            WHERE r.id_user = $idus";
        		$res['author'] = Yii::app()->db->createCommand($sql)->queryAll();

        	}

        $sql = "SELECT ai.id_user, ai.rating, ai.date_rating, ai.comment, ai.date_comment, ai.isread,
    				   ai.email, ai.notification
                FROM ideas_attrib ai
                WHERE ai.id = $id
                ORDER BY ai.id";
        /** @var $res CDbCommand */
      	$rest = Yii::app()->db->createCommand($sql);
        $res['attrib'] = $rest->queryAll();


        return $res;

    }


}




?>