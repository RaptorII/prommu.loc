<?
class Mailing extends CActiveRecord
{
	public $limit = 20;
	public $view;
	public $pageTitle;
	public static $cacheID = 'All_Mailing_Data'; // уникальный ID кеша
	public static $cacheTime = 31536000; // хранение кеша 1 год, или пока админ не изменит событие или шаблон

	function __construct()
	{
		$this->limit = 100;
		$this->view = 'notifications/system';
		$this->pageTitle = 'Просмотр отправки';
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return 'system_event_email';
	}
	/** 
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		$sort = (get_class($this)=='Mailing' ? 'id' : 'mdate') . ' desc';
		$criteria = new CDbCriteria;
		return new CActiveDataProvider(
				get_class($this), 
				array(
					'criteria' => $criteria,
					'pagination' => ['pageSize' => $this->limit],
					'sort' => ['defaultOrder' => $sort]
				)
			);
	}
	/**
	*		Чтение данных	
	*/
	public function getData($id)
	{
		return array('item' => $this::model()->findByPk($id));
	}
	/**
	*		общие константы (будут дополнятся)
	*/
	public static function mainParams()
	{
		return array(
							0 => array(
										'name' => "#SITE#",
										'pattern' => "/#SITE#/",
										'value' => Subdomain::site(),
										'description' => "Корень сайта"
									),
							1 => array(
										'name' => "#PAGE_PROFILE_COMMON#",
										'pattern' => "/#PAGE_PROFILE_COMMON#/",
										'value' => MainConfig::$PAGE_PROFILE_COMMON,
										'description' => "Раздел 'Анкеты'"
									),
							2 => array(
										'name' => "#PAGE_USER_CHATS_LIST#",
										'pattern' => "/#PAGE_USER_CHATS_LIST#/",
										'value' => Subdomain::site() . MainConfig::$PAGE_CHATS_LIST,
										'description' => "Раздел профиля пользователя 'Чаты'"
									),
							3 => array(
										'name' => "#PAGE_USER_VACANCY#",
										'pattern' => "/#PAGE_USER_VACANCY#/",
										'value' => Subdomain::site() . MainConfig::$PAGE_VACANCY,
										'description' => "Страница вакансии в публичной части"
									),
							4 => array(
										'name' => "#PAGE_FEEDBACK#",
										'pattern' => "/#PAGE_FEEDBACK#/",
										'value' => Subdomain::site() . MainConfig::$PAGE_FEEDBACK,
										'description' => "Страница обратной связи в публичной части"
									),
							5 => array(
										'name' => "#PAGE_ADMIN_USER_VACANCY#",
										'pattern' => "/#PAGE_ADMIN_USER_VACANCY#/",
										'value' => '/admin/site/VacancyEdit',
										'description' => "Страница вакансии в административной части"
									),
							6 => array(
										'name' => "#PAGE_USER_RESPONCES#",
										'pattern' => "/#PAGE_USER_RESPONCES#/",
										'value' => Subdomain::site() . MainConfig::$PAGE_RESPONSES,
										'description' => "Страница отзывов пользователя"
									),
							7 => array(
										'name' => "#PAGE_USER_VACANCY_EDIT#",
										'pattern' => "/#PAGE_USER_VACANCY_EDIT#/",
										'value' => Subdomain::site() . MainConfig::$PAGE_VACANCY_EDIT,
										'description' => "Страница редактирования вакансии в публичной части"
									),
							8 => array(
										'name' => "#PAGE_SERVICES_PREMIUM#",
										'pattern' => "/#PAGE_SERVICES_PREMIUM#/",
										'value' => Subdomain::site() . MainConfig::$PAGE_SERVICES_PREMIUM,
										'description' => "Страница услуги 'Премиум'"
									),
							9 => array(
										'name' => "#PAGE_SERVICES_GEO#",
										'pattern' => "/#PAGE_SERVICES_GEO#/",
										'value' => Subdomain::site() . MainConfig::$PAGE_SERVICES_GEO,
										'description' => "Страница услуги 'ГЕО'"
									),
							10 => array(
										'name' => "#PAGE_SERVICES_MEDICAL#",
										'pattern' => "/#PAGE_SERVICES_MEDICAL#/",
										'value' => Subdomain::site() . MainConfig::$PAGE_SERVICES_MEDICAL,
										'description' => "Страница услуги 'Медкнига'"
									),
							11 => array(
										'name' => "#PAGE_SERVICES_OUTSTAFFING#",
										'pattern' => "/#PAGE_SERVICES_OUTSTAFFING#/",
										'value' => Subdomain::site() . MainConfig::$PAGE_SERVICES_OUTSTAFFING,
										'description' => "Страница услуги 'Аутстаф'"
									),
							12 => array(
										'name' => "#PAGE_SETRATE#",
										'pattern' => "/#PAGE_SETRATE#/",
										'value' => Subdomain::site() . MainConfig::$PAGE_SETRATE,
										'description' => "Страница выставления рейтинга"
									),
							13 => array(
										'name' => "#PAGE_RATE#",
										'pattern' => "/#PAGE_RATE#/",
										'value' => Subdomain::site() . DS . MainConfig::$PAGE_RATE,
										'description' => "Страница просмотра рейтинга"
									)
				);
	}
	/**
	 * @param $status - bool
	 *		человекопонятный статус письма	
	 */
	public static function getStatus($status)
	{
		return $status<0 ? "Ошибка" : ($status>0 ? "Отправлено" : "Ожидание");
	}
	/**
	 * @param $status - bool
	 *		перевод значения boolean
	 */
	public static function getBool($bool)
	{
		return $bool ? "Да" : "Нет";
	}
	/**
	 * @param $date - integer (unix time)
	 * @param $format - string
	 *		красивая дата
	 */
	public static function getDate($date, $format = 'd.m.Y G:i')
	{
		return !empty($date) ? date($format, $date) : ' - ';
	}
	/**
	 * @param $email - string
	 */
	public function getEmailArray($email)
	{
		$arRes = array();
		$arItems = explode(',',$email);

		foreach ($arItems as $k => $v)
		{
			$email = trim($v);
			// проверяем на соответствие email или константе
			if(filter_var($email,FILTER_VALIDATE_EMAIL) || preg_match('/#{1}[A-Z_]+#{1}/', $email))
				$arRes[] = $email;
		}
		return $arRes;
	}
	/**
	 * @param $receiver - mixed (string|array)
	 * @param $title - string
	 * @param $body - string
	 * @param $isUrgent - bool
	 * Запись в БД для последущей отправки
	 */
	public static function setToMailing($receiver,$title,$body,$isUrgent=false)
	{
		$arRes = array();
		$time = time();

		if(is_array($receiver)) // если email указан массивом
		{
			foreach ($receiver as $email)
			{
				if(filter_var($email,FILTER_VALIDATE_EMAIL))
				{
					$arRes[] = array(
												'receiver' => $email,
												'title' => $title,
												'body' => $body,
												'cdate' => $time
											);					
				}
			}
		}
		else // если email один
		{
			if(filter_var($receiver,FILTER_VALIDATE_EMAIL))
			{
				$arRes[] = array(
											'receiver' => $receiver,
											'title' => $title,
											'body' => $body,
											'cdate' => $time
										);
			}
		}

		if(count($arRes))
			Share::multipleInsert(['system_event_email'=>$arRes]);
	}
	/**
	 * @param $limit - integer (300 in default)
	 * Отправка по крону и обновление значений таблицы system_event_email
	 */
	public static function send($limit=300)
	{
		$limit = intval($limit);
		!$limit && $limit = 300;
		$arRes = ['success'=>0, 'error'=>0, 'error-id'=>[]];

		$arMail = self::model()->findAll(array(
									'condition' => 'status=0',
									'order' => 'is_urgent DESC',
									'limit' => $limit
								)
							);

		if(!count($arMail))
			return $arRes;

		$SM = Yii::app()->swiftMailer; // swiftMailer !!!!!!!!!!!!!!!!!!!!!!!
		$Transport = $SM->smtpTransport('mail.companyreport.net', 25, 'null')
										->setUsername('noreply@prommu.com')
										->setPassword('1I1OD6iL');

		$Mailer = $SM->mailer($Transport);

		foreach($arMail as $k => $v)
		{
			$Message = $SM->newMessage($v->title)
										->setFrom(['auto-mailer@prommu.com'=>'Prommu.com'])
										->setTo([$v->receiver => ''])
										->addPart($v->body,'text/html')
										->setBody('');

			$arMail[$k]->rdate = time(); // фиксируем время отправки
			if (!$Mailer->send($Message, $failures))
			{
				$arMail[$k]->status = -1; // устанавливаем статус "Ошибка"
				$arMail[$k]->result = serialize($failures);
				$arRes['error-id'][] = $arMail[$k]->id;
				$arRes['error']++;
			}
			else
			{
				$arMail[$k]->status = 1; // устанавливаем статус "Отправлено"
				$arMail[$k]->result = serialize([]);
				$arRes['success']++;
			}

			$arMail[$k]->save();
		}
		return $arRes;
	}
	/**
	 * @param $event - number ID
	 * @param $arParams - array()
	 * @param $usertype - number (2,3,0=default)
	 * Распарсиваем ВСЕ строки и выполняем подмену констант с постановкой сформированного письма в очередь в активном шаблоне
	 */
  public static function set($event, $arParams, $usertype=0)
  {
		$arPatterns = array();
		$arRes = self::getCacheData();

		$objEvent = $arRes['events'][$event];
		$objTemplate = $arRes['template'];

		if(!$objEvent || !$objTemplate) // нет данных - смысла продолжать тоже нет
			return;

		$arEventParams = unserialize($objEvent->params);
		$arValues = array();
		// можем попробовать хранить пуш и емейл в одном месте
		if($objEvent->type==1 && $objEvent->is_active) // если это email 
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
			$arMainParams = Mailing::mainParams();
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

			// меняем константы в получателях
			$receivers = preg_replace($arPatterns, $arValues, $objEvent->receiver);
			$arReceivers = explode(",", $receivers);
			// меняем константы в заголовке
			$title = preg_replace($arPatterns, $arValues, $objEvent->title);
			// меняем константы в теле письма
			$body = preg_replace($arPatterns, $arValues, $objEvent->text);
			// помещаем письмо в шаблон
			if($objEvent->in_template)
			{
				$body = str_replace(MailingTemplate::$CONTENT, $body, $objTemplate->body);
			}
			// go 
			self::setToMailing($arReceivers, $title, $body, $objEvent->is_urgent);
		}
	}
	/**
	 * 		Делаем выборку из кеша
	 */
	public static function getCacheData()
	{
		$arRes = Cache::getData(self::$cacheID);
		if($arRes['data']===false)
			$arRes = self::setCacheData();
		
		return $arRes['data'];
	}
	/**
	 * 	Делаем запись в кеш
	 */
	public static function setCacheData()
	{
		$arRes = Cache::getData(self::$cacheID);
		$arRes['data'] = array();
		$event = new MailingEvent;
		$arEvents = $event::model()->findAll(); // Нужны все события
		foreach ($arEvents as $v)
			$arRes['data']['events'][$v->id] = $v;
		$template = new MailingTemplate;
		$arRes['data']['template'] = $template->getActiveTemplate(); // и активный шаблон
		Cache::setData($arRes, self::$cacheTime);
		return $arRes;
	}
}