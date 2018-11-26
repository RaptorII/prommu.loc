<?php

class ProjectStaff extends CActiveRecordBehavior{
	public $USERS_IN_PAGE = 20;
	public $MAX_USERS_IN_PROJECT = 100;
	public $limit;
	public $offset;
	/**
	 * @param $prj number - project ID
	 * @return staff output array
	 * страница Персонал
	 */
	public function getStaff($prj) {
		$arId = $this->getStaffProjectCnt($prj);
		$arRes['users-cnt'] = count($arId);
		$arRes['users-limit'] = $this->MAX_USERS_IN_PROJECT;
		$arRes['users-balance'] = $arRes['users-limit'] - $arRes['users-cnt'];
		$arRes['pages'] = new CPagination($arRes['users-cnt']);
		$arRes['pages']->pageSize = $this->USERS_IN_PAGE;
		$arRes['pages']->applyLimit($this);
		$arStaff = $this->getStaffProject($prj, $arId);
		$arRes['filter'] = $this->buildStaffFilterArray($arStaff);
		$arRes['users'] = $this->buildStaffArray($arStaff);

		return $arRes;
	}
	/**
	 * @param $arr array ['users','users-cnt','inv-name','inv-email','prfx-phone','inv-phone']
	 * @param $prj number - project ID
	 * Запись новых пользователей 
	 */
	public function recordStaff($arr, $prj) {
		if(!$prj)
			return false;

		$users = explode(',', $arr['users']);

		if($arr['users-cnt']>0) { // персонал из БД
			$sql = "SELECT r.id_user id, u.email 
			FROM resume r
			INNER JOIN user u ON r.id_user = u.id_user
			WHERE u.id_user IN({$arr['users']})";
			$arU = Yii::app()->db->createCommand($sql)->queryAll();

			$arPU = Yii::app()->db->createCommand()
								->select("user")
								->from('project_user')
								->where('project=:prj',array(':prj'=>$prj))
								->queryAll();			

			$arPUId = array();
			foreach ($arPU as $user)
				$arPUId[] = $user['user'];

			$arInsert = array();
			foreach ($arU as $user) 
			{
				if(!in_array($user['id'], $arPUId))
					$arInsert[] = array(
							'project' => $prj,
							'user' => $user['id'],
							'email' =>  $user['email'],
							'status' =>  isset($arr['users-activate']) ? 1 : 0,
							'phone' => ''
						);
			}
			$this->multipleInsert(['project_user' => $arInsert]);
		}

		if(strlen(reset($arr['inv-name']))) { // приглашенный персонал
			$arInsertC = array();
			$arInsertPU = array();
			$arInsertU = array();
			$arInsertR = array();
			$idCity = Subdomain::getId();
			$nU = count($arr['inv-name']);
			$date = date('Y-m-d H:i:s');
			$birthday = date("Y-m-d", strtotime('2002.09.10'));
			$Api = new Api();

			for($i = 0; $i < $nU; $i ++)
			{
				$pass = rand(11111,99999);
				$arInsertU[] = array(
						'access_time' => $date,
						'crdate' => $date,
						'mdate' => $date,
						'ismoder' => '0',
						'status' => 2,
						'isblocked' => '0',
						'email' => $arr['inv-email'][$i],
						'passw' => md5($pass),
						'login' => $arr['prfx-phone'][$i] . $arr['inv-phone'][$i]					
					);

				$male = $Api->maleor($arr['inv-name'][$i]);
				if(!isset($male['sex']))
					$male['sex'] = 1;

				$arInsertR[] = array(
						'firstname' => $arr['inv-name'][$i],
						'lastname' => $arr['inv-sname'][$i],
						'isman' => $male['sex'],
						'smart' => 1,
						'date_public' => $date,
						'mdate' => $date,
						'birthday' => $birthday,
					);

				$arInsertC[] = array('id_city' => $idCity);
				$arInsertPU[] = array(
													'project' => $prj,
													'email' => $arr['inv-email'][$i],
													'phone' => $arr['prfx-phone'][$i].$arr['inv-phone'][$i]					
												);
			}
			// делаем запись в user
			$this->multipleInsert(['user' => $arInsertU]);
			$id_user = Yii::app()->db->createCommand()
										->select("MAX(id_user)")
										->from('user')
										->queryScalar();
			$id_user = $id_user - $nU;
			for($i = 0; $i < $nU; $i ++) // готовим почву для следующих таблиц
			{
				$arInsertR[$i]['id_user'] = ++$id_user;
				$arInsertC[$i]['id_user'] = $id_user;
				$arInsertPU[$i]['user'] = $id_user;
			}
			// делаем запись в resume
			$this->multipleInsert(['resume' => $arInsertR]);
			$pid = Yii::app()->db->createCommand()
										->select("MAX(id)")
										->from('resume')
										->queryScalar();
			$pid = $pid - $nU;
			for($i = 0; $i < $nU; $i ++) // готовим id_resume
				$arInsertC[$i]['id_resume'] = ++$pid;

			$this->multipleInsert(array(
						'user_city' => $arInsertC,
						'project_user' => $arInsertPU
					));
		}
	}
	/**
	* @param $prj number OR array - project ID
	* @return array - ['conditions','values']
	* Собираем условия для фильтра данных
	*/
	public function getStaffFilter($prj) {
		$project = filter_var(
								Yii::app()->getRequest()->getParam('project'),
								FILTER_SANITIZE_NUMBER_INT
							);

		if($project>0)
			$prj = $project;

		if(is_array($prj)) { // all projects
			$arRes['conditions'] = 'pu.project IN (';
			for ($i=0, $n=sizeof($prj); $i<$n; $i++)
				$arRes['conditions'] .= $prj[$i]['project'] . ($i+1<$n ? ',' : ')');
			$arRes['values'] = array();
		}
		else {	// one project
			$arRes['conditions'] = 'pu.project = :prj';
			$arRes['values'] = array(':prj' =>$prj);
		}

		$filter = Yii::app()->getRequest()->getParam('filter');
		if(!isset($filter))
			return $arRes;

		$city = filter_var(
								Yii::app()->getRequest()->getParam('city'),
								FILTER_SANITIZE_NUMBER_INT
							);
		$metro = filter_var(
								Yii::app()->getRequest()->getParam('metro'),
								FILTER_SANITIZE_NUMBER_INT
							);
		$tname = filter_var(
								Yii::app()->getRequest()->getParam('tt_name'),
								FILTER_SANITIZE_FULL_SPECIAL_CHARS
							);
		$tindex = filter_var(
								Yii::app()->getRequest()->getParam('tt_index'),
								FILTER_SANITIZE_FULL_SPECIAL_CHARS
							);
		$fname = filter_var(
								Yii::app()->getRequest()->getParam('fname'),
								FILTER_SANITIZE_FULL_SPECIAL_CHARS
							);
		$lname = filter_var(
								Yii::app()->getRequest()->getParam('lname'),
								FILTER_SANITIZE_FULL_SPECIAL_CHARS
							);
		$status = filter_var(
								Yii::app()->getRequest()->getParam('status'),
								FILTER_SANITIZE_NUMBER_INT
							);
		$haspoint = filter_var(
								Yii::app()->getRequest()->getParam('haspoint'),
								FILTER_SANITIZE_NUMBER_INT
							);
		$hastask = filter_var(
								Yii::app()->getRequest()->getParam('hastask'),
								FILTER_SANITIZE_NUMBER_INT
							);

		if($city>0) {
			$arRes['conditions'] .= ' AND pc.id_city=:city';
			$arRes['values'][':city'] = $city;
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
		if(!empty($fname)) {
			$arRes['conditions'] .= " AND r.firstname LIKE '%".$fname."%'";
		}
		if(!empty($lname)) {
			$arRes['conditions'] .= " AND r.lastname LIKE '%".$lname."%'";
		}
		if(isset($status)) {
			switch ($status) {
				case 1: $arRes['conditions'] .= " AND pu.status=1"; break;
				case 2: $arRes['conditions'] .= " AND pu.status=0"; break;
				case 3: $arRes['conditions'] .= " AND pu.status=-1"; break;
			}
		}
		if($haspoint>0) {
			$arRes['conditions'] .= ($haspoint==1
				? " AND pb.point IS NOT NULL"
				: " AND pb.point IS NULL");
		}
		if($hastask>0) {
			$arRes['conditions'] .= ($hastask==1
				? " AND pt.id IS NOT NULL"
				: " AND pt.id IS NULL");
		}

		return $arRes;
	}
	/**
	 * @param $prj number - project ID
	 * @return staff data
	 * Выборка без пагинации
	 */
	public function getAllStaffProject($prj) {
		if(!$prj)
		return false;	
		$filter = $this->getStaffFilter($prj);
		$sql = Yii::app()->db->createCommand()
							->select(
							"pu.project,
							pu.user, 
							pu.status,
							pb.point, 
							r.firstname, 
							r.lastname,
							r.photo,
							r.isman,
							u.is_online")
							->from('project_user pu')
							->leftjoin('resume r', 'r.id_user=pu.user')
							->leftjoin('user u', 'u.id_user=pu.user')
							->leftjoin(
									'project_binding pb', 
									'pb.user=pu.user AND pb.project='.$prj
								)
							->leftjoin(
									'project_task pt', 
									'pt.user=pu.user AND pt.project='.$prj
								)	
							->leftjoin('project_city pc', 'pc.point=pb.point')
							->where($filter['conditions'], $filter['values'])
							->queryAll(); 

		return $sql;
	}
	
	/**
     * @param $user_id number - user ID
     * @return staff data
     * Получаем информацию о должностях соискателя
     */
     public function getUserMechInfo($user_id) {
         
         $mechs = $this->getUserProjectsInfoPost($user_id);
         for($i = 0; $i < count($mechs); $i ++){
             $main = Yii::app()->db->createCommand()
                    ->select("uad.name")
                    ->from('user_attr_dict uad')
                    ->where(' uad.id_par=110 AND uad.key=:key', array(':key' => $mechs[$i]['post']))
                    ->queryRow();
            if($main['name']) $mech[] = $main;
         }
        return $mech;
     }


	/**
	 * @param $prj number - project ID
	 * @return staff count
	 * подсчет пользователей проекта
	 */
	public function getStaffProjectCnt($prj) {
		if(!$prj)
			return false;	
		$filter = $this->getStaffFilter($prj);
		$sql = Yii::app()->db->createCommand()
							->select("DISTINCT(pu.user)")
							->from('project_user pu')
							->leftjoin('resume r', 'r.id_user=pu.user')
							->leftjoin(
									'project_binding pb', 
									'pb.user=pu.user AND pb.project='.$prj
								)
							->leftjoin('project_city pc', 'pc.point=pb.point')
							->leftjoin('city c', 'c.id_city=pc.id_city')
							->leftjoin('metro m', 'm.id=pc.metro')
							->where($filter['conditions'], $filter['values'])
							->queryAll();

		$arId = array(); // Нашли ID пользователей по фильтру
		for ($i=0, $n=sizeof($sql); $i<$n; $i++) 
			$arId[] = $sql[$i]['user'];

		return $arId;
	}
	/**
	 * @param $prj number - project ID
	 * @param $arId array - filtered ID of staff
	 * @return staff data
	 * Поиск персонала по ID
	 */
	public function getStaffProject($prj, $arId){
		if(!sizeof($arId))
			return array();
		// избавляемся от доп запроса выбирая только нужных юзеров по пагинации
		$arResId = array();
		for($i=$this->offset, $n=sizeof($arId); $i<$n; $i++)
			if($i<($this->offset+$this->limit))
				$arResId[] = $arId[$i];

		$strId = implode(',', $arResId);
		$sql = Yii::app()->db->createCommand()
							->select(
								"pu.project,
								pu.user, 
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
							->leftjoin(
									'project_binding pb',
									'pb.user=pu.user AND pb.project=:prj'
								)
							->leftjoin('project_city pc', 'pc.point=pb.point')
							->leftjoin('city c', 'c.id_city=pc.id_city')
							->leftjoin('metro m', 'm.id=pc.metro')
							->where(
								'pu.user IN(' . $strId . ') AND pu.project=:prj',
								array(':prj'=>$prj)
							)
							->order('pu.user desc')
							->queryAll();

		return $sql;
	}
	/**
	 * @param $arr array - sql result
	 * @return $arr array - filter data
	 */
	public function buildStaffFilterArray($arr) {
		$arRes = array();

		foreach ($arr as $v) {
			if(!empty($v['lname']))
				$arRes['tt_name'][] = $v['lname'];
			if(!empty($v['lindex']))
				$arRes['tt_index'][] = $v['lindex'];
			if(!empty($v['id_city']))
				$arRes['cities'][$v['id_city']] = array(
					'id_city' => $v['id_city'],
					'city' => $v['city'],
					'ismetro' => $v['ismetro']
				);
			if(!empty($v['metro']))
				$arRes['metros'][$v['id_metro']] = array(
					'id' => $v['id_metro'],
					'metro' => $v['metro'],
					'id_city' => $v['id_city'],
					'city' => $v['city']
				);
		}
		$arRes['tt_name'] = array_unique($arRes['tt_name']);
		$arRes['tt_index'] = array_unique($arRes['tt_index']);

		return $arRes;
	}
	/**
	 * @param $arr array - sql result
	 * @return $arr array - users data
	 */
	public function buildStaffArray($arr) {
		$arRes = array();

		foreach ($arr as $u) {
			$id = $u['user'];
			$arRes[$id]['project'] = $u['project'];
			$arRes[$id]['id_user'] = $id;
			$arRes[$id]['name'] = $u['lastname'] . ' ' . $u['firstname'];
			$arRes[$id]['src'] = $this->getPhoto(2, $u, 'small');
			$arRes[$id]['status'] = $u['status'];
			$arRes[$id]['is_online'] = $u['is_online'];
			if(!empty($u['point']))
				$arRes[$id]['points'][] = $u['point'];
			if(!empty($u['id_city']))
				$arRes[$id]['cities'][$u['id_city']] = $u['city'];
			if(!empty($u['id_metro']))
				$arRes[$id]['metros'][$u['id_metro']] = $u['metro'];
		}

		return $arRes;
	}
	/**
	*	@param $type number - user`s type
	*	@param $arr array - ['photo','isman','logo']
	* @param $size string - ['small', 'medium', 'big']
	*	@return string
	* Доп метод для формирования ссылки на картинку
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
	/**
	 * @param $arId array - projects IDies
	 * @return staff data
	 * Выборка без пагинации по всем проектам
	 */
	public function getStaffAllProjects($arId) {
		$filter = $this->getStaffFilter($arId);
		$sql = Yii::app()->db->createCommand()
							->select(
							"pu.project,
							pu.user, 
							pu.status,
							pb.point, 
							r.firstname, 
							r.lastname,
							r.photo,
							r.isman,
							u.is_online")
							->from('project_user pu')
							->leftjoin('resume r', 'r.id_user=pu.user')
							->leftjoin('user u', 'u.id_user=pu.user')
							->leftjoin('project_binding pb', 'pb.user=pu.user')
							->leftjoin('project_city pc', 'pc.point=pb.point')
							->where($filter['conditions'],$filter['values'])
							->queryAll(); 

		return $this->buildStaffArray($sql);
	}

    /**
     * @param $user_id number - user ID
     * @return staff data
     * Достаем основную информацию о юзере
     */
    public function getUserMainInfo($user_id) {
        $main = Yii::app()->db->createCommand()
            ->select("r.isman,r.birthday,r.firstname,r.lastname,r.photo,u.email,u.is_online")
            ->from('user u')
            ->leftjoin('resume r', 'r.id_user=u.id_user')
            //->leftjoin('user_attribs ua', 'ua.id_us=u.id_user')
            ->where('u.id_user =:user_id', array(':user_id' => $user_id))
            //->order('pu.user desc')
            ->queryAll();
        return $main;
    }

    /**
     * @param $user_id number - user ID
     * @return user-card data
     * Достаем информацию о контактах юзере
     */
    public function getUserContactsInfo($user_id) {
        $contacts = Yii::app()->db->createCommand()
            ->select("uad.name, ua.val, ua.key")
            ->from('user_attribs ua')
            ->leftjoin('user_attr_dict uad', 'ua.key=uad.key')
            ->where("ua.id_us =:user_id AND ua.val<>''", array(':user_id' => $user_id))
            ->queryAll();
        return $contacts;
    }

    /**
     * @param $user_id number - user ID
     * @return user-card data
     * Достаем информацию о проектах юзере
     */
    public function getUserProjectsInfo($user_id) {
        $project_info = Yii::app()->db->createCommand()
            ->select(
            //"DISTINCT(c.name)"
                "c.name city, p.project, p.name,pc.post"
            )
            ->from('project_user pu')
            ->leftjoin('project p', 'p.project=pu.project')
            ->leftjoin('project_city pc', 'pc.project=pu.project')
            ->leftjoin('city c', 'pc.id_city=c.id_city')
            ->where('pu.user =:user_id', array(':user_id' => $user_id))
            ->order('pu.user desc')
            ->queryAll();
        return $project_info;
    }
    
    /**
     * @param $user_id number - user ID
     * @return user-card data
     * Достаем информацию о проектах юзере
     */
    public function getUserProjectsInfoPost($user_id) {
        $project_info = Yii::app()->db->createCommand()
            ->select(
            //"DISTINCT(c.name)"
                "c.name city, p.project, p.name,pc.post"
            )
            ->from('project_user pu')
            ->leftjoin('project p', 'p.project=pu.project')
            ->leftjoin('project_city pc', 'pc.project=pu.project')
            ->leftjoin('city c', 'pc.id_city=c.id_city')
            ->where('pu.user =:user_id', array(':user_id' => $user_id))
            ->group('pc.post')
            ->queryAll();
        return $project_info;
    }

    /**
     * @param $user_id number - user ID
     * @return user-card data
     * Достаем Всю информацию о юзере для карточки персонала
     */
    public function getUserAllInfo($main, $mech, $contacts, $project_info) {
        $viData = [];
        foreach ($main as $key => $value) {
            $viData = $value;
        }
        
        foreach ($mech as $key => $value) {
             $mechs[] = $value['name'];
        }

        foreach ($contacts as $key => $value){
            if($value['key']=='mob'){
                $viData['tel'] = $value['val'];
            }
            else{
                $viData['CONTACTS'][]=$value;
            }
        }

        $arProjects = [];
        $arCities = [];
        foreach ($project_info as $key => $value) {
            if (!in_array($value['name'], $arProjects)) {
                $arProjects[] = $value['name'];
            }
            if (!in_array($value['city'], $arCities)) {
                $arCities[] = $value['city'];
            }
        }

        $viData['PROJECT'] = $arProjects;
        $viData['MECH'] =  $mechs;
        $viData['CITIES'] = $arCities;

        $viData['PHOTO'] = self::getPhoto('2', $viData ,'medium');

        return $viData;
    }
    /*
    *
    */
    public function buildUserData($arr) {
			$arr['logo'] = self::getPhoto('2', $arr ,'small');
			$arr['fullname'] = $arr['lastname'] . ' ' . $arr['firstname'];
			return $arr;
    }
		/**
		 * 	@param $arData - array(table => data)
		 *  запись в одно обращение
		 */
		public function multipleInsert($arData)
		{
			foreach ($arData as $table => $arInsert)
				if(count($arInsert))
				{
					Yii::app()->db->schema->commandBuilder
						->createMultipleInsertCommand($table, $arInsert)
						->execute();
				}
		}
}