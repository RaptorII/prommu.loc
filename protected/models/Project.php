<?php
/**
 * Created by Grescode
 * Date: 28.07.18
 */

class Project extends ARModel
{



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
                            'bdate' => date('Y-m-d', strtotime($clouds[$k]['bdate'])),
                            'edate' => date('Y-m-d', strtotime($clouds[$k]['edate'])),
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
                            'phone' => 'phone'
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
            if($city>0) {
                $params .= ' AND pc.id_city=:city';
                $arParams[':city'] = $city;
            }
            if(isset($bdate) && isset($edate)) {
                $params .= ' AND pc.bdate>=:bdate AND pc.edate<=:edate';
                $arParams[':bdate'] = date('Y-m-d', strtotime($bdate));
                $arParams[':edate'] = date('Y-m-d', strtotime($edate));
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
                pc.title"
            )
            ->from('project_city pc')
            ->join('city c', 'c.id_city=pc.id_city')
            ->where($params, $arParams)
            ->order('pc.bdate desc')
            ->queryAll();

        return $this->buildArray($result); 
    }
    
    public function getProjectPromo($project){
     
        $result = Yii::app()->db->createCommand()
            ->select("*")
            ->from('project_user pu')
            ->where('pu.project = :project', array(':project' =>$project))
            ->order('pu.user desc')
            ->queryAll();
            
        return $result;
    }
    
    public function getProject($project){
        $data = $this->getAdresProgramm($project);
        $data['user'] = $this->getProjectPromo($project);
        
        return $data;
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
                            'bdate' => explode("-", $sheet_array[$i]['H'])[0],
                            'edate' =>  explode("-", $sheet_array[$i]['H'])[1],
                     ), 'point = :point', array(':point' => $point));
                
                } else {
                    $res = Yii::app()->db->createCommand()
                        ->insert('project_city', array(
                            'project' => $project,
                            'title' => $title,
                            'name' =>  $sheet_array[$i]['B'],
                            'adres' =>  $sheet_array[$i]['C'].' '.$sheet_array[$i]['D'].' '.$sheet_array[$i]['E'].' '.$sheet_array[$i]['F'],
                            'id_city' =>  $city['id_city'],
                            'bdate' =>  explode("-", $sheet_array[$i]['G'])[0],
                            'edate' =>  explode("-", $sheet_array[$i]['G'])[1],
                            'btime' => explode("-", $sheet_array[$i]['H'])[0],
                            'etime' =>  explode("-", $sheet_array[$i]['H'])[1],
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

        for($i = 1; $i < count($sheet_array)+1; $i++){
                    
                if($sheet_array[$i]['E'] != ''){
                    $point = $sheet_array[$i]['I'];
                    
                     Yii::app()->db->createCommand()
                        ->update('project_city', array(
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
            ->select('pc.id, pc.user, pc.status, pc.firstname, pc.lastname, pc.email, pc.phone')
            ->from('project_users pc')
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
    public function buildArray($arr){
        if(!count($arr))
            return false;
   
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
            $arL['metro'] = '';         // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
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
    public function delLocation($arr){
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
                        //'metro' => $arPost['metro'][$c][$l] !!!!!!!!!!!!!!!!!!!!!!!!!!!!
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
                // Обработка файла
            }
        }
    }
}