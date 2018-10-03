<?php

class ProjectStaff extends Project {
	/**
	 * @param $arr array ['users','users-cnt','inv-name','inv-email','prfx-phone','inv-phone']
	 * @param $project number - project ID
	 * Запись новых пользователей 
	 */
	public function recordStaff($arr, $project) {
		if(!$project)
			return false;

		$users = explode(',', $arr['users']);

		if($arr['users-cnt']>0) { // персонал из БД
			$sql = "SELECT r.id_user id, u.email 
			FROM resume r
			INNER JOIN user u ON r.id_user = u.id_user
			WHERE u.id_user IN({$arr['users']})";
			$arU = Yii::app()->db->createCommand($sql)->queryAll();

			foreach ($arU as $user) {
				Yii::app()->db->createCommand()
				->insert('project_user', array(
					'project' => $project,
					'user' => $user['id'],
					'email' =>  $user['email'],
					'phone' => ''
				));
			}
		}

		if(strlen(reset($arr['inv-name']))) { // приглашенный персонал
			for($i = 0; $i < count($arr['inv-name']); $i ++){
				$pass = rand(11111,99999);
				$date = date('Y-m-d H:i:s');
				Yii::app()->db->createCommand()
				->insert('user', array(
					'access_time' => $date,
					'crdate' => $date,
					'mdate' => $date,
					'ismoder' => '0',
					'status' => 2,
					'isblocked' => '0',
					'email' => $arr['inv-email'][$i],
					'passw' => md5($pass),
					'login' => $arr['prfx-phone'][$i].$arr['inv-phone'][$i]
				));

				$id_user = Yii::app()->db->createCommand()
				->select("MAX(id_user)")
				->from('user')
				->queryScalar();

				$Api = new Api();
				$male = $Api->maleor($arr['inv-name'][$i]);
				if(!isset($male['sex']))
					$male['sex'] = 1;

				Yii::app()->db->createCommand()
				->insert('resume', array(
					'id_user' => $id_user+1,
					'firstname' => $arr['inv-name'][$i],
					'lastname' => $arr['inv-sname'][$i],
					'isman' => $male['sex'],
					'smart' => 1,
					'date_public' => $date,
					'mdate' => $date,
					'birthday' => date("Y-m-d", strtotime('2002.09.10')),
				));

				$pid = Yii::app()->db->createCommand()
				->select("MAX(id)")
				->from('resume')
				->queryScalar();

				Yii::app()->db->createCommand()
				->insert('user_city', array(
					'id_user' => $id_user+1,
					'id_resume' => $pid+1,
					'id_city' => Subdomain::getId()
				));

				Yii::app()->db->createCommand()
				->insert('project_user', array(
					'project' => $project,
					'user' => $id_user+1,
					'email' => $arr['inv-email'][$i],
					'phone' => $arr['prfx-phone'][$i].$arr['inv-phone'][$i]
				));
			}
		}
	}
	/**
	 * @param $project number - project ID
	 * @return staff count
	 * подсчет пользователей проекта
	 */
	public function getStaffProjectCnt($prj) {
		$filter = $this->getQueryParams($prj);
		return Yii::app()->db->createCommand()
						->select("COUNT(DISTINCT(pu.id))")
						->from('project_user pu')
						->leftjoin('resume r', 'r.id_user=pu.user')
						->leftjoin('project_binding pb', 'pb.user=pu.user')
						->leftjoin('project_city pc', 'pc.point=pb.point')
						->leftjoin('city c', 'c.id_city=pc.id_city')
						->leftjoin('metro m', 'm.id=pc.metro')
						->where($filter['staff'], $filter['values'])
						->queryScalar();
	}
	/**
	 * @param $prj number - project ID
	 * @return staff count
	 * Персонал
	 */
	public function getStaffProject($prj){
		$filter = $this->getQueryParams($prj);

		$sql = Yii::app()->db->createCommand()
				->select("DISTINCT(pu.user)")
				->from('project_user pu')
				->leftjoin('resume r', 'r.id_user=pu.user')
				->leftjoin('project_binding pb', 'pb.user=pu.user')
				->leftjoin('project_city pc', 'pc.point=pb.point')
				->where($filter['staff'], $filter['values'])
				->offset($this->offset)
				->limit($this->limit)
				->order('pu.user desc')
				->queryAll();

		if(!sizeof($sql))
			return array();

		$arId = array(); // Нашли ID пользователей по фильтру
		for ($i=0, $n=sizeof($sql); $i<$n; $i++) 
			$arId[] = $sql[$i]['user'];

	    $sql = Yii::app()->db->createCommand()
					->select(
						"pu.user, 
						pu.status, 
						pu.date,
						r.firstname, 
						r.lastname,
						r.photo,
						r.isman,
						pc.name lname,
						pc.adres lindex,
						pc.id_city,
						pc.metro id_metro,
						pb.point,
						c.name city,
						c.ismetro ismetro,
						m.name metro")
					->from('project_user pu')
					->leftjoin('resume r', 'r.id_user=pu.user')
					->leftjoin('project_binding pb', 'pb.user=pu.user')
					->leftjoin('project_city pc', 'pc.point=pb.point')
					->leftjoin('city c', 'c.id_city=pc.id_city')
					->leftjoin('metro m', 'm.id=pc.metro')
					->where(array('in','pu.user',$arId))
					->order('pu.user desc')
					->queryAll();  // поиск всех данных по найденым пользователям

		foreach ($sql as $v) {
			if(!empty($v['lname']))
				$arRes['filter']['tt_name'][] = $v['lname'];
			if(!empty($v['lindex']))
				$arRes['filter']['tt_index'][] = $v['lindex'];
			if(!empty($v['id_city']))
				$arRes['filter']['cities'][$v['id_city']] = array(
					'id_city' => $v['id_city'],
					'city' => $v['city'],
					'ismetro' => $v['ismetro']
				);
			if(!empty($v['metro']))
				$arRes['filter']['metros'][$v['id_metro']] = array(
					'id' => $v['id_metro'],
					'metro' => $v['metro'],
					'id_city' => $v['id_city'],
					'city' => $v['city']
				);
		}
		$arRes['filter']['tt_name'] = array_unique($arRes['filter']['tt_name']);
		$arRes['filter']['tt_index'] = array_unique($arRes['filter']['tt_index']);

		foreach ($sql as $u) {
			$id = $u['user'];
			$arRes['users'][$id]['id_user'] = $id;
			$arRes['users'][$id]['name'] = $u['lastname'] . ' ' . $u['firstname'];
			$arRes['users'][$id]['src'] = $this->getPhoto(2, $u, 'small');
			$arRes['users'][$id]['status'] = $u['status'];
			if(!empty($u['point']))
				$arRes['users'][$id]['points'][] = $u['point'];
			if(!empty($u['id_city']))
				$arRes['users'][$id]['cities'][$u['id_city']] = $u['city'];
			if(!empty($u['id_metro']))
				$arRes['users'][$id]['metros'][$u['id_metro']] = $u['metro'];
		}

		return $arRes;
	}
	/**
	 * @param $prj number - project ID
	 * @return staff output array
	 * страница Персонал
	 */
	public function getStaff($prj) {
		$cnt = $this->getStaffProjectCnt($prj);
		$arRes['pages'] = new CPagination($cnt);
		$arRes['pages']->pageSize = $this->USERS_IN_PAGE;
		$arRes['pages']->applyLimit($this);
		$arRes['project'] = $this->getProjectData($prj);
		$arRes = array_merge($arRes, $this->getStaffProject($prj));
		return $arRes;
	}
	/**
	*	@param $type number - user`s type
	*	@param $arr array - ['photo','isman','logo']
	* @param $size string - ['small', 'medium', 'big']
	*	@return string
	*/
	public static function getPhoto($type, $arr, $size) {
		$src = DS . 
			($type==2 ? MainConfig::$PATH_APPLIC_LOGO : MainConfig::$PATH_EMPL_LOGO)
			. DS;
		if($type==2) { // applicant
			if($arr['photo'])
				switch ($size) {
					case 'small': $src .= $arr['photo'] . '100.jpg'; break;
					case 'medium': $src .= $arr['photo'] . '400.jpg'; break;
					case 'big': $src .= $arr['photo'] . '000.jpg'; break;
				}
			else
				$src .= $arr['isman'] ? MainConfig::$DEF_LOGO : MainConfig::$DEF_LOGO_F;
		}
		if($type==3) { // employer
			if($arr['logo'])
				switch ($size) {
					case 'small': $src .= $arr['logo'] . '100.jpg'; break;
					case 'medium': $src .= $arr['logo'] . '400.jpg'; break;
					case 'big': $src .= $arr['logo'] . '000.jpg'; break;
				}
			else
				$src .= MainConfig::$DEF_LOGO;
		}
		return $src;
	}
}