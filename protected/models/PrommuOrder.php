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
     public function servicePrice($arId, $service){
        if(!sizeof($arId) || empty($service))
            return -1;

        $arBD = Yii::app()->db->createCommand()
            ->select("c.region")
            ->from('empl_city ec')
            ->leftjoin('city c', 'c.id_city=ec.id_city')
            ->where(array('in','id_vac',$arId))
            ->queryAll();

        if(!sizeof($arBD))
            return -1;

        $price = 0;
        $bin = $this->convertedRegion($arBD);
        
        if($service=='premium-vacancy' || $service=='email-invitation') {
            $arReg = $this->getRegionalPrice($service,$bin);
            $arPrices = Yii::app()->db->createCommand()
                ->select("price")
                ->from('service_prices')
                ->where(
                        'service=:service AND region IN('.join(',',$arReg).')',
                        array(':service' => $service)
                    )
                ->queryAll();

            foreach ($arPrices as $v)
                $price += $v['price'];
        }
        else {
            $arPrices = Yii::app()->db->createCommand()
                ->select("price")
                ->from('service_prices')
                ->where(
                        'service=:service',
                        array(':service' => $service)
                    )
                ->queryRow();  
            $price = $arPrices['price'];
        }

        return $price;
    }


    public function getPricesData(){
        $sql = "SELECT id, price, comment, service, region
            FROM service_prices";
        $results = Yii::app()->db->createCommand($sql)->queryAll();
        
        for($i = 0; $i < sizeof($results); $i ++)
            $data['prices'][$results[$i]['service']][] = $results[$i];
    
        return $data; 
    }

    function getFormSignature($account, $desc, $sum, $secretKey) {
        $hashStr = $account.'{up}'.$desc.'{up}'.$sum.'{up}'.$secretKey;
        return hash('sha256', $hashStr);
    }

    public function createPayLink($account, $desc, $sum){
         $publi = "84661-fc398";
         $secretKey = '56B61C8ED08-535F660B689-40C558A1CE';
         $hash = $this->getFormSignature($account, $desc, $sum, $secretKey);
         $link = "https://unitpay.ru/pay/$publi?sum=$sum&account=$account&desc=$sum&signature=$hash";

         return $link;

    }
    
    public function autoOrder($type, $stack, $account, $vacancy){
        if($type == 'email'){
            $res = Yii::app()->db->createCommand()
                ->update('service_cloud', array(
                    'status'=> 1,
                ), 'id_user=:id_user AND name=:name AND stack=:stack', array(':id_user' => "$account", ':name' => "$vacancy", ':stack'=> $stack));
                
                $sql = "SELECT  e.user
                FROM service_cloud e
                WHERE e.stack = {$stack}";
                $user = Yii::app()->db->createCommand($sql)->queryAll();
                
                $user = exolode(',', $user);
                 $sql = "SELECT  e.title
                FROM empl_vacations e
                WHERE e.id = {$name}";
                $vacancy = Yii::app()->db->createCommand($sql)->queryAll();

                 $sql = "SELECT  u.email, e.name, e.firstname, e.lastname
                    FROM employer e
                    LEFT JOIN user u ON u.id_user = e.id_user
                    WHERE e.id_user = {$account}";
                $empl = Yii::app()->db->createCommand($sql)->queryAll();
                
                for($i = 0; $i < count($user); $i ++){
                    $sql = "SELECT  e.id, u.email, e.firstname, e.lastname
                    FROM resume e
                    LEFT JOIN user u ON u.id_user = e.id_user
                    WHERE e.id_user = {$user['user'][$i]}";
                    $resume = Yii::app()->db->createCommand($sql)->queryAll();
                    
                    $message = '<p style="font-size:16px;"Работодатель'.$account.' '.$empl[0]['lastname'].' '.$empl[0]['firstname'].'<br/> </p>
                    <br/>

                    <p style=" font-size:16px;">
                     <br/>Компания: '.$empl[0]['name'].'<br/>
                  Приглашает на вакансию:  '.$name.' '.$vacancy[0]['title'].'<br/>
                  Ссылка на вакансию:  <a href="https://prommu.com/vacancy/'.$name.'">'.$vacancy[0]['title'].'</a><br/>

                    <br/>';
                    if(strpos($resume[0]['email'], "@") !== false){
                        Share::sendmail($empl[0]['email'], "Prommu.com. Приглашение На Вакансию", $message);
                        Share::sendmail('denisgresk@gmail.com', "Prommu.com. Приглашение На Вакансию", $message);
                    }
                    
                }
                

            
        } elseif($type == 'sms'){
            $res = Yii::app()->db->createCommand()
                ->update('service_cloud', array(
                    'status'=> 1,
                ), 'id_user=:id_user AND name=:name AND stack=:stack', array(':id_user' => "$account", ':name' => "$vacancy", ':stack'=> $stack));
                
                 $sql = "SELECT  e.user,e.key
                FROM service_cloud e
                WHERE e.stack = {$stack}";
                $user = Yii::app()->db->createCommand($sql)->queryAll();
                $users = exolode(',', $user['user']);
                for($i = 0; $i < count($users); $i ++){
                    $api = new Api();
                    $api->teleProm($users[$i], $user['key']);
                }
                
        } elseif($type == 'push'){
           $res = Yii::app()->db->createCommand()
                ->update('service_cloud', array(
                    'status'=> 1,
                ), 'id_user=:id_user AND name=:name AND stack=:stack', array(':id_user' => "$account", ':name' => "$vacancy", ':stack'=> $stack));

                $link = "https://prommu.com/vacancy/$user";
                $text = "Работодатель приглашает на вакансию";

                $sql = "SELECT r.push
                FROM user_push r
                WHERE r.id = {$user}";
                $res = Yii::app()->db->createCommand($sql)->queryRow();
                
                if($res) {
                $type = "vacancy";
                $api = new Api();
                $api->getPushApi($res['push'], $type, $text, $link);

                
                }
        }

    }
    
    public function serviceOrderSms($id_user,$sum, $status, $postback, $from, $to, $name,$type, $text, $id, $stack){

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
                                'user' => $id,
                                'stack' => $stack
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
            Share::sendmail('prommu.servis@gmail.com', "Prommu.com. Заказ Услуги Смс Информирование!", $message);
            Share::sendmail('Job@mandarin-agency.ru', "Prommu.com. Заказ Услуги Смс Информирование!", $message);
            Share::sendmail('e.market.easss@gmail.com', "Prommu.com. Заказ Услуги Смс Информирование!", $message);
            Share::sendmail('client@btl-me.ru', "Prommu.com. Заказ Услуги Смс Информирование!", $message);
            Share::sendmail('prommucom@gmail.com', "Prommu.com. Заказ Услуги Смс Информирование!", $message);

        }


    }

    public function serviceOrderEmail($id_user,$sum, $status, $postback, $from, $to, $name,$type, $text, $id,$stack){

        if($postback == 0) {
           
            $res = Yii::app()->db->createCommand()
                        ->insert('service_cloud', array('id_user' => $id_user,
                                'name' => $name,
                                'type' => $type, 
                                'bdate' => $from,
                                'edate' => $to,
                                'status' => $status,
                                'sum' => $sum,
                                'text' => $text,
                                'user' => $id,
                                'stack' => $stack
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
            Share::sendmail('prommu.servis@gmail.com', "Prommu.com. Заказ Услуги Смс Информирование!", $message);
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
            Share::sendmail('prommu.servis@gmail.com', "Prommu.com. Заказ Услуги Премиум Вакансия!", $message);
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
                                'date' => date('Y-m-d H:i:s')
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
            Share::sendmail('prommu.servis@gmail.com', "Prommu.com. Заказ Услуги Аутсорсинга!", $message);
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
                                'date' => date('Y-m-d H:i:s')
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
            Share::sendmail('prommu.servis@gmail.com', "Prommu.com. Заказ Услуги Аутсорсинга!", $message);
            Share::sendmail('Job@mandarin-agency.ru', "Prommu.com. Заказ Услуги Аутсорсинга!", $message);
            Share::sendmail('e.market.easss@gmail.com', "Prommu.com. Заказ Услуги Аутсорсинга!", $message);
            Share::sendmail('client@btl-me.ru', "Prommu.com. Заказ Услуги Аутсорсинга!", $message);
            Share::sendmail('prommucom@gmail.com', "Prommu.com. Заказ Услуги Аутстаффинга!", $message);
        }

    }
    /*
    *       Заказ услуги Премиум
    */
    public function orderPremium($arVacs, $vacPrice, $employer) {
        if(!isset($employer))
            return false;

        $arBDate = Yii::app()->getRequest()->getParam('from');
        $arEDate = Yii::app()->getRequest()->getParam('to');
        $strVacs = implode('.', $arVacs);
        $account = $employer . '.' . $strVacs;
        $day = 60 * 60 * 24;
        $mainPrice = 0;

        for($i=0, $n=sizeof($arVacs); $i<$n; $i++) {
            $from = strtotime($arBDate[$i]);
            $to = strtotime($arEDate[$i]);
            $days = ($to - $from) / $day;
            $price = $vacPrice * $days;
            $this->serviceOrder(
                    $employer,
                    $price,
                    0, 
                    0, 
                    $arBDate[$i], 
                    $arEDate[$i], 
                    $arVacs[$i], 
                    'vacancy'
                );
            $mainPrice += $price;
        }
        return $this->createPayLink($account, $strVacs, $mainPrice);
    }
    /*
    *       Заказ услуги Email рассылка
    */
    public function orderEmail($vacancy, $vacPrice, $employer) {
        $arApps = Yii::app()->getRequest()->getParam('users');
        $date = date("Y-m-d h-i-s");
        $stack = time();
           $this->serviceOrderEmail(
                    $employer,
                    $vacPrice,
                    0, 
                    0, 
                    $date,
                    $date, 
                    $vacancy, 
                    'email', 
                    $vacancy, 
                    $arApps,
                    $stack
                );
        $account = $employer . '.' . $vacancy . '.email.' .$stack;

        return $this->createPayLink($account, $vacancy,  $vacPrice);
    }
    /*
    *       Заказ услуги Push рассылка
    */
    public function orderPush($vacancy, $vacPrice, $employer) {
        $arApps = Yii::app()->getRequest()->getParam('users');
        $date = date("Y-m-d h-i-s");
        $stack = time();

        for($i=0, $n=count($arApps); $i<$n; $i++) {
            $this->serviceOrderEmail(
                    $employer,
                    $vacPrice, 
                    1, 
                    0, 
                    $date,
                    $date, 
                    $vacancy, 
                    'push',
                    $vacancy,
                    $arApps,
                    $stack
                );
        }
    }
    /*
    *       Заказ услуги Push рассылка
    */
    public function orderSms($vacancy, $vacPrice, $employer) {
        if(!isset($employer))
            return false;

        $arApps = Yii::app()->getRequest()->getParam('users');
        $sumArr = count(explode(',',$arApps));
        $stack = time();
        $text = Yii::app()->getRequest()->getParam('text');
        $date = date("Y-m-d h-i-s");
        $mainPrice = 0;

       
            $this->serviceOrderSms(
                    $employer,
                    $vacPrice, 
                    0, 
                    0, 
                    $date,
                    $date,
                    $vacancy, 
                    'sms', 
                    $text, 
                    $arApps,
                    $stack
                );
            $mainPrice= $vacPrice*$sumArr;
       
        $account = $employer . '.' . $vacancy . '.sms.' . $stack;

        return $this->createPayLink($account, $vacancy, $mainPrice);
    }
    /*
    *       Конвертация регионов таблиц city и service_prices
    */
    private function convertedRegion($arr) {
        $bin = 0;
        foreach ($arr as $c) {
            if($c['region']==1307) $bin|=1; // определяем МО
            elseif($c['region']==1838) $bin|=2; // определяем ЛО
            else $bin|=4;
        }
        return $bin;
    }
    /*
    *       Подбор подходящего региона
    */
    private function getRegionalPrice($service, $bin) {
        $arReg = [4];
        if($service=='premium-vacancy' || $service=='email-invitation') {
            switch ($bin) {
                case 1: $arReg = [1]; break;// МО
                case 2: $arReg = [2]; break;// ЛО
                case 4: $arReg = [3]; break;// др.рег.
            }
        }
        if($service=='premium-vacancy') {
            switch ($bin) {
                case 3: // МО + ЛО
                case 7: $arReg = [1,3]; break;// МО + ЛО + др.рег.
                case 5: $arReg = [1]; break;// МО + др.рег.
                case 6: $arReg = [2]; break;// ЛО + др.рег.
            }
        }
        if($service=='email-invitation') {
            switch ($bin) {
                case 3: // МО + ЛО
                case 7: $arReg = [1,2]; break;// МО + ЛО + др.рег.
                case 5: $arReg = [1,3]; break;// МО + др.рег.
                case 6: $arReg = [2,3]; break;// ЛО + др.рег.
            }
        }
        return $arReg;
    }
    /*
    *       ПОлучение данных о вакансиях работодателя
    */
    public function getVacRegions($arPrice) {
        if(Share::$UserProfile->type!=3)
            return $arPrice;

        $arRes = array();
        $id = Share::$UserProfile->id;
        $arBD = Yii::app()->db->createCommand()
            ->select("c.region")
            ->from('empl_city ec')
            ->leftjoin('empl_vacations ev', 'ev.id=ec.id_vac AND ev.status=1 AND ev.in_archive=0')
            ->leftjoin('city c', 'c.id_city=ec.id_city')
            ->where('ev.id_user=:id',array(':id'=>$id))
            ->queryAll();


        $bin = $this->convertedRegion($arBD);
        $arNewPrices = array();
        foreach ($arPrice as $s => $arP) {
            $arReg = $this->getRegionalPrice($s, $bin);
            foreach ($arP as $v)
                if(in_array($v['region'], $arReg))
                    $arNewPrices[$s][] = $v;
        }
        if(!array_key_exists('premium-vacancy', $arNewPrices))
            $arNewPrices['premium-vacancy'] = $arPrice['premium-vacancy'];
        if(!array_key_exists('email-invitation', $arNewPrices))
            $arNewPrices['email-invitation'] = $arPrice['email-invitation'];

        return $arNewPrices;
    }
}
?>