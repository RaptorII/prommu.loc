<?php
/**
 * Date: 07.06.2016
 *
 * Модель заказа карты промму
 */

/**
 * @deprecated 
 */
class Cardprommu extends Model
{
    /**
     * aaaaaaaaaaaa
     */
    public function getNews()
    {
        $lang = Yii::app()->session['lang'];
        $sql = "SELECT p.id, p.link
              , pc.name, pc.anons, pc.img
              , DATE_FORMAT(pc.crdate, '%d.%m.%Y') crdate
            FROM pages p
            INNER JOIN pages_content pc ON p.id = pc.page_id AND lang = '{$lang}'
            WHERE p.group_id = 2
            ORDER BY crdate DESC
            LIMIT {$this->offset}, {$this->limit}";
        /** @var $res CDbCommand */
        $res = Yii::app()->db->createCommand($sql);
        $res = $res->queryAll();

        foreach ($res as $key => &$val)
        {
//            $file = pathinfo($val['img']);
//            $val['imgSM'] = sprintf('%s-sm.%s', $file['filename'], $file['extension']) ;
            $val['imgSM'] = "thumbs/" . $val['img'];
//            $val['sname'] = Share::getShortText($val['html'], 300) ;
        } // end foreach

        return $res;
    }



    /**
     * Получаем новости
     */
    public function getLastNews($inId)
    {
        $lang = Yii::app()->session['lang'];
        $sql = "SELECT p.id, p.link
              , pc.name, pc.anons, pc.img
              , DATE_FORMAT(pc.crdate, '%d.%m.%Y') crdate
            FROM pages p
            INNER JOIN pages_content pc ON p.id = pc.page_id AND lang = '{$lang}' AND pc.page_id <> '{$inId}'
            WHERE p.group_id = 2
            ORDER BY crdate DESC
            LIMIT 4";
        /** @var $res CDbCommand */
        $res = Yii::app()->db->createCommand($sql);
        $res = $res->queryAll();

        foreach ($res as $key => &$val)
        {
//            $file = pathinfo($val['img']);
//            $val['imgSM'] = sprintf('%s-sm.%s', $file['filename'], $file['extension']) ;
            $val['imgSM'] = "thumbs/" . $val['img'];
//            $val['sname'] = Share::getShortText($val['html'], 300) ;
        } // end foreach

        return $res;
    }



    /**
     * Получаем новости
     */
    public function getNewsCount()
    {
        $lang = Yii::app()->session['lang'];
        $sql = "SELECT COUNT(*)
            FROM pages p
            INNER JOIN pages_content pc ON p.id = pc.page_id AND lang = '{$lang}'
            WHERE p.group_id = 2";
        /** @var $res CDbCommand */
        $res = Yii::app()->db->createCommand($sql);
        return $res->queryScalar();
    }



    /**
     * Получаем тело новости
     */
    public function getNewsSingle($inId)
    {
        $lang = Yii::app()->session['lang'];
        $sql = "SELECT pc.name, pc.html, pc.img, DATE_FORMAT(pc.crdate, '%d.%m.%Y') crdate
            FROM pages_content pc
            WHERE pc.page_id = '{$inId}'
              AND lang = '{$lang}'";
        /** @var $res CDbCommand */
        $res = Yii::app()->db->createCommand($sql);
        return array('data' => $res->queryRow());
    }
}