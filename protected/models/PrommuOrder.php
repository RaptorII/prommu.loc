<?php

class PrommuOrder {
  //Определение цены использования услуги
  public function servicePrice($arId, $service, $isArray=false, $vacCity=false)
  {
     if(!sizeof($arId) || empty($service))
       return -1;

     $result = (!$isArray ? 0 : []);
     $db = Yii::app()->db;

     $query = $db->createCommand()
       ->select('ec.id_vac, c.region')
       ->from('empl_city ec')
       ->join('city c', 'c.id_city=ec.id_city')
       ->where(['in','id_vac',$arId])
       ->queryAll();

     if(!sizeof($query))
     {
       return -1;
     }

     $arTemp = $arRegions = [];
     foreach ($query as $v)
     {
       $arTemp[$v['id_vac']]['regions'][] = $v['region'];
       $arTemp[$v['id_vac']]['price'] = 0;
     }
     foreach ($arTemp as $id => $v)
     {
       $arTemp[$id]['bin'] = $this->convertedRegion($v['regions']);
     }

     if(in_array(
       $service,
       ['premium-vacancy','podnyatie-vacansyi-vverh','email-invitation'])
     ) // цену формируем лишь для платных услуг
     {
       foreach ($arTemp as $id => $v)
       {
         $arRegions = $this->getRegionalPrice($service,$v['bin'],false);

         $price = $db->createCommand() // запрос в цикле чтоб не сумировались одинаковые регионы
           ->select('SUM(price)')
           ->from('service_prices')
           ->where([
             'and',
             "service='$service'",
             ['in','region',$arRegions]
           ])
           ->queryScalar();

         $isArray
         ? $result[$id]=$price
         : $result+=$price;
       }

       if (is_array($vacCity))
       {
            $arRes = [];
            for ($i=0; $i<count($arId); $i++)
            {
                //find array for regional prises
                $region[] = $vacCity[$i];
                $arRegPrise = $this->getRegionalPrice($service, $this->convertedRegion($region), false);

                $price = $db->createCommand()
                    ->select('price')
                        ->from('service_prices')
                        ->where([
                            'and',
                            "service='$service'",
                            ['in','region',$arRegPrise]
                        ])
                    ->queryScalar();

                $arRes[$i]['id_vac'] = $arId[$i];
                $arRes[$i]['region'] = $vacCity[$i];
                $arRes[$i]['price']  = $price;

                $region = [];
            }

           $result = $arRes;
       }

     }
     else
     {
       $result = $db->createCommand()
        ->select("price")
        ->from('service_prices')
        ->where('service=:service', [':service'=>$service])
        ->queryScalar();
     }

     return $result;
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
        $logo = Yii::app()->db->createCommand()
            ->select('photo')
            ->from('user_photos')
            ->where('id_user=:id',[':id'=>$id_user])
            ->queryColumn();
        $bigSrc = Share::getPhoto($id_user, 3, $logo, 'big');
        $imgCmpLogo = $_SERVER['SERVER_NAME'].$bigSrc;

      // собираем емейлы С
      $arEmails = Yii::app()->db->createCommand()
        ->select('email')
        ->from('user')
        ->where(['in','id_user',$arIdUsers])
        ->queryColumn();

      // для письма нужен заголовок вакансии и оплата
      $vacancyTitle = Yii::app()->db->createCommand()
        ->select('title, shour, sweek, smonth, svisit')
        ->from('empl_vacations')
        ->where('id=:id',[':id'=>$id_vacancy])
        ->queryScalar();

      if( $vacancyTitle['shour'] > 0 )
          $payForVacancy = $vacancyTitle['shour'] . ' руб/час';
      if( $vacancyTitle['sweek'] > 0 )
          $payForVacancy = $vacancyTitle['sweek'] . ' руб/неделю';
      if( $vacancyTitle['smonth'] > 0 )
          $payForVacancy = $vacancyTitle['smonth']. ' руб/месяц';
      if( $vacancyTitle['svisit'] > 0 )
          $payForVacancy = $vacancyTitle['svisit']. ' руб/посещение';

      //для письма от Р название должности
      $sql = "SELECT
            id_attr, d1.name
        FROM
            empl_attribs
        INNER JOIN
            user_attr_dict d
        ON
            d.id = 110
        INNER JOIN
            user_attr_dict d1
        ON
            empl_attribs.id_attr = d1.id AND d1.id_par = d.id
        WHERE
            id_vac =".$id_vacancy;
      $positionVacancy = Yii::app()->db->createCommand($sql)->execute();


      // для письма инфа о Р
      $arEmployer = Yii::app()->db->createCommand()
        ->select('e.name, e.firstname, e.lastname, u.email')
        ->from('employer e')
        ->leftJoin('user u','u.id_user=e.id_user')
        ->where('e.id_user=:id',[':id'=>$id_user])
        ->queryRow();
      // Письмо для С о том, что его приглашают на работу
      //   $arEmails[] = 'denisgresk@gmail.com'; // Добавляем в рассылку Денчика
      $arEmails[] = 'mikekarpenko@gmail.com'; // Добавляем в рассылку Мих
      Mailing::set(34,
        [
          'id_user'          => $id_user,
          'name_user'        => $arEmployer['lastname'] . ' ' . $arEmployer['firstname'],
          'id_vacancy'       => $id_vacancy,
          'company_user'     => $arEmployer['name'],
          'img_company_logo' => $imgCmpLogo,         //add
          'title_vacancy'    => $vacancyTitle,
          'position_vacancy' => $positionVacancy,    //add
          'pay_for_vacancy'  => $payForVacancy,      //add
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

	public function serviceOrder($id_user,$sum, $city, $status, $postback, $from, $to, $name,$type)
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
                  'city' => $city,
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
  public function orderPremium($arVacs, $employer, $vacPrc, $vacCity)
  {
    if(!isset($employer))
      return false;

    $arRes = [];
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
        $price = intval($vacPrc[$i] * $days);
        $arRes['id'][] = $this->serviceOrder(
            $employer,
            $price,
            $vacCity[$i],
            0,
            0,
            $arBDate[$i],
            $arEDate[$i],
            $arVacs[$i],
            'vacancy'
        );
        $arRes['cost'] += $price;
    }

    $arRes['account'] = $employer . '.' . implode('.', $arRes['id']) . '.vacancy.' . time();
    return $arRes;
  }
  /**
   * Service Upvacancy
   * @param $arVacs
   * @param $vacPrice
   * @param $employer
   * @return array|bool
   */
  public function orderUpVacancy($arVacs, $employer, $vacPrc=false, $vacCity=false)
  {
    if(!isset($employer))
      return false;

    $arRes = [];
    $arRes['id'] = [];
    $arBDate = date("Y.m.d");
    $arEDate = date('Y.m.d', strtotime("+30 days"));
    $arRes['cost'] = 0;

    for($i=0, $n=sizeof($arVacs); $i<$n; $i++)
    {
      $arRes['id'][] = $this->serviceOrder(
        $employer,
        $vacPrc[$i],
        $vacCity[$i],
        0,
        0,
        $arBDate[$i],
        $arEDate[$i],
        $arVacs[$i],
        'upvacancy'
      );
      $arRes['cost'] += $vacPrc[$i];
    }
    $arRes['account'] = $employer . '.' . implode('.', $arRes['id']) . '.upvacancy.' . time();
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
    *   Конвертация регионов таблиц city и service_prices
    *   @return int (Возвращаеи число от 0 до 7и, в котором значение устанавливается и проверяется побитно)
    *  1)001 - Московская область(МСК)
    *  2)010 - Ленинградская область(СПБ)
    *  3)011 - Московская + Ленинградская область
    *  4)100 - прочие города
    *  5)101 - прочие города + Московская область
    *  6)110 - прочие города + Ленинградская область
    *  7)111 - прочие города + Московская область + Ленинградская область
    */
    private function convertedRegion($arr)
    {
        $bin = 0;
        foreach ($arr as $v)
        {
          if($v==1307) $bin|=1; // определяем МО
          elseif($v==1838) $bin|=2; // определяем ЛО
          else $bin|=4;
        }
        return $bin;
    }
    /**
     * @param $service - string
     * @param $bin - integer (0-7)
     * @return array (0=>Вся РФ, 1=>МСК, 2=>СПБ, 3=>регионы, 4=>Бесплатно, 5=>МСК+регионы, 6=>СПБ+регионы, 7=>МСК+СПБ)
     */
    private function getRegionalPrice($service, $bin, $list=true)
    {
      $arReg = [4]; // по умолчанию бесплатно
      if(in_array(
        $service,
        ['premium-vacancy','podnyatie-vacansyi-vverh','email-invitation'])
      )
      {
        switch ($bin)
        {
          case 0:
          case 1: $arReg = [1]; break;
          case 2: $arReg = [2]; break;
          case 3: $arReg = ($list ? [1,2,7] : [7]); break;
          case 4: $arReg = [3]; break;
          case 5: $arReg = ($list ? [1,3,5] : [5]); break;
          case 6: $arReg = ($list ? [2,3,6] : [6]); break;
          case 7: $arReg = ($list ? [0,1,2,3,4,5,6,7] : [0]); break;
        }
      }

      return $arReg;
    }
  /*
  *       ПОлучение данных о вакансиях работодателя
  */
  public function getVacRegions($arPrice)
  {
    if(!Share::isEmployer())
      return $arPrice;

    $query = Yii::app()->db->createCommand()
      ->select("c.region")
      ->from('empl_city ec')
      ->leftjoin(
        'empl_vacations ev',
        'ev.id=ec.id_vac AND ev.status=:status AND ev.in_archive=:archive AND ev.remdate>=CURDATE()',
        [
          ':status' => Vacancy::$STATUS_ACTIVE,
          ':archive' => Vacancy::$INARCHIVE_FALSE
        ]
      )
      ->leftjoin('city c', 'c.id_city=ec.id_city')
      ->where('ev.id_user=:id',[':id'=>Share::$UserProfile->id])
      ->queryColumn();

    $bin = $this->convertedRegion($query); // целое число с конкретным значением каждого бита
    $arRes = [];
    foreach ($arPrice as $serviceCode => $arP)
    {
      $arReg = $this->getRegionalPrice($serviceCode, $bin);
      foreach ($arP as $v)
      {
        in_array($v['region'], $arReg) && $arRes[$serviceCode][]=$v;
      }
    }
    if(!array_key_exists('premium-vacancy', $arRes))
      $arRes['premium-vacancy'] = $arPrice['premium-vacancy'];
    if(!array_key_exists('email-invitation', $arRes))
      $arRes['email-invitation'] = $arPrice['email-invitation'];
    if(!array_key_exists('podnyatie-vacansyi-vverh', $arRes))
      $arRes['podnyatie-vacansyi-vverh'] = $arPrice['podnyatie-vacansyi-vverh'];

    return $arRes;
  }
  /*
   *  определение цены для формы создания вакансии
   */
  public function getPriceByCity($arCity, $service)
  {
    $arRes = [];
    if(!count($arCity))
    {
      return $arRes;
    }

    $arTemp = [];
    foreach ($arCity as $v)
    {
      $arTemp[] = [
        'id_city' => $v['id_city'],
        'region' => $this->convertedRegion([$v['region']])
      ];
    }

    foreach ($arTemp as $v)
    {
      $arRegions = $this->getRegionalPrice($service, $v['region'], false);
      $query = Yii::app()->db->createCommand() // запрос в цикле чтоб не сумировались одинаковые регионы
                        ->select('SUM(price)')
                        ->from('service_prices')
                        ->where([
                          'and',
                          "service='$service'",
                          ['in','region',$arRegions]
                        ])
                        ->queryScalar();
      $arRes[] = ['id_city'=>$v['id_city'], 'price'=>$query];
    }
    return $arRes;
  }
  /**
   *  Формирование заказа для формы создания вакансии
   */
  public function orderPremiumInCreationVac($id_vacancy, $arCity, $arPrice, $period)
  {
    $arRes = [];
    $arRes['cost'] = 0;
    $arRes['id'] = [];
    $day = 60 * 60 * 24;
    $from = strtotime('today');;
    $to = $from + $period*$day;
    $days = ($to - $from) / $day;

    for($i=0, $n=sizeof($arCity); $i<$n; $i++)
    {
      $price = intval($arPrice[$i] * $days);
      $arRes['id'][] = $this->serviceOrder(
        Share::$UserProfile->id,
        $price,
        $arCity[$i],
        0,
        0,
        date('Y-m-d', $from),
        date('Y-m-d', $to),
        $id_vacancy,
        'vacancy'
      );
      $arRes['cost'] += $price;
    }

    $arRes['account'] = Share::$UserProfile->id . '.' . implode('.', $arRes['id']) . '.vacancy.' . time();
    return $arRes;
  }
}
?>