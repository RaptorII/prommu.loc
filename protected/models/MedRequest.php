<?php
/**
 * Created by PhpStorm.
 * User: sl
 * Date: 11.06.2016
 * Time: 7:19
 */

class MedRequest {
    const SEP = ';';
    const QUOTE = '"';


    public function setCard($cloud){
        $res = Yii::app()->db->createCommand()
                ->insert('med_request', array(
                    'fff' => $cloud['surname'],
                    'iii' => $cloud['name'],
                    'ooo' => $cloud['patronymic'],
                    'tel' => $cloud['__phone_prefix'] . $cloud['phone'],
                    'email' => $cloud['email'],
                    'regaddr' => $cloud['adres'],
                    'pay' => $cloud['pay'],
                    'comment' => $cloud['comment'],
                    'crdate' => date("Y-m-d h-i-s"),
                    'id_user' => (!Share::isGuest() ? Share::$UserProfile->exInfo->id : null)
                ));
        Yii::app()->user->setFlash('success', '1');

        $message = '<p style="font-size:16px;">На сайте prommu.com заказана услуга Медицинская Карта</p>
                    <br/>

                <p style=" font-size:16px;">
               Пользователь: '.$cloud['surname'].' '.$cloud['name'].' '.$cloud['patronymic'].'<br/>
               Контакты: '.$cloud['email'].' '.$cloud['phone'].'<br/>
               Комментарий: '.$cloud['comment'].'<br/>
                    <br/>';
            Share::sendmail('denisgresk@gmail.com', "Prommu.com. Заказ Услуги Медицинская Карта!", $message);
            Share::sendmail('dsale_1@plan-o-gram.ru', "Prommu.com. Заказ Услуги Медицинская Карта!", $message);
            Share::sendmail('prommu.servis@gmail.com', "Prommu.com. Заказ Услуги Медицинская Карта!", $message);
            Share::sendmail('Job@mandarin-agency.ru', "Prommu.com. Заказ Услуги Медицинская Карта!", $message);
            Share::sendmail('e.market.easss@gmail.com', "Prommu.com. Заказ Услуги Медицинская Карта!", $message);
            Share::sendmail('client@btl-me.ru', "Prommu.com. Заказ Услуги Медицинская Карта!", $message);
            Share::sendmail('prommucom@gmail.com', "Prommu.com. Заказ Услуги Медицинская Карта!", $message);
    }

    public function getCard($id) {
        $data = Yii::app()->db->createCommand()
            ->select("*")
            ->from('med_request')
            //->join('city c', 'c.id_city=e.id_city')
            ->where('id=:id', array(':id' => $id))
            ->limit(1)
            ->queryRow();
        // Status is processed
        if(isset($data)) {
            if($data['processed']==0) {
                // set satus
                Yii::app()->db->createCommand()
                    ->update('med_request', array(
                        'processed'=>1,
                    ), 'id=:id', array(':id'=>$id));

            }
        }

        return $data;
    }
     public function deleteCard($cloud){
        foreach($cloud as $key => $value) {
            Yii::app()->db->createCommand()->delete('med_request', 'id = :id', array(':id' => $value));
        }
    }

    public function resetCardStatus($id) {
        Yii::app()->db->createCommand()
            ->update('med_request', array(
                'processed'=>0,
            ), 'id=:id', array(':id'=>$id));
        return true;
    }

    public function CardStatus($id, $status) {
        Yii::app()->db->createCommand()
            ->update('med_request', array(
                'status'=>$status,
            ), 'id=:id', array(':id'=>$id));
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
            ->update('med_request', array(
                'name'=>$data['name'],
                'fff'=>$data['fff'],
                'iii' => $data['iii'],
                'comad' => $comads,
                'ooo'=>$data['ooo'],
                'tel'=>$data['tel'],
                'regaddr'=>$data['regaddr'],
                'email'=>$data['email'],
                'pay'=>$data['pay'],
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
            ->from('med_request')
            ->where("id in ($ids)")
            ->order("id desc")
            ->queryAll();

        $csv_file = '<table border="1">
            <tr><td style="color:red; background:#E0E0E0">'.'ID'.
            '</td><td style="color:red; background:#E0E0E0">'.htmlentities(iconv("utf-8", "windows-1251", 'Статус'),ENT_QUOTES, "cp1251").
            '</td><td style="color:red; background:#E0E0E0">'.htmlentities(iconv("utf-8", "windows-1251", 'Фамилия'),ENT_QUOTES, "cp1251").
            '</td><td style="color:red; background:#E0E0E0">'.htmlentities(iconv("utf-8", "windows-1251", 'Имя'),ENT_QUOTES, "cp1251").
            '</td><td style="color:red; background:#E0E0E0">'.htmlentities(iconv("utf-8", "windows-1251", 'Отчество'),ENT_QUOTES, "cp1251").
            '</td><td style="color:red; background:#E0E0E0">'.htmlentities(iconv("utf-8", "windows-1251", 'Телефон'),ENT_QUOTES, "cp1251").
            '</td><td style="color:red; background:#E0E0E0">'.htmlentities(iconv("utf-8", "windows-1251", 'Электронная почта'),ENT_QUOTES, "cp1251").
            '</td><td style="color:red; background:#E0E0E0">'.htmlentities(iconv("utf-8", "windows-1251", 'Адрес выдачи'),ENT_QUOTES, "cp1251").
            '</td><td style="color:red; background:#E0E0E0">'.htmlentities(iconv("utf-8", "windows-1251", 'Дата создания'),ENT_QUOTES, "cp1251").
            '</td><td style="color:red; background:#E0E0E0">'.htmlentities(iconv("utf-8", "windows-1251", 'Комментарий'),ENT_QUOTES, "cp1251").
'</td></tr>';

        foreach ($data as $row) {

            // Generate list of scan documents


            $csv_file .= '<tr>';
            $b = "";
            $b_end = "";
            if ($row["processed"]==0) {
                $b = '<b>';
                $b_end = '</b>';
            }
            $csv_file .= '<td>'.$b.$row["id"].$b_end.
                '</td><td>'.$b.$row["status"].$b_end.
                '</td><td>'.$b.htmlentities(iconv("utf-8", "windows-1251", $row["fff"]),ENT_QUOTES, "cp1251").$b_end.
                '</td><td>'.$b.htmlentities(iconv("utf-8", "windows-1251", $row["iii"]),ENT_QUOTES, "cp1251").$b_end.
                '</td><td>'.$b.htmlentities(iconv("utf-8", "windows-1251", $row["ooo"]),ENT_QUOTES, "cp1251").$b_end.
                '</td><td>'.$b.$row["tel"].$b_end.
                '</td><td>'.$b.htmlentities(iconv("utf-8", "windows-1251", $row["email"]),ENT_QUOTES, "cp1251").$b_end.
                '</td><td>'.$b.htmlentities(iconv("utf-8", "windows-1251", $row["regaddr"]),ENT_QUOTES, "cp1251").$b_end.
                '</td><td>'.$b.$row["crdate"].$b_end.
                '</td><td>'.$b.htmlentities(iconv("utf-8", "windows-1251", $row["comment"]),ENT_QUOTES, "cp1251").$b_end.
                '</td></tr>';
        }

        $csv_file .='</table>';
        $file_name = $_SERVER['DOCUMENT_ROOT'].'/content/cards_exp.xlsx'; // название файла
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
        header('Content-Disposition: attachment; filename=cards_exp.xlsx');
        header('Content-transfer-encoding: binary');
        //header("content-type:application/csv;charset=ANSI");
        header('Content-Type: text/html; charset=windows-1251');
        header('Content-Type: application/x-unknown');
        header('P3P: CP="NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM"');
        //print "\xEF\xBB\xBF"; // UTF-8 BOM
        readfile($file_name); // считываем файл
        //unlink($file_name); // удаляем файл. то есть когда вы сохраните файл на локальном компе, то после он удалится с сервера

    }

    public function getUserData($idus, $type){
        $arRes = array();
        if($type==2){ // applicant
            $arRes = Yii::app()->db->createCommand()
                        ->select('r.firstname, r.lastname, a.val phone, u.email')
                        ->from('user u')
                        ->leftJoin('resume r', 'r.id_user=u.id_user')
                        ->leftJoin('user_attribs a', 'a.id_us=u.id_user AND a.id_attr=1')
                        ->where('u.id_user=:id_user', array(':id_user' => $idus))
                        ->queryRow();
        }
        else{ // employer
            $arRes = Yii::app()->db->createCommand()
                        ->select('e.firstname, e.lastname, a.val phone, u.email')
                        ->from('employer e')
                        ->leftJoin('user u', 'u.id_user=e.id_user')
                        ->leftJoin('user_attribs a', 'a.id_us=e.id_user AND a.id_attr=1')
                        ->where('e.id_user=:id_user', array(':id_user' => $idus))
                        ->queryRow();
        }

        if(isset($arRes['phone'])){
            $arRes['phone'] = str_replace('+','',$arRes['phone']);
            $pos = strpos($arRes['phone'], '(');
            $arRes['phone-code'] = substr($arRes['phone'],0,$pos);
            $arRes['phone'] = substr($arRes['phone'], $pos);     
        }

        return $arRes;
    }
  /**
   * @param $id_user - integer
   * @param $buildArray - bool
   * @return array
   */
  public function getMedBookByUser($id_user, $buildArray=false)
  {
    $arRes = [];
    $query = Yii::app()->db->createCommand()
      ->select('*')
      ->from('med_request')
      ->where('id_user=:id',[':id'=>$id_user])
      ->order('crdate desc')
      ->queryAll();

    if(count($query))
    {
      if($buildArray)
      {
        foreach ($query as $v)
        {
          $arRes[] = [
            'id_user' => $v['id_user'],
            'vacancy' => false,
            'type' => 'medbook',
            'name' => Services::getServiceName('medbook'),
            'date' => $v['crdate'],
            'cost' => 0,
            'status' => self::getAdminStatus($v['status']),
            'data' => []
          ];
        }
      }
      else
      {
        $arRes = $query;
      }
    }

    return $arRes;
  }
  /**
   * @param $status - card_request => processed
   * @return string
   */
  public static function getAdminStatus($status)
  {
    switch ($status)
    {
      case 1: $result='Просмотрено администратором'; break;
      case 2: $result='Отменена'; break;
      case 3: $result='В обработке'; break;
      case 4: $result='Не хватает данных'; break;
      case 5: $result='Выполнена'; break;
      default: $result='Новая'; break;
    }
    return $result;
  }
}