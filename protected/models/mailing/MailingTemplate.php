<?
class MailingTemplate extends Mailing
{
	public static $CONTENT = '#CONTENT#';

	function __construct()
	{
		$this->view = 'notifications/template';
		$this->pageTitle = 'шаблона';
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return 'admin_mailing_template';
	}
	/*
	*		Поиск активного шаблона
	*/
	public function getActiveTemplate()
	{
		return $this::model()->find('isactive=1');
	}
	/**
	*		Запись данных
	*/
	public function setData($obj)
	{
		$arRes = array();
		// id
		$id = $obj->getParam('id');
		// name
		$this->name = filter_var(
		                trim($obj->getParam('name')),
		                FILTER_SANITIZE_FULL_SPECIAL_CHARS
		            	);
		// body
		$this->body = $obj->getParam('body');
		// isactive
		$this->isactive = $obj->getParam('isactive');
		
		$activeTemplate = $this->getActiveTemplate();
		$pos = strripos($this->body, $this::$CONTENT);
		if(!$this->isactive && (!isset($activeTemplate->id) || $id==$activeTemplate->id))
			$arRes['messages'][] = 'один шаблон должен быть обязательно активен';
		if(empty($this->name) || empty($this->body))
			$arRes['messages'][] = 'поля "Название шаблона" и "Тело шаблона" должны быть заполнены';
		if($pos === false)
		  $arRes['messages'][] = 'в теле шаблона отсутствует константа '.$this::$CONTENT;

		if(count($arRes['messages'])) // error
		{
			$arRes['error'] = true;
			$arRes['item'] = $this;
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

		if($this->isactive && isset($activeTemplate->id))
			$this::model()->updateByPk($activeTemplate->id,['isactive'=>0]);

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
	}
}