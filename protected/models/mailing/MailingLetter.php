<?
class MailingLetter extends Mailing
{
	function __construct()
	{
		$this->view = 'notifications/letter';
		$this->pageTitle = 'письма';
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return 'admin_mailing_letter';
	}
	/**
	*		Чтение данных	
	*/
	public function getData($id)
	{
		$template = new MailingTemplate;
		$arRes['template'] = $template->getActiveTemplate();
		$arRes['item'] = $this::model()->findByPk($id);

		return $arRes;
	}
	/**
	*		Запись данных
	*/
	public function setData($obj)
	{
		$arParams = array();
		$arRes = array('error'=>false);
		// status
		$arParams['status'] = $obj->getParam('user_status');
		// ismoder
		$arParams['moder'] = $obj->getParam('user_moder');
		// emails
		$this->receiver = filter_var(
		                $obj->getParam('receiver'),
		                FILTER_SANITIZE_FULL_SPECIAL_CHARS
		            );
		$arReceiver = $this->getEmailArray($this->receiver);
		// title
		$this->title = filter_var(
		                trim($obj->getParam('title')),
		                FILTER_SANITIZE_FULL_SPECIAL_CHARS
		            	);
		// text
		$this->text = $obj->getParam('text');
		
		if(
			(!count($arParams['status']) && !count($arParams['moder']))
			&& 
			!count($arReceiver)
		)
			$arRes['messages'][] = 'необходимо задать параметры для выборки пользователей или ввести корректный Email';
		if(empty($this->title) || empty($this->text))
			$arRes['messages'][] = 'поля "Заголовок" и "Текст письма" должны быть заполнены';

		$this->params = serialize($arParams);

		if(count($arRes['messages'])) // error
		{
			$arRes['error'] = true;
			$arRes['item'] = $this;

			return $arRes;
		}
		
		$event = $obj->getParam('event_type');

		// Сохраняем в любом случае
		//if($event=='save') // save data
		//{
			$time = time();
			$this->mdate = $time;
			$id = $obj->getParam('id');

			if(!intval($id)) // insert
			{
				$this->cdate = $time;
				$this->setIsNewRecord(true);
			}
			else // update
			{
				$this->id = $id;
			}

			if($this->save())
			{
				$arRes['redirect'] = true;
				Yii::app()->user->setFlash('success', 'Данные успешно сохранены');
			}
			else
			{
				$arRes['redirect'] = true;
				Yii::app()->user->setFlash('danger', 'Ошибка сохранения');
			}

		//}
		if($event=='send') // send data
		{
			$conditions = '';
			// continue
			if(count($arParams['status']))
				$conditions = 'status IN(' . implode(',',$arParams['status']) . ')';
			if(count($arParams['moder']))
				$conditions = 'ismoder IN(' . implode(',',$arParams['moder']) . ')';

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
						$arReceiver[] = $v['email'];
						$arRes['items'][] = $v; // на будущее логирование
					}
				}				
			}

			$arReceiver = array_unique($arReceiver);

			if(count($arReceiver))
			{
				$this->setToMailing($arReceiver, $this->title, $this->text);
				$arRes['redirect'] = true;
				Yii::app()->user->setFlash('success', 'Данные сохранены поставлены в очередь отправки');
			}
			else
			{
				$arRes['redirect'] = true;
				Yii::app()->user->setFlash('danger', 'Постановка в очередь не удалась');
			}
		}

		return $arRes;
	}
}
?>