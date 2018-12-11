<?php

class ProjectTask extends CActiveRecordBehavior{
	/**
	*	@param 	number - project ID 
	*	@param 	bool - full data or only counters
	*	@return array
	* Отсортированные задания по проекту
	*/
	public function getTasks($project, $onlyCounters=false) {
		$arRes = $this->getTaskList($project);
		$arRes = $this->buildTaskArray($arRes, $onlyCounters);
		return $arRes;
	}
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
	 * @param 	bool - full data or only counters
	 * @return array
	 * Формирования подходящего массива с заданиями
	 */
	public function buildTaskArray($arr, $onlyCounters=false) {
		$arRes = array();

		if(!$onlyCounters) {
			for($i=0,$n=sizeof($arr); $i<$n; $i++) {
				$unix = strtotime($arr[$i]['date']);
				$point = $arr[$i]['point'];
				$user = $arr[$i]['user'];
				$arRes[$unix][$point][$user][$arr[$i]['id']] = $arr[$i];
			}
		}
		else {
			for($i=0,$n=sizeof($arr); $i<$n; $i++)
				$arRes[$arr[$i]['point']][$arr[$i]['user']] += 1;
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
	 * Вернуть задания пользователя
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
			$arParams[':date'] = date('Y-m-d 00:00:00',$arr['date']);
		}

		$sql = Yii::app()->db->createCommand()
							->select("id, DATE_FORMAT(date, '%d.%m.%Y') date, name, text")
							->from('project_task')
							->where($conditions, $arParams)
							->queryAll();

		if(sizeof($sql))
			$arRes = array('tasks' => $sql, 'error' => false);

		return $arRes;
	}
	/**
	 * @param $arr - array [project,user_id,tt_id,date,items=[[event, task_id, all_date, all_users, delete, title, descr, status]]       
	 * @return array ['error','data']
	 * обработчик событий  заданий
	 */
	public function taskAjaxHandler($arr)
	{
		$arRes = ['error' => true, 'data' => []];
		$arTask['project'] = filter_var($arr['project'],FILTER_SANITIZE_NUMBER_INT);
		$arTask['user'] = filter_var($arr['user_id'],FILTER_SANITIZE_NUMBER_INT);
		$arTask['point'] = filter_var($arr['tt_id'],FILTER_SANITIZE_NUMBER_INT);
		$arTask['date'] = filter_var($arr['date'],FILTER_SANITIZE_NUMBER_INT);

		if(!count($arr['items']) || !$arTask['project'] || !$arTask['user'] 
			 || !$arTask['point'] || !$arTask['date'])
			return $arRes;

		foreach ($arr['items'] as $v)
		{
			$arTask['name'] = filter_var($v['title'],FILTER_SANITIZE_FULL_SPECIAL_CHARS);
			$arTask['text'] = filter_var($v['descr'],FILTER_SANITIZE_FULL_SPECIAL_CHARS);
			$arTask['status'] = filter_var($v['status'],FILTER_SANITIZE_NUMBER_INT);
			$id = filter_var($v['task_id'],FILTER_SANITIZE_NUMBER_INT);

			// удаление задачи
			if($v['delete']==true && $id>0)
			{
				$sql = Yii::app()->db->createCommand()
								->delete('project_task','id=:id', [':id' => $id]);
				if($sql!=false)
				{
					$arRes['error'] = false;
					$arRes['data']['deleted'][] = $id;
				}
				continue;
			}

			// новая задача
			if($v['event']=='new') 
			{
				$sql = Yii::app()->db->createCommand()
								->insert('project_task', $arTask);
				if($sql!=false)
				{
					$id = Yii::app()->db->createCommand()
									->select("MAX(id)")
									->from('project_task')
									->queryScalar();

					$arRes['error'] = false;
					$arRes['data']['added'][] = $id;
				}
			}

			// изменение существующей задачи
			if($v['event']=='change' && $id>0) 
			{
				$sql = Yii::app()->db->createCommand()
								->update('project_task', $arTask, 'id=:id', [':id' => $id]);
				if($sql!=false)
				{
					$arRes['error'] = false;
					$arRes['data']['changed'][] = $id;
				}
			}
			// дублирование на все даты точки
			if($v['all_date']==true) 
			{
				$this->taskToAllDates($arTask);
				$arRes['error'] = false;
				$arRes['data']['all_date'][] = $id;
			}
			// дублирование на всех привязанных пользователей
			if($v['all_users']==true) 
			{
				$this->taskToAllUsers($arTask);
				$arRes['error'] = false;
				$arRes['data']['all_users'][] = $id;
			}
		}

		return $arRes;
	}
	/**
	 * @param $arr - array [project,user,point,date,name,text,status]
	 * @return bool
	 */
	public function taskToAllDates($arr)
	{
		$arInsert = array();
		$date = $arr['date'];
		$day = 60*60*24;

		$sql = Yii::app()->db->createCommand()
						->select("bdate, edate")
						->from('project_city')
						->where(
							'project=:prj AND point=:pnt',
							[ ':prj' => $arr['project'], ':pnt' => $arr['point'] ]
						)
						->queryRow();

		
		$bdate = strtotime($sql['bdate']);
		$edate = strtotime($sql['edate']);

		do{
			if($bdate!=$date)
			{
				$arInsert[] = array(
						'project' => $arr['project'],
						'user' => $arr['user'],
						'point' => $arr['point'],
						'name' => $arr['name'],
						'text' => $arr['text'],
						'date' => date('Y-m-d', $bdate),
						'status' => $arr['status']
					);
			}
			$bdate += $day;
		}
		while($bdate <= $edate);
		// добавление одним запросом
		Share::multipleInsert(['project_task'=>$arInsert]);
	}
	/**
	 * @param $arr - array [project,user,point,date,name,text,status]
	 * @return bool
	 */
	public function taskToAllUsers($arr)
	{
		$arInsert = array();
		$user = $arr['user'];
		$sql = Yii::app()->db->createCommand() // все юзеры, привязаные к точке
			->select("user")
			->from('project_binding')
			->where(
				'project=:prj AND point=:pnt',
				[ ':prj' => $arr['project'], ':pnt' => $arr['point'] ]
			)
			->queryAll();

		foreach ($sql as $v)
		{
			if($v['user'] != $user)
			{
				$arInsert[] = array(
						'project' => $arr['project'],
						'user' => $v['user'],
						'point' => $arr['point'],
						'name' => $arr['name'],
						'text' => $arr['text'],
						'date' => date('Y-m-d', $arr['date']),
						'status' => $arr['status']
					);
			}
		}
		// добавление одним запросом
		Share::multipleInsert(['project_task'=>$arInsert]);
	}
}