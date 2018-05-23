<?php

class PrommuOrder {

     public function getOrderAdmin()
    {

        $sql = "SELECT DISTINCT r.name as id, r.type
            FROM service_cloud r
            WHERE r.date >= CURDATE()
            ORDER BY id DESC, id DESC";
        $result = Yii::app()->db->createCommand($sql)->queryAll();

        return $result;
    }
    //Определение цены использования услуги
     public function servicePrice($user, $service){
       
      
        $sql = "SELECT r.id_user, r.type
            FROM service_cloud r
            WHERE r.id_user = $user AND r.status = 1 AND r.type = '$service'";
        $result = Yii::app()->db->createCommand($sql)->queryAll();

        $sql = "SELECT first, second, type, service
            FROM service_price 
            WHERE service = '$service'";
        $results = Yii::app()->db->createCommand($sql)->queryRow();

        for($i = 0; $i < count($result); $i ++) {
   
            if($result[$i]['type'] == $service){
               $price = $results['second'];
            } else $price = $results['first'];
        }

        return $price;

    }
    
    public function serviceOrderSms($id_user,$sum, $status, $postback, $from, $to, $name,$type, $text, $id){

        if($postback == 0) {
            $sql = "SELECT  e.title
                FROM empl_vacations e
                WHERE e.id = {$name}";
            $vacancy = Yii::app()->db->createCommand($sql)->queryAll();

             $sql = "SELECT  u.email, e.name, e.firstname, e.lastname 
                FROM employer e
                LEFT JOIN user u ON u.id_user = e.id_user
                WHERE e.id_user = {$id_user}";
            $empl = Yii::app()->db->createCommand($sql)->queryAll();

            $message = '<p style="font-size:16px;">На сайте prommu.com была оплачена услуга Смс Информирование Персонала</p>
                    <br/>

                <p style=" font-size:16px;">
               Пользователь: '.$id_user.' '.$empl[0]['lastname'].' '.$empl[0]['firstname'].'<br/>
                <br/>Компания: '.$empl[0]['name'].'<br/>
               Вакансия: '.$name.' '.$vacancy[0]['title'].'
                    <br/>';
            Share::sendmail('denisgresk@gmail.com', "Prommu.com. Заказ Услуги Смс Информирование!", $message);
            $res = Yii::app()->db->createCommand()
                        ->insert('service_cloud', array('id_user' => $id_user,
                                'name' => $name,
                                'type' => $type, 
                                'bdate' => $from,
                                'edate' => $to,
                                'status' => $status,
                                'sum' => $sum,
                                'text' => $text,
                                'user' => $id
                            ));
        } else {

            $res = Yii::app()->db->createCommand()
                ->update('service_cloud', array(
                    'status' => $status,
                ), 'id_user=:id_user AND sum=:sum', array(':id_user' => $id_user, ':sum' => $sum));
            $sql = "SELECT  e.title
                FROM empl_vacations e
                WHERE e.id = {$name}";
            $vacancy = Yii::app()->db->createCommand($sql)->queryAll();

             $sql = "SELECT  u.email, e.name, e.firstname, e.lastname 
                FROM employer e
                LEFT JOIN user u ON u.id_user = e.id_user
                WHERE e.id_user = {$id_user}";
            $empl = Yii::app()->db->createCommand($sql)->queryAll();

            $message = '<p style="font-size:16px;">На сайте prommu.com была оплачена услуга Смс Информирование Персонала</p>
                    <br/>

                <p style=" font-size:16px;">
               Пользователь: '.$id_user.' '.$empl[0]['lastname'].' '.$empl[0]['firstname'].'<br/>
                <br/>Компания: '.$empl[0]['name'].'<br/>
               Вакансия: '.$name.' '.$vacancy[0]['title'].'
                    <br/>';
            Share::sendmail('denisgresk@gmail.com', "Prommu.com. Заказ Услуги Смс Информирование!", $message);
            Share::sendmail('dsale_1@plan-o-gram.ru', "Prommu.com. Заказ Услуги Смс Информирование!", $message);
            Share::sendmail('mk0630733719@gmail.com', "Prommu.com. Заказ Услуги Смс Информирование!", $message);
            Share::sendmail('Job@mandarin-agency.ru', "Prommu.com. Заказ Услуги Смс Информирование!", $message);
            Share::sendmail('e.market.easss@gmail.com', "Prommu.com. Заказ Услуги Смс Информирование!", $message);
            Share::sendmail('client@btl-me.ru', "Prommu.com. Заказ Услуги Смс Информирование!", $message);
            Share::sendmail('prommucom@gmail.com', "Prommu.com. Заказ Услуги Смс Информирование!", $message);

        }


    }

	public function serviceOrder($id_user,$sum, $status, $postback, $from, $to, $name,$type){

        if($postback == 0) {
                

            $to = date( 'Y-m-d', strtotime($to)); 
            $from = date( 'Y-m-d H:i', strtotime($from)); 

        	$res = Yii::app()->db->createCommand()
                        ->insert('service_cloud', array('id_user' => $id_user,
                                'name' => $name,
                                'type' => $type, 
                                'bdate' => $from,
                                'edate' => $to,
                                'status' => $status,
                                'sum' => $sum,
                                'date' => date("Y-m-d"),
                            ));
        } else {

        	$res = Yii::app()->db->createCommand()
                ->update('service_cloud', array(
                    'status' => $status,
                ), 'id_user=:id_user AND sum=:sum', array(':id_user' => $id_user, ':sum' => $sum));

            $sql = "SELECT  e.title
                FROM empl_vacations e
                WHERE e.id = {$name}";
            $vacancy = Yii::app()->db->createCommand($sql)->queryAll();

             $sql = "SELECT  u.email, e.name, e.firstname, e.lastname 
                FROM employer e
                LEFT JOIN user u ON u.id_user = e.id_user
                WHERE e.id_user = {$id_user}";
            $empl = Yii::app()->db->createCommand($sql)->queryAll();

            $message = '<p style="font-size:16px;">На сайте prommu.com была оплачена услуга Премиум Вакансия</p>
                    <br/>

                <p style=" font-size:16px;">
               Пользователь: '.$id_user.' '.$empl[0]['lastname'].' '.$empl[0]['firstname'].'<br/>
                <br/>Компания: '.$empl[0]['name'].'<br/>
               Вакансия: '.$name.' '.$vacancy[0]['title'].'
                    <br/>';
            Share::sendmail('denisgresk@gmail.com', "Prommu.com. Заказ Услуги Премиум Вакансия!", $message);
            Share::sendmail('dsale_1@plan-o-gram.ru', "Prommu.com. Заказ Услуги Премиум Вакансия!", $message);
            Share::sendmail('mk0630733719@gmail.com', "Prommu.com. Заказ Услуги Премиум Вакансия!", $message);
            Share::sendmail('Job@mandarin-agency.ru', "Prommu.com. Заказ Услуги Премиум Вакансия!", $message);
            Share::sendmail('e.market.easss@gmail.com', "Prommu.com. Заказ Услуги Премиум Вакансия!", $message);
            Share::sendmail('client@btl-me.ru', "Prommu.com. Заказ Услуги Премиум Вакансия!", $message);
            Share::sendmail('prommucom@gmail.com', "Prommu.com. Заказ Услуги Премиум Вакансия!", $message);

        }


	}



    public function outstaffing($data){
        $id = $data['account'];
        $sql = "SELECT  r.id, r.firstname, r.lastname, r.name
            FROM employer r
            LEFT JOIN user u ON u.id_user = r.id_user
            WHERE r.id_user = {$id}";
        $res = Yii::app()->db->createCommand($sql)->queryAll();

        $name = $res[0]['name'];
        $firstname = $res[0]['firstname'];
        $lastname = $res[0]['lastname'];


        $email = $data['email'] ? $data['email']:"";
        $phone = $data['phone'] ? $data['phone']:"";
        $other = $data['other-contact'];
        $vacancy = $data['vacancy'][0];
        $id = $data['account'];
        $consult = $data['consultation'];
        $rezident = $data['registration-rf'];
        $nrezident = $data['registration-mr'];
        $advertising = $data['advertising'];
        $control = $data['control'];
        $type = $data['service'];

        $sql = "SELECT e.title
            FROM empl_vacations e 
            WHERE e.id = {$vacancy}";
        $resu = Yii::app()->db->createCommand($sql)->queryAll();
        $title = $resu[0]['title'];
        if($type == "outstaffing") {
             $res = Yii::app()->db->createCommand()
                        ->insert('outstaffing', array('id' => $id,
                                'consult' => $consult,
                                'rezident' => $rezident, 
                                'nrezident' => $nrezident,
                                'vacancy' => $vacancy,
                                'text' => $other,
                                'email' => $email,
                                'phone' => $phone,
                                'type' => $type,
                            ));
        $message = '<p style="font-size:16px;">На сайте prommu.com был оставлен запрос от клиента по Услуге Аутстаффинг </p>
                    <br/>

                 <p style=" font-size:16px;">
                    <br/>
               Пользователь: ' .$id.' '.$lastname.' '.$firstname.'
               <br/>Компания: '.$name.'<br/>
               Услуги: '.$nrezident.', '.$rezident.', '.$consult.' <br/>Вакансия: '.$vacancy.' '.$title.'<br/>
               Контактные данные: '.$phone.' '.$email.'<br/>
                Сообщение:'.$other.'</p>';
            Share::sendmail('denisgresk@gmail.com', "Prommu.com. Заказ Услуги Аутсорсинга!", $message);
            Share::sendmail('dsale_1@plan-o-gram.ru', "Prommu.com. Заказ Услуги Аутсорсинга!", $message);
            Share::sendmail('mk0630733719@gmail.com', "Prommu.com. Заказ Услуги Аутсорсинга!", $message);
            Share::sendmail('Job@mandarin-agency.ru', "Prommu.com. Заказ Услуги Аутсорсинга!", $message);
            Share::sendmail('e.market.easss@gmail.com', "Prommu.com. Заказ Услуги Аутсорсинга!", $message);
            Share::sendmail('client@btl-me.ru', "Prommu.com. Заказ Услуги Аутсорсинга!", $message);
            Share::sendmail('prommucom@gmail.com', "Prommu.com. Заказ Услуги Аутстаффинга!", $message);
        }
        elseif($type == "outsourcing") {
                $res = Yii::app()->db->createCommand()
                        ->insert('outstaffing', array('id' => $id,
                                'consult' => $consult,
                                'advertising' => $advertising, 
                                'control' => $control,
                                'vacancy' => $vacancy,
                                'text' => $other,
                                'email' => $email,
                                'phone' => $phone,
                                'type' => $type,
                            ));
            $message = '<p style="font-size:16px;">На сайте prommu.com был оставлен запрос от клиента по Услуге Аутсорсинг</p>
                    <br/>

                <p style=" font-size:16px;">
               Пользователь: '.$id.' '.$lastname.' '.$firstname.'<br/>
                <br/>Компания: '.$name.'<br/>
               Услуги: '.$control.', '.$advertising.', '.$consult.'<br/> Вакансия: '.$vacancy.' '.$title.'
                    <br/>
                Контактные данные: '.$phone.' '.$email.'<br/>
                Сообщение:'.$other.'</p>';
            Share::sendmail('denisgresk@gmail.com', "Prommu.com. Заказ Услуги Аутсорсинга!", $message);
            Share::sendmail('dsale_1@plan-o-gram.ru', "Prommu.com. Заказ Услуги Аутсорсинга!", $message);
            Share::sendmail('mk0630733719@gmail.com', "Prommu.com. Заказ Услуги Аутсорсинга!", $message);
            Share::sendmail('Job@mandarin-agency.ru', "Prommu.com. Заказ Услуги Аутсорсинга!", $message);
            Share::sendmail('e.market.easss@gmail.com', "Prommu.com. Заказ Услуги Аутсорсинга!", $message);
            Share::sendmail('client@btl-me.ru', "Prommu.com. Заказ Услуги Аутсорсинга!", $message);
            Share::sendmail('prommucom@gmail.com', "Prommu.com. Заказ Услуги Аутстаффинга!", $message);
        }

    }

	


	}



?>