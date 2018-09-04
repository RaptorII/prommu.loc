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
    
        //*Обрабатываем входящие данные
            for($i = 0; $i < count($props['city']); $i++){
                $cloud['city'][$i] = $props['city'][$i];
                $j = 0;
                foreach($props['lindex'][$props['city'][$i]] as $key => $value){
                    $cloud['lindex'][$props['city'][$i]][$j] = $value;
                    $j++;
                }

                $k = 0;
                foreach($props['lname'][$props['city'][$i]] as $key => $value){
                    $cloud['lname'][$props['city'][$i]][$k] = $value;
                    $k++;
                }
                
                $k = 0;
                foreach($props['metro'][$props['city'][$i]] as $key => $value){
                    $cloud['metro'][$props['city'][$i]][$k] = $value;
                    $k++;
                }
                
                $s = 0;
                $j = 0;
                foreach($props['bdate'][$props['city'][$i]] as $key => $value){
                    $s = 0;
                    foreach($props['bdate'][$props['city'][$i]][$key] as $keys => $values){
                        $cloud['bdate'][$props['city'][$i]][$j][$s] = $values;
                        
                        $s++;
                    }
                    $j++;
                }
                
          
                $j = 0;
                foreach($props['edate'][$props['city'][$i]] as $key => $value){
                    $s = 0;
                    foreach($props['edate'][$props['city'][$i]][$key] as $keys => $values){
                        $cloud['edate'][$props['city'][$i]][$j][$s] = $values;
                        $s++;
                    }
                    $j++;
                }
                
                $s = 0;
                $j = 0;
                foreach($props['btime'][$props['city'][$i]] as $key => $value){
                    $s = 0;
                    foreach($props['btime'][$props['city'][$i]][$key] as $keys => $values){
                        $cloud['btime'][$props['city'][$i]][$j][$s] = $values;
                        $s++;
                    }
                    $j++;
                }
                
                $s = 0;
                $j = 0;
                foreach($props['etime'][$props['city'][$i]] as $key => $value){
                    $s = 0;
                    foreach($props['etime'][$props['city'][$i]][$key] as $keys => $values){
                        $cloud['etime'][$props['city'][$i]][$j][$s] = $values;
                        $s++;
                    }
                    $j++;
                }
                
            }
        
       
        //*
        $k = 0;
         for($i = 0; $i < count($cloud['city']); $i++){
            for($j = 0; $j < count($cloud['lindex'][$cloud['city'][$i]]); $j ++){
                for($s = 0; $s < count($cloud['bdate'][$cloud['city'][$i]][$j]); $s ++){
                    $title = $props['name'];
                    

                    $clouds[$k]['city'] = $cloud['city'][$i];
                    $clouds[$k]['lindex'] = $cloud['lindex'][$cloud['city'][$i]][$j];
                    $clouds[$k]['metro'] = $cloud['metro'][$cloud['city'][$i]][$j];
                    $clouds[$k]['lname'] = $cloud['lname'][$cloud['city'][$i]][$j];
                    $clouds[$k]['bdate'] = $cloud['bdate'][$cloud['city'][$i]][$j][$s];
                    $clouds[$k]['edate'] = $cloud['edate'][$cloud['city'][$i]][$j][$s];
                    $clouds[$k]['btime'] = $cloud['btime'][$cloud['city'][$i]][$j][$s];
                    $clouds[$k]['etime'] = $cloud['etime'][$cloud['city'][$i]][$j][$s];
    
                    if($clouds[$k]['city']){
                    $res = Yii::app()->db->createCommand()
                        ->insert('project_city', array(
                            'project' => $project,
                            'title' => $title,
                            'adres' => $clouds[$k]['lindex'],
                            'name' => $clouds[$k]['lname'],
                            'id_city' => $cloud['city'][$i],
                            'bdate' => date('Y.m.d', strtotime($clouds[$k]['bdate'])),
                            'edate' => date('Y.m.d', strtotime($clouds[$k]['edate'])),
                            'btime' => $clouds[$k]['btime'],
                            'etime' => $clouds[$k]['etime'],
                            'metro' => $clouds[$k]['metro'],
                            'point' => $k.''.rand(1111,9999),
                            'location' => $j
                        ));
                    }
                    $k++;
                }

            }
        }
        $users = explode(',', $props['users']);

        for($i = 0; $i < count($users); $i ++){
            if($users[$i]){
            $res = Yii::app()->db->createCommand()
                        ->insert('project_user', array(
                            'project' => $project,
                            'user' => $users[$i],
                            'firstname' => 'firstname',
                            'lastname' => 'lastname',
                            'email' => 'email',
                            'phone' => 'phone',
                            'point' => NULL
                        ));
                    }
        }

        for($i = 0; $i < count($props['inv-name']); $i ++){
          
            $res = Yii::app()->db->createCommand()
                        ->insert('project_user', array(
                            'project' => $project,
                            'user' => rand(1111,3334),
                            'firstname' => $props['inv-name'][$i],
                            'lastname' => $props['inv-sname'][$i],
                            'email' => $props['inv-email'][$i],
                            'phone' => $props['prfx-phone'][$i].$props['inv-phone'][$i],
                            'point' => NULL
                        ));
                    
        }

         return $cloud;
    }


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
    
    public function getAdresProgramm($project, $filter=false){
        $params = 'pc.project=:project';
        $arParams = array(':project' =>$project);

        if($filter) {
            $city = Yii::app()->getRequest()->getParam('city');
            $bdate = Yii::app()->getRequest()->getParam('bdate');
            $edate = Yii::app()->getRequest()->getParam('edate');
            $point = Yii::app()->getRequest()->getParam('point');
            if($city>0) {
                $params .= ' AND pc.id_city=:city';
                $arParams[':city'] = $city;
            }
            if(isset($bdate) && isset($edate)) {
                $params .= ' AND pc.bdate>=:bdate AND pc.edate<=:edate';
                $arParams[':bdate'] = date('Y.m.d', strtotime($bdate));
                $arParams[':edate'] = date('Y.m.d', strtotime($edate));
            }
            if($point>0) {
                $params .= ' AND pc.point=:point';
                $arParams[':point'] = $point;
            }          
        }

        $result = Yii::app()->db->createCommand()
            ->select(
                "pc.name, 
                pc.adres, 
                pc.id_city, 
                c.name city, 
                c.ismetro,
                DATE_FORMAT(pc.bdate, '%d.%m.%Y') bdate, 
                DATE_FORMAT(pc.edate, '%d.%m.%Y') edate,
                pc.btime, 
                pc.etime, 
                pc.point,
                pc.location,
                pc.title,
                pc.metro id_metro,
                m.name metro"
            )
            ->from('project_city pc')
            ->leftjoin('city c', 'c.id_city=pc.id_city')
            ->leftjoin('metro m', 'm.id=pc.metro')
            ->where($params, $arParams)
            ->order('pc.bdate desc')
            ->queryAll();

        return $this->buildAdressArray($result); 
    }
    /*
    *       подсчет пользователей проекта
    */
    public function getProjectPromoCnt($filter) {
        $sql = Yii::app()->db->createCommand()
            ->select("pu.id")
            ->from('project_user pu')
            ->leftjoin('resume r', 'r.id_user=pu.user')
            ->leftjoin(
                'user u', 
                'u.id_user=pu.user AND u.ismoder=1 AND u.isblocked=0'
            )
            ->where($filter['conditions'], $filter['values'])
            ->queryAll();
        return sizeof($sql);
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
                ? " AND pu.point IS NOT NULL"
                : " AND pu.point IS NULL");
        }

        return $arRes;
    }
    /*
    *       Персонал
    */
    public function getProjectPromo($filter){
        $arRes = Yii::app()->db->createCommand()
            ->select("name title")
            ->from('project')
            ->where('project=:prj', $filter['values'])
            ->queryRow();

        $arT = array();
        $sql = Yii::app()->db->createCommand()
            ->select(
                "pu.user, 
                pu.status,
                pu.point, 
                pu.date,
                pu.firstname fname,
                pu.lastname lname,
                r.firstname, 
                r.lastname,
                r.photo,
                r.isman, 
                u.is_online")
            ->from('project_user pu')
            ->leftjoin('resume r', 'r.id_user=pu.user')
            ->leftjoin(
                'user u', 
                'u.id_user=pu.user AND u.ismoder=1 AND u.isblocked=0'
            )
            ->where($filter['conditions'], $filter['values'])
            ->order('pu.user desc')
            ->limit($this->limit)
            ->offset($this->offset)
            ->queryAll(); // поиск всех пользователей проекта

        if(!sizeof($sql))
            return array('users' => array());

        foreach ($sql as $v) {
            $arT['id'][] = $v['user']; // собираем ID всех пользователей проекта
            $arT['items'][$v['user']] = $v;
        }

        $arT['cities'] = Yii::app()->db->createCommand()
            ->select("uc.id_city, uc.id_user, c.name city, c.ismetro")
            ->from('user_city uc')
            ->leftjoin('city c', 'c.id_city=uc.id_city')
            ->where(array('in', 'uc.id_user', $arT['id']))
            ->queryAll();

        $arT['metros'] = Yii::app()->db->createCommand()
            ->select("um.id_us, m.id id_metro, m.name metro")
            ->from('user_metro um')
            ->leftjoin('metro m', 'm.id=um.id_metro')
            ->where(array('in', 'um.id_us', $arT['id']))
            ->queryAll();

        foreach ($arT['items'] as $user) {
            $id = $user['user'];
            $arRes['users'][$id] = array(
                'id_user' => $id,
                'name' => $user['lastname'] . ' ' . $user['firstname'],
                'src' => DS . MainConfig::$PATH_APPLIC_LOGO . DS 
                    . ($user['photo'] 
                        ? ($user['photo'] . '100.jpg')
                        : ($user['isman'] ? MainConfig::$DEF_LOGO : MainConfig::$DEF_LOGO_F)
                    ),
                'profile' => MainConfig::$PAGE_PROFILE_COMMON . DS . $id,
                'is_online' => $user['is_online'],
                'status' => $arT['items'][$id]['status'],
                'point' => $arT['items'][$id]['point'],
                'date' => $arT['items'][$id]['date']
            );
            foreach ($arT['cities'] as $c)
                if($c['id_user']==$id)
                    $arRes['users'][$id]['cities'][$c['id_city']] =  $c['city'];
            foreach ($arT['metros'] as $m)
                if($m['id_us']==$id)
                    $arRes['users'][$id]['metros'][$m['id_metro']] =  $m['metro'];
        }
        for ($i=0,$n=count($arT['cities']); $i<$n; $i++) {
            $arRes['filter']['cities'][$arT['cities'][$i]['id_city']] = array(
                'id' => $arT['cities'][$i]['id_city'],
                'metro' => $arT['cities'][$i]['ismetro'],
                'city' => $arT['cities'][$i]['city']
            );
        }
        for ($i=0,$n=count($arT['metros']); $i<$n; $i++) {
            $arRes['filter']['metros'][$arT['metros'][$i]['id_metro']] = array(
                'id' => $arT['metros'][$i]['id_metro'],
                'metro' => $arT['metros'][$i]['metro']
            );
        }
        return $arRes;
    }
    
    public function getProject($project){
        $filter = $this->getStaffFilter($project);
        return array_merge(
                $this->getAdresProgramm($project),
                $this->getProjectPromo($filter)
            );
    }
    
    public function importProject($props){
         $link = $props['link'];
        $project = $props['project'];
        $title = $props['title'];
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
                            'title' => $title,
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
                            'title' => $title,
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
        $title = $props['title'];
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
                            'point' => $sheet_array[$i]['E']
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
        
         $data = Yii::app()->db->createCommand()
            ->select('pc.id, pc.user, pc.status, pc.project, pc.firstname, pc.lastname, pc.email, pc.phone')
            ->from('project_user pc')
            ->where('pc.project = :project', array(':project' =>$project))
            ->order('pc.date desc')
            ->queryAll();
            
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
   
        $arRes = array(
            'title' => $arr[0]['title'],
            'bdate' => $arr[0]['bdate'],
            'edate' => $arr[0]['edate'],
            'bdate-short' => $arr[0]['bdate'],
            'edate-short' => $arr[0]['edate']
        );


        $arI = array();
        foreach ($arr as $i) {
            if(strtotime($i['bdate']) < strtotime($arRes['bdate']))
                $arRes['bdate'] = $i['bdate'];
            if(strtotime($i['edate']) > strtotime($arRes['edate']))
                $arRes['edate'] = $i['edate'];
            $arRes['cities'][$i['id_city']] = $i['city'];
            $arI[$i['id_city']] = array(
                'name' => $i['city'],
                'id' => $i['id_city'],
                'metro' => $i['ismetro']
            );            
        }
        $arRes['bdate-short'] = DateTime::createFromFormat('d.m.Y', $arRes['bdate'])->format('d.m.y');
        $arRes['edate-short'] = DateTime::createFromFormat('d.m.Y', $arRes['edate'])->format('d.m.y');

        foreach ($arr as $i) {
            $arL = array();
            $arL['id'] = $i['location'];
            $arL['name'] = $i['name'];
            $arL['index'] = $i['adres'];
            $arL['metro'][$i['id_metro']] = $i['metro'];
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
    *       Изменение адресной программы
    */
    public function setAdresProgramm($arPost) {
        if(!$arPost['project'])
            return false;

        $arBD = Yii::app()->db->createCommand()
            ->select("point,title")
            ->from('project_city')
            ->where('project=:project', array(':project' =>$arPost['project']))
            ->queryAll();

        $arOldP = $arNewP = $arRes = array();
        $title = $arBD[0]['title'];
        foreach ($arBD as $v)
            $arOldP[] = $v['point'];

        foreach ($arPost['city'] as $c) { // по городам
            foreach ($arPost['bdate'][$c] as $l => $arLoc) { // по локациям
                foreach ($arLoc as $p => $v) { // по точкам
                    $arRes[$p] = array(
                        'name' => $arPost['lname'][$c][$l],
                        'adres' => $arPost['lindex'][$c][$l],
                        'id_city' => $c,
                        'bdate' => date('Y-m-d', strtotime($arPost['bdate'][$c][$l][$p])),
                        'edate' => date('Y-m-d', strtotime($arPost['edate'][$c][$l][$p])),
                        'btime' => $arPost['btime'][$c][$l][$p],
                        'etime' => $arPost['etime'][$c][$l][$p],
                        'project' => $arPost['project'],
                        'point' => $p,
                        'location' => $l,
                        'title' => $title,
                        'metro' => $arPost['metro'][$c][$l]
                    );
                    $arNewP[] = $p;
                }       
            }
        }
        
        foreach ($arOldP as $p) 
            if( !in_array($p, $arNewP) ) {  // удаляем отсутствующие
                $res = Yii::app()->db->createCommand()
                    ->delete(
                        'project_city',
                        'point=:pnt AND project=:prj', 
                        array(':pnt'=>$p, ':prj'=>$arPost['project'])
                    );
            }

        foreach ($arRes as $p => $arV) {
            if( in_array($p, $arOldP) ) { // изменяем существующие
                $res = Yii::app()->db->createCommand()
                    ->update(
                        'project_city',
                        $arV,
                        'point=:point',
                        array(':point' => $new['point'])
                    );    
            }
            else { // добавляем новые
                $res = Yii::app()->db->createCommand()
                    ->insert('project_city', $arV);
            }
        }

        return $res;
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
                pc.btime,
                pc.etime,
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
        $arS = Yii::app()->db->createCommand()
            ->select('user, point')
            ->from('project_user')
            ->where('project=:prj', array(':prj'=>$prj))
            ->queryAll();

        foreach ($arS as $user) // убираем отчеканых пользователей
            if(!in_array($user['user'], $arr['user']) && $user['point']==$point)
                Yii::app()->db->createCommand()
                    ->update(
                        'project_user',
                        array('point' => NULL),
                        'project=:prj AND user=:user',
                        array(':prj' => $prj,':user' => $user['user'])
                    );

        foreach ($arr['user'] as $id) // устанавливаем чекнутых
            Yii::app()->db->createCommand()
                ->update(
                    'project_user',
                    array('point' => $point),
                    'project=:prj AND user=:user',
                    array(':prj' => $prj,':user' => $id)
                );
    }
    /*
    *       страница Персонал
    */
    public function getStaff($prj) {
        $filter = $this->getStaffFilter($prj);
        $cnt = $this->getProjectPromoCnt($filter);
        $pagination = new CPagination($cnt);
        $pagination->pageSize = $this->USERS_IN_PAGE;
        $pagination->applyLimit($this);
        $arRes = $this->getProjectPromo($filter);
        $arRes['pages'] = $pagination;
        return $arRes;
    }
    /*
    *       Добавление нового персонала
    */
    public function setProjectPromo($arPost) {
        if(!$arPost['project'])
            return false;

        $sql = Yii::app()->db->createCommand()
            ->select("user")
            ->from('project_user')
            ->where('project=:prj', array(':prj' =>$arPost['project']))
            ->queryAll();

        $arId = array();
        for($i=0,$n=sizeof($sql); $i<$n; $i++)
            $arId[] = $sql[$i]['user'];
        
        $arN = explode(',', $arPost['users']);

        for($i=0,$n=sizeof($arN); $i<$n; $i++) {
            if(!$arPost['users-cnt'])
                break;
            if(!in_array($arN[$i], $arId))
                $res = Yii::app()->db->createCommand()
                    ->insert('project_user', array(
                        'project' => $arPost['project'],
                        'user' => $arN[$i],
                        'firstname' => 'firstname',
                        'lastname' => 'lastname',
                        'email' => 'email',
                        'phone' => 'phone',
                        'point' => NULL
                    )); 
        }

        if(!strlen(trim($arPost['inv-name'][0])))
            return $res;

        for($i=0,$n=sizeof($arPost['inv-name']); $i<$n; $i++) {
            $fname = trim($arPost['inv-name'][$i]);
            $sname = trim($arPost['inv-sname'][$i]);
            if(!strlen($fname) || !strlen($sname))
                continue;

            $phone = $arPost['prfx-phone'][$i] . $arPost['inv-phone'][$i];
            $id = 0;
            do{ $id = rand(1111,3334); }while(in_array($id, $arId));
            $res = Yii::app()->db->createCommand()
                ->insert('project_user', array(
                    'project' => $arPost['project'],
                    'user' => $id,
                    'firstname' => $fname,
                    'lastname' => $sname,
                    'email' => $arPost['inv-email'][$i],
                    'phone' => $phone,
                    'point' => NULL
                ));
        }
        return $res;
    }
}