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
            $pid = Yii::app()->db->createCommand('SELECT LAST_INSERT_ID()')->queryScalar();
            
            return $pid;
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
        $account = $employer . '.' . $vacancy . '.email.' . implode('.', $stack);

        return $this->createPayLink($account, $vacancy,  $vacPrice);
    }
    /*
    *       Заказ услуги Push рассылка
    */
    public function orderPush($vacancy, $vacPrice, $employer) {
        $arApps = explode(",", Yii::app()->getRequest()->getParam('users'));
        $date = date("Y-m-d h-i-s");
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
                    $arApps[$i]
                );
        }
    }
    /*
    *       Заказ услуги Push рассылка
    */
    public function orderSms($vacancy, $vacPrice, $employer) {
        if(!isset($employer))
            return false;

        $arApps = explode(",", Yii::app()->getRequest()->getParam('users'));
        $text = Yii::app()->getRequest()->getParam('text');
        $date = date("Y-m-d h-i-s");
        $mainPrice = 0;

        for($i=0, $n=count($arApps); $i<$n; $i++) {
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
                    $arApps[$i]
                );
            $mainPrice += $vacPrice;
        }
        $account = $employer . '.' . $vacancy . '.sms.' . implode('.', $arApps);

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
            ->leftjoin('empl_vacations ev', 'ev.id=ec.id_vac AND ev.status=1')
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