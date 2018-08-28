<?php
	
	class Termostat{
		///Соискатель
		public function getPromoView($user,$arDates){

			$sql = "SELECT COUNT(*)
	            FROM termostat_analytic t
	            WHERE t.user = {$user} AND t.date between '{$arDates['bdate']}' AND '{$arDates['edate']}'
	            ";
	        	$res = Yii::app()->db->createCommand($sql)->queryScalar();

	        	if($res == ''){
	        		return '0';
	        	}
	     		else return $res;
		}
		
		public function setUserDataTime($user, $arDates, $analytic){

			$day = 0;
			$time = '0000-00-00 00:00:00';
			if(isset($analytic)){
				$day = $arDates['day'];
				$arTime = explode(':', $arDates['time']);
				for($i=0; $n=sizeof($arTime), $i<$n; $i++)
					$arTime[$i] = str_pad($arTime[$i], 2, "0", STR_PAD_LEFT);

				$time = '0000-00-00 ' . implode(':', $arTime) . ':00';
			}

			$res = Yii::app()->db->createCommand()
				->update(
					'user', 
					array('analytday' => $day, 'analyttime' => $time),
					'id_user=:id_us', 
					array(':id_us' => $user)
				);

			return $res;
		}

		public function getPromoResponse($user, $response, $status, $arDates){

			$sql = "SELECT COUNT(*)
            FROM resume r
            INNER JOIN vacation_stat s ON s.id_promo = r.id 
            WHERE r.id_user = {$user} AND s.date between '{$arDates['bdate']}' AND '{$arDates['edate']}' AND s.isresponse IN($response) AND s.status IN($status)";
        /** @var $res CDbCommand */
        $res = Yii::app()->db->createCommand($sql);
        $res = $res->queryScalar();

	        	if($res == ''){
	        		return '0';
	        	}
	     		else return $res;
		}

		///Работодатель

		public function getTermostatServicesCount($user){

			$sql = "SELECT COUNT(*)
	            FROM service_cloud s
	            WHERE s.id_user = {$user} AND s.date between  '12.12.2017' AND  '31.12.2019'AND s.status = 1
	            ";
	        	$res = Yii::app()->db->createCommand($sql)->queryScalar();

	        	if($res == ''){
	        		return '0';
	        	}
	     		else return $res;
		}

		public function getTermostatServices($user, $arDates){

			$t1 = strtotime($arDates['bdate']);
			$t2 = strtotime($arDates['edate']) + 60*60*24;
			$bdate = date("Y",$t1) . '-' . date("m",$t1) . '-' . date("d",$t1);
			$edate = date("Y",$t2) . '-' . date("m",$t2) . '-' . date("d",$t2);

			$sql = "SELECT s.type, s.name, s.sum
	            FROM service_cloud s
	            WHERE s.id_user = {$user} AND s.status = 1 AND s.date between TIMESTAMP('{$bdate}') AND TIMESTAMP('{$edate}') 
	            ";
	        $res = Yii::app()->db->createCommand($sql)->queryAll();

			$sql = "SELECT s.type
	            FROM outstaffing s
	            WHERE s.id = {$user}
	            ";
	        $ress = Yii::app()->db->createCommand($sql)->queryAll();	        

	        $res[0] = $res;
	        $res[1] = $ress;

	        return $res;
		}

		public function getTermostatCount($idvac){

			$sql = "SELECT COUNT(*)
	            FROM termostat_analytic t
	            WHERE t.id = {$idvac} AND t.date between  '12.12.2017' AND  '26.12.2017'
	            ";
	        	$res = Yii::app()->db->createCommand($sql)->queryScalar();

	
	        	if($res == ''){
	        		return '0';
	        	}
	     		else return $res;

		}
    
        
        public function getTermostatEmplUserCount($user, $arDates){

			$t1 = strtotime($arDates['bdate']);
			$t2 = strtotime($arDates['edate']);
			$day = 60 * 60 * 24;
			$days = ($t2 - $t1) / $day;
			$curDay = $t1;

			for($c=0; $c<=$days; $c++){ 
				$date = date("Y",$curDay).'.'.date("m",$curDay).'.'.(date("d",$curDay));

				$sql = "SELECT COUNT(*)
					FROM termostat_analytic t
					WHERE t.user = $user AND date(t.date) = '$date'
					ORDER BY t.date ASC";
				$res = Yii::app()->db->createCommand($sql)->queryScalar();

				$arGraph[$c] = array(date('d.m.y',$curDay), (int)$res);
				$curDay +=$day;
			}

			return $arGraph;
		}
		
		public function getTermostatEmplCount($user, $arDates){

			$t1 = strtotime($arDates['bdate']);
			$t2 = strtotime($arDates['edate']);
			$day = 60 * 60 * 24;
			$days = ($t2 - $t1) / $day;
			$curDay = $t1;

			for($c=0; $c<=$days; $c++){ 
				$date = date("Y",$curDay).'.'.date("m",$curDay).'.'.(date("d",$curDay));

				$sql = "SELECT COUNT(*)
					FROM termostat_analytic t
					WHERE t.id = $user AND date(t.date) = '$date'
					ORDER BY t.date ASC";
				$res = Yii::app()->db->createCommand($sql)->queryScalar();

				$arGraph[$c] = array(date('d.m.y',$curDay), (int)$res);
				$curDay +=$day;
			}

			return $arGraph;
		}

		public function getTermostatEmplCounts($user, $arDates){
			$t1 = strtotime($arDates['bdate']);
			$t2 = strtotime($arDates['edate']);
			$day = 60 * 60 * 24;
			$days = ($t2 - $t1) / $day;
			$curDay = $t1;
			$count = 0;

			for($c=0; $c<=$days; $c++){ 
				$date = date("Y",$curDay).'.'.date("m",$curDay).'.'.(date("d",$curDay));

				$sql = "SELECT COUNT(*)
					FROM termostat_analytic t
					WHERE t.id = $user AND date(t.date) = '$date'
					ORDER BY t.date ASC";
				$res = Yii::app()->db->createCommand($sql)->queryScalar();
				$count += (int)$res;
				$curDay +=$day;
			}

			return $count;
		}
		


		public function setTermostat($idvac, $user, $type){
			if($type == "vacancy"){
				 $vacancy = Yii::app()->db->createCommand()
                ->select("ismoder")
                ->from('empl_vacations')
                ->where('id=:id', array(':id' => $idvac))
                ->queryRow();
           		if($vacancy['ismoder'] == 100)
           		{
           			 $flag = 1;
           		} else $flag = 0;
			} else $flag = 1;
			if($user != 0 && $flag){
				$sql = "SELECT t.id
	            FROM termostat_analytic t
	            WHERE t.user = {$user} AND t.date between  '12.04.2018' AND  '26.12.2018' AND t.id = {$idvac}
	            ";
	        	$res = Yii::app()->db->createCommand($sql)->queryAll();

	        	if(!$res){
	        		$res = Yii::app()->db->createCommand()
                ->insert('termostat_analytic', array(
                        'id'   =>   $idvac,
                        'user' =>   $user,
                        'type' =>   $type,
                        'date' =>   date("Y-m-d h-i"),
                    ));

	        	}
        	}
        	elseif($flag){
        		$res = Yii::app()->db->createCommand()
                ->insert('termostat_analytic', array(
                        'id'   =>   $idvac,
                        'user' =>   $user,
                        'type' =>   $type,
                        'date' =>   date("Y-m-d h-i"),
                    ));
        	}

		}


		public function getDates(){
			if(Yii::app()->getRequest()->isPostRequest){
				$arDates = array(
					'bdate' => Yii::app()->getRequest()->getParam('pa-bdate'),
					'edate' => Yii::app()->getRequest()->getParam('pa-edate')
				);
			}
			else{
				$arDates = array(
					'bdate' => date('d.m.Y', mktime(0,0,0,date("m"),1,date("Y"))),// если не установлено, то начало текущего месяца
					'edate' => date('d.m.Y')
				);
			}
			return $arDates;
		}

	}	


?>