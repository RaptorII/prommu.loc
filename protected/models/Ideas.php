<?php

class Ideas
{
    /**
     * Активация пользователя
     */
    public function getIdeas()
    {
    	$sql = "SELECT (SELECT COUNT(id) FROM ideas_attrib ai WHERE ai.comment IS NOT NULL AND ai.id = i.id) comments,
    	(SELECT COUNT(id) FROM ideas_attrib ai WHERE ai.rating = 1 AND ai.id = i.id) posrating,
    	(SELECT COUNT(id) FROM ideas_attrib ai WHERE ai.rating = 0 AND ai.id = i.id) negrating,
    	 i.id, i.name, i.text, i.type, i.crdate, i.mdate, i.status, i.id_user
                FROM ideas i
                WHERE i.ismoder = 1";
        /** @var $res CDbCommand */
      	$res = Yii::app()->db->createCommand($sql);
        $res = $res->queryAll();

        for($i = 0; $i < count($res); $i ++){
        	$id = $res[$i]['id'];
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
                        'crdate' => date("Y-m-d h-i-s"),
                        'ismoder' => 0,
                    ));

    }

    public function setRating()
    {
    	$rating = Yii::app()->getRequest()->getParam('rating');
    	$idea = Yii::app()->getRequest()->getParam('id');
    	$id = Share::$UserProfile->id;

    	$res = Yii::app()->db->createCommand()
                    ->insert('ideas_attrib', array(
                        'id' => $idea,
                        'id_user' => $id,
                        'rating' => $rating,
                       	'date_rating' => date("Y-m-d h-i-s")
                    ));

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
                        'comment' => $comment,
                       	'comment_rating' => date("Y-m-d h-i-s")
                    ));

    }

    public function getIdea($id)
    {
    	$sql = "SELECT (SELECT COUNT(id) FROM ideas_attrib ai WHERE ai.comment IS NOT NULL AND ai.id = i.id) comments,
    	(SELECT COUNT(id) FROM ideas_attrib ai WHERE ai.rating = 1 AND ai.id = i.id) posrating,
    	(SELECT COUNT(id) FROM ideas_attrib ai WHERE ai.rating = 0 AND ai.id = i.id) negrating, i.name, i.text, i.type, i.crdate, i.mdate, i.status, i.id_user
                FROM ideas i
                WHERE i.id = $id";
        /** @var $res CDbCommand */
      	$res = Yii::app()->db->createCommand($sql);
        $res = $res->queryAll();

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