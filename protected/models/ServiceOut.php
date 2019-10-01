<?php

/**
 * This is the model class for table "feedback".
 *
 * The followings are the available columns in table 'feedback':
 * @property string $id
 * @property integer $type
 * @property string $name
 * @property string $theme
 * @property string $email
 * @property string $text
 * @property string $crdate
 * @property integer $pid
 * @property integer $is_smotr
 * @property string $date_smotr
 */
class ServiceOut extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'outstaffing';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('id, campaign, keywords,transition, last_referer, canal, type, id_us, referer, point', 'required'),
            array('type, campaign, last_referer', 'length', 'max'=>100),
            array('name, admin, content', 'length', 'max'=>50),
            array('date', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, type, name, id_us, canal, point, campaign, transition, date, referer, last_referer, keywords, point, content, admin', 'safe', 'on'=>'search'),
        );
    }

    public function deleteAnalytic($cloud){
        foreach ($cloud as $key => $value) {

            Yii::app()->db->createCommand()->delete('analytic', 'id = :id', array(':id' => $value));

        }
    }
    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'type' => 'Type',
            'name' => 'Name',
            'canal' => 'Canal',
            'Keywords' => 'Keywords',
            'campaign' => 'Campaign',
            'date' => 'Date',
            'referer' => 'Referer',
            'content' => 'Content',
            'id_us' => 'Id',
            'last_referer' => 'Last_rederer',
            'admin' => 'Admin',
            'point' => 'Point',
            'transition' => 'Transition',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria=new CDbCriteria;

        $criteria->compare('id',$this->id,true);
        $criteria->compare('vacancy',$this->vacancy, true);
        $criteria->compare('type',$this->type,true);
        $criteria->compare('phone',$this->phone, true);
        

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'pagination' => array('pageSize' => 50,),
            'sort' => ['defaultOrder'=>'date desc'],
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return FeedbackTreatment the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function exportAnalytic()
    {
                $data = Yii::app()->db->createCommand()
            ->select("*")
            ->from('analytic')
            ->where('active=:active', array(':active' => 1))
            ->order("id desc")
            ->queryAll();

            $ac = "Пользователь";
            $type = "Тип";
            $referer = "Реферер";
            $Canal = "Канал";
            $Campaign = "Кампания";
            $Content = "Контент";
            $Keywords = "Ключевые слова";
            $Point = "Поинт";
            $Last_referer = "Последний реферер";
            $Name = "Имя/Фамилия";
            $Date = "Дата";

        $csv_file = '<table border="1">
            <tr><td style="color:red; background:#E0E0E0">'.'ID'.
            '</td><td style="color:red; background:#E0E0E0">'.$type.
            '</td><td style="color:red; background:#E0E0E0">'.$Name.
            '</td><td style="color:red; background:#E0E0E0">'.$referer.
            '</td><td style="color:red; background:#E0E0E0">'.$Campaign.
            '</td><td style="color:red; background:#E0E0E0">'.$Canal.
            '</td><td style="color:red; background:#E0E0E0">'.$Content.
            '</td><td style="color:red; background:#E0E0E0">'.$Keywords.
            '</td><td style="color:red; background:#E0E0E0">'.$Date.
            '</td><td style="color:red; background:#E0E0E0">'.$Point.
            // '</td><td style="color:red; background:#E0E0E0">'.$Last_referer.

'</td></tr>';

        foreach ($data as $row) {


            $csv_file .= '<tr>';
            $b = "";
            $b_end = "";
            // if ($row["k"]==0) {
            //     $b = '<b>';
            //     $b_end = '</b>';
            // }
            if($row['type'] == 2){
                $types = "Соискатель";
                $id_user = $row['id_us'];
                $user = Yii::app()->db->createCommand()
            ->select("e.firstname, e.lastname")
            ->from('resume e')
            ->join('user usr', 'usr.id_user=e.id_user')
            ->where('e.id_user=:id_user', array(':id_user' => $id_user))
            ->queryAll();
            $firstname = $user[0]['firstname'];
            $lastname = $user[0]['lastname'];
            $fio = "$firstname ".$lastname;

            
            }
            else {
                $types = "Работодатель";
            }
            $csv_file .= '<td>'.$b.$row["id_us"].$b_end.
                '</td><td>'.$b.$types.$b_end.
                '</td><td>'.$b.$fio.$b_end.
                '</td><td>'.$b.$row["referer"].$b_end.
                '</td><td>'.$b.$row["campaign"].$b_end.
                '</td><td>'.$b.$row["canal"].$b_end.
                '</td><td>'.$b.$row["content"].$b_end.
                '</td><td>'.$b.$row["keywords"].$b_end.
                '</td><td>'.$b.$row["date"].$b_end.
                '</td><td>'.$b.$row["point"].$b_end.
                // '</td><td>'.$b.$row["last_referer"].$b_end.
                // '</td><td>'.$b.$row["date"].$b_end.
                '</td></tr>';
        }

        $csv_file .='</table>';
        $file_name = $_SERVER['DOCUMENT_ROOT'].'/content/analyt_de.xls'; // название файла
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

    }

    public function setViewed($id, $type, $cnd) {
        return Yii::app()->db->createCommand()->update(
            $this->tableName(),
            ['is_new' => $cnd],
            'id=:id AND type=:type',
            [
                ':id' => $id,
                ':type' => $type
            ]
        );
    }
}
