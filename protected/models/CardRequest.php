<?php
/**
 * Created by PhpStorm.
 * User: sl
 * Date: 11.06.2016
 * Time: 7:19
 */

class CardRequest {
    const SEP = ';';
    const QUOTE = '"';

    public function getCard($id) {
        $data = Yii::app()->db->createCommand()
            ->select("*")
            ->from('card_request')
            //->join('city c', 'c.id_city=e.id_city')
            ->where('id=:id', array(':id' => $id))
            ->limit(1)
            ->queryRow();
        // Status is processed
        if(isset($data)) {
            if($data['processed']==0) {
                // set satus
                Yii::app()->db->createCommand()
                    ->update('card_request', array(
                        'processed'=>1,
                    ), 'id=:id', array(':id'=>$id));

            }
        }

        return $data;
    }
     public function deleteCard($cloud){
        foreach($cloud as $key => $value) {
            Yii::app()->db->createCommand()->delete('card_request', 'id = :id', array(':id' => $value));
        }
    }

    public function resetCardStatus($id) {
        Yii::app()->db->createCommand()
            ->update('card_request', array(
                'processed'=>0,
            ), 'id=:id', array(':id'=>$id));
        return true;
    }

    public function CardStatus($id, $status) {
        Yii::app()->db->createCommand()
            ->update('card_request', array(
                'status'=>$status,
            ), 'id=:id', array(':id'=>$id));
    }

    public function DeleteScan($key, $id) {
        $data = array();
        $id_user = $id;
        if ($key > 0 && $id > 0) {
            $row = self::getCard($id);
            $scan = (array)json_decode($row['files']);
            $i=1;
            $id = 0;
            while ($r = current($scan))
            {
                if($i == $key) {
                    $id = key($scan);
                }
                $i++;
                next($scan);
            }
            $path = $_SERVER['DOCUMENT_ROOT'] . $scan[$id]->orig;
            //print_r($id);die;
            if (file_exists($path)) {
                unlink($path);
                $path_tb = explode('.', $path);
                unlink($path_tb[0] . 'tb.' . $path_tb[1]);

            }
                // Delete in DB
                unset($scan[$id]);
            //echo stripslashes(json_encode($scan)); die;
                //$data = $scan;
                Yii::app()->db->createCommand()
                    ->update('card_request', array(
                        'files'=>stripslashes(json_encode($scan)),
                    ), 'id=:id', array(':id'=>$id_user));

        }
        return $scan;
    }


    public function AddScan($fname, $id)
    {
        //echo $fname; die;
        if ($id > 0) {
            $row = self::getCard($id);
            $scan_obj = (array)json_decode($row['files']);
            $tmp_file = explode(".", $fname);
            $name = $tmp_file[0];
            $scan_obj = array_merge($scan_obj,
                array(str_replace('/images/services/', '', $name) => array(
                    "orig"=>$name.'.'.$tmp_file[1],
                    "tb"=>$name.'tb'.'.'.$tmp_file[1]
                )
            ));

            Yii::app()->db->createCommand()
                ->update('card_request', array(
                    'files'=>json_encode($scan_obj),
                ), 'id=:id', array(':id'=>$id));

            return json_decode(json_encode($scan_obj));
        }
        return array();
    }


    public function ConvertListToHtml($data)
    {
        $html = '';
        $i=1;
        //print_r($data); die;
        foreach($data as $r) {
            //print_r($r);
                $html = $html . '<li><a class="btn btn-success" href="' . $r->orig . '" target="_blank">Документ ' . $i . ' просмотр</a>';
                $html = $html . '<a href="javascript:Del(' . $i . ')" class="btn btn-warning">Удалить</a>';
                $html = $html . '</li>';
                $i++;
        }
        return $html;
    }

    public function updateCard($id, $data)
    {   
        $cloud = $this->getCard($id);
        $comad = $cloud['comad'];
        $dates=date("Y-m-d h-i-s");
        if($data['comad'] != ''){
           $comads =  $dates." ".$data['comad']."<br/>".$comad;
        }
        else $comads = $comad;
        if($data['status'] == 0){
            $status = 3;
        }
        else $status = $data['status'];
        Yii::app()->db->createCommand()
            ->update('card_request', array(
                'name'=>$data['name'],
                'post'=>$data['post'],
                'tabn'=>$data['tabn'],
                'fff'=>$data['fff'],
                'iii' => $data['iii'],
                'comad' => $comads,
                'ooo'=>$data['ooo'],
                'docser'=>$data['docser'],
                'doctype'=>$data['doctype'],
                'docnum'=>$data['docnum'],
                'docdate'=>$data['docdate'],
                'docorgcode'=>$data['docorgcode'],
                'docorgname'=>$data['docorgname'],
                'borndate'=>$data['borndate'],
                'bornplace'=>$data['bornplace'],
                'tel'=>$data['tel'],
                'regcountry'=>$data['regcountry'],
                'regaddr'=>$data['regaddr'],
                'liveaddr'=>$data['liveaddr'],
                'status'=>$status,
                'comment'=>$data['comment'],
            ), 'id=:id', array(':id'=>$id));

    }


    public function exportCSV($params)
    {
        $csv_file = ''; // создаем переменную, в которую записываем строки
        $ids = implode (",", $params);
        //print_r($ids);
        $data = Yii::app()->db->createCommand()
            ->select("*")
            ->from('card_request')
            ->where("id in ($ids)")
            ->order("id desc")
            ->queryAll();

        $csv_file = '<table border="1">
            <tr><td style="color:red; background:#E0E0E0">'.'ID'.
            '</td><td style="color:red; background:#E0E0E0">'.htmlentities(iconv("utf-8", "windows-1251", 'Статус'),ENT_QUOTES, "cp1251").
            '</td><td style="color:red; background:#E0E0E0">'.htmlentities(iconv("utf-8", "windows-1251", 'Фирма'),ENT_QUOTES, "cp1251").
            '</td><td style="color:red; background:#E0E0E0">'.htmlentities(iconv("utf-8", "windows-1251", 'Должность'),ENT_QUOTES, "cp1251").
            '</td><td style="color:red; background:#E0E0E0">'.htmlentities(iconv("utf-8", "windows-1251", 'Таб. номер'),ENT_QUOTES, "cp1251").
            '</td><td style="color:red; background:#E0E0E0">'.htmlentities(iconv("utf-8", "windows-1251", 'Фамилия'),ENT_QUOTES, "cp1251").
            '</td><td style="color:red; background:#E0E0E0">'.htmlentities(iconv("utf-8", "windows-1251", 'Имя'),ENT_QUOTES, "cp1251").
            '</td><td style="color:red; background:#E0E0E0">'.htmlentities(iconv("utf-8", "windows-1251", 'Отчество'),ENT_QUOTES, "cp1251").
            '</td><td style="color:red; background:#E0E0E0">'.htmlentities(iconv("utf-8", "windows-1251", 'Тип уд.личности'),ENT_QUOTES, "cp1251").
            '</td><td style="color:red; background:#E0E0E0">'.htmlentities(iconv("utf-8", "windows-1251", 'Серия документа'),ENT_QUOTES, "cp1251").
            '</td><td style="color:red; background:#E0E0E0">'.htmlentities(iconv("utf-8", "windows-1251", 'Номер документа'),ENT_QUOTES, "cp1251").
            '</td><td style="color:red; background:#E0E0E0">'.htmlentities(iconv("utf-8", "windows-1251", 'Дата документа'),ENT_QUOTES, "cp1251").
            '</td><td style="color:red; background:#E0E0E0">'.htmlentities(iconv("utf-8", "windows-1251", 'Код подразделения (выдавший паспорт)'),ENT_QUOTES, "cp1251").
            '</td><td style="color:red; background:#E0E0E0">'.htmlentities(iconv("utf-8", "windows-1251", 'Кем выдан документ'),ENT_QUOTES, "cp1251").
            '</td><td style="color:red; background:#E0E0E0">'.htmlentities(iconv("utf-8", "windows-1251", 'Дата Рождения'),ENT_QUOTES, "cp1251").
            '</td><td style="color:red; background:#E0E0E0">'.htmlentities(iconv("utf-8", "windows-1251", 'Место рождения'),ENT_QUOTES, "cp1251").
            '</td><td style="color:red; background:#E0E0E0">'.htmlentities(iconv("utf-8", "windows-1251", 'Телефон для SMS'),ENT_QUOTES, "cp1251").
            '</td><td style="color:red; background:#E0E0E0">'.htmlentities(iconv("utf-8", "windows-1251", 'Адрес прописки'),ENT_QUOTES, "cp1251").
            '</td><td style="color:red; background:#E0E0E0">'.htmlentities(iconv("utf-8", "windows-1251", 'Код страны (прописки)'),ENT_QUOTES, "cp1251").
            '</td><td style="color:red; background:#E0E0E0">'.htmlentities(iconv("utf-8", "windows-1251", 'Адрес факт. проживания'),ENT_QUOTES, "cp1251").
            '</td><td style="color:red; background:#E0E0E0">'.htmlentities(iconv("utf-8", "windows-1251", 'Код страны (прожив.)'),ENT_QUOTES, "cp1251").
            '</td><td style="color:red; background:#E0E0E0">'.htmlentities(iconv("utf-8", "windows-1251", 'Сканы документов'),ENT_QUOTES, "cp1251").
            '</td><td style="color:red; background:#E0E0E0">'.htmlentities(iconv("utf-8", "windows-1251", 'Дата создания'),ENT_QUOTES, "cp1251").
            '</td><td style="color:red; background:#E0E0E0">'.htmlentities(iconv("utf-8", "windows-1251", 'Комментарий'),ENT_QUOTES, "cp1251").
'</td></tr>';

        foreach ($data as $row) {

            // Generate list of scan documents
            $lst = json_decode($row["files"]);
            $scan = '<ul class="controls" id="lst_scan">';
            if(!empty($lst)) {
                $i = 1;
                foreach ($lst as $r) {
                    $scan.= '<li><a class="btn btn-success" href="http://'. $_SERVER['SERVER_NAME']. $r->orig . '" target="_blank">scan file ' . $i . '</a>';
                    $scan.= '</li>';
                    $i++;
                }
            }
            $scan.= '</ul>';

            $csv_file .= '<tr>';
            $b = "";
            $b_end = "";
            if ($row["processed"]==0) {
                $b = '<b>';
                $b_end = '</b>';
            }
            $csv_file .= '<td>'.$b.$row["id"].$b_end.
                '</td><td>'.$b.$row["status"].$b_end.
                '</td><td>'.$b.htmlentities(iconv("utf-8", "windows-1251", $row["name"]),ENT_QUOTES, "cp1251").$b_end.
                '</td><td>'.$b.htmlentities(iconv("utf-8", "windows-1251", $row["post"]),ENT_QUOTES, "cp1251").$b_end.
                '</td><td>'.$b.$row["tabn"].$b_end.
                '</td><td>'.$b.htmlentities(iconv("utf-8", "windows-1251", $row["fff"]),ENT_QUOTES, "cp1251").$b_end.
                '</td><td>'.$b.htmlentities(iconv("utf-8", "windows-1251", $row["iii"]),ENT_QUOTES, "cp1251").$b_end.
                '</td><td>'.$b.htmlentities(iconv("utf-8", "windows-1251", $row["ooo"]),ENT_QUOTES, "cp1251").$b_end.
                '</td><td>'.$b.$row["doctype"].$b_end.
                '</td><td>'.$b.htmlentities(iconv("utf-8", "windows-1251", $row["docser"]),ENT_QUOTES, "cp1251").$b_end.
                '</td><td>'.$b.$row["docnum"].$b_end.
                '</td><td>'.$b.$row["docdate"].$b_end.
                '</td><td>'.$b.$row["docorgcode"].$b_end.
                '</td><td>'.$b.htmlentities(iconv("utf-8", "windows-1251", $row["docorgname"]),ENT_QUOTES, "cp1251").$b_end.
                '</td><td>'.$b.$row["borndate"].$b_end.
                '</td><td>'.$b.htmlentities(iconv("utf-8", "windows-1251", $row["bornplace"]),ENT_QUOTES, "cp1251").$b_end.
                '</td><td>'.$b.$row["tel"].$b_end.
                '</td><td>'.$b.htmlentities(iconv("utf-8", "windows-1251", $row["regaddr"]),ENT_QUOTES, "cp1251").$b_end.
                '</td><td>'.$b.$row["regcountry"].$b_end.
                '</td><td>'.$b.htmlentities(iconv("utf-8", "windows-1251", $row["liveaddr"]),ENT_QUOTES, "cp1251").$b_end.
                '</td><td>'.$b.htmlentities(iconv("utf-8", "windows-1251", $row["livecountry"]),ENT_QUOTES, "cp1251").$b_end.
                '</td><td>'.$b.$scan.$b_end.
                '</td><td>'.$b.$row["crdate"].$b_end.
                '</td><td>'.$b.htmlentities(iconv("utf-8", "windows-1251", $row["comment"]),ENT_QUOTES, "cp1251").$b_end.
                '</td></tr>';
        }

        $csv_file .='</table>';
        $file_name = $_SERVER['DOCUMENT_ROOT'].'/content/cards_exp.xls'; // название файла
        $file = fopen($file_name,"w"); // открываем файл для записи, если его нет, то создаем его в текущей папке, где расположен скрипт

        //$fp = fopen('file.csv', 'w');
        //fputs($file, $bom =( chr(0xEF) . chr(0xBB) . chr(0xBF) ));

        /*
        foreach ($data as $fields) {
            //$ff=mb_convert_encoding($fields,"UTF-8","Windows-1251");
            fputcsv($file, $fields);
        }
        */

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
        header('Content-Disposition: attachment; filename=cards_exp.xls');
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