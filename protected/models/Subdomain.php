<?php

class Subdomain
{
	public static $MAIN_SEND_FILE_URL = '/ajax/AcceptFileFromSubdomain'; // пока не используется
	public static $MAIN_DEL_FILE_URL = '/ajax/DelThroughSubdomain'; // пока не используется
	public static $REDIRECT = '/api.auth_user/?id=#ID#&code=prommucomWd126wdn&url=';
	/**
	 * 	Определяем что за сайт
	 */
	public static function site()
	{
		return Yii::app()->request->hostInfo;
	}
	/**
	 *	получить имя сайта по урлу (без https://)
	 * 	@return string
	 */
	public static function getSiteName()
	{
		$site = self::site();
		$pos = strripos(self::site(), '://') + 3;
		$site = substr($site, $pos);
		return $site;
	}
	/**
	 * 	является ли текущий сайт доменом
	 * 	@return bool
	 */
	public static function isDomain()
	{
		return self::getSiteName() == self::domain()->url;
	}
	/**
	 * 	путь к корню домена
	 * 	@return string
	 */
	public static function domainRoot()
	{
		return self::domain()->root;
	}
	/**
	 * 	урл текущего сайта
	 * 	@return string
	 */
	public static function domainSite()
	{
		return 'https://' . self::domain()->url;
	}
	/**
	 * 	закешированые данные о урле
	 * 	@return object
	 */
	public static function domain()
	{
		$id = self::getSiteName() . '/Subdomain/Domain';
		$arRes = Cache::getData($id);
		if($arRes['data']===false)
		{
			$sql = Yii::app()->db->createCommand()
								->select("*")
								->from('subdomains')
								->where('is_domain=1')
								->queryRow();

			$arRes['data'] = (object) $sql;

			Cache::setData($arRes, 604800); // кеш на неделю
		}
		return $arRes['data'];
	}
	/*
	*		Получить ID текущего сайта
	*/
	public static function getId()
	{
		$res = Yii::app()->db->createCommand()
			->select('id')
			->from('subdomains')
			->where(['like', 'url', self::getSiteName()])
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
			->where('id<>:id',[':id'=>1307])
			->queryAll();

		$arRes = array();
		foreach ($sql as $d)
			$arRes[] = $d['id'];

		return $arRes;
	}
	/*
	*		Получаем всю инфу по сайтам
	*/
	public static function getData()
	{
		$arRes = array();
		$query = Yii::app()->db->createCommand()->select('*')->from('subdomains');

		if(self::isDomain()) // для домена
		{
			$query->where(['not like', 'url', self::getSiteName()]);
		}

		$query->order('sort asc, id asc');
		$result = $query->queryAll();

		foreach ($result as $d)
		{
			$arRes[$d['id']] = $d;
			$arRes[$d['id']]['url'] = 'https://' . $d['url'];
		}

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
				return ['name' => $arSub[$id]['meta']];

		return ['name' => self::domain()->meta];
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
				->where('uc.id_user=:idus', [':idus' => $idus])
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
			->where('id_user=:id_user', [':id_user' => $idus])
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
	public static function popupRedirect($city, $idus)
	{
		$city = urldecode($city);
		$city = urldecode($city);
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
	*		редирект страниц с фильтром при переходе на страницу
	*/
	public static function filterRedirect($arC, $idus, $type)
	{
		if(sizeof($arC)) {
			$arId = self::getIdies();
			$arId[] = 1307;
			$arCnt = array_fill_keys($arId, 0);
			$arRes = Yii::app()->db->createCommand()
				->select('c.region')
				->from('city c')
				->where(['in', 'c.id_city', $arC])
				->limit(10000);
			$arCities = $arRes->queryAll();

			if(!sizeof($arCities))
				return false;

			foreach ($arCities as $c)
				if(in_array($c['region'], $arId))
					$arCnt[$c['region']]++;

			$sId = self::getId();
			foreach ($arCnt as $id => $cnt) 
				if($cnt>0 && $cnt==sizeof($arC) && $id!=$sId) {
					if(in_array($type, [2,3]))
						self::setRedirect($idus, $id, true);
					else {
						$arData = self::getData();
						$url = 'Location: ' . $arData[$id]['url'] . $_SERVER['REQUEST_URI'];
						header($url);
						exit();
					}	
				}
		}
	}
	/*
	*		Получаем ID городов региона субдомена в виде строки
	*/
	public static function getCitiesIdies($main = false, $type = 'str') // упразднен, все делается в методе кеша
	{
		$arId = self::getIdies();
		$sId = self::getId();
		$arRes = array();
		if($main) {
			$arRes = Yii::app()->db->createCommand()
				->select('c.id_city id')
				->from('city c')
				->where(['in', 'c.region', $arId])
				->limit(10000)
				->queryAll();
		}
		else {
			$arRes = Yii::app()->db->createCommand()
				->select('c.id_city id')
				->from('city c')
				->where('c.region=:region', [':region' => $sId])
				->limit(10000)
				->queryAll();
		}

		$arCities = array();
		foreach ($arRes as $city)
			array_push($arCities, $city['id']);
		if($type=='str') {
			$strCities = implode(',', $arCities);
			return $strCities;
		}
		else {
			return $arCities;
		}
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
	*		редиректим гостя
	*/
	public static function guestRedirect($type) {
		$sId = self::getId();
		if($sId!=1307 && $type!=2 && $type!=3) {
			$url = 'Location: https://' . self::domain()->url . $_SERVER['REQUEST_URI'];
			header($url);
			exit();
		}
	}
	/*
	*		создание урла для редиректа на страницах с фильтром
	*/
	public static function ajaxFilterRedirect($arC, $idus, $type)
	{
		$url = '';
		if(sizeof($arC)) {
			$arId = self::getIdies();
			$arId[] = 1307;
			$arCnt = array_fill_keys($arId, 0);
			$arRes = Yii::app()->db->createCommand()
				->select('c.region')
				->from('city c')
				->where(['in', 'c.id_city', $arC])
				->limit(10000);
			$arCities = $arRes->queryAll();

			if(!sizeof($arCities))
				return false;

			foreach ($arCities as $c)
				if(in_array($c['region'], $arId))
					$arCnt[$c['region']]++;

			$arData = self::getData();
			$sId = self::getId();
			foreach ($arCnt as $id => $cnt) 
				if($cnt>0 && $cnt==sizeof($arC) && $id!=$sId) {
					if(in_array($type, [2,3])) {
						$url = $arData[$id]['url'] . str_replace('#ID#', $idus, self::$REDIRECT);
						$arUrl = explode('?', $_SERVER['REQUEST_URI']);
						$url .= substr($arUrl[0], 1); 
						if(strlen($arUrl[1])>0)
							$url .= DS . '?' . str_replace('&', ',', $arUrl[1]);
					}
					else {
						$url = $arData[$id]['url'] . $_SERVER['REQUEST_URI'];	
					}	
				}
		}
		if(strlen($url))
			$url = urldecode($url);

		return $url;
	}
	/*
	*		Получаем кешируемые данные
	*/
	public static function getCacheData()
	{
		$id = self::getSiteName() . '/Subdomain/All';
		$arRes = Cache::getData($id);
		if($arRes['data']===false)
		{
			$sql = Yii::app()->db->createCommand()
								->select("*")
								->from('subdomains')
								->where(['like', 'url', self::getSiteName()])
								->queryRow();

			empty($sql['seo']) && $sql['seo'] = 'seo';

			$arRes['data'] = (object) array(
					'host' => self::getSiteName(),
					'id' => $sql['id'],
					'seo' => $sql['seo'],
					'label' => $sql['label'],
					'url' => 'https://' . $sql['url'],
					'name' => $sql['meta'],
					'idies' => self::getIdies(),
					'data' => self::getData(),
					'arCitiesIdes' => array(),
					'strCitiesIdes' => '',
          'data_list' => ['0' => 'Все'],
          'domain' => self::domain()
				);

      $arRes['data']->data_list[$arRes['data']->domain->id] = $arRes['data']->domain->meta;
      foreach ($arRes['data']->data as $id => $v)
      {
        $arRes['data']->data_list[$id] = $v['meta'];
      }

			$sql = Yii::app()->db->createCommand()
									->select('c.id_city id')
									->from('city c')
									->where('c.region=:region', [':region' => $arRes['data']->id])
									->limit(10000)
									->queryAll();

			foreach ($sql as $city)
				array_push($arRes['data']->arCitiesIdes, $city['id']);

			$arRes['data']->strCitiesIdes = implode(',', $arRes['data']->arCitiesIdes);

			Cache::setData($arRes, 604800); // кеш на неделю
		}
		return $arRes['data'];
	}
}
?>