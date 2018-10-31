<?php

class Cache
{
    /**
     * @param $id string - cache ID
     * @return array - ['city','controller','page','id','data']
     * Получаем кеш страницы
     */
    public static function getData($id = '')
    {
        $arRes['city'] = Subdomain::$HOST;
        $arRes['controller'] = Yii::app()->controller->id;
        $arRes['page'] = Yii::app()->request->requestUri;
        if(!empty($id))
            $arRes['id'] = $id;
        else
            $arRes['id'] = $arRes['city'] . DS . $arRes['controller'] . $arRes['page'];
        $arRes['data'] = Yii::app()->cache->get($arRes['id']);
        return $arRes;
    }
    /**
     * @param $data array - ['id','data']
     * @param $time number - time cache (default - 1 hour )
     * Устанавливаем кеш страницы
     */
    public static function setData($data, $time = 3600)
    {
        if(empty($data['id']) || empty($data['data']))
            return false;
        
        Yii::app()->cache->set($data['id'], $data['data'], $time);
    }
}
