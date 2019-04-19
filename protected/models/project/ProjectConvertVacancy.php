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
		$arRes = array(
				'error' => false,
				'vacancy' => $arr['id']
			);
		$idus = Share::$UserProfile->id;
		if(!$arr['id'] || !$idus)
			return $arErr;

		// достаем название вакансии
		$arRes['name'] = $this->getVacancyTitle($arr['id'], $idus);

		if(empty($arRes['name']))
			return array_merge($arErr, ['vacancy-missing' => true]);

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
		$arU = $this->getVacancyUsers($arr['id']);
		if(!$arU)
			$arErr['empty-fields'][] = '- минимум один подтвержденный соискатель';
		else
			$arRes = array_merge($arRes, $arU);
		// достаем должности
		$arPosts = Yii::app()->db->createCommand()
									->select('ea.id_attr id')
									->from('empl_attribs ea')
									->leftjoin('user_attr_dict uad','uad.id=ea.id_attr')
									->where(
										'ea.id_vac=:id AND uad.id_par=110', 
										array(':id' => $arr['id']))
									->queryAll();

		if(sizeof($arRes['bdate'])) {
			$post = reset($arPosts)['id'];
			$nPost = sizeof($arPosts) - 1;
			for( $i = 0; $i < $nP; $i++ ) {
				for( $j = 0; $j < $nL; $j++ ) {
					if($arP[$i]['id_loc'] == $arL[$j]['id']) {
						$c = $arRes['city'][$arL[$j]['id_city']];
						$l = $arL[$j]['id'];
						$arRes['post'][$c][$l][$i] = $post;
						$post = (key($arPosts)==$nPost ? reset($arPosts)['id'] : next($arPosts)['id']);
					}
				}
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
	public function projectConvertToVacancy($arr) {
		$arErr = array('error' => true);
		$arRes = array('error' => false);
		$nI = sizeof($arr['index']);
		$nS = sizeof($arr['staff']);
		// делаем последние проверки
		$idus = Share::$UserProfile->id;
		if(empty($arr['project']['id']) || $arr['project']['id_user']!=$idus || !$idus)
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
			$id_city = $arr['index'][$i]['id_city'];
			$bdate = strtotime($arr['index'][$i]['bdate']);
			$edate = strtotime($arr['index'][$i]['edate']);
			$arC[$id_city]['id_city'] = $id_city;

			if(!isset($arC[$id_city]['bdate']) || $arC[$id_city]['bdate']>$bdate)
				$arC[$id_city]['bdate'] = $bdate;

			if(!isset($arC[$id_city]['edate']) || $arC[$id_city]['edate']<$edate)
				$arC[$id_city]['edate'] = $edate;

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
		$arP = array();
		for ($i = 0; $i < $nI; $i++) {
			if(in_array($arr['index'][$i]['location'], $arLId))
				continue;

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
			
			for ($j = 0; $j < $nI; $j++) {
				if($arr['index'][$i]['location'] != $arr['index'][$j]['location'])
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
		}

		if(sizeof($arP)) {
			Yii::app()->db->schema->commandBuilder
				->createMultipleInsertCommand('emplv_loc_times', $arP)
				->execute();
		}

		// записываем должности
		$insData = array();
		for ($i = 0; $i < $nI; $i++)
			$insData[] = array(
				'id_vac' => $idvac, 
				'id_attr' => $arr['index'][$i]['post'], 
				'key' => $arr['index'][$i]['post'],
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
	/**
	 * @param $id number - project ID
	 * @param $type string - 'vacancy' | 'project' | 'vacancy-delete'
	 * @return bool - true = success
	 * Синхронизация проекта и вакансии
	 */
	public function synphronization($id, $type, $id_user=false)
	{
		$idus = $id_user ?: Share::$UserProfile->id;

		if(!$id || !in_array($type,['vacancy','project','vacancy-delete']) || !$idus)
			return false;

		$where = (in_array($type,['vacancy','vacancy-delete']) ? 'vacancy=' : 'project=')
							. $id . ' AND id_user=' . $idus;

		$project = Yii::app()->db->createCommand()
						->select("*")
						->from('project')
						->where($where)
						->queryRow();
		//
		// синхронизация по вакансии
		if($type=='vacancy') {
			if(!$project['id'])
				return false;
			// достаем название вакансии
			$vacTitle = $this->getVacancyTitle($id, $idus);

			if(empty($vacTitle))
				return false;

			if($project['name']!=$vacTitle) {
				Yii::app()->db->createCommand()
					->update('project', ['name' => $vacTitle], 'id=' . $project['id']);
			}
			// достаем подтвержденных юзеров
			$arUsers = $this->getVacancyUsers($id);
			if(is_array($arUsers)) {
				$model = new Project();
				$model->recordStaff($arUsers, $project['project']);
			}
			return $project['project'];
		}
		//
		// синхронизация по проекту
		if($type=='project') {
			if(!$project['vacancy'])
				return false;

			$model = new Project();
			$arStaff = $model->getAllStaffProject($project['project']);
			$arIindex = $model->getIndex($project['project']);
			$nI = sizeof($arIindex);
			$nS = sizeof($arStaff);
			$date = date("Y-m-d H:i:s");
			$remdate = strtotime($arIindex[0]['edate']);

			for ($i = 0; $i < $nI; $i++) {
				$edate = strtotime($arIindex[$i]['edate']);
				($edate > $remdate) && ($remdate = $edate);
			}
			// записываем данные вакансии
			Yii::app()->db->createCommand()
				->update('empl_vacations', 
					array(
						//'title' => $project['project']['name'],
						'remdate' => date('Y-m-d', $remdate),
						'ismoder' => 0,
						'mdate' => $date						
					), 
					'id=:id',
					array(':id' => $project['vacancy'])
				);
			// записываем должности
			$insData = array();
			for ($i = 0; $i < $nI; $i++)
				$insData[] = array(
					'id_vac' => $project['vacancy'], 
					'id_attr' => $arIindex[$i]['post'], 
					'key' => $arIindex[$i]['post']
				);
			$sql = "DELETE empl_attribs 
							FROM empl_attribs 
							INNER JOIN user_attr_dict d ON d.id = 110
							INNER JOIN user_attr_dict d1 
								ON empl_attribs.id_attr=d1.id AND d1.id_par=d.id
							WHERE id_vac = ".$project['vacancy'];
			Yii::app()->db->createCommand($sql)->execute();

			Yii::app()->db->schema->commandBuilder
				->createMultipleInsertCommand('empl_attribs', $insData)
				->execute();
			// записываем персонал
			$sql = Yii::app()->db->createCommand()
								->select("vs.id_promo, r.id_user")
								->from('vacation_stat vs')
								->leftjoin('resume r','r.id=vs.id_promo')
								->where('vs.id_vac=' . $project['vacancy'])
								->queryAll();

			$arSId = array();
			$arSIduser = array();
			foreach ($sql as $u) {
				$arSId[] = $u['id_promo'];
				$arSIduser[] = $u['id_user'];
			}

			$arS = array();
			$arSPIdus = array();
			for ($i=0; $i < $nS; $i++) {
				$id_user = $arStaff[$i]['user'];
				if( in_array($id_user, $arSIduser) )
					continue;

				$arS[$id_user] = array(
						'id_vac' => $project['vacancy'],
						'isresponse' => 2, // приглашение работодателя
						'date' => $date,
						'id_jobs' => 0,
						'isend' => 0,
						'service' => 0
					);
				if($arStaff[$i]['status']==1)
					$arS[$id_user]['status'] = 5;
				elseif($arStaff[$i]['status']==0)
					$arS[$id_user]['status'] = 4;
				else
					$arS[$id_user]['status'] = 3;

				$arSPIdus[] = $id_user;
			}
			$res = Yii::app()->db->createCommand()
								->select("r.id, r.id_user")
								->from('resume r')
								->where(array('in','r.id_user',$arSPIdus))
								->queryAll();
			
			$arSId = array();
			foreach ($res as $u)
				$arSId[$u['id_user']] = $u['id'];

			if(sizeof($arS)) {
				foreach ($arS as $id_user => $s) {
					$arS[$id_user]['id_promo'] = $arSId[$id_user];
					Yii::app()->db->createCommand()
						->insert('vacation_stat', $arS[$id_user]);
				}
			}
			return $project['vacancy'];
		}
		//
		// отвязывание от удаляемой вакансии
		if($type=='vacancy-delete') {
			if(!$project['id'])
				return false;

			Yii::app()->db->createCommand()
				->update('project', ['vacancy'=>NULL], 'id=' . $project['id']);
			return true;
		}
	}
	/**
	 * @param $vacancy number - vacancy ID
	 * @return array
	 * достаем подтвержденных юзеров
	 */
	private function getVacancyUsers($vacancy) {
		$sql = Yii::app()->db->createCommand()
							->select("r.id_user")
							->from('vacation_stat vs')
							->leftjoin('resume r','r.id=vs.id_promo')
							->where(
								'vs.id_vac=:id AND vs.status=5',
								array(':id'=>$vacancy)
							)->queryAll();

		$arRes['users-cnt'] = sizeof($sql); 	// users-cnt
		$arRes['users-activate'] = true;			// users-activate

		if(!$arRes['users-cnt']) {
			return false;
		}

		for( $i = 0; $i < $arRes['users-cnt']; $i++ ) {
			$arRes['users'] .= $sql[$i]['id_user']; 	// users
			( $i + 1 ) < $arRes['users-cnt'] && $arRes['users'] .= ',';
		}

		return $arRes;
	}
	/**
	 * @param $vacancy number - vacancy ID
	 * @param $idus number - employer`s id_user
	 * @return string
	 * достаем подтвержденных юзеров
	 */
	private function getVacancyTitle($vacancy, $idus) {
		$sql = Yii::app()->db->createCommand()
							->select("ev.title")
							->from('empl_vacations ev')
							->where(
									'ev.id=:id AND id_user=:idus',
									array(':id'=>$vacancy,':idus'=>$idus)
							)
							->queryRow();

		return $sql['title'];
	}
	/**
	 * @param $arId - array('id')
	 * @return array - projects
	 */
	public function findRelatedProjects($arId)
	{
		$arRes = array();
		if(!count($arId))
			return $arRes;

		$sql = Yii::app()->db->createCommand()
						->select('project,vacancy')
						->from('project')
						->where(array('in','vacancy',$arId))
						->queryAll();

		foreach ($sql as $v)
			$arRes[$v['vacancy']] = $v['project'];

		return $arRes;
	}
}