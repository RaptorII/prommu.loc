<?
class MailingLetter extends CActiveRecord
{
	public $offset;
	public $limit;

	function __construct($scenario = 'insert')
	{
		parent::__construct($scenario = 'insert');

		$this->limit = 20;
		$this->offset = 0;
	}
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'admin_mailing_letter';
	}
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array();
	}
	/** 
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		$criteria = new CDbCriteria;
		return new CActiveDataProvider(
				'MailingLetter', 
				array(
					'criteria' => $criteria,
					'pagination' => ['pageSize' => 20],
					'sort' => ['defaultOrder'=>'id desc']
				)
			);
	}
	public static function getDate($date, $format = 'd.m.Y G:i')
	{
		return date($format, $date);
	}
	/**
	*		Запись письма	
	*/
	public function setLetter($id)
	{
		if(!Yii::app()->getRequest()->isPostRequest)
			return array('error'=>false);

		$arRes = array('error'=>true);
		$arReceiver = $arStatus = $arModer = array();
		$params = array();
		$conditions = '';
		// check form data
		// status
    $arRes['form']['status'] = Yii::app()->getRequest()->getParam('user_status');
		if(is_array($arRes['form']['status']))
			foreach ($arRes['form']['status'] as $v)
			{
				$v==1 && $arStatus[] = 0;
				$v==2 && $arStatus[] = $v;
				$v==3 && $arStatus[] = $v;
			}
		// ismoder
		$arRes['form']['moder'] = Yii::app()->getRequest()->getParam('user_moder');
		if(is_array($arRes['form']['moder']))
			foreach ($arRes['form']['moder'] as $v)
			{
				$v==1 && $arModer[] = $v;
				$v==2 && $arModer[] = 0;
			}
		// emails
    $arRes['form']['receivers'] = filter_var(
                    Yii::app()->getRequest()->getParam('receivers'),
                    FILTER_SANITIZE_FULL_SPECIAL_CHARS
                );
		$arT = explode(',',$arRes['form']['receivers']);
		foreach ($arT as $k => $v)
		{
			$arT[$k] = trim($v);
			if(filter_var($arT[$k],FILTER_VALIDATE_EMAIL))
				$arReceiver[] = $arT[$k];
		}
		// title
    $arRes['form']['title'] = filter_var(
                    Yii::app()->getRequest()->getParam('title'),
                    FILTER_SANITIZE_FULL_SPECIAL_CHARS
                );
    $arRes['form']['title'] = trim($arRes['form']['title']);
    // text
    $arRes['form']['text'] = Yii::app()->getRequest()->getParam('text');
    // event
		$event = Yii::app()->getRequest()->getParam('event_type');
		// error
		if((!count($arStatus) && !count($arModer)) && !count($arReceiver))
			$arRes['messages'][] = '- необходимо задать параметры для выборки пользователей или ввести валидный Email';
		if(empty($arRes['form']['title']) || empty($arRes['form']['text']))
			$arRes['messages'][] = '- поля "Заголовок" и "Текст письма" должны быть заполнены';
		if(count($arRes['messages']))
			return $arRes;
		// continue
		$arRes['error'] = false;
		if(count($arStatus))
			$conditions = 'status IN(' . implode(',',$arStatus) . ')';
		if(count($arModer))
			$conditions = 'ismoder IN(' . implode(',',$arModer) . ')';

		$arParams = array(
									'status' => $arRes['form']['status'],
									'moder' => $arRes['form']['moder']
								);
		$arInsert = array(
									'title'	=> $arRes['form']['title'],
									'text' => $arRes['form']['text'],
									'receiver' => $arRes['form']['receivers'],
									'params' => serialize($arParams),
									'mdate'	=> time(),
									'cdate'	=> time()
								);
		
		if($id==0)
		{
      Yii::app()->db->createCommand()->insert('admin_mailing_letter', $arInsert);
			$arRes['complete'] = true;
		}
		elseif($id>0)
		{

		}

		if($event=='send') // выполняем отправку
		{
			$sql = Yii::app()->db->createCommand()
							->select("id_user, email")
							->from('user')
							->where($conditions)
							->order('id_user desc')
							->queryAll();

			foreach ($sql as $v)
			{
				if(filter_var($v['email'],FILTER_VALIDATE_EMAIL))
				{
					$arReceiver[] = $v['email'];
					$arRes['items'][] = $v;
				}
			}
			$arReceiver = array_unique($arReceiver);
			/*
			*			Нужна отправка
			*/
		}

		return $arRes;
	}
	/**
	*		Чтение письма	
	*/
	public function getLetter($id)
	{
		$arRes = array('error'=>true);

		$data = $this->findByPk($id);

		if(!isset($data->id))
			return $arRes;

		$arRes['error'] = false;
		$params = unserialize($data->params);
		$arRes['form'] = array(
				'status' => $params['status'],
				'moder' => $params['moder'],
				'receivers' => $data->receiver,
				'title' => $data->title,
				'text' => $data->text,
			);
		return $arRes;
	}
}


?>