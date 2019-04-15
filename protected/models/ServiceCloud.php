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
		$arRes = array('items'=>[]);
		$cnt = $this->getVacDataCnt($id_vacancy);
		if(!$cnt)
			return $arRes;

		$arRes['pages'] = new CPagination($cnt);
		$arRes['pages']->pageSize = $this->limit;
		$arRes['pages']->applyLimit($this);

		$arRes['items'] = Yii::app()->db->createCommand()
							->select("id,
								type,
								name vacancy,
								status,
								user,
								DATE_FORMAT(date,'%H:%i %d.%m.%Y') date")
							->from('service_cloud')
							->where(
								"name=:id AND type IN('email','push','sms')",
								[':id'=>$id_vacancy]
							)
							->order('id desc')
							->limit($this->limit)
							->offset($this->offset)
							->queryAll();

		return $arRes;
	}
}