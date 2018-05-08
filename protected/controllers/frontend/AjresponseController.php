<?php
/**
 * Created by Vlasakh
 * Date: 17.09.16
 * Time: 6:41
 */


/**
 * Приём Ajax данных для откликов и приглашений
 */
class AjresponseController extends AppController
{
    public function actionIndex()
    {
    }



    /**
     * Получаем вакансии для запроса на приглашение
     */
    public function actionGetvacancies()
    {
//        $data = (new Vacancy())->getVacanciesQueries(['page' => 'invite']);
        $res = (new Vacancy())->getVacancies();
        if( $res )
        {
            foreach ($res['vacs'] as $key => $val)
            {
                if( $val['status'] == 1 ) $data['vacs'][] = $val;
            } // end foreach
        }
        else $data['vacs'] = array();
        echo CJSON::encode($data);
        Yii::app()->end();
    }



    /**
     * Осуществляем приглашение пользователя
     */
    public function actionInvite()
    {
        $res = (new ResponsesApplic())->invite();
        echo CJSON::encode($res);
        Yii::app()->end();
    }
}