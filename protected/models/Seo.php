<?php

/**
 * This is the model class for table "city".
 *
 * The followings are the available columns in table 'city':
 * @property integer $id_city
 * @property integer $ishide
 * @property string $name
 */
class Seo extends CActiveRecord
{
    public function exist($idOrUrl)
    {
        if((int)$idOrUrl)
            return Yii::app()->db->createCommand('SELECT * FROM seo WHERE id = '.(int)$idOrUrl)->queryRow();
        else
            return Yii::app()->db->createCommand('SELECT * FROM seo WHERE url = "'.$idOrUrl.'"')->queryRow();
    }

    public function existTemplate($url)
    {
    	if(!isset($_GET['template_url_params']))
    		return false;

        $xWord = isset($_GET['template_url_params']['promos']) ? 'promos' : 'vacancies';

    	if($_GET['template_url_params']['others'] == 0 && sizeof($_GET['template_url_params']['cities']) == 1 && sizeof($_GET['template_url_params']['occupations']) == 1)
    	{
    		$result = Yii::app()->db->createCommand('SELECT * FROM seo WHERE url = "/'.$xWord.'/[специальность]/[город]"')->queryRow();

    		if($result)
    		{
    			$city = reset($_GET['template_url_params']['cities']);

    			$result['meta_title'] = str_replace('[специальность]', reset($_GET['template_url_params']['occupations']), $result['meta_title']);
    			$result['meta_keywords'] = str_replace('[специальность]', reset($_GET['template_url_params']['occupations']), $result['meta_keywords']);
    			$result['meta_description'] = str_replace('[специальность]', reset($_GET['template_url_params']['occupations']), $result['meta_description']);
    			$result['seo_h1'] = str_replace('[специальность]', reset($_GET['template_url_params']['occupations']), $result['seo_h1']);
            

				$result['meta_title'] = str_replace('[город]', $city, $result['meta_title']);
				$result['meta_keywords'] = str_replace('[город]', $city, $result['meta_keywords']);
				$result['meta_description'] = str_replace('[город]', $city, $result['meta_description']);
				$result['seo_h1'] = str_replace('[город]', $city, $result['seo_h1']);

				return $result;
    		}
    	}
    	else if($_GET['template_url_params']['others'] == 0 && sizeof($_GET['template_url_params']['cities']) == 1 && !sizeof($_GET['template_url_params']['occupations']))
    	{
    		$result = Yii::app()->db->createCommand('SELECT * FROM seo WHERE url = "/'.$xWord.'/[город]"')->queryRow();

    		if($result)
    		{
    			$city = reset($_GET['template_url_params']['cities']);

				$result['meta_title'] = str_replace('[город]', $city, $result['meta_title']);
				$result['meta_keywords'] = str_replace('[город]', $city, $result['meta_keywords']);
				$result['meta_description'] = str_replace('[город]', $city, $result['meta_description']);
				$result['seo_h1'] = str_replace('[город]', $city, $result['seo_h1']);


				return $result;
    		}
    	}

    	return false;
    }

    public function saveNew($data)
    {
        $command = Yii::app()->db->createCommand();
        $command->insert('seo', array(
            'url' => $data['url'],
            'meta_title' => $data['meta_title'],
            'meta_description' => $data['meta_description'],
            'meta_keywords' => $data['meta_keywords'],
            'seo_h1' => $data['seo_h1'],
            'crdate' => date("Y-m-d h-i-s"),
            'index' => 0,
        ));
    }

    public function updateExist($data)
    {
        // Yii::app()->db->createCommand('UPDATE seo SET url = "'.$data['url'].'", meta_title = "'.$data['meta_title'].'", meta_description = "'.$data['meta_description'].'", meta_keywords= "'.$data['meta_keywords'].'", seo_h1 = "'.$data['seo_h1'].'" WHERE id = "'.$data['id'].'"')->execute();
            $res = Yii::app()->db->createCommand()
                    ->update('seo', array( 
                        'url' => $data['url'],
                        'meta_title' => $data['meta_title'],
                        'meta_description' => $data['meta_description'],
                        'meta_keywords' => $data['meta_keywords'],
                        'seo_h1' => $data['seo_h1'],
                        'mdate' => date("Y-m-d h-i-s"),
                        'index' => $data['index'],
                    ), 'id = :id', array(':id' => $data['id']));
        

    }




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
        return 'seo';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('url', 'required'),
            // array('ishide', 'numerical', 'integerOnly' => true),
            array('meta_title, meta_description, seo_h1', 'length', 'max' => 500),
            array('meta_keywords','safe'),
            array('url, index, meta_title, meta_description, meta_keywords, seo_h1', 'safe', 'on' => 'search'),
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
            'url' => 'URL',
            'meta_title' => 'Title',
            'meta_description' => 'Description',
            'meta_keywords' => 'Keywords',
            'seo_h1' => 'Seo H1',
            'index' => 'Index'
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
        $criteria->compare('id', $this->id);
        $criteria->compare('url', $this->url,true);
        $criteria->compare('meta_title', $this->meta_title,true);
        $criteria->compare('meta_description', $this->meta_description,true);
        $criteria->compare('meta_keywords', $this->meta_keywords,true);
        $criteria->compare('seo_h1', $this->seo_h1,true);
        $criteria->compare('mdate', $this->seo_h1,true);
        $criteria->compare('crdate', $this->seo_h1,true);
        $criteria->compare('index', $this->index,true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array('pageSize' => 20,),
            'sort' => ['defaultOrder'=>'mdate desc'],
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
        $where = array('t.name like :filter AND (t.id_co = :id OR :id = 0)', array(':filter' => "%{$filter}%", ':id' => $inID));

        $Q1 = Yii::app()->db->createCommand()
            ->select('t.id_city id, t.name, t.ismetro, t.id_co')
            ->from('city t')
            ->where($where[0], $where[1])
            ->limit($limit);
        $res = $Q1->queryAll();

        return $res;
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
                    if( $metro[0] > 0 ) $fields['id_metro'] = $metro[0];
                    $res = Yii::app()->db->createCommand()
                        ->insert('empl_locations', $fields);

                    $id = Yii::app()->db->createCommand('SELECT LAST_INSERT_ID()')->queryScalar();


                // редактируем локацию
                } else {
                    $fields = array(
                        'name' => $name,
                        'addr' => $addr,
                    );
                    if( $metro[0] > 0 ) $fields['id_metro'] = $metro[0];
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
     * Удаление
     */
    public function deleteSeo($id)
    {
        $command = Yii::app()->db->createCommand()->delete('seo', 'id=:id', array(':id'=>$id));
    }


    /*
    *   @param array $arParams(
    *       'firstname' - string,
    *       'lastname' - string,
    *       'cities' - array('name', 'region'),
    *       'posts' - array('isshow', 'val', 'pay', 'pay_type'),
    *       'isman' - boolean,
    *       'years' - string,
    *       'education' - string,
    *       'lang' - array()
    *   ) 
    */
    public static function getMetaForApp($arParams)
    {
        $arCities = array();
        $arVacancies = array();
        $arWages = array();
        $cities = '';
        $vacancies = '';
        $wage = '';
        $fio = $arParams['firstname'] . ' ' . $arParams['lastname'];

        foreach($arParams['cities'] as $city)
            isset($city) && $arCities[] = $city['name'] . '(е)'; // города

        foreach($arParams['posts'] as $p){
            switch ($p['pt']) {
                case 0: $p['pay_type'] ='руб. в час'; break;
                case 1: $p['pay_type'] ='руб. в неделю'; break;
                case 2: $p['pay_type'] ='руб. в месяц'; break;
                case 3: $p['pay_type'] ='руб. за посещение'; break;
            }

            if(!$p['isshow']){
                if($p['pay']>0) {
                    if(!sizeof($arWages)) {
                        $arWages = array(
                            'pt' => $p['pay_type'],
                            'pay' => round($p['pay']),
                        );
                    }
                    if($p['pay'] < $arWages['pay']) {
                        $arWages['pt'] = $p['pay_type'];
                        $arWages['pay'] = round($p['pay']);
                    }
                }
                else {
                    $wage = 'оплата по договоренности,'; 
                }
            }
            else {
                if($p['pname']=='без опыта')
                    $arVacancies[] = $p['val'] . ' без опыта работы';
                else
                    $arVacancies[] = $p['val'] . ' с опытом: ' . $p['pname'];                
            }
       }

        $arCities = array_unique($arCities);
        sort($arCities);

        if(sizeof($arCities)>0){       
          $cities = 'в ' . join(', ', $arCities) . ',';
        }
        if(sizeof($arVacancies)>0)
          $vacancies = ' ' . join(', ', $arVacancies) . ', ';

        if(sizeof($arWages)>0)
          $wage = ' ожидаемая оплата: от ' . $arWages['pay'] . ' ' . $arWages['pt'] . ', ';

        $sex = $arParams['isman'] ? 'мужской' : 'женский'; // пол
        $site = 
        $title = 'Резюме' . $vacancies 
            . $cities . ' - поиск сотрудников на ' 
            . Subdomain::getSubdomain($arParams['cities'])['name'];

        $description = "Резюме:" . $vacancies 
            . $cities . $wage . " возраст: " 
            . $arParams['years'] . ", пол: " . $sex;
        if(!empty($arParams['education']))
            $description .= ', образование:' . $arParams['education'];
        if(sizeof($arParams['lang'])>0)
            $description .= ', иностранные языки:' . join(', ',$arParams['lang']);

        $description = preg_replace("/\s{2,}/", " ", $description);


        return array(
                'meta_title' => $title,
                'meta_description' => $description
            );
    }
    /*
    *   @param array $arParams(
    *       'post' - array(),
    *       'city' - array(),
    *       'istemp' - boolean,
    *       'shour' - float,
    *       'sweek' - float,
    *       'smonth' - float,
    *       'svisit' - float,
    *       'isman' - boolean,
    *       'iswoman' - boolean,
    *       'agefrom' - int,
    *       'ageto' - int,
    *       'coname' - string,
    *   ) 
    */
    public static function getMetaForVac($arParams)
    {
        $cityName = current($arParams['city'])[0];
        $vacancies = strtolower(join(', ', $arParams['post'])); // вакансия(и)
        $city = ' в ' . $cityName . '(е) '; // город для заголовка
        $employ = $arParams['istemp'] ? 'Постоянная' : 'Временная';// вид занятости
        if( $arParams['shour'] > 0 ) $wage = $arParams['shour'] . ' руб/час' ;
        elseif( $arParams['sweek'] > 0 ) $wage = $arParams['sweek'] . ' руб/неделю' ;
        elseif( $arParams['smonth'] > 0 ) $wage = $arParams['smonth'] . ' руб/мес' ;
        elseif( $arParams['svisit'] > 0 ) $wage = $arParams['svisit'] . ' руб/посещение' ;
        else $wage = 'по договоренности';   // зп
        $sex = ($arParams['isman'] ? 'юноши' : '')
            . ($arParams['isman'] && $arParams['iswoman'] ? ', ' : '')
            . ($arParams['iswoman'] ? 'девушки' : ''); // пол
        $years = '';
        if($arParams['agefrom'] || $arParams['ageto']){
            $years = ($arParams['agefrom'] ? 'от ' . $arParams['agefrom'] : '')
                . ($arParams['ageto'] ? ' до ' . $arParams['ageto'] : '') 
                . 'лет';    // возраст
        }
        $h1 = 'Вакансия - ' . $vacancies . ' - оплата ' . $wage;
        $title = 'Вакансия ' . $vacancies . $city . ' - поиск работы на '
            . Subdomain::getSubdomain($arParams['city'])['name'];

        $description = $employ . ' вакансия от ' 
            . htmlspecialchars_decode(trim($arParams['coname'])) 
            . ': ' . htmlspecialchars_decode($vacancies) . ', ' 
            . $cityName . ', ' . $wage . ', Возраст: ' 
            . $years . ', Пол: ' . $sex;

        return array(
                'meta_h1' => $h1,
                'meta_title' => $title,
                'meta_description' => $description
            );
    }

    /*
    *       Ajax изменения СЕО параметров страницы
    */
    public function changeSeoParams($id,$param,$value)
    {
        if(isset($id) && isset($param)){
            return Yii::app()->db->createCommand()
                    ->update('seo', array( 
                        $param => $value,
                        'mdate' => date("Y-m-d h-i-s"),
                    ), 'id = :id', array(':id' => $id));
        }
        else
            return false;
    }
}