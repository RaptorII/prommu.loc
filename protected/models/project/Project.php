ф<?php
/**
 * Created by Grescode
 * Date: 28.07.18
 */

class Project extends CActiveRecord
{
    public $XLS_UPLOAD_PATH = '/var/www/dev.prommu/uploads/';
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
    *   Геокодирование
    */
    
    public function getCoords($geocode)
    {
        $response = json_decode(file_get_contents('https://geocode-maps.yandex.ru/1.x/?format=json&geocode='
                . $geocode));
        $coordsStr = $response->response->
            GeoObjectCollection
            ->featureMember[0]
            ->GeoObject
            ->Point
            ->pos;
     
        $coords = explode(' ', $coordsStr);
         
        return [
            'la' => $coords[1],
            'lo' => $coords[0],
        ];
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
    
    public function confirmXls($props){
        $arRes['error'] = true; 
        $link = $props['link'];
        $project = $props['project'];
        Yii::import('ext.yexcel.Yexcel');
        $sheet_array = Yii::app()->yexcel->readActiveSheet($this->XLS_UPLOAD_PATH . $link);
        $xls = reset($sheet_array);

        if( $props['type'] === 'xls-index')
        {
            $city = "Город";
            $location = "Локация";
            $street = "Улица";
            $home = "Дом";
            $build = "Здание";
            $str = "Строение";
            $corps = "Корпус";
            $date = "Дата работы";
            $time = "Время работы";

            if($xls['A'] == $city && $xls['B'] == $location &&
                $xls['C'] == $street && $xls['D'] == $home &&
                $xls['E'] == $build && $xls['F'] == $str &&
                $xls['G'] == $corps && $xls['H'] == $time && $xls['I'] == $date)
            {
                $arRes['error'] = false; 
            }
            
        }
        elseif($props['type'] === 'xls-staff')
        {
            $firstname = "Имя";
            $lastname = "Фамилия";
            $email = "Электронная почта";
            $phone = "Телефон";
            $location = "Локации";
            
            if($xls['A'] == $firstname && $xls['B'] == $lastname &&
                $xls['C'] == $email && $xls['D'] == $phone &&
                $xls['E'] == $location)
            {    
                $arRes['error'] = false;    
            }
            
        }
        
        return $arRes;
    } 
    
    
    public function importProject($props){
         $link = $props['link'];
        $project = $props['project'];
        Yii::import('ext.yexcel.Yexcel');
        $sheet_array = Yii::app()->yexcel->readActiveSheet($this->XLS_UPLOAD_PATH . $link);
        
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
                
                $bdate = explode("-", $sheet_array[$i]['I'])[0];
                $edate = explode("-", $sheet_array[$i]['I'])[1];
                   
                $bdate = str_replace(".", "-", $bdate);
                $edate = str_replace(".", "-", $edate);
                $adres = $sheet_array[$i]['A'].' ул.'.$sheet_array[$i]['C'].' дом '.$sheet_array[$i]['D'].' здание'.$sheet_array[$i]['E'].' строение '.$sheet_array[$i]['F'].' строение '.$sheet_array[$i]['G'];
                $location = $this->getCoords($adres);
                if($sheet_array[$i]['J'] != ''){
                    $point = $sheet_array[$i]['J'];
                    
                     Yii::app()->db->createCommand()
                        ->update('project_city', array(
                            'project' => $project,
                            'name' =>  $sheet_array[$i]['B'],
                            'adres' => $sheet_array[$i]['C'],
                            'house' => $sheet_array[$i]['D'],
                            'building' => $sheet_array[$i]['E'],
                            'construction' => $sheet_array[$i]['F'],
                            'corps' => $sheet_array[$i]['G'],
                            'id_city' => $city['id_city'],
                            'btime' =>  explode("-", $sheet_array[$i]['H'])[0],
                            'etime' =>  explode("-", $sheet_array[$i]['H'])[1],
                            'bdate' => $bdate,
                            'edate' =>  $edate,
                            'latitude' => $location['la'],
                            'longitude' => $location['lo'],
                     ), 'point = :point', array(':point' => $point));
                
                } else {
                    $res = Yii::app()->db->createCommand()
                        ->insert('project_city', array(
                           'project' => $project,
                            'name' =>  $sheet_array[$i]['B'],
                            'adres' => $sheet_array[$i]['C'],
                            'house' => $sheet_array[$i]['D'],
                            'building' => $sheet_array[$i]['E'],
                            'construction' => $sheet_array[$i]['F'],
                            'corps' => $sheet_array[$i]['G'],
                            'id_city' => $city['id_city'],
                            'btime' =>  explode("-", $sheet_array[$i]['I'])[0],
                            'etime' =>  explode("-", $sheet_array[$i]['I'])[1],
                            'bdate' => $bdate,
                            'edate' =>  $edate,
                            'latitude' => $location['la'],
                            'longitude' => $location['lo'],
                        ));   
                }
        }


        return $location;
    } 
    
    public function importUsers($props){
        $link = $props['link'];
        $project = $props['project'];
        Yii::import('ext.yexcel.Yexcel');
        
        $sheet_array = Yii::app()->yexcel->readActiveSheet($this->XLS_UPLOAD_PATH . $link);
        
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
            ->select('pc.id, pc.name, pc.adres, pc.id_city, c.name city, pc.bdate, pc.edate, pc.btime, pc.etime, pc.project, pc.point, pc.corps,
            pc.construction, pc.building, pc.house')
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
            $uploadfile = $this->XLS_UPLOAD_PATH . $name;
            if (move_uploaded_file($_FILES['xls']['tmp_name'], $uploadfile)) {
               $props['project'] = $id;
               $props['title'] = 'test';
               $props['link'] = $name;
               $this->importProject($props);
            }
        }
        if(isset($users)) {
            $name = $id . '.' . (end(explode('.', $_FILES['xls']['name'])));
            $uploadfile = $this->XLS_UPLOAD_PATH . $name;
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
        $arRes['items'] = $arI;
        $arRes['tasks'] = $this->getTasks($arr['project']['project']);

        foreach ($arr['users'] as $id => $v)
            if(sizeof($v['points'])>0)
                $arRes['users'][$v['id_user']] = $v;

        if(!sizeof($arRes['users']))
            return $arRes;

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
                    if(!in_array($p['point'], $arI[$bdate][$p['id_city']]['users'][$idus]))
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
                pc.latitude,
                pc.longitude,
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
        
        $point = 'project=:prj';
        $arPoint[':prj'] = $arr['project'];
        
        
        if(isset($arr['user'])) {
            $arCond .= ' AND user=:user';
            $arPrms[':user'] = $arr['user'];            
        }
        if(isset($arr['point'])) {
            $arCond .= ' AND point=:point';
            $point .=' AND point=:point';
            $arPrms[':point'] = $arr['point'];  
            $arPoint[':point'] = $arr['point'];
        }
        if(isset($arr['date'])) {
            $dates = $arr['date'];
            $arr['date'] = date('Y-m-d',$arr['date']);
            $arCond .= ' AND date(date)=:date';
            $arPrms[':date'] = $arr['date'];   
        }

        $arRes = array(); 
        $arRes['fact'] = Yii::app()->db->createCommand()
            ->select("*")
            ->from('project_report')
            ->where($arCond, $arPrms)
            ->queryAll();  // поиск всех пользователей проекта 
        
         $arRest['plane']= Yii::app()->db->createCommand()
            ->select("*")
            ->from('project_city')
            ->where($point, $arPoint)
            ->queryAll(); 
        file_put_contents('test.txt', date('d.m.Y H:i')."\t".var_export($arRest['plane'],1)."\n", FILE_APPEND | LOCK_EX);
        for($i = 0; $i < count($arRest['plane']); $i ++){
            file_put_contents('time.txt', date('d.m.Y H:i')."\t".strtotime($arRest['plane'][$i]['edate']).' ---'.strtotime($arRest['plane'][$i]['bdate']).'---'.$dates."\n", FILE_APPEND | LOCK_EX);
            if(strtotime($arRest['plane'][$i]['edate']) > $dates && 
               strtotime($arRest['plane'][$i]['bdate']) < $dates){
                   $arRes['plane'][] = $arRest['plane'][$i];
                   file_put_contents('plane.txt', date('d.m.Y H:i')."\t".var_export($arRest['plane'][$i],1)."\n", FILE_APPEND | LOCK_EX);
            }
        }
            
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
        $arRes['items'] = $arI;

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
            return $arRes;

        foreach ($arr['original'] as $p) {
            foreach ($arRes['users'] as $idus => $u) {
                if(!in_array($p['point'], $u['points']))
                    continue;

                $arRes['points'][$p['point']] = $p;
                $c = $p['id_city'];
                $date = strtotime($p['bdate']);
                do{
                    $arI[$date]['date'] = date('d.m.Y',$date);
                    $arI[$date]['cities'][$c]['city']['city'] = $p['city'];
                    $arI[$date]['cities'][$c]['city']['ismetro'] = $p['ismetro'];
                    if(!in_array($p['point'], $arI[$date]['cities'][$c]['user'][$idus]['points']))
                        $arI[$date]['cities'][$c]['user'][$idus]['points'][] = $p['point'];
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
        $arI = array();
        $date = date('Y-m-d');
        $arRes['project'] = $arr['project'];
        $arRes['items'] = $arI;
        $arRes['date'] = date('d.m.Y');
        $arRes['unix'] = strtotime($date . ' 00:00:00');

        // сортируем по времени
        usort($arr['original'], 
            function($a, $b){
                $t1 = strtotime($a['btime']);
                $t2 = strtotime($b['btime']);
                return ($t1 < $t2) ? -1 : 1;
            }
        );

        $arRes['id_user'] = filter_var(
                        Yii::app()->getRequest()->getParam('user_id'),
                        FILTER_SANITIZE_NUMBER_INT
                    );
        $arRes['id_point'] = filter_var(
                        Yii::app()->getRequest()->getParam('point'),
                        FILTER_SANITIZE_NUMBER_INT
                    );
        // проверяем наличие данных для карточки
        if(
            (!empty($arRes['id_user']) && empty($arRes['id_point']))
            ||
            (!empty($arRes['id_user']) && !array_key_exists($arRes['id_user'],$arr['users']))
            ||
            (!empty($arRes['id_point']) && !sizeof($arr['original']))
        ) {
            return array('error' => true);
        }

        //      координаты
        $arRes['gps'] = $this->getСoordinates(['project'=>$arr['project']['project']]);

        // собираем данные для карточки
        if(!empty($arRes['id_user']) && !empty($arRes['id_point'])) {
            if(!sizeof($arr['users'][$arRes['id_user']]['points']))
                return array('error' => true);
            $arRes['user'] = $arr['users'][$arRes['id_user']];
          
            foreach ($arr['original'] as $p) {
                foreach ($arRes['gps'] as $v) {
                    $d = date('Y-m-d 00:00:00',strtotime($v['date']));
                    $d = strtotime($d);
                    if($arRes['unix']==$d && $p['point']==$v['point'] && $arRes['id_user']==$v['user']) {
                        if(!isset($arRes['item']['bfact'])) {
                            $arRes['item']['bfact'] = date('G:i',strtotime($v['date']));
                            $d1 = strtotime($date . ' ' . $p['btime'] . ':00');
                            $d2 = strtotime($v['date']);
                            $d3 = strtotime($date . ' ' . $p['etime'] . ':00');
                            $arRes['item']['is-lateness'] = $d1 < $d2; // опоздание
                            $arRes['item']['is-missed'] = $d3 < $d2; // пропуск
                            if($arRes['item']['is-lateness'])
                                $arRes['item']['time-lateness'] = ($d2 - $d1) / 60;
                            $curTime = new DateTime();
                            $bfact = new DateTime($v['date']);
                            $tActive = $curTime->diff($bfact);
                            if($tActive)
                                $arRes['item']['time-isactive'] = $tActive->h.':'.str_pad($tActive->i, 2, "0", STR_PAD_LEFT);
                        }
                        $arRes['item']['last-point'] = $v['point'];
                    }
                }
                $arRes['item']['bplan'] = date('G:i',strtotime($p['btime']));
                $arRes['item']['eplan'] = date('G:i',strtotime($p['etime']));
                $arRes['item']['efact'] = date('H:i',strtotime($v['date']));
                $arRes['tasks'] = $this->getTasks($arr['project']['project']);          
                $arRes['point'] = $p;
            }
            return $arRes;
        }

        foreach ($arr['users'] as $id => $v)
            if(sizeof($v['points'])>0)
                $arRes['users'][$v['id_user']] = $v;

        if(!sizeof($arRes['users']))
            return $arRes;

        $fStatus = filter_var(
                            Yii::app()->getRequest()->getParam('user_status'), // 0,1,2
                            FILTER_SANITIZE_NUMBER_INT
                        );
        $fStatus = intval($fStatus);
        $arU = [0=>[],1=>[],2=>[]]; // 0=>all, 1=>active, 2=>noactive
        foreach ($arr['original'] as $p)
            foreach ($arRes['users'] as $idus => $u) {
                if(!in_array($p['point'], $u['points']))
                    continue;

                !in_array($idus, $arU[0]) && array_push($arU[0], $idus);
                $city = $p['id_city'];
                foreach ($arRes['gps'] as $v) {
                    $d = date('Y-m-d 00:00:00',strtotime($v['date']));
                    $d = strtotime($d);
                    if($arRes['unix']==$d && $p['point']==$v['point'] && $idus==$v['user']) {
                        !in_array($idus, $arU[1]) && array_push($arU[1], $idus);
                        if( $fStatus==2 )
                            continue;

                        if(!isset($arI[$city]['users'][$idus]['bfact'])) {
                            $arI[$city]['users'][$idus]['bfact'] = date('G:i',strtotime($v['date']));
                            $d1 = strtotime($date . ' ' . $p['btime'] . ':00');
                            $d2 = strtotime($v['date']);
                            $d3 = strtotime($date . ' ' . $p['etime'] . ':00');
                            $arI[$city]['users'][$idus]['is-lateness'] = $d1 < $d2; // опоздание
                            $arI[$city]['users'][$idus]['is-missed'] = $d3 < $d2; // пропуск

                            $curTime = new DateTime();
                            $bfact = new DateTime($v['date']);
                            $tActive = $curTime->diff($bfact);
                            if($tActive)
                                $arI[$city]['users'][$idus]['time-isactive'] = $tActive->h.':'.str_pad($tActive->i, 2, "0", STR_PAD_LEFT);
                        }
                        $arI[$city]['users'][$idus]['bplan'] = date('G:i',strtotime($p['btime']));
                        $arI[$city]['users'][$idus]['eplan'] = date('G:i',strtotime($p['etime']));
                        $arI[$city]['users'][$idus]['efact'] = date('H:i',strtotime($v['date']));
                        $arI[$city]['users'][$idus]['last-point'] = $v['point'];
                    }
                }
                !in_array($idus, $arU[1]) && array_push($arU[2], $idus);

                if(in_array($idus, $arU[$fStatus])) {
                    if(!in_array($p['point'], $arI[$city]['users'][$idus]['points']))
                        $arI[$city]['users'][$idus]['points'][] = $p['point'];
                    $arI[$city]['date'] = date('d.m.Y');
                    $arI[$city]['city'] = $p['city'];
                    $arI[$city]['ismetro'] = $p['ismetro'];
                }

                $arRes['points'][$p['point']] = $p;
            }
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
    public function buildReportArrayNew($arr) {
        $project = $arr['project']['project'];
        $arI = array();
        $fbdate = Yii::app()->getRequest()->getParam('bdate');
        $fedate = Yii::app()->getRequest()->getParam('edate');
        $filter = Yii::app()->getRequest()->getParam('filter');
        $arEvents = Yii::app()->getRequest()->getParam('event_status');
        /* $arEvents = array()
            1 =>    'План прибытия',
            2 =>    'Факт Прибытия',
            3 =>    'План убытия',
            4 =>    'Факт убытия',
            5 =>    'Пробыл на ТТ',
            6 =>    'Опоздания',
            7 =>    'Отмечен на ТТ',
            8 =>    'Не отмечен на ТТ'    
        */
        $isUserLateness = in_array(6, $arEvents) ?: false; // 'Опоздания'
        $isUserChecked = in_array(7, $arEvents) ?: false; // 'Отмечен на ТТ'
        $isUserNoChecked = in_array(8, $arEvents) ?: false; // 'Не отмечен на ТТ'
        
        if(isset($fbdate) && isset($fedate) && isset($filter)) {
            $fbdate = strtotime($fbdate);
            $fedate = strtotime($fedate);
        }
     
        $arRes['project'] = $arr['project'];
        $arRes['items'] = $arI;
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
            return $arRes;

        //      координаты
        $arRes['gps'] = $this->getСoordinates(['project'=>$project]);
        $arRes['tasks'] = $this->getTasks($project);
        $arGPS = array(); // временный массив для фильтра
        $arTime = array(); // временный массив для фильтра
        foreach ($arRes['gps'] as $v) {
            $u = $v['user'];
            $p = $v['point'];
            $d = date('Y-m-d 00:00:00',strtotime($v['date']));
            $d = strtotime($d);
            // тестовый массив
            if(!in_array($v['point'], $arGPS[$v['user']][$d]))
                $arGPS[$v['user']][$d][] = $v['point'];
            //
            $arT = $arRes['gps-info'][$u][$d][$p];
            if(!count($arT['marks'])) // это значит, что сейчас первый проход - время начала(первый чек)
            {
               $arT['btime-fact'] = date('G:i',strtotime($v['date']));
               $arTime[$v['user']][$d][$v['point']] = date('H:i:00',strtotime($v['date']));
            }
            else // если есть отметки - значит это уже не первый проход, и можно записать в время окончания
            {
                $arT['etime-fact'] = date('G:i',strtotime($v['date']));
            }
            if(isset($arT['btime-fact']) && isset($arT['etime-fact']))
            {
                $ttime = (strtotime($arT['etime-fact']) - strtotime($arT['btime-fact'])) / 60; // минут
                if($ttime > 60)
                    $arT['time-total'] = floor($ttime/60) . ':' . ($ttime%60);
                else
                    $arT['time-total'] = $ttime;                
            }

            $arT['moving'] = 15; // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! setting

            $arT['tasks-total'] = count($arRes['tasks'][$d][$p][$u]);
            $arT['tasks-fact'] = 0;
            foreach ($arRes['tasks'][$d][$p][$u] as $t)
                if($t['status'])
                  $arT['tasks-fact']++;  

            $arT['marks'][$v['id']] = $v;
            $arRes['gps-info'][$u][$d][$p] = $arT;
        }

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
                    $filterLike = true;
                    if($isUserChecked && !in_array($p['point'], $arGPS[$idus][$bdate])) // Отмечен на ТТ
                       $filterLike = false;
                    if($isUserNoChecked && in_array($p['point'], $arGPS[$idus][$bdate])) // Не отмечен на ТТ
                       $filterLike = false;
                    $isUserChecked && $isUserNoChecked && $filterLike = true;
                    if($isUserLateness) // при наличии отметки проверяем на опоздание
                    {
                        if(isset($arTime[$idus][$bdate][$p['point']]))
                        {
                            $d1 = strtotime($p['btime'] . ':00');
                            $d2 = strtotime($arTime[$idus][$bdate][$p['point']]);
                            $filterLike = $d1 < $d2; // опоздание                           
                        }
                        else
                            $filterLike = false;
                    }

                    if($filterLike)
                    {
                        $arI[$idus][$bdate][$p['id_city']]['date'] = date('d.m.Y',$bdate);
                        $arI[$idus][$bdate][$p['id_city']]['city'] = $p['city'];
                        $arI[$idus][$bdate][$p['id_city']]['ismetro'] = $p['ismetro'];
                        if(!in_array($p['point'], $arI[$idus][$bdate][$p['id_city']]['points']))
                            $arI[$idus][$bdate][$p['id_city']]['points'][] = $p['point'];                       
                    }
                    $bdate += $day;
                }
                while($bdate <= $edate);
            }
        }

        foreach ($arI as $k => $v) {
            ksort($v);
            $arRes['items'][$k] = $v;
        }
        $arRes['filter'] = $this->getFilter($arRes['points']);

        return $arRes;
    }
}