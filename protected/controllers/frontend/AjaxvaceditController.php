<?php

class AjaxVacEditController extends AppController
{
    public $layout = '//layouts/ajax';


    function __construct($id, $module = null)
    {
        parent::__construct($id, $module);

        // проверка авторизации
//        $this->doAuth();

        Share::$isAjaxRequest = 1;
    }



    // actionIndex вызывается всегда, когда action не указан явно.
    function actionIndex()
    {
        Yii::app()->end();
    }



    /**
     * Получаем города для радактирования вакансии
     */
    public function actionVeGetCities()
    {
        $id = Yii::app()->getRequest()->getParam('idco') ?: 0;
        $filter = Yii::app()->getRequest()->getParam('query');
        $limit = Yii::app()->getRequest()->getParam('limit', '10');
        $res = (new City)->getCityList($id, $filter, $limit);

        $data = (object)array();
        if( $res )
            foreach ($res as $key => $val)
            {
                $data->suggestions[] = array('data' => $val['id'], 'value' => $val['name'], 'ismetro' => $val['ismetro']);
            } // end foreach
        else
        {
            $data->suggestions[] = array('data' => 'man', 'value' => $filter);
        } // endif

        echo CJSON::encode($data);
        Yii::app()->end();
    }



    /**
     * Сохраняем данные города
     */
    public function actionCityDataSave()
    {
        $data = (new City)->saveCityInfo();

        echo CJSON::encode($data);
        Yii::app()->end();
    }



    /**
     * Сохраняем данные локации
     */
    public function actionLocationdatasave()
    {
        $data = (new City)->saveLocationInfo();

        echo CJSON::encode($data);
        Yii::app()->end();
    }



    /**
     * Получаем данные города определенной вакансии
     */
    public function actionGetvecityblockdata()
    {
        echo CJSON::encode((new City)->getvecityblockdata());
        Yii::app()->end();
    }



    /**
     * Получаем данные локации города
     */
    public function actionGetvelocationdata()
    {
        echo CJSON::encode((new City)->getvelocationdata());
        Yii::app()->end();
    }



    /**
     * Удаляем город вакансии
     */
    public function actionDelvecityblock()
    {
        echo CJSON::encode((new City)->delCityBlock());
        Yii::app()->end();
    }



    /**
     * Удаляем локацию
     */
    public function actionDelvelocation()
    {
        echo CJSON::encode((new City)->delLocation());
        Yii::app()->end();
    }


    /**
     *  Изменяем город
     */
    public function actionCityDataChange()
    {
        echo CJSON::encode((new City)->changeCity());
        Yii::app()->end();
    }


    /**
     *  Изменяем вакансию
     */
    public function actionLocationDataChange()
    {
        echo CJSON::encode((new City)->changeLocation());
        Yii::app()->end();
    }
}