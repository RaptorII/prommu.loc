<?php
/**
 * Date: 07.09.2016
 *
 * Проверяем различные счётчики для уведомления пользователя
 */

class PushChecker extends Model
{
    /** @var  UserProfile */
    protected $Profile;


    function __construct($Profile = null)
    {
        $this->Profile = $Profile instanceof UserProfile ? $Profile : Share::$UserProfile;
    }



    /**
     * Получаем кол-во новых заявок на вакансии
     */
    public function getNewUerMessages()
    {
        $id = $this->Profile->id;

        if( $this->Profile->type == 2 )
        {
            $resp = 1;
            $where = 'id_usp';
        }
        else
        {
            $resp = 0;
            $where = 'id_use';
        } // endif

        $sql = "SELECT ct.id
              , coun.countn count
              -- , em.name, em.logo, em.firstname nnn, em.lastname fff
            FROM chat_theme ct
            INNER JOIN (
                SELECT ca.id_theme, COUNT(*) countn FROM chat ca WHERE ca.is_read = 0 AND ca.is_resp = {$resp} GROUP BY ca.id_theme 
            ) coun ON coun.id_theme = ct.id
            WHERE ct.{$where} = {$id}";
        /** @var $res CDbCommand */
        $res = Yii::app()->db->createCommand($sql);
        $data['newmessages'] = $res->queryAll();
        $data['vacancy_public_mess_cnt'] = VacDiscuss::publicVacChatCnt();

        return $data;
    }



    /**
     * Получаем кол-во новых отзывов
     */
    public function getNewUerComments()
    {
        if( $this->Profile->type == 3 )
        {
            $id = $this->Profile->exInfo->eid;

            $sql = "SELECT COUNT(m.id) cou
                FROM comments mm
                INNER JOIN resume m ON mm.id_promo = m.id
                WHERE mm.iseorp = 0 
                  AND mm.processed = 0
                  AND mm.isactive = 1
                  AND mm.id_empl = {$id} ";
        }
        else
        {
            $id = $this->Profile->exInfo->id_resume;

            $sql = "SELECT COUNT(m.id) cou
                FROM comments mm
                INNER JOIN employer m ON mm.id_empl = m.id
                WHERE mm.iseorp = 1
                  AND mm.processed = 0
                  AND mm.isactive = 1
                  AND mm.id_promo = 1 ";
        } // endif

        /** @var $res CDbCommand */
        $res = Yii::app()->db->createCommand($sql);
        $data['newcomments'] = $res->queryScalar();

        return $data;
    }
}