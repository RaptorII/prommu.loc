<?php

class PrommuOrder {
    //Определение цены использования услуги
     public function servicePrice($arId, $service) {
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
        
        if(
            $service=='premium-vacancy' ||
            $service=='email-invitation'||
            $service=='podnyatie-vacansyi-vverh'
        ) {
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
  /**
   * @param $serviceType - тип услуги
   * @param $transaction - номер транзакции
   * @param $id_user - id_user заказчика вакансии
   * @param $id_vacancy - ID вакансии
   */
  public function autoOrder($serviceType, $transaction, $id_user, $id_vacancy)
  {
    // устанавливаем статус Оплачено
    Yii::app()->db->createCommand()
      ->update(
        'service_cloud',
        ['status'=>1],
        'stack=:stack',
        [':stack'=>$transaction]
      );
    // все данные о заказе
    $arService = Yii::app()->db->createCommand()
      ->select('*')
      ->from('service_cloud')
      ->where('stack=:stack',[':stack'=>$transaction])
      ->queryRow();
    $arIdUsers = explode(',', $arService['user']);

    if($arService['status']==0)
      return;

    if ($serviceType == 'email')
    {
      // собираем емейлы С
      $arEmails = Yii::app()->db->createCommand()
        ->select('email')
        ->from('user')
        ->where(['in','id_user',$arIdUsers])
        ->queryColumn();

      // для письма нужен заголовок вакансии
      $vacancyTitle = Yii::app()->db->createCommand()
        ->select('title')
        ->from('empl_vacations')
        ->where('id=:id',[':id'=>$id_vacancy])
        ->queryScalar();
      // для письма инфа о Р
      $arEmployer = Yii::app()->db->createCommand()
        ->select('e.name, e.firstname, e.lastname, u.email')
        ->from('employer e')
        ->leftJoin('user u','u.id_user=e.id_user')
        ->where('e.id_user=:id',[':id'=>$id_user])
        ->queryRow();
      // Письмо для С о том, что его приглашают на работу
      $arEmails[] = 'denisgresk@gmail.com'; // Добавляем в рассылку Денчика
      Mailing::set(21,
        [
          'id_user' => $id_user,
          'name_user' => $arEmployer['lastname'] . ' ' . $arEmployer['firstname'],
          'company_user' => $arEmployer['name'],
          'id_vacancy' => $id_vacancy,
          'title_vacancy' => $vacancyTitle
        ],
        UserProfile::$APPLICANT,
        $arEmails
      );
    }
    elseif ($serviceType == 'sms')
    {
      for ($i=0, $n=count($arIdUsers); $i<$n; $i++)
      {
        $api = new Api();
        $api->teleProm($arIdUsers[$i], $arService['key']);
      }
    }
    elseif ($serviceType == 'push')
    {
      $query = Yii::app()->db->createCommand()
        ->select('push')
        ->from('user_push')
        ->where(['in','id',$arIdUsers])
        ->queryColumn();

      for($i=0, $n=count($query); $i<$n; $i++)
      {
        $api = new Api();
        $api->getPushApi(
          $query[$i],
          'vacancy',
          'Работодатель приглашает на вакансию',
          Subdomain::site() . MainConfig::$PAGE_VACANCY . DS . $id_vacancy
        );
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
            $result = Yii::app()->db->createCommand()
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
            // записываем инфу, если юр. лицо
            $result && $result=Yii::app()->db->getLastInsertID();
        } else {

          $result = Yii::app()->db->createCommand()
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

      return $result;
    }

    public function serviceOrderEmail($id_user,$sum, $status, $postback, $from, $to, $name,$type, $text, $id,$stack){

        if($postback == 0) {

          $result = Yii::app()->db->createCommand()
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
          // записываем инфу, если юр. лицо
          $result && $result=Yii::app()->db->getLastInsertID();
        } else {
          $result = Yii::app()->db->createCommand()
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

      return $result;
    }

	public function serviceOrder($id_user,$sum, $status, $postback, $from, $to, $name,$type)
  {
    if($postback == 0)
    {
      $to = date( 'Y-m-d', strtotime($to));
      $from = date( 'Y-m-d H:i', strtotime($from));

      $result = Yii::app()->db->createCommand()
              ->insert(
                'service_cloud',
                [
                  'id_user' => $id_user,
                  'name' => $name,
                  'type' => $type,
                  'bdate' => $from,
                  'edate' => $to,
                  'status' => $status,
                  'sum' => $sum,
                  'date' => date("Y-m-d H:i:s")
                ]
              );
      // записываем инфу, если юр. лицо
      $result && $result=Yii::app()->db->getLastInsertID();
    }
    else
    {
      $result = Yii::app()->db->createCommand()
            ->update(
              'service_cloud',
              ['status'=>$status],
              'id_user=:id_user AND sum=:sum',
              [':id_user'=>$id_user, ':sum'=>$sum]
            );

      $title = Yii::app()->db->createCommand()
        ->select('title')
        ->from('empl_vacations')
        ->where('id=:id',[':id'=>$name])
        ->queryScalar();

      $arUser = Share::getUsers([$id_user])[0];

      $message = '<p style="font-size:16px;">На сайте prommu.com была оплачена услуга Премиум Вакансия</p>
              <br/><p style=" font-size:16px;">
         Пользователь: ' . $id_user . ' <br/>
          <br/>Компания: ' . $arUser['name'] . '<br/>
         Вакансия: ' . $name . ' ' . $title . '<br/>';

      Share::sendmail('denisgresk@gmail.com', "Prommu.com. Заказ Услуги Премиум Вакансия!", $message);
      Share::sendmail('dsale_1@plan-o-gram.ru', "Prommu.com. Заказ Услуги Премиум Вакансия!", $message);
      Share::sendmail('prommu.servis@gmail.com', "Prommu.com. Заказ Услуги Премиум Вакансия!", $message);
      Share::sendmail('Job@mandarin-agency.ru', "Prommu.com. Заказ Услуги Премиум Вакансия!", $message);
      Share::sendmail('e.market.easss@gmail.com', "Prommu.com. Заказ Услуги Премиум Вакансия!", $message);
      Share::sendmail('client@btl-me.ru', "Prommu.com. Заказ Услуги Премиум Вакансия!", $message);
      Share::sendmail('prommucom@gmail.com', "Prommu.com. Заказ Услуги Премиум Вакансия!", $message);
    }

    return $result;
	}



    public function outstaffing($data)
    {
      $model = new Vacancy();
      $arVacs = $model->getVacanciesById($data['vacancy']);

      $arInsert = [
        'id' => $data['account'],
        'consult' => $data['consultation'],
        'vacancy' => implode(',',$data['vacancy']),
        'text' => $data['other-contact'],
        'email' => ($data['email'] ? $data['email'] : ''),
        'phone' => ($data['phone'] ? $data['phone'] : ''),
        'type' => $data['service'],
        'date' => date('Y-m-d H:i:s')
      ];
      $arServices = $arContacts = [];
      !empty($data['email']) && $arContacts[] = $data['email'];
      !empty($data['phone']) && $arContacts[] = $data['phone'];

      if ($data['service'] == "outstaffing")
      {
        $arInsert['rezident'] = $data['registration-rf'];
        $arInsert['nrezident'] = $data['registration-mr'];
        !empty($data['registration-mr']) && $arServices[] = $data['registration-mr'];
        !empty($data['registration-rf']) && $arServices[] = $data['registration-rf'];
        !empty($data['consultation']) && $arServices[] = $data['consultation'];
      }
      elseif ($data['service'] == "outsourcing")
      {
        $arInsert['advertising'] = $data['advertising'];
        $arInsert['control'] = $data['control'];
        !empty($data['control']) && $arServices[] = $data['control'];
        !empty($data['advertising']) && $arServices[] = $data['advertising'];
        !empty($data['consultation']) && $arServices[] = $data['consultation'];
      }
      $strVacs = '';
      foreach ($arVacs as $v)
      {
        $strVacs .= '<a href="' . Mailing::mainParams()[5]['name'] . DS . $v['id']
          . '" style="color:#abb837">' . $v['title'] . ' (' . $v['id'] . ')</a>,<br>';
      }

      $name = trim(Share::$UserProfile->exInfo->efio);
      empty($name) && $name = 'Пользователь';
      Mailing::set(26,
        [
          'service_name' => 'Аутсорсинг',
          'id_user' => $data['account'],
          'name_user' => $name,
          'company_user' => Share::$UserProfile->exInfo->name,
          'subservices' => implode(',<br>', $arServices),
          'vacancies' => $strVacs,
          'contacts_user' => implode(',<br>', $arContacts),
          'message_user' => $data['other-contact'],
        ],
        UserProfile::$EMPLOYER
      );

      return Yii::app()->db->createCommand()->insert('outstaffing', $arInsert);
    }
    /*
    *       Заказ услуги Премиум
    */
    public function orderPremium($arVacs, $vacPrice, $employer)
    {
      if(!isset($employer))
        return false;

      $arRes = [];
      $arRes['strVacancies'] = implode('.', $arVacs);
      $arRes['account'] = $employer . '.' . $arRes['strVacancies'];

      $arRes['cost'] = 0;
      $arRes['id'] = [];
      $arBDate = Yii::app()->getRequest()->getParam('from');
      $arEDate = Yii::app()->getRequest()->getParam('to');
      $day = 60 * 60 * 24;

      for($i=0, $n=sizeof($arVacs); $i<$n; $i++)
      {
        $from = strtotime($arBDate[$i]);
        $to = strtotime($arEDate[$i]);
        $days = ($to - $from) / $day;
        $price = intval($vacPrice * $days);
        $arRes['id'][] = $this->serviceOrder(
          $employer,
          $price,
          0,
          0,
          $arBDate[$i],
          $arEDate[$i],
          $arVacs[$i],
          'vacancy'
        );
        $arRes['cost'] += $price;
      }

      return $arRes;
    }

    /**
     * @param $arVacs
     * @param $vacPrice
     * @param $employer
     * @return array|bool
     */
    public function orderUpVacancy($arVacs, $vacPrice, $employer)
    {

        display($employer);
        display($vacPrice);
        display($arVacs);
//        display($_POST);

        //die('orderUpVacancy 1');

        //$employer = Share::$UserProfile->id;

        if(!isset($employer))
            return false;

        $arRes = [];
        $arRes['strVacancies'] = implode('.', $arVacs);
        $arRes['account'] = $employer . '.' . $arRes['strVacancies'];

        $arRes['cost'] = 0;
        $arRes['id'] = [];
        $arBDate = 'start data format';
        $arEDate = 'end data format';
        $day = 60 * 60 * 24;

        $arRes['cost'] = $vacPrice * count($arVacs);

        die('orderUpVacancy 2');

        return $arRes;
    }
    /**
     * @param $arServices
     * @return code
     */
    public function setLegalEntityReceipt($arServices)
    {
      $rq = Yii::app()->getRequest();
      if($rq->getParam('personal')!=='legal')
        return;

      $index = filter_var($rq->getParam('index'), FILTER_SANITIZE_NUMBER_INT);
      $city = filter_var($rq->getParam('city'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
      $detail = filter_var($rq->getParam('detail'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
      $index = "$index, г.$city, ул.$detail";
      $code = time();

      Yii::app()->db->createCommand()
        ->update('service_cloud', ['legal'=>$code], ['in','id',$arServices]);

      $result = Yii::app()->db->createCommand()
        ->insert(
          'payment_legal',
          [
            'code' => $code,
            'id_user' => Share::$UserProfile->exInfo->id,
            'company' => filter_var($rq->getParam('name'), FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            'inn' => filter_var($rq->getParam('inn'), FILTER_SANITIZE_NUMBER_INT),
            'kpp' => filter_var($rq->getParam('kpp'), FILTER_SANITIZE_NUMBER_INT),
            'email' => filter_var($rq->getParam('email'), FILTER_SANITIZE_EMAIL),
            'index' => $index,
            'with_nds' => $rq->getParam('with_nds')==1
          ]
        );

      $result && $result=$code;

      Yii::app()->user->setFlash(
        'prommu_flash',
        "Ожидаем от Вас оплаты по <a href='" . MainConfig::$PAGE_LEGAL_ENTITY_RECEIPT . $result . "' target='_blank'>счету</a>. "
        . "Оплата зачисляется автоматически. В назначинии обязательно указывайте Ваш ID и номер счета."
      );

      return $result;
    }
    /**
     * @param $code
     * @return array|bool
     */
    public function getLegalEntityReceipt($code)
    {
      $NDS = 18; // НДС
      $arRes = Yii::app()->db->createCommand()
        ->select('*')
        ->from('payment_legal')
        ->where('code=:code',[':code'=>$code])
        ->queryRow();

      if(!is_array($arRes))
        return false;

      $query = Yii::app()->db->createCommand()
        ->select('*')
        ->from('service_cloud')
        ->where(['in','legal',$code])
        ->queryAll();

      $arRes['cost'] = 0;
      $arRes['services'] = [];
      foreach ($query as $v)
      {
        $arRes['cost'] += $v['sum'];
        $v['title'] = Services::getServiceName($v['type']);
        $v['cost'] = $v['sum'] . ',00';
        $arRes['services'][] = $v;
      }

      $arRes['cost'] = round($arRes['cost']);
      if($arRes['with_nds'])
      {
        $arRes['nds'] = ($arRes['cost'] / 100) * $NDS;
        $arRes['nds'] = $arRes['nds'] . ',00';
        $arRes['total_cost'] = round($arRes['cost'] + $arRes['nds']);
        $arRes['total_cost_str'] = number2string($arRes['total_cost']) . ' 00 копеек';
        $arRes['total_cost'] = $arRes['total_cost'] . ',00';
      }
      else
      {
        $arRes['total_cost_str'] = number2string($arRes['cost']) . ' 00 копеек';
        $arRes['total_cost'] = $arRes['cost'] . ',00';
      }

      $arRes['cost'] = $arRes['cost'] . ',00';

      $arMonths = array(
        1=>'января',2=>'февраля',3=>'марта',
        4=>'апреля',5=>'мая',6=>'июня',
        7=>'июля',8=>'августа',9=>'сентября',
        10=>'октября',11=>'ноября',12=>'декабря'
      );

      $arRes['date1'] = date('d',$arRes['code']) . ' '
        . $arMonths[date('n',$arRes['code'])] . ' '
        . date('Y',$arRes['code']) . ' г';
      $arRes['date2'] = date('d/m/y',$arRes['code']);
      $arRes['date3'] = date('d.m.Y',$arRes['code']);
      $arRes['last_date'] = date('d.m.Y',(5*86400 + $arRes['code']));

      return $arRes;
    }
    /*
    *       Заказ услуги Email рассылка
    */
    public function orderEmail($vacancy, $vacPrice, $employer)
    {
      $arRes = [];
      $arApps = Yii::app()->getRequest()->getParam('users');
      $date = date("Y-m-d h-i-s");
      $stack = time();
      $arRes['id'] = $this->serviceOrderEmail(
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
      $arRes['account'] = $employer . '.' . $vacancy . '.email.' . $stack;

      return $arRes;
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
    public function orderSms($vacancy, $price, $employer)
    {
      if(!isset($employer))
          return false;

      $arRes = [];
      $stack = time();
      $date = date("Y-m-d h-i-s");
      $arApps = Yii::app()->getRequest()->getParam('users');
      $text = Yii::app()->getRequest()->getParam('text');
      $arRes['cost'] = $price * count(Share::explode($arApps));

      $arRes['id'] = $this->serviceOrderSms(
          $employer,
          $arRes['cost'],
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

      $arRes['account'] = $employer . '.' . $vacancy . '.sms.' . $stack;

      return $arRes;
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
        if($service=='premium-vacancy'  ||
           $service=='email-invitation' ||
           $service=='podnyatie-vacansyi-vverh' ){
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
        if($service=='podnyatie-vacansyi-vverh') {
            switch ($bin) {
                case 3: // МО + ЛО
                case 7: $arReg = [1,3]; break;// МО + ЛО + др.рег.
                case 5: $arReg = [1]; break;// МО + др.рег.
                case 6: $arReg = [2]; break;// ЛО + др.рег.
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
        if(!array_key_exists('podnyatie-vacansyi-vverh', $arNewPrices))
            $arNewPrices['podnyatie-vacansyi-vverh'] = $arPrice['podnyatie-vacansyi-vverh'];

        return $arNewPrices;
    }
}
?>