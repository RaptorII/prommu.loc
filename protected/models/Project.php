<?php
/**
 * Created by Grescode
 * Date: 28.07.18
 */

class Project extends ARModel
{
    public $USERS_IN_PAGE = 20;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'project';
    }




    public function createProject($props){
        $cloud = [];
        $project = time().rand(11111,99999);
        ///Обработка адресной программы
        $idus = Share::$UserProfile->id;
        $res = Yii::app()->db->createCommand()
                        ->insert('project', array(
                            'project' => $project,
                            'id_user' => $idus,
                            'name' => $props['name'],
                            'crdate' => date('Y-m-d h-i-s')
                        ));
    
        $this->recordIndex($props, $project, true);
        $this->recordStaff($props, $project);

        return $cloud;
    }
    /*
    *       Запись адресной программы
    */
    public function recordIndex($arr, $project, $isCreate=false) {
        if(!$project)
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
                        'project' => $project,
                        'latitude' => rand(1111,9999),
                        'longitude' => rand(1111,9999),
                        'point' => $isCreate ? ($pId.rand(1111,9999)) : $p,
                        'location' => $isCreate ? $lId : $l,
                        'metro' => $arr['metro'][$c][$l]
                    );
                    $arNewP[] = $p;
                }     
            }
        }

        // ищем существующие точки
        $arBD = Yii::app()->db->createCommand()
            ->select("point")
            ->from('project_city')
            ->where('project=:prj', array(':prj' =>$project))
            ->queryAll();

        foreach ($arBD as $v)
            $arOldP[] = $v['point'];


        foreach ($arOldP as $p) 
            if( !in_array($p, $arNewP) ) {  // удаляем отсутствующие
                Yii::app()->db->createCommand()
                    ->delete(
                        'project_city',
                        'point=:pnt AND project=:prj', 
                        array(':pnt'=>$p, ':prj'=>$project)
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
    /*
    *       Запись местоположения пользователя
    */
    public function recordReport($arr){
        if(!$arr['project'])
            return array('error'=>true);

        $res = Yii::app()->db->createCommand()
                    ->insert('project_report', array(
                            'project' => $arr['project'],
                            'user' => $arr['user'],
                            'point' => $arr['point'],
                            'date' => date("Y-m-d H-i-s"),
                            'longitude' => $arr['longitude'],
                            'latitude' =>  $arr['latitude'],
                        ));

        return $res ? ['error'=>false] : ['error'=>true];
    }
    /*
    *       Запись новых пользователей
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
    /*
    *       Проекты для Р
    */
    public function getProjectEmployer($isArcive){
        $arRes = ['items' => [], 'archive' => []];
        $idus = Share::$UserProfile->id;
        $sql = Yii::app()->db->createCommand()
            ->select("
                    p.project, 
                    p.name, 
                    p.crdate, 
                    pu.status, 
                ")
            ->from('project p')
            ->where('p.id_user=:idus', array(':idus'=>$idus))
            ->leftjoin('project_user pu', 'pu.project=p.project')
            ->order('p.crdate desc')
            ->queryAll();

        foreach ($sql as $v) {
            $p = $v['project'];
            $arRes['items'][$p]['name'] = $v['name']; 
            $arRes['items'][$p]['date'] = date('d.m.Y',strtotime($v['crdate']));

            if(!isset($arRes['items'][$p]['ignored']))
                $arRes['items'][$p]['ignored'] = 0;

            if(!isset($arRes['items'][$p]['agreed']))
                $arRes['items'][$p]['agreed'] = 0;

            if(!isset($arRes['items'][$p]['refused']))
                $arRes['items'][$p]['refused'] = 0;

            switch ($v['status']) {
                case '0': $arRes['items'][$p]['ignored']++; break;
                case '1': $arRes['items'][$p]['agreed']++; break;
                case '-1': $arRes['items'][$p]['refused']++; break;
            }
        }

        $arRes['employer'] = Yii::app()->db->createCommand()
            ->select("name, logo")
            ->from('employer')
            ->where('id_user=:idus', array(':idus'=>$idus))
            ->queryRow();

        $arRes['employer']['src'] = ProjectStaff::getPhoto(3, $arRes['employer'], 'medium');

        return $arRes;
    }
    /*
    *       Проекты для С
    */
    public function getProjectApplicant() {
			$idus = Share::$UserProfile->id;
			$sql = Yii::app()->db->createCommand()
				->select("
						p.project, 
						p.name, 
						p.id_user, 
						p.crdate, 
						pu.status, 
						e.name emp,
						e.logo
					")
				->from('project p')
				->leftjoin('project_user pu', 'pu.project=p.project')
				->leftjoin('employer e', 'e.id_user=p.id_user')
				->where('pu.user = :idus', array(':idus' =>$idus))
				->order('p.crdate desc')
				->queryAll();

			$arRes = array();
			foreach ($sql as $v) {
				$v['link'] = MainConfig::$PAGE_PROJECT_LIST . DS . $v['project'];
                $v['src'] = ProjectStaff::getPhoto(3, $v, 'small');
                $v['profile'] = MainConfig::$PAGE_PROFILE_COMMON . DS . $v['id_user'];

				switch ($v['status']) {
					case '0': $arRes['new-items'][] = $v; break;
					case '1': $arRes['items'][] = $v; break;
					case '-1': $arRes['failures'][] = $v; break;
				}
			}

			return $arRes;
    }
    /**
    * @param number - project ID
    * @return array - ['index','staff','values']
    */
    private function getQueryParams($prj) {
        $arRes['index'] = 'pc.project=:prj';
        $arRes['staff'] = 'pu.project = :prj';
        $arRes['values'] = array(':prj' =>$prj);

        $filter = Yii::app()->getRequest()->getParam('filter');
        if(!isset($filter))
            return $arRes;
        // index
        $city = Yii::app()->getRequest()->getParam('city');
        $bdate = Yii::app()->getRequest()->getParam('bdate');
        $edate = Yii::app()->getRequest()->getParam('edate');
        $point = Yii::app()->getRequest()->getParam('point');
        $tname = Yii::app()->getRequest()->getParam('tt_name');
        $tindex = Yii::app()->getRequest()->getParam('tt_index');
        $metro = Yii::app()->getRequest()->getParam('metro');
        // staff
        $fname = Yii::app()->getRequest()->getParam('fname');
        $lname = Yii::app()->getRequest()->getParam('lname');
        $status = Yii::app()->getRequest()->getParam('status');
        $haspoint = Yii::app()->getRequest()->getParam('haspoint');

        if($city>0) {
            $arRes['index'] .= ' AND pc.id_city=:city';
            $arRes['staff'] .= $arRes['index'];
            $arRes['values'][':city'] = $city;
        }
        if(isset($bdate) && isset($edate)) {
            $arRes['index'] .= ' AND ((pc.bdate>=:bdate AND pc.edate<=:edate)'
            . ' OR (pc.edate>=:bdate AND pc.edate<=:edate)'
            . ' OR (pc.bdate>=:bdate AND pc.bdate<=:edate))';
            $arRes['values'][':bdate'] = date('Y.m.d', strtotime($bdate));
            $arRes['values'][':edate'] = date('Y.m.d', strtotime($edate));
        }
        if($point>0) {
            $arRes['index'] .= ' AND pc.point=' . $point;
        }
        if(!empty($tname)) {
            $arRes['index'] .= " AND pc.name LIKE '".$tname."'";
            $arRes['staff'] .= $arRes['index'];
        }
        if(!empty($tindex)) {
            $arRes['index'] .= " AND pc.adres LIKE '".$tindex."'";
            $arRes['staff'] .= $arRes['index'];
        }
        if($metro>0) {
            $arRes['index'] .= " AND pc.metro=:metro";
            $arRes['staff'] .= $arRes['index'];
            $arRes['values'][':metro'] = $metro;
        }

        if(!empty($fname)) {
            $arRes['staff'] .= " AND r.firstname LIKE '%".$fname."%'";
        }
        if(!empty($lname)) {
            $arRes['staff'] .= " AND r.lastname LIKE '%".$lname."%'";
        }
        if(isset($status)) {
            switch ($status) {
                case 1: $arRes['staff'] .= " AND pu.status=1"; break;
                case 2: $arRes['staff'] .= " AND pu.status=0"; break;
                case 3: $arRes['staff'] .= " AND pu.status=-1"; break;
            }
        }
        if($haspoint>0) {
            $arRes['staff'] .= ($haspoint==1
                ? " AND pb.point IS NOT NULL"
                : " AND pb.point IS NULL");
        }

        return $arRes;
    }
    /*
    *       Список Адресной программы
    */
    public function getAdresProgramm($project){
        $filter = $this->getQueryParams($project);
        $sql = Yii::app()->db->createCommand()
            ->select(
                "pc.name, 
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
            ->where($filter['index'], $filter['values'])
            ->order('pc.bdate desc')
            ->queryAll();

        return $this->buildAdressArray($sql); 
    }
    /*
    *       подсчет пользователей проекта
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
    /*
    *       Персонал
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
            $arRes['users'][$id]['src'] = ProjectStaff::getPhoto(2, $u, 'small');
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
    
    public function getProject($prj){
        $arRes = $this->getAdresProgramm($prj);
        $arRes['users'] = $this->getProjectPromo($prj);
        $arRes['project'] = $this->getProjectData($prj);

        return $arRes;
    }
    
    public function importProject($props){
         $link = $props['link'];
        $project = $props['project'];
        Yii::import('ext.yexcel.Yexcel');
        $sheet_array = Yii::app()->yexcel->readActiveSheet("/var/www/dev.prommu/uploads/$link");
        
        $city = "Город";
        $location = "Локация";
        $street = "Улица";
        $home = "Дом";
        $build = "Здание";
        $str = "Строение";
        $date = "Дата работы";
        $time = "Время работы";
        
        
        $location = [];

        for($i = 1; $i < count($sheet_array)+1; $i++){
            $city = Yii::app()->db->createCommand()
                    ->select('c.id_city')
                    ->from('city c')
                    ->where('c.name = :name', array(':name' =>$sheet_array[$i]['A']))
                    ->queryRow();
                
                $bdate = explode("-", $sheet_array[$i]['H'])[0];
                $edate = explode("-", $sheet_array[$i]['H'])[1];
                   
                $bdate = str_replace(".", "-", $bdate);
                $edate = str_replace(".", "-", $edate);
                    
                if($sheet_array[$i]['I'] != ''){
                    $point = $sheet_array[$i]['I'];
                    
                    
                     Yii::app()->db->createCommand()
                        ->update('project_city', array(
                            'project' => $project,
                            'name' =>  $sheet_array[$i]['B'],
                            'adres' =>  $sheet_array[$i]['C'].' '.$sheet_array[$i]['D'].' '.$sheet_array[$i]['E'].' '.$sheet_array[$i]['F'],
                            'id_city' => $city['id_city'],
                            'btime' =>  explode("-", $sheet_array[$i]['G'])[0],
                            'etime' =>  explode("-", $sheet_array[$i]['G'])[1],
                            'bdate' => $bdate,
                            'edate' =>  $edate,
                     ), 'point = :point', array(':point' => $point));
                
                } else {
                    $res = Yii::app()->db->createCommand()
                        ->insert('project_city', array(
                            'project' => $project,
                            'name' =>  $sheet_array[$i]['B'],
                            'adres' =>  $sheet_array[$i]['C'].' '.$sheet_array[$i]['D'].' '.$sheet_array[$i]['E'].' '.$sheet_array[$i]['F'],
                            'id_city' =>  $city['id_city'],
                            'bdate' =>  $bdate,
                            'edate' =>  $edate,
                            'btime' => explode("-", $sheet_array[$i]['G'])[0],
                            'etime' =>  explode("-", $sheet_array[$i]['G'])[1],
                            'point' => $i.''.rand(1111,9999),
                        ));   
                }
        }


        return $location;
    } 
    
    public function importUsers($props){
        $link = $props['link'];
        $project = $props['project'];
        Yii::import('ext.yexcel.Yexcel');
        $sheet_array = Yii::app()->yexcel->readActiveSheet("/var/www/dev.prommu/uploads/$link");
        
        $firstname = "Имя";
        $lastname = "Фамилия";
        $email = "Электронная почта";
        $phone = "Телефон";
        $phone = "Локации";
        
        $location = [];

        for($i = 2; $i < count($sheet_array)+1; $i++){
                 $data = Yii::app()->db->createCommand()
                ->select('pc.id, pc.user, pc.status, pc.project, pc.email, pc.phone')
                ->from('project_user pc')
                ->where('pc.email = :email', array(':email' =>$sheet_array[$i]['C']))
                ->order('pc.date desc')
                ->queryRow();   
                if($data['email']){
                    $point = $sheet_array[$i]['I'];
                    
                     Yii::app()->db->createCommand()
                        ->update('project_user', array(
                            'project' => $project,
                            'phone' =>  $sheet_array[$i]['D'],
                     ), 'email = :email', array(':email' => $sheet_array[$i]['C']));
                
                } else {
                    $result = Yii::app()->db->createCommand()
                        ->select('id_user')
                        ->from('user')
                        ->where('email=:email', array(':email'=>$sheet_array[$i]['C']))
                        ->queryAll();
                    if(count($result))
                    {
                        $id_user = $result[0]['id_user'];
                    } else {
                        $arr['prfx-phone'][0] = '';
                        $arr['inv-phone'][0] = $sheet_array[$i]['D'];
                        $arr['inv-email'][0] = $sheet_array[$i]['C'];
                        $arr['inv-name'][0] = $sheet_array[$i]['A'];
                        $arr['inv-sname'][0] = $sheet_array[$i]['B'];
                        $this->recordStaff($arr, $project);
                        $id_user = Yii::app()->db->createCommand()
                        ->select("MAX(id_user)")
                        ->from('user')
                        ->queryScalar();
                    }
                    
                    $res = Yii::app()->db->createCommand()
                        ->insert('project_user', array(
                            'project' => $project,
                            'user' => $id_user,
                            'email' =>  $sheet_array[$i]['C'],
                            'phone' =>  $sheet_array[$i]['D'],
                        ));   
                }
        }


        return $location;
    } 
    
    
    public function exportUsers($project){
        Yii::import('ext.yexcel.Yexcel');
        
        
           $data = Yii::app()->db->createCommand()
            ->select('pc.id, pc.user, pc.status, pc.project,  r.firstname, r.lastname, pc.email, pc.phone')
            ->from('project_user pc')
            ->join('resume r', 'r.id_user=pc.user')
            ->where('pc.project = :project', array(':project' =>$project))
            ->queryAll();
        
          for($i = 0; $i < count($data); $i ++){
           $datas = Yii::app()->db->createCommand()
            ->select('prc.name')
            ->from('project_user pc')
            ->join('project_binding pb', 'pb.user=pc.user')
            ->join('project_city prc', 'prc.point=pb.point')
            ->where('pb.project = :project AND pb.user = :user', array(':project' =>$project, ':user' => $data[$i]['user']))
            ->group('prc.name')
            ->queryAll();
            
            $data[$i]['point'] = $datas;
        }
        

            
        $sheet_array = Yii::app()->yexcel->setActiveSheetUsers($data);
            
    }
    
    
   public function exportProject($project){
        Yii::import('ext.yexcel.Yexcel');
        
         $data = Yii::app()->db->createCommand()
            ->select('pc.id, pc.name, pc.adres, pc.id_city, c.name city, pc.bdate, pc.edate, pc.btime, pc.etime, pc.project, pc.point')
            ->from('project_city pc')
            ->join('city c', 'c.id_city=pc.id_city')
            ->where('pc.project = :project', array(':project' =>$project))
            ->order('pc.bdate desc')
            ->queryAll();
            
        $sheet_array = Yii::app()->yexcel->setActiveSheet($data);
            
    }
    /*
    *       Проверка доступа
    */
    public function hasAccess($prj){
        $idus = Share::$UserProfile->id;
        $t = Share::$UserProfile->type;
        if($t==3) {
            $result = Yii::app()->db->createCommand()
                ->select('id')
                ->from('project')
                ->where('id_user=:idus AND project=:prj', array(':idus'=>$idus,':prj'=>$prj))
                ->queryAll();
        }
        if($t==2) {
            $result = Yii::app()->db->createCommand()
                ->select('id')
                ->from('project_user')
                ->where('user=:idus AND project=:prj', array(':idus'=>$idus,':prj'=>$prj))
                ->queryAll();
        }
            
        return sizeof($result);
    }
    /*
    *       формирование массива адреса
    */
    public function buildAdressArray($arr){
        if(!count($arr))
            return array();
   
        $arF = array(
            'bdate' => $arr[0]['bdate'],
            'edate' => $arr[0]['edate'],
            'bdate-short' => $arr[0]['bdate'],
            'edate-short' => $arr[0]['edate']
        );


        $arI = array();
        foreach ($arr as $i) {
            if(strtotime($i['bdate']) < strtotime($arF['bdate']))
                $arF['bdate'] = $i['bdate'];
            if(strtotime($i['edate']) > strtotime($arF['edate']))
                $arF['edate'] = $i['edate'];
            $arF['cities'][$i['id_city']] = $i['city'];
            $arI[$i['id_city']] = array(
                'name' => $i['city'],
                'id' => $i['id_city'],
                'metro' => $i['ismetro']
            );            
        }
        $arF['bdate-short'] = date('d.m.y', strtotime($arF['bdate']));
        $arF['edate-short'] = date('d.m.y', strtotime($arF['edate']));


        foreach ($arr as $i) {
            $arL = array();
            $arL['id'] = $i['location'];
            $arL['name'] = $i['name'];
            $arL['index'] = $i['adres'];
            if(isset($i['id_metro'])) {
                $arL['metro'][$i['id_metro']] = $i['metro'];
                $arF['metro'][$i['id_metro']] = $i['metro'];               
            }

            $arI[$i['id_city']]['locations'][$i['location']] = $arL;
        }
        
        foreach ($arr as $i) {
            $arP = array();
            $arP['id'] = $i['point'];
            $arP['bdate'] = $i['bdate'];
            $arP['edate'] = $i['edate'];
            $arP['btime'] = $i['btime'];
            $arP['etime'] = $i['etime'];
            $arI[$i['id_city']]['locations'][$i['location']]['periods'][$i['point']] = $arP;
        }
        $arRes['location'] = $arI;
        $arRes['filter'] = $arF;
        $arRes['original'] = $arr;

        return $arRes;
    }
    /*
    *       удаление элементов проекта
    */
    public function delLocation($arr) {
        if(!$this->hasAccess($arr['project']))
            return 0;
        
        if(array_key_exists('point',$arr)) {
            $result = Yii::app()->db->createCommand()
                ->delete(
                    'project_city',
                    'point=:pnt AND project=:prj', 
                    array(
                        ':pnt'=>$arr['point'], 
                        ':prj'=>$arr['project']
                    )
                );
            return $result;
        }
        if(array_key_exists('location',$arr)) {
            $result = Yii::app()->db->createCommand()
                ->delete(
                    'project_city',
                    'location=:loc AND id_city=:city AND project=:prj', 
                    array(
                        ':loc'=>$arr['location'], 
                        ':city'=>$arr['city'],
                        ':prj'=>$arr['project']
                    )
                );
            return $result;
        }
        if(array_key_exists('city',$arr)) {
            $result = Yii::app()->db->createCommand()
                ->delete(
                    'project_city',
                    'id_city=:city AND project=:prj', 
                    array(
                        ':city'=>$arr['city'], 
                        ':prj'=>$arr['project']
                    )
                );
            return $result;
        }
    }
    /*
    *       Получение XLS
    */
    public function getXLSFile() {
        $index = Yii::app()->getRequest()->getParam('xls-index');
        $users = Yii::app()->getRequest()->getParam('xls-users');
        $id = Yii::app()->getRequest()->getParam('id');
        
        if(isset($index)) {
            $name = $id . '.' . (end(explode('.', $_FILES['xls']['name'])));
            $uploadfile = '/var/www/dev.prommu/uploads/' . $name;
            if (move_uploaded_file($_FILES['xls']['tmp_name'], $uploadfile)) {
               $props['project'] = $id;
               $props['title'] = 'test';
               $props['link'] = $name;
               $this->importProject($props);
            }
        }
        if(isset($users)) {
            $name = $id . '.' . (end(explode('.', $_FILES['xls']['name'])));
            $uploadfile = '/var/www/dev.prommu/uploads/' . $name;
            if (move_uploaded_file($_FILES['xls']['tmp_name'], $uploadfile)) {
               $props['project'] = $id;
               $props['title'] = 'test';
               $props['link'] = $name;
               $this->importUsers($props);
            }
        }
    }
    /*
    *       Получаем значение точки
    */
    public function getPoint($prj,$point) {
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
    /*
    *       привязка пользователя к точке
    */
    public function setPromoToPoint($arr) {
        if(!sizeof($arr['user']))
            return false;

        $prj = Yii::app()->getRequest()->getParam('id');
        $point = Yii::app()->getRequest()->getParam('point');

        $sql = Yii::app()->db->createCommand()
            ->select('*')
            ->from('project_binding')
            ->where(
                'project=:prj AND point=:pnt', 
                array(':prj'=>$prj,':pnt'=>$point)
            )
            ->queryAll();

        $arUsers = array();
        foreach ($sql as $b) { // убираем отчеканых пользователей
            $arUsers[] = $b['user'];
            if(!in_array($b['user'], $arr['user']))
                Yii::app()->db->createCommand()
                    ->delete('project_binding','id=:id', [':id'=>$b['id']]);
        }
        foreach ($arr['user'] as $user) { // добавляем новых юзеров
            if(!in_array($user, $arUsers))
                Yii::app()->db->createCommand()
                    ->insert('project_binding', array(
                        'project' => $prj,
                        'user' => $user,
                        'point' => $point
                    ));
        }
    }
    /*
    *       страница Персонал
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
    /*
    *       Список задач
    */
    public function getTaskList($prj) {
        $arRes = array();
        $sql = Yii::app()->db->createCommand()
            ->select("*")
            ->from('project_task')
            ->where('project = :prj', array(':prj' =>$prj))
            ->queryAll();

        for($i=0,$n=sizeof($sql); $i<$n; $i++)
            $arRes[strtotime($sql[$i]['date'])][$sql[$i]['point']][$sql[$i]['user']][$sql[$i]['id']] = $sql[$i];

        return $arRes;
    }
    /*
    *       Данные проекта
    */
    public function getProjectData($prj) {

        $sql = Yii::app()->db->createCommand()
            ->select("*")
            ->from('project')
            ->where('project=:prj', array(':prj'=>$prj))
            ->queryRow();

        return $sql;
    }
    /*
    *       Весь персонал
    */
    public function getProjectPromo($prj) {
        $arRes = array();
        $arP['staff'] = 'pu.project = :prj';
        $arP['values'] = array(':prj' =>$prj);

        $filter = Yii::app()->getRequest()->getParam('filter');
        if(isset($filter)) {
            $fname = Yii::app()->getRequest()->getParam('fname');
            $lname = Yii::app()->getRequest()->getParam('lname');
            if(!empty($fname))
                $arP['staff'] .= " AND r.firstname LIKE '%".$fname."%'";
            if(!empty($lname))
                $arP['staff'] .= " AND r.lastname LIKE '%".$lname."%'";
        }

        $sql = Yii::app()->db->createCommand()
            ->select(
                "pu.user, 
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
            ->where($arP['staff'], $arP['values'])
            ->queryAll(); 

        foreach ($sql as $u) {
            $id = $u['user'];
            $arRes[$id]['id_user'] = $id;
            $arRes[$id]['name'] = $u['lastname'] . ' ' . $u['firstname'];
            $arRes[$id]['src'] = ProjectStaff::getPhoto(2, $u, 'small');
            $arRes[$id]['status'] = $u['status'];
            $arRes[$id]['is_online'] = $u['is_online'];
            if(!empty($u['point']))
                $arRes[$id]['points'][] = $u['point'];
        }

        return $arRes;
    }
    /*
    *       Формирование массива задач
    */
    public function buildTaskArray($arr) {
        $arI = array();
        $fbdate = Yii::app()->getRequest()->getParam('bdate');
        $fedate = Yii::app()->getRequest()->getParam('edate');
        $filter = Yii::app()->getRequest()->getParam('filter');
        if(isset($fbdate) && isset($fedate) && isset($filter)) {
            $fbdate = strtotime($fbdate);
            $fedate = strtotime($fedate);
        }
    
        $arRes['project'] = $arr['project'];
        $arRes['tasks'] = $this->getTaskList($arr['project']['project']);

        foreach ($arr['users'] as $id => $v)
            if(sizeof($v['points'])>0)
                $arRes['users'][$v['id_user']] = $v;

        if(!sizeof($arRes['users']))
            return array('items' => $arI);

        foreach ($arr['original'] as $p)
            foreach ($arRes['users'] as $idus => $u) {
                if(!in_array($p['point'], $u['points']))
                    continue;

                $arRes['points'][$p['point']] = $p;
                $bdate = strtotime($p['bdate']);
                $edate = strtotime($p['edate']); 
                $bdate = (isset($filter) && $fbdate>$bdate) ? $fbdate : $bdate;
                $edate = (isset($filter) && $fedate<$edate) ? $fedate : $edate;

                do{
                    $arI[$bdate][$p['id_city']]['date'] = date('d.m.Y',$bdate);
                    $arI[$bdate][$p['id_city']]['city'] = $p['city'];
                    $arI[$bdate][$p['id_city']]['ismetro'] = $p['ismetro'];
                    $arI[$bdate][$p['id_city']]['users'][$idus][] = $p['point'];
                    $bdate += (60*60*24);
                }
                while($bdate <= $edate);               
            }

        ksort($arI);
        $arRes['items'] = $arI;
        $arRes['filter'] = $this->getFilter($arRes['points']);

        return $arRes;
    }
    /*
    *       Изменение задач
    */
    public function changeTask($arr) {
        $arRes = ['error' => 1, 'data' => []];
        if(!$this->hasAccess($arr['project']))
           return $arRes;

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

                    $arRes['error'] = 0;
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
                if($sql) $arRes['error'] = 0;
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
                        if($sql) $arRes['error'] = 0;
                    }
                    else {
                        $sql = Yii::app()->db->createCommand()
                            ->insert('project_task', $arNew);
                        if($sql) $arRes['error'] = 0;                      
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
                        if($sql) $arRes['error'] = 0;
                    }
                    else {
                        $sql = Yii::app()->db->createCommand()
                            ->insert('project_task', $arNew);
                        if($sql) $arRes['error'] = 0;                         
                    }
                }
                break;
            case 'delete-task': // изменение существующего
                $sql = Yii::app()->db->createCommand()
                    ->delete('project_task','id=:id', [':id'=>$arr['task']]);
                if($sql) $arRes['error'] = 0; 
                break;
        }

        return $arRes;
    }
    /*
    *
    */
    public function getProjectAppPromoTemp($prj) {
        $arRes = array();
        $idus = Share::$UserProfile->id;
        $sql = Yii::app()->db->createCommand()
            ->select(
                "pu.status, 
                pu.date,
                pc.name lname,
                pc.adres lindex,
                pc.id_city,
                pc.metro id_metro,
                pb.point,
                c.name city,
                c.ismetro ismetro,
                m.name metro")
            ->from('project_user pu')
            ->leftjoin('project_binding pb', 'pb.user=pu.user')
            ->leftjoin('project_city pc', 'pc.point=pb.point')
            ->leftjoin('city c', 'c.id_city=pc.id_city')
            ->leftjoin('metro m', 'm.id=pc.metro')
            ->where(
                'pu.project=:prj AND pu.user=:idus', 
                array(':prj' =>$prj, ':idus'=>$idus)
            )
            ->queryAll();  // поиск всех пользователей проекта

        foreach ($sql as $u) {
            $arRes[$idus]['id_user'] = $idus;
            $arRes[$idus]['status'] = $u['status'];
            if(!empty($u['point']))
                $arRes[$idus]['points'][] = $u['point'];
            if(!empty($u['id_city']))
                $arRes[$idus]['cities'][$u['id_city']] = $u['city'];
            if(!empty($u['id_metro']))
                $arRes[$idus]['metros'][$u['id_metro']] = $u['metro'];
        }

        return $arRes;
    }
    /*
    *       Изменение статуса
    */
    public function changeAppStatus() {
        $arRes['project'] = Yii::app()->getRequest()->getParam('project');
        $arRes['status'] = Yii::app()->getRequest()->getParam('status');
        $idus = Share::$UserProfile->id;
        $type = Share::$UserProfile->type;

        if(!isset($arRes['project']) || !isset($arRes['status']) || $type!=2)
            return array('status'=>0);

        if(!$this->hasAccess($arRes['project']))
            return array('status'=>0);

        Yii::app()->db->createCommand()
            ->update(
                'project_user',
                array('status'=>$arRes['status']),
                'project=:prj AND user=:idus',
                array(':prj'=>$arRes['project'], ':idus'=>$idus)
            );
        return $arRes;    
    }
    /*
    *       Получение координат по проекту
    */
    public function getСoordinates($arr) {
        $arCond = 'project=:prj';
        $arPrms[':prj'] = $arr['project'];

        if(isset($arr['user'])) {
            $arCond .= ' AND user=:user';
            $arPrms[':user'] = $arr['user'];            
        }
        if(isset($arr['point'])) {
            $arCond .= ' AND point=:point';
            $arPrms[':point'] = $arr['point'];            
        }
        if(isset($arr['date'])) {
            $arr['date'] = date('Y-m-d',$arr['date']);
            $arCond .= ' AND date(date)=:date';
            $arPrms[':date'] = $arr['date'];            
        }

        $arRes = array(); 
        $arRes = Yii::app()->db->createCommand()
            ->select("*")
            ->from('project_report')
            ->where($arCond, $arPrms)
            ->queryAll();  // поиск всех пользователей проекта  

        if(!sizeof($arRes))
            $arRes = array('error'=>true);
        return $arRes;
    }
    /*
    *       Формирование массива для маршута
    */
    public function buildRouteArray($arr) {
        $arI = array();
        
        $arRes['project'] = $arr['project'];
        // сортируем по времени
        usort($arr['original'], 
            function($a, $b){
                $t1 = strtotime($a['btime']);
                $t2 = strtotime($b['btime']);
                return ($t1 < $t2) ? -1 : 1;
            }
        );

        foreach ($arr['users'] as $id => $v)
            if(sizeof($v['points'])>0)
                $arRes['users'][$v['id_user']] = $v;

        if(!sizeof($arRes['users']))
            return array('items' => $arI);

        foreach ($arr['original'] as $p) {
            foreach ($arRes['users'] as $idus => $u) {
                if(!in_array($p['point'], $u['points']))
                    continue;

                $arRes['points'][$p['point']] = $p;
                $c = $p['id_city'];
                $date = strtotime($p['bdate']);
                do{
                    $arI[$date][$idus][$c]['date'] = date('d.m.Y',$date);
                    $arI[$date][$idus][$c]['city'] = $p['city'];
                    $arI[$date][$idus][$c]['ismetro'] = $p['ismetro'];
                    $arI[$date][$idus][$c]['points'][] = $p['point'];
                    $date += (60*60*24);
                }
                while($date <= strtotime($p['edate']));
            }
        }

        ksort($arI);
        $arRes['items'] = $arI;
        $arRes['filter'] = $this->getFilter($arRes['points']);

        return $arRes;
    }
    /*
    *       ормирование данных фильтра
    */
    public function getFilter($arr) {
        if(!count($arr))
            return array();
    
        $first = reset($arr);
        $arRes = array(
                'bdate' => $first['bdate'],
                'edate' => $first['edate'],
                'bdate-short' => $first['bdate'],
                'edate-short' => $first['edate']
            );

        foreach ($arr as $v) {
            if(strtotime($v['bdate']) < strtotime($arRes['bdate']))
                $arRes['bdate'] = $v['bdate'];

            if(strtotime($v['edate']) > strtotime($arRes['edate']))
                $arRes['edate'] = $v['edate'];

            $arRes['cities'][$v['id_city']] = array(
                    'city' => $v['city'],
                    'hasmetro' => $v['ismetro']
                );

            if(!empty($v['name']))
                $arRes['tt_name'][] = $v['name'];
            if(!empty($v['adres']))
                $arRes['tt_index'][] = $v['adres'];

            if(isset($v['id_metro']))
                $arRes['metros'][$v['id_metro']] = array(
                    'id' => $v['id_metro'],
                    'metro' => $v['metro'],
                    'id_city' => $v['id_city'],
                    'city' => $v['city']
                );
        }

        $arRes['bdate-short'] = date('d.m.y', strtotime($arRes['bdate']));
        $arRes['edate-short'] = date('d.m.y', strtotime($arRes['edate']));
        $arRes['tt_name'] = array_unique($arRes['tt_name']);
        $arRes['tt_index'] = array_unique($arRes['tt_index']);

        return $arRes;
    }
    /*
    *
    */
    public function buildReportArray($arr) {
        $project = $arr['project']['project'];
        $arI = array();
        $fbdate = Yii::app()->getRequest()->getParam('bdate');
        $fedate = Yii::app()->getRequest()->getParam('edate');
        $filter = Yii::app()->getRequest()->getParam('filter');
        if(isset($fbdate) && isset($fedate) && isset($filter)) {
            $fbdate = strtotime($fbdate);
            $fedate = strtotime($fedate);
        }
     
        $arRes['project'] = $arr['project'];
        // сортируем по времени
        usort($arr['original'], 
            function($a, $b){
                $t1 = strtotime($a['btime']);
                $t2 = strtotime($b['btime']);
                return ($t1 < $t2) ? -1 : 1;
            }
        );

        foreach ($arr['users'] as $id => $v)
            if(sizeof($v['points'])>0)
                $arRes['users'][$v['id_user']] = $v;

        if(!sizeof($arRes['users']))
            return array('items' => $arI);

        $day = 60 * 60 * 24;
        foreach ($arr['original'] as $p) {
            foreach ($arRes['users'] as $idus => $u) {
                if(!in_array($p['point'], $u['points']))
                    continue;

                $arRes['points'][$p['point']] = $p;
                $arRes['points'][$p['point']]['btime'] = date('G:i',strtotime($p['btime']));
                $arRes['points'][$p['point']]['etime'] = date('G:i',strtotime($p['etime']));

                $bdate = strtotime($p['bdate']);
                $edate = strtotime($p['edate']); 
                $bdate = (isset($filter) && $fbdate>$bdate) ? $fbdate : $bdate;
                $edate = (isset($filter) && $fedate<$edate) ? $fedate : $edate;

                do{
                    $arI[$bdate][$idus][$p['id_city']]['date'] = date('d.m.Y',$bdate);
                    $arI[$bdate][$idus][$p['id_city']]['city'] = $p['city'];
                    $arI[$bdate][$idus][$p['id_city']]['ismetro'] = $p['ismetro'];
                    $arI[$bdate][$idus][$p['id_city']]['points'][] = $p['point']; 
                    $bdate += $day;
                }
                while($bdate <= $edate);
            }
        }
        //      координаты
        $arRes['gps'] = $this->getСoordinates(['project'=>$project]);
        $arRes['tasks'] = $this->getTaskList($project);
        foreach ($arRes['gps'] as $v) {
            $u = $v['user'];
            $p = $v['point'];
            $d = date('Y-m-d 00:00:00',strtotime($v['date']));
            $d = strtotime($d);
            $arT = $arRes['gps-info'][$d][$u][$p];
            if(!count($arT['marks']))
               $arT['btime-fact'] = date('G:i',strtotime($v['date']));
            $arT['etime-fact'] = date('G:i',strtotime($v['date']));
            $ttime = strtotime($arT['etime-fact']) - strtotime($arT['btime-fact']);
            $arT['time-total'] = $ttime / 60; // минут
            $arT['moving'] = 30; // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! setting

            $arT['tasks-total'] = count($arRes['tasks'][$d][$p][$u]);
            $arT['tasks-fact'] = 0;
            foreach ($arRes['tasks'][$d][$p][$u] as $t)
                if($t['status'])
                  $arT['tasks-fact']++;  

            $arT['marks'][$v['id']] = $v;
            $arRes['gps-info'][$d][$u][$p] = $arT;
        }

        ksort($arI);
        $arRes['items'] = $arI;
        $arRes['filter'] = $this->getFilter($arRes['points']);

        return $arRes;
    }
    /*
    *
    */
    public function buildGeoArray($arr) {
        $project = $arr['project']['project'];
        $arI = array();
        $fbdate = Yii::app()->getRequest()->getParam('bdate');
        $fedate = Yii::app()->getRequest()->getParam('edate');
        $filter = Yii::app()->getRequest()->getParam('filter');
        if(isset($fbdate) && isset($fedate) && isset($filter)) {
            $fbdate = strtotime($fbdate);
            $fedate = strtotime($fedate);
        }
    
        $arRes['project'] = $arr['project'];
        // сортируем по времени
        usort($arr['original'], 
            function($a, $b){
                $t1 = strtotime($a['btime']);
                $t2 = strtotime($b['btime']);
                return ($t1 < $t2) ? -1 : 1;
            }
        );
        //      координаты
        $arRes['gps'] = $this->getСoordinates(['project'=>$project]);

        foreach ($arr['users'] as $id => $v)
            if(sizeof($v['points'])>0)
                $arRes['users'][$v['id_user']] = $v;

        if(!sizeof($arRes['users']))
            return array('items' => $arI);

        foreach ($arr['original'] as $p)
            foreach ($arRes['users'] as $idus => $u) {
                if(!in_array($p['point'], $u['points']))
                    continue;

                $arRes['points'][$p['point']] = $p;
                $bdate = strtotime($p['bdate']);
                $edate = strtotime($p['edate']); 
                $bdate = (isset($filter) && $fbdate>$bdate) ? $fbdate : $bdate;
                $edate = (isset($filter) && $fedate<$edate) ? $fedate : $edate;
                do{
                    $arI[$bdate][$p['id_city']]['date'] = date('d.m.Y',$bdate);
                    $arI[$bdate][$p['id_city']]['city'] = $p['city'];
                    $arI[$bdate][$p['id_city']]['ismetro'] = $p['ismetro'];
                    if(!isset($arI[$bdate][$p['id_city']]['users'][$idus]['points']))
                        $arI[$bdate][$p['id_city']]['users'][$idus]['points'] = 1;
                    else
                        $arI[$bdate][$p['id_city']]['users'][$idus]['points']++;

                    foreach ($arRes['gps'] as $v) {
                        $d = date('Y-m-d 00:00:00',strtotime($v['date']));
                        $d = strtotime($d);
                        if($bdate==$d && $p['point']==$v['point'] && $idus==$v['user']) {
                            if(!isset($arI[$bdate][$p['id_city']]['users'][$idus]['plan'])) {
                                $arI[$bdate][$p['id_city']]['users'][$idus]['plan'] = $p['btime'];
                                $arI[$bdate][$p['id_city']]['users'][$idus]['fact'] = date('H:i',strtotime($v['date']));
                                $d1 = strtotime($p['bdate'] . ' ' . $p['btime'] . ':00'); 
                                $d2 = strtotime($v['date']);
                                $arI[$bdate][$p['id_city']]['users'][$idus]['diff'] = $d1 < $d2;                         
                            }
                            $arI[$bdate][$p['id_city']]['users'][$idus]['last-point'] = $v['point'];
                        }
                    }


                    $bdate += (60*60*24);
                }
                while($bdate <= $edate);
            }

        ksort($arI);
        $arRes['items'] = $arI;
        $arRes['filter'] = $this->getFilter($arRes['points']);

        return $arRes;
    }
}