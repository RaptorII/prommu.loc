<?php
/**
 * Date: 08.09.2016
 *
 * Модель отзывов работодателя
 */

class CommentsEmpl extends Comments
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
            INNER JOIN resume m ON mm.id_promo = m.id
            WHERE mm.iseorp = 0
              AND mm.isactive = 1
              AND mm.id_empl = {$this->idProfile}";
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
              , CONCAT(m.firstname,' ',m.lastname) fio, m.photo, m.id_user
            FROM comments mm
            INNER JOIN resume m ON mm.id_promo = m.id
            WHERE mm.iseorp = 0
              AND mm.isactive = 1
              {$filter}
              AND mm.id_empl = {$this->idProfile}
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
            $id = Share::$UserProfile->exInfo->eid;

            $sql = "UPDATE comments 
                    INNER JOIN (
                        SELECT id
                        FROM comments mm
                        WHERE mm.iseorp = 0 
                          AND mm.processed = 0
                          AND mm.id_empl = {$id}
                    ) t1 ON comments.id = t1.id
                    SET processed = 1 ";

        /** @var $res CDbCommand */
        $res = Yii::app()->db->createCommand($sql);
        return $res->execute();
    }

}