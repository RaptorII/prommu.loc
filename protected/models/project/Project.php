<?php
/**
 * Created by Grescode
 * Date: 28.07.18
 */

class Project extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'project';
    }

    public function behaviors(){
        return array(
            'ProjectTask' => array('class' => 'ProjectTask'),
            'ProjectStaff' => array('class' => 'ProjectStaff'),
            'ProjectIndex' => array('class' => 'ProjectIndex')
        );
    }


    public function createProject($props){
        $project = time().rand(11111,99999);
        ///Обработка адресной программы
        $idus = Share::$UserProfile->id;
        $res = Yii::app()->db->createCommand()
                        ->insert('project', array(
                            'project' => $project,
                            'id_user' => $idus,
                            'name' => $props['name'],
                            'crdate' => date('Y-m-d h-i-s'),
                            'vacancy' => $props['vacancy']
                        ));
    
        $this->recordIndex($props, $project, true);
        $this->recordStaff($props, $project);

        return $project;
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
                    p.vacancy, 
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
            $arRes['items'][$p]['vacancy'] = $v['vacancy'];

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

    public function getProject($prj){
        $arRes['original'] = $this->getIndex($prj);
        $arRes['location'] = $this->buildIndexArray($arRes['original']);
        $arRes['filter'] = $this->buildIndexFilterArray($arRes['original']);
        $arRes['users'] = $this->getAllStaffProject($prj);
        $arRes['users'] = $this->buildStaffArray($arRes['users']);
        $arRes['task-counters'] = $this->getTasks($prj,$onlyCounters=true);
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
    public function hasAccess($prj=0){
        $idus = Share::$UserProfile->id;
        $t = Share::$UserProfile->type;
        $t==3 && $conditions = 'id_user=:idus';
        $t==2 && $conditions = 'user=:idus';
        $values = array(':idus' => $idus);

        if($prj>0) {
            $conditions .= ' AND project=:prj';
            $values[':prj'] = $prj;
        }

        if($t==3) {
            $result = Yii::app()->db->createCommand()
                        ->select('id')
                        ->from('project')
                        ->where($conditions, $values)
                        ->queryAll();
        }
        if($t==2) {
            $result = Yii::app()->db->createCommand()
                        ->select('id')
                        ->from('project_user')
                        ->where($conditions, $values)
                        ->queryAll();
        }
            
        return sizeof($result);
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
    *       Формирование массива задач
    */
    public function buildTaskPageArray($arr) {
        $arI = array();
        $fbdate = Yii::app()->getRequest()->getParam('bdate');
        $fedate = Yii::app()->getRequest()->getParam('edate');
        $filter = Yii::app()->getRequest()->getParam('filter');
        if(isset($fbdate) && isset($fedate) && isset($filter)) {
            $fbdate = strtotime($fbdate);
            $fedate = strtotime($fedate);
        }
    
        $arRes['project'] = $arr['project'];
        $arRes['tasks'] = $this->getTasks($arr['project']['project']);

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
        $arRes['tasks'] = $this->getTasks($project);

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
                    $arI[$bdate][$p['id_city']]['users'][$idus]['points'][] = $p['point'];

                    foreach ($arRes['gps'] as $v) {
                        $d = date('Y-m-d 00:00:00',strtotime($v['date']));
                        $d = strtotime($d);
                        if($bdate==$d && $p['point']==$v['point'] && $idus==$v['user']) {
                           // if(!isset($arI[$bdate][$p['id_city']]['users'][$idus]['plan'])) {
                                $arI[$bdate][$p['id_city']]['users'][$idus]['plan_start'] = $p['btime'];
                                $arI[$bdate][$p['id_city']]['users'][$idus]['plan_end'] = $p['etime'];
                                $arI[$bdate][$p['id_city']]['users'][$idus]['fact'] = date('H:i',strtotime($v['date']));
                                $d1 = strtotime($p['bdate'] . ' ' . $p['btime'] . ':00'); 
                                $d2 = strtotime($v['date']);
                                $arI[$bdate][$p['id_city']]['users'][$idus]['diff'] = $d1 < $d2;                         
                          //  }
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
    /**
     * @param $idus number - user ID
     * @return staff data
     * Выборка без пагинации по всем проектам
     */
    public function getAllProjectsId($idus) {
        if(!$idus)
            return false;

        $sql = Yii::app()->db->createCommand()
                ->select('project, name')
                ->from('project')
                ->where('id_user=:idus', array(':idus' => $idus))
                ->queryAll();

        return $sql;
    }

    /**
     * 
     */
    public function buildReportArrayTemp($arr) {
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
                    $arI[$idus][$bdate][$p['id_city']]['date'] = date('d.m.Y',$bdate);
                    $arI[$idus][$bdate][$p['id_city']]['city'] = $p['city'];
                    $arI[$idus][$bdate][$p['id_city']]['ismetro'] = $p['ismetro'];
                    $arI[$idus][$bdate][$p['id_city']]['points'][] = $p['point']; 
                    $bdate += $day;
                }
                while($bdate <= $edate);
            }
        }
        //      координаты
        $arRes['gps'] = $this->getСoordinates(['project'=>$project]);
        $arRes['tasks'] = $this->getTasks($project);

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

        foreach ($arI as $k => $v) {
            ksort($v);
            $arRes['items'][$k] = $v;
        }
        $arRes['filter'] = $this->getFilter($arRes['points']);

        return $arRes;
    }
}