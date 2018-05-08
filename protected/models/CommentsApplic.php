<?php
/**
 * Date: 08.09.2016
 *
 * Модель отзывов соискателя
 */

class CommentsApplic extends Comments
{
    function __construct()
    {
        parent::__construct();
    }



    /**
     * Получаем кол-во комментариев
     */
    public function getCommentsCount()
    {
        $sql = "SELECT COUNT(*)
            FROM comments mm
            INNER JOIN employer m ON mm.id_empl = m.id
            WHERE mm.iseorp = 1
              AND mm.isactive = 1
              AND mm.id_promo = {$this->idProfile}";
        /** @var $res CDbCommand */
        $res = Yii::app()->db->createCommand($sql);
        return $res->queryScalar();
    }



    /**
     * Получаем комментарии
     */
    protected function getCommentsData()
    {
        $type = filter_var(Yii::app()->getRequest()->getParam('view', ''), FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        // фильтруем положительные/отрицательные
        if( $type == 'p' ) $filter = ' AND mm.isneg = 0 ' ;
        elseif( $type == 'n' )  $filter = ' AND mm.isneg = 1 ' ;

        $sql = "SELECT mm.id, mm.message, mm.isneg, mm.processed
              , DATE_FORMAT(mm.crdate, '%d.%m.%Y') crdate 
              , m.name fio, m.logo, m.id_user
            FROM comments mm
            INNER JOIN employer m ON mm.id_empl = m.id
            WHERE mm.iseorp = 1
              AND mm.isactive = 1
              {$filter}
              AND mm.id_promo = {$this->idProfile}
            ORDER BY mm.crdate DESC 
            LIMIT {$this->offset}, {$this->limit}";

        /** @var $res CDbCommand */
        $res = Yii::app()->db->createCommand($sql);
        return $res->queryAll();
    }



    /**
     * Фиксируем комментарии как просмотренные
     */
    protected function setCommentsProcessed()
    {
        $id = Share::$UserProfile->exInfo->id_resume;

        $sql = "UPDATE comments 
                INNER JOIN (
                  SELECT mm.id
                  FROM comments mm
                  WHERE mm.iseorp = 1
                    AND mm.processed = 0
                    AND mm.id_promo = {$id} 
                ) t1 ON comments.id = t1.id
                SET processed = 1 ";

        /** @var $res CDbCommand */
        $res = Yii::app()->db->createCommand($sql);
        return $res->execute();
    }
}