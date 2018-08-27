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
                            'bdate' => $clouds[$k]['bdate'],
                            'edate' => $clouds[$k]['edate'],
                            'btime' => $clouds[$k]['btime'],
                            'etime' => $clouds[$k]['etime'],
                            'point' => time(),
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
    
    public function getAdresProgramm($project){
     
        $result = Yii::app()->db->createCommand()
            ->select('pc.id, pc.name, pc.adres, pc.id_city, c.name city, pc.bdate, pc.edate, pc.btime, pc.etime')
            ->from('project_city pc')
            ->join('city c', 'c.id_city=pc.id_city')
            ->where('pc.project = :project', array(':project' =>$project))
            ->order('pc.bdate desc')
            ->queryAll();
            
        return $result;
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
        $data['location'] = $this->getAdresProgramm($project);
        $data['user'] = $this->getProjectPromo($project);
        
        return $data;
    }
    
    public function importProject($props){
         $link = $props['link'];
        $project = $props['project'];
        $title = $props['title'];
        Yii::import('ext.yexcel.Yexcel');
        $sheet_array = Yii::app()->yexcel->readActiveSheet("/var/www/dev.prommu/uploads/$link");
        var_dump($sheet_array);
        $city = "Город";
        $location = "Локация";
        $street = "Улица";
        $home = "Дом";
        $build = "Здание";
        $str = "Строение";
        $date = "Дата работы";
        $time = "Время работы";
        
        var_dump($sheet_array);
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
                            'bdate' =>  explode("-", $sheet_array[$i]['G'])[0],
                            'edate' =>  explode("-", $sheet_array[$i]['G'])[1],
                            'btime' => explode("-", $sheet_array[$i]['H'])[0],
                            'etime' =>  explode("-", $sheet_array[$i]['H'])[1],
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
    
    
    
   public function exportProject($project){


            
        $data = Yii::app()->db->createCommand()
            ->select('pc.id, pc.name, pc.adres, pc.id_city, c.name city, pc.bdate, pc.edate, pc.btime, pc.etime, pc.project, pc.point')
            ->from('project_city pc')
            ->join('city c', 'c.id_city=pc.id_city')
            ->where('pc.project = :project', array(':project' =>$project))
            ->order('pc.bdate desc')
            ->queryAll();
            
            
        $csv_file = '<table border="1">
            <tr><td style="color:red; background:#E0E0E0">Город'.
            '</td><td style="color:red; background:#E0E0E0">Локация'.
            '</td><td style="color:red; background:#E0E0E0">Улица'.
            '</td><td style="color:red; background:#E0E0E0">Дом'.
            '</td><td style="color:red; background:#E0E0E0">Здание'.
            '</td><td style="color:red; background:#E0E0E0">Строение'.
            '</td><td style="color:red; background:#E0E0E0">Дата работы'.
            '</td><td style="color:red; background:#E0E0E0">Время работы'.
            '</td><td style="color:red; background:#E0E0E0">Идентификатор'.
            
'</td></tr>';




        
        foreach ($data as $row) {

            $csv_file .= '<tr>';
            $b = "";
            $b_end = "";

                    $city = Yii::app()->db->createCommand()
                    ->select('c.name')
                    ->from('city c')
                    ->where('c.id_city = :id_city', array(':id_city' =>$row['id_city']))
                    ->queryRow();
            
                    $csv_file .= '<td>'.$b.$city['name'].$b_end.
                    '</td><td>'.$b.$row['name'].$b_end.
                    '</td><td>'.$b.$row['adres'].$b_end.
                    '</td><td>'.$b.$row['adres'].$b_end.
                    '</td><td>'.$b.$row['adres'].$b_end.
                    '</td><td>'.$b.$row['adres'].$b_end.
                    '</td><td>'.$b.$row['bdate'].'-'.$row['edate'].$b_end.
                    '</td><td>'.$b.$row['btime'].'-'.$row['etime'].$b_end.
                    '</td><td>'.$b.$row['point'].$b_end.
                    '</td></tr>';

        }

         $csv_file .='</table>';
        $file_name = $_SERVER['DOCUMENT_ROOT'].'/content/project_export.xls'; // название файла
        $file = fopen($file_name,"w"); // открываем файл для записи, если его нет, то создаем его в текущей папке, где расположен скрипт


        fwrite($file,trim($csv_file)); // записываем в файл строки
        fclose($file); // закрываем файл

      
        header('Pragma: no-cache');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Description: File Transfer');
        //header('Content-Type: text/csv');
        //header('Content-Disposition: attachment; filename=export.csv;');
        header('Content-Disposition: attachment; filename=project_export.xls');
        header('Content-transfer-encoding: binary');
        //header("content-type:application/csv;charset=ANSI");
        header('Content-Type: text/html; charset=windows-1251');
        header('Content-Type: application/x-unknown');
        header('P3P: CP="NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM"');
        // print "\xEF\xBB\xBF"; // UTF-8 BOM
        readfile($file_name); // считываем файл

    }
    
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


}