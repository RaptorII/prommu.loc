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
        
         for($i = 0; $i < count($props['city']); $i++){
            for($j = 0; $j < count($props['lindex'][$props['city'][$i]]); $j ++){
                for($s = 0; $s < count($props['bdate'][$props['city'][$i]][$j]); $s ++){
                    $title = $props['name'];

                    $cloud[$s]['city'] = $props['city'][$i];
                    $cloud[$s]['lindex'] = $props['lindex'][$props['city'][$i]][$j];
                    $cloud[$s]['lname'] = $props['lname'][$props['city'][$i]][$j];
                    $cloud[$s]['bdate'] = $props['bdate'][$props['city'][$i]][$j][$s];
                    $cloud[$s]['edate'] = $props['edate'][$props['city'][$i]][$j][$s];
                    $cloud[$s]['btime'] = $props['btime'][$props['city'][$i]][$j][$s];
                    $cloud[$s]['etime'] = $props['etime'][$props['city'][$i]][$j][$s];

                    if($cloud[$s]['city']){
                    $res = Yii::app()->db->createCommand()
                        ->insert('project_city', array(
                            'project' => $project,
                            'title' => $title,
                            'adres' => $cloud[$s]['lindex'],
                            'name' => $cloud[$s]['lname'],
                            'id_city' => $props['city'][$i],
                            'bdate' => $cloud[$s]['bdate'],
                            'edate' => $cloud[$s]['edate'],
                            'btime' => $cloud[$s]['btime'],
                            'etime' => $cloud[$s]['etime'],
                        ));
                    }
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

         return $project;
    }


    public function getProjectEmployer(){
        $idus = Share::$UserProfile->id;
        $result = Yii::app()->db->createCommand()
            ->select("*")
            ->from('project p')
            ->order('p.crdate desc')
            ->queryAll();
            
        return $result;
    }

    public function exportProjectCSV()
    {
        $csv_file = ''; // создаем переменную, в которую записываем строки
        $result = Yii::app()->db->createCommand()
            ->select("e.id_user, e.name, e.type, e.firstname, e.lastname")
            ->from('employer e')
            //->where('`id_user`=:id_user', array(':id_user' => $id_user))
            //->limit(1)
            ->order('e.id_user desc')
            ->queryAll();
        //$result = $user[0];

        // Get directory of color of hair
        $cmp_type = Share::getDirectory('cotype');


        // Get attributes
        foreach($result as &$res) {
            $res['attr'] = self::getUserAttrib($res['id_user']);


            //print_r($result); die;

            // Blocks - City
            $list = Yii::app()->db->createCommand()
                ->select('uc.id_city, c.name as city_name, c.id_co, uc.street, uc.addinfo')
                ->from('user_city uc')
                ->join('city c', 'c.id_city=uc.id_city')
                //->order('uc.id')
                ->where('uc.id_user=:id_user', array(':id_user' => $res['id_user']))
                ->queryAll();
            $res['blocks_city'] = $list;
            //print_r($list); die;
            $res['blocks_cnt'] = count($list);

            // Country
            if(!empty($result['blocks_city'])) {
                $list = Yii::app()->db->createCommand()
                    ->select('id_co, name')
                    ->from('country')
                    ->where('id_co=:id_country', array(':id_country' => $res['blocks_city'][0]['id_co']))
                    ->limit(1)
                    ->queryAll();
                $res['id_co'] = $list[0]['id_co'];
                $res['country_name'] = $list[0]['name'];
            } else {
                $res['country_name'] = '';
            }
        }


        $data = [];
        $i = 0;
        foreach ($result as $res) {
            $attr = Yii::app()->db->createCommand()
                ->select("e.*")
                ->from('empl_attribs e, empl_vacations v')
                ->where('v.id_user=:id and e.id_vac=v.id', array(':id' => $res['id_user']))
                ->queryAll();
            $arr_at = [];

            foreach ($attr as $at) {
                if (!empty($at['key'])) {
                    $arr_at[$at['key']] = $at['val'];
                }
            }
            $data[$i] = $res;
            $data[$i]['attr'] = $arr_at;
            $i++;
        }

        $csv_file = '<table border="1">
            <tr><td style="color:red; background:#E0E0E0">'.'ID'.
            '</td><td style="color:red; background:#E0E0E0">'.htmlentities(iconv("utf-8", "windows-1251", 'Название компании'),ENT_QUOTES, "cp1251").
            '</td><td style="color:red; background:#E0E0E0">'.htmlentities(iconv("utf-8", "windows-1251", 'Тип компании'),ENT_QUOTES, "cp1251").
            '</td><td style="color:red; background:#E0E0E0">'.htmlentities(iconv("utf-8", "windows-1251", 'Страна'),ENT_QUOTES, "cp1251").
            '</td><td style="color:red; background:#E0E0E0">'.htmlentities(iconv("utf-8", "windows-1251", 'Город'),ENT_QUOTES, "cp1251").
            '</td><td style="color:red; background:#E0E0E0">'.htmlentities(iconv("utf-8", "windows-1251", 'Улица'),ENT_QUOTES, "cp1251").
            '</td><td style="color:red; background:#E0E0E0">'.htmlentities(iconv("utf-8", "windows-1251", 'Другое'),ENT_QUOTES, "cp1251").
            '</td><td style="color:red; background:#E0E0E0">'.htmlentities(iconv("utf-8", "windows-1251", 'Web сайт'),ENT_QUOTES, "cp1251").
            '</td><td style="color:red; background:#E0E0E0">'.htmlentities(iconv("utf-8", "windows-1251", 'Логотип (фото url)'),ENT_QUOTES, "cp1251").
            '</td><td style="color:red; background:#E0E0E0">'.htmlentities(iconv("utf-8", "windows-1251", 'Имя'),ENT_QUOTES, "cp1251").
            '</td><td style="color:red; background:#E0E0E0">'.htmlentities(iconv("utf-8", "windows-1251", 'Фамилия'),ENT_QUOTES, "cp1251").
            '</td><td style="color:red; background:#E0E0E0">'.htmlentities(iconv("utf-8", "windows-1251", 'Skype'),ENT_QUOTES, "cp1251").
            '</td><td style="color:red; background:#E0E0E0">'.htmlentities(iconv("utf-8", "windows-1251", 'Моб. Телефон)'),ENT_QUOTES, "cp1251").
            '</td><td style="color:red; background:#E0E0E0">'.htmlentities(iconv("utf-8", "windows-1251", 'Должность'),ENT_QUOTES, "cp1251").
            '</td></tr>';

        $block_status = ["полностью активен", "заблокирован", "ожидает активации", "активирован", "остановлен к показу"];

        //print_r($data); die;
        foreach ($data as $row) {


            $csv_file .= '<tr>';
            $b = "";
            $b_end = "";
            if ($row["ismoder"]==0) {
                $b = '<b>';
                $b_end = '</b>';
            }
            $ismed = $row["ismed"] ? 'X' : '';
            $ishasavto = $row["ishasavto"] ? 'X' : '';
            $isman = $row["isman"] ? 'М' : 'Ж';
            $csv_file .= '<td>'.$b.$row["id_user"].$b_end.
                '</td><td>'.$b.htmlentities(iconv("utf-8", "windows-1251", $row["name"]),ENT_QUOTES, "cp1251").$b_end.
                '</td><td>'.$b.htmlentities(iconv("utf-8", "windows-1251", $cmp_type[$row["type"]]),ENT_QUOTES, "cp1251").$b_end.
                '</td><td>'.$b.htmlentities(iconv("utf-8", "windows-1251", $row["country_name"]),ENT_QUOTES, "cp1251").$b_end.
                '</td><td>'.$b.htmlentities(iconv("utf-8", "windows-1251", $row["blocks_city"][0]["city_name"]),ENT_QUOTES, "cp1251").$b_end.
                '</td><td>'.$b.htmlentities(iconv("utf-8", "windows-1251", $row["blocks_city"][0]["street"]),ENT_QUOTES, "cp1251").$b_end.
                '</td><td>'.$b.htmlentities(iconv("utf-8", "windows-1251", $row["blocks_city"][0]["addinfo"]),ENT_QUOTES, "cp1251").$b_end.
                '</td><td>'.$b.$row['attr']['web'].$b_end.
                '</td><td>'.$b.$row['attr']['logo'].$b_end.
                '</td><td>'.$b.$b.htmlentities(iconv("utf-8", "windows-1251", $row['firstname']),ENT_QUOTES, "cp1251").$b_end.
                '</td><td>'.$b.$b.htmlentities(iconv("utf-8", "windows-1251", $row['lastname']),ENT_QUOTES, "cp1251").$b_end.
                '</td><td>'.$b.htmlentities(iconv("utf-8", "windows-1251", $row['attr']["phone_mb"]),ENT_QUOTES, "cp1251").$b_end.
                '</td><td>'.$b.htmlentities(iconv("utf-8", "windows-1251", $row['attr']['position']),ENT_QUOTES, "cp1251").$b_end.
                '</td></tr>';
        }

        $csv_file .='</table>';
        $file_name = $_SERVER['DOCUMENT_ROOT'].'/content/empl_exp.xls'; // название файла
        $file = fopen($file_name,"w"); // открываем файл для записи, если его нет, то создаем его в текущей папке, где расположен скрипт


        fwrite($file,trim($csv_file)); // записываем в файл строки
        fclose($file); // закрываем файл

        // задаем заголовки. то есть задаем всплывающее окошко, которое позволяет нам сохранить файл.
        //header('Content-type: application/csv'); // указываем, что это csv документ
        //header("Content-Disposition: inline; filename=".$file_name); // указываем файл, с которым будем работать
        header('Pragma: no-cache');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Description: File Transfer');
        //header('Content-Type: text/csv');
        //header('Content-Disposition: attachment; filename=export.csv;');
        header('Content-Disposition: attachment; filename=empl_exp.xls');
        header('Content-transfer-encoding: binary');
        //header("content-type:application/csv;charset=ANSI");
        header('Content-Type: text/html; charset=windows-1251');
        header('Content-Type: application/x-unknown');
        header('P3P: CP="NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM"');
        //print "\xEF\xBB\xBF"; // UTF-8 BOM
        readfile($file_name); // считываем файл
        //unlink($file_name); // удаляем файл. то есть когда вы сохраните файл на локальном компе, то после он удалится с сервера
    }
}