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
    public function getProjectEmployer(){
        $idus = Share::$UserProfile->id;
         $result = Yii::app()->db->createCommand()
            ->select("*")
            ->from('project p')
            ->where('p.id_user = :idus', array(':idus' =>$idus))
            ->order('p.crdate desc')
            ->queryAll();
            
        return $result;
    }
    /*
    *       Фильтр для Адресной программы
    */
    public function getIndexFilter($prj) {
        $arRes['conditions'] = 'pc.project=:prj';
        $arRes['values'] = array(':prj' =>$prj);

        $city = Yii::app()->getRequest()->getParam('city');
        $bdate = Yii::app()->getRequest()->getParam('bdate');
        $edate = Yii::app()->getRequest()->getParam('edate');
        $point = Yii::app()->getRequest()->getParam('point');
        $filter = Yii::app()->getRequest()->getParam('filter');

        if(!isset($filter))
            return $arRes;
        if($city>0) {
            $arRes['conditions'] .= ' AND pc.id_city=:city';
            $arRes['values'][':city'] = $city;
        }
        if(isset($bdate) && isset($edate)) {
            $arRes['conditions'] .= ' AND pc.bdate>=:bdate AND pc.edate<=:edate';
            $arRes['values'][':bdate'] = date('Y.m.d', strtotime($bdate));
            $arRes['values'][':edate'] = date('Y.m.d', strtotime($edate));
        }
        if($point>0) {
            $arRes['conditions'] .= ' AND pc.point=:point';
            $arRes['values'][':point'] = $point;
        }

        return $arRes;
    }
    /*
    *       Список Адресной программы
    */
    public function getAdresProgramm($project){
        $filter = $this->getIndexFilter($project);
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
            ->where($filter['conditions'], $filter['values'])
            ->order('pc.bdate desc')
            ->queryAll();

        return $this->buildAdressArray($sql); 
    }
    /*
    *       подсчет пользователей проекта
    */
    public function getStaffProjectCnt($prj) {
        $filter = $this->getStaffFilter($prj);
        return Yii::app()->db->createCommand()
            ->select("COUNT(DISTINCT(pu.id))")
            ->from('project_user pu')
            ->leftjoin('resume r', 'r.id_user=pu.user')
            ->leftjoin('project_binding pb', 'pb.user=pu.user')
            ->leftjoin('project_city pc', 'pc.point=pb.point')
            ->leftjoin('city c', 'c.id_city=pc.id_city')
            ->leftjoin('metro m', 'm.id=pc.metro')
            ->where($filter['conditions'], $filter['values'])
            ->queryScalar();
    }
    /*
    *       Фильтр для персонала
    */
    public function getStaffFilter($prj) {
        $arRes['conditions'] = 'pu.project = :prj';
        $arRes['values'] = array(':prj' =>$prj);

        $fname = Yii::app()->getRequest()->getParam('fname');
        $lname = Yii::app()->getRequest()->getParam('lname');
        $status = Yii::app()->getRequest()->getParam('status');
        $point = Yii::app()->getRequest()->getParam('haspoint');
        $city = Yii::app()->getRequest()->getParam('city');
        $tname = Yii::app()->getRequest()->getParam('tt_name');
        $tindex = Yii::app()->getRequest()->getParam('tt_index');
        $metro = Yii::app()->getRequest()->getParam('metro');
        $filter = Yii::app()->getRequest()->getParam('filter');

        if(!isset($filter))
            return $arRes;
        if(!empty($fname)) {
            $arRes['conditions'] .= " AND r.firstname LIKE '%".$fname."%'";
        }
        if(!empty($lname)) {
            $arRes['conditions'] .= " AND r.lastname LIKE '%".$lname."%'";
        }
        if($status>0) {
            $arRes['conditions'] .= " AND pu.status=" . ($status==1 ? 1 : 0);
        }
        if($point>0) {
            $arRes['conditions'] .= ($point==1
                ? " AND pb.point IS NOT NULL"
                : " AND pb.point IS NULL");
        }
        if($city>0) {
            $arRes['conditions'] .= " AND pc.id_city=:city";
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

        return $arRes;
    }
    /*
    *       Персонал
    */
    public function getStaffProject($prj){
        $filter = $this->getStaffFilter($prj);

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
            ->where($filter['conditions'], $filter['values'])
            ->order('pu.user desc')
            ->limit($this->limit)
            ->offset($this->offset)
            ->queryAll(); // поиск всех пользователей проекта

        if(!sizeof($sql))
            return array();

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
            $arRes['users'][$id]['src'] = DS . MainConfig::$PATH_APPLIC_LOGO . DS . 
                ($u['photo'] 
                    ? ($u['photo'] . '100.jpg')
                    : ($u['isman'] ? MainConfig::$DEF_LOGO : MainConfig::$DEF_LOGO_F)
                );
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
                ->select('pc.id, pc.user, pc.status, pc.project, pc.firstname, pc.lastname, pc.email, pc.phone')
                ->from('project_user pc')
                ->where('pc.email = :email', array(':email' =>$sheet_array[$i]['C']))
                ->order('pc.date desc')
                ->queryRow();   
                if($data['email']){
                    $point = $sheet_array[$i]['I'];
                    
                     Yii::app()->db->createCommand()
                        ->update('project_user', array(
                            'project' => $project,
                            'user' => rand(111,333),
                            'firstname' =>  $sheet_array[$i]['A'],
                            'lastname' =>  $sheet_array[$i]['B'],
                            'phone' =>  $sheet_array[$i]['D'],
                            //'point' => $sheet_array[$i]['E'] !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
                     ), 'email = :email', array(':email' => $sheet_array[$i]['C']));
                
                } else {
                    $res = Yii::app()->db->createCommand()
                        ->insert('project_user', array(
                            'project' => $project,
                            'user' => rand(111,333),
                            'firstname' =>  $sheet_array[$i]['A'],
                            'lastname' =>  $sheet_array[$i]['B'],
                            'email' =>  $sheet_array[$i]['C'],
                            'phone' =>  $sheet_array[$i]['D'],
                        ));   
                }
        }


        return $location;
    } 
    
    
    public function exportUsers($project){
        Yii::import('ext.yexcel.Yexcel');
        
        $project = Yii::app()->getRequest()->getParam('project');
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
        else {
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
        $sql = Yii::app()->db->createCommand()
            ->select(
                "pu.user, 
                pu.status,
                pb.point, 
                r.firstname, 
                r.lastname,
                r.photo,
                r.isman")
            ->from('project_user pu')
            ->leftjoin('resume r', 'r.id_user=pu.user')
            ->leftjoin('project_binding pb', 'pb.user=pu.user')
            ->where('pu.project=:prj', array(':prj'=>$prj))
            ->queryAll(); 

        foreach ($sql as $u) {
            $id = $u['user'];
            $arRes[$id]['id_user'] = $id;
            $arRes[$id]['name'] = $u['lastname'] . ' ' . $u['firstname'];
            $arRes[$id]['src'] = DS . MainConfig::$PATH_APPLIC_LOGO . DS . 
                ($u['photo'] 
                    ? ($u['photo'] . '100.jpg')
                    : ($u['isman'] ? MainConfig::$DEF_LOGO : MainConfig::$DEF_LOGO_F)
                );
            $arRes[$id]['status'] = $u['status'];
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
        
        $arRes['project'] = $arr['project'];
        $arRes['tasks'] = $this->getTaskList($arr['project']['project']);

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
                    $arI[$date][$c]['date'] = date('d.m.Y',$date);
                    $arI[$date][$c]['city'] = $p['city'];
                    $arI[$date][$c]['ismetro'] = $p['ismetro'];
                    $arI[$date][$c]['users'][$idus][] = $p['point'];
                    $date += (60*60*24);
                }
                while($date <= strtotime($p['edate']));
            }
        }

        ksort($arI);
        $arRes['items'] = $arI;
        $arRes['filter'] = $this->buildAdressArray($arr['original'])['filter'];

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
}