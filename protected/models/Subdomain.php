<?php

class Subdomain
{
	public static function getSubdomain($arCities)
	{
		$cnt = 0;
		foreach ($arCities as $city)
			if($city['region']==MainConfig::$SUBDOMAIN_CITY_ID_SPB)
				$cnt++;

		if($cnt==count($arCities)){
			return array(
				'name' => 'Spb.prommu.com'
			);
		}
		else{
			return array(
				'name' => 'Prommu.com'
			);
		}
	}
}

?>