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
	 * 
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
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
		$arRes = $this->validateForm();
		if($arRes['error'])
			return $arRes;

		$event = Yii::app()->getRequest()->getParam('event_type');


		$arParams = array(
									'status' => $arRes['form']['status'],
									'moder' => $arRes['form']['moder']
								);

		$arNewData = array(
									'title'	=> $arRes['form']['title'],
									'text' => $arRes['form']['text'],
									'receiver' => $arRes['form']['receivers'],
									'params' => serialize($arParams),
									'mdate'	=> time(),
									'cdate'	=> time()
								);
		
		if($id=='0')
		{
			Yii::app()->db->createCommand()->insert(
					'admin_mailing_letter', 
					$arNewData
				);
			$arRes['complete'] = true;
		}
		elseif($id>0)
		{
			Yii::app()->db->createCommand()->update(
					'admin_mailing_letter', 
					$arNewData, 
					'id=:id', 
					[':id' => $id]
				);
			$arRes['complete'] = true;
		}

		if($event=='send') // выполняем отправку
		{
			$conditions = '';
			// continue
			if(count($arRes['status']))
				$conditions = 'status IN(' . implode(',',$arRes['status']) . ')';
			if(count($arRes['moder']))
				$conditions = 'ismoder IN(' . implode(',',$arRes['moder']) . ')';

			if(strlen($conditions))
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
						$arRes['receiver'][] = $v['email'];
						$arRes['items'][] = $v; // на будущее логирование
					}
				}				
			}

			$arRes['receiver'] = array_unique($arRes['receiver']);
			// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
			if(count($arRes['receiver'])<1000)
			{
				$SM = Yii::app()->swiftMailer;
				$Transport = $SM->smtpTransport('mail.companyreport.net', 25, 'null')
												->setUsername('noreply@prommu.com')
												->setPassword('1I1OD6iL');

				$Mailer = $SM->mailer($Transport);

				foreach ($arRes['receiver'] as $email)
				{
					$Message = $SM->newMessage($arRes['title'])
												->setFrom(['auto-mailer@prommu.com'=>'Prommu.com'])
												->setTo([$email => ''])
												->addPart($arRes['text'],'text/html')
												->setBody('');
					// Send mail
					$Mailer->send($Message);
				}
			}
			// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
		}

		return $arRes;
	}
	/**
	*		Чтение письма	
	*/
	public function getLetter($id)
	{
		$arRes = array('error'=>true);

		$data = MailingLetter::model()->findByPk($id);

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
	/**
	 *  Проверка полей
	 */
	public function validateForm()
	{
		$arRes = array('error'=>false);
		// check form data
		// status
    $arRes['form']['status'] = Yii::app()->getRequest()->getParam('user_status');
		if(is_array($arRes['form']['status']))
			foreach ($arRes['form']['status'] as $v)
			{
				$v==1 && $arRes['status'][] = 0;
				$v==2 && $arRes['status'][] = $v;
				$v==3 && $arRes['status'][] = $v;
			}
		// ismoder
		$arRes['form']['moder'] = Yii::app()->getRequest()->getParam('user_moder');
		if(is_array($arRes['form']['moder']))
			foreach ($arRes['form']['moder'] as $v)
			{
				$v==1 && $arRes['moder'][] = $v;
				$v==2 && $arRes['moder'][] = 0;
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
				$arRes['receiver'][] = $arT[$k];
		}
		// title
    $arRes['form']['title'] = filter_var(
                    Yii::app()->getRequest()->getParam('title'),
                    FILTER_SANITIZE_FULL_SPECIAL_CHARS
                );
    $arRes['form']['title'] = trim($arRes['form']['title']);
    $arRes['title'] = $arRes['form']['title'];
    // text
    $arRes['form']['text'] = Yii::app()->getRequest()->getParam('text');
    $arRes['text'] = $arRes['form']['text'];
		// error
		if((!count($arRes['status']) && !count($arRes['moder'])) && !count($arRes['receiver']))
			$arRes['messages'][] = '- необходимо задать параметры для выборки пользователей или ввести валидный Email';
		if(empty($arRes['form']['title']) || empty($arRes['form']['text']))
			$arRes['messages'][] = '- поля "Заголовок" и "Текст письма" должны быть заполнены';
		if(count($arRes['messages']))
		{
			$arRes['error'] = true;
		}

		return $arRes;
	}
}


?>