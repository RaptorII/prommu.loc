<?php

class ProjectIndex extends CActiveRecordBehavior{
	/**
	 *	@param 	number - project ID 
	 *	@return array
	 * Отсортированные задания по проекту
	 */
	public function getIndexes($prj) {
		$arRes['original'] = $this->getIndex($prj);
		$arRes['location'] = $this->buildIndexArray($arRes['original']);
		$arRes['filter'] = $this->buildIndexFilterArray($arRes['original']);
		$arP = (new Vacancy)->getPost();
		for ($i=0, $n=sizeof($arP); $i < $n; $i++) 
			$arRes['posts'][$arP[$i]['id']] = $arP[$i]['name'];

		return $arRes;
	}
	/**
	 * @param $arr array - ['city','metro','lname','lindex','bdate','edate','btime','etime']
	 * @param $prj number - project ID
	 * @param $isCreate bool
	 * Запись адресной программы
	 */
	public function recordIndex($arr, $prj, $isCreate=false) {
		if(!$prj)
			return false;

		$arOldP = $arNewP = $arRes = array();
		$lId = $pId = 0;

		foreach ($arr['city'] as $c) { // по городам
			foreach ($arr['bdate'][$c] as $l => $arLoc) { // по локациям
				$lId++;
				foreach ($arLoc as $p => $v) { // по точкам
					$pId++;
					$arRes[$p] = array(
						'name' => $arr['lname'][$c][$l],
						'adres' => $arr['lindex'][$c][$l],
						'id_city' => $c,
						'bdate' => date('Y-m-d', strtotime($arr['bdate'][$c][$l][$p])),
						'edate' => date('Y-m-d', strtotime($arr['edate'][$c][$l][$p])),
						'btime' => $arr['btime'][$c][$l][$p],
						'etime' => $arr['etime'][$c][$l][$p],
						'project' => $prj,
						'latitude' => rand(1111,9999),
						'longitude' => rand(1111,9999),
						'point' => $isCreate ? ($pId.rand(1111,9999)) : $p,
						'location' => $isCreate ? $lId : $l,
						'metro' => $arr['metro'][$c][$l],
						'post' => $arr['post'][$c][$l][$p]
					);
					$arNewP[] = $p;
				}     
			}
		}

		// ищем существующие точки
		$arBD = Yii::app()->db->createCommand()
		->select("point")
		->from('project_city')
		->where('project=:prj', array(':prj' =>$prj))
		->queryAll();

		foreach ($arBD as $v)
			$arOldP[] = $v['point'];

		foreach ($arOldP as $p) 
			if( !in_array($p, $arNewP) ) {  // удаляем отсутствующие
				Yii::app()->db->createCommand()
				->delete(
					'project_city',
					'point=:pnt AND project=:prj', 
					array(':pnt'=>$p, ':prj'=>$prj)
				);
			}

		foreach ($arRes as $p => $arV) {
			if( in_array($p, $arOldP) ) { // изменяем существующие
				Yii::app()->db->createCommand()
				->update(
					'project_city',
					$arV,
					'point=:point',
					array(':point' => $new['point'])
				);    
			}
			else { // добавляем новые
				Yii::app()->db->createCommand()
				->insert('project_city', $arV);
			}
		}
	}
	/**
	* @param $prj number OR array - project ID
	* @return array - ['conditions','values']
	* Собираем условия для фильтра данных
	*/
	public function getIndexFilter($prj) {
		$project = Yii::app()->getRequest()->getParam('project');
		if($project>0)
			$prj = $project;

		if(is_array($prj)) { // all projects
			$arRes['conditions'] = 'pc.project IN (';
			for ($i=0, $n=sizeof($prj); $i<$n; $i++)
				$arRes['conditions'] .= $prj[$i]['project'] . ($i+1<$n ? ',' : ')');
			$arRes['values'] = array();
		}
		else {	// one project
			$arRes['conditions'] = 'pc.project = :prj';
			$arRes['values'] = array(':prj' =>$prj);
		}

		$filter = Yii::app()->getRequest()->getParam('filter');
		if(!isset($filter))
			return $arRes;

		$city = Yii::app()->getRequest()->getParam('city');
		$bdate = Yii::app()->getRequest()->getParam('bdate');
		$edate = Yii::app()->getRequest()->getParam('edate');
		$point = Yii::app()->getRequest()->getParam('point');
		$tname = Yii::app()->getRequest()->getParam('tt_name');
		$tindex = Yii::app()->getRequest()->getParam('tt_index');
		$metro = Yii::app()->getRequest()->getParam('metro');

		if($city>0) {
			$arRes['conditions'] .= ' AND pc.id_city=:city';
			$arRes['values'][':city'] = $city;
		}
		if(isset($bdate) && isset($edate)) {
			$arRes['conditions'] .= ' AND ((pc.bdate>=:bdate AND pc.edate<=:edate)'
			. ' OR (pc.edate>=:bdate AND pc.edate<=:edate)'
			. ' OR (pc.bdate>=:bdate AND pc.bdate<=:edate))';
			$arRes['values'][':bdate'] = date('Y.m.d', strtotime($bdate));
			$arRes['values'][':edate'] = date('Y.m.d', strtotime($edate));
		}
		if($point>0) {
			$arRes['conditions'] .= ' AND pc.point=:point';
			$arRes['values'][':point'] = $point;
		}
		if(!empty($tname)) {
			$arRes['conditions'] .= " AND pc.name LIKE '".$tname."'";
		}
		if(!empty($tindex)) {
			$arRes['conditions'] .= " AND pc.adres LIKE '".$tindex."'";
		}
		if($metro>0) {
			$arRes['conditions'] .= " AND pc.metro=:metro";
			$arRes['values'][':metro'] = $metro;
		}

		return $arRes;
	}
	/**
	 * @param $prj number - project ID
	 * Список всей адресной программы
	 */
	public function getIndex($prj){
		if(!$prj)
			return false;
		$filter = $this->getIndexFilter($prj);
		$sql = Yii::app()->db->createCommand()
							->select(
								"pc.project,
								pc.name, 
								pc.adres, 
								pc.id_city, 
								c.name city, 
								c.ismetro,
								DATE_FORMAT(pc.bdate, '%d.%m.%Y') bdate, 
								DATE_FORMAT(pc.edate, '%d.%m.%Y') edate,
								TIME_FORMAT(pc.btime, '%H:%i') btime, 
								TIME_FORMAT(pc.etime, '%H:%i') etime,
								pc.point,
								pc.location,
								pc.post,
								pc.metro id_metro,
								m.name metro"
							)
							->from('project_city pc')
							->leftjoin('city c', 'c.id_city=pc.id_city')
							->leftjoin('metro m', 'm.id=pc.metro')
							->where($filter['conditions'], $filter['values'])
							->order('pc.bdate desc')
							->queryAll();

		return $sql; 
	}
	/**
	 * @param $arr array - sql result
	 * @return $arr array - filter data
	 * Формирование массива адреса
	 */
	public function buildIndexFilterArray($arr) {
		$arRes = array(
				'bdate' => $arr[0]['bdate'],
				'edate' => $arr[0]['edate'],
				'bdate-short' => $arr[0]['bdate'],
				'edate-short' => $arr[0]['edate']
			);

		foreach ($arr as $i) {
			$arRes['cities'][$i['id_city']] = $i['city'];
			if(strtotime($i['bdate']) < strtotime($arRes['bdate']))
				$arRes['bdate'] = $i['bdate'];
			if(strtotime($i['edate']) > strtotime($arRes['edate']))
				$arRes['edate'] = $i['edate'];
			if(!empty($i['name']))
				$arRes['tt_name'][] = $i['name'];
			if(!empty($i['adres']))
				$arRes['tt_index'][] = $i['adres'];
			if(!empty($i['metro']))
				$arRes['metros'][$i['id_metro']] = array(
					'id' => $i['id_metro'],
					'metro' => $i['metro'],
					'id_city' => $i['id_city'],
					'city' => $i['city']
				);
		}
		$arRes['bdate-short'] = date('d.m.y', strtotime($arRes['bdate']));
		$arRes['edate-short'] = date('d.m.y', strtotime($arRes['edate']));
		$arRes['tt_name'] = array_unique($arRes['tt_name']);
		$arRes['tt_index'] = array_unique($arRes['tt_index']);

		return $arRes;
	}
	/**
	 * @param $arr array - sql result
	 * @return $arr array - index data
	 * Формирование массива адреса
	 */
	public function buildIndexArray($arr) {
		$arRes = array();

		foreach ($arr as $i) {
			$arRes[$i['id_city']] = array(
				'name' => $i['city'],
				'id' => $i['id_city'],
				'metro' => $i['ismetro']
			);            
		}

		foreach ($arr as $i) {
			$arL = array();
			$arL['id'] = $i['location'];
			$arL['name'] = $i['name'];
			$arL['index'] = $i['adres'];
			if(isset($i['id_metro']))
				$arL['metro'][$i['id_metro']] = $i['metro'];
			$arRes[$i['id_city']]['locations'][$i['location']] = $arL;
		}

		foreach ($arr as $i) {
			$arP = array();
			$arP['id'] = $i['point'];
			$arP['project'] = $i['project'];
			$arP['bdate'] = $i['bdate'];
			$arP['edate'] = $i['edate'];
			$arP['btime'] = $i['btime'];
			$arP['etime'] = $i['etime'];
			$arP['post'] = $i['post'];
			$arRes[$i['id_city']]['locations'][$i['location']]['periods'][$i['point']] = $arP;
		}

		return $arRes;
	}
	/**
	 * @param $arr array - ['project','city','location','point']
	 * удаление элементов адресной программы
	 */
	public function deleteLocation($arr) {
		if(array_key_exists('point',$arr)) {
			$sql = Yii::app()->db->createCommand()
										->delete(
											'project_city',
											'point=:pnt AND project=:prj', 
											array(
												':pnt'=>$arr['point'], 
												':prj'=>$arr['project']
											)
										);
			return $sql ? ['error'=>false] : ['error'=>true];
		}
		if(array_key_exists('location',$arr)) {
			$sql = Yii::app()->db->createCommand()
										->delete(
											'project_city',
											'location=:loc AND id_city=:city AND project=:prj', 
											array(
												':loc'=>$arr['location'], 
												':city'=>$arr['city'],
												':prj'=>$arr['project']
											)
										);
			return $sql ? ['error'=>false] : ['error'=>true];
		}
		if(array_key_exists('city',$arr)) {
			$sql = Yii::app()->db->createCommand()
										->delete(
											'project_city',
											'id_city=:city AND project=:prj', 
											array(
												':city'=>$arr['city'], 
												':prj'=>$arr['project']
											)
										);
			return $sql ? ['error'=>false] : ['error'=>true];
		}
	}
	/**
	 * @param $prj number - project ID
	 * @param $point number - point ID
	 * @return array - point data
	 * Получаем данные по точке
	 */
	public function getPoint($prj, $point) {
		if(!$prj)
			return false;

		$sql = Yii::app()->db->createCommand()
							->select(
								"pc.name,
								pc.adres,
								pc.id_city,
								pc.metro id_metro,
								DATE_FORMAT(pc.bdate, '%d.%m.%Y') bdate,
								DATE_FORMAT(pc.edate, '%d.%m.%Y') edate,
								TIME_FORMAT(pc.btime, '%H:%i') btime,
								TIME_FORMAT(pc.etime, '%H:%i') etime,
								pc.location,
								c.name city, 
								c.ismetro,
								m.name metro"
							)
							->from('project_city pc')
							->leftjoin('city c', 'c.id_city=pc.id_city')
							->leftjoin('metro m', 'm.id=pc.metro')
							->where(
								'pc.project=:prj AND pc.point=:point', 
								array(':prj'=>$prj, ':point'=>$point)
							)
							->queryAll();

		$arRes = array(
			'id_city' => $sql[0]['id_city'],
			'id_loc' => $sql[0]['location'],
			'id_period' => $point,
			'city' => $sql[0]['city'],
			'ismetro' => $sql[0]['ismetro'],
			'locname' => $sql[0]['name'],
			'locindex' => $sql[0]['adres'],
			'metro' => $sql[0]['metro'],
			'date' => $sql[0]['bdate']==$sql[0]['edate']
			? $sql[0]['bdate']
			: ('с ' . $sql[0]['bdate'] . ' по ' . $sql[0]['edate']),
			'time' => $sql[0]['btime'] . '-' . $sql[0]['etime']
		);

		return $arRes;
	}
	/**
	 * @param $arId array - projects IDies
	 * @return staff data
	 * Выборка без пагинации по всем проектам
	 */
	public function getIndexAllProjects($arId) {
		$filter = $this->getIndexFilter($arId);
		$sql = Yii::app()->db->createCommand()
							->select(
								"pc.project,
								pc.name, 
								pc.adres, 
								pc.id_city, 
								c.name city, 
								c.ismetro,
								DATE_FORMAT(pc.bdate, '%d.%m.%Y') bdate, 
								DATE_FORMAT(pc.edate, '%d.%m.%Y') edate,
								TIME_FORMAT(pc.btime, '%H:%i') btime, 
								TIME_FORMAT(pc.etime, '%H:%i') etime,
								pc.point,
								pc.location,
								pc.metro id_metro,
								m.name metro"
							)
							->from('project_city pc')
							->leftjoin('city c', 'c.id_city=pc.id_city')
							->leftjoin('metro m', 'm.id=pc.metro')
							->where($filter['conditions'],$filter['values'])
							->order('pc.bdate asc')
							->queryAll();

		return array(
				'original' => $sql,
				'location' => $this->buildIndexArray($sql),
				'filter' => $this->buildIndexFilterArray($sql)
			);
	}
}