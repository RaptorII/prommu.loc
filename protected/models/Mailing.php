<?
class Mailing extends CActiveRecord
{
	public $offset;
	public $limit;

	public static $EVENT_TYPE_EMAIL = 1;
	public static $EVENT_TYPE_PUSH = 2;
	public static $TYPES = array(
			1 => 'email',
			2 => 'push'
		);

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
		return 'admin_mailing_event';
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
				'Mailing', 
				array(
					'criteria' => $criteria,
					'pagination' => ['pageSize' => 20],
					'sort' => ['defaultOrder'=>'id desc']
				)
			);
	}
	/**
	*		красивая дата
	*/
	public static function getDate($date, $format = 'd.m.Y G:i')
	{
		return date($format, $date);
	}


















	public static function mainParams()
	{
		return array(
							0 => array(
										'name' => "#SITE#",
										'pattern' => "/#SITE#/",
										'value' => Subdomain::$HOST,
										'description' => "Корень сайта"
									),
							1 => array(
										'name' => "#PAGE_PROFILE_COMMON#",
										'pattern' => "/#PAGE_PROFILE_COMMON#/",
										'value' => MainConfig::$PAGE_PROFILE_COMMON,
										'description' => "Раздел 'Анкеты'"
									)
				);
	}
	// набор параметров для события, которые пото можно перенести
	public static $PARAMS = array(
		'EMAIL_CHANGE_PROFILE_LOGO' => array( // собитие изменения лого
			'receivers' => "mk0630733719@gmail.com,susgresk@gmail.com",
			'title' => "Prommu.com Изменение профиля юзера #ID_USER#",
			'text' => "Пользователь <a href='#LINK_PROFILE#'>#ID_USER#</a> изменил данные профиля.<br/>Изменены поля: Логотип компании|<br/>Перейти на модерацию соискателя <a href='#LINK_PROFILE_ADMIN#'>по ссылке</a>.",
			'params' => array(
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
		)
	);
	/**
	 * @param $event - number ID
	 * @param $arParams - array()
	 * @param $usertype - number
	 */
  public static function send($event, $arParams, $usertype=0)
  {
		$arPatterns = array();
		$type = 'email'; // можем попробовать хранить пуш и емейл в одном месте
		$arEventParams = self::$PARAMS[$event]['params'];
		$arValues = array();

		if($type=='email')
		{
			foreach ($arEventParams as $k => $v) // собираем из двух массивов
			{
				$arEventParams[$k]['value'] = is_array($v['value']) 
					? $v['value'][$usertype] : $v['value']; // если есть зависимость от типа юзера

				if(array_key_exists($k, $arParams)) // если value пришло при вызове метода
					$arEventParams[$k]['value'] = $arParams[$k];

				if(!$v['breplace'])
				{
					$arPatterns[] = $arEventParams[$k]['pattern'];
					$arValues[] = $arEventParams[$k]['value'];
				}
			}
			// добавляем общих параметров
			$arMainParams = self::mainParams();
			foreach ($arMainParams as $k => $v)
			{
				$arPatterns[] = $v['pattern'];
				$arValues[] = $v['value'];
			}
			// заменяем константы в значениях
			foreach ($arEventParams as $k => $v) // собираем из двух массивов
			{
				if($v['breplace']==true)
				{
					$arEventParams[$k]['value'] = preg_replace(
																					$arPatterns, 
																					$arValues, 
																					$arEventParams[$k]['value']
																				);
					$arPatterns[] = $v['pattern'];
					$arValues[] = $arEventParams[$k]['value'];
				}
			}

			$receivers = preg_replace(
											$arPatterns, 
											$arValues, 
											self::$PARAMS[$event]['receivers']
										);

			$title = preg_replace(
											$arPatterns, 
											$arValues, 
											self::$PARAMS[$event]['title']
										);

			$text = preg_replace(
											$arPatterns, 
											$arValues, 
											self::$PARAMS[$event]['text']
										);

			$arReceivers = explode(",", $receivers);

			foreach ($arReceivers as $v)
			{
				$email = trim($v);
				if(filter_var($email, FILTER_VALIDATE_EMAIL))
					Share::sendmail($email, $title, $text);
			}
		}
	}
}