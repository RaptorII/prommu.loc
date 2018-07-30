<?php

class Geolocation extends ARModel {

	/* Получение точек местонахождения соискателя */
	public function getGeoStatistics($idus, $id_vac)
	{

		$sql = "SELECT g.zone, g.lat, g.lon, g.date, g.id_point
            	FROM geo_statistics g
            	WHERE g.id_user = {$idus} AND g.id_vac = {$id_vac}";
        $res = Yii::app()->db->createCommand($sql)->queryAll();

        return $res;

	}

	/* Запись точек работы на вакансии соискателем */
	public function setGeoStatistics() 
	{

		$idus = Share::$UserProfile->exInfo->id;
        $id_vac = Yii::app()->getRequest()->getParam('id_vac');
        $zone =  Yii::app()->getRequest()->getParam('zone');
        $lat = Yii::app()->getRequest()->getParam('lat');
        $lon = Yii::app()->getRequest()->getParam('lon');
        $id_point = Yii::app()->getRequest()->getParam('id_point');


        $res = Yii::app()->db->createCommand()
                ->insert('geo_statistics', array('id_vac' => $id_vac,
                        'id_user' => $idus,
                        'zone' => $zone,
                        'lat' => $lat,
                        'lon' => $lon,
                        'date' => date("Y-m-d h-i-s"),
                        'id_point' => $id_point,
                    ));

	}

		/* Получение точек вакансии */
	public function getGeoPoints($id_vac)
	{

		$sql = "SELECT g.id, g.date_begin, g.date_end, g.time_begin, g.time_end,
					   g.lat, g.lon, g.radius, g.id_city, g.address
            	FROM geo_points g
            	WHERE g.id_vac = {$id_vac}";
        $res = Yii::app()->db->createCommand($sql)->queryAll();

        return $res;

	}
        
    
	/* Запись точек работы на вакансии */
	public function setGeoStatistics() 
	{

        $id_vac = Yii::app()->getRequest()->getParam('id_vac');
        $date_begin =  Yii::app()->getRequest()->getParam('date_begin');
        $date_end = Yii::app()->getRequest()->getParam('date_end');
        $time_begin = Yii::app()->getRequest()->getParam('time_begin');
        $time_end = Yii::app()->getRequest()->getParam('time_end');
        $lat = Yii::app()->getRequest()->getParam('lat');
        $lon = Yii::app()->getRequest()->getParam('lon');
        $radius = Yii::app()->getRequest()->getParam('radius');
        $id_city = Yii::app()->getRequest()->getParam('id_city');
        $address = Yii::app()->getRequest()->getParam('address');

        $res = Yii::app()->db->createCommand()
                ->insert('geo_points', array('id_vac' => $id_vac,
                        'date_begin' => $date_begin,
                        'date_end' => $date_end,
                        'time_begin' => $time_begin,
                        'time_end' => $time_end,
                        'lat' => $lat,
                        'lon' => $lon,
                        'radius' => $radius,
                        'id_city' => $id_city,
                        'address' => $address,
                    ));

	}
        
        
    public function createProject() 
	{

        $id_vac = Yii::app()->getRequest()->getParam('id_vac');
        $date_begin =  Yii::app()->getRequest()->getParam('date_begin');
        $date_end = Yii::app()->getRequest()->getParam('date_end');
        $time_begin = Yii::app()->getRequest()->getParam('time_begin');
        $time_end = Yii::app()->getRequest()->getParam('time_end');
        $id_city = Yii::app()->getRequest()->getParam('id_city');
        $address = Yii::app()->getRequest()->getParam('address');

        $res = Yii::app()->db->createCommand()
                ->insert('projects', array('id_vac' => $id_vac,
                        'date_begin' => $date_begin,
                        'date_end' => $date_end,
                        'time_begin' => $time_begin,
                        'time_end' => $time_end,
                        'id_city' => $id_city,
                        'address' => $address,
                    ));

	}

}



?>