<?php
/**
 * Date: 21.12.2016
 * GreskoD
 * Модель обратной связи
 */

class Feedback extends Model
{
    /**
     * получаем данные для формы
     */

     public function getDatas($id)
    {


        // $id = filter_var(Yii::app()->getRequest()->getParam('id'), FILTER_SANITIZE_NUMBER_INT);

        $sql = "SELECT f.id, f.type, f.theme,f.chat, f.email, f.text, f.name, DATE_FORMAT(f.crdate, '%d.%m.%Y') crdate
                FROM feedback f WHERE f.id = {$id}";
        $res = Yii::app()->db->createCommand($sql);
        $data = $res->queryAll();
        return  $data;
    }

     public function getDatAdmin()
    {


        // $id = filter_var(Yii::app()->getRequest()->getParam('id'), FILTER_SANITIZE_NUMBER_INT);

        $sql = "SELECT f.id, f.type, f.theme,f.chat, f.email, f.text, f.name, DATE_FORMAT(f.crdate, '%d.%m.%Y') crdate
                FROM feedback f WHERE f.is_smotr = 0 AND crdate >= CURDATE()
                ORDER BY f.id DESC, f.id DESC
                LIMIT 100";
        $res = Yii::app()->db->createCommand($sql);
        $data = $res->queryAll();
        return  $data;
    }

    public function getData()
    {
    	/*
        $id = filter_var(Yii::app()->getRequest()->getParam('id'), FILTER_SANITIZE_NUMBER_INT);

        $sql = "SELECT s.id, s.name, DATE_FORMAT(s.crdate, '%d.%m.%Y') crdate
                FROM services s WHERE s.id = {$id}";
        return array(); 
        */
        //Yii::app()->db->createCommand($sql)->queryRow();

        return array(
        	'type' => filter_var(Yii::app()->getRequest()->getParam('type', 0), FILTER_SANITIZE_NUMBER_INT),
        	'name' => filter_var(Yii::app()->getRequest()->getParam('name'), FILTER_SANITIZE_FULL_SPECIAL_CHARS),
        	'theme' => filter_var(Yii::app()->getRequest()->getParam('name'), FILTER_SANITIZE_FULL_SPECIAL_CHARS),
        	'email' => filter_var(Yii::app()->getRequest()->getParam('email'), FILTER_SANITIZE_EMAIL),
        	'text' => filter_var(Yii::app()->getRequest()->getParam('text'), FILTER_SANITIZE_FULL_SPECIAL_CHARS)
        );
    }

    public function setFeedback($cloud){
   
        Yii::app()->db->createCommand()
            ->update('feedback', array(
                'chat' => $cloud['chat'],
            ), 'id=:id', array(':id' => $cloud['id']));

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
        $emails = filter_var(Yii::app()->getRequest()->getParam('email'), FILTER_SANITIZE_EMAIL);
        $text = filter_var(Yii::app()->getRequest()->getParam('text'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $referer = filter_var(Yii::app()->getRequest()->getParam('referer'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $transition = filter_var(Yii::app()->getRequest()->getParam('transition'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $canal = filter_var(Yii::app()->getRequest()->getParam('canal'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $campaign = filter_var(Yii::app()->getRequest()->getParam('campaign'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $content = filter_var(Yii::app()->getRequest()->getParam('content'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $id = filter_var(Yii::app()->getRequest()->getParam('id'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $keywords = filter_var(Yii::app()->getRequest()->getParam('keywords'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $point = filter_var(Yii::app()->getRequest()->getParam('point'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $last_referer = filter_var(Yii::app()->getRequest()->getParam('last_referer'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $text = substr($text, 0, 5000);
        $roistat = (isset($_COOKIE['roistat_visit'])) ? $_COOKIE['roistat_visit'] : "(none)";

        $arErrors = false;
        if(empty($id)){
        	$recaptcha = Yii::app()->getRequest()->getParam('g-recaptcha-response');
			if(!empty($recaptcha))
			{
				$google_url="https://www.google.com/recaptcha/api/siteverify";
				$secret='6Lf2oE0UAAAAAPkKWuPxJl0cuH7tOM2OoVW5k6yH';
				$ip=$_SERVER['REMOTE_ADDR'];
				$url=$google_url."?secret=".$secret."&response=".$recaptcha."&remoteip=".$ip;
				//
				$curl = curl_init();
				curl_setopt($curl, CURLOPT_URL, $url);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($curl, CURLOPT_TIMEOUT, 10);
				curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16");
				$res = curl_exec($curl);
				curl_close($curl);
				//
				$res = json_decode($res, true);//reCaptcha введена
				if(!$res['success']) // wrong captcha
				{
				  $arErrors = array('ERROR'=>'captcha','MESSAGE'=>'Вы допустили ошибку при прохождении проверки "Я не робот"');
				}
			}
			else
			{
				$arErrors = array('ERROR'=>'captcha','MESSAGE'=>'Необходимо пройти проверку "Я не робот"');
			}        	
        }

        if(!$arErrors){
	        if($autotype == 2) {
	            $texts = "Добрый день, $name. Ваш вопрос '$text' на рассмотрении";
	            $message = $texts;
	            $new = $id;
	            $idus = 1766;
	            $idTm = $theme;
	            $Im = new ImEmpl();
	            $figaro = compact('message', 'new', 'idus','idTm', 'theme');
	            $resu = $Im->sendUserMessages($figaro);
	        }
	        if($autotype == 3) {
	            $texts = "Добрый день, $name. Ваш вопрос '$text' на рассмотрении";
	            $message = $texts;
	            $new = $id;
	            $idus = 2054;
	            $idTm = $theme;
	            $Im = new ImApplic();
	            $figaro = compact('message', 'new', 'idus','idTm', 'theme');
	            $resu = $Im->sendUserMessages($figaro);

	        }
	        
	        $texs = "Пользователь $name оставил обращение по обратной связи: $text. Необходима модерация https://prommu.com/admin/feedback";
            $sendto ="https://api.telegram.org/bot525649107:AAFWUj7O8t6V-GGt3ldzP3QBEuZOzOz-ij8/sendMessage?chat_id=@prommubag&text=$texs";
            file_get_contents($sendto);

	        if(!empty($resu['idtm'])){
	            $res = Yii::app()->db->createCommand()
	            ->insert('feedback', array(
	                'type' => $autotype,
	                'name' => $name,
	                'theme' => $theme,
	                'text' => $text,
	                'email' => $emails,
	                'crdate' => date("Y-m-d"),
	                'chat' => $resu['idtm'],
	                'content' => $content,
	                'referer' => $referer,
	                'last_referer' => $last_referer,
	                'point' => $point,
	                'keywords' => $keywords,
	                'canal' => $canal, 
	                'campaign' => $campaign,
	                'transition' => $transition));
	        }
	        else 
	            $res = Yii::app()->db->createCommand()
	            ->insert('feedback', array(
	                'type' => $autotype,
	                'name' => $name,
	                'theme' => $theme,
	                'text' => $text,
	                'email' => $emails,
	                'crdate' => date("Y-m-d H:i:s"),
	                'content' => $content,
	            	'referer' => $referer,
	                'last_referer' => $last_referer,
	                'point' => $point,
	                'keywords' => $keywords,
	                'canal' => $canal, 
	                'campaign' => $campaign,
	                'transition' => $transition));

	        $message = '<p style="font-size:16px;text-align: center">Здравствуйте'.$name.'</p>
	                    <br/>

	                <p style=" font-size:16px;">
	                    <br/>
	               Ваше обращение поставлено в очередь на рассмотрение.</p>
	                    <br/>
	                ';
	        Share::sendmail($emails, "Prommu.com. Пришло сообщение ", $message);
	    
	        $message = sprintf("На сайте <a href='http://%s'>prommu.com</a> было оставлено сообщение через обратную связь 
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
	                <br/>
	                ----------------------------------------------------------
	                </br>
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
	                Реферер: <b>%s</b>
	                <br/>
	                Roistat: <b>%s</b>  ",
	        MainConfig::$SITE, $name, $theme, $emails, $text, $referer, $transition, $canal, $campaign, $content, $keywords, $point, $last_referer, $roistat);
	        $messages = sprintf("Добрый день! Ваш вопрос: $theme зарегистрирован под № $id
	        	В ближайшее время Вы получите ответ. Спасибо что выбрали наш Сервис!");
	           
	        $email[0] = "denisgresk@gmail.com";
	        $email[1] = "man.market2@gmail.com";
	        $email[2] = "mk0630733719@gmail.com";
	        $email[3] = "e.market.easss@gmail.com"; 
	        $email[4] = "projekt.sergey@gmail.com";
	        $email[5] = "manag_reports@euro-asian.ru";
	        $email[6] = "e.marketing@euro-asian.ru";
	        $email[7] = "site.adm@euro-asian.ru";
	        for($i = 0; $i <8; $i++){
	            Share::sendmail($email[$i], "Prommu: сообщение через обратную связь", trim($message));
	        }
	    
	        // Share::sendmail($emails, "Prommu: сообщение через обратную связь", trim($messages));

	        Yii::app()->user->setFlash('Message', ['message' => 'Ваше обращение принято в обработку. После обработки вашего письма нашими менеджерами, мы свяжемся с вами', 'type' => $type]);

	        return array('res' => $res);
	    }
	    else{
	    	return $arErrors;
	    }
    }

    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria=new CDbCriteria;

        $criteria->compare('id',$this->id,true);
        $criteria->compare('type',$this->type);
        $criteria->compare('name',$this->name,true);
        $criteria->compare('theme',$this->theme,true);
        $criteria->compare('email',$this->email,true);
        $criteria->compare('text',$this->text,true);
        $criteria->compare('crdate',$this->crdate,true);
        $criteria->compare('pid',$this->pid);
         $criteria->compare('chat',$this->chat);
        $criteria->compare('is_smotr',$this->is_smotr);
        $criteria->compare('date_smotr',$this->date_smotr,true);

        return new CActiveDataProvider('Feedback', array(
            'criteria'=>$criteria,
        ));
    }

     public function deleteFeedback($id){
        Yii::app()->db->createCommand()
            ->delete('feedback', 'id=:id', array(':id' => $id));
    }

    public function ChangeModer($id, $status){

        $res = Yii::app()->db->createCommand()
                ->update('feedback', array(
                    'is_smotr' => $status,
                ), 'id=:id', array(':id' => $id));
    }
}