<?php
/**
 * Задача для запуска раз в 5 мин
 *
 * Created by Vlasakh
 * Date: 12.09.16
 * Time: 17:53
 */

// D:\xampp\php>php.exe D:\Project\prommu\protected\yiic.php task01 init
class Task01Command extends CConsoleCommand
{
    public function actionIndex()
    {
        print "hello index";
    }



    public function actionInit()
    {
        YII_DEBUG && print "Check unread messages:\n";
        $this->checkMessagesApplic();
        $this->checkMessagesEmpl();
    }



    /**
     * Проверяем наличие непрочитанных сообщений соискателей и отправляем mail
     */
    private function checkMessagesApplic()
    {
        $sql = "SELECT ca.id_theme, ca.id_usp, MIN(ca.crdate) crdate
              , u.email
              , ct.title
              , v.title vname
            FROM chat ca 
            INNER JOIN chat_theme ct ON ct.id = ca.id_theme
            INNER JOIN user u ON u.id_user = ca.id_usp
            LEFT JOIN empl_vacations v ON v.id = ct.id_vac
            WHERE ca.is_read = 0 
              AND ca.is_mailnotif = 0
              AND ca.is_resp = 1
            GROUP BY ca.id_theme ";
        /** @var $res CDbCommand */
        $res = Yii::app()->db->createCommand($sql);
        $res = $res->queryAll();

        $data = array();
        foreach ($res as $key => $val)
        {
            $data[$val['id_usp']][] = $val;
        } // end foreach


        // Обрабатываем непрочитанные сообщения
        if( $data )
        {
            foreach ($data as $key => $val)
            {

                $themesStr = '';
                foreach ($val as $key => $val2)
                {
                    $timezone = new DateTimeZone("Europe/Kiev");
                    $datetime1 = new DateTime("now", $timezone);
                    $datetime2 = (new DateTime())->createFromFormat('Y-m-d H:i:s', $val2['crdate'], $timezone);
                    $interval = $datetime1->diff($datetime2);

                    // больше 5 мин
                    if( $interval->format('%i') > 5 )
                    {
                        $themesStr .= sprintf("<b>&laquo;%s&raquo;</b><br/>", $val2['title'] ?: $val2['vname']);
                        $themesIDs[] = $val2['id_theme'];
                    } // endif
                } // end foreach


                // Отправляем письмо
                $message = sprintf("Вы получили новые сообщения на сервисе &laquo;Prommu.com&raquo;
                        в диалогах:
                        <br />
                        %s
                        <br />
                        Нажмите на <a href='%s'>ссылку</a>, чтобы перейти на страницу диалогов.
                        ",
                    $themesStr
                    , Subdomain::site() . MainConfig::$PAGE_CHATS_LIST
                );

                if( $themesStr )
                {
                    Share::sendmail($val2['email'], "Prommu.com. Новые сообщения в диалогах", $message);
                    YII_DEBUG && print $val2['email'] . "\n";
                }
            } // end foreach


            // помечаем как уведемленные
            if( $themesIDs )
            {
                foreach ($themesIDs as $key => $val)
                {
                    $res = Yii::app()->db->createCommand()
                        ->update('chat', array(
                                'is_mailnotif' => 1,
                            ), array('and', 'is_read = 0', 'is_resp = 1', 'is_mailnotif = 0', array('in', 'id_theme', $themesIDs))
                        );
    //                    array(':id_user' => $id));
                } // end foreach
            } // endif
        } // endif
    }



    /**
     * Проверяем наличие непрочитанных сообщений соискателей и отправляем mail
     */
    private function checkMessagesEmpl()
    {
        $sql = "SELECT ca.id, ca.id_theme, ca.id_use, MIN(ca.crdate) crdate
              , u.email
              , ct.title
              , v.title vname
            FROM chat ca 
            INNER JOIN chat_theme ct ON ct.id = ca.id_theme
            INNER JOIN user u ON u.id_user = ca.id_use
            LEFT JOIN empl_vacations v ON v.id = ct.id_vac
            WHERE ca.is_read = 0 
              AND ca.is_mailnotif = 0
              AND ca.is_resp = 0
            GROUP BY ca.id_theme  ";
        /** @var $res CDbCommand */
        $res = Yii::app()->db->createCommand($sql);
        $res = $res->queryAll();

        $data = array();
        foreach ($res as $key => $val)
        {
            $data[$val['id_use']][] = $val;
        } // end foreach


        // Обрабатываем напрочитанные сообщения
        if( $data )
        {
            foreach ($data as $key => $val)
            {

                $themesStr = '';
                foreach ($val as $key => $val2)
                {
                    $timezone = new DateTimeZone("Europe/Kiev");
                    $datetime1 = new DateTime("now", $timezone);
                    $datetime2 = (new DateTime())->createFromFormat('Y-m-d H:i:s', $val2['crdate'], $timezone);
                    $interval = $datetime1->diff($datetime2);

                    // больше 5 мин
                    if( $interval->format('%i') > 5 )
                    {
                        $themesStr .= sprintf("<b>&laquo;%s&raquo;</b><br/>", $val2['title'] ?: $val2['vname']);
                        $themesIDs[] = $val2['id_theme'];
                    } // endif
                } // end foreach


                // Отправляем письмо
                $message = sprintf("Вы получили новые сообщения на сервисе &laquo;Prommu.com&raquo;
                        в диалогах:
                        <br />
                        %s
                        <br />
                        Нажмите на <a href='%s'>ссылку</a>, чтобы перейти на страницу диалогов.
                        ",
                    $themesStr
                    , Subdomain::site() . MainConfig::$PAGE_CHATS_LIST
                );

                if( $themesStr ) {
                    Share::sendmail($val2['email'], "Prommu.com. Новые сообщения в диалогах", $message);
                    YII_DEBUG && print $val2['email'] . "\n";
                }
            } // end foreach


            // помечаем как уведемленные
            if( $themesIDs )
            {
                foreach ($themesIDs as $key => $val)
                {
                    $res = Yii::app()->db->createCommand()
                        ->update('chat', array(
                                'is_mailnotif' => 1,
                            ), array('and', 'is_read = 0', 'is_resp = 0', 'is_mailnotif = 0', array('in', 'id_theme', $themesIDs))
                        );
    //                    array(':id_user' => $id));
                } // end foreach
            }
            else
            {
            } // endif
        } // endif
    }
}