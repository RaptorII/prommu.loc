<?php

class Subdomain
{
	public static $CITY_ID = 1307; // MOSCOW
	public static $MAIN_SITE = 'https://prommu.com';
	public static $MAIN_SEND_FILE_URL = '/ajax/AcceptFileFromSubdomain';
	public static $MAIN_DEL_FILE_URL = '/ajax/DelThroughSubdomain';
	public static $MAIN_SITE_ROOT = '/var/www/html'; // для загрузки файлов на домен
	public static $REDIRECT = '/api.auth_user/?id=#ID#&code=prommucomWd126wdn&url=';

	public static $SUBDOMAINS = array(
			1838, 	// SPB
			1449 	// Novosibirsk
		);

	public static $SITES = array(
			1307 => array(
					'seo' => 'seo',
					'url' => 'https://prommu.com',
					'meta' => 'Prommu.com',
					'label' => 'Сервис №1 в поиске временной работы и персонала для BTL и Event-мероприятий',
					'city' => 'Москва'
				),
			1838 => array(
					'seo' => 'seo_spb',
					'url' => 'https://spb.prommu.com',
					'meta' => 'Spb.prommu.com',
					'label' => 'Сервис №1 в поиске временной работы и персонала для BTL и Event-мероприятий в Санкт-Петербурге',
					'city' => 'Санкт-Петербург'
				),
			1449 => array(
					'seo' => 'seo_novosibirsk',
					'url' => 'https://novosibirsk.prommu.com',
					'meta' => 'Novosibirsk.prommu.com',
					'label' => 'Сервис №1 в поиске временной работы и персонала для BTL и Event-мероприятий в Новосибирске',
					'city' => 'Новосибирск'
				)
		);
	/*
	*		Определяем название для МЕТА
	*/
	public static function getSubdomain($arCities)
	{
		$arCnt = array_fill_keys(self::$SUBDOMAINS, 0);
		foreach ($arCities as $c)
			if(in_array($c['region'], self::$SUBDOMAINS))
				$arCnt[$c['region']]++;

		foreach ($arCnt as $id => $cnt)
			if($cnt==count($arCities))
				return array(
					'name' => self::$SITES[$id]['meta']
				);

		return array(
			'name' => self::$SITES[1307]['meta']
		);
	}
	/*
	*		Определяем город и при необходимости редиректимся на главной
	*		$typeUs => user type
	*		$idus => user ID
	*/
	public static function getCity($typeUs, $idus)
	{
		if(in_array($typeUs, [2,3])){ // определение города юзера
			$arRes = Yii::app()->db->createCommand()
				->select('
					uc.id_city id, 
					c.region, c.name, 
					c.id_co country,
					c.ismetro metro,
					c.seo_url seo
				')
				->from('user_city uc')
				->join('city c', 'uc.id_city=c.id_city')
				->where('uc.id_user=:idus', array(':idus' => $idus))
				->queryAll();

			$arCnt = array_fill_keys(self::$SUBDOMAINS, 0);
			foreach ($arRes as $c)
				if(in_array($c['region'], self::$SUBDOMAINS))
					$arCnt[$c['region']]++;

			if(!$arCnt[self::$CITY_ID]){ // Редиректим только если вообще нет городов субдомена
				foreach ($arCnt as $id => $cnt)
					if($cnt>0 && $cnt==sizeof($arRes))
						self::setRedirect($idus, $id);
				// если никуда не перешли - идем на основной
				self::setRedirect($idus, 1307);
			}
			Yii::app()->request->cookies['city'] = new CHttpCookie('city', $arRes[0]['id']);
			return $arRes[0];
		}
		else{ // определяем гостя
			$geo = new Geo();
			$city = $geo->getCity(Yii::app()->request->cookies['city']->value);
			Yii::app()->request->cookies['city'] = new CHttpCookie('city', $city['id']);
			return $city;
		}
	}
	/*
	*		Редирект со страницы профиля
	*/
	public static function profileRedirect($idus)
	{
		$arRes = Yii::app()->db->createCommand()
			->select('uc.id_city, c.region')
			->from('user_city uc')
			->join('city c', 'uc.id_city=c.id_city')
			->where('id_user=:id_user', array(':id_user' => $idus))
			->queryAll();


		$arCnt = array_fill_keys(self::$SUBDOMAINS, 0);
		foreach ($arRes as $c)
			if(in_array($c['region'], self::$SUBDOMAINS))
				$arCnt[$c['region']]++;

		if(!$arCnt[self::$CITY_ID]){ // Редиректим только если вообще нет городов субдомена
			foreach ($arCnt as $id => $cnt) 
				if($cnt>0 && $cnt==sizeof($arRes))
					self::setRedirect($idus, $id, true);

			// если никуда не перешли - идем на основной
			self::setRedirect($idus, 1307, true);
		}
	}
	/*
	*
	*/
	public static function popupRedirect($city, $idus){
		$arRes = Yii::app()->db->createCommand()
			->select('t.name, t.region')
			->from('city t')
			->where('t.id_co=1') // only RF!!!
			->limit(10000);
		$arCities = $arRes->queryAll();

		foreach ($arCities as $c)
			if($city==$c['name'] && $c['region']!=self::$CITY_ID) {
				// редирект если выбраный город не относится к региону субдомена
				$site = in_array($c['region'], self::$SUBDOMAINS) ? $c['region'] : 1307;
				self::setRedirect($idus, $site, true);
			}
	}
	/*
	*		Получаем ID городов региона субдомена в виде строки
	*/
	public static function getCitiesIdies($main = false)
	{
		$arRes = array();
		if($main) {
			$arRes = Yii::app()->db->createCommand()
				->select('c.id_city id')
				->from('city c')
				->where(array('in', 'c.region', self::$SUBDOMAINS) )
				->limit(10000)
				->queryAll();
		}
		else {
			$arRes = Yii::app()->db->createCommand()
				->select('c.id_city id')
				->from('city c')
				->where('c.region=:region', array(':region' => self::$CITY_ID))
				->limit(10000)
				->queryAll();
		}

		$arCities = array();
		foreach ($arRes as $city)
			array_push($arCities, $city['id']);
		$strCities = implode(',', $arCities);

		return $strCities;
	}
	/*
	*		Выполняем редирект
	*/
	public static function setRedirect($idus, $id, $hasParams=false) {
		if($id!=self::$CITY_ID) {
			$url = 'Location: ' 
				. self::$SITES[$id]['url'] 
				. str_replace('#ID#', $idus, self::$REDIRECT);

			if($hasParams) {
				$arUrl = explode('?', $_SERVER['REQUEST_URI']);
				$url .= substr($arUrl[0], 1); 
				if(strlen($arUrl[1])>0) {
					$url .= DS . '?' . str_replace('&', ',', $arUrl[1]);
				}
			}
			header($url);
			exit();
		}
	}
	/*
	*		Получаем название SEO таблицы
	*/
	public static function getSeoTable()
	{
		return self::$SITES[self::$CITY_ID]['seo'];
	}
	/*
	*
	*/
	public static function getLabel()
	{
		return self::$SITES[self::$CITY_ID]['label'];
	}
	/*
	*
	*/
	public static function getUrl()
	{
		return self::$SITES[self::$CITY_ID]['url'];
	}
	/*
	*
	*/
	public static function getName()
	{
		return self::$SITES[self::$CITY_ID]['meta'];
	}
}

?>