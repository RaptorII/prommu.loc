<?php

class AjnotifyController extends AppController
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
     * Получаем новые сообщения пользователя
     */
    public function actionGetusernewmessages()
    {
        $data = (new PushChecker())->getNewUerMessages();

        echo CJSON::encode($data);
        Yii::app()->end();
    }



    /**
     * Получаем новые комментарии пользователя
     */
    public function actionGetusernewcomments()
    {
        $data = (new PushChecker())->getNewUerComments();

//        echo CJSON::encode(array('newcomments' => 5));
        echo CJSON::encode($data);
        Yii::app()->end();
    }
}