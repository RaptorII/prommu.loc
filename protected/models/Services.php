<?php
/**
 * Date: 29.03.2016
 *
 * Модель услуг и заказа
 */

class Services extends Model
{
    /**
     * получаем данные услуги
     */
    public function getServiceData($inLink, $inId = 0)
    {
        $lang = Yii::app()->session['lang'];

        if( $inLink ) $where = "p.link = '{$inLink}'";
        else $where = "p.id = '{$inId}'";

        $sql = "SELECT p.id, p.link, pc.name, 
                pc.html, pc.img, pc.imganons, 
                pc.meta_title, pc.meta_description
            FROM pages p
            INNER JOIN pages_content pc ON p.id = pc.page_id AND pc.lang = '{$lang}'
            WHERE {$where}";
        $data['service'] = Yii::app()->db->createCommand($sql)->queryRow();
        // получаем должности
        $sql = "SELECT m.id , m.`key` , m.name val FROM user_attr_dict m WHERE m.id_par = 110  ORDER BY val";
        $data['posts'] = Yii::app()->db->createCommand($sql)->queryAll();
        $data['file_path'] = UserCard::$FILE_PATH;
        $data['small_img_suffix'] = UserCard::$SMALL_IMG_SUFFIX;

        return $data;
    }
    /**
     * @return array|CDbDataReader
     */
    public function getDataAll()
    {
      return Yii::app()->db->createCommand()
        ->select(
          'p.id,
          p.link, 
          pc.name, 
          pc.anons, 
          pc.html, 
          pc.img, 
          pc.imganons')
        ->from('pages p')
        ->join(
          'pages_content pc',
          'p.id = pc.page_id AND pc.lang=:lang',
          [':lang'=>Yii::app()->session['lang']]
        )
        ->where('p.group_id=3')
        ->order('npp')
        ->queryAll();
    }
    /**
     * получаем все услуги
     */
    public function getServices()
    {
      $arRes = $arIdUsers = $arIdVac = [];

      $query = $this->getDataAll();

      // получаем меню услуг
      $menu = $this->getMenu();
      // объединение с меню
      foreach ($menu as $m)
      {
        $m['icon'] = str_replace('/services/', '', $m['link']);
        foreach ($query as $s)
        {
          $arRes['services'][$s['id']] = $s;
          if($m['icon']==$s['link'] || $m['icon']=='invitations')
          {
            $m['anons'] = $s['anons'];
            $arRes['menu'][$m['parent_id']][$m['id']] =  $m;
          }
        }
      }
      //
      if(!Share::isGuest())
      {
        $id_user = Share::$UserProfile->exInfo->id;
        $arRes['history'] = ['items'=>[], 'users'=>[], 'vacancies'=>[], 'cnt'=>0];

        $model = new ServiceCloud();
        $arRes['history']['items'] = $model->getDataByUser($id_user, true);

        $arRes['history']['items'] = array_merge(
          $arRes['history']['items'],
          (new MedRequest())->getMedBookByUser($id_user, true),
          (new CardRequest())->getCardByUser($id_user, true)
        );

        if(Share::isEmployer())
        {
          // собираем услуги с таболицы outstaffing
          $arRes['history']['items'] = array_merge(
            $arRes['history']['items'],
            $this->getOutstaffingByUser($id_user, true)
          );
          // собираем публикации вакансий
          $model = new Vacancy();
          $arVacs = $model->getVacanciesByUser($id_user);
          if(count($arVacs))
          {
            foreach ($arVacs as $v)
            {
              $arRes['history']['items'][] = [
                'id_user' => $id_user,
                'vacancy' => $v['id'],
                'type' => 'vacpub',
                'name' => 'Размещение вакансии',
                'date' => $v['crdate'],
                'title' => $v['title'],
                'status' => (($v['status']&&$v['ismoder'])?'Активна':($v['ismoder']?'Неактивна':'В процессе модерации'))
              ];

              $arRes['history']['vacancies'][$v['id']] = $v;
            }
          }
        }
        // сортируем по дате заказа
        usort(
          $arRes['history']['items'],
          function($a, $b){ return strtotime($b['date']) - strtotime($a['date']); }
          );
        $arRes['history']['cnt'] = count($arRes['history']['items']);
        //
        if($arRes['history']['cnt']>0)
        {
          foreach ($arRes['history']['items'] as &$v)
          {
            if(in_array($v['type'],['email','push','sms']) && !empty($v['data']['user'])) // service_cloud
            {
              $arT = explode(',',$v['data']['user']);
              foreach ($arT as $u)
              {
                if(intval($u))
                {
                  $arIdUsers[] = intval($u);
                  $v['users'][] = intval($u);
                }
              }
            }
            if(in_array($v['type'],['email','push','sms','repost','vacancy','outsourcing','outstaffing']))
            {
              !empty($v['vacancy']) && $arIdVac[]=$v['vacancy'];
            }

            if(!empty($v['data']['legal']))
            {
              $v['payment_legal'] = MainConfig::$PAGE_LEGAL_ENTITY_RECEIPT . $v['data']['legal'];
            }
          }
          //
          //$arRes['history']['users'] = Share::getUsers($arIdUsers);
        }
        unset($v);
      }

//      display( $arRes['menu'] );
//      die();

      return $arRes;
    }

    public function orderPrommu()
    {
      $rq = Yii::app()->getRequest();
      $data['post'] = filter_var($rq->getParam('post'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
      $data['fff'] = filter_var($rq->getParam('fff'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
      $data['iii'] = filter_var($rq->getParam('nnn'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
      $data['ooo'] = filter_var($rq->getParam('ooo'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
      $ser = explode("-",$rq->getParam('doc-ser'));
      $data['docser'] = $ser[0];
      $data['docnum'] = $ser[1];
      $data['docdate'] = $rq->getParam('birthday');
      $data['docorgname'] = filter_var($rq->getParam('doc-org'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
      $data['borndate'] =  $rq->getParam('birthday');
      $data['bornplace'] = filter_var($rq->getParam('bornplace'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
      $data['regaddr'] = filter_var($rq->getParam('regaddr'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
      $data['liveaddr'] = filter_var($rq->getParam('addr'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
      $data['tel'] = filter_var($rq->getParam('tel'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
      $phoneCode = filter_var($rq->getParam('__phone_prefix'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
      $data['tel'] = $phoneCode . $data['tel'];
      $data['docorgcode'] = filter_var($rq->getParam('docorgcode'), FILTER_SANITIZE_NUMBER_INT);
      $data['comment'] = filter_var($rq->getParam('comment'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
      $data['crdate'] = date("Y-m-d H:i:s");
      $arFiles = $rq->getParam('files');
      $data['files'] = '';

      if(count($arFiles))
      {
        $data['files'] = implode(',', $arFiles);
      }
      $data['id_user'] = (!Share::isGuest() ? Share::$UserProfile->exInfo->id : null);

      $result = Yii::app()->db->createCommand()->insert('card_request', $data);
      if($result)
      {
        Yii::app()->user->setFlash(
          'prommu_flash',
          'Ваша заявка успешно принята в обработку. Ожидайте, наши менеджеры свяжутся с вами'
        );
      }

      return ['error' => $result];
    }



    private function getMenu()
    {
        $lang = Share::getLangSelected();
        $menu = new Menu;
        $res = $menu->getTreeDB(0, $lang, 2, 0);

        return $res;
    }
    /*
    *   добываем анкеты для услуг
    */
    public function prepareFilterData()
    {
        $vacId = Yii::app()->getRequest()->getParam('vacancy');
        $model = new Vacancy();
        $arData = $model->getFilterForVacancy($vacId);
        $_GET = $arData['filter']; // это надо чтоб установились в фильтр параметры вакансии и чтоб правильно работала навигация
		return $this->getFilteredPromos($arData['filter']);
    }
    /*
    *
    */
    public function getFilteredPromos($filter=array()){
		$arRes = array();
        $model = new SearchPromo();
        $arProps = ['filter' => $filter];
        $arAllId = $model->searchPromosCount($arProps);
        $arRes['app_count'] = sizeof($arAllId);
        $arRes['pages'] = new CPagination($arRes['app_count']);
        $arRes['pages']->pageSize = 21;
        $arRes['pages']->applyLimit($model);
        $arRes['workers'] = $model->getPromos($arAllId, false, $arProps);
        return $arRes;    	
    }
    /**
    *   тянем данные для карты
    */
    public function getUserDataForCard() {
        $arRes['months'] = array(0=>'Январь',1=>'Февраль',2=>'Март',3=>'Апрель',4=>'Май',5=>'Июнь',6=>'Июль',7=>'Август',8=>'Сентябрь',9=>'Октябрь',10=>'Ноябрь',11=>'Декабрь');
        $id = Share::$UserProfile->id;

        if($id>0) {
            $arRes['user'] = Yii::app()->db->createCommand()
                                ->select('a.val phone, u.email, u.status')
                                ->from('user u')
                                ->leftJoin(
                                    'user_attribs a', 
                                    'a.id_us=u.id_user AND a.id_attr=1'
                                )
                                ->where(
                                    'u.id_user=:idus', 
                                    array(':idus' => $id)
                                )
                                ->queryRow();

            if(isset($arRes['user']['phone'])){
                $arRes['user']['phone'] = str_replace('+','',$arRes['user']['phone']);
                $pos = strpos($arRes['user']['phone'], '(');
                $arRes['user']['phone-code'] = substr($arRes['user']['phone'],0,$pos);
                $arRes['user']['phone'] = substr($arRes['user']['phone'], $pos);
            }

            if($arRes['user']['status']==2){
              $res = Yii::app()->db->createCommand()
                ->select('r.firstname, r.lastname, r.birthday')
                ->from('resume r')
                ->where('r.id_user=:idus', array(':idus' => $id))
                ->queryRow();
            }
            if($arRes['user']['status']==3){
              $res = Yii::app()->db->createCommand()
                ->select('e.firstname, e.lastname')
                ->from('employer e')
                ->where('e.id_user=:idus', array(':idus' => $id))
                ->queryRow();
            }
            $arRes['user']['firstname'] = $res['firstname'];
            $arRes['user']['lastname'] = $res['lastname'];

            if(!empty($res['birthday']))
                $arRes['user']['birthday'] = DateTime::createFromFormat(
                                                    'Y-m-d', 
                                                    $res['birthday']
                                                )->format('d.m.Y');
        }
        return $arRes;
    }
    /**
     * @param $id_user - integer
     * @param $buildArray - bool
     * @return array
     */
    public function getOutstaffingByUser($id_user, $buildArray=false)
    {
      $arRes = [];
      $query = Yii::app()->db->createCommand()
        ->select('*')
        ->from('outstaffing o')
        ->where('id=:id',[':id'=>$id_user])
        ->order('date desc')
        ->queryAll();

      if(count($query))
      {
        if($buildArray)
        {
          foreach ($query as $v)
          {
            $arService = [];
            !empty($v['rezident']) && $arService[]=$v['rezident'];
            !empty($v['nrezident']) && $arService[]=$v['nrezident'];
            !empty($v['consult']) && $arService[]=$v['consult'];
            !empty($v['advertising']) && $arService[]=$v['advertising'];
            !empty($v['control']) && $arService[]=$v['control'];

            $arRes[] = [
              'id_user' => $v['id'],
              'vacancy' => $v['vacancy'],
              'type' => $v['type'],
              'name' => self::getServiceName($v['type']),
              'date' => $v['date'],
              'cost' => 0,
              'status' => ($v['is_new']?'В обработке':'Выполнена'),
              'data' => [
                'phone' => $v['phone'],
                'email' => $v['email'],
                'text' => $v['text'],
              ],
              'services' => $arService
            ];
          }
        }
        else
        {
          $arRes = $query;
        }
      }

      return $arRes;
    }
    /*
     * Глобальное название услуг
     */
    public static function getServiceName($code)
    {
      switch ($code)
      {
        case 'creation-vacancy': $name = 'Создание вакансии'; break;
        case 'vacancy': $name = 'Премиум вакансия'; break;
        case 'repost': $name = 'Публикация в соцсетях'; break;
        case 'email': $name = 'Электронная почта'; break;
        case 'push': $name = 'PUSH уведомления'; break;
        case 'sms': $name = 'SMS информирование'; break;
        case 'api': $name = 'Получение API ключа'; break;
        case 'outsourcing': $name = 'Личный менеджер и аутсорсинг персонала'; break;
        case 'outstaffing': $name = 'Аутстаффинг персонала'; break;
        case 'card': $name = 'Получение корпоративной карты Prommu'; break;
        case 'medbook': $name = 'Получение медицинской книги'; break;
        default: $name = 'Услуга'; break;
      }

      return $name;
    }
    /**
     *  счетчик непросмотренных услуг
     */
    public static function getAdminCnt()
    {
      $arRes = ['cnt'=>0,'items'=>[]];
      // service_order
      $cnt = ServiceGuestOrder::getCount();
      if($cnt>0)
      {
        $arRes['items'][] = [
          'name' => 'Заказ услаг гостями',
          'link' => '/admin/service/service_order',
          'icon' => 'glyphicon-envelope',
          'cnt' => $cnt
        ];
        $arRes['cnt'] += $cnt;
      }
      // service_cloud
      $arOrder = Service::getAdminCnt();
      if($arOrder['cnt'])
      {
        if($arOrder['vacancy']>0) // premium
        {
          $arRes['items'][] = [
            'name' => Services::getServiceName('vacancy'),
            'link' => '/admin/service/service_cloud/vacancy',
            'icon' => 'glyphicon-star-empty',
            'cnt' => $arOrder['vacancy']
          ];
        }
        if($arOrder['email']>0) // email
        {
          $arRes['items'][] = [
            'name' => Services::getServiceName('email'),
            'link' => '/admin/service/service_cloud/email',
            'icon' => '',
            'cnt' => $arOrder['email']
          ];
        }
        if($arOrder['sms']>0) // sms
        {
          $arRes['items'][] = [
            'name' => Services::getServiceName('sms'),
            'link' => '/admin/service/service_cloud/sms',
            'icon' => 'glyphicon-envelope',
            'cnt' => $arOrder['sms']
          ];
        }
        if($arOrder['push']>0) // push
        {
          $arRes['items'][] = [
            'name' => Services::getServiceName('push'),
            'link' => '/admin/service/service_cloud/push',
            'icon' => 'glyphicon-comment',
            'cnt' => $arOrder['push']
          ];
        }
        if($arOrder['repost']>0) // repost
        {
          $arRes['items'][] = [
            'name' => Services::getServiceName('repost'),
            'link' => '/admin/service/service_cloud/repost',
            'icon' => 'glyphicon-bullhorn',
            'cnt' => $arOrder['repost']
          ];
        }
        if($arOrder['api']>0) // api
        {
          $arRes['items'][] = [
            'name' => Services::getServiceName('api'),
            'link' => '/admin/service/service_cloud/api',
            'icon' => 'glyphicon-cog',
            'cnt' => $arOrder['api']
          ];
        }
        $arRes['cnt'] += $arOrder['cnt'];
      }
      // outstaffing
      $arOrder = Outstaffing::getAdminCnt();
      if($arOrder['cnt'])
      {
        if($arOrder['outstaffing']>0) // outstaffing
        {
          $arRes['items'][] = [
            'name' => Services::getServiceName('outstaffing'),
            'link' => '/admin/service/outstaffing/outstaffing',
            'icon' => 'glyphicon-edit',
            'cnt' => $arOrder['outstaffing']
          ];
        }
        if($arOrder['outsourcing']>0) // outsourcing
        {
          $arRes['items'][] = [
            'name' => Services::getServiceName('outsourcing'),
            'link' => '/admin/service/outstaffing/outsourcing',
            'icon' => 'glyphicon-check',
            'cnt' => $arOrder['outsourcing']
          ];
        }
        $arRes['cnt'] += $arOrder['cnt'];
      }
      // med_request
      $cnt = MedCard::getAdminCnt();
      if($cnt>0)
      {
        $arRes['items'][] = [
          'name' => Services::getServiceName('medbook'),
          'link' => '/admin/service/med_request',
          'icon' => 'glyphicon-plus-sign',
          'cnt' => $cnt
        ];
        $arRes['cnt'] += $cnt;
      }
      // card_request
      $cnt = UserCard::getAdminCnt();
      if($cnt>0)
      {
        $arRes['items'][] = [
          'name' => Services::getServiceName('card'),
          'link' => '/admin/service/card_request',
          'icon' => 'glyphicon-credit-card',
          'cnt' => $cnt
        ];
        $arRes['cnt'] += $cnt;
      }
      return $arRes;
    }
}