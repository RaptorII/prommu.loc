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
class Service extends CActiveRecord
{
  public $company_search;
  public $vacancy_search;
  public $type_custom;
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'service_cloud';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('id, name, sum,bdate, edate, status, type, id_user, key, date', 'required'),
            array('type, name', 'length', 'max'=>100),
            array('name', 'length', 'max'=>50),
            array('date', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, type, name, id_user, sum, bdate, edate, status, key, text, user, date', 'safe', 'on'=>'search'),
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
      return array();
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
      $get = Yii::app()->getRequest()->getParam('Service');
      $criteria=new CDbCriteria;

      $criteria->select = 't.*, e.name as company_search, ev.title as vacancy_search';
      $criteria->join = "LEFT JOIN employer e ON e.id_user=t.id_user "
       . "LEFT JOIN empl_vacations ev ON ev.id=t.name";
      $arCondition = [];

      if(!empty($get['company_search']))
      {
        $arCondition[] = "e.name LIKE '%" . $get['company_search'] . "%'";
        $this->company_search = $get['company_search'];
      }
      if(!empty($get['vacancy_search']))
      {
        $arCondition[] = "ev.title LIKE '%" . $get['vacancy_search'] . "%'";
        $this->vacancy_search = $get['vacancy_search'];
      }
      if($value=Share::checkFormatDate($get['bdate']))
      {
        $arCondition[] = "t.bdate='$value'";
        $this->bdate = $get['bdate'];
      }
      if($value=Share::checkFormatDate($get['edate']))
      {
        $arCondition[] = "t.edate='$value'";
        $this->edate = $get['edate'];
      }
      if(in_array($get['stack'],[1,2]))
      {
        $arCondition[] = ($get['stack']==1 ? "t.stack<>''" : "t.stack=''");
        $this->stack = $get['stack'];
      }
      if(in_array($get['key'],[1,2]))
      {
        $arCondition[] = ($get['key']==1 ? "t.key<>''" : "t.key=''");
        $this->key = $get['key'];
      }
      if(in_array($get['legal'],[1,2]))
      {
        $arCondition[] = ($get['legal']==1 ? "t.legal<>''" : "t.legal=''");
        $this->legal = $get['legal'];
      }

      if(count($arCondition))
      {
        $criteria->condition = implode(' and ', $arCondition);
      }

      $this->id = $get['id'];
      $this->sum = $get['sum'];
      $this->status = $get['status'];
      $this->is_new = $get['is_new'];
      $this->id_user = $get['id_user'];
      $this->name = $get['name'];
      $criteria->compare('t.id', $this->id, true);
      $criteria->compare('t.type', $this->type);
      $criteria->compare('t.sum', $this->sum, true);
      $criteria->compare('t.status', $this->status, true);
      $criteria->compare('t.is_new', $this->is_new, true);
      $criteria->compare('t.id_user', $this->id_user);
      $criteria->compare('t.name', $this->name);

      return new CActiveDataProvider($this, array(
        'criteria' => $criteria,
        'pagination' => ['pageSize' => 20],
        'sort' => ['defaultOrder' => 't.is_new desc, t.id desc'],
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
    
    /**
     * экспорт соискателей в админке
     */
    public function exportServices()
    {
        $offset = 0;
        $limit = 100; // вакансий за 1 итерацию
        $arRes = array(
            'items'=>[],
            'city'=>[],
            'responses'=>[],
            'employers'=>[],
            'views'=>[],
            'head' => [
                'ID Компании',
                'Название компании',
                'Название В.',
                'ID В.',
                'ID У.',
                'Название У.',
                'Платная / Бесплатная',
                'Стоимость',
                'Домены использования У.',
                'Дата и время создание У.',
                'Дата и время оплаты У.',
                'Дата и время отработки Услуги',
                'Повторный заказ У.',
                'Стоимость за повторный',
                'Дата снятия У.',
              ],
            'autosize' => [0,1,2,3,6,7,8,9,10,11,12,13,14]
          );
          
        $db = Yii::app()->db;
        $conditions = $params = [];
        $rq = Yii::app()->getRequest();
        
        $dateType = $rq->getParam('export_date');
        $bDate = $rq->getParam('export_beg_date');
        $eDate = $rq->getParam('export_end_date');
        
        $birthbDate = $rq->getParam('birthday_beg_date');
        $birtheDate = $rq->getParam('birthday_end_date');
        
        $status = $rq->getParam('export_status');
        $phones = $rq->getParam('export_phone');
        
        $bDate = date('Y-m-d',strtotime($bDate));
        $eDate = date('Y-m-d',strtotime($eDate));
        
        $birthbDate = date('Y-m-d',strtotime($birthbDate));
        $birtheDate = date('Y-m-d',strtotime($birtheDate));

        if($bDate!='1970-01-01')
        {
            switch ($dateType)
            {
                case 'create': 
                    $conditions[] = 'e.date_public>=:bdate';
                    $params[':bdate'] = $bDate . ' 00:00:00';
                    break;
            }   
        }
        if($eDate!='1970-01-01')
        {
            switch ($dateType)
            {
                case 'create': 
                    $conditions[] = 'e.date_public<=:edate';
                    $params[':edate'] = $eDate . ' 23:59:59';
                    break;
            }   
        }
        
        if($birthbDate!='1970-01-01')
        {
            switch ($dateType)
            {
                case 'create': 
                    $conditions[] = 'e.birthday>=:bsdate';
                    $params[':bsdate'] = $birthbDate . ' 00:00:00';
                    break;
            }   
        }
        if($birtheDate!='1970-01-01')
        {
            switch ($dateType)
            {
                case 'create': 
                    $conditions[] = 'e.birthday<=:esdate';
                    $params[':esdate'] = $birtheDate . ' 23:59:59';
                    break;
            }   
        }
        
        
        if($status!='all')
        {
            $conditions[] = 'u.ismoder =' . ($status=='active' ? '1' : '0');
        }
        
        
        $arId = $db->createCommand()
                                ->select("e.id, e.id_user, e.firstname,e.birthday, e.lastname, e.date_public, e.mdate, e.photo, e.card, e.cardPrommu,
                                          e.ismed, e.ishasavto, e.smart")
                                ->from('resume e')
                                ->join('user u', 'u.id_user=e.id_user')
                                ->where(implode(' and ',$conditions), $params)
                                ->order('e.id desc')
                                ->queryAll();

        $n = count($arId);
        if(!$n)
        {
          Yii::app()->user->setFlash('danger', 'Соискателей не найдено');
          return false;
        }
        
         
    
        foreach ($arId as $k => $v)
        {
            
            
            $time = $this->getOnlineTime($v['id']);
            //!empty($data['userAttribs']['mob']['val']) && 
           
                
                
                $data = $this->getUserExcelInfo($v['id_user']);
                $now = time(); 
                $your_date = strtotime($v['date_public']); 
                $datediff = $now - $your_date; 
                
                $days = floor($datediff / (60 * 60 * 24)); 
                $id = $v['id'];
                $id_user = $v['id_user'];
                $arT[$id]['id'] = $v['id'];
                $arT[$id]['fio'] = $v['firstname'].' '.$v['lastname'];
                $arT[$id]['birthday'] = $v['birthday'];
                if($v['photo']){
                    $arT[$id]['photo'] = "https://files.prommu.com/users/".$v['id'].'/'.$v['photo'].'.jpg';
                } else $arT[$id]['photo'] = "";
    
                $arT[$id]['photocount'] = 1;
                
                ///city
                $city = $this->getCityUserExcel($v['id_user']);
                
                $arT[$id]['country'] = $city['coname'];
                $arT[$id]['city'] = $city['name'];
                $arT[$id]['region'] = $city['region'];
                
                ///contact
                $arT[$id]['phone'] = $data['userAttribs']['mob']['val'];
                $arT[$id]['email'] = $data[0]['email'];
                $arT[$id]['skype'] = $data['userAttribs']['skype']['val'];
                $arT[$id]['whatsapp'] = $data['userAttribs']['whatsapp']['val'];
                $arT[$id]['viber'] = $data['userAttribs']['viber']['val'];
                $arT[$id]['telegram'] = $data['userAttribs']['telegram']['val'];
                $arT[$id]['messenger'] = $data['userAttribs']['google']['val'];
                
                ///дата создания
                $arT[$id]['crdate'] = $v['date_public'];
                $arT[$id]['mdate'] = $v['mdate'];
                $arT[$id]['edate'] = $v['mdate'];
                $arT[$id]['dedate'] = $v['mdate'];
                $arT[$id]['online'] = $v['mdate'];
                $arT[$id]['daysfromsite'] = $days;
                $arT[$id]['daysonline'] = $time['time'];
                
                ///вакансия
                
                $sql = "SELECT COUNT(id)
								FROM termostat_analytic
								WHERE user = {$id_user} 
									AND type = 'vacancy'";
			    $countvac = Yii::app()->db->createCommand($sql)->queryScalar();
			    
			    $sql = "SELECT COUNT(id)
								FROM vacation_stat
								WHERE id_promo = {$id} 
									AND isresponse = 1";
			    $countactivevac = Yii::app()->db->createCommand($sql)->queryScalar();
			    
			    $sql = "SELECT COUNT(id)
								FROM vacation_stat
								WHERE id_promo = {$id} 
									AND status IN (5,6,7)";
			    $countarchivevac = Yii::app()->db->createCommand($sql)->queryScalar();
			    
			    $sql = "SELECT COUNT(id)
								FROM vacation_stat
								WHERE id_promo = {$id} 
									AND status IN (3)";
			    $countinvitevac = Yii::app()->db->createCommand($sql)->queryScalar();
			    
			    
			    $sql = "SELECT COUNT(id)
								FROM vacation_stat
								WHERE id_promo = {$id} 
									AND status IN (7)";
			    $countresponsevac = Yii::app()->db->createCommand($sql)->queryScalar();
			
                $arT[$id]['countvac'] = $countvac;
                $arT[$id]['countactivevac'] = $countactivevac;
                $arT[$id]['countarchivevac'] = $countarchivevac;
                $arT[$id]['countinvitevac'] = $countinvitevac;
                $arT[$id]['countresponsevac'] = $countresponsevac;
                $arT[$id]['countrefusedvac'] = "";
                
                
                ///рейтинг
                $arT[$id]['countrating'] = "";
                $arT[$id]['feedback'] = "";
                $arT[$id]['countratingpromo'] = "";
                
                
                ///наличие атрибутов
                $arT[$id]['ismed'] = $v['ismed'];
                $arT[$id]['cardPrommu'] = $v['cardPrommu'];
                $arT[$id]['card'] = $v['card'];
                $arT[$id]['smart'] = $v['smart'];
                $arT[$id]['ishasavto'] = $v['ishasavto'];
            
        }
        
         $arRes['items'] = $arT;
         
        return $arRes;
        
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

    public function setViewed($id, $cnd) {
        return Yii::app()->db->createCommand()->update(
            $this->tableName(),
            ['is_new' => $cnd],
            'id=:id',
            [':id' => $id]
        );
    }
  /**
   * @param $id
   * @return array
   * Данные о отдельном заказе
   */
  public function getOrder($id)
  {
    $arRes = [];
    $arRes['item'] = $this::model()->findByPk($id);
    if($arRes['item'])
    {
      $id_user = $arRes['item']['id_user'];
      $arRes['employer'] = Share::getUsers([$id_user])[$id_user];
      $model = new Vacancy();
      $arRes['vacancy'] = $model->getVacanciesById($arRes['item']['name'])[0];
      if(in_array($arRes['item']['type'],['email','sms','push']))
      {
        $arRes['applicants'] = Share::getUsers(Share::explode($arRes['item']['user']));
      }
    }

    return $arRes;
  }
  /**
   * @param $id
   * @return int
   */
  public function setAdminViewed($id)
  {
    return $this::model()->updateByPk($id,['is_new'=>0]);
  }
  /**
   * Сохранение услуги и запуск услуги админом
   */
  public function setData()
  {
    $arParam = Yii::app()->getRequest()->getParam('Service_cloud');
    $this::model()->updateByPk(
      $arParam['id'],
      ['status'=>$arParam['status'], 'key'=>$arParam['key']]
    );
    $message = 'Данные успешно сохранены';
    if($arParam['start_service'] && !empty($arParam['legal']))
    {
      $stack = time();
      $this::model()->updateAll(
        ['status'=>1, 'key'=>'Запустил администратор', 'stack'=>$stack],
        'legal=:legal',
        [':legal'=>$arParam['legal']]
      );

      $arServices = Yii::app()->db->createCommand()
        ->from($this->tableName())
        ->where('legal=:legal', [':legal'=>$arParam['legal']])
        ->queryAll();

      foreach ($arServices as $v)
      {
        if($v['type']=='vacancy') // premium
        {
          $model = new Vacancy();
          $model->updateParam($v['name'],['ispremium'=>1]);
        }
        if(in_array($v['type'],['email','sms'])) // email, sms
        {
          $model = new PrommuOrder();
          $model->autoOrder(
            $v['type'],
            $stack,
            $v['id_user'],
            $v['name']
          );
        }

        if($v['type']=='personal-invitation') // personal-invitation
        {
          // set invite after send moneypay-service unitpay
          $arRes = Yii::app()->db->createCommand()
            ->select('id, id_user, name, user')
            ->from('service_cloud')
            ->where(
              'id_user=:idp and id=:id',
              [
                ':idp'=>$v['id_user'],
                ':id' =>$v['id']
              ])
            ->queryRow();

          $props = ['idvac'=>$v['name']];

          if (explode(',',$v['user'])) {
            $users = explode(',', $v['user']);
          } else {
            $users = $v['user'];
          }

          for($i=0; $i<=count($users); ++$i)
          {
            $props['id'] = Yii::app()->db->createCommand()
              ->select('id')
              ->from('resume')
              ->where(
                'id_user=:idp',
                [':idp'=>$users[$i]]
              )
              ->queryScalar();

            if ($props['id']) {
              (new ResponsesApplic())->invitePersonal($props);
            }
          }
        }
      }
      $message = 'Услуга "' . Services::getServiceName($arParam['service']) . '" запущена';
    }
    Yii::app()->user->setFlash('success', $message);
  }
  /**
   *  счетчик непросмотренных услуг
   */
  public static function getAdminCnt()
  {
    $arRes = ['cnt'=>0];

    $query = Yii::app()->db->createCommand()
      ->select('count(id) cnt, type')
      ->from('service_cloud')
      ->where('is_new=1')
      ->group('type')
      ->queryAll();

    if(count($query))
    {
      foreach($query as $v)
      {
        $arRes[$v['type']] = $v['cnt'];
        $arRes['cnt'] += $v['cnt'];
      }
    }
    return $arRes;
  }
}
