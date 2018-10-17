<?php

class ProjectConvertVacancy
{
	/**
	 * @param $arr array - ['id' => vacancy ID ]
	 * @return $arr error or data
	 * Конвертировать вакансию в проект
	 */
	public function vacancyConvertToProject($arr) {
		$arErr = array('error' => true);
		$idus = Share::$UserProfile->id;
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

}