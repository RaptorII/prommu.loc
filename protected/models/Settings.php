<?
class Settings extends CActiveRecord
{
	public static $cacheID = 'All_Settings_Data'; // уникальный ID кеша
	public static $cacheTime = 31536000; // хранение кеша 1 год, или пока админ не изменит данные

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
			$arRes['data'] = $this::model()->findAll();
			Cache::setData($arRes, self::$cacheTime);
		}
		
		return $arRes['data'];
	}
	/**
	*		Чтение конкретного значения	
	*/
	public function getDataByCode($code)
	{
		$result = false;
		$arData = $this->getData();

		foreach ($arData as $obj)
		{
			if($obj->code!==$code)
				continue;

			$result = $obj->value;
		}

		return $result;
	}
	/**
	 * получение корня файлового сервера
	 */
	public static function getFilesRoot()
	{
		$data = Cache::getData(self::$cacheID);
		if($data['data']===false)
		{
			$data['data'] = self::model()->findAll();
			Cache::setData($data, self::$cacheTime);
		}
	
		foreach ($data['data'] as $obj)
		{
			if($obj->code!=='files_root')
				continue;

			return $obj->value;
		}
		return false;
	}
	/**
	 * получение урла файлового сервера
	 */
	public static function getFilesUrl()
	{
		$data = Cache::getData(self::$cacheID);
		if($data['data']===false)
		{
			$data['data'] = self::model()->findAll();
			Cache::setData($data, self::$cacheTime);
		}
	
		foreach ($data['data'] as $obj)
		{
			if($obj->code!=='files_url')
				continue;

			return $obj->value;
		}
		return false;
	}
	/**
	*		Запись данных
	*/
	public function setData($obj)
	{
		$value = intval($obj->getParam('register_captcha'));
		$this::model()->updateAll(['value'=>$value], "code='register_captcha'");

		$value = filter_var($obj->getParam('files_root'),FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$this::model()->updateAll(['value'=>$value], "code='files_root'");

		$value = filter_var($obj->getParam('files_url'),FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$this::model()->updateAll(['value'=>$value], "code='files_url'");

		Yii::app()->user->setFlash('success', 'Данные успешно сохранены');

		$arRes = Cache::getData(self::$cacheID);
		$arRes['data'] = $this::model()->findAll();
		Cache::setData($arRes, self::$cacheTime);
	}
}