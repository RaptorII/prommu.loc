<?php

class ProjectConvertVacancy
{
	/**
	 * @param $arr array - ['id' => vacancy ID ]
	 * @return $arr error or data
	 * Конвертировать вакансию в проект
	 */
	public function vacancyConvertToProject($arr, $idus) {
		$arErr = array('error' => true);
		$arRes = array(
				'error' => false,
				'vacancy' => $arr['id']
			);
		if(!$arr['id'])
			return $arErr;

		// достаем название вакансии
		$arV = Yii::app()->db->createCommand()
							->select("ev.title")
							->from('empl_vacations ev')
							->where(
									'ev.id=:id AND id_user=:idus',
									array(':id'=>$arr['id'],':idus'=>$idus)
							)
							->queryRow();
		if(empty($arV['title'])) {
			$arErr['vacancy-missing'] = true;
			return $arErr;
		}
		$arRes['name'] = $arV['title'];
		

		$arProject = Yii::app()->db->createCommand()
							->select("*")
							->from('project')
							->where('vacancy=:id',array(':id'=>$arr['id']))
							->queryRow();

		if(is_array($arProject)) {
			$arErr['already-created'] = true;
			return $arErr;
		}

		// достаем адреса
		$arC = Yii::app()->db->createCommand()
							->select("id, id_city")
							->from('empl_city')
							->where('id_vac=:id',array(':id'=>$arr['id']))
							->queryAll();

		$nC = sizeof($arC);
		if(!$nC)
			$arErr['empty-fields'][] = 'необходим хотя бы один город';
		for( $i = 0; $i < $nC; $i++ )
			$arRes['city'][$arC[$i]['id']] = $arC[$i]['id_city']; // city


		$arL = Yii::app()->db->createCommand()
							->select("id, id_city, id_metro, name, addr")
							->from('empl_locations')
							->where('id_vac=:id',array(':id'=>$arr['id']))
							->queryAll();

		$nL = sizeof($arL);
		if(!$nL) {
			$arErr['empty-fields'][] = '- минимум одна локация в каждом городе';
		}
		else {
			$arLC = array();
			$arLId = array();
			for( $i = 0; $i < $nL; $i++ ) {
				$c = $arRes['city'][$arL[$i]['id_city']];
				$l = $arL[$i]['id'];
				$arLC[$c] = $c;
				$arLId[] = $l;
				if($arL[$i]['id_metro']>0)
					$arRes['metro'][$c][$l] = $arL[$i]['id_metro']; // metro
				$arRes['lindex'][$c][$l] = $arL[$i]['addr']; 		// lindex
				$arRes['lname'][$c][$l] = $arL[$i]['name']; 		// lname
			}
			if($nC > sizeof($arLC))
				$arErr['empty-fields'][] = '- минимум одна локация в каждом городе';

			$arP = Yii::app()->db->createCommand()
								->select("id_loc, btime, etime,
										DATE_FORMAT(bdate, '%Y-%m-%d') bdate,
										DATE_FORMAT(edate, '%Y-%m-%d') edate")
								->from('emplv_loc_times')
								->where(array('in','id_loc',$arLId))
								->queryAll();

			$nP = sizeof($arP);
			if(!$nP) {
				$arErr['empty-fields'][] = '- минимум один временной период в каждой локации';
			}
			else {
				for( $i = 0; $i < $nP; $i++ ) {
					for( $j = 0; $j < $nL; $j++ ) {
						if($arP[$i]['id_loc'] == $arL[$j]['id']) {
							$c = $arRes['city'][$arL[$j]['id_city']];
							$l = $arL[$j]['id'];
							$arRes['bdate'][$c][$l][$i] = $arP[$i]['bdate']; 	// bdate
							$arRes['edate'][$c][$l][$i] = $arP[$i]['edate']; 	// edate
							$h = floor($arP[$i]['btime'] / 60);
							$m = $arP[$i]['btime'] - $h * 60;
							$arRes['btime'][$c][$l][$i] = sprintf('%02d:%02d', $h, $m); // btime
							$h = floor($arP[$i]['etime'] / 60);
							$m = $arP[$i]['etime'] - $h * 60;
							$arRes['etime'][$c][$l][$i] = sprintf('%02d:%02d', $h, $m); // etime
						}
					}
				}
			}
		}
		// достаем подтвержденных юзеров
		$arU = Yii::app()->db->createCommand()
							->select("r.id_user")
							->from('vacation_stat vs')
							->leftjoin('resume r','r.id=vs.id_promo')
							->where('vs.id_vac=:id AND vs.status=5',array(':id'=>$arr['id']))
							->queryAll();

		$arRes['users-cnt'] = sizeof($arU); 	// users-cnt
		$arRes['users-activate'] = true;			// users-activate
		if(!$arRes['users-cnt']) {
			$arErr['empty-fields'][] = '- минимум один подтвержденный соискатель';
		}
		else {
			for( $i = 0; $i < $arRes['users-cnt']; $i++ ) {
				$arRes['users'] .= $arU[$i]['id_user']; 	// users
				( $i + 1 ) < $arRes['users-cnt'] && $arRes['users'] .= ',';
			}
		}

		if(sizeof($arErr['empty-fields']))
			return $arErr;

		return $arRes;
	}
	/**
	 * @param $arr array - ['id' => vacancy ID ]
	 * @return $arr error or data
	 * Конвертировать вакансию в проект
	 */
	public function projectConvertToVacancy($arr, $idus) {
		$arErr = array('error' => true);
		$arRes = array('error' => false);
		$nI = sizeof($arr['index']);
		$nS = sizeof($arr['staff']);
		// делаем последние проверки
		if(empty($arr['project']['id']) || $arr['project']['id_user']!=$idus)
			return array_merge($arErr, ['project-missing' => true]);
		if(!$nI)
			$arErr['empty-fields'][] = '- добавление минимум одного соискателя';
		if(!sizeof($arr['staff']))
			$arErr['empty-fields'][] = '- добавление минимум одного ТТ с периодом';
		if(!empty($arr['project']['vacancy']) || sizeof($arErr['empty-fields']))
			return $arErr;

		// готовим данные
		$date = date("Y-m-d H:i:s");
		$arU = Yii::app()->db->createCommand()
							->select("id")
							->from('employer')
							->where('id_user=:id', array(':id'=>$idus))
							->queryRow();

		$remdate = strtotime($arr['index'][0]['edate']);
		$arC = array();
		for ($i = 0; $i < $nI; $i++) {
			$bdate = strtotime($arr['index'][$i]['bdate']);
			$edate = strtotime($arr['index'][$i]['edate']);
			$arC[$arr['index'][$i]['id_city']]['id_city'] = $arr['index'][$i]['id_city'];

			if(
				!isset($arC[$arr['index'][$i]['id_city']]['bdate'])
				||
				$arC[$arr['index'][$i]['id_city']]['bdate'] > $bdate
			) {
				$arC[$arr['index'][$i]['id_city']]['bdate'] = $bdate;
			}

			if(
				!isset($arC[$arr['index'][$i]['id_city']]['edate'])
				||
				$arC[$arr['index'][$i]['id_city']]['edate'] < $edate
			){
				$arC[$arr['index'][$i]['id_city']]['edate'] = $edate;
			}

			if($edate>$remdate)
				$remdate = $edate;
		}
		// создаем вакансию
		$arVac = array(
				'id_user' => $idus,
				'id_empl' => $arU['id'],
				'title' => $arr['project']['name'],
				'requirements' => $arr['project']['name'],
				'remdate' => date('Y-m-d',$remdate),
				'isman' => 1,
				'iswoman' => 1,
				'agefrom' => 14,
				'ageto' => 0,
				'status' => 0,
				'ismoder' => 0,
				'crdate' => $date,
				'mdate' => $date,
				'bdate' => '',
				'edate' => ''
			);
		Yii::app()->db->createCommand()->insert('empl_vacations', $arVac);

		$idvac = Yii::app()->db->createCommand('SELECT LAST_INSERT_ID()')->queryScalar();
		// записываем адресную программу
		foreach ($arC as $k => $c) {
			Yii::app()->db->createCommand()
					->insert('empl_city', array(
							'id_vac' => $idvac,
							'id_city' => $c['id_city'],
							'bdate' => date("Y-m-d 00:00:00",$c['bdate']),
							'edate' => date("Y-m-d 00:00:00",$c['edate']),
						));
		}
		$arC = Yii::app()->db->createCommand()
							->select("id, id_city")
							->from('empl_city')
							->where('id_vac=:id', array(':id'=>$idvac))
							->queryAll();

		$arLId = array();
		for ($i = 0; $i < $nI; $i++) {
			$city;
			foreach ($arC as $c)
				if($arr['index'][$i]['id_city']==$c['id_city'])
					$city = $c['id'];
			
			Yii::app()->db->createCommand()
					->insert('empl_locations', 
						array('id_vac' => $idvac,
							'id_city' => $city,
							'npp' => 1,
							'name' => $arr['index'][$i]['name'],
							'addr' => $arr['index'][$i]['adres'],
						));
			$idloc = Yii::app()->db->createCommand('SELECT LAST_INSERT_ID()')->queryScalar();	

			$npp = 1;
			$arP = array();
			for ($j = 0; $j < $nI; $j++) {
				if(
					in_array($arr['index'][$j]['location'], $arLId)
					||
					($arr['index'][$i]['location'] != $arr['index'][$j]['location'])
				)
					continue;
				
				$arTb = explode(':', $arr['index'][$j]['btime']);
				$arTe = explode(':', $arr['index'][$j]['etime']);

				$arP[] = array(
							'id_loc' => $idloc, 
							'npp' => $npp, 
							'bdate' => date("Y-m-d", strtotime($arr['index'][$j]['bdate'])), 
							'edate' => date("Y-m-d", strtotime($arr['index'][$j]['edate'])), 
							'btime' => $arTb[0] * 60 + $arTb[1], 
							'etime' => $arTe[0] * 60 + $arTe[1]
						);
				$npp++;
			}
			$arLId[] = $arr['index'][$i]['location'];

			if(sizeof($arP)) {
				Yii::app()->db->schema->commandBuilder
					->createMultipleInsertCommand('emplv_loc_times', $arP)
					->execute();			
			}
		}
		// для будущих должностей
		// Тестовая запись !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
		$insData[] = array(
			'id_vac' => $idvac, 
			'id_attr' => 135, 
			'key' => 135,
			'crdate' => $date
		);
		Yii::app()->db->schema->commandBuilder
			->createMultipleInsertCommand('empl_attribs', $insData)
			->execute();
		// записываем персонал
		$arS = array();
		$arSIdus = array();
		for ($i=0; $i < $nS; $i++) {
			$id = $arr['staff'][$i]['user'];
			$arS[$id] = array(
					'id_vac' => $idvac,
					'isresponse' => 2, // приглашение работодателя
					'date' => $date,
					'id_jobs' => 0,
					'isend' => 0,
					'service' => 0
				);
			if($arr['staff'][$i]['status']==1)
				$arS[$id]['status'] = 5;
			elseif($arr['staff'][$i]['status']==0)
				$arS[$id]['status'] = 4;
			else
				$arS[$id]['status'] = 3;

			$arSIdus[] = $arr['staff'][$i]['user'];
		}

		$res = Yii::app()->db->createCommand()
							->select("r.id, r.id_user")
							->from('resume r')
							->where(array('in','r.id_user',$arSIdus))
							->queryAll();
		
		$arSId = array();
		foreach ($res as $u)
			$arSId[$u['id_user']] = $u['id'];

		foreach ($arS as $id_user => $s) {
			$arS[$id_user]['id_promo'] = $arSId[$id_user];
			Yii::app()->db->createCommand()
				->insert('vacation_stat', $arS[$id_user]);
		}
		// обновление проекта
		Yii::app()->db->createCommand()
				->update(
						'project', 
						array('vacancy' => $idvac), 
						'id=:id', 
						array(':id' => $arr['project']['id'])
					);

		$arRes['link'] = MainConfig::$PAGE_VACANCY . '/' . $idvac;

		return $arRes;
	}
}