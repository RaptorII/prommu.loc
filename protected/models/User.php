<?php

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property integer $id_user
 * @property string $login
 * @property string $passw
 * @property string $access_time
 * @property integer $booble_index
 * @property string $ip
 * @property integer $status
 * @property integer $isblocked
 */
class User extends CActiveRecord
{
    static public $SCOPE_ACTIVE = 1;


	/**
	 * Returns the static model of the specified AR class.
	 * @return User the static model class
	 */
	public $confirm;


	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'user';
	}


	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_user, login, passw, email, confirm', 'required'),
			array('booble_index, status, isblocked, id_user', 'numerical', 'integerOnly'=>true),
			array('login, passw', 'length', 'max'=>64),
			array('email','email'),
			array('id_user, login, email, booble_index, status, isblocked', 'safe', 'on'=>'search'),
		);

	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
				'employer'=>array(self::HAS_MANY, 'Employer', 'id_user'),
				'resume'=>array(self::HAS_MANY, 'Promo', 'id_user')
			);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id_user' => '#',
			'login' => Share::lng('ANEM_LOGIN'),
			'passw' => Share::lng('ANEM_PASSW'),
			'email' => Share::lng('AL_EMAIL'),
			'access_time' => 'Дата входа',
			'booble_index' => 'INDEX',
			'ip' => 'Ip',
			'status' =>'Статус',
			'isblocked' => '+/-',
			'confirm' => Share::lword('CONFIRM'),
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */

	public function searchpr()
    {

        $criteria=new CDbCriteria;
   
        return new CActiveDataProvider('Promo', array(
            'criteria'=>$criteria,
            'pagination' => array('pageSize' => 20,),
            'sort' => ['defaultOrder'=>'id_user desc'],
        ));
    }



	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id_user',$this->id_user);

		$criteria->compare('login',$this->login,true);

		$criteria->compare('passw',$this->passw,true);

		$criteria->compare('email',$this->email,true);

		$criteria->compare('access_time',$this->access_time,true);

		$criteria->compare('booble_index',$this->booble_index);

		$criteria->compare('ip',$this->ip,true);

		$criteria->compare('status',$this->status);

		$criteria->compare('isblocked',$this->isblocked);

		return new CActiveDataProvider('User', array(
			'criteria'=>$criteria,
			'pagination' => array('pageSize' => 500,),
			'sort' => ['defaultOrder'=>'access_time desc'],
		));
	}
	/**
	 * 
	 */
  public function searchAll()
  {
		$arGet = Yii::app()->getRequest()->getParam('User');
		$page = Yii::app()->getRequest()->getParam('page');
		$sort = Yii::app()->getRequest()->getParam('sort');
		$dir = Yii::app()->getRequest()->getParam('dir');
		$arRes = [
				'id' => [],
				'limit' => 20,
				'items' => []
			];
		$arRes['offset'] = ($page-1) * $arRes['limit'];
		$arRes['offset']<0 && $arRes['offset']=0;

		$order = empty($sort) ? "id_user desc" : "$sort $dir";
		$rCondition = ["r.id_user is not null"];
		$eCondition = ["e.id_user is not null"];
		// id_user
		$value = filter_var($arGet['id_user'], FILTER_SANITIZE_NUMBER_INT);
		if($value>0)
		{
			$rCondition[]="r.id_user={$value}";
			$eCondition[]="e.id_user={$value}";
		}
		// status
		$value = $arGet['status'];
		if(in_array($value, ['2','3']))
		{
			$rCondition[]="u.status={$value}";
			$eCondition[]="u.status={$value}";
		}
		// name, firsname, lastname
		$value = filter_var($arGet['name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		if(!empty($value))
		{
			$rCondition[]="(r.firstname like '%{$value}%' or r.lastname like '%{$value}%')";
			$eCondition[]="e.name like '%{$value}%'";
		}
		$value = trim($arGet['email']);
		$value = filter_var($value, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		if(!empty($value))
		{
			$rCondition[]="u.email like '%{$value}%'";
			$eCondition[]="u.email like '%{$value}%'";
		}
		// date creating
		$value = $this->setDateQuery('r','date_public','cbdate','cedate');
		!empty($value) && $rCondition[] = $value;
		$value = $this->setDateQuery('e','crdate','cbdate','cedate');
		!empty($value) && $eCondition[] = $value;
		// date moderation
		$value = $this->setDateQuery('r','mdate','mbdate','medate');
		!empty($value) && $rCondition[] = $value;
		$value = $this->setDateQuery('e','mdate','mbdate','medate');
		!empty($value) && $eCondition[] = $value;
		// isblocked
		$value = $arGet['isblocked'];
		if(in_array($value, ['0','1','2','3','4']))
		{
			$rCondition[]="u.isblocked={$value}";
			$eCondition[]="u.isblocked={$value}";
		}
		// ismoder
		$value = $arGet['ismoder'];
		if(in_array($value, ['0','1']))
		{
			$rCondition[]="r.ismoder={$value}";
			$eCondition[]="e.ismoder={$value}";
		}
		//
  	$rCondition = implode(' and ',$rCondition);
  	$eCondition = implode(' and ',$eCondition);
  	
		$arRes['id'] = Yii::app()->db->createCommand()
										->select('u.id_user')
										->from('user u')
										->rightjoin('resume r','r.id_user=u.id_user')
										->where($rCondition)
										->order($order)
										->union("SELECT u.id_user 
															FROM user u 
															RIGHT JOIN employer e ON e.id_user=u.id_user 
															WHERE {$eCondition}")
										->queryColumn();

		if(!count($arRes['id']))
			return $arRes;

		$arId = [];
		for ($i=$arRes['offset'], $n=count($arRes['id']); $i<$n; $i++ )
		{
			if(($i < ($arRes['offset'] + $arRes['limit'])) && isset($arRes['id'][$i]))
				$arId[] = $arRes['id'][$i];
		}

		$arRes['items'] = Yii::app()->db->createCommand()
												->select('
													u.id_user, 
													u.status,
													u.email, 
													e.name,
													r.firstname,
													r.lastname,
													e.crdate ecdate,
													r.date_public rcdate,
													e.mdate emdate,
													r.mdate rmdate,
													u.isblocked,
													u.ismoder,
													u.messenger,
													u.is_online')
												->from('user u')
												->leftjoin('employer e','e.id_user=u.id_user')
												->leftjoin('resume r','r.id_user=u.id_user')
												->where(['in','u.id_user',$arId])
												->order("u.$order")
												->queryAll();

		$arRes['pages'] = new CPagination(count($arRes['id']));
		$arRes['pages']->pageSize = $arRes['limit'];
		$objPager = (object)['limit'=>$arRes['limit'],'offset'=>$arRes['offset']];
		$arRes['pages']->applyLimit($objPager);

		return $arRes;
  }
  /**
   * 
   */
	private function setDateQuery($prefix, $column, $p1, $p2)
	{
		$rq = Yii::app()->getRequest();
		$d1 = Share::checkFormatDate($rq->getParam($p1));
		$d2 = Share::checkFormatDate($rq->getParam($p2));
		if($d1 && $d2)
		{
			return "({$prefix}.{$column} between '{$d1} 00:00:00' and '{$d2} 23:59:59')";
		}
		elseif($d1)
		{
			return "{$prefix}.{$column} >= '{$d1} 00:00:00'";
		}
		elseif($d2)
		{
			return "{$prefix}.{$column} <= '{$d2} 23:59:59'";
		}
	}
  /**
   * 
   */
	public static function getIsBlockedArray()
	{
		return [
				'активен',
				'заблокирован',
				'ожидает активации',
				'активирован',
				'не отображается'
			];
  }
	// *** Services ***
	public function blocked($id, $st)
	{

   		Yii::app()->db->createCommand()
            ->update('employer', array(
    		'isblocked'=>$st),
                'id_user=:id', array(':id'=>$id));

            Yii::app()->db->createCommand()
            ->update('user', array(
    		'isblocked'=>$st),
                'id_user=:id', array(':id'=>$id));

           

	}	

	public function blockedPromo($id, $st)
	{

   		Yii::app()->db->createCommand()
            ->update('resume', array(
    		'isblocked'=>$st),
                'id_user=:id', array(':id'=>$id));

            Yii::app()->db->createCommand()
            ->update('user', array(
    		'isblocked'=>$st),
                'id_user=:id', array(':id'=>$id));

           



	}	

	public function commandrate($cloud, $id){

		$cloud['message'] = trim(preg_replace('/&lt;([\/]?(?:div|b|i|br|u))&gt;/i', "<$1>", $cloud['message']));
        Yii::app()->db->createCommand()
                    ->insert('comments', array(
                        'id_promo' => $id,
                        'id_empl' => $cloud['id_empl'],
                        'message' => $cloud['message'],
                        'iseorp' => 0,
                        'isneg' => $cloud['isneg'],
                        'isactive' => 1,
                        'processed' => 0,
                        'crdate' => date("Y-m-d H:i:s"),
                    ));
        $rate= $cloud['rate'];
        foreach ($rate as $key => $val) {
         Yii::app()->db->createCommand()
                    ->insert('rating_details', array(
                        'id_userf' => $id,
                        'id_user' => $cloud['id'],
                        'id_vac' => $cloud['vac'],
                        'id_point' => $key,
                        'point' => $val,
                        'crdate' => date("Y-m-d H:i:s"),
                    ));
        }
        return  $data = ['error' => '0', 'message' => 'CommAndRate sent'];
	}

	public function newUser()
	{
		$this->ip = '0.0.0.0';
		$this->access_time = '0';
		$this->passw = md5('123456');
		$this->isblocked = '2';
		return $this;
	}

	/**
	 * @deprecated
	 * used function updatePromo($data, $id)
	 *
	 * @param $id
	 * @param $attributes
	 * @return bool
	 */
	public function updateUser($id, $attributes)
	{
   		if($id==0){
   			$md = new User;
   			$md->newUser();
   			$md->id_user=null;
   		}
   		else {
   			$md = User::model()->findByPk($id);
   		}
   		$md->login = $attributes['login'];
   		$md->email = $attributes['email'];
   		$md->status = $attributes['status'];
   		$md->isblocked = $attributes['isblocked'];
   		$md->booble_index = $attributes['booble_index'];
   		return $md->save();
	}



	public function updatePromo($data, $id) {
		if(empty($id) || $id<=0) return null;

		Yii::app()->db->createCommand()
			->update('user', array(
				'ismoder' => $data['ismoder'],
				'status' => 2,
				'isblocked' => $data['isblocked'],
			), 'id_user=:id_user', array(':id_user' => $id));

        if(isset($data['email'])) {
            Yii::app()->db->createCommand()
                ->update('user', array(
                    'email' => $data['email'],
                ), 'id_user=:id_user', array(':id_user' => $id));

            //email to promo
            if (isset($data['ismoder'])) {
                $message = sprintf("Ваша анкета прошла модерацию.");
                Share::sendmail($data['email'], "Prommu.com Модерация соискателя" . $id, $message);
            }
        }

 		//Основная информация
		Yii::app()->db->createCommand()
			->update('resume', array(
				'firstname' => $data['firstname'],
				'lastname' => $data['lastname'],
				'smart' => $data['smart'],
				'cardPrommu' => $data['cardPrommu'],
				'card' => $data['card'],
				'birthday' => Share::dateFormatToMySql($data['birthday']),
				'ismed' => $data['ismed'],
				'ishasavto' => $data['ishasavto'],
				'isman' => $data['isman'],
				'ismoder' => $data['ismoder'],
				'isblocked' => $data['isblocked'],
				'aboutme'=> $data['aboutme'],
				'index'=> $data['index'],
				'meta_title'=> $data['meta_title'],
				'meta_h1'=> $data['meta_h1'],
				'meta_description'=> $data['meta_description'],
				'comment' => $data['comment'],
                'is_new' => (integer) !$data['ismoder'],
				
			), 'id_user=:id_user', array(':id_user' => $id));

        if (isset($data['city'])) {
            $city = Yii::app()->db->createCommand()
                ->select('*')
                ->from('user_city')
                ->where("id_user=:id_user", array(':id_user' => $id))
                ->queryRow();

            if (count($city)) {

                Yii::app()->db->createCommand()
                    ->update('user_city', array(
                        'id_city' => $data['city'],
                    ), "id_user=:id_user", array(':id_user' => $id));

            } else {

                Yii::app()->db->createCommand()
                    ->insert('user_city', array(
                        'id_user' => $id,
                        'id_city' => $data['city'],
                    ));
            }
        }
    		
        if(count($data['userAttribs']))
    		{
        	$attr = $data['userAttribs'];
        	Yii::app()->db->createCommand()->delete(
	        		'user_attribs',
	        		'id_us=:id_user',
	        		array(':id_user' => $id)
	        	);
        
            foreach($attr as $key=>$val)
            {

                if(!empty($val))
                {
                        $result = Yii::app()->db->createCommand()
                                                        ->select('*')
                                                        ->from('user_attribs')
                                                        ->where(
                                                            "id_us=:id_user and `key`=:key",
                                                            array(':id_user'=>$id, ':key'=>$key)
                                                        )
                                                        ->queryRow();
                    if($result['key'] == $key)
                    {
                        Yii::app()->db->createCommand()
                            ->update(
                                'user_attribs',
                                array('val' => $val),
                                "id_us=:id_user and `key`=:key",
                                array(':id_user' => $id, ':key' => $key)
                                );
                    }
                    else {
                        $userdict = Yii::app()->db
                            ->createCommand()
                            ->select('d.id , d.type, d.key')
                            ->from('user_attr_dict d')
                            ->where('d.key = :key', array(':key' => $key))
                            ->queryRow();

                        Yii::app()->db->createCommand()
                            ->insert('user_attribs', array(
                            'id_attr' => $userdict['id'],
                            'type' => $userdict['type'],
                            'val' => $val,
                            'key' => $key,
                            'id_us' => $id,
                            'crdate' => date("Y-m-d H:i:s")
                        ));
                    }
                }
            }
        }

    		if(count($data['bodyAttribs']))
    		{
        	$body = $data['bodyAttribs'];

					foreach($body as $key=>$val)
					{
						if(!empty($val))
						{
							$result = Yii::app()->db->createCommand()
													->select('*')
													->from('user_attribs')
													->where(
														"id_us=:id_user and `key`=:key", 
														array(':id_user'=>$id, ':key'=>$key)
													)
													->queryRow();

							if($result['key'] == $key)
							{
								$userdict = Yii::app()->db->createCommand()
															->select('d.id , d.type, d.key')
															->from('user_attr_dict d')
															->where('d.id = :key', array(':key' => $val))
															->queryRow();

								Yii::app()->db->createCommand()->update(
									'user_attribs',
									array('val' => $userdict['name']),
									"id_us=:id_user and `key`=:key",
									array(':id_user' => $id, ':key' => $key)
								);
							}
							else
							{
								$userdict = Yii::app()->db->createCommand()
															->select('d.id , d.type, d.key')
															->from('user_attr_dict d')
															->where('d.id = :key', array(':key' => $val))
															->queryRow();

								Yii::app()->db->createCommand()
									->insert(
											'user_attribs', 
											array(
												'id_attr' => $userdict['id'],
												'type' => $userdict['type'],
												'val' => $val,
												'key' => $key,
												'id_us' => $id,
												'crdate' => date("Y-m-d H:i:s")
											)
										);
							}
						}
					}
    		}
			
    for($i = 0; $i < count($data['workDays']); $i++){
        $insData[] = array('id_city' => $data['workDays'][$i]->id_city, 'id_us' => $id, 'wday' => $data['workDays'][$i]->day, 'timeb' => $data['workDays'][$i]->timeb, 'timee' => $data['workDays'][$i]->timee);
    }

    if( count($insData) ){
        Yii::app()->db->createCommand()->delete('user_wtime', 'id_us=:id_user', array(':id_user' => $id));
        $command = Yii::app()->db->schema->commandBuilder->createMultipleInsertCommand('user_wtime', $insData);
        $res = $command->execute();
    }
    
   
    for($i = 0; $i < count($data['userMech']); $i++)
    {
        $insDatas[] = array('id_us' => $id, 'id_mech' => $data['userMech'][$i]->id_mech, 'isshow' => $data['userMech'][$i]->isshow, 'namme' => '', 'pay' => $data['userMech'][$i]->pay, 'pay_type' => $data['userMech'][$i]->paylims, 'crdate' => date("Y-m-d H:i:s"));
    } // end foreach

    if( count($insDatas) ){
        Yii::app()->db->createCommand()
        ->delete('user_mech', array('and', 'id_us=:id_user', 'isshow=0'), array(':id_user' => $id));
        $command = Yii::app()->db->schema->commandBuilder->createMultipleInsertCommand('user_mech', $insDatas);
        $command->execute();
    }
			
    return array('error'=>0,  'message'=>'success' ,'sendmail'=>0); 
	}


	public function updateEmployer($data, $id) {
		if(empty($id) || $id<=0) return null;

		if(
			isset($data['send-private-manager-mail'])
			&&
			strpos($data['email'], "@") !== false 
		){
// 			$content = file_get_contents(Yii::app()->basePath . "/views/mails/private-manager.html");
// 			$content = str_replace('#EMPLOYER#', $data['firstname'].' '.$data['lastname'], $content);
// 			$content = str_replace('#MANAGER#', "Светлана", $content);
// 			$content = str_replace('#MANAGER_FIO#', "Гусева Светлана", $content);
// 			$content = str_replace('#PHONE#', "+74996535185", $content);
// 			$content = str_replace('#PHONE_MOB#', "+74996535185", $content);
// 			$content = str_replace('#EMAIL#',"account_manager1@prommu.com", $content);
// 			$content = str_replace('#COMPANY#', $data['name'], $content);
//
// 			$result = Share::sendmail($data['email'], "Prommu: Аккаунт Менеджер", $content);



			Yii::app()->db->createCommand()
				->update(
					'employer', 
					array('accountmail' => 1), 
					'id_user=:id_user', 
					array(':id_user' => $id)
				);

			return array(
				'error'=>0,
				'sendmail'=>1,
				'result'=>$result
			);
		}
		else{
			// Update table user_attribs
			if(isset($data['email'])) {
				$res = Yii::app()->db->createCommand()
					->update('user', array(
						'email' => $data['email'],
						'ismoder' => $data['ismoder'],
						'isblocked' => $data['isblocked'],
						//'date_login' => date('Y-m-d H:i:s'),
					), 'id_user=:id_user', array(':id_user' => $id));
			}

			//email to employer
			if (isset($data['ismoder'])) {
                $message = sprintf("Ваша анкета прошла модерацию.");
                Share::sendmail($data['email'], "Prommu.com Модерация работодаля" . $id, $message);
            }

			Yii::app()->db->createCommand()
				->update('user', array(
					'ismoder' => $data['ismoder'],
					'isblocked' => $data['isblocked'],
				), 'id_user=:id_user', array(':id_user' => $id));

			Yii::app()->db->createCommand()
				->update('employer', array(
					'name' => $data['name'],
					'type' => $data['type'],
					'firstname' => $data['firstname'],
					'lastname' => $data['lastname'],
					'position' => $data['post'],
					'ismoder' => $data['ismoder'],
                    'is_new' => (integer) !$data['ismoder'],
					'isblocked' => $data['isblocked'],
					'city' => $data['city'],
					'contact' => $data['contact'],
					'aboutme' => $data['aboutme'],
					), 'id_user=:id_user', array(':id_user' => $id));

			$attr[] = $data['userAttribs'];
			
			foreach($attr as $key=>$val) {
				Yii::app()->db->createCommand()
					->update('user_attribs', array(
						'val' => $val,
					), "id_us=:id_user and `key`=:key", array(':id_user' => $id, ':key' => $key));
			}
			return array('error'=>0,  'message'=>'success' ,'sendmail'=>0);
		}
	}
	
	
	public function updateEmployerApi($data, $id) {
		if(empty($id) || $id<=0) return null;
	
			Yii::app()->db->createCommand()
				->update('employer', array(
					'name' => $data['name'],
					'type' => $data['type'],
					'firstname' => $data['firstname'],
					'lastname' => $data['lastname'],
					'position' => $data['post'],
					'city' => $data['city'],
					'contact' => $data['contact'],
					'position' => $data['position'],
					'web' => $data['web'],
					), 'id_user=:id_user', array(':id_user' => $id));
			   
			   
			///city
            
            $city = Yii::app()->db->createCommand()
    			->select('*')
    			->from('user_city')
                ->where("id_user=:id_user", array(':id_user'=>$id))
    			->queryRow();
    			
    		if(count($city)){
    		    
    		   Yii::app()->db->createCommand()
					->update('user_city', array(
						'id_city' => $data['city'],
					), "id_user=:id_user", array(':id_user' => $id));
				
    		} else {
    		    
    		    Yii::app()->db->createCommand()
                    ->insert('user_city', array(
                        'id_user' => $id,
                        'id_city' => $data['city'],
                    ));
    		}
    		
    		///attribs
			$attr = $data['userAttribs'];
		
            Yii::app()->db->createCommand()->delete('user_attribs', 'id_us=:id_user', array(':id_user' => $id));
            
            foreach($attr as $key=>$val) {
			    $result = Yii::app()->db->createCommand()
    			->select('*')
    			->from('user_attribs')
                ->where("id_us=:id_user and `key`=:key", array(':id_user'=>$id, ':key'=>$key))
    			->queryRow();
    			
    			if($result['key'] == $key){
    			    Yii::app()->db->createCommand()
					->update('user_attribs', array(
						'val' => $val,
					), "id_us=:id_user and `key`=:key", array(':id_user' => $id, ':key' => $key));
				
    			} else {

            		$userdict = Yii::app()->db->createCommand()
                        ->select('d.id , d.type, d.key')
                        ->from('user_attr_dict d')
                        ->where('d.key = :key', array(':key' => $key))
                        ->queryRow();

            			
    			    Yii::app()->db->createCommand()
                    ->insert('user_attribs', array(
                        'id_attr' => $userdict['id'],
                        'type' => $userdict['type'],
                        'val' => $val,
                        'key' => $key,
                        'id_us' => $id,
                        'crdate' => date("Y-m-d H:i:s")
                    ));
    			}
				
			}


		
			return array('error'=>0,  'message'=>'success' ,'sendmail'=>0);
		
	}

	public function getUsers($type)
	{
		$result = Yii::app()->db->createCommand()
    			->select('id_user, login, passw, email, access_time, booble_index, ip, status, isblocked')
    			->from('user')
                ->where('status=:status', array(':status'=>$type))
    			->order('booble_index, id_user')
    			->queryAll();
    	return $result;
	}
	/*
	*
	*/
	public function getUserEmpl($id)
	{
		$result = Yii::app()->db->createCommand()
    		->select("r.id, u.id_user, u.login, u.passw, 
    			u.email, u.access_time, u.status, 
    			u.isblocked, u.ismoder, r.firstname, r.contact, r.aboutme,
    			r.logo, r.lastname, r.name, r.type, r.city, r.rate, r.rate_neg"
    		)
			->leftjoin('employer r', 'r.id_user=u.id_user')
    		->from('user u')
		    ->where('u.id_user=:id', array(':id'=>$id))
		    ->queryRow();

		$result['src'] = Share::getPhoto($result['id_user'],3,$result['logo']);

		$attr = Yii::app()->db->createCommand()
			->select("*")
			->from('user_attribs')
			->where('id_us=:id', array(':id'=>$id))
			->queryAll();
		$arr_at = [];
		foreach($attr as $at) {
			if(!empty($at['key'])) {
				$arr_at[$at['key']] = $at['val'];
			}
		}
		$result['attr']=$arr_at;
		//
		// photo
		$result['photos'] = Yii::app()->db->createCommand()
			->select("p.id, p.photo, p.signature")
			->from('employer r')
			->leftjoin('user_photos p', 'p.id_empl = r.id')
			->where('r.id=:id', array(':id'=>$result['id']))
			->queryAll();

		if(sizeof($result['photos']))
		{
			foreach($result['photos'] as &$item)
			{
				$item['orig'] = Share::getPhoto($result['id_user'], 3, $item['photo'], 'big');
				$item['photo'] = Share::getPhoto($result['id_user'], 3, $item['photo'], 'medium');
			}
			unset($item);
		}
		else{
			$result['photos'] = array();
		}

		$arIdies = array();
        // читаем вакансии
        $sql = "SELECT v.id, v.title, v.status, v.count, 
        			v.vk_link, v.fb_link, v.tl_link, 
        			DATE_FORMAT(v.crdate, '%d.%m.%Y') crdate, 
        			DATE_FORMAT(v.remdate, '%d.%m.%Y') remdate, 
        			t.bdate, t.edate, vs.id_promo, 
        			v.ispremium, v.repost, v.ismoder, 
        			vs.isresponse + 1 isresp
	            FROM empl_vacations v
	            INNER JOIN ( 
	            	SELECT v.id 
	            	FROM empl_vacations v 
	            	WHERE v.id_user = {$id} 
	            	ORDER BY v.id DESC
	            ) t1 ON t1.id = v.id 
	            LEFT JOIN vacation_stat vs ON vs.id_vac = v.id 
	            LEFT JOIN empl_locations l ON v.id = l.id_vac 
	            LEFT JOIN emplv_loc_times t ON l.id = t.id_loc
	            LEFT JOIN employer e ON e.id_user = v.id_user
	            WHERE v.id_user = {$id}
	            AND v.status=1 AND v.in_archive=0
	            ORDER BY v.id DESC";
        
        $res = Yii::app()->db->createCommand($sql)->queryAll();
        foreach ($res as $key => $val)
        {
        	$arIdies[] = $val['id'];
            $Termostat = new Termostat();
            $result['analytic'][$val['id']] = $Termostat->getTermostatCount($val['id']);

            if( !isset($result['vacancies'][$val['id']]) ) 
            	$result['vacancies'][$val['id']] = array_merge(
            		$val, 
            		array('isresp' => array($val['count'],0)
            	)
            );
            if( $val['isresp'] ) 
            	$result['vacancies'][$val['id']]['isresp'][$val['isresp']-1]++;
            $result['vacancies'][$val['id']]['link'] = '/admin/VacancyEdit/' . $val['id'];
        }
        //
        // читаем архив
        $sql = "SELECT v.id, v.title, v.status, v.count, 
        			DATE_FORMAT(v.crdate, '%d.%m.%Y') crdate, 
        			DATE_FORMAT(v.remdate, '%d.%m.%Y') remdate, 
        			vs.id_promo, vs.isresponse + 1 isresp
	            FROM empl_vacations v
	            INNER JOIN (
	            	SELECT v.id 
	            	FROM empl_vacations v 
	            	WHERE v.id_user = {$id} 
	            	ORDER BY v.id DESC
	            ) t1 ON t1.id = v.id 
	            LEFT JOIN vacation_stat vs ON vs.id_vac = v.id 
	            LEFT JOIN employer e ON e.id_user = v.id_user
	            WHERE v.id_user = {$id}
	            AND (v.status = 0 OR vs.status in (6,7))
	            ORDER BY v.id DESC";
        
        $res = Yii::app()->db->createCommand($sql)->queryAll();
        foreach ($res as $key => $val)
        {
        	$arIdies[] = $val['id'];
            $Termostat = new Termostat();
            $result['analytic'][$val['id']] = $Termostat->getTermostatCount($val['id']);

            if( !isset($result['vacancies_arch'][$val['id']]) ) 
            	$result['vacancies_arch'][$val['id']] = array_merge(
            		$val, 
            		array('isresp' => array($val['count'],0)
            	)
            );
            if( $val['isresp'] ) 
            	$result['vacancies_arch'][$val['id']]['isresp'][$val['isresp']-1]++;
            $result['vacancies_arch'][$val['id']]['link'] = '/admin/VacancyEdit/' . $val['id'];
        }

        $result['responses'] = array();

        if(sizeof($arIdies)){
        	$strId = implode(',', $arIdies);
            $sql = "SELECT v.id id_vac, vs.id_promo, 
            		vs.id id_resp, r.firstname, r.lastname, 
            		vs.isresponse, vs.status, r.id_user
                FROM empl_vacations v 
                LEFT JOIN vacation_stat vs ON vs.id_vac = v.id
                LEFT JOIN resume r ON r.id = vs.id_promo
                WHERE (v.id IN({$strId}))";
            $result['responses'] = Yii::app()->db->createCommand($sql)->queryAll();
            foreach ($result['responses'] as &$r)
            	if(!empty($r['firstname'])){
            		$r['profile'] = '/admin/PromoEdit/' . $r['id_user'];
            		$r['name'] = $r['firstname'] . ' ' . $r['lastname'];
            	}
            unset($r);
        }

    	return $result;
	}
	/*
	*
	*/
	public function getUser($id)
	{
		$result = Yii::app()->db->createCommand()
    			->select("u.id_user,r.meta_title, r.meta_h1, 
    				r.meta_description, r.index, u.login, 
    				u.passw, u.email, u.access_time, r.id, 
    				u.status, u.isblocked, u.ismoder,
    				r.comment, r.firstname,r.photo, r.lastname, 
    				DATE_FORMAT(r.birthday,'%d.%m.%Y') as birthday, 
    				r.ismed, r.ishasavto, r.isman, r.aboutme,
    				r.smart, r.card, r.cardPrommu, r.rate, r.rate_neg")
				->leftjoin('resume r', 'r.id_user=u.id_user')
    			->from('user u')
		    	->where('u.id_user=:id', array(':id'=>$id))
		    	->queryRow();

		if(!is_array($result))
		{
			Yii::app()->user->setFlash('danger', 'Внимание! Ошибка базы данных id_user='.$id);
			return ['error'=>1];
		}

		$result['src'] = Share::getPhoto($result['id_user'],2,$result['photo'],'small',$result['isman']);
		$result['years'] = date('Y') -  date('Y', strtotime($result['birthday']));
		$result['years'] .= ' ' . Share::endingYears($result['years']); // возраст
		//
        // справочник характиристики пользователя
        $sql = "SELECT d.id, d.id_par idpar, d.type, d.name 
        	FROM user_attr_dict d 
        	WHERE d.id_par IN(11,12,13,14,15,16,69) 
        	ORDER BY idpar, id";
        $arAppNames = Yii::app()->db->createCommand($sql)->queryAll();
        $arAppear = array(
        	11=>'hcolor',
        	12=>'hlen',
        	13=>'ycolor',
        	14=>'chest',
        	15=>'waist',
        	16=>'thigh',
        	69=>'edu'
        );
        $res = array();
        foreach ($arAppNames as $item)
        	$res[$item['id']] = $item['name'];
        $arAppNames = $res;
        //
        // языки словаря
        $sql = "SELECT d.id, d.name
                FROM user_attr_dict d 
                WHERE d.id_par = 40
                ORDER BY name";
        $arLangs = Yii::app()->db->createCommand($sql)->queryAll();
        $res = array();
        foreach ($arLangs as $item)
        	$res[$item['id']] = $item['name'];
        $arLangs = $res;
        //
        // свойства
		$attr = Yii::app()->db->createCommand()
			->select("*")
			->from('user_attribs')
			->where('id_us=:id', array(':id'=>$id))
			->queryAll();
		$arr_at = [];
		foreach($attr as $at) {
			if(!empty($at['key'])) {
				if(in_array($at['key'], $arAppear))
					$arr_at[$at['key']] = $arAppNames[$at['id_attr']];
				else
					$arr_at[$at['key']] = $at['val'];
			}
			elseif(array_key_exists($at['id_attr'], $arLangs)) {
				$arr_at['lang'][$at['id_attr']] = $arLangs[$at['id_attr']];
			}
		}
		$result['attr']=$arr_at;
		//
		// photo
		$result['photos'] = Yii::app()->db->createCommand()
			->select("p.id, p.photo, p.signature")
			->from('resume r')
			->leftjoin('user_photos p', 'p.id_promo = r.id')
			->where('p.id_user=:id', array(':id'=>$id))
			->queryAll();

		if(sizeof($result['photos']))
		{
			foreach($result['photos'] as $key => &$item)
			{
				$item['orig'] = Share::getPhoto($result['id_user'],2,$item['photo'],'big',$result['isman']);
				$item['photo'] = Share::getPhoto($result['id_user'],2,$item['photo'],'medium',$result['isman']);			
			}
			unset($item);
		}
		else{
			$result['photos'] = array();
		}
		//
        // должности, отработанные и желаемые
        $sql = "SELECT r.id, um.isshow, 
        		um.pay, um.pay_type pt,
        		um.pay_type, um.id_attr, 
        		um.mech, d1.name pname,
        		d.name val, d.id idpost
            FROM resume r
            INNER JOIN user_mech um ON um.id_us = r.id_user
            LEFT JOIN user_attr_dict d1 ON d1.id = um.id_attr
            INNER JOIN user_attr_dict d ON d.id = um.id_mech 
            WHERE r.id_user = {$id}
            ORDER BY um.isshow, val";
        $res = Yii::app()->db->createCommand($sql)->queryAll();

        foreach ($res as $key => $val)
        {
        	switch ($val['pay_type']) {
        		case 0: $res[$key]['pay_type'] ='руб. в час'; break;
        		case 1: $res[$key]['pay_type'] ='руб. в неделю'; break;
        		case 2: $res[$key]['pay_type'] ='руб. в месяц'; break;
        		case 3: $res[$key]['pay_type'] ='руб. за посещение'; break;
        	}
        } // end foreach
        $result['user_posts'] = $res;

				$result['posts'] = array();
				foreach($result['user_posts'] as $post){
					$result['posts'][$post['idpost']]['val'] = $post['val'];
					if(!$post['isshow']){
						$result['posts'][$post['idpost']]['pay'] = $post['pay']>0 
						? round($post['pay']) 
						: '';
	        	switch ($post['pt']) {
	        		case 0: $result['posts'][$post['idpost']]['pt'] ='Час'; break;
	        		case 1: $result['posts'][$post['idpost']]['pt'] ='Неделю'; break;
	        		case 2: $result['posts'][$post['idpost']]['pt'] ='Месяц'; break;
	        		case 3: $result['posts'][$post['idpost']]['pt'] ='Посещение'; break;
	        	}
					}
					if($post['isshow'])
						$result['posts'][$post['idpost']]['pname'] = $post['pname'];
				}
				//
        // read cities
        $sql = "SELECT ci.id_city id, ci.name, 
        	co.id_co, co.name coname, 
        	ci.ismetro, ci.region
            FROM user_city uc
            LEFT JOIN city ci ON uc.id_city = ci.id_city
            LEFT JOIN country co ON co.id_co = ci.id_co
            WHERE uc.id_user = {$id}";
        $res = Yii::app()->db->createCommand($sql)->queryAll();

        $result['user_cities'] = array();
        foreach ($res as $key => $val)
        {
            $cityPrint[$val['id']] = $val['name'];
            $result['user_cities'][$val['id']] = array(
            	'id' => $val['id'], 
            	'name' => $val['name'], 
            	'ismetro' => $val['ismetro'], 
            	'region' => $val['region']
            );
        }
        //
        // read metro
        $sql = "SELECT m.id, m.id_city idcity, m.name FROM user_metro um
                LEFT JOIN metro m ON um.id_metro = m.id
                WHERE um.id_us = {$id} ORDER BY name";
        $res = Yii::app()->db->createCommand($sql)->queryAll();
        foreach ($res as $key => $val):
            $metro[$val['id']] = array('idcity' => $val['idcity'], 'name' => $val['name']);
        endforeach;
        $result['user_metros'] = $metro;
        //
        // read week times
        $dayNames = array('Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб', 'Вс');
        $sql = "SELECT t.id_city idcity, t.wday, t.timeb, t.timee FROM user_wtime t WHERE t.id_us = {$id}";
        $wdays = array();
        $res = Yii::app()->db->createCommand($sql)->queryAll();
        foreach ($res as $key => $val):
            $val['wdayName'] = $dayNames[$val['wday']-1];
            $h = floor($val['timeb'] / 60);
            $m = $val['timeb'] - $h * 60;
            $val['timeb'] = sprintf('%d:%02d', $h, $m);
            $h = floor($val['timee'] / 60);
            $m = $val['timee'] - $h * 60;
            $val['timee'] = sprintf('%d:%02d', $h, $m);
            $wdays[$val['idcity']][$val['wday']] = $val;
        endforeach;
        $result['worktime'] = $wdays;
        $result['days'] = array(
        	1=>'ПН', 
        	2=>'ВТ',
        	3=>'СР', 
        	4=>'ЧВ', 
        	5=>'ПТ', 
        	6=>'СБ', 
        	7=>'ВС'
        );
        //
		// last jobs
        $jobFilter = Vacancy::getScopesCustom(Vacancy::$SCOPE_APPLIC_WORKING, 'vs');

        $sql = "SELECT v.id, v.title, 
        		DATE_FORMAT(v.crdate, '%d.%m.%Y') crdate, 
        		DATE_FORMAT(v.remdate, '%d.%m.%Y') remdate,
        		e.id_user idus, e.name
            FROM empl_vacations v
            INNER JOIN vacation_stat vs ON vs.id_vac = v.id 
            INNER JOIN employer e ON e.id_user = v.id_user
            WHERE vs.id_promo = {$result['id']} AND {$jobFilter}
            ORDER BY v.id DESC
            LIMIT 9";
        $res = Yii::app()->db->createCommand($sql)->queryAll();
        foreach ($res as $key => $job) {
        	$result['jobs'][$key] = $job;
			$result['jobs'][$key]['link'] = '/admin/site/VacancyEdit/' . $job['id'];
			$result['jobs'][$key]['empl'] = '/admin/site/EmplEdit/' . $job['idus'];
        }

        $sql = "SELECT COUNT(vs.id) cou
			FROM empl_vacations v
			INNER JOIN vacation_stat vs ON vs.id_vac = v.id 
			WHERE vs.id_promo = {$result['id']} AND {$jobFilter}";
        $res = Yii::app()->db->createCommand($sql)->queryScalar();
        $result['jobs_cnt'] = $res;
        //
        //	SEO
        //
		$arSeoParams = array(
			'firstname' => $result['firstname'],
			'lastname' => $result['lastname'],
			'cities' => $result['user_cities'],
			'posts' => $result['user_posts'],
			'isman' => $result['isman'],
			'years' => $result['years'],
			'education' => $result['attr']['edu'],
			'lang' => $result['attr']['lang']
		);
		$arSeo = Seo::getMetaForApp($arSeoParams);
		// устанавливаем title
		if(empty($result['meta_title']))
		  $result['meta_title'] = $arSeo['meta_title'];
		// устанавливаем description
		if(empty($result['meta_description']))
			$result['meta_description'] = $arSeo['meta_description'];

    /*
    // считываем опыт
    $sql = "SELECT d.id, d.type, d.name FROM user_attr_dict d WHERE d.id_par = 31 ORDER BY id";
    $result['expir'] = Yii::app()->db->createCommand($sql)->queryAll();
		*/
    return $result;
	}

    /**
     * Поиск по ID
     * @param $idus
     * @return $this
     */
    public function getById($idus)
    {
        $this->getDbCriteria()->mergeWith(array(
            'condition' => 'id_user = ' . $idus,
        ));
        return $this;
    }



    /**
     * Готовые условия для ручных запросов по пользователям
     * @param string $inName
     * @param string $alias
     * @return string
     */
    static public function getScopesCustom($inName, $alias = 'u')
    {
        // Если удаляем условия убивать и $SCOPE_XXXXXXX чтобы сразу выявить использование условия
        $aliasPlh = '{{alias}}';
        switch ( (int)$inName )
        {
           case self::$SCOPE_ACTIVE : $condition = 'isblocked = 0'; break;
           default : $condition = "";
        }

        return $condition ? str_replace($aliasPlh, $alias . '.', $aliasPlh . $condition) : '';
    }

	public function ChangeModer($id, $st)
	{
		Yii::app()->db->createCommand()
			->update('user', array(
				'ismoder' => $st,
			), 'id_user=:id_user', array(':id_user' => $id));

			Yii::app()->db->createCommand()
			->update('resume', array(
				'status' => $st,
			), 'id_user=:id_user', array(':id_user' => $id));

			Yii::app()->db->createCommand()
			->update('employer', array(
				'ismoder' => $st,
			), 'id_user=:id_user', array(':id_user' => $id));

			 if($st == 1){
            	file_get_contents("https://prommu.com/api.mailer/?id=$id&type=3&method=moder");
            	file_get_contents("https://prommu.com/api.mailer/?id=$id&type=2&method=moder");

            }
	}

	public function exportPromoCSV()
	{
		$csv_file = ''; // создаем переменную, в которую записываем строки
		$result = Yii::app()->db->createCommand()
			->select("u.id_user, u.login, u.passw, u.email, u.access_time, u.status, u.isblocked, u.ismoder,
    			r.firstname, r.lastname, DATE_FORMAT(r.birthday,'%d.%m.%Y') as birthday, r.ismed, r.ishasavto,
    			r.isman")
			->leftjoin('resume r', 'r.id_user=u.id_user')
			->from('user u')
			->where('u.status=:st', array(':st'=>2))
			->order('id_user desc')
			->queryAll();

		$data = [];
		$i=0;
		foreach($result as $res) {
			$attr = Yii::app()->db->createCommand()
				->select("*")
				->from('user_attribs')
				->where('id_us=:id', array(':id' => $res['id_user']))
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
			'</td><td style="color:red; background:#E0E0E0">'.htmlentities(iconv("utf-8", "windows-1251", 'Статус'),ENT_QUOTES, "cp1251").
			'</td><td style="color:red; background:#E0E0E0">'.htmlentities(iconv("utf-8", "windows-1251", 'Фамилия'),ENT_QUOTES, "cp1251").
			'</td><td style="color:red; background:#E0E0E0">'.htmlentities(iconv("utf-8", "windows-1251", 'Имя'),ENT_QUOTES, "cp1251").
			'</td><td style="color:red; background:#E0E0E0">'.htmlentities(iconv("utf-8", "windows-1251", 'Дата рождения'),ENT_QUOTES, "cp1251").
			'</td><td style="color:red; background:#E0E0E0">'.htmlentities(iconv("utf-8", "windows-1251", 'Медкнижка'),ENT_QUOTES, "cp1251").
			'</td><td style="color:red; background:#E0E0E0">'.htmlentities(iconv("utf-8", "windows-1251", 'Автомобиль'),ENT_QUOTES, "cp1251").
			'</td><td style="color:red; background:#E0E0E0">'.htmlentities(iconv("utf-8", "windows-1251", 'Пол'),ENT_QUOTES, "cp1251").
			'</td><td style="color:red; background:#E0E0E0">'.htmlentities(iconv("utf-8", "windows-1251", 'Телефон'),ENT_QUOTES, "cp1251").
			'</td><td style="color:red; background:#E0E0E0">'.htmlentities(iconv("utf-8", "windows-1251", 'Доп. Телефон'),ENT_QUOTES, "cp1251").
			'</td><td style="color:red; background:#E0E0E0">'.htmlentities(iconv("utf-8", "windows-1251", 'Email'),ENT_QUOTES, "cp1251").
			'</td><td style="color:red; background:#E0E0E0">'.htmlentities(iconv("utf-8", "windows-1251", 'Skype'),ENT_QUOTES, "cp1251").
			'</td><td style="color:red; background:#E0E0E0">'.htmlentities(iconv("utf-8", "windows-1251", '>Место рождения)'),ENT_QUOTES, "cp1251").
			'</td><td style="color:red; background:#E0E0E0">'.htmlentities(iconv("utf-8", "windows-1251", 'Страница ВКОНТАКТЕ (сылка)'),ENT_QUOTES, "cp1251").
			'</td><td style="color:red; background:#E0E0E0">'.htmlentities(iconv("utf-8", "windows-1251", 'Страница Facebook (ссылка)'),ENT_QUOTES, "cp1251").
			'</td><td style="color:red; background:#E0E0E0">'.htmlentities(iconv("utf-8", "windows-1251", 'Viber'),ENT_QUOTES, "cp1251").
			'</td><td style="color:red; background:#E0E0E0">'.htmlentities(iconv("utf-8", "windows-1251", 'ICQ'),ENT_QUOTES, "cp1251").
			'</td><td style="color:red; background:#E0E0E0">'.htmlentities(iconv("utf-8", "windows-1251", 'Другое'),ENT_QUOTES, "cp1251").
			'</td></tr>';

		$block_status = ["полностью активен", "заблокирован", "ожидает активации", "активирован", "остановлен к показу"];

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
				'</td><td>'.$b.htmlentities(iconv("utf-8", "windows-1251", $block_status[$row["isblocked"]]),ENT_QUOTES, "cp1251").$b_end.
				'</td><td>'.$b.htmlentities(iconv("utf-8", "windows-1251", $row["lastname"]),ENT_QUOTES, "cp1251").$b_end.
				'</td><td>'.$b.htmlentities(iconv("utf-8", "windows-1251", $row["firstname"]),ENT_QUOTES, "cp1251").$b_end.
				'</td><td>'.$b.$row["birthday"].$b_end.
				'</td><td>'.$b.$ismed.$b_end.
				'</td><td>'.$b.$ishasavto.$b_end.
				'</td><td>'.$b.htmlentities(iconv("utf-8", "windows-1251", $isman),ENT_QUOTES, "cp1251").$b_end.
				'</td><td>'.$b.$row['attr']['mob'].$b_end.
				'</td><td>'.$b.$row['attr']['addmob'].$b_end.
				'</td><td>'.$b.$row['attr']['email'].$b_end.
				'</td><td>'.$b.$row['attr']['skype'].$b_end.
				'</td><td>'.$b.htmlentities(iconv("utf-8", "windows-1251", $row["bornplace"]),ENT_QUOTES, "cp1251").$b_end.
				'</td><td>'.$b.$row['attr']['vk'].$b_end.
				'</td><td>'.$b.$row['attr']['fb'].$b_end.
				'</td><td>'.$b.$row['attr']['viber'].$b_end.
				'</td><td>'.$b.$row['attr']['icq'].$b_end.
				'</td><td>'.$b.htmlentities(iconv("utf-8", "windows-1251", $row["attr"]["custcont"]),ENT_QUOTES, "cp1251").$b_end.
				'</td></tr>';
		}

		$csv_file .='</table>';
		$file_name = $_SERVER['DOCUMENT_ROOT'].'/content/promo_exp.xls'; // название файла
		$file = fopen($file_name,"w"); // открываем файл для записи, если его нет, то создаем его в текущей папке, где расположен скрипт

		fwrite($file,trim($csv_file)); // записываем в файл строки
		fclose($file); // закрываем файл

		header('Pragma: no-cache');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Content-Description: File Transfer');
		header('Content-Disposition: attachment; filename=cards_exp.xls');
		header('Content-transfer-encoding: binary');
		header('Content-Type: text/html; charset=windows-1251');
		header('Content-Type: application/x-unknown');
		header('P3P: CP="NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM"');
		
		readfile($file_name); // считываем файл

	}
  /*
   * @param $search - mixed(string | number) - поисковой ввод
   * @param $limit - number - ограничение по кол-ву
   * @param $arWithout - array - массив id_user, которые нужно исключить
   */
	public static function searchUsers($search, $limit=20, $arWithout=[])
  {
    $arRes = [];
    if( is_numeric($search) )
    {
      $query = Yii::app()->db->createCommand()
        ->selectDistinct("u.id_user,
          u.email,
          u.status, 
          r.isman,
          CONCAT(r.firstname,' ',r.lastname) app_name,
          r.photo app_photo,
          e.name emp_name,    
          e.logo emp_photo")
        ->from('user u')
        ->leftjoin('resume r','r.id_user=u.id_user')
        ->leftjoin('employer e','e.id_user=u.id_user')
        ->where([
          'and',
          'u.id_user='.$search,
          ['not in','u.id_user',$arWithout]
        ])
        ->limit($limit)
        ->queryAll();
    }
    else
    {
      $arQ = explode(' ', $search);
      $n=count($arQ);
      $arWhere = [];

      if($n==2)
      {
        $arWhere[] = "((r.lastname LIKE '%" . $arQ[0] . "%') AND (r.firstname LIKE '%" . $arQ[1] . "%'))";
        $arWhere[] = "((r.lastname LIKE '%" . $arQ[1] . "%') AND (r.firstname LIKE '%" . $arQ[0] . "%'))";
        $arWhere[] = "((e.name LIKE '%" . $arQ[0] . "%') AND (e.name LIKE '%" . $arQ[1] . "%'))";
        $arWhere[] = "((e.name LIKE '%" . $arQ[1] . "%') AND (e.name LIKE '%" . $arQ[0] . "%'))";
        $arWhere[] = "((e.lastname LIKE '%" . $arQ[0] . "%') AND (e.firstname LIKE '%" . $arQ[1] . "%'))";
        $arWhere[] = "((e.lastname LIKE '%" . $arQ[1] . "%') AND (e.firstname LIKE '%" . $arQ[0] . "%'))";
      }
      elseif($n>2)
      {
        $arWhere[] = "(r.lastname LIKE '%" . $search . "%')";
        $arWhere[] = "(e.name LIKE '%" . $search . "%')";
        $arWhere[] = "(e.lastname LIKE '%" . $search . "%')";
        $arWhere[] = "(r.firstname LIKE '%" . $search . "%')";
        $arWhere[] = "(e.firstname LIKE '%" . $search . "%')";
      }
      else
      {
        for($i=0; $i<$n; $i++)
        {
          $arWhere[] = "(r.lastname LIKE '%" . $arQ[$i] . "%')";
          $arWhere[] = "(e.name LIKE '%" . $arQ[$i] . "%')";
          $arWhere[] = "(e.lastname LIKE '%" . $arQ[$i] . "%')";
          $arWhere[] = "(r.firstname LIKE '%" . $arQ[$i] . "%')";
          $arWhere[] = "(e.firstname LIKE '%" . $arQ[$i] . "%')";
        }
      }

      $sql = "SELECT DISTINCT u.id_user,
                    u.email,
                    u.status, 
                    r.isman,
                    CONCAT(r.firstname,' ',r.lastname) app_name,
                    r.photo app_photo,
                    e.name emp_name,    
                    e.logo emp_photo
                FROM user u
                LEFT JOIN resume r ON r.id_user=u.id_user
                LEFT JOIN employer e ON e.id_user=u.id_user
                WHERE (" . implode(' || ', $arWhere) . ")"
                  . (count($arWithout) ? ' AND u.id_user NOT IN (' . implode(',',$arWithout) . ')' : '') . "
                ORDER BY CASE 
                WHEN r.lastname LIKE '{$search}' THEN 0
                WHEN e.name LIKE '{$search}' THEN 1
                WHEN e.lastname LIKE '{$search}' THEN 2
                WHEN r.firstname LIKE '{$search}' THEN 3
                WHEN e.firstname LIKE '{$search}' THEN 4
                WHEN r.lastname LIKE '{$search}%' THEN 5
                WHEN e.name LIKE '{$search}%' THEN 6
                WHEN e.lastname LIKE '{$search}%' THEN 7
                WHEN r.firstname LIKE '{$search}%' THEN 8
                WHEN e.firstname LIKE '{$search}%' THEN 9
                WHEN r.lastname LIKE '%{$search}%' THEN 10
                WHEN e.lastname LIKE '%{$search}%' THEN 11
                WHEN e.name LIKE '%{$search}%' THEN 12
                WHEN r.firstname LIKE '%{$search}%' THEN 13
                WHEN e.firstname LIKE '%{$search}%' THEN 14
                ELSE 15 END
                LIMIT {$limit}";
      $query = Yii::app()->db->createCommand($sql)->queryAll();
    }

    if(count($query))
    {
      foreach ($query as $i => $v)
      {
        if($v['status']==2)
        {
          $arRes[$i] = array(
            'id' => $v['id_user'],
            'email' => $v['email'],
            'status' => $v['status'],
            'name' => $v['app_name'],
            'src' => Share::getPhoto($v['id_user'],2,$v['app_photo'],'small',$v['isman']),
            'profile' => MainConfig::$PAGE_PROFILE_COMMON . DS . $v['id_user']
          );
        }
        if($v['status']==3)
        {
          $arRes[$i] = array(
            'id' => $v['id_user'],
            'email' => $v['email'],
            'status' => $v['status'],
            'name' => $v['emp_name'],
            'src' => Share::getPhoto($v['id_user'],3,$v['emp_photo']),
            'profile' => MainConfig::$PAGE_PROFILE_COMMON . DS . $v['id_user']
          );
        }
        if(isset($arRes[$i]) && empty($arRes[$i]['name']))
        {
          $arRes[$i]['name'] = '(безымянный)';
        }
        if(in_array($v['id_user'], [1766,2054]))
        {
          $arRes[$i]['name'] = 'Администрация Prommu';
          $arRes[$i]['src'] = '/theme/pic/prommu-adm.jpg';
        }
      }
    }
    return $arRes;
  }
  /**
   * Проверяем ИНН самозанятого
   */
  public function checkSelfEmployed(&$arRes)
  {
    $arProxy = array(
      '87.225.90.97:3128',
      '5.140.233.65:60437',
      '46.0.126.134:3128',
      '217.107.197.177:30436',
      '87.103.234.116:3128',
      '78.31.73.222:8080',
      '1.10.185.8:42106',
      '1.20.101.124:55264',
      '1.20.99.89:31799',
      '176.117.233.184:8080',
      '94.242.55.108:10010',
      '46.63.162.171:8080',
      '31.15.87.197:53281',
      '79.98.212.14:3128',
      '91.214.70.99:3128'
    );
    $inn = filter_var(Yii::app()->getRequest()->getParam('inn'), FILTER_SANITIZE_NUMBER_INT);
    $date = date('Y-m-d');
    $sData = "{\n \"inn\": \"$inn\",\n\"requestDate\": \"$date\"\n}";
    $options = array(
      CURLOPT_USERAGENT => "Mozilla/4.0 (Windows; U; Windows NT 5.0; En; rv:1.8.0.2) Gecko/20070306 Firefox/1.0.0.4",
      CURLINFO_HEADER_OUT => true,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_TIMEOUT => 20,
      CURLOPT_CONNECTTIMEOUT => 20,
      CURLOPT_POST => true,
      CURLOPT_POSTFIELDS => $sData,
      CURLOPT_HTTPPROXYTUNNEL => true,
      CURLOPT_SSL_VERIFYPEER => false,
      CURLOPT_SSL_VERIFYHOST => false,
      CURLOPT_HTTPHEADER => array(
        "Accept: */*",
        "Cache-Control: no-cache",
        "Connection: keep-alive",
        "Content-Type: application/json",
        "Host: statusnpd.nalog.ru",
        "cache-control: no-cache"
      )
    );

    do {
      $options[CURLOPT_PROXY] = $arProxy[array_rand($arProxy)];
      $ch = curl_init(MainConfig::$RESOURCE_SELF_EMPLOYED);
      curl_setopt_array($ch, $options);
      $arRes['response'] = curl_exec($ch);
      $arRes['response'] = json_decode($arRes['response']);
      $error = curl_error($ch);
      $arRes['error'] = ($error ?: false);
      //$arRes['headers'] = curl_getinfo($ch);
      curl_close($ch);
    }while($arRes['error']!=false);
  }
  /**
   * Проверяем ИНН самозанятого
   */
  public function checkMultiSelfEmployed(&$arRes)
  {
    $arProxy = array(
      '87.225.90.97:3128',
      '5.140.233.65:60437',
      '46.0.126.134:3128',
      '217.107.197.177:30436',
      '87.103.234.116:3128',
      '78.31.73.222:8080',
      '1.10.185.8:42106',
      '1.20.101.124:55264',
      '1.20.99.89:31799',
      '176.117.233.184:8080',
      '94.242.55.108:10010',
      '46.63.162.171:8080',
      '31.15.87.197:53281',
      '79.98.212.14:3128',
      '91.214.70.99:3128'
    );
    $arInn = Yii::app()->getRequest()->getParam('inn');
    $date = date('Y-m-d');
    $arCurlParams = [];

    if(!count($arInn))
      return;

    // собираем параметры
    foreach ($arInn as $inn)
    {
      $arCurlParams[] = array(
        CURLOPT_USERAGENT => "Mozilla/4.0 (Windows; U; Windows NT 5.0; En; rv:1.8.0.2) Gecko/20070306 Firefox/1.0.0.4",
        CURLINFO_HEADER_OUT => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 20,
        CURLOPT_CONNECTTIMEOUT => 20,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => "{\n \"inn\": \"$inn\",\n\"requestDate\": \"$date\"\n}",
        CURLOPT_HTTPPROXYTUNNEL => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_HTTPHEADER => array(
          "Accept: */*",
          "Cache-Control: no-cache",
          "Connection: keep-alive",
          "Content-Type: application/json",
          "Host: statusnpd.nalog.ru",
          "cache-control: no-cache"
        )
      );
    }
    // запускаем потоки
    foreach ($arInn as $key => $inn)
    {
      $thread = new MultiThread($inn, $arCurlParams[$key], $arProxy);
      $thread->start() && $thread->join();
      $arRes['result'][] = $thread->result;
    }
  }
}