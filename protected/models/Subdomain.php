<?php

class Subdomain
{
	public static $MAIN_SITE = 'https://prommu.com';
	public static $MAIN_SEND_FILE_URL = '/ajax/AcceptFileFromSubdomain';
	public static $MAIN_DEL_FILE_URL = '/ajax/DelThroughSubdomain';
	public static $MAIN_SITE_ROOT = '/var/www/html'; // для загрузки файлов на домен
	public static $REDIRECT = '/api.auth_user/?id=#ID#&code=prommucomWd126wdn&url=';
	/*
	*		Получить ID текущего сайта
	*/
	public static function getId()
	{
		$str = 'https://' . $_SERVER['HTTP_HOST'];
		$res = Yii::app()->db->createCommand()
			->select('id')
			->from('subdomains')
			->where(array('like', 'url', $str))
			->queryRow();
		return $res['id'];
	}
	/*
	*		Получаем ID всех субдоменов (кроме домена)
	*/
	public static function getIdies()
	{
		$sql = Yii::app()->db->createCommand()
			->select('id')
			->from('subdomains')
			->where('id<>:id',array(':id'=>1307))
			->queryAll();

		$arRes = array();
		foreach ($sql as $d)
			$arRes[] = $d['id'];

		return $arRes;
	}
	/*
	*		Получаем всю инфу по сайтам
	*/
	public static function getData($without=false)
	{
		$arRes = array();

		if($without)
		{
			$str = 'https://' . $_SERVER['HTTP_HOST'];
			$sql = Yii::app()->db->createCommand()
				->select('*')
				->from('subdomains')
				->where(array('not like', 'url', $str))
				->queryAll();
		}
		else
		{
			$sql = Yii::app()->db->createCommand()
				->select('*')
				->from('subdomains')
				->queryAll();
		}
		
		foreach ($sql as $d)
			$arRes[$d['id']] = $d;

		return $arRes;
	}
	/*
	*		Определяем название для МЕТА
	*/
	public static function getSubdomain($arCities)
	{
		$arSub = self::getData();
		$arId = self::getIdies();
		$arCnt = array_fill_keys($arId, 0);
		foreach ($arCities as $c)
			if(in_array($c['region'], $arId))
				$arCnt[$c['region']]++;

		foreach ($arCnt as $id => $cnt)
			if($cnt==count($arCities))
				return array(
					'name' => $arSub[$id]['meta']
				);

		return array(
			'name' => $arSub[1307]['meta']
		);
	}
	/*
	*		Определяем город и при необходимости редиректимся на главной
	*		$typeUs => user type
	*		$idus => user ID
	*/
	public static function getCity($typeUs, $idus)
	{
		$arId = self::getIdies();
		$sId = self::getId();
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

			$arCnt = array_fill_keys($arId, 0);
			foreach ($arRes as $c)
				if(in_array($c['region'], $arId))
					$arCnt[$c['region']]++;

			if(!$arCnt[$sId]){ // Редиректим только если вообще нет городов субдомена
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
		$arId = self::getIdies();
		$sId = self::getId();
		$arRes = Yii::app()->db->createCommand()
			->select('uc.id_city, c.region')
			->from('user_city uc')
			->join('city c', 'uc.id_city=c.id_city')
			->where('id_user=:id_user', array(':id_user' => $idus))
			->queryAll();


		$arCnt = array_fill_keys($arId, 0);
		foreach ($arRes as $c)
			if(in_array($c['region'], $arId))
				$arCnt[$c['region']]++;

		if(!$arCnt[$sId]){ // Редиректим только если вообще нет городов субдомена
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
		$arId = self::getIdies();
		$sId = self::getId();
		$arRes = Yii::app()->db->createCommand()
			->select('t.name, t.region')
			->from('city t')
			->where('t.id_co=1') // only RF!!!
			->limit(10000);
		$arCities = $arRes->queryAll();

		foreach ($arCities as $c)
			if($city==$c['name'] && $c['region']!=$sId) {
				// редирект если выбраный город не относится к региону субдомена
				$site = in_array($c['region'], $arId) ? $c['region'] : 1307;
				self::setRedirect($idus, $site, true);
			}
	}
	/*
	*		Получаем ID городов региона субдомена в виде строки
	*/
	public static function getCitiesIdies($main = false)
	{
		$arId = self::getIdies();
		$sId = self::getId();
		$arRes = array();
		if($main) {
			$arRes = Yii::app()->db->createCommand()
				->select('c.id_city id')
				->from('city c')
				->where(array('in', 'c.region', $arId) )
				->limit(10000)
				->queryAll();
		}
		else {
			$arRes = Yii::app()->db->createCommand()
				->select('c.id_city id')
				->from('city c')
				->where('c.region=:region', array(':region' => $sId))
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
		$sId = self::getId();
		if($id!=$sId) {
			$arSub = self::getData();
			$url = 'Location: ' 
				. $arSub[$id]['url'] 
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
		$str = 'https://' . $_SERVER['HTTP_HOST'];
		$res = Yii::app()->db->createCommand()
			->select('seo')
			->from('subdomains')
			->where(array('like', 'url', $str))
			->queryRow();
		if(!isset($res['seo']))
			$res['seo'] = 'seo';

		return $res['seo'];
	}
	/*
	*
	*/
	public static function getLabel()
	{
		$str = 'https://' . $_SERVER['HTTP_HOST'];
		$res = Yii::app()->db->createCommand()
			->select('seo')
			->from('subdomains')
			->where(array('like', 'url', $str))
			->queryRow();
		return $res['label'];
	}
	/*
	*
	*/
	public static function getUrl()
	{
		$str = 'https://' . $_SERVER['HTTP_HOST'];
		$res = Yii::app()->db->createCommand()
			->select('seo')
			->from('subdomains')
			->where(array('like', 'url', $str))
			->queryRow();
		return $res['url'];
	}
	/*
	*
	*/
	public static function getName()
	{
		$str = 'https://' . $_SERVER['HTTP_HOST'];
		$res = Yii::app()->db->createCommand()
			->select('seo')
			->from('subdomains')
			->where(array('like', 'url', $str))
			->queryRow();
		return $res['meta'];
	}
	/*
	*		редиректим гостя
	*/
	public static function guestRedirect($type) {
		$sId = self::getId();
		if($sId!=1307 && $type!=2 && $type!=3) {
			$url = 'Location: ' . self::$MAIN_SITE . $_SERVER['REDIRECT_URL'];
			header($url);
			exit();
		}
	}
}

?>