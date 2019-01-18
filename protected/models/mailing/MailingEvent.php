<?
class MailingEvent extends Mailing
{
	public static $EVENT_TYPE_EMAIL = 1;
	public static $EVENT_TYPE_PUSH = 2;
	public static $TYPES = array(
			1 => 'email',
			2 => 'push'
		);

	function __construct()
	{
		$this->view = 'notifications/event';
		$this->pageTitle = 'события';
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return 'admin_mailing_event';
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
		$arRes = array('error'=>false);
		// id
		$id = $obj->getParam('id');
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
		
		if(!count($arReceiver))
			$arRes['messages'][] = 'необходимо ввести корректный Email';
		if(empty($this->title) || empty($this->text))
			$arRes['messages'][] = 'поля "Заголовок" и "Текст письма" должны быть заполнены';

		if(count($arRes['messages'])) // error
		{
			$arRes['error'] = true;
			$event = $this->getData($id)['item'];
			$this->comment = $event->comment;
			$this->type = $event->type;
			$this->params = $event->params;
			$arRes['item'] = $this;
			$template = new MailingTemplate;
			$arRes['template'] = $template->getActiveTemplate();

			return $arRes;
		}

		$time = time();
		$this->mdate = $time;

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
			$this->setCacheData(); // Заносим все в кеш для ускорения работы
			Yii::app()->user->setFlash('success', 'Данные успешно сохранены');
			return array('redirect' => true);
		}
		else
		{
			Yii::app()->user->setFlash('danger', 'Ошибка сохранения');
			return array('redirect' => true);
		}

		return $arRes;
	}



	/*
		Пример массива параметров для события
		array(
			'id_user' => array(
										'name' => "#ID_USER#",
										'pattern' => "/#ID_USER#/",
										'description' => "ID пользователя сайта"
									),
			'link_profile' => array(
										'name' => "#LINK_PROFILE#",
										'pattern' => "/#LINK_PROFILE#/",
										'value' => '#SITE##PAGE_PROFILE_COMMON#/#ID_USER#',
										'breplace' => true, // флаг, обозначающий наличие заменяемых частей в value
										'description' => "Ссылка на профиль пользователя"
									),
			'link_profile_admin' => array(
										'name' => "#LINK_PROFILE_ADMIN#",
										'pattern' => "/#LINK_PROFILE_ADMIN#/",
										'value' => array( // ключ массива в value соответствует типу $usertype
												2 => '#SITE#/admin/site/PromoEdit/#ID_USER#',
												3 => '#SITE#/admin/site/EmplEdit/#ID_USER#'
											),
										'breplace' => true,
										'description' => "Ссылка на профиль пользователя в админке"
									)
		)
	*/
}
?>