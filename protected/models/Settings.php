<?
class Settings extends CActiveRecord
{
	public static $cacheID = 'All_Settings_Data'; // уникальный ID кеша
	public static $cacheTime = 31536000; // хранение кеша 1 год, или пока админ не изменит данные
	public $settingsId;

	function __construct($reviewId=0)
	{
		$this->settingsId = 1;
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return 'admin_settings';
	}
	/**
	*		Чтение данных	
	*/
	public function getData()
	{
		$arRes = Cache::getData(self::$cacheID);
		if($arRes['data']===false)
		{
			$arRes['data'] = $this::model()->findByPk($this->settingsId);
			Cache::setData($arRes, self::$cacheTime);
		}
		
		return $arRes['data'];
	}
	/**
	*		Запись данных
	*/
	public function setData($obj)
	{
		$this->register_captcha = intval($obj->getParam('register_captcha'));
		$this->id = $this->settingsId;

		if($this->save())
		{
			Yii::app()->user->setFlash('success', 'Данные успешно сохранены');
		}
		else
		{
			Yii::app()->user->setFlash('danger', 'Ошибка сохранения');
		}

		$arRes = Cache::getData(self::$cacheID);
		$arRes['data'] = $this;
		Cache::setData($arRes, self::$cacheTime);
	}
}