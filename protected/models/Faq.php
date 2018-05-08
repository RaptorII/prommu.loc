<?php
/**
 *degres
 * Модель статей
 */

class Faq extends Model
{

	public function getFaq()
    {
  
        $sql = "SELECT f.id, f.answer, f.question, f.theme, f.type
            FROM faq_api f";
        /** @var $res CDbCommand */
        $res = Yii::app()->db->createCommand($sql);
        $res = $res->queryAll();

        

        return $res;
    }


}

?>