<?php
/**
 * Date: 29.04.2016
 *
 * Модель новостей
 */

class News extends Model
{
    /**
     * Получаем новости
     */
    public function getNews()
    {
        $lang = Yii::app()->session['lang'];
        $sql = "SELECT p.id, p.link
              , pc.name, pc.anons, pc.img
              , DATE_FORMAT(pc.pubdate, '%d.%m.%Y') pubdate
            FROM pages p
            INNER JOIN pages_content pc ON p.id = pc.page_id AND lang = '{$lang}'
            WHERE p.group_id = 2
            ORDER BY pubdate DESC
            LIMIT {$this->offset}, {$this->limit}";
        /** @var $res CDbCommand */
        $res = Yii::app()->db->createCommand($sql);
        $res = $res->queryAll();

        foreach ($res as $key => &$val)
        {
            $val['imgSM'] = "thumbs/" . $val['img'];
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
        $sql = "SELECT p.id, pc.name, pc.html, pc.img, pc.meta_title, pc.meta_description, DATE_FORMAT(pc.crdate, '%d.%m.%Y') crdate
            FROM pages_content pc
            INNER JOIN pages p ON pc.page_id = p.id
            WHERE p.link = '{$inId}'
              AND lang = '{$lang}'";
        /** @var $res CDbCommand */
        $res = Yii::app()->db->createCommand($sql);
        $data = $res->queryRow();
        self::setImgAttributes($data['html']); // устанавливаем атрибуты для картинок
        return array('data' => $data);
    }


    /*
    *   устанавливаем атрибуты alt и title для картинок в тексте
    */
    public function setImgAttributes(&$content)
    {
        $imgCount = substr_count($content, '<img ');

        if($imgCount > 0){  // если есть картинки
            $hPregExp = "'<h[2][^>]*?>.*?</h[2]>'si"; // собираем все заголовки

            preg_match_all($hPregExp, $content, $arHeaders);
            
            if(sizeof($arHeaders[0]) > 0){ // если заголовки найдены
                $arStrContent = explode('<img', $content);
                $newContent = $arStrContent[0];
                $i=1; // начинаем не с начала текста, а с первого найденного img
                while($i < sizeof($arStrContent)){
                    $title = $arHeaders[0][$i-1];
                    $title = strip_tags($title);

                    $newContent .= "<img title='$title' alt='$title'". $arStrContent[$i];
                    $i++;
                }
                $content = $newContent;
            }
        }       
    }
}