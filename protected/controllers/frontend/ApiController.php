<?php


class ApiController extends AppController
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
     * Получаем данные по api методу
     */
    public function actionApi()
    {
        $this->layout = 'ajax';

        $Api = new Api();
        $data = $Api->apiProcess();
//        error_code access_token
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
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