<?php
/**
 * Created by Vlasakh
 * Date: 25.07.2016
 * Time: 10:28
 */



/**
 * Class LogError <<Singleton>>
 */
//LogError::write(__CLASS__ . ':' . __METHOD__ . ": copy fail \$data->photo_200 ({$data->photo_200})");
class LogError
{
    private static $instance;

    private function __construct()
    {
    }



    /**
     * Запись в лог
     */
    public static function write($inText)
    {
        if( !self::$instance ) self::$instance = new self();

        self::$instance->logwrite($inText);
    }


    private function logwrite($inText)
    {
        $data = array(
            'text' => $inText,
            'crdate' => date("Y-m-d H:i:s"),
        );
        $data['id_user'] = Share::$UserProfile->id;

        return Yii::app()->db->createCommand()->insert('log_error', $data);
    }
}