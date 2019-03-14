<?php


class CronController extends AppController
{
    public $layout = 'column1';


    public function actionIndex()
    {
        $view = MainConfig::$VIEWS_API;
        $this->render(
            $view, 
            array('viData' => $data, 'id' => $id),
            array(
                'pageTitle' => '<h1>'.$title.'</h1>', 
                'htmlTitle' => $title
            )
        );
    }



    /**
     * Получаем данные по Cron методу
     */
    public function actionCron()
    {
        $this->layout = 'ajax';

        Cron = new Cron();
        $data = Cron->cronProcess();
//        error_code access_token
        echo CJSON::encode($data);
        Yii::app()->end();
    }



    public function actionTestform()
    {
        $this->render('page-testform-tpl');
    }



    /**
     * This is the action to handle external exceptions.
     */
    public function actionError()
    {
        if ($error = Yii::app()->errorHandler->error) {
                echo $error['message'];
//            if (Yii::app()->request->isAjaxRequest)
//            else
//                $this->render('error', array('viData' => $error));
        }
    }
}