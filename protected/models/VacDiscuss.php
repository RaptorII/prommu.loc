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
    public function getDiscuss($inIdVac)
    {
        $sql = "SELECT d.id, d.id_user, d.mess,
                   DATE_FORMAT(d.crdate, '%H:%i %d.%m.%y') crdate
                , r.firstname, r.lastname
                , e.name
            FROM emplv_discuss d
            LEFT JOIN resume r ON r.id_user = d.id_user
            LEFT JOIN employer e ON e.id_user = d.id_user
            WHERE id_vac = {$inIdVac}
            ORDER BY d.crdate DESC
            LIMIT {$this->offset}, {$this->limit}";
        /** @var $res CDbCommand */
        $res = Yii::app()->db->createCommand($sql);
        $res = $res->queryAll();

        return $res;
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
            $res = Yii::app()->db->createCommand()
                ->insert('emplv_discuss', array(
                    'id_vac' => $id,
                    'id_user' => Share::$UserProfile->id,
                    'mess' => $mess,
                    'crdate' => date("Y-m-d H:i:s"),
                ));

            $error = 0;
            $message = "Сообщение добавлено";
        }
        else
        {
            $error = 1;
            $message = "Ошибка добавления сообщения";
        } // endif

        Yii::app()->user->setFlash('data', array('error' => $error, 'message' => $message));

        return array('error' => $error);
    }
}