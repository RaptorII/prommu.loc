<?
class ServiceCloud
{
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
            'data' => [
              'bdate' => $v['bdate'],
              'edate' => $v['edate'],
              'status' => $v['status'],
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
}