<?php
/**
 * Работа с API
 * Date: 15.03.19
 * Time: 16:30
 * PROMMU
 */
class Cron
{
    private static $HEADER_POST = 1;
    private static $HEADER_GET = 2;


    public function cronProcess()
    {
        $cronMethod = filter_var(Yii::app()->getRequest()->getParam('cron'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        try
        {
            switch( strtolower($cronMethod) )
            {
                case 'rest_one_day': $this->checkMethodHeader(self::$HEADER_GET); $data = $this->getRestOneDay(); break;
                case 'rest_one_test': $this->checkMethodHeader(self::$HEADER_GET); $data = $this->getTest(); break;

                default: throw new ExceptionApi('No such method', 1001);

            }

        }
        catch (Exception $e) {
            $code = abs($e->getCode());

            switch( $e->getCode() )
            {
                case -1001 : $message = 'No such Cron method'; break;
                case -1003 : $message = 'Wrong header'; break;
                default: $code= 1002; $message = $e->getMessage();
            }

            $data = ['error' => $code, 'message' => $message];
        } 

        return $data;
    }
    
    public function getRestOneDay(){
 
        $Share = new Share();
        $Share->getOnline();
    }
    
    public function getTest(){
        echo "PROMMU TEST";
    }

}
