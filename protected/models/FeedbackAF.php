<?php
/**
 * Date: 07.10.2016
 *
 * Модель обратной связи
 */

class FeedbackAF extends CActiveRecord
{
    public $autotype;
    public $type;
    public $name;
    public $theme;
    public $email;
    public $text;
    public $referer;
    public $transition;
    public $canal;
    public $campaign;
    public $content;
    public $keywords;
    public $point;
    public $last_referer;


    public function tableName()
	{
		return 'feedback';
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


    /**
     * получаем данные для формы
     */
    public function getData()
    {


        $id = filter_var(Yii::app()->getRequest()->getParam('id'), FILTER_SANITIZE_NUMBER_INT);

        $sql = "SELECT s.id, s.name, DATE_FORMAT(s.crdate, '%d.%m.%Y') crdate
                FROM services s WHERE s.id = {$id}";
        return array(); //Yii::app()->db->createCommand($sql)->queryRow();
    }



    /**
     * Сохраняем отзыв
     */
    public function saveData()
    {
        $autotype = filter_var(Yii::app()->getRequest()->getParam('autotype'), FILTER_SANITIZE_NUMBER_INT);
        $type = filter_var(Yii::app()->getRequest()->getParam('type', 0), FILTER_SANITIZE_NUMBER_INT);
        $name = filter_var(Yii::app()->getRequest()->getParam('name'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $theme = filter_var(Yii::app()->getRequest()->getParam('theme'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $email = filter_var(Yii::app()->getRequest()->getParam('email'), FILTER_SANITIZE_EMAIL);
        $text = filter_var(Yii::app()->getRequest()->getParam('text'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $referer = filter_var(Yii::app()->getRequest()->getParam('referer'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $transition = filter_var(Yii::app()->getRequest()->getParam('transition'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $canal = filter_var(Yii::app()->getRequest()->getParam('canal'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $campaign = filter_var(Yii::app()->getRequest()->getParam('campaign'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $content = filter_var(Yii::app()->getRequest()->getParam('content'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $keywords = filter_var(Yii::app()->getRequest()->getParam('keywords'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $point = filter_var(Yii::app()->getRequest()->getParam('point'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $last_referer = filter_var(Yii::app()->getRequest()->getParam('last_referer'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $text = substr($text, 0, 5000);

        $type = $autotype ?: $type;

        $res = Yii::app()->db->createCommand()
            ->insert('feedback', array(
                'type' => $type,
                'name' => $name,
                'theme' => $theme,
                'text' => $text,
                'email' => $email,
                'crdate' => date("Y-m-d H:i:s"),
                'referer' => $referer,
                'transition' => $transition,
                'canal' => $canal,
                'campaign' => $campaign,
                'content' => $content,
                'keywords' => $keywords,
                'point' => $point,
                'last_referer' => $last_referer,
            ));

        $message = sprintf("На сайте <a href='http://%s'>http://%1$01s</a> было оставлено сообщение через обратную связь 
                <br/>
                <br/>
                Пользователь: <b>%s</b>  
                <br/>
                Тема: <b>%s</b>  
                <br/>
                Email: <b>%s</b>  
                <br/>
                <br/>
                Сообщение:
                <br/>
                &laquo;%s&raquo;
                ------------------------------------------------------
                Тип трафика: <b>%s</b>
                <br/>
                Источник: <b>%s</b>
                <br/>
                Канал: <b>%s</b>
                <br/>
                Кампания: <b>%s</b>
                <br/>
                Контент: <b>%s</b>
                <br/>
                Ключевые слова: <b>%s</b>
                <br/>
                Точка входа: <b>%s</b>
                <br/>
                Реферер: <b>%s</b>\",
",
            Subdomain::getSiteName(), $name, $theme, $email, $text, $referer, $transition, $canal, $campaign, $content, $keywords, $point, $last_referer);

        $email = (new Options)->getOption('moderEmail')->val;
//        $email = "Zotaper@localhost.com";
//        $email = "Zotaper@yandex.ru";
        Share::sendmail($email, "Prommu: сообщение через обратную связь", trim($message));

        Yii::app()->user->setFlash('Message', ['message' => 'Ваше обращение принято в обработку. После обработки вашего письма нашими менеджерами, мы свяжемся с вами', 'type' => $type]);

        return array('res' => $res);
    }
}