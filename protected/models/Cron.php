<?php

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
                case 'rest_one_period': $this->checkMethodHeader(self::$HEADER_GET); $data = $this->mailBox(); break;
                case 'rest_one_hour': $this->checkMethodHeader(self::$HEADER_GET); $data = $this->getRestOneHour(); break;
                case 'rest_cron_test': $this->checkMethodHeader(self::$HEADER_GET); $data = $this->getRestOneTest(); break;
                case 'rest_cron_mailing': $this->checkMethodHeader(self::$HEADER_GET); $data = $this->getRestCronMailing(); break;
                
                
                default: throw new ExceptionApi('No such method', 1001);

            }

        }
        catch (Exception $e) {
            $code = abs($e->getCode());

            switch( $e->getCode() )
            {
                case -1001 : $message = 'No such API method'; break;
                case -1003 : $message = 'Wrong header'; break;
                default: $code= 1002; $message = $e->getMessage();
            }

            $data = ['error' => $code, 'message' => $message];
        } // endtry

        return $data;
    }
    
    public function getRestCronMailing(){
        
        $Mailing = new Mailing();
        $template = new MailingTemplate;
        
        $template = $template->getActiveTemplate();
        
        $receiver = 'denisgresk@gmail.com';
        $title = 'Активируйте аккаунт';
        $rdate = "2019-09-25 14:35";
        $body = str_replace(
                    MailingTemplate::$CONTENT,
                    'Активируйте профиль',
                    $template->body
                );
      
        $set = $Mailing->setToMailingNotActive($receiver,$title,$body,$isUrgent=false, $rdate);
        // $MailingLetter = new MailingLetter();
        // $result = $MailingLetter->getData($_GET['id']);
        
        return $result;
    }
    
    public function getRestOneDay()
    {
        
    }
    
    
    public function mailBox()
    {
      /*
      $text = " Cron отработал";
      $sendto ="https://api.telegram.org/bot525649107:AAFWUj7O8t6V-GGt3ldzP3QBEuZOzOz-ij8/sendMessage?chat_id=@prommubag&text=$text";
      file_get_contents($sendto);
      */
      User::disableUsersOnlineStatus(); // выключаем у юзеров статус "Онлайн"
      GoogleAnalytics::MeasurementProtocol();

      return Mailing::send(); // рассылка событий из очереди событий https://prommu.com/admin/notifications?anchor=tab_system
    }
    
    public function getRestOneTest(){
        echo 'PROMMU CRON';
    }


    /**
     * Проверка на правильный POST/GET заголовок
     * @throws ExceptionApi
     */
    private function checkMethodHeader($headerType)
    {

        switch( $headerType )
        {
            case self::$HEADER_POST : $res = Yii::app()->getRequest()->isPostRequest; break;
            case self::$HEADER_GET : $res = !Yii::app()->getRequest()->isPostRequest;
        }

        if( !$res ) throw new ExceptionApi('', -1003);
    }
    /**
     * 
     */
    public function getRestOneHour()
    {
        $hour = date('G');
        switch ($hour)
        {
            case '0':
                    $yandex = new Yandex();
                    $yandex->generateFile(); // формируем вакансии для Яндекс Работа 2 раза в день
                    (new Jooble())->generateFile(); // generate Jooble xml file (jooble_job.yvl)
                    $termostat = new Termostat;
                    $termostat->sendEmailNotifications(); // рассылка аналитики за месяц 1го числа каждого месяца
                    Im::sendEmailNotifications(); // рассылке уведомлений о наличии непрочитанных сообщений в чатах за прошедший день
                    UserNotifications::setVacancyDateNotificetions(); // установка и сброс уведомлений для пользователя в ЛК по вакансиям
                    UserNotifications::setMSGForHaveNTEmailUsers(); // Send messages to users who did not fill out the email and registration fields
                break;
            case '1':
                UserRegister::setRegisterNotificetions(); // отправляем напоминающие письма товарищам, незавершившим регистрацию
                break;
            case '5':
              //$yandex = new Yandex();
              //$yandex->generateCSVForMetric(); // формируем CSV с офлайн конверсиями для Яндекс метрики и отправляем сразу в ЯМ
              break;
            case '12':
                    $model = new Yandex();
                    $model->generateFile(); // формируем вакансии для Яндекс Работа 2 раза в день
                    (new Jooble())->generateFile(); // generate Jooble xml file (jooble_job.yvl)
                break;
        }
        $vacancy = new Vacancy();
        $vacancy->chkVacsEnds(); // завершение вакансий выполняем каждый час
    }
}
