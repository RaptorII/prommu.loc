<?
class ServiceCloud
{
  //
  // Оплата за создание вакансии
  //
  const PAYMENT_FOR_CREATE = [
    ['id_city' => 1307, 'price' => 200],
    ['id_city' => 1838, 'price' => 100]
  ];
  //
  //
  //
	public $limit;
	public $offset;

	function __construct()
	{
		$this->limit = 5;
		$this->offset = 0;
	}
	/**
	* @param $id_vacancy - int
	*		Чтение данных	
	*/
	public function getVacDataCnt($id_vacancy)
	{
		if(!$id_vacancy)
			return false;

		$query = Yii::app()->db->createCommand()
							->select("user")
							->from('service_cloud')
							->where(
								"name=:id AND type IN('email','push','sms')",
								[':id'=>$id_vacancy]
							)
							->queryAll();

		if(count($query))
		{
			$cnt = 0;
			foreach ($query as $v)
				$cnt += count(explode(',', $v['user']));

			return $cnt;
		}
		else
			return 0;
	}
	/**
	* @param $id_vacancy - int
	*		Чтение данных	
	*/
	public function getVacData($id_vacancy)
	{
		$arRes = array(
				'items'=>[],
				'users'=>[],
				'email'=>['items'=>[], 'good_status'=>0],
				'push'=>['items'=>[], 'good_status'=>0],
				'sms'=>['items'=>[], 'good_status'=>0]
			);
		$cnt = $this->getVacDataCnt($id_vacancy);
		if(!$cnt)
			return $arRes;

		$query = Yii::app()->db->createCommand()
							->select("id,
								type,
								name vacancy,
								status,
								user,
								UNIX_TIMESTAMP(date) date")
							->from('service_cloud')
							->where(
								"name=:id AND type IN('email','push','sms')",
								[':id'=>$id_vacancy]
							)
							->order('id desc')
							->queryAll();

		$arUsers = array();
		for ($i=0, $n=count($query); $i<$n; $i++)
		{
			$arUsers[] = $query[$i]['user'];
			$arRes['items'][$query[$i]['id']] = $query[$i];
			if($query[$i]['type']=='email')
			{
				$arRes['email']['items'][$query[$i]['id']] = $query[$i];
				$query[$i]['status'] && $arRes['email']['good_status']++;
			}
			if($query[$i]['type']=='push')
			{
				$arRes['push']['items'][$query[$i]['id']] = $query[$i];
				$query[$i]['status'] && $arRes['push']['good_status']++;
			}
			if($query[$i]['type']=='sms')
			{
				$arRes['sms']['items'][$query[$i]['id']] = $query[$i];
				$query[$i]['status'] && $arRes['sms']['good_status']++;
			}
		}

		$arRes['users'] = Share::getUsers($arUsers);

		return $arRes;
	}
  /**
   * @param $id_user - integer
   * @param $buildArray - bool
   * @param $limit - integer
   * @return array
   */
	public function getDataByUser($id_user, $buildArray=false, $limit=20)
  {
    $arRes = [];
    $this->limit = $limit;

    $query = Yii::app()->db->createCommand()
      ->select('*')
      ->from('service_cloud')
      ->where('id_user=:id',[':id'=>$id_user])
      ->order('id desc')
      ->limit($limit)
      ->offset($this->offset)
      ->queryAll();

    if(count($query))
    {
      if($buildArray)
      {
        foreach ($query as $v)
        {
          $arRes[] = [
            'id' => $v['id'],
            'id_user' => $v['id_user'],
            'vacancy' => $v['name'],
            'type' => $v['type'],
            'name' => Services::getServiceName($v['type']),
            'date' => $v['date'],
            'cost' => $v['sum'],
            'city' => $v['city'],
            'status' => ($v['status'] ? 'Выполнено' : 'Ожидает оплаты'),
            'data' => [
              'bdate' => $v['bdate'],
              'edate' => $v['edate'],
              //'key' => $v['key'],
              //'text' => $v['text'],
              'user' => $v['user'],
              'legal' => $v['legal']
            ],
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
  /**
   * @param $id_user - integer (user => id_user)
   * @param int $limit - integer
   * @return array
   */
  public function getServiceUsers($id_service, $id_user, $limit=30)
  {
    $this->limit = $limit;
    $query = Yii::app()->db->createCommand()
      ->select('user')
      ->from('service_cloud')
      ->where(
        'id=:id and id_user=:id_user',
        [':id'=>$id_service, ':id_user'=>$id_user]
      )
      ->order('id desc')
      ->limit($this->limit)
      ->offset($this->offset)
      ->queryScalar();

    $arRes = ['items'=>[],'pages'=>[]];
    $arId = Share::explode($query);

    if(count($arId))
    {
      rsort($arId);
      $arRes['pages'] = new CPagination(count($arId));
      $arRes['pages']->pageSize = $limit;
      $arRes['pages']->applyLimit($this);
      $arResId = [];

      for($i=$this->offset, $n=count($arId); $i<$n; $i++)
        if($i<($this->offset+$this->limit))
          $arResId[] = $arId[$i];

      $arRes['items'] = Share::getUsers($arResId);
    }

    return $arRes;
  }
  /**
   *
   */
  public function orderApi()
  {
    $api = Yii::app()->getRequest()->getParam('api');
    $id_user = Share::$UserProfile->id;

    if(in_array($api, [1,2]))
    {
      $arChat = [
        'idus' => Im::$ADMIN_APPLICANT,
        'theme' => 'API запрос',
        'message' => "Здравствуйте, я PROMMU BOT. Метод https://prommu.com/api.promo_search подготовлен. "
          . "Документация выгружена в файл по ссылке https://prommu.com/api-help#PROMO_SEARCH. "
          . "При использовании API ресурсами сервиса PROMMU вы соглашаетесь с пользовательским соглашением и принимаете условия использования https://prommu.com/api-private",
        'new' => $id_user
      ];
      $Im = new ImApplic();
      $resu = $Im->sendUserMessages($arChat);

      Yii::app()->db->createCommand()
        ->insert(
          'feedback',
          [
            'type' => UserProfile::$EMPLOYER,
            'name' => 'API ' . Share::$UserProfile->exInfo->name,
            'theme' => 'API запрос',
            'text' => 'Запрос на выгрузку',
            'email' => Share::$UserProfile->exInfo->email,
            'crdate' => date("Y-m-d"),
            'chat' => $resu['idtm']
          ]
        );
    }

    Yii::app()->db->createCommand()
      ->insert(
        'service_cloud',
        [
          'id_user' => $id_user,
          'name' => $id_user,
          'type' => "api",
          'bdate' => date("Y-m-d h-i-s"),
          'edate' => date("Y-m-d h-i-s"),
          'status' => 1,
          'sum' => 0,
          'text' => "Запрос на выгрузку API",
          'user' => "api"
        ]
      );

    Yii::app()->user->setFlash(
      'prommu_flash',
      'Ваша заявка на формирование запроса команд API сформирована. Все нужные команды Вы сможете взять из сформировавшегося окна диалогов. Также в нём можно будет задать вопросы администратору по возникшим техническим вопросам'
    );
  }
  /**
   * @param $arr
   * Получаем записи по массиву id
   */
  public function getServices($arr)
  {
    if(!is_array($arr) || !count($arr))
    {
      return false;
    }
    return Yii::app()->db->createCommand()
      ->select('*')
      ->from('service_cloud')
      ->where(['in','id',$arr])
      ->queryAll();
  }
  /**
   * @param $arCities - array of id_city
   * @return string
   * метод для вычисления стоимости создания вакансии
   */
  public static function getCostForVacancyCreate($arCities)
  {
    $cost = 0;
    foreach (self::PAYMENT_FOR_CREATE as $v)
    {
      if(in_array($v['id_city'], $arCities) && $v['price']>$cost)
      {
        $cost = $v['price'];
      }
    }
    return $cost;
  }
  /**
   * @param $id_vacancy
   * @return array
   */
  public function getServicesByVacancy($id_vacancy)
  {
    $result = [
      'creation_vacancy' => (object)[
        'items' => [],
        'legal_links' => [],
        'cities' => [],
        'individual_link' => ''
      ],
      'premium' => (object)[
        'items' => [],
        'cities' => [],
      ],
      'upvacancy' => (object)['items' => []],
      'personal-invitation' => (object)['items' => []],
      'email' => (object)['items' => []],
      'sms' => (object)['items' => []],
      'push' => (object)['items' => []],
      'outsourcing' => (object)['items' => []],
      'outstaffing' => (object)['items' => []],
    ];

    $query = Yii::app()->db->createCommand()
      ->from('service_cloud')
      ->where("name=:id_vacancy", [':id_vacancy' => $id_vacancy])
      ->queryAll();

    if(!$query)
    {
      return $result;
    }

    $arId = [];
    $cost = 0;
    foreach ($query as $v)
    {
      if($v['type']==='creation-vacancy' && $v['status']==0)
      {
        $result['creation_vacancy']->items[] = $v;
        if ($v['legal']) // юр.лицо
        {
          if(!in_array($v['legal'], $result['creation_vacancy']->legal_links)) // делаем чтобы счета не повторялись
          {
            $result['creation_vacancy']->legal_links[] = $v['legal'];
          }
        }
        else
        {
          $arId[] = $v['id'];
          $cost += $v['sum'];
        }
        $result['creation_vacancy']->cities[] = $v['city'];
      }
      elseif($v['type']==='vacancy')
      {
        $result['premium']->items[] = $v;
        $result['premium']->cities[] = $v['city'];
      }
      else
      {
        $result[$v['type']]->items[] = $v;
      }
    }

    if(count($result['creation_vacancy']->legal_links))
    {
      foreach ($result['creation_vacancy']->legal_links as &$v)
      {
        $v = MainConfig::$PAGE_LEGAL_ENTITY_RECEIPT . $v;
      }
      unset($v);
    }

    if(count($arId))
    {
      $result['creation_vacancy']->individual_link = (new PrommuOrder())->createPayLink(
        Share::$UserProfile->id . '.' . implode('.', $arId) . '.creation-vacancy.' . time(),
        '',
        $cost
      );
    }

    $arOutstaffing = Outstaffing::model()->findAll(
      "vacancy=:id AND type IN('outstaffing','outsourcing')",
      [':id'=>$id_vacancy]
    );
    if(count($arOutstaffing))
    {
      foreach ($arOutstaffing as $v)
      {
        $result[$v->type]->items[] = $v->getAttributes();
      }
    }

    return $result;
  }
}