<?php

class ProjectTask extends CActiveRecordBehavior{
	/**
	*	@param 	number - project ID 
	*	@return array
	* Задания по проекту
	*/
	public function getTaskList($prj) {
		if(!$prj)
			return false;

		$sql = Yii::app()->db->createCommand()
		->select("*")
		->from('project_task')
		->where('project=:prj', array(':prj'=>$prj))
		->queryAll();

		return $sql;
	}
	/**
	 * @param array tasks query
	 * @return array
	 * Формирования подходящего массива с заданиями
	 */
	public function buildTaskArray($arr) {
		$arRes = array();

		for($i=0,$n=sizeof($arr); $i<$n; $i++) {
			$unix = strtotime($arr[$i]['date']);
			$point = $arr[$i]['point'];
			$user = $arr[$i]['user'];
			$arRes[$unix][$point][$user][$arr[$i]['id']] = $arr[$i];
		}

		return $arRes;
	}
	/**
	 * @param array ['project','user','point','title','text','date']
	 * @return array ['error','data']
	 * Изменение заданий
	 */
	public function changeTask($arr) {
		$arRes = ['error' => true, 'data' => []];

		$arNew = array(
			'project' => $arr['project'],
			'user' => $arr['user'],
			'point' => $arr['point'],
			'name' => $arr['title'],
			'text' => $arr['text'],
			'date' => date('Y-m-d', $arr['date'])
		);

		switch ($arr['type']) {
			case 'new-task': // создание нового задания
				$sql = Yii::app()->db->createCommand()
				->insert('project_task', $arNew);
				if($sql) {
					$sql = Yii::app()->db->createCommand()
						->select("MAX(id)")
						->from('project_task')
						->queryScalar();

					$arRes['error'] = false;
					$arRes['data']['task'] = $sql;
				}
				break;

			case 'change-task': // изменение существующего
				$sql = Yii::app()->db->createCommand()
					->update(
						'project_task', 
						$arNew, 
						'id = :id', 
						array(':id' => $arr['task'])
					);
				if($sql) $arRes['error'] = false;
				break;

			case 'all-dates-task': // дублирование на все даты точки
				$sql = Yii::app()->db->createCommand()
					->select("bdate, edate")
					->from('project_city')
					->where(
						'project=:prj AND point=:pnt',
						array(
							':prj' => $arr['project'],
							':pnt' => $arr['point']
						)
					)
					->queryRow();
				$day = 60*60*24;
				$bdate = strtotime($sql['bdate']);
				$edate = strtotime($sql['edate']);

				$sql = Yii::app()->db->createCommand()
					->select("*")
					->from('project_task')
					->where(
						'project=:prj AND point=:pnt AND user=:u',
						array(
							':prj' => $arr['project'],
							':pnt' => $arr['point'],
							':u' => $arr['user']
						)
					)
					->queryAll();

				$arT = array();
				foreach ($sql as $v)
					$arT[] = strtotime($v['date']);

				do{
					$arNew['date'] = date('Y-m-d', $bdate);
					if(in_array($bdate, $arT)) {
						$sql = Yii::app()->db->createCommand()
						->update(
							'project_task', 
							$arNew, 
							'project=:prj AND point=:pnt AND user=:u AND date=:d',
							array(
								':prj' => $arr['project'],
								':pnt' => $arr['point'],
								':u' => $arr['user'],
								':d' => $arNew['date']
							)
						);
						if($sql) $arRes['error'] = false;
					}
					else {
						$sql = Yii::app()->db->createCommand()
						->insert('project_task', $arNew);
						if($sql) $arRes['error'] = false;
					}
					$bdate += $day;
				}
				while($bdate <= $edate);
				break;

			case 'all-users-task': // дублирование на всех привязанных пользователей
				$sql = Yii::app()->db->createCommand()
					->select("*")
					->from('project_task')
					->where(
						'project=:prj AND point=:pnt AND date=:d',
						array(
							':prj' => $arr['project'],
							':pnt' => $arr['point'],
							':d' => date('Y-m-d', $arr['date'])
						)
					)
					->queryAll();

				$arU = array();
				foreach ($sql as $v)
					$arU[] = $v['user'];

				$sql = Yii::app()->db->createCommand()
					->select("user")
					->from('project_binding')
					->where(
						'project=:prj AND point=:pnt',
						array(
							':prj' => $arr['project'],
							':pnt' => $arr['point']
						)
					)
					->queryAll();

				foreach ($sql as $v) {
					$arNew['user'] = $v['user'];
					if(in_array($v['user'], $arU)) { 
						$sql = Yii::app()->db->createCommand()
						->update(
							'project_task', 
							$arNew, 
							'project=:prj AND point=:pnt AND user=:u AND date=:d',
							array(
								':prj' => $arr['project'],
								':pnt' => $arr['point'],
								':u' => $arNew['user'],
								':d' => date('Y-m-d', $arr['date'])
							)
						);
						if($sql) $arRes['error'] = false;
					}
					else {
						$sql = Yii::app()->db->createCommand()
						->insert('project_task', $arNew);
						if($sql) $arRes['error'] = false;                         
					}
				}
				break;

			case 'delete-task': // изменение существующего
				$sql = Yii::app()->db->createCommand()
				->delete('project_task','id=:id', [':id'=>$arr['task']]);
				if($sql) $arRes['error'] = false; 
				break;
		}
		return $arRes;
	}
	/**
	 * @param array ['project','user','point','title','text','date']
	 * @return array ['error','data']
	 * Изменение заданий
	 */
	public function getUserTasks($arr) {
		$arRes = array('error'=>true);

		if(!intval($arr['project']) || !intval($arr['user']) || !intval($arr['point']))
			return $arRes;

		$conditions = 'project=:prj AND user=:user AND point=:pnt';
		$arParams[':prj'] = $arr['project'];
		$arParams[':user'] = $arr['user'];
		$arParams[':pnt'] = $arr['point'];

		if(!empty($arr['date'])) {
			$conditions .= ' AND date=:date';
			$arParams[':date'] = $arr['date'];
		}

		$sql = Yii::app()->db->createCommand()
							->select("*")
							->from('project_task')
							->where($conditions, $arParams)
							->queryAll();

		if(sizeof($sql))
			$arRes = array('tasks' => $sql, 'error' => false);

		return $arRes;
	}
}