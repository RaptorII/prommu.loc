<?php

/**
 * This is the model class for table "city".
 *
 * The followings are the available columns in table 'city':
 * @property integer $id_city
 * @property integer $ishide
 * @property string $name
 */
class City extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return City the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }



    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'city';
    }



    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name', 'required'),
            array('ishide', 'numerical', 'integerOnly' => true),
            array('name', 'length', 'max' => 128),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id_city, ishide, name', 'safe', 'on' => 'search'),
        );
    }



    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array();
    }



    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id_city' => '#',
            'ishide' => 'Ishide',
            'name' => 'Название',
        );
    }



    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id_city', $this->id_city);
        $criteria->compare('ishide', $this->ishide);
        $criteria->compare('name', $this->name, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array('pageSize' => 20,),
        ));
    }




    public function GetList($inID, $limit, $filter)
    {
		if( $filter ) $where = array('t.name like :filter AND (t.id_co = :id OR :id = 0)', array(':filter'=>"%{$filter}%", ':id'=>$inID));
//        if ($filter) $where = array("t.name like '%{$filter}%' AND (t.id_co = :id OR :id = 0)", array(':id' => $inID));
        else $where = array('t.id_co = :id OR :id = 0', array(':id' => $inID));


//        $sql = "SELECT `t`.`id_city`, `t`.`name`, `t`.`ismetro`
//            FROM `city` `t`
//            WHERE t.name like '%{$filter}%' AND (t.id_co = {$inID} OR {$inID} = 0) LIMIT 20";
//        $res = Yii::app()->db->createCommand($sql);
//        $res = $res->queryAll();

        $Q1 = Yii::app()->db->createCommand()
            ->select('t.id_city, t.name, t.ismetro')
            ->from('city t')
            ->where($where[0], $where[1])
            ->limit($limit);
        $res = $Q1->queryAll();

        return $res;
    }



    /**
     * Получаем города для фильтра вакансий
     * @param $inID int
     * @param $filter string
     * @param $limit int
     * @return mixed
     */
    public function getCityList($inID, $filter, $limit)
    {
        $arRes = array();
        $name = urldecode($filter);
        $name = trim($name);
        $name = stripslashes($name);
        $inID = intval($inID);

        if(!empty($name))
        {
          $sql = "SELECT id_city id, name, ismetro, id_co
                FROM city
                WHERE name LIKE '%{$name}%' AND (id_co={$inID} OR {$inID}=0)
                ORDER BY CASE 
                WHEN name LIKE '{$name}' THEN 0
                WHEN name LIKE '{$name}%' THEN 1
                WHEN name LIKE '%{$name}%' THEN 2
                ELSE 3 END
                LIMIT {$limit}";
        }
        else
        {
          $sql = "SELECT id_city id, name, ismetro, id_co
                FROM city
                WHERE id_co={$inID} OR {$inID}=0
                ORDER BY sort ASC
                LIMIT {$limit}";
        }

        $res = Yii::app()->db->createCommand($sql)->queryAll();

        $n = count($res);
        
        if(!$n) {
            $arRes['suggestions'][] = array('data' => 'man', 'value' => $name);
            return $arRes;
        }

        for ( $i=0; $i < $n; $i++ ) {
            $arRes['suggestions'][] = array(
                    'data' => $res[$i]['id'],
                    'value' => $res[$i]['name'],
                    'ismetro' => $res[$i]['ismetro']
                );
        }

        return $arRes;
    }



    public function GetListMetro($inID, $inUsID = 0)
    {
        $Q1 = Yii::app()->db->createCommand()
            ->select('t.id, t.name')
            ->from('metro t')
            ->where('t.id_city = :id', array(':id' => $inID))
            ->order('name');
        $res = $Q1->queryAll();
//$s1 = '$where='.var_export([$where,$res,$Q1->getText()], 1)."\n";
//0||$notpr||file_put_contents('D:\DENVER2\home\localhost\prommu\_file1', "\n--------------------\n".date("H:i:s")."\n".$s1, 0);//FILE_APPEND
        return $res;
    }



    public function AddName($name)
    {
        $command = Yii::app()->db->createCommand();
        $command->insert('city', array(
            'ishide' => '0',
            'name' => $name,
        ));
    }



    public function EditName($name, $id)
    {
        $command = Yii::app()->db->createCommand();
        $command->update('city', array(
            'name' => $name
        ), 'id_city=:id', array(':id' => $id));
    }



    public function SetActive($id)
    {
        $res = Yii::app()->db->createCommand()
            ->select('ishide')
            ->from('city')
            ->where('id_city=:id', array(':id' => $id))
            ->limit(1)
            ->queryRow();

        $newStatus = $res['ishide'] ^ 1;

        $command = Yii::app()->db->createCommand();
        $command->update('city', array(
            'ishide' => $newStatus
        ), 'id_city=:id', array(':id' => $id));

        return $newStatus;
    }


// BM: ================================================================================================= MY SERVICES ===
    /**
     * Получаем данные города определенной вакансии
     */
    public function getvecityblockdata()
    {
        $idvac = Yii::app()->session['editVacId'];
        $id = Yii::app()->getRequest()->getParam('id');

        $Q1 = Yii::app()->db->createCommand()
            ->select('ci.id, ci.id_city idcity, ci.citycu, c2.name, DATE_FORMAT(ci.bdate, \'%d.%m.%Y\') bdate, DATE_FORMAT(ci.edate, \'%d.%m.%Y\') edate')
            ->from('empl_city ci')
            ->leftJoin('city c2', 'ci.id_city = c2.id_city')
            ->where('ci.id = :id', array(':id'=>$id));
//            ->where('ci.id_vac = :idvac AND ci.id_city = :idcity', array(':idvac'=>"{$idvac}", ':idcity'=>$idcity));
        $res = $Q1->queryRow();

        if( !$res['name'] ) $res['name'] = $res['citycu'];

        return $res;
    }



    /**
     * Получаем данные локации города
     */
    public function getvelocationdata()
    {
        $idvac = Yii::app()->session['editVacId'];
        $id = filter_var(Yii::app()->getRequest()->getParam('id'), FILTER_SANITIZE_NUMBER_INT);
        $idcity = filter_var(Yii::app()->getRequest()->getParam('idcity', 0), FILTER_SANITIZE_NUMBER_INT);

        /** @var $Q1 CDbCommand */
        $Q1 = Yii::app()->db->createCommand()
            ->select('l.id, l.name, l.addr, l.id_metro idmetro, l.id_vac idvac
                , ci.id_city idcity
                , c2.ismetro
                , DATE_FORMAT(t.bdate, \'%d.%m.%Y\') bdate
                , DATE_FORMAT(t.edate, \'%d.%m.%Y\') edate
                , CONCAT(t.bdate, t.edate, t.btime, t.etime) perHash
                , t.btime, t.etime
                , m.id mid, m.name mname ')
            ->from('empl_locations l')
            ->join('empl_city ci', 'ci.id = l.id_city')
            ->leftJoin('emplv_loc_times t', 't.id_loc = l.id')
            ->leftJoin('metro m', 'm.id = l.id_metro')
            ->leftJoin('city c2', 'c2.id_city = ci.id_city')
            ->where('l.id = :id AND l.id_vac = :idvac', array(':id'=>$id, 'idvac' => $idvac))
            ->order('t.npp');
        $res = $Q1->queryAll();

        $data['loc'] = array();
        foreach ($res as $key => $val)
        {

            $btime = $etime = '';
            if( $val['btime'] )
            {
                $h = floor($val['btime'] / 60);
                $m = $val['btime'] - $h * 60;
                $btime = sprintf('%d:%02d', $h, $m);
            }

            if( $val['etime'] )
            {
                $h = floor($val['etime'] / 60);
                $m = $val['etime'] - $h * 60;
                $etime = sprintf('%d:%02d', $h, $m);
            } // endif
            $data['loc']['loctimes'][md5($val['perHash'])] = array($val['bdate'], $val['edate'], $btime, $etime);
//            if( !isset($data['vac'][0])) $data['vac'][0] = array('city' => array(), 'post' => array(), 'metroes' => array(), 'hasmetro' => array(), 'location' => array());

            $data['loc'] = array_merge($data['loc'], $val);
        }


        // metro
        $data['metro'] = array();
        if( $idcity )
        {
            if( $data['loc']['ismetro'] ) $data['metro'] = $this->GetListMetro($idcity);
        } // endif

        return $data;
    }



    /**
     * Сохраняем данные города определенной вакансии
     */
    public function saveCityInfo()
    {
        $idvac = Yii::app()->session['editVacId'];
        $id = filter_var(Yii::app()->getRequest()->getParam('id'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $idcity = filter_var(Yii::app()->getRequest()->getParam('idcity'), FILTER_SANITIZE_NUMBER_INT);
        $name = filter_var(Yii::app()->getRequest()->getParam('name'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $bdate = filter_var(Yii::app()->getRequest()->getParam('bdate'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $edate = filter_var(Yii::app()->getRequest()->getParam('edate'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $ismetro = filter_var(Yii::app()->getRequest()->getParam('metro'), FILTER_SANITIZE_NUMBER_INT);

        /** @var $Q1 CDbCommand */
        $Q1 = Yii::app()->db->createCommand()
            ->select('ci.id_city id')
            ->from('empl_city ci')
            ->where('ci.id_vac = :idvac AND ci.id_city = :idcity AND id <> :id', array(':idvac'=>"{$idvac}", ':idcity'=>$idcity, ':id'=>$id));
        $res = $Q1->queryRow();

        // если такой город уже есть
        if( $res['id'] )
        {
            $message = 'Такой город уже добавлен к вакансии';
            $error = -101;
        }
        elseif( $idvac )
        {
            if( time() > strtotime($bdate) + 86400-1 )
            {
                $message = 'Дата начала меньше текущей';
                $error = -102;
            }
            elseif( strtotime($edate) < strtotime($bdate) )
            {
                $message = 'Дата начала больше даты окончания';
                $error = -103;

            // Параметры в порядке сохраняем
            } else {
//                return array('error' => -101, 'message' => 'OKOK');

                // новый блок города
                if( $id == 'new' )
                {
                    $res = Yii::app()->db->createCommand()
                        ->insert('empl_city', array(
                            'id_vac' => $idvac,
                            'id_city' => $idcity,
                            'bdate' => date("Y-m-d", strtotime($bdate)),
                            'edate' => date("Y-m-d", strtotime($edate)),
                        ));

                    $id = Yii::app()->db->createCommand('SELECT LAST_INSERT_ID()')->queryScalar();


                    // metro
                    if( $ismetro )
                    {
                        $data['metro'] = array();
                        $data['metro'] = $this->GetListMetro($idcity);
                    } // endif


                // редактируем блок города
                } else {
                    $fields = array(
                        'id_city' => $idcity,
                        'bdate' => date("Y-m-d", strtotime($bdate)),
                        'edate' => date("Y-m-d", strtotime($edate)),
                    );

                    if( !$idcity )
                    {
                        $fields['id_city'] = 0;
                        $fields['citycu'] = $name;
                    }
                    $res = Yii::app()->db->createCommand()
                        ->update('empl_city', $fields, 'id = :id AND id_vac = :idvac', array(':id' => $id, ':idvac' => $idvac));
                } // endif

            } // endif
        } else {
//                $message = 'Вакансия не определена';
            $error = -104;
        } // endif

        if( $error )
        {
            return array('error' => $error, 'message' => $message);
        }
        else
        {
            return array('error' => 100, 'message' => 'Данные успешно сохранены', 'id' => $id, 'data' => $data);
        } // endif
    }



    /**
     * Сохраняем данные локации
     */
    public function saveLocationInfo()
    {
        $idvac = Yii::app()->session['editVacId'];
        $idloc = filter_var(Yii::app()->getRequest()->getParam('idloc'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $idcity = filter_var(Yii::app()->getRequest()->getParam('idcity'), FILTER_SANITIZE_NUMBER_INT);
        $name = filter_var(Yii::app()->getRequest()->getParam('name'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $addr = filter_var(Yii::app()->getRequest()->getParam('addr'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $metro = Yii::app()->getRequest()->getParam('metro');
        $bdate = Yii::app()->getRequest()->getParam('bdate');
        $edate = Yii::app()->getRequest()->getParam('edate');
        $btime = Yii::app()->getRequest()->getParam('btime');
        $etime = Yii::app()->getRequest()->getParam('etime');


        // проверка владения вакансией
        $id = $idloc == 'new' ? 0 : $idloc;
        $iduser = Share::$UserProfile->exInfo->id;
        $sql = "SELECT v.id FROM empl_vacations v
                INNER JOIN empl_locations l ON l.id_vac = v.id AND l.id = {$id} OR 0 = {$id} 
                WHERE v.id_user = {$iduser} AND v.id = {$idvac}";
        /** @var $res CDbCommand */
        $res = Yii::app()->db->createCommand($sql);
        $res = $res->queryRow();

        // вакансия редактируется
        $message = 'Ошибка сохранения локации';
        $error = -100;

        try
        {
            // вакансия не пренадлежит пользователю
            if( !$res['id'] ) throw new Exception($message, -100);
//            throw new Exception($message, -100);


            // если такой город уже есть
            if( empty($name) || empty($addr) )
            {
                throw new Exception("Неправильно заполнены поля локации", -101);
            }
            else
            {

                // новый блок города
                if( $idloc == 'new' )
                {
                    /** @var $Q1 CDbCommand */
                    $Q1 = Yii::app()->db->createCommand()
                        ->select('IFNULL(MAX(l.npp), 0) npp ')
                        ->from('empl_locations l')
                        ->where('l.id_vac = :idvac AND l.id_city = :idcity', array(':idvac'=>"{$idvac}", ':idcity'=>$idcity));
                    $res = $Q1->queryScalar();

                    $fields = array(
                        'id_vac' => $idvac,
                        'id_city' => $idcity,
                        'npp' => $res+1,
                        'name' => $name,
                        'addr' => $addr,
                    );
                    if(!empty($metro)){
                        $arMetros = explode(',', $metro);
                        $fields['id_metro'] = $arMetros[0]; // записываем первую станцию
                        if(sizeof($arMetros)>1){
                            $fields['id_metros'] = substr($metro, (strlen($arMetros[0]) + 1)); // остальные записываем в одно поле через запятую
                        }
                        else{
                            $fields['id_metros'] = '';
                        }
                    }
                    $res = Yii::app()->db->createCommand()
                        ->insert('empl_locations', $fields);

                    $id = Yii::app()->db->createCommand('SELECT LAST_INSERT_ID()')->queryScalar();


                // редактируем локацию
                } else {
                    $fields = array(
                        'name' => $name,
                        'addr' => $addr,
                    );
                    if(!empty($metro)){
                        $arMetros = explode(',', $metro);
                        $fields['id_metro'] = $arMetros[0]; // записываем первую станцию
                        if(sizeof($arMetros)>1){
                            $fields['id_metros'] = substr($metro, (strlen($arMetros[0]) + 1)); // остальные записываем в одно поле через запятую
                        }
                        else{
                            $fields['id_metros'] = '';
                        }
                    }
                    else{
                        $fields['id_metro'] = 0;
                        $fields['id_metros'] = '';
                    }
                    $res = Yii::app()->db->createCommand()
                        ->update('empl_locations', $fields, 'id = :id', array(':id' => $idloc));
                } // endif


                // удаляем периоды локации
                $this->savePeriods(array($idloc == 'new' ? $id : $idloc, $bdate, $edate, $btime, $etime));

                $error = 0;
            } // endif
        }
        catch (Exception $e) {
            $error = $e->getCode();
            $message = $e->getMessage();
        } // endtry


        if( $error )
        {
            return array('error' => $error, 'message' => $message);
        }
        else
        {
            return array('error' => 100, 'message' => 'Данные успешно сохранены', 'id' => $id);
        } // endif
    }


    /**
     * Сохраняем периоды
     */
     private function savePeriods($inPer)
     {
         // удаляем периоды
         $res = Yii::app()->db->createCommand()->delete('emplv_loc_times', '`id_loc`=:idloc', array(':idloc' => $inPer[0]));

         $npp = 1;
         foreach ($inPer[1] ?: array() as $key => $val)
         {
             if( empty($inPer[4][$key]) || empty($inPer[1][$key]) || empty($inPer[2][$key]) || empty($inPer[3][$key]) )
                 throw new Exception("Неправильное заполнения периодов локации", -100);

             // сохраняем новые
             $arr = explode(':', $inPer[3][$key]);
             $btime = $arr[0] * 60 + $arr[1];
             $arr = explode(':', $inPer[4][$key]);
             $etime = $arr[0] * 60 + $arr[1];


             $res = Yii::app()->db->createCommand()
                 ->insert('emplv_loc_times', array(
                     'id_loc' => $inPer[0],
                     'npp' => $npp,
                     'bdate' => date("Y-m-d H:i:s", strtotime($inPer[1][$key])),
                     'edate' => date("Y-m-d H:i:s", strtotime($inPer[2][$key])),
                     'btime' => $btime,
                     'etime' => $etime,
                 ));

             $npp++;
         } // end foreach
     }



    /**
     * Удаляем город вакансии
     */
    public function delCityBlock()
    {
        $idvac = Yii::app()->session['editVacId'];
        $id = filter_var(Yii::app()->getRequest()->getParam('id'), FILTER_SANITIZE_NUMBER_INT);

        /** @var $Q1 CDbCommand */
        $Q1 = Yii::app()->db->createCommand()
            ->select('l.id')
            ->from('empl_locations l')
            ->where('l.id_vac = :idvac AND l.id_city = :idcity', array(':idvac'=>"{$idvac}", ':idcity'=>$id));
        $res = $Q1->queryAll();

        // есть локации
        if( count($res) > 0 )
        {
            foreach ($res as $key => $val)
            {
                $locans[] = $val['id'];
            } // end foreach

            // del times
            $res = Yii::app()->db->createCommand()->delete('emplv_loc_times', array('in', 'id_loc', ($locans)));
            // del locations
            $res = Yii::app()->db->createCommand()->delete('empl_locations', array('in', 'id', ($locans)));
        } // endif

        $res = Yii::app()->db->createCommand()->delete('empl_city', '`id`=:id AND id_vac = :idvac', array(':id' => $id, ':idvac' => $idvac));


        if( $res == 1 )
        {
            $message = 'Город удалён';
            $error = 100;
        }
        else
        {
            $message = 'Ошибка удаления города вакансии';
            $error = -101;
        } // endif

        if( $error )
        {
            return array('error' => $error, 'message' => $message);
        }
        else
        {
            return array('error' => 100, 'message' => 'Данные успешно удалены', 'id' => $id);
        } // endif
    }



    /**
     * Удаляем локацию
     */
    public function delLocation()
    {
        $idvac = Yii::app()->session['editVacId'];
        $id = filter_var(Yii::app()->getRequest()->getParam('id'), FILTER_SANITIZE_NUMBER_INT);

        try
        {
            /** @var $Q1 CDbCommand */
            $Q1 = Yii::app()->db->createCommand()
                ->select('l.id')
                ->from('empl_locations l')
                ->where('l.id_vac = :idvac AND l.id = :idloc', array(':idvac'=>"{$idvac}", ':idloc'=>$id));
            $res = $Q1->queryRow();

            // нет локации
            if( !$res['id'] ) throw new Exception('Ошибка удаления локации', -100);

            // del times
            $res = Yii::app()->db->createCommand()->delete('emplv_loc_times', 'id_loc = :idloc', array(':idloc' => $id));

            // del location
            $res = Yii::app()->db->createCommand()->delete('empl_locations', '`id`=:id', array(':id' => $id));


            if( $res == 1 )
            {
                throw new Exception('Локация удалена', 0);
            }
            else
            {
                throw new Exception('Ошибка удаления локации', -101);
            } // endif
        }
        catch (Exception $e) {
            $error = $e->getCode();
            $message = $e->getMessage();
        } // endtry


        if( $error )
        {
            return array('error' => $error, 'message' => $message);
        }
        else
        {
            return array('error' => 100, 'message' => 'Данные успешно удалены', 'id' => $id);
        } // endif
    }
    /**
     * Изменяем города определенной вакансии
     */
    public function changeCity()
    {
        $idvac = Yii::app()->session['editVacId'];
        $id = filter_var(Yii::app()->getRequest()->getParam('id'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $idcity = filter_var(Yii::app()->getRequest()->getParam('idcity'), FILTER_SANITIZE_NUMBER_INT);

        /** @var $Q1 CDbCommand */
        $Q1 = Yii::app()->db->createCommand()
            ->select('ci.id_city id')
            ->from('empl_city ci')
            ->where('ci.id_vac = :idvac AND ci.id_city = :idcity AND id <> :id', array(':idvac'=>"{$idvac}", ':idcity'=>$idcity, ':id'=>$id));
        $res = $Q1->queryRow();

        if($res['id']){ // если такой город уже есть
            $message = 'Такой город уже добавлен к вакансии';
            $error = -101;
        }
        elseif($idvac){
            $fields['id_city'] = $idcity;

            $res = Yii::app()->db->createCommand()
                ->update('empl_city', $fields, 'id = :id AND id_vac = :idvac', array(':id' => $id, ':idvac' => $idvac));
        }
        else{
            $message = 'Вакансия не определена';
            $error = -104;
        }

        if( $error ){
            return array('error' => $error, 'message' => $message);
        }
        else{
            return array('error' => 100, 'message' => 'Город изменен');
        }
    }

    /**
     * Изменяем данные локации
     */
    public function changeLocation()
    {
        $idvac = Yii::app()->session['editVacId'];
        $idloc = filter_var(Yii::app()->getRequest()->getParam('idloc'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $name = filter_var(Yii::app()->getRequest()->getParam('name'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $addr = filter_var(Yii::app()->getRequest()->getParam('addr'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $metro = filter_var(Yii::app()->getRequest()->getParam('metro'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        // проверка владения вакансией
        $iduser = Share::$UserProfile->exInfo->id;
        $sql = "SELECT v.id FROM empl_vacations v
                INNER JOIN empl_locations l ON l.id_vac = v.id AND l.id = {$idloc}
                WHERE v.id_user = {$iduser} AND v.id = {$idvac}";
        /** @var $res CDbCommand */
        $res = Yii::app()->db->createCommand($sql);
        $res = $res->queryRow();

        // вакансия редактируется или не пренадлежит пользователю
        $message = 'Ошибка сохранения локации';
        $error = -100;
                 
        if($res['id']){
            $fields = array(
                'name' => $name,
                'addr' => $addr
            );
            if(!empty($metro)){
                $arMetros = explode(',', $metro);
                $fields['id_metro'] = $arMetros[0]; // записываем первую станцию
                if(sizeof($arMetros)>1){
                    $fields['id_metros'] = substr($metro, (strlen($arMetros[0]) + 1)); // остальные записываем в одно поле через запятую
                }
                else{
                    $fields['id_metros'] = '';
                }
            }
            else{
                $fields['id_metro'] = 0;
                $fields['id_metros'] = '';
            }
            $res = Yii::app()->db->createCommand()
               ->update('empl_locations', $fields, 'id = :id', array(':id' => $idloc));
            $error = 0;
        }

        if($error){
            return array('error' => $error, 'message' => $message);
        }
        else{
            return array('error' => 100, 'message' => 'Локация изменена');
        }
    }
    /*
    *   
    */
    public function getMetroList($id_city, $filter, $select)
    {
        $filter = urldecode($filter);
        if(empty($select))
            $select = '0';

        $sql = "SELECT m.id, m.name 
            FROM metro m
            WHERE m.name like '%{$filter}%' 
                AND m.id_city = {$id_city} 
                AND m.id NOT IN({$select})
            LIMIT 10";
        $res = Yii::app()->db->createCommand($sql);
        $res = $res->queryAll();

        return $res;
    }

    /**
     * @param $id
     * @return array|CDbDataReader
     * @throws CException
     */
    public static function getCityNameById($id)
    {
        $city = Yii::app()->db->createCommand("
            SELECT 
                name
            FROM 
                city
            WHERE 
                id_city={$id}
        ")->queryAll();

        $city = $city[0]['name'];

        return $city;
    }

    /**
     * @param $id
     * @return mixed
     */
    public static function getCityIdByUserId($id)
    {
        $city = Yii::app()->db->createCommand("
            SELECT 
                id_city
            FROM 
                user_city
            WHERE 
                id_user={$id}
        ")->queryAll();

        $city = $city[0]['id_city'];

        return $city;
    }
}